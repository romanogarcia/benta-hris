<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AssetCategory;
use DataTables;
use Auth;
class AssetCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('asset.category.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('asset.category.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request);
        $validatedData = $this->validate($request, [
            'category'      => 'required|max:150',
        ]);

        $asset_category             = new AssetCategory();
        $asset_category->category   = $request->category;
        $is_saved                   = $asset_category->save();
        
        if($is_saved)
            return redirect(route('asset_category.index'))->with('success', 'Successfully Added');
        else
            return redirect(route('asset_category.index'))->with('error', 'An error occured while adding the data');
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
        $data = AssetCategory::findOrFail($id);
        return view('asset.category.edit', compact('data'));
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
        $validatedData = $this->validate($request, [
            'category'      => 'required|max:150',
        ]);

        $asset_category             = AssetCategory::findOrFail($id);
        $asset_category->category   = $request->category;
        $is_saved                   = $asset_category->save();

        if($is_saved)
            return redirect(route('asset_category.index'))->with('success', 'Successfully Updated');
        else
            return redirect(route('asset_category.index'))->with('error', 'An error occured while updating the data');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $asset_category = AssetCategory::findOrFail($id);
        $is_deleted     = $asset_category->delete();

        if($is_deleted)
            return redirect(route('asset_category.index'))->with('success', 'Successfully Deleted');
        else
            return redirect(route('asset_category.index'))->with('error', 'An error occured while deleting the data');
    }

    public function search_filter(Request $request){
        
        $to_select = array('*');

        $data = AssetCategory::select($to_select)
        ->orderBy('created_at', 'desc')
        ->get();

        $data_tables = Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('id', function($row){
                $url_edit = route('asset_category.edit', [$row->id]);
				if((check_permission(Auth::user()->Employee->department_id,"Categories","full")) || (check_permission(Auth::user()->Employee->department_id,"Categories","Edit"))){
                $response = '<a href="'.$url_edit.'">'.$row->id.'</a>';
                return $response;
				}
				else{
					return $row->id;
				}
            })
            
			->addIndexColumn()
            ->addColumn('category', function($row){
                return ucfirst($row->category);
			})
			->addIndexColumn()
            ->addColumn('action', function($row){
				$url_edit = route('asset_category.edit', [$row->id]);
				$response="";
				if((check_permission(Auth::user()->Employee->department_id,"Categories","full")) || (check_permission(Auth::user()->Employee->department_id,"Categories","Edit"))){
				$response = '<button  type="button" class="btn btn-outline-secondary btn-rounded btn-icon btn-sm" onclick="window.location.href = \''.$url_edit.'\';"><i style="margin-left: -6px;" class="mdi mdi-lead-pencil"></i></button>';
				}
				if((check_permission(Auth::user()->Employee->department_id,"Categories","full")) || (check_permission(Auth::user()->Employee->department_id,"Categories","Delete"))){
                $response .= ' <button type="button" data-toggle="modal" data-id="'.$row->id.'" data-target="#DeleteModal" class="btn btn-outline-secondary btn-rounded btn-icon btn-sm btn-delete_row ml-3 "><i style="margin-left: -7px;" class="mdi mdi-delete"></i></button>';
				}
                return $response;
			})
            ->rawColumns(['id', 'category', 'action'])
            ->make(true);
            
        return $data_tables;
    }
}
