<?php

namespace App\Http\Controllers;

use App\Attendance;
use Illuminate\Http\Request;
use App\LeaveRequest;
use App\Employee;
use DataTables;
use Excel;
use Illuminate\Support\Facades\DB;
use PDF;
use App\Companies;
use Auth;
use App\Department;
use App\Payroll;

class DailyTimeRecordController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
	public function reports()
	{
		return view('daily_time_record.reports-list',[]);
	}
    public function history(){
		$employees = [];
		if(Auth::user()->employee->account_type == 1){
        $employees = Employee::orderby('first_name', 'ASC')->orderby('last_name', 'ASC')->get();
		}	
        return view('daily_time_record.history',['employees'=>$employees]);
    }
	
    public function get_history(Request $request){
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
        );
        
        $currentMonth = date("m");
        $currentYear = date("Y");

        $employee_id = $request->employee_id;
		
		if(Auth::user()->employee->account_type == 0){
			if(!empty($employee_id) && (in_array(Auth::user()->employee->id, $employee_id))){
				$employee_id = array(Auth::user()->employee->id);
			}
			else if(!empty($employee_id)){
				$employee_id = array('-1');
			}
			else{
				$employee_id = array(Auth::user()->employee->id);
			}
        }
        $date = $request->date;
		$time_in = $request->time_in;
		$time_out = $request->time_out;
		$last_updated = $request->last_updated;
        $filters=[];
        /*if(!empty($employee_id)){
			$filters['employee_id']=$employee_id;
		}	*/

            if($date!=''){
                $date_array  = explode(" - ",$date);
				$from = \Carbon\Carbon::createFromFormat(get_date_format(), $date_array[0])->format('Y-m-d');
            	$to = \Carbon\Carbon::createFromFormat(get_date_format(), $date_array[1])->format('Y-m-d');
                $data = Attendance::where($filters);
				$data = $data->whereBetween('attendances.at_date',[$from,$to]);
            } else {
                $data = Attendance::where($filters);
                $data =  $data->whereRaw('MONTH(attendances.at_date) = ?', [$currentMonth])
                    ->whereRaw('YEAR(attendances.at_date) = ?', [$currentYear]);
                    
            }
			if(!empty($employee_id)){
				$data= $data->whereIn('employee_id', $employee_id);
			}
			if($time_in!=''){
				$data = $data->where('time_in','like','%'.$time_in.'%');
			}
			if($time_out!=''){
				$data = $data->where('time_out','like','%'.$time_out.'%');
			}
			if($last_updated!=''){
				$last_updated =  \Carbon\Carbon::createFromFormat(get_date_format(), $last_updated)->format('Y-m-d');
				$data = $data->where('attendances.updated_at','like','%'.$last_updated.'%');
			}
			$data = $data->join('employees', 'employees.id',  '=', 'attendances.employee_id')
                    ->get($select_qry);
        	$data_tables = Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('date', function($row){

                $label      = date(get_date_format(), strtotime($row->at_date));
                $data_sort  = date('Y-m-d', strtotime($row->at_date));
                $response   = '<label data-sort="'.$data_sort.'">'.$label.'</label>';
               
                if($row->night_shift == 1){
                    $response .= '<small><span class="badge badge-dark">night</span></small';
                }

                return $response;
            })
            ->addIndexColumn()
            ->addColumn('name', function($row){
                return ucwords($row->first_name).' '.ucwords($row->last_name);
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
                    $total_time = 60 * $row->total;
                    $response = gmdate("H:i:s", $total_time);
                }

                return $response;
            })
			->addIndexColumn()
            ->addColumn('last_updated', function($row){
                $response = '';
                if($row->updated_at != ''){
                    $response = date("Y-m-d", strtotime($row->updated_at));
                }
                return $response;
            })
            ->addIndexColumn()
            ->rawColumns(['date','name','time_in','time_out','total', 'last_updated'])
            ->make(true);
        
        return $data_tables;

    }
    public function payrolls(){
		$employees = [];
		if(Auth::user()->employee->account_type == 1){
		$employees = Employee::orderby('first_name', 'ASC')->orderby('last_name', 'ASC')->get();
		}	
		$departments = Department::orderBy('name', 'ASC')->get();
        return view('daily_time_record.payrolls',['employees'=>$employees,'departments'=>$departments]);
	}
	public function get_payrolls(Request $request)
	{
		$select_qry = array(
            'employees.first_name',
            'employees.last_name',
            'payrolls.billing_number as batch',
            'payrolls.payroll_number as payroll',
            'payrolls.period_from',
            'payrolls.period_to',
            'payrolls.payroll_date',
            'payrolls.netpay',
        );
		$currentMonth = date("m");
        $currentYear = date("Y");

        $employee_id = $request->employee_id;
		$date = $request->date;
		$department_id=$request->department_id;
        $filters=[];
		if(Auth::user()->employee->account_type == 0){
			if(!empty($employee_id) && (in_array(Auth::user()->employee->id, $employee_id))){
				$employee_id = array(Auth::user()->employee->id);
			}
			else if(!empty($employee_id)){
				$employee_id = array('-1');
			}
			else{
				$employee_id = array(Auth::user()->employee->id);
			}
        }
		if($date!=''){
        	$date_array  = explode(" - ",$date);
			$from = \Carbon\Carbon::createFromFormat(get_date_format(), $date_array[0])->format('Y-m-d');
           	$to = \Carbon\Carbon::createFromFormat(get_date_format(), $date_array[1])->format('Y-m-d');
           	$data = Payroll::where($filters);
			$data = $data->whereBetween('payroll_date',[$from,$to]);
        } 
		else 
		{
           	$data = Payroll::where($filters);
            $data =  $data->whereRaw('MONTH(payroll_date) = ?', [$currentMonth])
                    ->whereRaw('YEAR(payroll_date) = ?', [$currentYear]);  
		}
		$employees = new Employee;
            if($employee_id && $department_id == NULL){
                $employees = Employee::whereIn('user_id', $employee_id)->select('user_id')->get()->toArray();
            } else if($department_id && $employee_id == NULL){
                $employees = Employee::where('department_id', $department_id)->select('user_id')->get()->toArray();
            } else if($employee_id && $department_id){
                $employees = Employee::whereIn('user_id', $employee_id)
                    ->where('department_id', $department_id)->select('user_id')->get()->toArray();
            } else {
                $employees = Employee::select('user_id')->get()->toArray();
            }
		if(count($employees)>0){
			$data= $data->whereIn('employee_id', $employees);
		}
		else{
			$employees=array(-1);
			$data= $data->whereIn('employee_id', $employees);
		}
		
		$data = $data->join('employees', 'employees.id',  '=', 'payrolls.employee_id')
                    ->get($select_qry);
        	$data_tables = Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('employee', function($row){
                return ucwords($row->first_name).' '.ucwords($row->last_name);
            })
			->addIndexColumn()
            ->addColumn('batch', function($row){
                return $row->batch;
            })
			->addIndexColumn()
            ->addColumn('payroll', function($row){
                return $row->payroll;
            })
			->addIndexColumn()
            ->addColumn('from', function($row){
                return $row->period_from;
            })
			->addIndexColumn()
            ->addColumn('to', function($row){
                return $row->period_to;
            })
			->addIndexColumn()
            ->addColumn('date', function($row){
              return $row->payroll_date;
            })
			->addIndexColumn()
            ->addColumn('paid', function($row){
              return number_format($row->netpay, 2);
            })
            ->addIndexColumn()
            ->rawColumns(['employee','batch','payroll','from','to', 'payroll_date','paid'])
            ->make(true);
        
        return $data_tables;
	}	
    public function filter(){
        $currentMonth = date("m");
        $currentYear = date("Y");
        $records = Attendance::whereRaw('MONTH(date) = ?', [$currentMonth])
        ->whereRaw('YEAR(attendances.at_date) = ?', [$currentYear])
        ->paginate(20);


        $employees = Employee::orderby('first_name', 'ASC')->orderby('last_name', 'ASC')->get();

        return view('daily_time_record.filter',
            [
                'record'        =>  $records, 
                'employees'     =>  $employees
            ]
        );
    }

    public function filter_list(Request $request){
        //FILTER LIST VALIDATE
        // dd($request);
        
        $employee_id = $request->employee_id;
        $date_range = $request->date_range_picker;

        $filters = [];

        if($employee_id!=''){
            $filters['employee_id'] = $employee_id;
        }

        $to_select = array(
            'attendances.*',
            'employees.employee_number as employee_no',
        ); 

        if($date_range != ''){
			$date_array  = explode(" - ",$date_range);
			$from = date('Y-m-d',strtotime($date_array[0]));
            $to = date('Y-m-d',strtotime($date_array[1]));

            $data = Attendance::where($filters)
            ->whereBetween('attendances.at_date',[$from,$to])
            ->join('employees', 'employees.id', '=', 'attendances.employee_id')
            ->get($to_select);
        }else{
            $data = Attendance::where($filters)
            ->join('employees', 'employees.id', '=', 'attendances.employee_id')
            ->latest()
            ->get($to_select);
        }
        
        $data_tables = Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('date', function($row){
                $return_date = $row->at_date;
                
                if($row->night_shift == true){
                    $return_date .= ' <small><span class="badge badge-dark">night</span></small';
                }

                return $return_date;
            })
            ->addIndexColumn()
            ->addColumn('employee_id', function($row){
                return $row->employee_no;
            })
            ->addIndexColumn()
            ->addColumn('employee_name', function($row){
                return $row->name;
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
                $time_in = strtotime($row->time_in);
                $time_out = strtotime($row->time_out);
                $total_time = $time_out - $time_in;
                return gmdate("H:i:s", $total_time-3600);
            })
            ->rawColumns(['date','employee_id','employee_name','time_in','time_out', 'total'])
            ->make(true);
            
        return $data_tables;
    }

    public function tardiness(){
        $records = Attendance::where('total', '<', 8)
        ->paginate(20);
		$employees = [];
		if(Auth::user()->employee->account_type == 1){
		$employees = Employee::orderby('first_name', 'ASC')->orderby('last_name', 'ASC')->get();
		}	
        return view('daily_time_record.tardiness',['record'=>$records,'employees'=>$employees]);
    }
	public function tardiness_list(Request $request){
		$employee_id = $request->employee_id;
        $date_range = $request->date_range_picker;
		$time_in = $request->time_in;
		$time_out = $request->time_out;
		$total = $request->total_hours;
        $filters = [];
		
		if(Auth::user()->employee->account_type == 0){
			if(!empty($employee_id) && ($employee_id==Auth::user()->employee->id)){
				$employee_id = Auth::user()->employee->id;
			}
			else if(!empty($employee_id) && ($employee_id != Auth::user()->employee->id)){
				$employee_id =-1;
			}
			else{
				$employee_id = Auth::user()->employee->id;
			}
        }
		
        if($employee_id!=''){
            $filters['employee_id'] = $employee_id;
        }
		
        $to_select = array(
            'attendances.*',
            'employees.employee_number as employee_no',
        ); 
		
        if($date_range != ''){
			$date_array  = explode(" - ",$date_range);
			$from = \Carbon\Carbon::createFromFormat(get_date_format(), $date_array[0])->format('Y-m-d');
            $to = \Carbon\Carbon::createFromFormat(get_date_format(), $date_array[1])->format('Y-m-d');
            $data = Attendance::where($filters)->where('total', '<', 8)
            ->whereBetween('attendances.at_date',[$from,$to]);
			
        }else{
            $data = Attendance::where($filters)->where('total', '<', 8);
			
        }
		if($time_in!=''){
			$data = $data->where('time_in','like','%'.$time_in.'%');
		}
		if($time_out!=''){
			$data = $data->where('time_out','like','%'.$time_out.'%');
		}
		if($total!=''){
			$tot_array = explode(":",$total);
			$tot = $tot_array[0] + ($tot_array[1]/60) + ($tot_array[2]/3600);
			$tot = round($tot,2);
			$data = $data->where('total','=',$tot);
		}
		$data = $data->join('employees', 'employees.id', '=', 'attendances.employee_id')
            ->latest()->get($to_select);
        
        $data_tables = Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('date', function($row){
                
                $label      = date(get_date_format(), strtotime($row->at_date));
                $data_sort  = date('Y-m-d', strtotime($row->at_date));
                $return_date   = '<label data-sort="'.$data_sort.'">'.$label.'</label>';

                if($row->night_shift == true){
                    $return_date .= ' <small><span class="badge badge-dark">night</span></small';
                }

                return $return_date;
            })
            ->addIndexColumn()
            ->addColumn('employee_name', function($row){
                return $row->name;
            })
            ->addIndexColumn()
            ->addColumn('time_in', function($row){
				$time_in = date('H:i', strtotime($row->time_in));
                $time_in .= ' <abbr title="'.date('h:i a ', strtotime($row->time_in)).' @ '.date('D M-j-Y', strtotime($row->at_date)).'"><small><em>'.date('Y-m-d', strtotime($row->at_date)).'</em></small></abbr>';
                return $time_in;
            })
            ->addIndexColumn()
            ->addColumn('time_out', function($row){
				$time_out = '';
				 if(strlen($row->time_out)!=1){
                       $time_out= date('H:i', strtotime($row->time_out)).' <abbr title="'.date('h:i a ', strtotime($row->time_out)).' @ '.date('D M-j-Y', strtotime($row->at_date)).'"><small><em>'.date('Y-m-d', strtotime($row->at_date)).'</em></small></abbr>';
				 }else{
                       $time_out =  '--:--';
				 }
					
                return $time_out;
            })
            ->addIndexColumn()
            ->addColumn('total', function($row){
                $time_in = strtotime($row->time_in);
                $time_out = strtotime($row->time_out);
				if($row->time_out!='-'){
                	$total_time = $time_out - $time_in;
                	return gmdate("H:i:s", $total_time);
				}	
				else
				{
					return "00:00:00";
				}	
            })
            ->rawColumns(['date','employee_name','time_in','time_out', 'total'])
            ->make(true);
            
        return $data_tables;
	}
    public function absences(){
        $leave_data = DB::table('leaves')->get();
        $records = LeaveRequest::where('state_status', '=', 'Approved')
        ->paginate(5);

        $records = LeaveRequest::join('users', 'users.id', '=', 'leave_requests.user_id')
        ->where('leave_requests.state_status', '=', 'Approved')
        ->paginate(5, 
            array(
                'leave_requests.*',
                'leave_requests.created_at as date_request',
                'users.name as name',
            )
        );
		$employees = [];
		if(Auth::user()->employee->account_type == 1){
        $employees = Employee::orderby('first_name', 'ASC')->orderby('last_name', 'ASC')->get();
		}	
        return view('daily_time_record.absences',['record'=>$records,'employees'=>$employees,'leave_data'=>$leave_data]);
    }
	public function absence_list(Request $request)
	{
		$employee_id = $request->search_employee_id;
        $date_range = $request->date_range_picker;
        $datefiled = $request->datefiled;
        $approveby = $request->approveby;
		$type = $request->type;
		$approved_date = $request->approved_date;
		if(Auth::user()->employee->account_type == 0){
			if(!empty($employee_id) && ($employee_id==Auth::user()->employee->id)){
				$employee_id = Auth::user()->employee->id;
			}
			else if(!empty($employee_id) && ($employee_id != Auth::user()->employee->id)){
				$employee_id =-1;
			}
			else{
				$employee_id = Auth::user()->employee->id;
			}
        }
		
        $filters = [];

        if($employee_id!='') {
            $filters['employee_id'] = $employee_id;
        }
        if($datefiled!='') {
            $filters['date_filed'] = \Carbon\Carbon::createFromFormat(get_date_format(),$datefiled)->format('Y-m-d');
        }
		if($approved_date!='') {
            $filters['approved_date'] = \Carbon\Carbon::createFromFormat(get_date_format(),$approved_date)->format('Y-m-d');
        }
        if($approveby != '') {
            $filters['approved_by'] = $approveby;
        }
		if($type != '')
		{
			$filters['type'] = $type;
		}
        $to_select = array(
            'leave_requests.*',
            'leave_requests.created_at as date_request',
            'users.name as name',
        ); 

        if($date_range != ''){
			$date_array  = explode(" - ",$date_range);
			$from = \Carbon\Carbon::createFromFormat(get_date_format(),$date_array[0])->format('Y-m-d');
            $to = \Carbon\Carbon::createFromFormat(get_date_format(),$date_array[1])->format('Y-m-d');

            $data = LeaveRequest::join('users', 'users.id', '=', 'leave_requests.user_id')
        	->where('leave_requests.state_status', '=', 'Approved')
            ->whereBetween('leave_requests.date_filed',[$from,$to])
            ->orWhereBetween('leave_requests.date_start',[$from,$to])
            ->orWhereBetween('leave_requests.date_end',[$from,$to])
            ->orWhereBetween('leave_requests.approved_date',[$from,$to])
            ->get($to_select);
        }else{
            $data = LeaveRequest::where($filters)
            ->join('users', 'users.id', '=', 'leave_requests.user_id')
        	->where('leave_requests.state_status', '=', 'Approved')
            ->latest()
            ->get($to_select);
        }
		$data_tables = Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('name', function($row){
             
                return $row->name;
            })
            ->addIndexColumn()
            ->addColumn('date_filed', function($row){
                $label      = date(get_date_format(), strtotime($row->date_filed));
                $data_sort  = date('Y-m-d', strtotime($row->date_filed));
                $return_date   = '<label data-sort="'.$data_sort.'">'.$label.'</label>';

                return $return_date;
            })
			->addIndexColumn()
            ->addColumn('date_start', function($row){
                $label      = date(get_date_format(), strtotime($row->date_start));
                $data_sort  = date('Y-m-d', strtotime($row->date_start));
                $return_date   = '<label data-sort="'.$data_sort.'">'.$label.'</label>';

                return $return_date;
            })
			->addIndexColumn()
            ->addColumn('date_end', function($row){
                $label      = date(get_date_format(), strtotime($row->date_end));
                $data_sort  = date('Y-m-d', strtotime($row->date_end));
                $return_date   = '<label data-sort="'.$data_sort.'">'.$label.'</label>';

                return $return_date;
            })
            ->addIndexColumn()
            ->addColumn('type', function($row){
                return $row->type;
            })
			
			->addIndexColumn()
            ->addColumn('approved_by', function($row){
                return $row->approved_by;
            })
			->addIndexColumn()
            ->addColumn('approved_date', function($row){
                return $row->approved_date;
            })
			
            ->rawColumns(['name','date_filed','date_start','date_end','type','approved_by','approved_date'])
            ->make(true);
            
        return $data_tables;
	}
	function excel_tardiness(Request $request)
    {
		
		$employee_id = $request->search_employee_id;
        $date_range = $request->date_range_picker;
        $time_in = $request->time_in;
        $time_out = $request->time_out;
		$total = $request->total_hours;
		
		if(Auth::user()->employee->account_type == 0){
			if(!empty($employee_id) && ($employee_id==Auth::user()->employee->id)){
				$employee_id = Auth::user()->employee->id;
			}
			else if(!empty($employee_id) && ($employee_id != Auth::user()->employee->id)){
				$employee_id =-1;
			}
			else{
				$employee_id = Auth::user()->employee->id;
			}
        }
         $filters = [];

        if($employee_id!=''){
            $filters['employee_id'] = $employee_id;
        }

        $to_select = array(
            'attendances.*',
            'employees.employee_number as employee_no',
            'employees.first_name',
            'employees.last_name',
        ); 

       if($date_range != ''){
			$date_array  = explode(" - ",$date_range);
			$from = \Carbon\Carbon::createFromFormat(get_date_format(), $date_array[0])->format('Y-m-d');
            $to = \Carbon\Carbon::createFromFormat(get_date_format(), $date_array[1])->format('Y-m-d');
            $attendence_data = Attendance::where($filters)->where('total', '<', 8)
            ->whereBetween('attendances.at_date',[$from,$to]);
			
        }else{
            $attendence_data = Attendance::where($filters)->where('total', '<', 8);
			
        }
		if($time_in!=''){
			$attendence_data = $attendence_data->where('time_in','like','%'.$time_in.'%');
		}
		if($time_out!=''){
			$attendence_data = $attendence_data->where('time_out','like','%'.$time_out.'%');
		}
		if($total!=''){
			$tot_array = explode(":",$total);
			$tot = $tot_array[0] + ($tot_array[1]/60) + ($tot_array[2]/3600);
			$tot = round($tot,2);
			$data = $attendence_data->where('total','=',$tot);
		}
		$attendence_data = $attendence_data->join('employees', 'employees.id', '=', 'attendances.employee_id')
            ->latest()->get($to_select);
		
		 //$attendence_data = Attendance::where('total', '<', 8)->get()->toArray();
		 $attendence_array[] = array('Date', 'Name', 'Time-In', 'Time-Out', 'Total');
		 foreach($attendence_data as $attendence)
		 {
		  $attendence_array[] = array(
		   'Date'  => ($attendence['night_shift'] == true)?$attendence['at_date'].' ( night ) ':$attendence['at_date'],
		   'Name'   => $attendence['name'],
		   'Time-In'    => date('H:i', strtotime($attendence['time_in'])).'('.date('Y-m-d', strtotime($attendence['at_date'])).')',
		   'Time-Out'  => (strlen($attendence['time_out'])!=1)?date('H:i', strtotime($attendence['time_out'])).'('.date('Y-m-d', strtotime($attendence['at_date'])).')':"--:--",
		   'Total'   => ($attendence['time_out']!='-')?gmdate("H:i:s",((strtotime($attendence['time_out']) - strtotime($attendence['time_in'])))):'00:00:00'
		  );
		 }
		if($request->export_type == 'pdf'){
			$company = Companies::first();
			$pdf = PDF::setOptions(['isPhpEnabled' => true]);
			$details = ['title' => 'Tardiness Report','data_array' => $attendence_data,'company'=>$company,'pdf'=>$pdf];
			
			$pdf = $pdf->loadView('daily_time_record.tardinessReport', $details); 
			$pdf->setPaper('A4', 'landscape');
			
			return $pdf->download('Tardiness-Report-'.date("Ymd-His").'.pdf');
		}
		else{
			Excel::create('Tardiness-Report-'.date('Ymd').'-'.date('His'), function($excel) use ($attendence_array){
			  $excel->setTitle('Tardiness Report');
			  $excel->sheet('Tardiness Data', function($sheet) use ($attendence_array){
			   $sheet->fromArray($attendence_array, null, 'A1', false, false);
			  });
			 })->download('xlsx');
		}
		 
    }
	function download_filter_export(Request $request)
	{
		
		$employee_id = $request->employee_id;
        $date_range = $request->date_range_picker;

        $filters = [];

        if($employee_id!=''){
            $filters['employee_id'] = $employee_id;
        }

        $to_select = array(
            'attendances.*',
            'employees.employee_number as employee_no',
        ); 

        if($date_range != ''){
			$date_array  = explode(" - ",$date_range);
			$from = date('Y-m-d',strtotime(str_replace("-","/",$date_array[0])));
            $to = date('Y-m-d',strtotime(str_replace("-","/",$date_array[1])));

            $data = Attendance::where($filters)
            ->whereBetween('attendances.at_date',[$from,$to])
            ->join('employees', 'employees.id', '=', 'attendances.employee_id')
            ->get($to_select);
        }else{
            $data = Attendance::where($filters)
            ->join('employees', 'employees.id', '=', 'attendances.employee_id')
            ->latest()
            ->get($to_select);
        }
        
			$filter_array = [];
			foreach($data as $d)
			{
				  $time_in = strtotime($d->time_in);
					$time_out = strtotime($d->time_out);
					$total_time = $time_out - $time_in;

				$filter_array[]=array(
					'Date'=>$d->at_date,
					'Employee No'=>$d->employee_no,
					'Name'=>$d->name,
					'Time-In'=>date('H:i', strtotime($d->time_in)),
					'Time-Out'=>date('H:i', strtotime($d->time_out)),
					'Total'=>gmdate("H:i:s", $total_time-3600)
				);
			}
			Excel::create('DTR_filter_reports', function($excel) use ($filter_array) {
				$excel->sheet('DTR_Sheet', function($sheet) use ($filter_array)
				{

				  $sheet->fromArray($filter_array);

				});
		   })->download('xlsx');
		
	}	
	function download_absence_excel(Request $request)
	{
		$employee_id = $request->search_employee_id;
        $date_range = $request->date_range_picker;
		$datefiled = $request->datefiled;
        $approveby = $request->approveby;
		$type = $request->type;
		$approved_date = $request->approved_date;
		if(Auth::user()->employee->account_type == 0){
			if(!empty($employee_id) && ($employee_id==Auth::user()->employee->id)){
				$employee_id = Auth::user()->employee->id;
			}
			else if(!empty($employee_id) && ($employee_id != Auth::user()->employee->id)){
				$employee_id =-1;
			}
			else{
				$employee_id = Auth::user()->employee->id;
			}
        }
		
        $filters = [];
		
		if($employee_id!=''){
            $filters['employee_id'] = $employee_id;
        }
		        if($datefiled!='') {
            $filters['date_filed'] = \Carbon\Carbon::createFromFormat(get_date_format(),$datefiled)->format('Y-m-d');
        }
		if($approved_date!='') {
            $filters['approved_date'] = \Carbon\Carbon::createFromFormat(get_date_format(),$approved_date)->format('Y-m-d');
        }
        if($approveby != '') {
            $filters['approved_by'] = $approveby;
        }
		if($type != '')
		{
			$filters['type'] = $type;
		}
        $to_select = array(
            'leave_requests.*',
            'leave_requests.created_at as date_request',
            'users.name as name',
        ); 

        if($date_range != ''){
			$date_array  = explode(" - ",$date_range);
			$from = \Carbon\Carbon::createFromFormat(get_date_format(), $date_array[0])->format('Y-m-d');
            $to = \Carbon\Carbon::createFromFormat(get_date_format(), $date_array[1])->format('Y-m-d');

            $data = LeaveRequest::join('users', 'users.id', '=', 'leave_requests.user_id')
        	->where('leave_requests.state_status', '=', 'Approved')
            ->whereBetween('leave_requests.date_filed',[$from,$to])
            ->orWhereBetween('leave_requests.date_start',[$from,$to])
            ->orWhereBetween('leave_requests.date_end',[$from,$to])
            ->orWhereBetween('leave_requests.approved_date',[$from,$to])
            ->get($to_select);
        }else{
            $data = LeaveRequest::join('users', 'users.id', '=', 'leave_requests.user_id')
        	->where('leave_requests.state_status', '=', 'Approved')
            ->latest()
            ->get($to_select);
        }
		$absence_array = [];
		foreach($data as $d)
		{

			$absence_array[]=array(
				'Name'=>$d->name,
				'Date Filed'=>$d->date_filed,
				'Date Start'=>$d->date_start,
				'Date End'=>$d->date_end,
				'Type'=>$d->type,
				'Reason'=>$d->reason,
				'File Path'=>$d->filepath,
				'Approved By'=>$d->approved_by,
				'Approved Date'=>$d->approved_date,
				'Remarks'=>$d->remarks
			);
		}
		
	if($request->export_type == 'pdf'){
			$company = Companies::first();
			$pdf = PDF::setOptions(['isPhpEnabled' => true]);
			$details = ['title' => 'Employee Absenses Report','data_array' => $data,'company'=>$company,'pdf'=>$pdf];
			
			$pdf = $pdf->loadView('daily_time_record.absenseReport', $details); 
			$pdf->setPaper('A4', 'landscape');
			
			return $pdf->download('Employee-Absenses-Report-'.date("Ymd-his").'.pdf');
		}
		else{
			Excel::create('Employee-Absenses-Report-'.date('Ymd').'-'.date('His'), function($excel) use ($absence_array) {
				$excel->sheet('Absenses-Report', function($sheet) use ($absence_array)
				{
				  $sheet->fromArray($absence_array);
				});
		   })->download('xlsx');
		}	
    }	
    
    function download_history_report(Request $request){
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
        );
        
        $currentMonth = date("m");
        $currentYear = date("Y");

        $employee_id = $request->employee_id;
		if(Auth::user()->employee->account_type == 0){
			if(!empty($employee_id) && (in_array(Auth::user()->employee->id, $employee_id))){
				$employee_id = array(Auth::user()->employee->id);
			}
			else if(!empty($employee_id)){
				$employee_id = array('-1');
			}
			else{
				$employee_id = array(Auth::user()->employee->id);
			}
        }
        $date = $request->date;
		$time_in = $request->time_in;
		$time_out = $request->time_out;
		$last_updated = $request->last_updated;
        $filters=[];
        /*if(!empty($employee_id)){
			$filters['employee_id']=$employee_id;
		}	*/

            if($date!=''){
                $date_array  = explode(" - ",$date);
				$from = \Carbon\Carbon::createFromFormat(get_date_format(), $date_array[0])->format('Y-m-d');
            	$to = \Carbon\Carbon::createFromFormat(get_date_format(), $date_array[1])->format('Y-m-d');
                $data = Attendance::where($filters);
				$data = $data->whereBetween('attendances.at_date',[$from,$to]);
            } else {
                $data = Attendance::where($filters);
                $data =  $data->whereRaw('MONTH(attendances.at_date) = ?', [$currentMonth])
                    ->whereRaw('YEAR(attendances.at_date) = ?', [$currentYear]);
                    
            }
			if(!empty($employee_id)){
				$data= $data->whereIn('employee_id', $employee_id);
			}
			if($time_in!=''){
				$data = $data->where('time_in','like','%'.$time_in.'%');
			}
			if($time_out!=''){
				$data = $data->where('time_out','like','%'.$time_out.'%');
			}
			if($last_updated!=''){
				$last_updated =  \Carbon\Carbon::createFromFormat(get_date_format(), $last_updated)->format('Y-m-d');
				$data = $data->where('attendances.updated_at','like','%'.$last_updated.'%');
			}
			$data = $data->join('employees', 'employees.id',  '=', 'attendances.employee_id')
                    ->get($select_qry);

        $data_array = [];
        foreach($data as $row){
            $total = '00:00:00';

            if($row->time_out != '-'){
                $total_time = 60 * $row->total;
                $total = gmdate("H:i:s", $total_time);
            }

            $updated_at = '';
            if($row->updated_at != ''){
                $updated_at = date("Y-m-d", strtotime($row->updated_at));
            }

            $date_type = $row->at_date;
            if($row->night_shift == true){
                $date_type .= ' (Night)';
            }
            
			$data_array[] = array(
				'Date'          => $date_type,
				'Name'          => ucwords($row->first_name).' '.ucwords($row->last_name),
				'Time In'       => date('H:i', strtotime($row->time_in)),
				'Time Out'      => date('H:i', strtotime($row->time_out)),
				'Total'         => $total,
			);
        }
        if($request->export_type == 'pdf'){
			$company = Companies::first();
			$pdf = PDF::setOptions(['isPhpEnabled' => true]);
			$details = ['title' => 'Attendances Report','data_array' => $data,'company'=>$company,'pdf'=>$pdf];
			
			$pdf = $pdf->loadView('daily_time_record.historyReport', $details); 
			$pdf->setPaper('A4', 'landscape');
			
			return $pdf->download('Attendances-Report-'.date("Ymd-his").'.pdf');
		}
		else{
			Excel::create('Attendances-Report-'.date("Ymd-his"), function($excel) use ($data_array) {
				$excel->sheet('Attendances-Report', function($sheet) use ($data_array){
				  $sheet->fromArray($data_array);
				});
		   })->download('xlsx');
		}
    }
	public function download_payroll_report(Request $request)
	{
		$select_qry = array(
            'employees.first_name',
            'employees.last_name',
            'payrolls.billing_number as batch',
            'payrolls.payroll_number as payroll',
            'payrolls.period_from',
            'payrolls.period_to',
            'payrolls.payroll_date',
            'payrolls.netpay',
        );
		$currentMonth = date("m");
        $currentYear = date("Y");

        $employee_id = $request->employee_id;
		$date = $request->date;
		$department_id=$request->department_id;
        $filters=[];
		if(Auth::user()->employee->account_type == 0){
			if(!empty($employee_id) && (in_array(Auth::user()->employee->id, $employee_id))){
				$employee_id = array(Auth::user()->employee->id);
			}
			else if(!empty($employee_id)){
				$employee_id = array('-1');
			}
			else{
				$employee_id = array(Auth::user()->employee->id);
			}
        }
		if($date!=''){
        	$date_array  = explode(" - ",$date);
			$from = \Carbon\Carbon::createFromFormat(get_date_format(), $date_array[0])->format('Y-m-d');
           	$to = \Carbon\Carbon::createFromFormat(get_date_format(), $date_array[1])->format('Y-m-d');
           	$data = Payroll::where($filters);
			$data = $data->whereBetween('payrolls.payroll_date',[$from,$to]);
        } 
		else 
		{
           	$data = Payroll::where($filters);
            $data =  $data->whereRaw('MONTH(payrolls.payroll_date) = ?', [$currentMonth])
                    ->whereRaw('YEAR(payrolls.payroll_date) = ?', [$currentYear]);  
		}
		$employees = new Employee;
            if($employee_id && $department_id == NULL){
                $employees = Employee::whereIn('user_id', $employee_id)->select('user_id')->get()->toArray();
            } else if($department_id && $employee_id == NULL){
                $employees = Employee::where('department_id', $department_id)->select('user_id')->get()->toArray();
            } else if($employee_id && $department_id){
                $employees = Employee::whereIn('user_id', $employee_id)
                    ->where('department_id', $department_id)->select('user_id')->get()->toArray();
            } else {
                $employees = Employee::select('user_id')->get()->toArray();
            }
		if(count($employees)>0){
			$data= $data->whereIn('employee_id', $employees);
		}
		else{
			$employees=array(-1);
			$data= $data->whereIn('employee_id', $employees);
		}
		
		$data = $data->join('employees', 'employees.id',  '=', 'payrolls.employee_id')->get($select_qry);
        	$data_array = [];
        foreach($data as $row){ 
			$data_array[] = array(
				'Employee' => ucwords($row->first_name).' '.ucwords($row->last_name),
				'Batch' => $row->batch,
				'Payroll' => $row->payroll,
				'From' => $row->period_from,
				'To' => $row->period_to,
				'Payroll Date' => $row->payroll_date,
				'Paid' => number_format($row->netpay, 2),
			);
        }
        if($request->export_type == 'pdf'){
			$company = Companies::first();
			$pdf = PDF::setOptions(['isPhpEnabled' => true]);
			$details = ['title' => 'Payroll Register Report','data_array' => $data,'company'=>$company,'pdf'=>$pdf];
			
			$pdf = $pdf->loadView('daily_time_record.payroll_register', $details); 
			$pdf->setPaper('A4', 'landscape');
			
			return $pdf->download('Payroll-Register-Report-'.date("Ymd-his").'.pdf');
		}
		else{
			Excel::create('Payroll-Register-Report-'.date("Ymd-his"), function($excel) use ($data_array) {
				$excel->sheet('Payroll-Register-Report', function($sheet) use ($data_array){
				  $sheet->fromArray($data_array);
				});
		   })->download('xlsx');
		}
	}	
	function comming_soon(){
		return view('daily_time_record.comming_soon');
	}
}