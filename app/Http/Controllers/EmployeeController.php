<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Employee;
use Illuminate\Support\Facades\Hash;
use App\User;
use App\Role;
use DataTables;
use Illuminate\Support\Facades\DB;
use PDF;
use Auth;
use Session;
use App\Country;
use App\EmployeeBanks;
use Illuminate\Support\Facades\Validator;

class EmployeeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            // user id
            $this->user = Auth::user()->id;
            $this->page = 'EMPLOYEES';
            return $next($request);
        });
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $employees = Employee::all();

        // return view('employees.index', compact('employees'));
  
        $employees = Employee::latest()->paginate(20);

        $filters = Session::get("empfilters");
        $from_emp_list = Session::get("from_emp_list");

        return view('employees.index',compact('employees'))
            ->with([
                'employenum' => Session::get("employeid"),
                'filterdata' => $filters,
                'i' => (request()->input('page', 1) - 1) * 5,
                'from_emp_list' => $from_emp_list
            ]);
    }
    
	public function emp_list(Request $request)
    {
		$department_id = $request->department_id;
        $employment_status_id = $request->employment_status_id;
        $is_active = $request->is_active;
		$date_range = $request->date_range;
		$filters = [];
        if($department_id!=''){
         	$filters['department_id'] = $department_id;
        }
		if($employment_status_id!=''){
         	$filters['employment_status_id'] = $employment_status_id;
        } 
		if($is_active!=''){
         	$filters['is_active'] = $is_active;
        } 
		$data = Employee::where($filters)->latest()->get();
		if($date_range!='')
		{
			$date_array  = explode(" - ",$date_range);
			$from = date('Y-m-d',strtotime(str_replace("-","/",$date_array[0])));
			$to = date('Y-m-d',strtotime(str_replace("-","/",$date_array[1])));
			$data = Employee::where($filters)->whereBetween('date_hired',[$from,$to])->latest()->get();
		}
        $datatables =  Datatables::of($data)
					->addIndexColumn()
                    ->addColumn('department', function($row){
   
                            return ($row->department->name)??"";
                    })
					->addIndexColumn()
                    ->addColumn('emp_satatus', function($row){
   
                            return ($row->employment_status->name)??"";
                    })
					->addIndexColumn()
                    ->addColumn('emp_image', function($row){
						if(file_exists(public_path().'/images/faces/'.$row->employee_image) && !is_dir(public_path().'/images/faces/'.$row->employee_image) && $row->employee_image!=''){
   							$img = '<a href="'.url('employees/' . $row->id ).'"><img src="'.asset('/public/images/faces/' . $row->employee_image).'" alt="image"></a>';
						}	
						else
						{
							$img = '<a href="'.url('employees/' . $row->id ).'"><img src="'.asset('/public/images/default-user.png').'" alt="image"></a>';
						}	
                            return $img;
                    })
					->addIndexColumn()
                    ->addColumn('active_status', function($row){
							if( $row->is_active == 1 ){
								$status = '<a href="'.url('users/' . $row->id . '/edit').'"><div class="badge badge-success badge-pill">&nbsp;&nbsp;YES&nbsp;&nbsp;</div></a>';
							}			
							else{
								$status = '<a href="'.url('users/' . $row->id . '/edit').'"><div class="badge badge-danger badge-pill">&nbsp;&nbsp;NO&nbsp;&nbsp;</div></a>';
							}	
							
                            return $status;
                    })
					->addIndexColumn()
                    ->addColumn('action', function($row){
						  $btn = '<a href="'.url('employees/' . $row->id . '/edit').'"><i class="mdi mdi-lead-pencil"></i></a>';

                   		  $btn = $btn.'<a href="javascript:;" data-toggle="modal" onclick="deleteData('.$row->id.')" data-target="#DeleteModal"><i class="mdi mdi-delete"></i></a>';
    
                          return $btn;
                    })
                    ->rawColumns(['action','emp_image','emp_satatus','department','active_status'])
					->make(true);
		return $datatables;
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $employee_number = date('ymd') . 1;
        $employee = Employee::orderBy('created_at', 'desc')->first();
        $employee_inc = $employee->id ?? 0;
        $employee_number = date('ymd') . $employee_inc + 1;
        $countries = Country::orderBy('country_name', 'asc')->get(array('country_name', 'id'));
        // dd($countries);
       
        // return view('employees.create', compact('employee_number', 'countries','hiddenInput'));
        return view('employees.create', compact('employee_number', 'countries')); //remove the hiddenInput
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {	
		  $v = Validator::make($request->all(), [
				'first_name'    =>  'required',
            	'last_name'     =>  'required',
           		'email' => 'required|string|email|max:255|unique:users,email|unique:employees,email',
            	'employee_image'  => 'image|mimes:jpg,JPG,jpeg,JPEG,png,PNG,gif,GIF|max:2048',
            	'work_agreement'  => 'mimes:doc,docx,pdf|max:10240',
            	'employee_cv'  => 'mimes:doc,docx,pdf|max:10240',
			  	'department_id' => 'required',
            	'personal_allowance' => 'nullable|numeric',
            	'food_allowance' => 'nullable|numeric',
            	'transportation_allowance' => 'nullable|numeric'
			]);

			if ($v->fails())
			{
				$ext_array= array('jpg','JPG','jpeg','JPEG','png','PNG','gif','GIF');
				if($request->file('employee_image') != null){
					$image = $request->file('employee_image');
					if(in_array($image->getClientOriginalExtension(),$ext_array)){
						$new_name = $request->employee_number . '.' . $image->getClientOriginalExtension();
						$image->move(public_path('images/faces/'), $new_name);  
						Session::put('last_image',asset('/images/faces/'.$new_name));
					}	
				}
				return redirect()->back()->withErrors($v->errors())->withInput();
			}


        $new_name = "";
        if ($request->file('employee_image') != null){
			if(Session::get('last_image') != ''){
				$image_array = explode("/",Session::get('last_image'));
				unlink(public_path('images/faces/').end($image_array));
				Session::forget('last_image');
			}
            $image = $request->file('employee_image');
            $new_name = $request->employee_number . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/faces/'), $new_name);  
			
        }
		if($request->file('employee_image') == null && Session::get('last_image') != '')
		{	
			$image_array = explode("/",Session::get('last_image'));
			$new_name = end($image_array);
			Session::forget('last_image');
		}
		
        $employee                                   = new Employee();
        $employee->user_id                          = 1;
        $employee->employee_number                  = $request->employee_number;
        $employee->employee_image                   = $new_name;
        $employee->last_name                        = $request->last_name;
        $employee->first_name                       = $request->first_name;
        $employee->middle_name                      = $request->middle_name;
        $employee->gender                           = $request->gender;
        $employee->birthdate                        = date("Y-m-d",strtotime(str_replace("-","/",$request->birthdate)));
        $employee->civil_status                     = $request->civil_status;
        $employee->address                          = $request->address;
        $employee->city                             = $request->city;
        $employee->zipcode                          = $request->zipcode;
        $employee->email                            = $request->email;
        $employee->personal_phone                   = $request->personal_phone;
        $employee->home_phone                       = $request->home_phone;
        $employee->basic_salary                     = str_replace("$","", str_replace(",","", $request->basic_salary));
        $employee->payment_schedule                 = $request->payment_schedule;
        $employee->hmo                              = $request->hmo;
        $employee->employment_status_id             = $request->employment_status_id;
        $employee->position                         = $request->position;
        $employee->department_id                    = $request->department_id;
        $employee->date_hired                       = date("Y-m-d",strtotime(str_replace("-","/",$request->date_hired)));
        $employee->is_active                        = 1; 
        $employee->tax_status                       = $request->tax_status;
        $employee->personal_allowance               = $request->personal_allowance;
        $employee->food_allowance                   = $request->food_allowance;
        $employee->transportation_allowance         = $request->transportation_allowance;
        $employee->sss_number                       = $request->sss_number;
        $employee->tin_number                       = $request->tin_number;
        $employee->pagibig_number                   = $request->pagibig_number;
        $employee->philhealth_number                = $request->philhealth_number;
        $employee->number_of_dependents             = $request->number_of_dependents;
        $employee->dependent1                       = $request->dependent1;
        $employee->dependent2                       = $request->dependent2;
        $employee->dependent3                       = $request->dependent3;
        $employee->dependent4                       = $request->dependent4;
        $employee->dependent1_bday                  = date("Y-m-d",strtotime(str_replace("-","/",$request->dependent1_bday)));
        $employee->dependent2_bday                  = date("Y-m-d",strtotime(str_replace("-","/",$request->dependent2_bday)));
        $employee->dependent3_bday                  = date("Y-m-d",strtotime(str_replace("-","/",$request->dependent3_bday)));
        $employee->dependent4_bday                  = date("Y-m-d",strtotime(str_replace("-","/",$request->dependent4_bday)));
        $employee->dependent1_rel                   = $request->dependent1_rel;
        $employee->dependent2_rel                   = $request->dependent2_rel;
        $employee->dependent3_rel                   = $request->dependent3_rel;
        $employee->dependent4_rel                   = $request->dependent4_rel;
        $employee->contact_emergency_name           = $request->contact_emergency_name;
        $employee->contact_emergency_phone          = $request->contact_emergency_phone;
        $employee->contact_emergency_rel            = $request->contact_emergency_rel;
        $employee->contact_emergency_addr           = $request->contact_emergency_addr;
        // $employee->bank_name                        = $request->bank_name;
        // $employee->bank_account_number              = $request->bank_account_number;
        $employee->vacation_leave                   = $request->vacation_leave;
        $employee->sick_leave                       = $request->sick_leave;
        $employee->country_id                       = $request->country;
        $employee->extra_address                    = $request->extra_address;
        $employee->state                            = $request->state;
        $employee->account_type                            = $request->account_type;
		$path = public_path('documents/'.$request->employee_number.'/');

		if(!File::isDirectory($path)){
			File::makeDirectory($path, 0777, true, true);
		}
		 $work_agreement = null;
        if ($request->file('work_agreement') != null){
            $image = $request->file('work_agreement');
            $work_agreement = 'Work-agreement-'.$request->employee_number . '.' . $image->getClientOriginalExtension();
            $image->move($path, $work_agreement);   
            $employee->work_agreement  = $work_agreement;
        }
		 $employee_cv = null;
        if ($request->file('employee_cv') != null){
            $image = $request->file('employee_cv');
            $employee_cv = 'Employee-CV-'.$request->employee_number . '.' . $image->getClientOriginalExtension();
            $image->move($path, $employee_cv);   
            $employee->employee_cv  = $employee_cv;
        }

        $employee->save();    
        
        $users = User::all();
        $users_array = [];
        foreach ($users as $key => $value){
            $users_array[] = $value->employee_id;
        }

        if (in_array($employee->id, $users_array)) {
            return redirect('employees/search')->with('error', 'User already exists');    
        }

        // Register the user
        $data = [
            'name' => $employee->first_name . ' ' . $employee->last_name,
            'email' => $employee->email,
            'employee_id' => $employee->id,
            'password' => Hash::make('secret'), //Hash::make($employee->email),
        ];
        
        if ($employee){
            $user = new User;
            $row = $user->create($data);
        }

        $employee->user_id = $row->id;

        $employee_bank                      = new EmployeeBanks();
        $employee_bank->bank_name           = $request->bank_name;
        $employee_bank->account_number      = $request->account_number;
        $employee_bank->address             = $request->bank_address;
        $employee_bank->extra_address       = $request->bank_extra_address;
        $employee_bank->city                = $request->bank_city;
        $employee_bank->state               = $request->bank_state;
        $employee_bank->zipcode             = $request->bank_zipcode;
        $employee_bank->country_id          = $request->bank_country;
        $employee_bank->iban                = $request->bank_iban;
        $employee_bank->bic                 = $request->bank_bic;
        $employee_bank->member_no           = $request->bank_member_no;
        $employee_bank->clearing_no         = $request->bank_clearing_no;
        $employee_bank->save();

        $employee->employee_bank_id = $employee_bank->id;
        $employee->save();

        
        
        return redirect('employees/search')->with('success', 'Employee successfully created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $employee = Employee::findOrFail($id);
              
        return view('employees.profile',compact('employee'));         
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {        
        $to_select = array(
            'employees.*', //select all fields in employee

            // select all employee bank information
            'eb.bank_name',
            'eb.account_number',
            'eb.bank_name',
            'eb.member_no as bank_member_no',
            'eb.address as bank_address',
            'eb.extra_address as bank_extra_address',
            'eb.zipcode as bank_zipcode',
            'eb.city as bank_city',
            'eb.country_id as bank_country_id',
            'eb.iban as bank_iban',
            'eb.bic as bank_bic',
            'eb.clearing_no as clearing_no',
            'eb.iban as bank_iban',
        );

        $employee = Employee::where('employees.id', $id)
        ->leftJoin('employee_banks as eb', 'eb.id', '=', 'employees.employee_bank_id')
        ->firstOrFail($to_select);
        // dd($employee);

        $imagepath = $employee->employee_image;
        $directoryPath = 'images/faces/'.$imagepath;
        // dd($directoryPath);
        $isImage = false;
        if(File::exists(public_path($directoryPath))){
            $isImage = true;
        }

        $filters = Session::get("empfilters");

        if(is_array($filters)){ 
            Session::flash("empfilters", $filters);
        } 
        Session::flash("from_emp_list", 1);

        $countries = Country::orderBy('country_name', 'asc')->get(array('country_name', 'id'));

        return view('employees.edit',compact('employee', 'isImage', 'countries')); 
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
        // dd($request->birthdate);

        // dd($request);
       
        $employee = Employee::findOrFail($id);
        $user     = User::findOrFail($employee->user_id);
     
		$v = Validator::make($request->all(), [
			'first_name'    =>  'required',
			'last_name'     =>  'required',
			'email' => 'required|string|email|max:255|unique:users,email,'.$employee->user_id.'|unique:employees,email,'.$employee->id,
			'employee_image'  => 'image|mimes:jpg,JPG,jpeg,JPEG,png,PNG,gif,GIF|max:2048',
			'work_agreement'  => 'mimes:doc,docx,pdf|max:10240',
            'employee_cv'  => 'mimes:doc,docx,pdf|max:10240',
			'personal_allowance' => 'nullable|numeric',
			'department_id' => 'required',
			'food_allowance' => 'nullable|numeric',
			'transportation_allowance' => 'nullable|numeric'
		]);

		if ($v->fails())
		{
			$ext_array= array('jpg','JPG','jpeg','JPEG','png','PNG','gif','GIF');
			if($request->file('employee_image') != null){
				$image = $request->file('employee_image');
				if(in_array($image->getClientOriginalExtension(),$ext_array)){
					$new_name = $request->employee_number . '.' . $image->getClientOriginalExtension();
					$image->move(public_path('images/faces/'), $new_name);  
					$employee->employee_image = $new_name;
					$employee->save();    
				}	
			}
			return redirect()->back()->withErrors($v->errors())->withInput();
		}
		
        $new_name = null;
        if ($request->file('employee_image') != null){
            $image = $request->file('employee_image');
            $new_name = $request->employee_number . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/faces/'), $new_name);   
            $employee->employee_image                   = $new_name;
        }
		$path = public_path('documents/'.$employee->employee_number.'/');

		if(!File::isDirectory($path)){
			File::makeDirectory($path, 0777, true, true);
		}
		 $work_agreement = null;
        if ($request->file('work_agreement') != null){
            $image = $request->file('work_agreement');
            $work_agreement = 'Work-Agreement-'.$request->employee_number . '.' . $image->getClientOriginalExtension();
            $image->move($path, $work_agreement);   
            $employee->work_agreement                = $work_agreement;
        }
		 $employee_cv = null;
        if ($request->file('employee_cv') != null){
            $image = $request->file('employee_cv');
            $employee_cv = 'Employee-CV-'.$request->employee_number . '.' . $image->getClientOriginalExtension();
            $image->move($path, $employee_cv);   
            $employee->employee_cv                   = $employee_cv;
        }
        // else if($request->file('xemployee_image') != null){
        //     $image = $request->file('xemployee_image');
        //     $new_name = $request->employee_number . '.' . $image->getClientOriginalExtension();
        //     $image->move(public_path('images/faces/'), $new_name);   
        //     $employee->employee_image                   = $new_name;
        // }


        if($employee->employee_bank_id != ''){
            $employee_bank = EmployeeBanks::findOrFail($employee->employee_bank_id);
        }else{
            $employee_bank = new EmployeeBanks();
        }
        $employee_bank->bank_name           = $request->bank_name;
        $employee_bank->account_number      = $request->account_number;
        $employee_bank->address             = $request->bank_address;
        $employee_bank->extra_address       = $request->bank_extra_address;
        $employee_bank->city                = $request->bank_city;
        $employee_bank->state               = $request->bank_state;
        $employee_bank->zipcode             = $request->bank_zipcode;
        $employee_bank->country_id          = $request->bank_country;
        $employee_bank->iban                = $request->bank_iban;
        $employee_bank->bic                 = $request->bank_bic;
        $employee_bank->member_no           = $request->bank_member_no;
        $employee_bank->clearing_no         = $request->bank_clearing_no;
        $employee_bank->save();

        if($employee->employee_bank_id == ''){
            $employee->employee_bank_id = $employee_bank->id;
        }

        $employee->employee_number                  = $request->employee_number;
        $employee->last_name                        = $request->last_name;
        $employee->first_name                       = $request->first_name;
        $employee->middle_name                      = $request->middle_name;
        $employee->gender                           = $request->gender;
        $employee->birthdate                        = date("Y-m-d",strtotime(str_replace("-","/",$request->birthdate)));
        $employee->civil_status                     = $request->civil_status;
        $employee->address                          = $request->address;
        $employee->city                             = $request->city;
        $employee->zipcode                          = $request->zipcode;
        $employee->email                            = $request->email;
        $employee->personal_phone                   = $request->personal_phone;
        $employee->home_phone                       = $request->home_phone;
        $employee->basic_salary                     = str_replace("$","", str_replace(",","", $request->basic_salary));
        $employee->payment_schedule                 = $request->payment_schedule;
        $employee->hmo                              = $request->hmo;
        $employee->employment_status_id             = $request->employment_status_id;
        $employee->position                         = $request->position;
        $employee->department_id                    = $request->department_id;
        $employee->date_hired                       = date("Y-m-d",strtotime(str_replace("-","/",$request->date_hired)));
        // $employee->is_active                        = (boolean)$request->is_active;
        $employee->tax_status                       = $request->tax_status;
        $employee->personal_allowance               = str_replace("$","", str_replace(",","", $request->personal_allowance));
        $employee->food_allowance                   = str_replace("$","", str_replace(",","", $request->food_allowance));
        $employee->transportation_allowance         = str_replace("$","", str_replace(",","", $request->transportation_allowance));
        $employee->sss_number                       = $request->sss_number;
        $employee->tin_number                       = $request->tin_number;
        $employee->pagibig_number                   = $request->pagibig_number;
        $employee->philhealth_number                = $request->philhealth_number;
        $employee->number_of_dependents             = $request->number_of_dependents;
        $employee->dependent1                       = $request->dependent1;
        $employee->dependent2                       = $request->dependent2;
        $employee->dependent3                       = $request->dependent3;
        $employee->dependent4                       = $request->dependent4;
        $employee->dependent1_bday                  = date("Y-m-d",strtotime(str_replace("-","/",$request->dependent1_bday)));
        $employee->dependent2_bday                  = date("Y-m-d",strtotime(str_replace("-","/",$request->dependent2_bday)));
        $employee->dependent3_bday                  = date("Y-m-d",strtotime(str_replace("-","/",$request->dependent3_bday)));
        $employee->dependent4_bday                  = date("Y-m-d",strtotime(str_replace("-","/",$request->dependent4_bday)));
        $employee->dependent1_rel                   = $request->dependent1_rel;
        $employee->dependent2_rel                   = $request->dependent2_rel;
        $employee->dependent3_rel                   = $request->dependent3_rel;
        $employee->dependent4_rel                   = $request->dependent4_rel;
        $employee->contact_emergency_name           = $request->contact_emergency_name;
        $employee->contact_emergency_phone          = $request->contact_emergency_phone;
        $employee->contact_emergency_rel            = $request->contact_emergency_rel;
        $employee->contact_emergency_addr           = $request->contact_emergency_addr;
        $user->email                                = $request->email;
        // $employee->bank_name                        = $request->bank_name;
        $employee->vacation_leave                   = $request->vacation_leave;
        $employee->sick_leave                       = $request->sick_leave;
        $employee->country_id                       = $request->country;
        $employee->extra_address                    = $request->extra_address;
        $employee->state                            = $request->state;
        $employee->account_type                     = $request->account_type;
        $user->save();
        $employee->save();      
    
        // return redirect('employees');


        Session::flash("employeid", $employee->employee_number);

        return redirect('employees/search')->with('success', 'Successfully updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
		
        $employee = Employee::findOrFail($id);
		$user = User::findOrFail($employee->user_id);
        $employee->delete();
		$user->delete();

        return redirect('employees/search')->with('success', 'Successfully deleted');
    }
    public function employeeSearchView()
    {
        return view('employees.search');
    }
    
    public function empSearch(Request $request)
    {
        $emp_num = $request->employee_number;
        $fname = $request->first_name;
        $lname = $request->last_name;
		$department_id = $request->department_id;
        $employment_status_id = $request->employment_status_id;
        $is_active = $request->is_active;
        $not_active = $request->not_active;
		$date_range = $request->date_hired;

        $postbtn = $request->postbtn;
        $from_emp_list = $request->from_emp_list;

        $filters = [];

        if($emp_num!=''){
            $filters['employee_number'] = $emp_num;
        }
        if($fname!=''){
            $filters['first_name'] = $fname;
        }
        if($lname!=''){
            $filters['last_name'] = $lname;
        }
        if($department_id!=''){
         	$filters['department_id'] = $department_id;
        }
		if($employment_status_id!=''){
         	$filters['employment_status_id'] = $employment_status_id;
        } 
		if($is_active!=''){
            $filters['is_active'] = $is_active;
        }
		// if(Auth::user()->employee->account_type == 0){
        //     $filters['id'] = Auth::user()->employee->id;
        // }
        if($postbtn)
        {
            Session::put("empfilters", $filters);
        }elseif($from_emp_list){
          Session::flash("empfilters", $filters);
        }else
        {
            Session::forget("empfilters");
        }

		// $data = Employee::where($filters)->latest()->get();
		if($date_range!='')
		{
			// $date_array  = explode(" - ",$date_range);
			// $from = date('Y-m-d',strtotime($date_array[0]));
			// $to = date('Y-m-d',strtotime($date_array[1]));
            // $data = Employee::where($filters)->whereBetween('date_hired',[$from,$to])->latest()->get();
            $filters['date_hired'] =  date("Y-m-d",strtotime(str_replace("-","/",$date_range)));
        }
        
		$data = Employee::where($filters)->latest()->get();

        return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('employee_number', function($row){
						if((check_permission(Auth::user()->Employee->department_id,"employees","full")) || (check_permission(Auth::user()->Employee->department_id,"employees","Edit"))){
                            return '<a href="'.url('employees/' . $row->id . '/edit').'">'.$row->employee_number.'</a>';
						}
						else{
							  return $row->id;
						}
                    })
                    ->addIndexColumn()
                    ->addColumn('first_name', function($row){
                            return $row->first_name;
                    })
                    ->addIndexColumn()
                    ->addColumn('last_name', function($row){
                            return $row->last_name;
                    })
					->addIndexColumn()
                    ->addColumn('emp_status', function($row){
   
                            return ($row->employment_status->name)??"";
                    })
					->addIndexColumn()
                    ->addColumn('emp_image', function($row){
						if(file_exists(public_path().'/images/faces/'.$row->employee_image) && !is_dir(public_path().'/images/faces/'.$row->employee_image) && $row->employee_image!=''){
   							$img = '<a href="'.url('employees/' . $row->id  ).'"><img src="'.get_profile_picture($row->user_id).'" alt="image"></a>';
						}	
						else
						{
							$img = '<a href="'.url('employees/' . $row->id ).'"><img src="'.get_profile_picture($row->user_id).'" alt="image"></a>';
						}	
                            return $img;
                    })
					->addIndexColumn()
                    ->addColumn('active_status', function($row){
						if((check_permission(Auth::user()->Employee->department_id,"employees","full")) || (check_permission(Auth::user()->Employee->department_id,"employees","Edit"))){
							if( $row->is_active == 1 ){
								$status = '<a href="'.url('users/' . $row->id . '/edit').'"><div class="badge badge-success badge-pill">&nbsp;&nbsp;YES&nbsp;&nbsp;</div></a>';
							}			
							else{
								$status = '<a href="'.url('users/' . $row->id . '/edit').'"><div class="badge badge-danger badge-pill">&nbsp;&nbsp;NO&nbsp;&nbsp;</div></a>';
							}	
							
                            return $status;
						}
                    })
					->addIndexColumn()
                    ->addColumn('action', function($row){
                            $link = url('employees/' . $row->id . '/edit');

                            $btn = '<div class="row col-sm">';
                                if((check_permission(Auth::user()->Employee->department_id,"employees","full")) || (check_permission(Auth::user()->Employee->department_id,"employees","Edit"))){
                                $btn .= '<div class="col">';
                                    $btn .= '<button type="button" onclick="window.location.href=\' '.$link.' \'" class="btn btn-outline-secondary btn-rounded btn-icon btn-sm"><i class="mdi mdi-lead-pencil" style="margin-left: -6px;"></i></button>';
                                $btn .= '</div>';
								}
								if((check_permission(Auth::user()->Employee->department_id,"employees","full")) || (check_permission(Auth::user()->Employee->department_id,"employees","delete"))){
                                $btn .= '<div class="col">';
                                    $btn .= '<button type="submit" href="javascript:void();" data-toggle="modal" onclick="deleteData( '.$row->id  .')" data-target="#DeleteModal" class="btn btn-outline-secondary btn-rounded btn-icon btn-sm"><i class="mdi mdi-delete" style="margin-left: -6px;"></i></button>';
                                $btn .= '</div>';
								}
						    
                            $btn .= '</div>';

                            // $btn = '<a href="'.url('employees/' . $row->id . '/edit').'"><i class="mdi mdi-lead-pencil"></i></a>';
                       		// $btn = $btn.'<a href="javascript:;" data-toggle="modal" onclick="deleteData('.$row->id.')" data-target="#DeleteModal"><i class="mdi mdi-delete"></i></a>';
    
                          return $btn;
                    })
                    ->rawColumns(['emp_image','employee_number','first_name','last_name','emp_status', 'active_status', 'action'])
					->make(true);
    }
	public function generatePDF($emp_id)
    {
		$employee = Employee::findOrFail($emp_id);
        $data = ["employee"=>$employee];
        $pdf = PDF::loadView('employees.emppaystub', $data);
  
        return $pdf->download('EMP-paystub.pdf');
		//return view('employees.emppaystub',$data);
    }
	public function generatePDF1($emp_id)
    {
		$employee = Employee::findOrFail($emp_id);
        $data = ["employee"=>$employee];
        $pdf = PDF::loadView('employees.emppayroll', $data);
  
        return $pdf->download('EMP-payroll.pdf');
		//return view('employees.emppayroll',$data);
    }
}