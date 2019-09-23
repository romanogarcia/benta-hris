<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Auth;
use App\Leave;
use App\LeaveRequest;
use App\Employee;
use App\Department;
use DataTables;
use Session;

class LeaveController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // return redirect(route('leaves.leavesearch'));
        // return redirect(route('leaves.leave_list'));
    }

    public function leave_list(){
        $leaves = Leave::latest()->paginate(20);

        return view('settings.leavelist',compact('leaves'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function leave_edit($id)
    {   
        $editleave = Leave::findOrFail($id);
        $leaves = Leave::latest()->paginate(20);

        return view('settings.leavelist', compact('editleave', 'leaves')); 
    }

    public function requestList()
    {
        $likeauth = auth()->user()->id;
        $leave_list = LeaveRequest::where('user_id',$likeauth)->get();

        return view('leave_request/list', compact('leave_list'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
     public function create()
    {
		$editleaverequest = false;
		$leave_data = DB::table('leaves')->get();
        $leavesrequest = LeaveRequest::latest()->paginate(20);
        $user_id = auth()->user()->id;
        $state_status = array('Pending','Approved','Rescheduled','Declined');
        $today = date("Y-m-d");
        $leavesrequest = LeaveRequest::latest()->paginate(20);
        $leave_list = LeaveRequest::where('user_id',$user_id)->get();


        return view('leaves_request/make_leaverequest', compact('leavesrequest','state_status', 'today', 'leave_data', 'leave_list','user_id')); 
		
    }
	public function search()
	{		
		$leave_data = DB::table('leaves')->get();
		$employees = [];
		if(Auth::user()->employee->account_type == 1){
			$employees = Employee::orderBy('first_name', 'ASC')->orderBy('last_name', 'ASC')->get();
		}	
		$state_status = array('Pending','Approved','Rescheduled','Declined');
        
        return view('leaves_request.search_leaverequest', compact('employees','leave_data','state_status'));
	}	
	public function search_filter(Request $request){
        // dd($request);
        
        $employee_id = $request->employee_id;
        $date_range = $request->date_range_pick;
		$leave_type = $request->leave_type;
		$approved_by = $request->approved_by;
		$state_status = $request->state_status;
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
        $filters = [];
        if($employee_id!=''){
            $filters['leave_requests.user_id'] = $employee_id;
        } 
		if($state_status!=''){
			$filters['state_status'] = $state_status;
		}
		if($leave_type!=''){
			$filters['type'] = $leave_type;
        }
        
        $select_qry = array(
            'employees.first_name',
            'employees.last_name',
            'leave_requests.id as lr_id',
            'leave_requests.state_status',
            'leave_requests.approved_by',
            'leave_requests.approved_date',
            'leave_requests.approved_time',
            'leave_requests.date_start',
            'leave_requests.date_end',
        );

        if($date_range != ''){
			$date_array  = explode(" - ",$date_range);
			$from = \Carbon\Carbon::createFromFormat(get_date_format(),$date_array[0])->format('Y-m-d');
            $to =  \Carbon\Carbon::createFromFormat(get_date_format(),$date_array[1])->format('Y-m-d');

            if($approved_by != ''){
                $data = LeaveRequest::where($filters)
                ->where('approved_by','like',"%$approved_by%")
                ->whereBetween('leave_requests.date_start',[$from,$to])
                ->join('employees', 'leave_requests.user_id', '=' , 'employees.user_id')
                ->get($select_qry);
            }else{
                $data = LeaveRequest::where($filters)
                ->whereBetween('leave_requests.date_start',[$from,$to])
                ->join('employees', 'leave_requests.user_id', '=' , 'employees.user_id')
                ->get($select_qry);
            }
            
        }else{

            if($approved_by != ''){
                $data = LeaveRequest::where($filters)
                ->where('approved_by','like',"%$approved_by%")
                ->join('employees', 'leave_requests.user_id', '=' , 'employees.user_id')
                ->get($select_qry);
            }else{
                $data = LeaveRequest::where($filters)
                ->join('employees', 'leave_requests.user_id', '=' , 'employees.user_id')
                ->get($select_qry);
            }
            
        }


        $data_tables = Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('id', function($row){
				if((check_permission(Auth::user()->Employee->department_id,"Leave Request","full")) || (check_permission(Auth::user()->Employee->department_id,"Leave Request","Edit"))){
                $url_edit = url('leaves/' . $row->lr_id . '/edit');

                return '<a href="'.$url_edit.'">'.$row->lr_id.'</a>';
				}else{
				return $row->lr_id;
				}
            })
            ->addIndexColumn()
            ->addColumn('name', function($row){
                return ucwords($row->first_name).' '.ucwords($row->last_name);
            })
			->addIndexColumn()
            ->addColumn('state_status', function($row){
                if($row->state_status == 'Pending')
                    $lbl = '<label class="badge badge-warning">'.$row->state_status.'</label>';
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
                if($row->approved_by == ''){
                    $appr.= '<span class="text-warning"><i>Pending</i></span>';
                }else{
                    $appr.= '<span class="text-success"><i>'.ucwords($row->approved_by).'</i></span>';
                    $appr.= '<br>';
					if($row->approved_date!='' && $row->approved_date!=null){
                        $label      = date(get_date_format(), strtotime($row->approved_date));
                        $data_sort  = date('Y-m-d', strtotime($row->approved_date));
                    	$appr.= '<span data-sort="'.$data_sort.'" class="text-muted"><i>'. $label.'</i></span>';
					}	
					if($row->approved_time!='' && $row->approved_time!=null){
						$appr.= '<br>';
						$appr.= '<span class="text-muted"><i>'. $row->approved_time.'</i></span>';
					}
                }   
                
                return $appr;
            })
            ->addIndexColumn()
            ->addColumn('from_date', function($row){
                $label      = date(get_date_format(), strtotime($row->date_start));
                $data_sort  = date('Y-m-d', strtotime($row->date_start));
                $response   = '<label data-sort="'.$data_sort.'">'.$label.'</label>';
                return $response;
            })
			->addIndexColumn()
            ->addColumn('to_date', function($row){
                $label      = date(get_date_format(), strtotime($row->date_end));
                $data_sort  = date('Y-m-d', strtotime($row->date_end));
                $response   = '<label data-sort="'.$data_sort.'">'.$label.'</label>';
                return $response;
            })
            ->addIndexColumn()
            ->addColumn('action', function($row){
                $url_edit = url('leaves/' . $row->lr_id . '/edit');
				$action="";
				if((check_permission(Auth::user()->Employee->department_id,"Leave Request","full")) || (check_permission(Auth::user()->Employee->department_id,"Leave Request","Edit"))){
                $action = '<td><button type="button" onclick="window.location.href=\''.$url_edit.'\'" class="btn btn-outline-secondary btn-rounded btn-icon btn-sm"><i style="margin-left: -6px;" class="mdi mdi-lead-pencil"></i></button> </td>';
				}
				if((check_permission(Auth::user()->Employee->department_id,"Leave Request","full")) || (check_permission(Auth::user()->Employee->department_id,"Leave Request","delete"))){
                $action .= '<td><button type="button" data-toggle="modal" data-id="'.$row->lr_id.'" data-target="#DeleteModal" class="btn btn-outline-secondary btn-rounded btn-icon btn-sm btn-delete_row ml-4"><i style="margin-left: -7px;" class="mdi mdi-delete"></i></button></td>';
				}
                return $action;
            })
            ->rawColumns(['id','name','state_status','approved_by','from_date', 'to_date', 'action'])
            ->make(true);
        
        return $data_tables;
    }

    public function requestUpdate(Request $request, $id)
    {
        // dd($request);

        $this->validate($request,[
            'comments' => 'required',
            'state_status' => 'required',
            'select_file' => 'image|nullable|max:1999'
            
        ]);
        //Handle file upload
        if($request->hasFile('select_file')){
            $leave_file_to_unlink = LeaveRequest::findOrFail($id);
            $old_filepath = $leave_file_to_unlink->filepath;

            $filenameWithExt = $request->file('select_file')->getClientOriginalName();
            //Get just filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            //Get just ext
            $extension = $request->file('select_file')->getClientOriginalExtension();
            //File to store
            $fileNameToStore = $filename. '_' . time() . '.' . $extension;
            //Upload Image
            $path = $request->file('select_file')->move(public_path('images/files/'), $fileNameToStore);

            if($path){
                if($old_filepath != null){
                    
                    if($_SERVER['REMOTE_ADDR'] == '127.0.0.1'){
                        $old_path_check = 'images/files/'.$old_filepath;
                    }else{
                        $old_path_check = 'public/images/files/'.$old_filepath;
                    }

                    if(file_exists($old_path_check)){
                        unlink($old_path_check);
                    }
                }

            }

        }
        $leave = LeaveRequest::findOrFail($id);
        $leave->comments = $request->comments;

        if($request->state_status == 'Approved'){
            $get_current_employee   = Employee::where('user_id', auth()->user()->id)->first();
            $leave->approved_by     = ucwords($get_current_employee->first_name).' '.ucwords($get_current_employee->last_name);
            $leave->approved_date   = date("Y-m-d");
            $leave->approved_time   = date("H:i:s");
        }else{
            $leave->approved_by     = null;
            $leave->approved_date   = null;   
        }
		if($request->input('half_day')){
			$leave->half_day = $request->input('half_day');
		}
		else
		{
			$leave->half_day = 0;
		}
        $leave->reason = $request->reason;
        if($request->hasFile('select_file')){
            $leave->filepath = $fileNameToStore;
        }

        $leave->state_status = $request->state_status;

        $leave->save();
        
        return  redirect('leaves/filter/result')->with('success', 'Leave request successfully updated');
    }

    public function resultlist()
    {
        return view('leaves_request.list_result');
    }
    public function requestEdit(Request $request, $id)
    {
        $leave_data = DB::table('leaves')->get();
        $editleaverequest = LeaveRequest::findOrFail($id);
        $leavesrequest = LeaveRequest::latest()->paginate(20);
        $user_id = auth()->user()->id;
        $state_status = array('Pending','Approved','Rescheduled','Declined');
        $today = date("Y-m-d");
        $leavesrequest = LeaveRequest::latest()->paginate(20);
        $leave_list = LeaveRequest::where('user_id',$user_id)->get();


        return view('leaves_request/edit', compact('editleaverequest','leavesrequest','state_status', 'today', 'leave_data', 'leave_list')); 
    }

    public function requestdestroy($id)
    {
        $request_id = LeaveRequest::findOrfail($id);
        $request_id->delete();

        return redirect(route('leave.leavesearch'))->with('success', 'Successfully deleted');
    }


    public function requestLeave(Request $request)
    {
        $leave_data = DB::table('leaves')->get();
        $today = date("Y-m-d");
        $state_status = array('Pending','Approved','Rescheduled','Declined');
        $user = auth()->user();

        if($user == null)
        {
            return redirect('/home')->with('success', 'Please Login your account');
        }
        else 
        {
            return redirect(route('leave.leavesearch'));
            // $user_id = auth()->user()->id;
            // $leave_list = LeaveRequest::where('user_id',$user_id)->get();

            // return view('leaves_request/layout', compact('leave_data', 'today', 'state_status', 'user_id','leave_list'));
        }
    }

    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function requestPost(Request $request)
    {
        $this->validate($request,[
            'date_start' => 'required',
            'date_end' => 'required',
            'type' => 'required',
            'reason' => 'required',
            'select_file' => 'image|nullable|max:1999'
        ]);

        //Handle file upload
        if($request->hasFile('select_file')){
            $filenameWithExt = $request->file('select_file')->getClientOriginalName();
            //Get just filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            //Get just ext
            $extension = $request->file('select_file')->getClientOriginalExtension();
            //File to store
            $fileNameToStore = $filename. '_' . time() . '.' . $extension;
            //Upload Image
            $path = $request->file('select_file')->move(public_path('images/files/'), $fileNameToStore);
        }
        else{
            $fileNameToStore = null;    
        }
        //Create Post
        $leave = new LeaveRequest;
        $leave->user_id = auth()->user()->id;
        $leave->state_status = "Pending";
        $leave->date_filed = date("Y-m-d");
        $leave->date_start = \Carbon\Carbon::createFromFormat(get_date_format(),$request->input('date_start'))->format('Y-m-d');
        $leave->date_end = \Carbon\Carbon::createFromFormat(get_date_format(),$request->input('date_end'))->format('Y-m-d');
        $leave->type = $request->input('type');
        $leave->reason = $request->input('reason');
        $leave->filepath = $fileNameToStore;
		if($request->input('half_day')){
			$leave->half_day = $request->input('half_day');
		}
		else
		{
			$leave->half_day = 0;
		}
        $leave->save();
        
        Session::flash('leaves_request_type', $request->input('type'));
        Session::flash('leaves_request_state_status', 'Pending');
        Session::flash('leaves_request_user_id', auth()->user()->id);
        return redirect(route('leave.leavesearch'))->with('success', 'Leave successfully created');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       
        $validatedData = $request->validate([
            'name' => 'required|max:255',
        ]);

        Leave::create($validatedData);

        return redirect('leaves')->with('success', 'Leave successfully created');
        
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
        $editleave = Leave::findOrFail($id);
        $leaves = Leave::latest()->paginate(20);

     
        return view('settings.leavelist', compact('editleave', 'leaves')); 
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
        $leave = Leave::findOrFail($id);
        $leave->name = $request->name;   
        $leave->save();
        
        return redirect()->back()->with('success', 'Successfully updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $leave = Leave::findOrFail($id);
        $leave->delete();

        return redirect('leaves')->with('success', 'Successfully deleted');
    }
}