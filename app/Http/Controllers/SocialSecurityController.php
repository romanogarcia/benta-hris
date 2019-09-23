<?php

namespace App\Http\Controllers;

use App\Tax;
use Illuminate\Http\Request;
use App\SocialSecurity;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class SocialSecurityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $perpage = (int) $request->query('perpage',100);
        $rows = SocialSecurity::orderBy('salary','asc')->paginate($perpage);
        $entries = "<p>Showing ".$rows->firstItem()." to ".$rows->lastItem()." of ".$rows->total()." entries </p>";
        return view('settings.sss.ssslist',['rows'=>$rows,'entries'=>$entries]);
            
    }
   
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('settings.sss.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!Schema::hasTable('social_securities')) 
        {
            return redirect('/payroll_settings')->withErrors(['field' => 'Table does not exists.']);
        }



        if ($request->has(['ec'])) {
            $request->validate([
                'ec' => 'required',
                'er' => 'required',
                'ee' => 'required',
                'salary_min' => 'required',
                'salary_max' => 'required'
            ]);
            $sss_total = $request->ee + $request->er;
            $sss = new SocialSecurity;
            $sss->min = $request->salary_min;
            $sss->max = $request->salary_max;
            $sss->salary = $request->salary;
            $sss->sss_er = $request->er;
            $sss->sss_ee = $request->ee;
            $sss->sss_ec_er = $request->ec;
            $sss->sss_total = $sss_total;
            $sss->total_contribution_er = $request->er + $request->ec;
            $sss->total_contribution_ee = $request->ee;
            $toal = $request->er + $request->ec + $request->ee;
            $sss->total_contribution_total = $toal;
            $sss->save();

            return redirect('/payroll_settings')->with('success','SSS table added successfully');
        } else {
            $request->validate([
                'select_file' => 'required',

            ]);
            $batch_names = $request->file('select_file')->getClientOriginalName();
            $allowed = array('txt','csv');
            $path = $request->file('select_file')->getRealPath();
            $ext = pathinfo($batch_names, PATHINFO_EXTENSION);
            $datas = array_map('str_getcsv', file($path));

            if(!in_array($ext,$allowed))
            {
                return redirect('/payroll_settings')->withErrors(['field' => 'Invalid file format']);
            }

            if(!empty($datas) && count($datas)) {
                foreach($datas as $key => $value) {
                    $insert[] = array(
                        'min' => $value[0],
                        'max' => $value[1],
                        'salary'    => $value[2],
                        'sss_er'    => $value[3],
                        'sss_ee' => $value[4],
                        'sss_total'    => $value[5],
                        'sss_ec_er'    => $value[6],
                        'total_contribution_er'    => $value[7],
                        'total_contribution_er' => $value[8],
                        'total_contribution_er'    => $value[9],
                    );
                }
                if(!empty($insert))
                {
                    DB::table('social_securities')->insert($insert);
                }

                return redirect('/payroll_settings')->with('success', 'Successfully uploaded');
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return "show";
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = SocialSecurity::findOrFail($id);
       return view('settings.sss.edit',
           [
               'sss' => $data
           ]
       );
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
        $sss_total = $request->ee + $request->er;
        $sss = SocialSecurity::findOrFail($id);
        $sss->min = $request->salary_min;
        $sss->max = $request->salary_max;
        $sss->salary = $request->salary;
        $sss->sss_er = $request->er;
        $sss->sss_ee = $request->ee;
        $sss->sss_ec_er = $request->ec;
        $sss->sss_total = $sss_total;
        $sss->total_contribution_er = $request->er + $request->ec;
        $sss->total_contribution_ee = $request->ee;
        $toal = $request->er + $request->ec + $request->ee;
        $sss->total_contribution_total = $toal;
        $sss->update();

        return redirect('/payroll_settings')->with('success','SSS table updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        SocialSecurity::destroy($id);
		return response()->json(array('success' => 'SSS record deleted successfully'));
       // return redirect('sss')->with('success', 'SSS table successfully deleted');
    }
	
}
