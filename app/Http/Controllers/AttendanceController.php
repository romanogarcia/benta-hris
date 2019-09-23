<?php

namespace App\Http\Controllers;

use App\Attendance;
use App\Employee;
use App\Tax;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use DataTables;
use DateTime;
use Auth;
use Session;
use Excel;
class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $employee_id = Auth::user()->employee_id;
        $records = Attendance::paginate(10);
        $current_employee = Employee::findOrFail($employee_id);
		$employees = [];
		if(Auth::user()->employee->account_type == 1){
        $employees = Employee::orderBy('first_name', 'ASC')->get();
		}	
        $filters = Session::get("attendfilters");
        $from_attandance_list = Session::get("from_attandance_list");

        return view('attendance.list',
            [
                'record'            =>  $records, 
                'employees'         =>  $employees,
                'current_employee'  =>  $current_employee,
                'filterdata'        =>  $filters,
                'from_attandance_list' => $from_attandance_list
            ]
        );
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexCard(Request $request)
    {
        $perpage = $request->query('perpage',10);
       $rows = Attendance::where('at_date',date('Y-m-d'))->paginate(1);
        $entries = "<p>Showing ".$rows->firstItem()." to ".$rows->lastItem()." of ".$rows->total()." entries </p>";
        return view('attendance.attendancecard',
            [
                'rows' => $rows,
                'entries' => $entries,
                'total' => $rows->total()
            ]
        );
    }
    
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
        $employees = Employee::orderBy('first_name', 'ASC')->get();
        return view('attendance.create', ['employees' => $employees]);
    }

    /**
     * handles the uploaded csv file
     */
    public function uploadCsv(Request $request)
    {
        $this->validate($request,[
            'file'     => 'required|mimes:xlsx'          
        ]);

        $file                   = $request->file('file');
        $file_path              = $file->getRealPath();
        $file_extension         = $file->getClientOriginalExtension();

        try{
            $data   = Excel::load($file_path)->get();
            // dd($data->toArray());
            foreach($data->toArray() as $key => $row){
                $employee_number    = trim($row['ac_no.']);
                $employee_number    = number_format($employee_number, 0, ',', '');
                $get_employee       = Employee::where('employee_number', $employee_number)
                ->select(array('id'))
                ->first();

                // dd($get_employee);
                if($employee_number != '' && $get_employee){
                    $employee_id        = $get_employee->id;
                    $time               = trim($row['time']);
                    $time               = strtotime($time);
                    $date               = date('Y-m-d', $time);
                    $exact_time         = date('H:ia Y-m-d', $time);
                    $state              = trim($row['state']);
                    $name               = trim($row['name']);
                    $check_in           = 'C/In';
                    $check_out          = 'C/Out';
                    $overtime_in        = 'OverTime In';
                    $overtime_out       = 'OverTime Out';
                    
                    $filters                = [];
                    $filters['employee_id'] = $employee_id;

                    $attendance = Attendance::where($filters)
                        // ->where(function ($q) use ($date){
                        //     $q->where('time_out', 'LIKE', "%$date%")
                        //     ->orWhere('time_in', 'LIKE', "%$date%");
                        // })
                        ->where('at_date', $date)
                        ->first();
                    
                    if(!$attendance){
                        $attendance                 = new Attendance();
                        $attendance->employee_id    = $employee_id;
                        $attendance->name           = $name;
                        $attendance->at_date        = $date;
                    }

                    if($state == $check_in)
                        $attendance->time_in        = $exact_time;
                    else if($state == $check_out)
                        $attendance->time_out       = $exact_time;

                    if(date('h', strtotime($attendance->time_in)) > 11)
                        $night_shift                = true;
                    else 
                        $night_shift                = false;

                    if($attendance->time_out != '-' && $attendance->time_out != null && $attendance->time_in != '-' && $attendance->time_in != null){

                        $total_time_in       = $attendance->time_in[0].$attendance->time_in[1];
                        $total_time_in      .= ':'.$attendance->time_in[3].$attendance->time_in[4];
                        $total_time_in      .= ':00';
                        $total_time_in_str   = strtotime($total_time_in);

                        $total_time_out      = $attendance->time_out[0].$attendance->time_out[1];
                        $total_time_out     .= ':'.$attendance->time_out[3].$attendance->time_out[4];
                        $total_time_out     .= ':00';
                        $total_time_out_str  = strtotime($total_time_out);

                        $total               = round(abs($total_time_out_str - $total_time_in_str) / 3600,2);
                        $attendance->total          = $total;
                    }
                    $attendance->night_shift        = $night_shift;
                    $attendance->save();
                    
                }
            }

            return redirect(route('attendance.index'))->with('success','Upload successfull!'); 
        }catch(\Exception $e){
            // dd("Failed, Something went wrong in file you uploaded. Please check the MS Excel version that you want to upload. The System accepts MS-Excel Version 2007 and up");
            return redirect()->back()->with('error','Failed, Something went wrong in file you uploaded. Please check the MS Excel version that you want to upload. The System accepts MS-Excel Version 2007 and up'); 
        }
        
    }

    public function readCsv($file) 
    {
      date_default_timezone_set('Asia/Manila');
      $delimeter = $this->detectDelimiter($file);
			$fileread = fopen($file, "r");
			$datas = array();
			while (($getData = fgetcsv($fileread, 10000, $delimeter)) !== FALSE) {
				$data = array (
					
					'id' => $getData[0],
					'date' => $getData[1],
					'name' => $getData[4],
					'for' => $this->detectMethod($getData[5])
					
				);
				$datas[] = $data;
			}
      fclose($fileread);
      return $datas;
    }

    private function detectDelimiter($csvFile)
    {
      $delimiters = array(
          ';' => 0,
          ',' => 0,
          "\t" => 0,
          "|" => 0
      );
      $handle = fopen($csvFile,"r");
      $firstLine = fgets($handle);
      fclose($handle);
      foreach ($delimiters as $delimiter => &$count) {
          $count = count(str_getcsv($firstLine, $delimiter));
      }
      return array_search(max($delimiters), $delimiters);
    }

    private function detectMethod($method) 
    {
            if($method == 'I') {
                return 'timein';
            } else if($method == 'o') {
                return 'breakin';
            } else if($method == 'O') {
                return 'timeout';
            } else if($method == 'i') {
                return 'breakout';
            }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request);
        date_default_timezone_set('Asia/Manila');
        $this->validate($request,[
            'date'     => 'required',
            'name'     => 'required', //'name'     => 'required|regex:/[A-Za-z\s\.\-]{5,50}$/', 
            'time_in'  => 'required'
        ]);

        $employee_id = $request->name;
        $get_employee = Employee::findOrFail($employee_id);
        $employee_name = $get_employee->first_name;
        $employee_name .= ' '. $get_employee->last_name;
        if(\Carbon\Carbon::createFromFormat(get_date_format(), $request->date)->format('Y-m-d') == date('Y-m-d')){
            return redirect()->back()->with('error','This request is not allowed!'); 
        }
        
        $current_timeout = $request->time_out == null ?  date('H:i'): $request->time_out;
        
        $timein  = $request->time_in . " " . \Carbon\Carbon::createFromFormat(get_date_format(), $request->date)->format('Y-m-d');
        $timeout = $current_timeout . " " . \Carbon\Carbon::createFromFormat(get_date_format(), $request->date)->format('Y-m-d');
        $night   = $request->night_shift == 'on' ? true : false;
        //echo self::compute($timein, $timeout, $night);die;
        $record           = new Attendance;
        $record->at_date     = \Carbon\Carbon::createFromFormat(get_date_format(), $request->date)->format('Y-m-d');
        $record->employee_id = $employee_id; //Auth::user()->id;
        $record->name     = ucwords($employee_name);
        $record->time_in  = $request->time_in ?? "-";
        $record->time_out = $request->time_out ?? "-";
        $record->total = $request->time_out == null ? 0:self::compute($timein, $timeout, $night);
        $record->night_shift = $night;
        $record->save(); 

        return redirect('attendance')->with('success','Record added successfully!'); 
    }

    public function timeIn(Request $request)
    {
        /*if(Attendance::where(['date'=>date('Y-m-d'),'employee_id'=>Auth::user()->id])  
        ->first() != null) {
            return redirect()->back()->with('error','Your time stamp is already active.'); 
        }*/
        date_default_timezone_set('Asia/Manila');
        $night   = strtotime(date('H:i')) > strtotime(date('11:59')) ? true : false;
        $timein_real = date('H:i');
        $record = new Attendance;
        $record->at_date = date('Y-m-d');
        $record->employee_id = Auth::user()->employee_id;
        $record->name     = ucwords(Auth::user()->name);
        $record->time_in  = $timein_real;
        $record->time_out = '-';
        $record->total = 0.0;
        $record->night_shift = $night;
        $record->save();
        Session::put('attendance_id',$record->id);

        Session::flash("flash_attendance", array('employee_id' => $record->employee_id, 'date' => $record->date));
        
        return redirect(route('attendance.index'))->with('success','Time-in successfully! at '.$timein_real.' '.date('Y-m-d'));
    }

    public function timeOut(Request $request)
    {
      date_default_timezone_set('Asia/Manila');
      $timeout = date('H:i Y-m-d');
	
      	$record = Attendance::where(['id'=>$request->att_id])->get();
        if($record->count() > 0 && $record[0]->time_out != '-'){
            return redirect()->back()->with('error','This request is not allowed. You may not have been time-in for this day or you are trying multiple request.');
        }
		else if($record->count()==0){
			return redirect()->back()->with('error','This request is not allowed. You may not have any current time-in request.');
		}
      $timeou_real = date('H:i');
	  $record = Attendance::find($request->att_id);	
      $record->time_out = $timeou_real;
      $record->state_status = "Approved";
	  $record->approved_by     = ucwords(Auth::user()->employee->first_name).' '.ucwords(Auth::user()->employee->last_name);
      $record->approved_date = date('Y-m-d');
      $record->approved_time = date('H:i');
      $current_timein = $record->time_in.' '.$record->at_date;
      $record->total = self::compute($current_timein, $timeout, false);
      $record->save();

      Session::flash("flash_attendance", array('employee_id' => $record->employee_id, 'date' => $record->date));
      
      return redirect(route('attendance.index'))->with('success','Time-out successfully! at '.$timeou_real.' '.date('Y-m-d'));
    }

    /**
     * Compute the interval hours between two input times
     * @param datetime
     * @return double
     */
    private static function compute($timein, $timeout, $night)
    {
        $a = DateTime::createFromFormat('H:i Y-m-d', $timein);
        if($night) {
            $b = DateTime::createFromFormat('H:i Y-m-d', $timeout)->modify('+1 day');
        } else {
            $b = DateTime::createFromFormat('H:i Y-m-d', $timeout);
        }
        $interval = $a->diff($b);
        $hours    = ($interval->days * 24) + $interval->h
                    + ($interval->i / 60) + ($interval->s / 3600);
            /*if($hours >= 8) {
                $total = $hours - 1;
            } else {*/
                $total = $hours;
            //}
        return $total;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function show(Attendance $attendance)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function edit(Attendance $attendance)
    {
        $employees = Employee::orderBy('first_name', 'ASC')->get();
        $records = Attendance::findOrFail($attendance->id);

        $filters = Session::get("attendfilters");
		$user_id = auth()->user()->id;
		$state_status = array('Pending','Approved','Rescheduled','Declined');
        if(is_array($filters)){
          Session::flash("attendfilters", $filters);
        }
        Session::flash("from_attandance_list", 1);

        return view('attendance.edit', ['record' => $records, 'employees' => $employees,'user_id'=>$user_id,'state_status'=>$state_status]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Attendance $attendance)
    {
        $this->validate($request,[
            'date'     => 'required',
            'name'     => 'required', //'required|regex:/[A-Za-z\s\.\-]{5,50}$/',
            'time_in'  => 'required',
			'state_status' => 'required',
        ]);

        $current_timeout = $request->time_out == null ?  date('H:i'): $request->time_out;

        $timein  = $request->time_in . " " . \Carbon\Carbon::createFromFormat(get_date_format(), $request->date)->format('Y-m-d');
        $timeout = $current_timeout . " " . \Carbon\Carbon::createFromFormat(get_date_format(), $request->date)->format('Y-m-d');
        $night   = $request->night_shift == 'on' ? true : false;
        
        $total = $request->time_out == null ? 0 : self::compute($timein, $timeout, $night);

        $employee_id = $request->name;
        $get_employee = Employee::findOrFail($employee_id);

        $record = Attendance::find($attendance->id);
        $record->at_date     =  \Carbon\Carbon::createFromFormat(get_date_format(), $request->date)->format('Y-m-d');
        $record->employee_id = $employee_id; //Auth::user()->id;
        $record->name     = $get_employee->first_name.' '.$get_employee->last_name;
        $record->time_in  = $request->time_in ?? "-";
        $record->time_out = $request->time_out ?? "-";
        $record->total = $total;
        $record->night_shift = $night;
		if($request->state_status == 'Approved'){
            $get_current_employee    = Employee::where('user_id', auth()->user()->id)->first();
            $record->approved_by     = ucwords($get_current_employee->first_name).' '.ucwords($get_current_employee->last_name);
            $record->approved_date   = date("Y-m-d");
            $record->approved_time   = date("H:i:s");
        }else{
            $record->approved_by     = null;
            $record->approved_date   = null;   
        }
		$record->state_status = $request->state_status;
        if($record->save()){
            Session::flash('attendance_updated', $attendance->id); 
            return redirect(route('attendance.index'))->with('success','Record updated successfully!'); 
        }
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function destroy(Attendance $attendance)
    {
        if(Attendance::destroy($attendance->id)){
            return redirect()->back()->with('success','Record deleted successfully!');
        } else {
            return redirect()->back()->with('error','Request Failed!');
        }
        
    }

    public function dailyTime()
    {
        return view('attendance.dailytime');
    }
    
    public function processDTR(Request $request)
    {
        date_default_timezone_set('Asia/Manila');
        
        $method = $request->method;
        if($method == 'timein') {
            $timein = date('H:i');

            $record = new Attendance;
            $record->at_date = date('Y-m-d');
            $record->employee_id = $request->employeeID;
            $record->name = $request->employeeID;
            $record->time_in = $timein;
            $record->time_out = "-";
            $record->total = "0";
            $record->night_shift = false;
            if($record->save()){
                $data = array (
                'error'=>'',
                'message'=> 'Hello '. $request->employeeID . ' You have successfully ' . $request->method,
                'employeeID' => $request->employeeID,
                'time'=>$timein
                );
            }
        }
        return json_encode($data);
    
    }

    public function searchIndex(Request $request)
    {
        // $employee_id = Auth::user()->employee_id;

        $employee_id = $request->employee_id;
        $date_range = $request->date_range_pick;
        $status     = $request->status;
		if(Auth::user()->employee->account_type == 0){
			if(!empty($employee_id) && ($employee_id==Auth::user()->employee->id)){
				$employee_id = Auth::user()->employee->id;
			}
			else if(!empty($employee_id) && ($employee_id != Auth::user()->employee->id)){
				$employee_id = -1;
			}
			else{
				$employee_id = Auth::user()->employee->id;
			}
        }

        $postbtn = $request->postbtn;
        $from_attandance_list = $request->from_attandance_list;
        
        $select_qry = array(
            'employees.first_name',
            'employees.last_name',
            'attendances.id as r_id',
            'attendances.time_in',
            'attendances.time_out',
            'attendances.total',
            'attendances.night_shift',
            'attendances.updated_at',
            'attendances.at_date',
			'attendances.state_status',
            'attendances.approved_by',
            'attendances.approved_date',
            'attendances.approved_time',
        );

        $filters = [];

        if($employee_id != ''){
            $filters['employee_id'] = $employee_id;
        }
        if($status != ''){
            if($status=='Pending'){
                $status = Null;
            }
            $filters['state_status'] = $status;
        }
        if($date_range != ''){
            $filters['date_range'] = $date_range;
        }


        if($postbtn)
        {
            Session::put("attendfilters", $filters);
        }elseif($from_attandance_list){
          Session::flash("attendfilters", $filters);
        }else
        {
            Session::forget("attendfilters");
        }

        if(!empty($date_range)){
            $date_array  = explode(" - ",$date_range);
            $from = date('Y-m-d',strtotime($date_array[0]));
            $to = date('Y-m-d',strtotime($date_array[1]));

                $data = Attendance::where($filters)
                ->select($select_qry)
                ->join('employees','employees.id','=','attendances.employee_id')
                ->whereRaw("attendances.at_date >= ? AND attendances.at_date <=?",[$from.' 00:00:00',$to.' 23:59:59'])
                ->orderBy('attendances.at_date','desc')
                ->get();
            
        }else{
                $data = Attendance::where($filters)
                ->select($select_qry)
                ->join('employees','employees.id','=','attendances.employee_id')
                ->orderBy('attendances.at_date','desc')
                ->get(); 
            
        }

        

        $data_tables = Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('id', function($row){
				if((check_permission(Auth::user()->Employee->department_id,"Attendance","full")) || (check_permission(Auth::user()->Employee->department_id,"Attendance","Edit"))){
                
                $url_edit = route('attendance.edit', [$row->r_id]);
                $response = '<a href="'.$url_edit.'">'.$row->r_id.'</a>';
                return $response;
				}
				else{
					return $row->r_id;
				}
            })
            ->addIndexColumn()
            ->addColumn('at_date', function($row){
                $label      = date(get_date_format(), strtotime($row->at_date));
                $data_sort  = date('Y-m-d', strtotime($row->at_date));
                $response   = '<label data-sort="'.$data_sort.'">'.$label.'</label>';
                if($row->night_shift == 1){
                    $response .= ' <small><span class="badge badge-dark">night</span></small>';
                }

                return $response;
            })
            ->addIndexColumn()
            ->addColumn('name', function($row){
                return ucwords($row->first_name).' '.ucwords($row->last_name);
            })
			->addIndexColumn()
            ->addColumn('state_status', function($row){
                if($row->state_status == 'Pending')
					$lbl = '<label class="badge badge-warning">'.$row->state_status.'</label>';
                elseif(empty($row->state_status))
                    $lbl = '<label class="badge badge-warning">Pending</label>';
                elseif($row->state_status == 'Approved')
                    $lbl = '<label class="badge badge-success">'.$row->state_status.'</label>';
                elseif($row->state_status == 'Declined')
                    $lbl = '<label class="badge badge-danger">'.$row->state_status.'</label>';
                elseif($row->state_status == 'Rescheduled')
                    $lbl = '<label class="badge badge-info">'.$row->state_status.'</label>';
                elseif($row->state_status == 'Cancel')
                    $lbl = '<label class="badge badge-danger">'.$row->state_status.'</label>';
                return $lbl;
            })
			->addIndexColumn()
            ->addColumn('approved_by', function($row){
                $appr = '';
                if($row->approved_by == '' && !empty($row->state_status)){
                    $appr.= '<span class="text-warning"><i>'.$row->state_status.'</i></span>';
				}
				elseif($row->approved_by == '' && empty($row->state_status)){
					$appr.= '<span class="text-warning"><i>Pending</i></span>';
				}
				else{
                    $appr.= '<span class="text-success"><i>'.ucwords($row->approved_by).'</i></span>';
                    $appr.= '<br>';
					if($row->approved_date!='' && $row->approved_date!=null){
                        $approved_date      = date(get_date_format(), strtotime($row->approved_date));
                        $approved_date_sort = date('Y-m-d', strtotime($row->approved_date));
                        $appr.= '<span data-sort="'.$approved_date_sort.'" class="text-muted"><i>'.$approved_date.'</i></span>';
					}	
					if($row->approved_time!='' && $row->approved_time!=null){
						$appr.= '<br>';
						$appr.= '<span class="text-muted"><i>'. $row->approved_time.'</i></span>';
					}
                }   
                
                return $appr;
            })
			->addIndexColumn()
            ->addColumn('time_in', function($row){
                return date('H:i', strtotime($row->time_in));
            })
			->addIndexColumn()
            ->addColumn('time_out', function($row){
                return date('H:i', strtotime($row->time_out));
            })
            ->addIndexColumn()
            ->addColumn('total', function($row){
               $response = '00:00:00';

                if($row->time_out != '-'){
                    //$total_time = 60 * $row->total;
                    //$response = gmdate("H:i:s", $total_time);
					$a = DateTime::createFromFormat('H:i Y-m-d', date("H:i Y-m-d",strtotime($row->time_in)));
					$b = DateTime::createFromFormat('H:i Y-m-d', date("H:i Y-m-d",strtotime($row->time_out)));
					$interval = $a->diff($b);
					$hours    = ($interval->days * 24) + $interval->h
								+ ($interval->i / 60) + ($interval->s / 3600);
					$total = $hours;
					$response = gmdate("H:i:s", (60 * 60 * $total));
                }
		
                return $response;
            })
			->addIndexColumn()
            ->addColumn('last_updated', function($row){
                $response = '';
                if($row->updated_at != ''){
                    $label      = date(get_date_format(), strtotime($row->updated_at));
                    $data_sort  = date('Y-m-d', strtotime($row->updated_at));
                    $response   = '<label data-sort="'.$data_sort.'">'.$label.'</label>';
                return $response;
                }
                return $response;
            })
            ->addIndexColumn()
            ->addColumn('action', function($row){
                $url_edit = route('attendance.edit', [$row->r_id]);
				$action="";
				if((check_permission(Auth::user()->Employee->department_id,"Attendance","full")) || (check_permission(Auth::user()->Employee->department_id,"Attendance","Edit"))){
                $action = '<button type="button" onclick="window.location.href=\''.$url_edit.'\'" class="btn btn-outline-secondary btn-rounded btn-icon btn-sm"><i style="margin-left: -6px;" class="mdi mdi-lead-pencil"></i></button> ';
				}
				if((check_permission(Auth::user()->Employee->department_id,"Attendance","full")) || (check_permission(Auth::user()->Employee->department_id,"Attendance","delete"))){
                $action .= '  <button type="button" data-toggle="modal" data-id="'.$row->r_id.'" data-target="#DeleteModal" class="btn btn-outline-secondary btn-rounded btn-icon btn-sm btn-delete_row ml-2"><i style="margin-left: -7px;" class="mdi mdi-delete"></i></button>';
				}
                return $action;
            })
            ->rawColumns(['id', 'at_date','name','state_status','approved_by','time_in','time_out','total','last_updated','action'])
            ->make(true);
        
        return $data_tables;
    }

}
