<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\EmploymentStatus;

class EmploymentStatusController extends Controller
{
    public function __construct(){
        $this->model = 'EmploymentStatus';
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $rows = EmploymentStatus::latest()->paginate(20);

        return view('settings.emp-status',compact('rows'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
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
        $validatedData = $request->validate([
            'name' => 'required|max:255',
        ]);

        EmploymentStatus::create($validatedData);

        return redirect('employment-status')->with('success', 'Successfully created'); 
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
        $row = EmploymentStatus::findOrFail($id);
        $rows = EmploymentStatus::latest()->paginate(20);
        return view('settings.emp-status', compact('row', 'rows')); 
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
        $row = EmploymentStatus::findOrFail($id);

        $row->name = $request->name;
        $row->save();
        return redirect('employment-status')->with('success', 'Successfully updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $row = EmploymentStatus::findOrFail($id);
        $row->delete();

        return redirect('employment-status')->with('success', 'Successfully deleted');
    }
}
