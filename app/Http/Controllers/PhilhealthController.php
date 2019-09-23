<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Philhealth;

class PhilhealthController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $perpage = $request->query('perpage',100);
        $rows = Philhealth::latest()->paginate($perpage);
      $entries = "<p>Showing ".$rows->firstItem()." to ".$rows->lastItem()." of ".$rows->total()." entries </p>";
        return view('settings.philhealth.philhealthlist',['rows'=>$rows,'entries'=>$entries]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('settings.philhealth.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!Schema::hasTable('philhealths')) 
        {
            return redirect('philhealth')->withErrors(['field' => 'Table does not exists.']);
        }

        $request->validate([
            'salary_min' => 'required',
            'salary_max' => 'required',
            'monthly'  => 'required'
        ]);

        $bracket = $request->salary_min.'-'.$request->salary_max;
        $share = $request->monthly / 2;
        $ph = new Philhealth;
        $ph->salary_bracket = $bracket;
        $ph->salary_min = $request->salary_min;
        $ph->salary_max = $request->salary_max;
        $ph->total_monthly_premium = $request->monthly;
        $ph->employee_share = $share;
        $ph->employer_share = $share;
        $ph->save();
        return redirect('/payroll_settings')->with('success', 'Range successfully added'); 
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
        $data = Philhealth::findOrFail($id);
        return view('settings.philhealth.edit',[
            'ph' => $data
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
            'salary_min' => 'required',
            'salary_max' => 'required',
            'monthly'  => 'required'
        ]);
        
        $ph = Philhealth::find($id);
        $bracket = $request->salary_min.'-'.$request->salary_max;
        $share = $request->monthly / 2;
        $ph->salary_bracket = $bracket;
        $ph->salary_min = $request->salary_min;
        $ph->salary_max = $request->salary_max;
        $ph->total_monthly_premium = $request->monthly;
        $ph->employee_share = $share;
        $ph->employer_share = $share;
        $ph->save();
        return redirect('/payroll_settings')->with('success', 'Successfully updated'); 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Philhealth::destroy($id);
        return response()->json(array('success' => 'Record deleted successfully'));
    }
}
