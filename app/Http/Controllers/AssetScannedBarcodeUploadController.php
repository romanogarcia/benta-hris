<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

use App\AssetScannedBarcodeUpload;
use App\AssetScannedBarcode;
use App\AssetLocation;
use App\User;
use DataTables;
use Auth;
class AssetScannedBarcodeUploadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $locations      = AssetLocation::orderBy('location', 'asc')->get();
        $uploaded_by    = User::orderBy('email', 'asc')->get();
        return view('asset.scanned-barcode-upload.index', compact('locations', 'uploaded_by'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $locations      = AssetLocation::orderBy('location', 'asc')->get();
        return view('asset.scanned-barcode-upload.create', compact('locations'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $this->validate($request, [
            'description'       => 'nullable',
            'asset_location'    => 'nullable',
            'file'              => 'required|mimes:txt',
        ]);

        $description            = $request->description;
        $location_id            = $request->asset_location;
        
        $uploaded_file          = $request->file('file');
        $extension              = $uploaded_file->getClientOriginalExtension();
        $original_name          = pathinfo($uploaded_file->getClientOriginalName(), PATHINFO_FILENAME);
        $generated_file_name    = generate_unique_token();
        

        $file                   = $generated_file_name.'.'.$extension;
        $path                   = create_new_upload_dir('scanned-barcode-uploaded'); // public/uploads/scanned-barcode-uploaded/
        $is_uploaded            = $uploaded_file->move($path, $file);
        $slug_token             = generate_unique_token();

        $uploaded_by            = auth()->user()->id;

        $uploaded_data                   = new AssetScannedBarcodeUpload();
        $uploaded_data->file             = $file;
        $uploaded_data->file_extension   = $extension;
        $uploaded_data->file_name        = $original_name;
        $uploaded_data->slug_token       = $slug_token;
        $uploaded_data->description      = $description;
        $uploaded_data->location_id      = $location_id;
        $uploaded_data->uploaded_by      = $uploaded_by;
        $is_saved                        = $uploaded_data->save();

        if($is_saved){
            $file_contents      = File::get($path.$file);
            $file_contents_list = explode("\r\n", $file_contents);
            
            foreach($file_contents_list as $barcode){
                $filter                                 = [];
                $filter['barcode']                      = $barcode;
                $filter['scanned_barcode_upload_id']    = $uploaded_data->id;
                $udd_is_exist = AssetScannedBarcode::where($filter);
                if($barcode != '' && $udd_is_exist->count() <= 0){
                    $uploaded_data_details                              = new AssetScannedBarcode();
                    $uploaded_data_details->scanned_barcode_upload_id   = $uploaded_data->id;
                    $uploaded_data_details->barcode                     = $barcode;
                    $uploaded_data_details->location_id                 = $location_id;
                    $uploaded_data_details->uploaded_by                 = $uploaded_by;
                    $uploaded_data_details->save();
                }

            }

        }

        return redirect(route('asset_scanned_barcode_upload.index'))->with('success', 'Successfully Uploaded');
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
    public function edit($slug_token)
    {
        $data = AssetScannedBarcodeUpload::where('slug_token', $slug_token)
        ->firstOrFail();
        $locations      = AssetLocation::orderBy('location', 'asc')->get();

        return view('asset.scanned-barcode-upload.edit', compact('data', 'locations'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $slug_token)
    {
        $data = AssetScannedBarcodeUpload::where('slug_token', $slug_token)
        ->firstOrFail();

        $data->location_id = $request->asset_location;
        $data->description = $request->description;
        $is_saved          = $data->save();
        
        if($is_saved){
            $scanned_barcode = AssetScannedBarcode::where('scanned_barcode_upload_id', $data->id)->get();
            if($scanned_barcode){
                foreach($scanned_barcode as $row){
                    $selected_scanned_barcode               = AssetScannedBarcode::find($row->id);
                    $selected_scanned_barcode->location_id  = $data->location_id;
                    $selected_scanned_barcode->save();
                }    
            }
        }
        

        return redirect(route('asset_scanned_barcode_upload.index'))->with('success', 'Successfully Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($slug_token)
    {
        $data = AssetScannedBarcodeUpload::where('slug_token', $slug_token)
        ->firstOrFail();

        if($data->file){
            $path = public_path('uploads/scanned-barcode-uploaded/'.$data->file);
            if(file_exists($path)){
                unlink($path);
            }
        }

        $data->delete();

        return redirect(route('asset_scanned_barcode_upload.index'))->with('success', 'Successfully Deleted');
    }

    public function search_filter(Request $request){
        $location_id        = $request->asset_location;
        $uploaded_by        = $request->uploaded_by;
        $date_range_pick    = $request->date_range_pick;
        
        $filters    = [];
        
        if(!empty($location_id))
            $filters['asbu.location_id'] = $location_id;
        
        if(!empty($uploaded_by))
            $filters['asbu.uploaded_by'] = $uploaded_by;
        
        if(!empty($date_range_pick)){
            $date_array  = explode(" - ",$date_range_pick);
            $from = date('Y-m-d',strtotime($date_array[0]));
            $to = date('Y-m-d',strtotime($date_array[1]));
        }

        $to_select  = array(
            'asbu.id',
            'asbu.file',
            'asbu.file_name',
            'asbu.file_extension',
            'asbu.description',
            'asbu.slug_token',
            'asbu.created_at as date_added',
            'u.username as added_by_username',
            'u.email as added_by_email',
            'l.location',
        );

        if(!empty($date_range_pick)){
            $data = AssetScannedBarcodeUpload::from('asset_scanned_barcode_uploads as asbu')
            ->select($to_select)
            ->join('users as u', 'u.id', '=', 'asbu.uploaded_by')
            ->leftJoin('asset_locations as l', 'l.id', '=', 'asbu.location_id')
            ->where($filters)
            ->whereRaw("asbu.created_at >= ? AND asbu.created_at <=?",[$from.' 00:00:00',$to.' 23:59:59'])
            ->orderBy('asbu.created_at', 'desc')
            ->get();
        }else{
            $data = AssetScannedBarcodeUpload::from('asset_scanned_barcode_uploads as asbu')
            ->select($to_select)
            ->join('users as u', 'u.id', '=', 'asbu.uploaded_by')
            ->leftJoin('asset_locations as l', 'l.id', '=', 'asbu.location_id')
            ->where($filters)
            ->orderBy('asbu.created_at', 'desc')
            ->get();
        }


        $data_tables = Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('id', function($row){
                $url_edit = route('asset_scanned_barcode_upload.edit', [$row->slug_token]);
                $response       = '<a data-sort="'.$row->id.'" href="'.$url_edit.'">'.$row->id.'</a>';
                return $response;
            })
            ->addIndexColumn()
            ->addColumn('file', function($row){
                $original_file  = $row->file_name.'.'.$row->file_extension;
                $response       = '<a data-sort="'.$original_file.'" href="'.asset('uploads/scanned-barcode-uploaded/'.$row->file).'" target="_blank" download="'.$original_file.'" title="Download"><i class="mdi mdi-download"></i> '.$original_file.'</a>';
                return $response;
            })
            ->addIndexColumn()
            ->addColumn('scanned_location', function($row){
                return $row->location;
            })
            ->addIndexColumn()
            ->addColumn('description', function($row){
                return ucfirst($row->description);
            })
            ->addIndexColumn()
            ->addColumn('added_by', function($row){
                if($row->added_by_username)
                    $response = $row->added_by_username;
                else
                    $response = $row->added_by_email;

                return $response;
            })
            ->addIndexColumn()
            ->addColumn('date_added', function($row){
                $label      = date(get_date_format(), strtotime($row->date_added));
                $data_sort  = date('Y-m-d', strtotime($row->date_added));
                $response   = '<label data-sort="'.$data_sort.'">'.$label.'</label>';
                return $response;
            })
            ->addIndexColumn()
            ->addColumn('action', function($row){
                $url_edit = route('asset_scanned_barcode_upload.edit', [$row->slug_token]);
				$response="";
				if((check_permission(Auth::user()->Employee->department_id,"Scanned Upload","full")) || (check_permission(Auth::user()->Employee->department_id,"Scanned Upload","Edit"))){
				$response = '<button  type="button" class="btn btn-outline-secondary btn-rounded btn-icon btn-sm" onclick="window.location.href = \''.$url_edit.'\';"><i style="margin-left: -6px;" class="mdi mdi-lead-pencil"></i></button>';
				}
				if((check_permission(Auth::user()->Employee->department_id,"Scanned Upload","full")) || (check_permission(Auth::user()->Employee->department_id,"Scanned Upload","Delete"))){
                $response .= ' <button type="button" data-toggle="modal" data-id="'.$row->id.'" data-slug_token="'.$row->slug_token.'" data-target="#DeleteModal" class="btn btn-outline-secondary btn-rounded btn-icon btn-sm btn-delete_row ml-3 "><i style="margin-left: -7px;" class="mdi mdi-delete"></i></button>';
				}
                return $response;
            })
            ->rawColumns(['id', 'file','scanned_location','description','added_by','date_added', 'action'])
            ->make(true);

        return $data_tables;
    }
}
