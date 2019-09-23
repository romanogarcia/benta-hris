<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\OvertimeRequests;
use App\Employee;
use DB;
use Redirect; 
use Illuminate\Support\Facades\Input;
use DataTables;
use Auth;

class OvertimeRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {  
        $records = OvertimeRequests::join('employees', 'employees.user_id', '=', 'overtime_requests.user_id');

        if ($request->isMethod('post'))
        {
            if($request->input('request_id') != null)
            {
                $records = $records->where("overtime_requests.id", $request->input('request_id'));
            }
            if($request->input('start_date') != null)
            {
                $startdate = date('Y-m-d', strtotime(str_replace("-","/",$request->input('start_date'))));
                $records = $records->where("overtime_requests.date_start", "=", $startdate);
            }
            if($request->input('end_date') != null)
            {
                $enddate = date('Y-m-d', strtotime(str_replace("-","/",$request->input('end_date'))));
                $records = $records->where("overtime_requests.date_end", "=", $enddate);
            }
            if($request->input('overtime_type') != null)
            {
                $records = $records->where("overtime_requests.type", $request->input('overtime_type'));
            }
        } 

        $records = $records->paginate(5, 
            array(
                'overtime_requests.*',
                'overtime_requests.created_at as date_request',
                DB::raw('concat(employees.first_name, " ", employees.last_name) as name'),
            )
        );

