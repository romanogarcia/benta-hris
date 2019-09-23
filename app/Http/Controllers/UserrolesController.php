<?php

namespace App\Http\Controllers;

use App\UserRoles as Roles;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use DateTime;
use Auth;
use Session;

class UserrolesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $records = Roles::paginate(20);
        return view('userroles.list',['record'=>$records]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('userroles.create');
    }

    
    public function edit($id)
    {
        $records = Roles::findOrFail($id);
        return view('userroles.edit', ['record' => $records]);
    }
		
	public function save(Request $request)
	{
		 $this->validate($request,[
            'type'     => 'required',
            'access'     => 'required',
            'created_by'     => 'required',
            'modified_by'  => 'required'
        ]);
	
        $record           = new Roles;
        $record->type     = $request->type;
		$record->access     = $request->access;
		$record->modified_by     = $request->modified_by;
		$record->created_by     = $request->created_by;
     	$record->save();
        return redirect()->back()->with('success','Record added successfully!'); 
	}	
	public function update($id,Request $request)
	{
		$this->validate($request,[
            'type'     => 'required',
            'access'     => 'required',
            'created_by'     => 'required',
            'modified_by'  => 'required'
        ]);
		
		$role = Roles::find($id);

		$role->type     = $request->type;
		$role->access     = $request->access;
		$role->modified_by     = $request->modified_by;

		$role->save();
		return redirect()->back()->with('success','Record updated successfully!'); 
	}	
	public function destroy(Request $request)
    {
        if(Roles::where('id', $request->id)->delete()){
            return redirect()->back()->with('success','Record deleted successfully!');
        } else {

        }
        
    }

}
