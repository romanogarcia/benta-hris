<?php

namespace App\Http\Controllers;

use App\Modules;
use Illuminate\Http\Request;

class ModulesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		$modules=Modules::all();
        return view('module.index',['modules'=>$modules]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('module.create');
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
     * @param  \App\Modules  $modules
     * @return \Illuminate\Http\Response
     */
    public function show(Modules $modules)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Modules  $modules
     * @return \Illuminate\Http\Response
     */
    public function edit(Modules $modules)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Modules  $modules
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Modules $modules)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Modules  $modules
     * @return \Illuminate\Http\Response
     */
    public function destroy(Modules $modules)
    {
        //
    }
}
