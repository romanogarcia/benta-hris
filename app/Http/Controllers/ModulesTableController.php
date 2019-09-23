<?php

namespace App\Http\Controllers;

use App\modules_table;
use Illuminate\Http\Request;

class ModulesTableController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $modules=modules_table::all();
		return view('module_table.index',['modules'=>$modules]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		$modules=modules_table::all();
        return view('module_table.create',['modules'=>$modules]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       $module=modules_table::create([
	   'module_name'=>$request->module_name,
	   'module_link'=>$request->module_link,
	   'parent'=>$request->parent,
	   'priority'=>$request->priority,
	   'menu_icon'=>$request->menu_icon,
	   'route_name'=>$request->route_name,
	   ]);
		$module->save();
		return redirect(route('module_table.index'))->with('success',"Successfully Added");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\modules_table  $modules_table
     * @return \Illuminate\Http\Response
     */
    public function show(modules_table $modules_table)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\modules_table  $modules_table
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
		$module=modules_table::find($id);
        $modules=modules_table::all();
        return view('module_table.create',['modules'=>$modules,'module'=>$module]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\modules_table  $modules_table
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
       	$module=modules_table::find($id);
		$module->module_name=$request->module_name;
	   	$module->module_link=$request->module_link;
	   	$module->parent=$request->parent;
	   	$module->priority=$request->priority;
	   	$module->menu_icon=$request->menu_icon;
	   	$module->route_name=$request->route_name;
		$module->save();
		return redirect(route('module_table.index'))->with('success',"Successfully Updated");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\modules_table  $modules_table
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
		$module=modules_table::find($id)->delete();
		return redirect(route('module_table.index'))->with('success',"Successfully Deleted");
    }
}
