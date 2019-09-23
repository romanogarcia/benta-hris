<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Role;
use App\Department;
use DataTables;
use Validator;
use App\Page_Role;
use App\modules_table;
use App\Report;
use App\module_permission;
use DB;
use Auth;

class RoleController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        $data = Role::get();
        $roles = getRouteNames();
        $methods = getDefaultMethods();
        $departments = Department::orderBy('name', 'ASC')->get();

        //return view('roles.index',compact('data', 'roles', 'methods','departments'));
        return view('roles.departments-list',compact('data', 'roles', 'methods','departments'));
    }

    public function role_list(Request $request)
    {
        
        // $role_access      = $request->role_access;
        $department_id  = $request->department_id;
        // $is_active      = $request->is_active;
        // $role_name  = $request->role_name;

        // $filters = [];

        // if($role_access != ''){
        //     $filters['roles.role'] = $role_access;
        // }
        // if($department_id != ''){
        //     $filters['roles.department_id'] = $department_id;
        // }
        // if($role_name != ''){
        //     $filters['roles.role_name'] = $role_name;
        // }
        // if($is_active != ''){
        //     $filters['roles.is_active'] = $is_active;
        // }

        // $to_select = array(
        //     'roles.role',
        //     'roles.page',
        //     'roles.id as r_id',
        //     'roles.is_active',
        //     'roles.department_id',
        //     'roles.role_name',
        //     'departments.name as department_name'
        // );

        // if(count($filters) > 0){
        //     $data = Role::join('departments', 'roles.department_id', '=', 'departments.id')
        // 	->where($filters)
        //     ->get($to_select);
        // }else{
        //     $data = Role::join('departments', 'roles.department_id', '=', 'departments.id')
        //     ->get($to_select);
        // }

        if($department_id != ''){
            $data = Department::where('id', $department_id)
            ->orderBy('name', 'desc')
            ->get(array('id as r_id', 'name as department_name'));
        }else{
            $data = Department::orderBy('name', 'desc')
            ->get(array('id as r_id', 'name as department_name'));
        }

        $data_tables = Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('id', function($row){
				if((check_permission(Auth::user()->Employee->department_id,"Role Management","full")) || (check_permission(Auth::user()->Employee->department_id,"Role Management","Edit"))){
                $url_edit = route('role.edit_department', [$row->r_id]);
                $response = '<a href="'.$url_edit.'">'.$row->r_id.'</a>';
                return $response;
				}else{
					return $row->r_id;
				}
            })
            ->addIndexColumn()
            ->addColumn('department', function($row){
                return ucfirst($row->department_name);
            })
            // ->addIndexColumn()
            // ->addColumn('role_name', function($row){
            //     return ucfirst($row->role_name);
            // })
            // ->addIndexColumn()
            // ->addColumn('page', function($row){
            //     return ucfirst($row->page);
			// })
			// ->addIndexColumn()
            // ->addColumn('role', function($row){
            //     return ucfirst($row->role);
			// })
			// ->addIndexColumn()
            // ->addColumn('active', function($row){
				
			// 	if($row->is_active){
			// 		$status = '<div class="badge badge-success badge-pill">&nbsp;&nbsp;YES&nbsp;&nbsp;</div>';
			// 	}else{
			// 		$status = '<div class="badge badge-danger badge-pill">&nbsp;&nbsp;NO&nbsp;&nbsp;</div>';
			// 	}

            //     return $status;
			// })
			->addIndexColumn()
            ->addColumn('action', function($row){
				$url_edit = route('role.edit_department', [$row->r_id]);
				if((check_permission(Auth::user()->Employee->department_id,"Role Management","full")) || (check_permission(Auth::user()->Employee->department_id,"Role Management","Edit"))){
				$response = '<button  type="button" class="btn btn-outline-secondary btn-rounded btn-icon btn-sm" onclick="window.location.href = \''.$url_edit.'\';"><i style="margin-left: -6px;" class="mdi mdi-lead-pencil"></i></button>';
                // $response .= ' <button type="button" data-toggle="modal" data-id="'.$row->r_id.'" data-target="#DeleteModal" class="btn btn-outline-secondary btn-rounded btn-icon btn-sm btn-delete_row"><i style="margin-left: -7px;" class="mdi mdi-delete"></i></button>';
                return $response;
				}
			})
            // ->rawColumns(['id','role_name','page','role','active', 'action']) //department
            ->rawColumns(['id','department_name', 'action']) //department
            ->make(true);
            
        return $data_tables;
    }

    public function edit_department($id){
        /*$data = Role::get();
        $pages = getRouteNames();
        $methods = getDefaultMethods();
        $department = Department::findOrFail($id);*/
		

        //return view('roles.department_edit',compact('data', 'pages', 'methods', 'department'));
		
		//$pageroles = Page_Role::get();
		$pageroles = DB::table('page_roles')
            ->select('page_roles.*','modules_tables.*')
            ->join('modules_tables', 'modules_tables.id', '=', 'page_roles.module_id')
			->where("modules_tables.parent", 0)
			->OrderBy('modules_tables.priority',"ASC")
            ->get();
		$department = Department::findOrFail($id);
        return view('roles.role-list',compact('pageroles','department'));
		
    }
	public function module_permission_store(Request $request){
		$department_id=$request->department_id;
		$modules=page_role::all();
		$request->PayrollSettings;
		foreach($modules as $module){
			$modulename=str_replace(' ','',$module->page_name);
			if(isset($request->$modulename)){
				
				$module_id=$module->id;
				$modulepermission=module_permission::where('department_id',$department_id)->where('module_id',$module_id)->get();
				$permission=$request->$modulename;
				$edit=0;
				$delete=0;
				$view=0;
				$add=0;
				$full=0;
				if(in_array('full',$permission)){
					$full=1;
				}
				else{
					if(in_array('EDIT',$permission)){
						$edit=1;
					}
					if(in_array('ADD',$permission)){
						$add=1;
					}
					if(in_array('VIEW',$permission)){
						$view=1;
					}
					if(in_array('DELETE',$permission)){
						$delete=1;
					}
				}
				if(count($modulepermission)==0){
					$addmodulepermission=module_permission::create([
						'module_id'=>$module_id,
						'department_id'=>$department_id,
						'full'=>$full,
						'add'=>$add,
						'view'=>$view,
						'edit'=>$edit,
						'delete'=>$delete
					]);
					
					$addmodulepermission->save();
				}
				else{				
					$editmodulepermission=module_permission::where('department_id',$department_id)->where('module_id',$module_id)->first();
					$editmodulepermission->module_id=$module_id;
					$editmodulepermission->department_id=$department_id;
					$editmodulepermission->full=$full;
					$editmodulepermission->add=$add;
					$editmodulepermission->view=$view;
					$editmodulepermission->edit=$edit;
					$editmodulepermission->delete=$delete;
					$editmodulepermission->save();
				}
				
			}
			else{
				if($modulename=="Reports")
				{
					if(isset($request->dailyreport)){
						$module_id=$module->id;
						$modulepermission=module_permission::where('department_id',$department_id)->where('module_id',$module_id)->get();
						$permission=array('VIEW');
						$edit=0;
						$delete=0;
						$view=0;
						$add=0;
						$full=0;
						if(in_array('full',$permission)){
							$full=1;
						}
						else{
							if(in_array('EDIT',$permission)){
								$edit=1;
							}
							if(in_array('ADD',$permission)){
								$add=1;
							}
							if(in_array('VIEW',$permission)){
								$view=1;
							}
							if(in_array('DELETE',$permission)){
								$delete=1;
							}
						}
						if(count($modulepermission)==0){
							$addmodulepermission=module_permission::create([
								'module_id'=>$module_id,
								'department_id'=>$department_id,
								'full'=>$full,
								'add'=>$add,
								'view'=>$view,
								'edit'=>$edit,
								'delete'=>$delete
							]);

							$addmodulepermission->save();
						}
						else{				
							$editmodulepermission=module_permission::where('department_id',$department_id)->where('module_id',$module_id)->first();
							$editmodulepermission->module_id=$module_id;
							$editmodulepermission->department_id=$department_id;
							$editmodulepermission->full=$full;
							$editmodulepermission->add=$add;
							$editmodulepermission->view=$view;
							$editmodulepermission->edit=$edit;
							$editmodulepermission->delete=$delete;
							$editmodulepermission->save();
						}
					}
					else{
							$module_id=$module->id;
						$modulepermission=module_permission::where('department_id',$department_id)
							->where('module_id',$module_id)->get();
						if(count($modulepermission)>0){
							$delete_module=module_permission::where('department_id',$department_id)
								->where('module_id',$module_id)->delete();
						}
					}
					
				}
				else{
					$module_id=$module->id;
					$modulepermission=module_permission::where('department_id',$department_id)
						->where('module_id',$module_id)->get();
					if(count($modulepermission)>0){
						$delete_module=module_permission::where('department_id',$department_id)
							->where('module_id',$module_id)->delete();
					}
				}
			}
		}
		$exist=Report::where('department_id',$department_id)->delete();
		if(isset($request->dailyreport)){
			$reports=$request->dailyreport;
			foreach($reports as $report){
					$data=Report::create([
					'report_name'=>$report,
					'view'=>1,
					'department_id'=>$department_id,
					]);
					$data->save();
			}
		}
		//return redirect(route('role.edit_department', $department_id));
		return redirect(route('roles.index'))->with('success', 'Role access successfully updated');
	}

    public function edit_department_filter(Request $request){
        $role_access      = $request->role_access;
        $department_id  = $request->department_id;
        $is_active      = $request->is_active;
        $page      = $request->page;

        $filters = [];
        if($department_id != ''){
            $filters['roles.department_id'] = $department_id;
        }
        if($role_access != ''){
            $filters['roles.role'] = $role_access;
        }
        if($is_active != ''){
            $filters['roles.is_active'] = $is_active;
        }
        if($page != ''){
            $filters['roles.page'] = $page;
        }

        $to_select = array(
            'roles.role',
            'roles.page',
            'roles.id as r_id',
            'roles.is_active',
            'roles.department_id',
            'roles.role_name',
            'departments.name as department_name'
        );

        if(count($filters) > 0){
            $data = Role::join('departments', 'roles.department_id', '=', 'departments.id')
        	->where($filters)
            ->get($to_select);
        }else{
            $data = Role::join('departments', 'roles.department_id', '=', 'departments.id')
            ->get($to_select);
        }

        $data_tables = Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('id', function($row){
                $url_edit = route('roles.edit', [$row->r_id]);
                $response = '<a href="'.$url_edit.'">'.$row->r_id.'</a>';
                return $response;
            })
            ->addIndexColumn()
            ->addColumn('page', function($row){
                return ucfirst($row->page);
            })
			->addIndexColumn()
            ->addColumn('role', function($row){
                return getDefaultMethods()[$row->role];
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
				$url_edit = route('roles.edit', [$row->r_id]);
				$response = '<button  type="button" class="btn btn-outline-secondary btn-rounded btn-icon btn-sm" onclick="window.location.href = \''.$url_edit.'\';"><i style="margin-left: -6px;" class="mdi mdi-lead-pencil"></i></button>';
                $response .= ' <button type="button" data-toggle="modal" data-id="'.$row->r_id.'" data-target="#DeleteModal" class="btn btn-outline-secondary btn-rounded btn-icon btn-sm btn-delete_row"><i style="margin-left: -7px;" class="mdi mdi-delete"></i></button>';
                return $response;
			})
            ->rawColumns(['id', 'page','role', 'is_active', 'action']) //department
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
        $departments = Department::all();
        $pages = getRouteNames();
        $methods = getDefaultMethods();
        return view('roles.create', compact('departments', 'pages', 'methods'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $is_validated = Validator::make($request->all(), [
            'department_id'     =>  'required',
            'page'              =>  'required',
            'role'              =>  'required',
            'is_active'         =>  'required',
        ]);
        
        if($is_validated->fails()){
			return back()->withErrors($is_validated)->withInput();
		}

        $role = new Role;
        $role->department_id = $request->department_id;
        $role->page = $request->page;
        $role->role = $request->role;
        $role->is_active = $request->is_active;
        $role->save();
        return redirect()->back()->with('success', 'Successfully created'); 

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
        $role = Role::findOrFail($id);
        $departments = Department::all();
        $pages = getRouteNames();
        $methods = getDefaultMethods();
        return view('roles.edit', compact('role', 'departments', 'pages', 'methods'));
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
        $is_validated = Validator::make($request->all(), [
            'department_id'     =>  'required',
            'page'              =>  'required',
            'role'              =>  'required',
            'is_active'         =>  'required',
        ]);
        
        if($is_validated->fails()){
			return back()->withErrors($is_validated)->withInput();
        }
        
        $role = Role::findOrFail($id);

        $role->department_id = $request->department_id;
        $role->page = $request->page;
        $role->role = $request->role;
        $role->is_active = $request->is_active;
        $role->role_name = $request->role_name;
        $role->save();

        return redirect('roles/department/'.$request->department_id)->with('success', 'Successfully updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $role = Role::findOrFail($id);
		$dept_id = $role->department_id;
        $role->delete();

        return redirect('roles/department/'.$dept_id)->with('success', 'Successfully deleted');
    }

    // public function enable()
    // {
    //     return "controller";
   
    //     return response()->json([
    //         'error' => false,
    //         'task'  => $task,
    //     ], 200);

        // $user = Role::findOrFail($request->user_id);
        // if($user->active == 1){
        //     $user->active = 0;
        // } else {
        //     $user->active = 1;
        // }

    // }

    private function splitWords($word){
        $nc = str_replace('Controller', '', $word);
        $pattern = '/(.*?[a-z]{1})([A-Z]{1}.*?)/';
        $replace = '${1} ${2}';
        $word = strtoupper(preg_replace($pattern, $replace, $nc));
        return $word;
    }
}
