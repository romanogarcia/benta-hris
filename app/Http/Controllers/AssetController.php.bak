<?php

namespace App\Http\Controllers;
use App\Asset;
use App\AssetCategory;
use App\Employee;
use App\AssetLocation;
use App\AssetSupplier;
use Illuminate\Http\Request;
use Auth;
use DataTables;
use Excel;
use PDF;
class AssetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $to_select      = array('*');

        $categories     = AssetCategory::select($to_select)->orderBy('created_at', 'desc')->get();
        $locations      = AssetLocation::select($to_select)->orderBy('created_at', 'desc')->get();
        $employees      = Employee::select($to_select)->orderBy('created_at', 'desc')->get();
        $suppliers      = AssetSupplier::select($to_select)->orderBy('created_at', 'desc')->get();
        $date           = date('Y-m-d');

        return view('asset.inventory.index', compact('categories','locations','employees','suppliers','date'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $to_select      = array('*');

        $categories     = AssetCategory::select($to_select)->orderBy('created_at', 'desc')->get();
        $locations      = AssetLocation::select($to_select)->orderBy('created_at', 'desc')->get();
        $employees      = Employee::select($to_select)->orderBy('created_at', 'desc')->get();
        $suppliers      = AssetSupplier::select($to_select)->orderBy('created_at', 'desc')->get();
        $date           = date('Y-m-d');

        return view('asset.inventory.create', compact('categories','locations','employees','suppliers','date'));
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
            'property_number'   => 'required',
            'item_description'  => 'required',
            'asset_no'          => 'nullable',
            'serial_number'     => 'required',
            'mr_number'         => 'required',
            'po_no'             => 'required',
            'acquisition_cost'  => 'required|regex:/^[0-9]{1,}(,[0-9]{3})*(\.[0-9]+)*$/',
            'date_acquired'     => 'required',
            'condition'         => 'required',
            'warranty'          => 'nullable',
            'employee'          => 'required|numeric',
            'location'          => 'required',
            'supplier'          => 'required',
            'category'          => 'required',
                
        ]);
        if($validatedData){
            //Select box
            $category_id                = $request->category;
            $supplier_id                = $request->supplier;
            $location_id                = $request->location;    
            $added_by                   = auth()->user()->id; //get the id of the current user
            $accountable_employee_id    = $request->employee;
            $item_description           = $request->item_description;
            $acquisition_cost           = str_replace(',', '',$request->acquisition_cost);
            $asset_number               = $request->asset_no;
            $po_number                  = $request->po_no;
            $mr_number                  = $request->mr_number;
            $warranty                   = $request->warranty;
            $serial_number              = $request->serial_number;
            $property_number            = $request->property_number;
            $condition                  = $request->condition;
            $date_acquired              = \Carbon\Carbon::createFromFormat(get_date_format(), $request->date_acquired)->format('Y-m-d');
            $slug_token                 = generate_unique_token();

            //add new
            $asset = new Asset();
            
            // foreign keys
            $asset->employee_id             = $accountable_employee_id;
            $asset->category_id             = $category_id;
            $asset->supplier_id             = $supplier_id;
            $asset->location_id             = $location_id;
            $asset->added_by                = $added_by;
            
            //mandatory fields 
            $asset->item_description        = $item_description;
            $asset->acquisition_cost        = $acquisition_cost;
            $asset->asset_number            = $asset_number;
            $asset->purchase_order_number   = $po_number;
            $asset->mr_number               = $mr_number;
            $asset->warranty                = $warranty;
            $asset->serial_number           = $serial_number;
            $asset->property_number         = $property_number;
            $asset->date_acquired           = $date_acquired;
            $asset->condition               = $condition;
            $asset->slug_token              = $slug_token;
            $is_saved                       = $asset->save();
        

        if($is_saved)
            return redirect(route('asset_inventory.index'))->with('success', 'Successfully Added');
        else
            return redirect(route('asset_inventory.index'))->with('error', 'An error occured while adding the data');
            
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
        $to_select  = array('*');
        //findOrFail
        $data           = Asset::where('assets.slug_token','=',$slug_token)
        ->select($to_select)
        ->leftJoin('asset_categories','asset_categories.id','=','assets.category_id')
        ->leftJoin('asset_locations','asset_locations.id','=','assets.location_id')
        ->join('employees','employees.id','=','assets.employee_id')
        ->leftJoin('asset_suppliers','asset_suppliers.id','=','assets.supplier_id')
        ->firstOrFail();
        
        
        
        $categories     = AssetCategory::select($to_select)->orderBy('created_at', 'desc')->get();
        $locations      = AssetLocation::select($to_select)->orderBy('created_at', 'desc')->get();
        $employees      = Employee::select($to_select)->orderBy('created_at', 'desc')->get();
        $suppliers      = AssetSupplier::select($to_select)->orderBy('created_at', 'desc')->get();
        
        return view('asset.inventory.edit', compact('data','categories','locations','employees','suppliers'));

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
        $validatedData = $this->validate($request, [
            'property_number'   => 'required',
            'item_description'  => 'required',
            'asset_no'          => 'nullable',
            'serial_number'     => 'required',
            'mr_number'         => 'required',
            'po_no'             => 'required',
            'acquisition_cost'  => 'required|regex:/^[0-9]{1,}(,[0-9]{3})*(\.[0-9]+)*$/',
            'date_acquired'     => 'required',
            'condition'         => 'required',
            'warranty'          => 'nullable',
            'employee'          => 'required|numeric',
            'location'          => 'required',
            'supplier'          => 'required',
            'category'          => 'required',
        ]);
            
        $asset                          = Asset::where('slug_token', $slug_token)->firstOrFail();
        $asset->location_id             = $request->location;
        $asset->category_id             = $request->category;
        $asset->supplier_id             = $request->supplier;
        $asset->location_id             = $request->location;    
        $asset->employee_id             = $request->employee;
        $asset->item_description        = $request->item_description;
        $asset->acquisition_cost        = str_replace(',', '',$request->acquisition_cost);
        $asset->asset_number            = $request->asset_no;
        $asset->purchase_order_number   = $request->po_no;
        $asset->mr_number               = $request->mr_number;
        $asset->warranty                = $request->warranty;
        $asset->serial_number           = $request->serial_number;
        $asset->property_number         = $request->property_number;
        $asset->condition               = $request->condition;
        $asset->date_acquired           = \Carbon\Carbon::createFromFormat(get_date_format(), $request->date_acquired)->format('Y-m-d');
        $is_saved                       = $asset->save();

        if($is_saved)
            return redirect(route('asset_inventory.index'))->with('success', 'Successfully Updated');
        else
            return redirect(route('asset_inventory.index'))->with('error', 'An error occured while updating the data');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($slug_token)
    {
        $asset          = Asset::where('slug_token', $slug_token)->firstOrFail();
        $is_deleted     = $asset->delete();

        if($is_deleted)
            return redirect(route('asset_inventory.index'))->with('success', 'Successfully Deleted');
        else
            return redirect(route('asset_inventory.index'))->with('error', 'An error occured while deleting the data');
    }

    public function search_filter(Request $request){
        $accountable_employee_id       = $request->employee_id;
        $location_id                   = $request->location_id;
        $property_number               = $request->property_number;
        $category_id                   = $request->category_id;
        $supplier_id                   = $request->supplier_id;
        $date_range_pick               = $request->date_range_pick;

        $to_select = array('assets.id',
                           'assets.property_number',
                           'assets.item_description',
                           'assets.acquisition_cost',
                           'assets.date_acquired',
                           'assets.slug_token',
                           'assets.condition',
                           'employees.first_name',
                           'employees.last_name',
                           'asset_locations.location',
                           );
        
        $filters = [];
        if(!empty($accountable_employee_id)){
            $filters['assets.employee_id']      = $accountable_employee_id;
        }
        if(!empty($location_id)){
            $filters['assets.location_id']      = $location_id;
        }
        if(!empty($property_number)){
            $filters['assets.property_number']  = $property_number;
        }
        if(!empty($category_id)){
            $filters['assets.category_id']      = $category_id;
        }
        if(!empty($supplier_id)){
            $filters['assets.supplier_id']      = $supplier_id;
        }

        if(!empty($date_range_pick)){
            $date_array  = explode(" - ",$date_range_pick);
            $from = date('Y-m-d',strtotime($date_array[0]));
            $to = date('Y-m-d',strtotime($date_array[1]));

            $data = Asset::where($filters)
            ->select($to_select)
            ->Leftjoin('asset_locations','asset_locations.id','=','assets.location_id')
            ->join('employees','employees.id','=','assets.employee_id')
            ->whereRaw("assets.created_at >= ? AND assets.created_at <=?",[$from.' 00:00:00',$to.' 23:59:59'])
            ->orderBy('assets.created_at','desc')
            ->get();
        }else{
            $data = Asset::where($filters)
            ->select($to_select)
            ->leftJoin('asset_locations','asset_locations.id','=','assets.location_id')
            ->join('employees','employees.id','=','assets.employee_id')
            ->orderBy('assets.created_at','desc')
            ->get();    
        }
        
        $data_tables = Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('property_no', function($row){
                $url_edit = route('asset_inventory.edit', [$row->slug_token]);
				if((check_permission(Auth::user()->Employee->department_id,"Inventory","full")) || (check_permission(Auth::user()->Employee->department_id,"Inventory","Edit"))){
                $response = '<a href="'.$url_edit.'">'.$row->property_number.'</a>';
                return $response;
				}
				else{
					return $row->property_number;
				}
				
            })
			->addIndexColumn()
            ->addColumn('item_description', function($row){
                return ucfirst($row->item_description);
            })
            ->addIndexColumn()
            ->addColumn('acquisition_cost', function($row){
                return $row->acquisition_cost;
            })
            ->addIndexColumn()
            ->addColumn('date_acquired', function($row){
                $label      = date(get_date_format(), strtotime($row->date_acquired));
                $data_sort  = date('Y-m-d', strtotime($row->date_acquired));
                $response   = '<label data-sort="'.$data_sort.'">'.$label.'</label>';
                return $response;
            })
            ->addIndexColumn()
            ->addColumn('location', function($row){
                return ucfirst($row->location);
            })
            ->addIndexColumn()
            ->addColumn('condition', function($row){
                return ucfirst($row->condition);
            })
            ->addColumn('employee', function($row){
                return ucfirst($row->first_name)." ".ucfirst($row->last_name);
            })
			->addIndexColumn()
            ->addColumn('action', function($row){
				$url_edit = route('asset_inventory.edit', [$row->slug_token]);
				$response="";
				if((check_permission(Auth::user()->Employee->department_id,"Inventory","full")) || (check_permission(Auth::user()->Employee->department_id,"Inventory","Edit"))){
				$response = '<button  type="button" class="btn btn-outline-secondary btn-rounded btn-icon btn-sm" onclick="window.location.href = \''.$url_edit.'\';"><i style="margin-left: -6px;" class="mdi mdi-lead-pencil"></i></button>';
				}
				if((check_permission(Auth::user()->Employee->department_id,"Inventory","full")) || (check_permission(Auth::user()->Employee->department_id,"Inventory","Delete"))){
                $response .= ' <button type="button" data-toggle="modal" data-id="'.$row->slug_token.'" data-target="#DeleteModal" class="btn btn-outline-secondary btn-rounded btn-icon btn-sm btn-delete_row ml-3 "><i style="margin-left: -7px;" class="mdi mdi-delete"></i></button>';
				}
            
                return $response;
			})
            ->rawColumns(['property_no', 'item_description','acquisition_cost','date_acquired','location','condition','employee', 'action'])
            ->make(true);
            
        return $data_tables;
    }
    public function report_index()
    {
        $to_select = array('*');

        $categories = AssetCategory::select($to_select)->orderBy('created_at', 'desc')->get();
        $locations = AssetLocation::select($to_select)->orderBy('created_at', 'desc')->get();
        $employees = Employee::select($to_select)->orderBy('created_at', 'desc')->get();
        $suppliers = AssetSupplier::select($to_select)->orderBy('created_at', 'desc')->get();
        $date=date('Y-m-d');
        return view('asset.report.index', compact('categories','locations','employees','suppliers','date'));
    }

    public function inventory_report(Request $request)
    {
        $accountable_employee_id       = $request->employee_id;
        $location_id                   = $request->location_id;
        $property_number               = $request->property_number;
        $category_id                   = $request->category_id;
        $supplier_id                   = $request->supplier_id;
        $date_range_pick               = $request->date_range_pick;
        $export_type                   = $request->export_type;
        $employee                      = " ";
        $location                      = " ";   
        $property_no                   = " ";
        $category                      = " ";
        $supplier                      = " ";
        $row_count                     = " ";
        $to_select                     = array('assets.property_number',
                                               'assets.item_description',
                                               'assets.asset_number',
                                               'assets.serial_number',
                                               'assets.mr_number',
                                               'assets.serial_number',
                                               'assets.purchase_order_number',
                                               'assets.acquisition_cost',
                                               'assets.condition',
                                               'assets.warranty',
                                               'employees.first_name',
                                               'employees.last_name',
                                               'asset_locations.location',
                                               'asset_suppliers.supplier',
                                               'asset_categories.category',
                                               'assets.date_acquired');
        
        $filters = [];
        if(!empty($accountable_employee_id)){
            $filters['assets.employee_id']      = $accountable_employee_id;
        }
        if(!empty($location_id)){
            $filters['assets.location_id']      = $location_id;
        }
        if(!empty($property_number)){
            $filters['assets.property_number']  = $property_number;
        }
        if(!empty($category_id)){
            $filters['assets.category_id']      = $category_id;
        }
        if(!empty($supplier_id)){
            $filters['assets.supplier_id']      = $supplier_id;
        }
        if(!empty($date_range_pick)){
            $date_array  = explode(" - ",$date_range_pick);
            $from = date('Y-m-d',strtotime($date_array[0]));
            $to = date('Y-m-d',strtotime($date_array[1]));

            $data = Asset::where($filters)
            ->select($to_select)
            ->leftJoin('asset_locations','asset_locations.id','=','assets.location_id')
            ->join('employees','employees.id','=','assets.employee_id')
            ->leftJoin('asset_categories','asset_categories.id','=','assets.category_id')
            ->leftJoin('asset_suppliers','asset_suppliers.id','=','assets.supplier_id')
            ->whereRaw("assets.created_at >= ? AND assets.created_at <=?",[$from.' 00:00:00',$to.' 23:59:59'])
            ->orderBy('assets.created_at','desc')
            ->get();
        }else{
            $data = Asset::where($filters)
            ->select($to_select)
            ->leftJoin('asset_locations','asset_locations.id','=','assets.location_id')
            ->join('employees','employees.id','=','assets.employee_id')
            ->leftJoin('asset_categories','asset_categories.id','=','assets.category_id')
            ->leftJoin('asset_suppliers','asset_suppliers.id','=','assets.supplier_id')
            ->orderBy('assets.created_at','desc')
            ->get();
        }

        $rows           = [];
        $total_amount   = 0;

        foreach($data as $d)
        {
            $rows[]=array(
                'Property Number'   => $d->property_number,
                'Item Description'  => $d->item_description,
                'Asset Number'      => $d->asset_number,
                'Serial Number'     => $d->serial_number,
                'MR Number'         => $d->mr_number,
                'PO No.'            => $d->purchase_order_number,
                'Acquisition_Cost'  => $d->acquisition_cost,
                'Condition'         => $d->condition,
                'Warranty'          => $d->warranty,
                'Employee'          => ucfirst($d->first_name)." ".ucfirst($d->last_name),
                'Location'          => $d->location,
                'Supplier'          => $d->supplier,
                'Category'          => $d->category,
                'Date Acquired'     => $d->date_acquired,
                'Date Added'        => date(get_date_format(),strtotime($d->created_at)),
            );
            if(!empty($accountable_employee_id))
                $employee=ucwords($d->fname.' '.$d->lname);
            if(!empty($location_id))
                $location=$d->location;
            if(!empty($property_number))
                $property_no = $d->property_number;
            if(!empty($category_id))
                $category= $d->category;
            if(!empty($supplier_id))
                $supplier=ucwords($d->supplier);
            
            $total_amount           += $d->acquisition_cost;
     
        }
        if($data->count()==0)
            return redirect(route('asset_report.index'))->with('error', 'Empty Generated Report.');
        else {
            $count = $data->count();
        }

        $date=date('Y-m-d');
        if( $date_range_pick == ''){
           $from='';
           $to='';
        }
        $row_count=$count+6;

        if($export_type==="pdf"){
            $pdf = PDF::loadView('asset.report.pdf.generate-inventory-pdf', compact('data','total_amount'))->setPaper('A4', 'landscape');
        
            return $pdf->download('Asset Inventory Report'.date('Ymd').'_'.date('His').'.pdf');
        }
        else{
        Excel::create('Asset_inventory_report_'.date('Ymd').'_'.date('His'), function($excel) use ($rows,$date,$from,$to,$location,$employee,$property_no,$category,$supplier,$count,$row_count,$total_amount) {
            $excel->sheet('Asset_sheet', function($sheet) use ($rows,$date,$from,$to,$location,$employee,$property_no,$category,$supplier,$count,$row_count,$total_amount){

                $sheet->cells('A1', function ($cells) {
                    $cells->setFont(array(
                        'family' => 'arial',
                        'size' => '20',
                        'bold' => true));
                });
                $sheet->cells('A2:F2', function ($cells) {
                    $cells->setFont(array(
                        'family' => 'arial',
                        'size' => '14',
                        'bold' => true));
                });
                $sheet->cells('A4:O4', function ($cells) {
                    $cells->setFont(array(
                        'family' => 'arial',
                        'size' => '12',
                        'bold' => true));
                });
                $sheet->cells('N'.$row_count, function ($cells) {
                    $cells->setFont(array(
                        'family' => 'arial',
                        'size' => '14',
                        'horizontal' => 'right',
                        'bold' => true))->setAlignment('right');
                });
                $sheet->cells('O'.$row_count, function ($cells) {
                    $cells->setFont(array(
                        'family' => 'arial',
                        'size' => '14',
                        'bold' => true));
                });
                $sheet->mergeCells('A1:O1');
                $sheet->cell('A1', function($cell) {
                    $cell->setValue('ASSET INVENTORY REPORT')->setAlignment('center');
                });
                $sheet->setCellValue('A2', 'Date: ' . $date);
                if($employee != ' ')
                    $sheet->setCellValue('B2', 'Employee: '.$employee);
                if($location != ' ')
                    $sheet->setCellValue('C2', 'Location: '.$location);
                if($property_no != ' ')
                    $sheet->setCellValue('D2', 'Property Number: '.$property_no);
                if($category != ' ')
                    $sheet->setCellValue('E2', 'Category: '.$category);
                if($supplier != ' ')
                    $sheet->setCellValue('F2', 'Supplier: '.$supplier);

                if($from != '' && $to != '') {
                    $sheet->setCellValue('I2', 'From: ' . $from );
                    $sheet->setCellValue('J2', 'To: ' . $to);
                }

                $sheet->fromArray($rows, null, 'A4', true);
                $sheet->setCellValue('N'.$row_count,'ACQUISITION COST:');
                $sheet->setCellValue('O'.$row_count, $total_amount);
                $row_count++;
                $sheet->cells('N'.$row_count, function ($cells) {
                    $cells->setFont(array(
                        'family' => 'arial',
                        'size' => '14',
                        'horizontal' => 'right',
                        'bold' => true))->setAlignment('right');
                });
                $sheet->cells('O'.$row_count, function ($cells) {
                    $cells->setFont(array(
                        'family' => 'arial',
                        'size' => '14',
                        'bold' => true));
                });
                $sheet->setCellValue('N'.$row_count,'TOTAL ASSET:');
                $sheet->setCellValue('O'.$row_count, $count);
                });
            
            })->download('xlsx');
        }
    }

    
}