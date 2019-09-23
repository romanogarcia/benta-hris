<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

use App\AssetScannedBarcodeUpload;
use App\AssetScannedBarcode;
use App\AssetLocation;
use DataTables;
class AssetScannedBarcodeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $locations = AssetLocation::orderBy('location', 'asc')->get();
        return view('asset.scanned-barcode.index', compact('locations'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $locations = AssetLocation::orderBy('location', 'asc')->get();
        return view('asset.scanned-barcode.create', compact('locations'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = array(
            'barcode'       => 'required',
            'asset_location'=> 'required',
        );
        $errors = $rules;

        $validator = Validator::make($request->all(), $rules);
        $messages = $validator->getMessageBag()->toArray();
        

        foreach($errors as $error_key => $error_value){
            if(!array_key_exists($error_key, $messages))
                $errors[$error_key] = '';
            else
                $errors[$error_key] = $messages[$error_key];
        }
        
        $response = array(
            'success'           => false,
            'errors'            => $errors,
        );

        if(!$validator->fails()){
            $response['success']    = true;
        }

        if($response['success']){
            $barcode        = $request->barcode;
            $location_id    = $request->asset_location;

            $asset_scanned_barcode              = new AssetScannedBarcode();
            $asset_scanned_barcode->barcode     = $barcode;
            $asset_scanned_barcode->uploaded_by = auth()->user()->id;
            $asset_scanned_barcode->location_id = $location_id;
            $asset_scanned_barcode->save();
        }

        return $response;
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $asset_scanned_barcode  = AssetScannedBarcode::findOrFail($id);
        $is_deleted             = $asset_scanned_barcode->delete();
        if($is_deleted)
            return redirect(route('asset_scanned_barcode.index'))->with('success', 'Successfully Deleted');
        else
            return redirect(route('asset_scanned_barcode.index'))->with('error', 'An error occured while deleting the data');
    }

    public function search_filter(Request $request){
        $barcode        = $request->barcode;
        $location_id    = $request->asset_location;
        $status         = $request->status;
        $date_range_pick= $request->date_range;
        $filters        = [];

        if(!empty($barcode))
            $filters['asb.barcode'] = $barcode;
        
        if(!empty($location_id))
            $filters['asb.location_id'] = $location_id;
        
        if(!empty($date_range_pick)){
            $date_array  = explode(" - ",$date_range_pick);
            $from = date('Y-m-d',strtotime($date_array[0]));
            $to = date('Y-m-d',strtotime($date_array[1]));
        }

        $to_select = array(
            'asb.id as scanned_barcode_id',
            'asb.barcode',
            'asb.created_at as date_added',

            'al.location as scanned_location',
            'ab.username as added_by',
            
            'asbu.file',
            'asbu.file_name',
            'asbu.file_extension',
            
            'a.slug_token as asset_slug_token',

        );
        if($status != ''){
            if($status == 'found'){
                if(!empty($date_range_pick)){
                    $data = AssetScannedBarcode::from('asset_scanned_barcodes as asb')
                    ->select($to_select)
                    ->leftJoin('asset_locations as al', 'al.id', '=', 'asb.location_id')
                    ->join('users as ab', 'ab.id', '=', 'asb.uploaded_by')
                    ->leftJoin('asset_scanned_barcode_uploads as asbu', 'asbu.id', '=', 'asb.scanned_barcode_upload_id')
                    ->leftJoin('assets as a', 'a.property_number', '=', 'asb.barcode')
                    ->whereRaw("asb.created_at >= ? AND asb.created_at <=?",[$from.' 00:00:00',$to.' 23:59:59'])
                    ->where($filters)
                    ->whereNotNull('a.property_number')
                    ->orderBy('asb.created_at', 'desc')
                    ->get();
                }else{
                    $data = AssetScannedBarcode::from('asset_scanned_barcodes as asb')
                    ->select($to_select)
                    ->leftJoin('asset_locations as al', 'al.id', '=', 'asb.location_id')
                    ->join('users as ab', 'ab.id', '=', 'asb.uploaded_by')
                    ->leftJoin('asset_scanned_barcode_uploads as asbu', 'asbu.id', '=', 'asb.scanned_barcode_upload_id')
                    ->leftJoin('assets as a', 'a.property_number', '=', 'asb.barcode')
                    ->where($filters)
                    ->whereNotNull('a.property_number')
                    ->orderBy('asb.created_at', 'desc')
                    ->get();
                }
            }else{
                if(!empty($date_range_pick)){
                    $data = AssetScannedBarcode::from('asset_scanned_barcodes as asb')
                    ->select($to_select)
                    ->leftJoin('asset_locations as al', 'al.id', '=', 'asb.location_id')
                    ->join('users as ab', 'ab.id', '=', 'asb.uploaded_by')
                    ->leftJoin('asset_scanned_barcode_uploads as asbu', 'asbu.id', '=', 'asb.scanned_barcode_upload_id')
                    ->leftJoin('assets as a', 'a.property_number', '=', 'asb.barcode')
                    ->whereRaw("asb.created_at >= ? AND asb.created_at <=?",[$from.' 00:00:00',$to.' 23:59:59'])
                    ->where($filters)
                    ->whereNull('a.property_number')
                    ->orderBy('asb.created_at', 'desc')
                    ->get();
                }else{
                    $data = AssetScannedBarcode::from('asset_scanned_barcodes as asb')
                    ->select($to_select)
                    ->leftJoin('asset_locations as al', 'al.id', '=', 'asb.location_id')
                    ->join('users as ab', 'ab.id', '=', 'asb.uploaded_by')
                    ->leftJoin('asset_scanned_barcode_uploads as asbu', 'asbu.id', '=', 'asb.scanned_barcode_upload_id')
                    ->leftJoin('assets as a', 'a.property_number', '=', 'asb.barcode')
                    ->where($filters)
                    ->whereNull('a.property_number')
                    ->orderBy('asb.created_at', 'desc')
                    ->get();
                }
            }
        }else{
            if(!empty($date_range_pick)){
                $data = AssetScannedBarcode::from('asset_scanned_barcodes as asb')
                ->select($to_select)
                ->leftJoin('asset_locations as al', 'al.id', '=', 'asb.location_id')
                ->join('users as ab', 'ab.id', '=', 'asb.uploaded_by')
                ->leftJoin('asset_scanned_barcode_uploads as asbu', 'asbu.id', '=', 'asb.scanned_barcode_upload_id')
                ->leftJoin('assets as a', 'a.property_number', '=', 'asb.barcode')
                ->whereRaw("asb.created_at >= ? AND asb.created_at <=?",[$from.' 00:00:00',$to.' 23:59:59'])
                ->where($filters)
                ->orderBy('asb.created_at', 'desc')
                ->get();
            }else{
                $data = AssetScannedBarcode::from('asset_scanned_barcodes as asb')
                ->select($to_select)
                ->leftJoin('asset_locations as al', 'al.id', '=', 'asb.location_id')
                ->join('users as ab', 'ab.id', '=', 'asb.uploaded_by')
                ->leftJoin('asset_scanned_barcode_uploads as asbu', 'asbu.id', '=', 'asb.scanned_barcode_upload_id')
                ->leftJoin('assets as a', 'a.property_number', '=', 'asb.barcode')
                ->where($filters)
                ->orderBy('asb.created_at', 'desc')
                ->get();
            }
        }
        // dd($data);

        $data_tables = Datatables::of($data)
			->addIndexColumn()
            ->addColumn('scanned_barcode', function($row){
                if($row->asset_slug_token){
                    $url = route('asset_inventory.edit', ['slug_token'=>$row->asset_slug_token]);
                    $response = '<a data-sort="'.$row->barcode.'" title="click to view" href="'.$url.'">'.$row->barcode.'</a>';
                }else{
                    $response = $row->barcode;
                }
                return $response;
            })
            ->addIndexColumn()
            ->addColumn('scanned_location', function($row){
                return ucfirst($row->scanned_location);
            })
            ->addIndexColumn()
            ->addColumn('status', function($row){
                if($row->asset_slug_token)
                    $response = '<label data-sort="1" class="badge badge-success">Found</label>';
                else
                    $response = '<label data-sort="2" class="badge badge-danger">Not Found</label>';
                
                return $response;
            })
            ->addIndexColumn()
            ->addColumn('added_by', function($row){
                return $row->added_by;
            })
            ->addIndexColumn()
            ->addColumn('source', function($row){
                if($row->file){
                    $download_url = get_scanned_barcode_upload_path($row->file);
                    $response = '<a download="'.$row->file_name.'.'.$row->file_extension.'" href="'.$download_url.'">'.$row->file_name.'.'.$row->file_extension.'</a>';
                }else{
                    $response = 'none';
                }
                return $response;
            })
            ->addColumn('date_added', function($row){
                $label      = date(get_date_format(), strtotime($row->date_added));
                $data_sort  = date('Y-m-d', strtotime($row->date_added));
                $response   = '<label data-sort="'.$data_sort.'">'.$label.'</label>';
                return $response;
            })
			->addIndexColumn()
            ->addColumn('action', function($row){
				$url_edit = route('asset_scanned_barcode.edit', [$row->scanned_barcode_id]);
                $response = ' <button type="button" data-toggle="modal" data-id="'.$row->scanned_barcode_id.'" data-target="#DeleteModal" class="btn btn-outline-secondary btn-rounded btn-icon btn-sm btn-delete_row ml-3 "><i style="margin-left: -7px;" class="mdi mdi-delete"></i></button>';
                return $response;
			})
            ->rawColumns(['scanned_barcode', 'scanned_location','status','added_by','source','date_added','action'])
            ->make(true);

        return $data_tables;
    }
}
