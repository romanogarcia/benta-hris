<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Employee;
use App\Department;
use App\Companies;
use DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Auth;
use Validator;
use Session;
use Illuminate\Validation\Rule;
use DataTables;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		$rows = User::orderBy('name', 'ASC')->get();        
		$employees = Employee::orderBy('first_name', 'ASC')->get();
		$departments = Department::orderBy('name', 'ASC')->get();
        return view('users.index',compact('rows', 'employees', 'departments'));
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
        
        $user = User::findOrFail($id);
		$employee = Employee::where('user_id', $id)->firstOrFail();
		$departments = Department::orderBy('name', 'ASC')->get();
		$company = Companies::first();
		return view('users.edit', compact('user', 'employee', 'departments','company')); 
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
	
	public function settings()
    {
        $rows = User::orderBy('name', 'ASC')->get();        

        return view('users.view_settings',compact('rows'));
    }
	
   public function update(Request $request,$id)
    {
		// dd($request);
		$employee 			     = Employee::where('user_id', $id)->firstOrFail();
		$user 			         = User::findOrFail($id);
		$validation = ['profile_photo'  => 'image|mimes:jpg,JPG,jpeg,JPEG,png,PNG,gif,GIF|max:2048',
			"email" => [
				"required",
				Rule::unique('users', 'email')->ignore($id),
				Rule::unique('employees', 'email')->ignore($employee->id)
			],
			"name" => [
				"required"
			],];
	   if($request->phone!=''){
		$validation["phone"] = 'regex:/^([0-9\s\-\+\(\)]*)$/|min:7';
	   }   
	   if($request->username!=''){
			$validation["username"] = Rule::unique('users', 'username')->ignore($id);
	   }   
		$validate = Validator::make($request->all(), $validation);
		$validate->after(function ($validate) use ($request, $user){
			if (!preg_match('/^\S*$/u', $request->username))
			{
				return $validate->errors()->add('username', 'White space not allowed in username.');
			}
		});
		if($validate->fails()){
			return back()->withErrors($validate)->withInput();
		}

		$new_name = "";
       
		 if ($request->file('profile_photo') != null){
			if($user->employee['employee_image']!='' && file_exists(public_path().'/images/faces/'.$user->employee['employee_image']) && !is_dir(public_path().'/images/faces/'.$user->employee['employee_image'])){
				@unlink(public_path().'/images/faces/'.$user->employee['employee_image']);
			}
            $image = $request->file('profile_photo');
            $new_name = $user->employee['employee_number'] . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/faces/'), $new_name);  
		}
		
			// $user->language = $request->language;
			// $user->locale = $request->locale;
			$user->email 				= $request->email;
			$user->name 				= $request->name;
			$user->username 			= $request->username;
	   		if($request->is_locked){
				$user->is_locked 	= $request->is_locked;
			}
	   		else{
				$user->is_locked 	= 0;
			}
			$user->save();

			$employee->personal_phone 	= $request->phone;
			$employee->email 			= $request->email;
			if($new_name != ''){
				$employee->employee_image = $new_name;
			}
			$employee->save();

		Session::flash('users_state_user_id', $employee->user_id);
		Session::flash('users_state_department_id', $employee->department_id);
		return redirect('/users')->with('success', 'Successfully updated');
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

    public function isenable(Request $request, $id)
    {

        $user = User::findOrFail($id);
        dd($user);
        // if($user->active == 1){
        //     $user->active = 0;
        // } else {
        //     $user->active = 1;
        // }
    }
	
	
	
	/*
	public function update(Request $request)
    {
        $user = User::findOrFail($id);
       
        $user->language                  = $request->language;
        $user->locale                        = $request->locale;
        
        $user->save();      
    
        // return redirect('employees');
        return redirect('settings')->with('success', 'Successfully updated');
    }
	*/


	
	/*
	public function dummy()
	{
		DB::table('users')->insert([
            'employee_id' => 1,
            'name' => 'Admin',
            'email' => 'test@test.com',
            'password' =>  Hash::make('12345678'),
        ]);
        DB::table('employees')->insert([
            'user_id' => 1,
            'employee_number' => 1,
            'last_name' => 'Admin',
            'first_name' => 'Bentacos',
            'email' => 'test@test.com',
        ]);
        DB::table('departments')->insert([
            'name' => 'HUMAN RESOURCES',
        ]);
        DB::table('employment_statuses')->insert([
            'name' => 'REGULAR',
        ]);
        DB::table('leaves')->insert([
            'name' => 'VACATION LEAVE',
        ]);
	}
	*/
	function change_password(Request $request)
	{ 
		if(Auth::check())
		{
			$user = Auth::user();
            $company = Companies::first();
			$validate = Validator::make($request->all(), [
				'new_password' => 'required|min:8|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/',
				'confirm_password' => 'required|same:new_password',
			],
			[ 	  
            	'new_password.regex' => 'Password must contain Minimum 8 characters, at least one uppercase, one lowercase, one number and one special character.',
        	]);
			$validate->after(function ($validate) use ($request, $user,$company){
			$username = $user->username;
			$name = $user->name;
			$password = $request->new_password;
                if ((stripos($password, $name) !== false) || (stripos($password, $username) !== false)) {
                    return $validate->errors()->add('new_password', 'Personal Information are not allowed in Password ');
                }
                else
                {
                    $companies_name=explode(' ',$company->company_name);
                    foreach($companies_name as $com_name){
                    if (stripos($password, $com_name) !== false){
                        return $validate->errors()->add('new_password', 'Personal Information are not allowed in Password');
                    }
                }
            }
			});
			
			if($validate->fails()){
				return back()->withErrors($validate)->withInput();
			}
			else {
				$user->password = Hash::make($request->new_password);
				if($user->save()){
					return back()->with("success", "Password updated successfully!");;	
				}else
				{
					return back()->with("error", "something went wrong!");
				}
			}
		}
		else
		{
			return redirect('/');
		}
	}
	
	
	public function update_profile(Request $request)
	{
		$user = Auth::user();
		
		$validate = Validator::make($request->all(), [
			// 'full_name'    =>  'required',
            'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:7',
			'profile_photo'  => 'image|mimes:jpg,JPG,jpeg,JPEG,png,PNG,gif,GIF|max:2048',
			"email" => [
				"required",
				Rule::unique('users', 'email')->ignore($user->id),
				Rule::unique('employees', 'email')->ignore($user->employee_id)
			],
			"username" => [
				"required",
				Rule::unique('users', 'username')->ignore($user->id)
			],
		]);
		$validate->after(function ($validate) use ($request, $user){
			if (!preg_match('/^\S*$/u', $request->username))
			{
				return $validate->errors()->add('username', 'White space not allowed in username.');
			}
		});
		if($validate->fails()){
			return back()->withErrors($validate)->withInput();
		}
		$new_name = "";   
		 if ($request->file('profile_photo') != null){
			if($user->employee['employee_image']!='' && file_exists(public_path().'/images/faces/'.$user->employee['employee_image']) && !is_dir(public_path().'/images/faces/'.$user->employee['employee_image'])){
				@unlink(public_path().'/images/faces/'.$user->employee['employee_image']);
			}
            $image = $request->file('profile_photo');
            $new_name = $user->employee['employee_number'] . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/faces/'), $new_name);  
        }
        // $user->name = $request->full_name;
		// $user->language = $request->language;
		// $user->locale = $request->locale;
		$user->email		 		= $request->email;
		$user->username 			= $request->username;
	
		$user->save();

		$employee 					= $user->employee;
		$employee->personal_phone 	= $request->phone;
		$employee->email 			= $request->email;
		if($new_name!=''){
			$employee->employee_image = $new_name;
		}
		$employee->save();

		return redirect()->back()->with('success', 'Profile updated successfully changed.');
	}

	public function search_filter(Request $request){
		$user_id		= $request->user_id;
		$department_id	= $request->department_id;
		$is_active		= $request->is_active;

		$filters = [];

		if($user_id !='' ){
            $filters['user_id'] = $user_id;
		}
		if($department_id !='' ){
            $filters['department_id'] = $department_id;
		}
		if($is_active != ''){
            $filters['is_active'] = $is_active;
		}
		
		$to_select = array(
            'employees.first_name',
            'employees.last_name',
            'employees.email',
            'employees.is_active',
			'employees.user_id',
			'employees.employee_number',
			'departments.name as department'
		); 

		
		if(count($filters) <= 0){
			$data = Employee::join('departments', 'employees.department_id', '=', 'departments.id')
            ->get($to_select);
		}else{
			$data = Employee::join('departments', 'employees.department_id', '=', 'departments.id')
        	->where($filters)
            ->get($to_select);
		}


		$data_tables = Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('id', function($row){
				if((check_permission(Auth::user()->Employee->department_id,"User Management","full")) || (check_permission(Auth::user()->Employee->department_id,"User Management","Edit"))){
                $url_edit = route('users.edit', [$row->user_id]);
                $response = '<a href="'.$url_edit.'">'.$row->employee_number.'</a>';
                return $response;
				}else{
				return $row->employee_number;
				}
            })
            ->addIndexColumn()
            ->addColumn('name', function($row){
                return ucwords($row->first_name).' '.ucwords($row->last_name);
            })
            ->addIndexColumn()
            ->addColumn('email', function($row){
                return $row->email;
			})
			->addIndexColumn()
            ->addColumn('department', function($row){
                return $row->department;
			})
			->addIndexColumn()
            ->addColumn('is_active', function($row){
				
				if($row->is_active){
					$status = '<div class="badge badge-success badge-pill">&nbsp;&nbsp;YES&nbsp;&nbsp;</div>';
				}else{
					$status = '<div class="badge badge-danger badge-pill">&nbsp;&nbsp;NO&nbsp;&nbsp;</div>';
				}

                return $status;
			})

			->addIndexColumn()
            ->addColumn('action', function($row){
				$url_edit = route('users.edit', [$row->user_id]);
				$response="";
				if((check_permission(Auth::user()->Employee->department_id,"User Management","full")) || (check_permission(Auth::user()->Employee->department_id,"User Management","Edit"))){
				$response = '<button  type="button" class="btn btn-outline-secondary btn-rounded btn-icon btn-sm" onclick="window.location.href = \''.$url_edit.'\';"><i style="margin-left: -6px;" class="mdi mdi-lead-pencil"></i></button>';

				if($row->is_active){
					$url_action = route("user.deactivate_user", ":id");
					$response .= ' <button type="button" data-toggle="modal" title="Deactivate User" data-action="Deactivate" data-url="'.$url_action.'" data-id="'.$row->user_id.'" data-employee_id="'.$row->employee_number.'" data-target="#DeleteModal" class="btn btn-outline-secondary btn-rounded btn-icon btn-sm btn-delete_row ml-4"><i style="margin-left: -7px;" class="mdi mdi-lock"></i></button>';
				}else{
					$url_action = route("user.activate_user", ":id");
					$response .= ' <button type="button" data-toggle="modal" title="Activate User" data-action="Activate" data-url="'.$url_action.'" data-id="'.$row->user_id.'" data-employee_id="'.$row->employee_number.'" data-target="#DeleteModal" class="btn btn-outline-secondary btn-rounded btn-icon btn-sm btn-delete_row ml-4"><i style="margin-left: -7px;" class="mdi mdi-key"></i></button>';
				}
				}
				return $response;
			})
			

            ->rawColumns(['id','name','email','department','is_active', 'action'])
            ->make(true);
            
        return $data_tables;		
		
	}

	public function create_new_password(Request $request){
		$user = User::findOrFail($request->user_id);
        $company = Companies::first(); 
		$validate = Validator::make($request->all(), [
			'new_password' =>  'required|min:8|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/',
		],[ 	  
            'new_password.regex' => 'Password must contain Minimum 8 characters, at least one uppercase, one lowercase, one number and one special character.',
        ]); 
		$validate->after(function ($validate) use ($request, $user,$company){
			$username = $user->username;
			$name = $user->name;
			$password = $request->new_password;
			if (stripos($password, (string)$name) !== false || stripos($password, (string)$username) !== false) {
				return $validate->errors()->add('new_password', 'Personal Information are not allowed in Password');
			}
            else{
                $companies_name=explode(' ',$company->company_name);
                foreach($companies_name as $com_name){
                    if (stripos($password, $com_name) !== false){
                        return $validate->errors()->add('new_password', 'Personal Information are not allowed in Password');
                    }
                }
            }
		});
		if($validate->fails()){
			return back()->withErrors($validate)->withInput();
		}
		$user->password = Hash::make($request->new_password);
		$user->save();
		return redirect('/users')->with('success', 'Password successfully changed');
	}

	public function deactivate_user(Request $request, $id){
		$employee = Employee::where('user_id', $id)->firstOrFail();
		$employee->is_active = 0;
		$employee->save();
		
		return redirect('/users')->with('success', 'User successfully deactivated');
	}

	public function activate_user(Request $request, $id){
		$employee = Employee::where('user_id', $id)->firstOrFail();
		$employee->is_active = 1;
		$employee->save();

		return redirect('/users')->with('success', 'User successfully activated');
	}
}