        return view('overtime_request.list', [
            'record' => $records,
            'requests' => $request
        ]);
    }
	
	public function search()
	{
        return view('overtime_request.search_overtimerequest');
	}
	public function search_filter(Request $request){
        // dd($request);
        
        $employee_id ="";
		if(Auth::user()->employee->account_type == 0){
			$employee_id =Auth::user()->employee->id;
        }
        $date_range = $request->date_range_pick;
		$leave_type = $request->leave_type;
		$approved_by = $request->approved_by;
		$state_status = $request->state_status;
		
		$filters = [];
		if($employee_id != null)
		{
			$filters['overtime_requests.user_id'] = $employee_id;
		}
		if($request->input('request_id') != null)
		{
			$filters['overtime_requests.id'] = $request->input('request_id');
		}
		if($request->input('start_date') != null)
		{
			$startdate = date('Y-m-d', strtotime(str_replace("-","/",$request->input('start_date'))));
			$filters['overtime_requests.date_start'] = $startdate;
		}
		if($request->input('end_date') != null)
		{
			$enddate = date('Y-m-d', strtotime(str_replace("-","/",$request->input('end_date'))));
			$filters['overtime_requests.date_end'] = $enddate;
		}
		if($request->input('overtime_type') != null)
		{
			$filters['overtime_requests.type'] =  $request->input('overtime_type');
		}
		
        
        $select_qry = array(
            'employees.first_name',
            'employees.last_name',
            'overtime_requests.id as or_id',
            'overtime_requests.date_start',
            'overtime_requests.date_end',
            'overtime_requests.time_start',
            'overtime_requests.time_end',
            'overtime_requests.type',
            'overtime_requests.status',
            'overtime_requests.approved_by',
            'overtime_requests.approved_date',
        );

       
		$data = OvertimeRequests::where($filters)
		->join('employees', 'employees.user_id', '=', 'overtime_requests.user_id')
		->get($select_qry);
            
        $data_tables = Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('id', function($row){
				if((check_permission(Auth::user()->Employee->department_id,"Overtime Request","full")) || (check_permission(Auth::user()->Employee->department_id,"Overtime Request","Edit"))){
                $url_edit = route('overtime.edit', [$row->or_id]);

                return '<a href="'.$url_edit.'">'.$row->or_id.'</a>';
				}else{
				return $row->or_id;
				}
            })
            ->addIndexColumn()
            ->addColumn('name', function($row){
                return ucwords($row->first_name).' '.ucwords($row->last_name);
            })
			->addIndexColumn()
            ->addColumn('date_start', function($row){
                return date(get_date_format(), strtotime($row->date_start)).'<br>'.date("h:ia", strtotime($row->time_start));
				
            })
			->addIndexColumn()
            ->addColumn('date_end', function($row){
                return date(get_date_format(), strtotime($row->date_end)).'<br>'.date("h:ia", strtotime($row->time_end));
            })
			->addIndexColumn()
            ->addColumn('type', function($row){
                return $row->type;
            })
            ->addIndexColumn()
            ->addColumn('status', function($row){
				if($row->status == 'Pending')
					$lbl = '<label class="badge badge-warning">'.$row->status.'</label>';
				elseif($row->status == 'Approved')
					$lbl = '<label class="badge badge-success">'.$row->status.'</label>';
				elseif($row->status == 'Declined')
					$lbl = '<label class="badge badge-danger">'.$row->status.'</label>';
				elseif($row->status == 'Rescheduled')
					$lbl = '<label class="badge badge-info">'.$row->status.'</label>';
				elseif($row->status == 'Cancel')
					$lbl = '<label class="badge badge-danger">'.$row->status.'</label>';
                return $lbl;
            })
			->addIndexColumn()
            ->addColumn('approved_by', function($row){
                    $appr = '';
                    $label      = date(get_date_format(), strtotime($row->approved_date));
                    $data_sort  = date('Y-m-d', strtotime($row->approved_date));
                    $response   = '<label data-sort="'.$data_sort.'">'.$label.'</label>';
                    if($row->approved_by == ''){
                        $appr.= '<span class="text-warning"><i>Pending</i></span>';
					}else{
						$appr.= '<span class="text-success"><i>'.ucwords($row->approved_by).'</i></span>';
						$appr.= '<br>';
						$appr.= '<span data-sort="'.$data_sort.' class="text-muted"><i>'.$label.'<br>'.date("h:ia", strtotime($row->approved_time)).'</i></span>';
					}	
					
					return $appr;	
            })
            ->addIndexColumn()
            ->addColumn('action', function($row){
                $url_edit = url('overtime/'. $row->or_id .'/edit');
				$action="";
				if((check_permission(Auth::user()->Employee->department_id,"Overtime Request","full")) || (check_permission(Auth::user()->Employee->department_id,"Overtime Request","Edit"))){
                $action = '<td><button type="button" onclick="window.location.href=\''.$url_edit.'\'" class="btn btn-outline-secondary btn-rounded btn-icon btn-sm"><i style="margin-left: -6px;" class="mdi mdi-lead-pencil"></i></button> </td>';
				}
				if((check_permission(Auth::user()->Employee->department_id,"Overtime Request","full")) || (check_permission(Auth::user()->Employee->department_id,"Overtime Request","delete"))){
                $action .= '<td><button type="button" data-toggle="modal" data-id="'.$row->or_id.'" data-target="#DeleteModal" class="btn btn-outline-secondary btn-rounded btn-icon btn-sm btn-delete_row ml-4"><i style="margin-left: -7px;" class="mdi mdi-delete"></i></button></td>';
				}
                return $action;
            })
            ->rawColumns(['id','name','date_start','date_end','type', 'status', 'approved_by','action'])
            ->make(true);
			
        return $data_tables;
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $today = date("Y-m-d");
        $overtime_type = array('Regular Overtime', 'Holiday Overtime', 'Sunday Overtime');
        $overtime_status = array('Pending','Approved', 'Cancel', 'Rescheduled','Declined');

        $user = auth()->user();

        if($user == null)
        {
            return redirect('/home')->with('success', 'Please Login your account');
        }
        else 
        {
            
            return view('overtime_request/create', compact('overtime_type', 'today', 'overtime_status'));
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
        $request->status = 'Pending';
        $validatedData = $this->validate($request, [
            'time_start' => 'required|before:'.$request->time_end,
            'time_end' => 'required|after:'.$request->time_start,
            'date_start' => 'required|before_or_equal:'.$request->date_end,
            'date_end' => 'required',
            'type' => 'required',
            'reason' => 'required',
            // 'status' => 'required',
            'user_id' => 'required',
        ]);
		$validatedData['date_start'] = date('Y-m-d',strtotime(str_replace("-","/",$request->date_start)));
		$validatedData['date_end'] = date('Y-m-d',strtotime(str_replace("-","/",$request->date_end)));
        OvertimeRequests::create($validatedData);
        return redirect('overtime')->with('success', 'Overtime Request successfully created');

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
     * @param  \App\OvertimeRequests  $overtime_request
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $today = date("Y-m-d");
        $overtime_type = array('Regular Overtime', 'Holiday Overtime', 'Sunday Overtime');
        $overtime_status = array('Pending','Approved', 'Cancel', 'Rescheduled','Declined');

        $records = OvertimeRequests::findOrFail($id);
        return view('overtime_request.edit', ['r' => $records, 'today'=>$today, 'overtime_type' => $overtime_type, 'overtime_status' => $overtime_status]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  OvertimeRequests  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // dd($request);

        $validatedData = $this->validate($request, [
            'time_start' => 'required|before:'.$request->time_end,
            'time_end' => 'required|after:'.$request->time_start,
            'date_start' => 'required|before_or_equal:'.$request->date_end,
            'date_end' => 'required',
            'type' => 'required',
            'reason' => 'required',
            'status' => 'required',
        ]);
        
		$validatedData['date_start'] = date('Y-m-d',strtotime(str_replace("-","/",$request->date_start)));
		$validatedData['date_end'] = date('Y-m-d',strtotime(str_replace("-","/",$request->date_end)));
        $overtime_request = OvertimeRequests::findOrFail($id);
        $overtime_request->fill($validatedData);
        if($request->status == 'Approved'){
            $get_current_employee            = Employee::where('user_id', auth()->user()->id)->first();
            $overtime_request->approved_by   = ucwords($get_current_employee->first_name).' '.ucwords($get_current_employee->last_name);
            $overtime_request->approved_date = date("Y-m-d");
           // $overtime_request->approved_time = date("h:ia");
        }else{
            $overtime_request->approved_by      = null;
            $overtime_request->approved_date    = null;
            //$overtime_request->approved_time    = null;
        }

        $overtime_request->save();
        
        
        // return redirect()->back()->with('success', 'Overtime Request successfully updated');
        return redirect('overtime')->with('success', 'Overtime Request successfully updated');
    
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(OvertimeRequests::destroy($id)){
            return redirect()->back()->with('success','Overtime Request deleted successfully!');
        } else {
            return redirect()->back()->with('error','Request Failed!');
        }
    }
}