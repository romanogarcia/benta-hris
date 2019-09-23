<?php

namespace App\Http\Controllers;

use App\Page_Role;
use Illuminate\Http\Request;
use App\modules_table;
use App\Role;
use Auth;
use App\Department;
use Route;

class PageRoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		$pageroles=Page_Role::all();
        return view('page_role.index',['pageroles'=>$pageroles]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		$pages = modules_table::all();
        $permission = getDefaultMethods();
        return view('page_role.create',['pages'=>$pages,'permissions'=>$permission]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
		$request->validate([
			'page_name'=>'required|unique:page_roles'
		]);
		
		$module_id=modules_table::where('module_name',$request->page_name)->get();
		
		if($module_id[0]->route_name!=""){
			$routeArray=Route::getRoutes()->getByName($module_id[0]->route_name)->action;
			$controllerAction = class_basename($routeArray['controller']);
			list($controller, $action) = explode('@', $controllerAction);
			$finalpage=substr($controller, 0, -10);
			$departments = Department::all();
			foreach($departments as $department){
				$role = new Role;
				$role->department_id =$department->id;
				$role->page = $finalpage;
				$role->role = 'full';
				$role->is_active = 1;
				$role->save();
				}
		}
		if(in_array('full',$request->permission)){
			$permissions=implode('|',$request->permission);
			$data=Page_Role::create([
			'page_name'=>$request->page_name,
			'permissions'=>$permissions,
			'full'=>1,
			'add'=>0,
			'view'=>0,
			'edit'=>0,
			'delete'=>0,
			'module_id'=>$module_id[0]->id,
			]);
		$data->save();
		}
		else{
			$edit=0;
			$delete=0;
			$view=0;
			$add=0;
			$full=0;
			if(in_array('EDIT',$request->permission)){
				$edit=1;
			}
			if(in_array('ADD',$request->permission)){
				$add=1;
			}
			if(in_array('VIEW',$request->permission)){
				$view=1;
			}
			if(in_array('DELETE',$request->permission)){
				$delete=1;
			}
			
			
			$permissions=implode('|',$request->permission);
			$data=Page_Role::create([
			'page_name'=>$request->page_name,
			'permissions'=>$permissions,
			'full'=>$full,
			'add'=>$add,
			'view'=>$view,
			'edit'=>$edit,
			'delete'=>$delete,
			'module_id'=>$module_id[0]->id,
			]);
		$data->save();
		}
		
		return redirect(route('page-role.create'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Page_Role  $page_Role
     * @return \Illuminate\Http\Response
     */
    public function show(Page_Role $page_Role)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Page_Role  $page_Role
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
       	$pagerole=Page_Role::find($id);
		$pages = modules_table::all();
        $permission = getDefaultMethods();
        return view('page_role.create',['pages'=>$pages,'permissions'=>$permission,'pagerole'=>$pagerole]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Page_Role  $page_Role
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
		$departments = Department::all();
		$permissions=implode('|',$request->permission);
		$module_id=modules_table::where('module_name',$request->page_name)->get();
		if($module_id[0]->route_name!=""){
			$routeArray=Route::getRoutes()->getByName($module_id[0]->route_name)->action;
			$controllerAction = class_basename($routeArray['controller']);
			list($controller, $action) = explode('@', $controllerAction);
			$finalpage=substr($controller, 0, -10);
			$departments = Department::all();
			foreach($departments as $department){
				 $roles=Role::where('department_id',$department->id)->where('page',$finalpage)->get();
				
				if(count($roles)==0){
				$role = new Role;
				$role->department_id =$department->id;
				$role->page = $finalpage;
				$role->role = 'full';
				$role->is_active = 1;
				$role->save();
				}
			}
		}
		if(in_array('full',$request->permission)){
			$pagerole=Page_Role::find($id);
			$pagerole->page_name=$request->page_name;
			$pagerole->permissions=$permissions;
			$pagerole->full=1;
			$pagerole->add=0;
			$pagerole->view=0;
			$pagerole->edit=0;
			$pagerole->delete=0;
			$pagerole->module_id=$module_id[0]->id;
			$pagerole->save();
		}
		else{
			$module_id=modules_table::where('module_name',$request->page_name)->get();
			$pagerole=Page_Role::find($id);
			$pagerole->page_name=$request->page_name;
			$pagerole->permissions=$permissions;
			$pagerole->module_id=$module_id[0]->id;
			$pagerole->full=0;
			if(in_array("EDIT",$request->permission)){
				$pagerole->edit=1;
			}
			if(in_array("CREATE",$request->permission)){
				$pagerole->add=1;
			}
			if(in_array('VIEW',$request->permission)){
				$pagerole->view=1;
			}
			if(in_array('DELETE',$request->permission)){
				$pagerole->delete=1;
			}
			$pagerole->save();
		}
		return redirect(route('page-role.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Page_Role  $page_Role
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       $pagerole=Page_Role::find($id)->delete();
    }
}
