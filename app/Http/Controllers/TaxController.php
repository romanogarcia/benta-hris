<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tax;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class TaxController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $perpage = $request->query('perpage',100);
        $rows = Tax::latest()->paginate($perpage);
        $entries = "<p>Showing ".$rows->firstItem()." to ".$rows->lastItem()." of ".$rows->total()." entries </p>";
        return view('settings.taxes.taxlist',['rows'=>$rows,'entries'=>$entries]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('settings.taxes.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!Schema::hasTable('taxes')) 
        {
            return redirect('tax')->withErrors(['field' => 'Table does not exists.']);
        }

        $request->validate([
            'cl'         => 'required',
            'over'       => 'required',
            'tax'        => 'required',
            'percentage' => 'required'
        ]);

        $tax = new Tax;
        $tax->compensation_level = $request->cl;
        $tax->over = $request->over;
        $tax->tax = $request->tax;
        $tax->percentage = $request->percentage;
        $tax->save();

        return redirect('/payroll_settings')->with('success', 'Tax compensation range successfully added');
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
        $data = Tax::findOrFail($id);
        return view('settings.taxes.edit',[
            'tax' => $data
        ]);
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
        $request->validate([
            'cl'         => 'required',
            'over'       => 'required',
            'tax'        => 'required',
            'percentage' => 'required'
        ]);

        $tax = Tax::findOrFail($id);
        $tax->compensation_level = $request->cl;
        $tax->over = $request->over;
        $tax->tax = $request->tax;
        $tax->percentage = $request->percentage;
        $tax->save();

        return redirect('/payroll_settings')->with('success', 'Tax compensation range successfully updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Tax::destroy($id);
        return response()->json(array('success' => 'Record deleted successfully'));
    }
}
