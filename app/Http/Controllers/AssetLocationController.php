<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AssetLocation;
use App\Country;
use DataTables;
use Auth;
class AssetLocationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        return view('asset.location.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
        
        $countries=Country::select('id','country_name')->get();
        return view('asset.location.create',compact('countries'));
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
            'location'      => 'max:150',
            'zipcode'       => 'max:150',
            'address'       => 'max:150',
            'city'          => 'max:150',
            'country'       => 'max:150',
        ]);

        $asset_location               = new AssetLocation();
        $asset_location->location     = $request->location;
        $asset_location->zip_code     = $request->zipcode;
        $asset_location->address      = $request->address;
        $asset_location->city         = $request->city;
        $asset_location->country_id   = $request->country;
        $is_saved                     = $asset_location->save();
        
        if($is_saved)
            return redirect(route('asset_location.index'))->with('success', 'Successfully Added');
        else
            return redirect(route('asset_location.index'))->with('error', 'An error occured while adding the data');
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
        $data = AssetLocation::findOrFail($id);
        $countries=Country::select('id','country_name')->get();
        return view('asset.location.edit', compact('countries','data'));
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
            'location'      => 'max:150',
            'zipcode'       => 'max:150',
            'address'       => 'max:150',
            'city'          => 'max:150',
            'country'       => 'max:150',

        ]);

        $asset_location               = AssetLocation::findOrFail($id);
        $asset_location->location     = $request->location;
        $asset_location->zip_code     = $request->zipcode;
        $asset_location->address      = $request->address;
        $asset_location->city         = $request->city;
        $asset_location->country_id   = $request->country;
        $is_saved                     = $asset_location->save();

        if($is_saved)
            return redirect(route('asset_location.index'))->with('success', 'Successfully Updated');
        else
            return redirect(route('asset_location.index'))->with('error', 'An error occured while updating the data');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $asset_location = AssetLocation::findOrFail($id);
        $is_deleted     = $asset_location->delete();

        if($is_deleted)
            return redirect(route('asset_location.index'))->with('success', 'Successfully Deleted');
        else
            return redirect(route('asset_location.index'))->with('error', 'An error occured while deleting the data');
    }
    
    public function search_filter(Request $request){
        $to_select = array('asset_locations.id',
                           'asset_locations.zip_code',
                           'asset_locations.location',
                           'asset_locations.address',
                           'asset_locations.city',
                           'countries.country_name');
        $response="";
        $data = AssetLocation::select($to_select)
        ->leftjoin('countries','countries.id','=','asset_locations.country_id')
        ->orderBy('asset_locations.created_at', 'desc')
        ->get();

        $data_tables = Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('id', function($row){
                $url_edit = route('asset_location.edit', [$row->id]);
				if((check_permission(Auth::user()->Employee->department_id,"Locations","full")) || (check_permission(Auth::user()->Employee->department_id,"Locations","Edit"))){
                $response = '<a href="'.$url_edit.'">'.$row->id.'</a>';
                return $response;
				}else{
				return $row->id;
				}
            })
            
			->addIndexColumn()
            ->addColumn('location', function($row){
                $response= $row->location;
            if(!empty($row->address))
                $response.=", ".$row->address;
            if(!empty($row->city))
                $response.=", ".$row->city;
            if(!empty($row->country_name))
                $response.=", ".$row->country_name;
            if(!empty($row->zip_code))
                $response.=", ".$row->zip_code;
           

                return $response;
			})
			->addIndexColumn()
            ->addColumn('action', function($row){
				$url_edit = route('asset_location.edit', [$row->id]);
				$response="";
				if((check_permission(Auth::user()->Employee->department_id,"Locations","full")) || (check_permission(Auth::user()->Employee->department_id,"Locations","Edit"))){
				$response = '<button  type="button" class="btn btn-outline-secondary btn-rounded btn-icon btn-sm" onclick="window.location.href = \''.$url_edit.'\';"><i style="margin-left: -6px;" class="mdi mdi-lead-pencil"></i></button>';
				}
				if((check_permission(Auth::user()->Employee->department_id,"Locations","full")) || (check_permission(Auth::user()->Employee->department_id,"Locations","Delete"))){
                $response .= ' <button type="button" data-toggle="modal" data-id="'.$row->id.'" data-target="#DeleteModal" class="btn btn-outline-secondary btn-rounded btn-icon btn-sm btn-delete_row ml-3 "><i style="margin-left: -7px;" class="mdi mdi-delete"></i></button>';
				}
                return $response;
			})
            ->rawColumns(['id', 'location', 'action'])
            ->make(true);
            
        return $data_tables;
    }
}
