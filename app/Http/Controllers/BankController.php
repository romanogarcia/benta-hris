<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Bank;
use Session;
use DataTables;
use Auth;
use App\Companies;
use Validator;

class BankController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $banks = Bank::orderBy('bank_name')->paginate(10);

        return view('bank.index', compact('banks'));
            // ->with([
            //     'banknum' => Session::get("bankid"),
            //     'i' => (request()->input('page', 1) - 1) * 5
            // ]);
    }

    public function bank_list(Request $request)
    {
        $bank_id = $request->bank_id;
        
		$filters = [];
        if($bank_id!=''){
         	$filters['id'] = $bank_id;
        }
		$data = Bank::where($filters)->latest()->get();
        return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('bank_name', function($row){
                    return ($row->bank_name)??"";
                })
                ->addIndexColumn()
                ->addColumn('IBAN', function($row){
                    return ($row->IBAN)??"";
                })
                ->addIndexColumn()
                ->addColumn('BIC', function($row){
                    return ($row->BIC)??"";
                })
                ->addIndexColumn()
                ->addColumn('member_no', function($row){
                    return ($row->member_no)??"";
                })
                ->addIndexColumn()
                ->addColumn('clearing_no', function($row){
                    return ($row->clearning_no)??"";
                })
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '<a href="'.url('bank/' . $row->id . '/edit').'"><i class="mdi mdi-lead-pencil"></i></a>';
                    $btn .= '<a href="javascript:;" data-toggle="modal" onclick="deleteData('.$row->id.')" data-target="#DeleteModal"><i class="mdi mdi-delete"></i></a>';
                    return $btn;
                })

                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $url_edit = route('banks.edit', [$row->id]);
                    $btn = '<button type="button" class="btn btn-outline-secondary btn-rounded btn-icon btn-sm" onclick="window.location.href = \''.$url_edit.'\';"><i style="margin-left: -6px;" class="mdi mdi-lead-pencil"></i></button>';
                    $btn .= '<button type="button" data-toggle="modal" data-id="'.$row->id.'" data-target="#DeleteModal" onclick="deleteData('.$row->id.')" class="btn btn-outline-secondary btn-rounded btn-icon btn-sm btn-delete_row"><i style="margin-left: -7px;" class="mdi mdi-delete"></i></button>';
                    return $btn;
                })
                ->rawColumns(['bank_name','IBAN','BIC','member_no','clearing_no','action'])
                ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('bank.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $bank = new Bank;
        $bank->bank_name = $request->bank_name;
        $bank->address = $request->address;
        $bank->extra_address = $request->extra_address;
        $bank->city = $request->city;
        $bank->state = $request->state;
        $bank->zipcode = $request->zipcode;
        $bank->country = $request->country;
        $bank->iban = $request->iban;
        $bank->bic = $request->bic;
        $bank->member_no = $request->member_no;
        $bank->clearing_no = $request->clearing_no;
        $bank->save();

        return redirect('banks')->with('success', 'Bank record successfully added.');
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
        $bank = Bank::findOrFail($id);
        return view('bank.edit', compact('bank'));
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
        $bank = Bank::findOrFail($id);
        $bank->bank_name = $request->bank_name;
        $bank->address = $request->address;
        $bank->extra_address = $request->extra_address;
        $bank->city = $request->city;
        $bank->state = $request->state;
        $bank->zipcode = $request->zipcode;
        $bank->country = $request->country;
        $bank->iban = $request->iban;
        $bank->bic = $request->bic;
        $bank->member_no = $request->member_no;
        $bank->clearing_no = $request->clearing_no;
        $bank->save();

        return redirect('banks')->with('success', 'Record successfully updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $bank = Bank::findOrFail($id);
        $bank->delete();

        $banks = Bank::orderBy('bank_name', 'asc')->get();
        return view('bank.index', compact('banks'))->with('success', 'Record deleted!');
    }

	public function settings()
	{
		$records = Companies::first();

        $date_format = array(
            'm-d-Y'     => 'MM-DD-YYYY',
            'Y-m-d'     => 'YYYY-MM-DD',
            'd-m-Y'     => 'DD-MM-YYYY',
        );
		return view('bank.bank_settings',['r'=>$records,'date_format'=>$date_format]);
	}	
	public function update_settings(Request $request)
	{
		$user = Auth::user();
		
		$rules = [
			'date_format' => 'nullable',
			'company_logo' => 'nullable|image|max:2000|dimensions:max_width=933,max_height=250',
            'company_mobile_logo' => 'nullable|image|max:2000|dimensions:max_width=512,max_height=512',
			'browser_icon' => 'nullable|image|max:2000|dimensions:max_width=512,max_height=512',
		];
		    $customMessages = [
				'company_logo.dimensions' => 'The Logo allows image with 933px * 250px dimensions.',
				'company_mobile_logo.dimensions' => 'The Mobile Logo allows image with 512px * 512px dimensions.',
				'browser_icon.dimensions' => 'The Browser Icon allows image with 512px * 512px dimensions.'
			];

 
		$validate = Validator::make($request->all(), $rules,$customMessages);
		if($validate->fails()){
			return back()->withErrors($validate)->withInput();
		}
		
		$payroll_deactivate = $request->payroll_deactivate;
		$time_in_out_deactivate = $request->time_in_out_deactivate;
		if($payroll_deactivate){
			$user->payroll_deactivate = $payroll_deactivate;
		}
		else{
			$user->payroll_deactivate = 0;
		}
		if($time_in_out_deactivate){
			$user->time_in_out_deactivate = $time_in_out_deactivate;
		}
		else{
			$user->time_in_out_deactivate = 0;
		}
		$user->save();
		if($request->company_id!=''){
			
			$company = Companies::findOrFail($request->company_id);
			$new_name = null;
			$new_name1 = null;
			$new_name2 = null;
			if ($request->hasFile('company_logo')){
				 if ($company->company_logo != ''){
					if (file_exists(public_path('images/'. $company->company_logo))) {
						unlink(public_path('images/'. $company->company_logo));
					}
				}
				$image = $request->file('company_logo');
				$new_name = uniqid() . '.' . $image->getClientOriginalExtension();
				$image->move(public_path('images/'), $new_name);
			} 
			if ($request->hasFile('company_mobile_logo')){
				if ($company->company_mobile_logo != ''){
					if (file_exists(public_path('images/'. $company->company_mobile_logo))) {
						unlink(public_path('images/'. $company->company_mobile_logo));
					}
				}
				$image = $request->file('company_mobile_logo');
				$new_name1 = uniqid() . '.' . $image->getClientOriginalExtension();
				$image->move(public_path('images/'), $new_name1);
			}
			if ($request->hasFile('browser_icon')){
				if ($company->browser_icon != ''){
					if (file_exists(public_path('images/'. $company->browser_icon))) {
						unlink(public_path('images/'. $company->browser_icon));
					}
				}
				$image = $request->file('browser_icon');
				$new_name2 = uniqid() . '.' . $image->getClientOriginalExtension();
				$image->move(public_path('images/'), $new_name2);
			}
			if($new_name!=null){
				$company->company_logo = $new_name;
			}
			if($new_name1!=null){
				$company->company_mobile_logo = $new_name1;
			}
			if($new_name2!=null){
				$company->browser_icon = $new_name2;
			}
			if($request->nx_login_failed){
				$company->nx_login_failed = $request->nx_login_failed;
			}
			else{
				$company->nx_login_failed = 0;
			}
			$company->date_format = $request->date_format;
			$company->save();
		}	
		return redirect()->back()->with('success', 'Setting updated!');
	}	
    // public function bankSearch(Request $request)
    // {
    //     $bank_id = $request->bank_id;
        
	// 	$filters = [];
    //     if($bank_id!=''){
    //      	$filters['id'] = $bank_id;
    //     }
	// 	$data = Bank::where($filters)->latest()->get();
    //     return Datatables::of($data)
    //             ->addIndexColumn()
    //             ->addColumn('bank_name', function($row){
    //                 return ($row->bank_name)??"";
    //             })
    //             ->addIndexColumn()
    //             ->addColumn('IBAN', function($row){
    //                 return ($row->IBAN)??"";
    //             })
    //             ->addIndexColumn()
    //             ->addColumn('BIC', function($row){
    //                 return ($row->BIC)??"";
    //             })
    //             ->addIndexColumn()
    //             ->addColumn('member_no', function($row){
    //                 return ($row->member_no)??"";
    //             })
    //             ->addIndexColumn()
    //             ->addColumn('clearing_no', function($row){
    //                 return ($row->clearning_no)??"";
    //             })
    //             ->addIndexColumn()
    //             ->addColumn('action', function($row){
    //                 // $btn = '<a href="'.url('bank/' . $row->id . '/edit').'"><i class="mdi mdi-lead-pencil"></i></a>';
    //                 // $btn .= '<a href="javascript:;" data-toggle="modal" onclick="deleteData('.$row->id.')" data-target="#DeleteModal"><i class="mdi mdi-delete"></i></a>';


    //                 $url_edit = route('roles.edit', [$row->id]);
    //                 $btn = '<button type="button" class="btn btn-outline-secondary btn-rounded btn-icon btn-sm" onclick="window.location.href = \''.$url_edit.'\';"><i style="margin-left: -6px;" class="mdi mdi-lead-pencil"></i></button>';
    //                 $btn .= '<button type="button" data-toggle="modal" data-id="'.$row->id.'" data-target="#DeleteModal" class="btn btn-outline-secondary btn-rounded btn-icon btn-sm btn-delete_row"><i style="margin-left: -7px;" class="mdi mdi-delete"></i></button>';
    //                 return $btn;
    //             })
    //             ->rawColumns(['bank_name','IBAN','BIC','member_no','clearing_no','action'])
    //             ->make(true);
                    
    // }
}
