<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Pagibig;
use DataTables;
use Auth;

class PagibigController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $rows = Pagibig::latest()->paginate(100);

        return view('settings.pagibig.pagibiglist',compact('rows'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }
	public function pagibig_list()
	{
		$data = Pagibig::latest()->get();
		 $data_tables = Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('id', function($row){
				if((check_permission(Auth::user()->Employee->department_id,"Pag-lbig","full")) || (check_permission(Auth::user()->Employee->department_id,"Pag-lbig","Edit"))){
                $url_edit = route('pagibig.edit',[$row->id]);

                return '<a href="'.$url_edit.'">'.$row->id.'</a>';
				}
				else{
				return $row->id;
				}
            })
            ->addIndexColumn()
            ->addColumn('monthly_compensation', function($row){
                return '₱ '.str_replace('-',' to ₱ ',$row->monthly_compensation);
            })
			->addIndexColumn()
            ->addColumn('employee_share', function($row){
                return $row->employee_share;
            })
			->addIndexColumn()
            ->addColumn('employer_share', function($row){
                return $row->employer_share;
            })
            ->addIndexColumn()
            ->addColumn('action_edit', function($row){
				if((check_permission(Auth::user()->Employee->department_id,"Pag-lbig","full")) || (check_permission(Auth::user()->Employee->department_id,"Pag-lbig","Edit"))){
				$action_edit = '<button type="button" class="btn btn-outline-secondary btn-rounded btn-icon btn-sm" onclick="window.location.href = '."'".route('pagibig.edit',[$row->id])."'".'" style="padding: 0;"><i class="mdi mdi-lead-pencil"></i></button>';             
                return $action_edit;
				}
            })
			->addIndexColumn()
            ->addColumn('action_delete', function($row){
				if((check_permission(Auth::user()->Employee->department_id,"Pag-lbig","full")) || (check_permission(Auth::user()->Employee->department_id,"Pag-lbig","Delete"))){
				$action_delete = '<button type="button" data-target="#DeleteModal" data-toggle="modal" class="btn btn-outline-secondary btn-rounded btn-icon btn-sm btn-delete_row" data-id="'.$row->id.'" style="padding: 0;"><i class="mdi mdi-delete"></i></button>';             
                return $action_delete;
				}
            }) 
            ->rawColumns(['id','monthly_compensation','employee_share','employer_share','action_edit', 'action_delete'])
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
        return view('settings.pagibig.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!Schema::hasTable('pagibigs')) 
        {
            return redirect('pagibig')->withErrors(['field' => 'Table does not exists.']);
        }

        $request->validate([
            'ee' => 'required',
            'er' => 'required',
            'salary_min' => 'required',
            'salary_max' => 'required'
        ]);
        
        $pagibig = new Pagibig;
        $pagibig->monthly_compensation = $request->salary_min . '-' . $request->salary_max;
        $pagibig->employee_share = $request->ee;
        $pagibig->employer_share = $request->er;
        $pagibig->save();

        return redirect('/payroll_settings')->with('success', 'Pag-Ibig was successfully created');
        
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
        $data = Pagibig::findOrFail($id);
        return view('settings.pagibig.edit',[
            'pagibig' => $data
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
            'ee' => 'required',
            'er' => 'required'
        ]);

        $pagibig = Pagibig::find($id);
        $pagibig->monthly_compensation = $request->salary_min . '-' . $request->salary_max;
        $pagibig->employee_share = $request->ee;
        $pagibig->employer_share = $request->er;
        $pagibig->save();

        return redirect('/payroll_settings')->with('success', 'Pag-Ibig was successfully updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Pagibig::destroy($id);
        return redirect('/payroll_settings')->with('success', 'Successfully Deleted'); 
    }
}