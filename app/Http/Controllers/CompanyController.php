<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use Validator;
use App\Companies;
use App\Country;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /* FOR LIST */
        // $records = Companies::paginate(5);
        // return view('company.list',['record'=>$records]);

        /* Redirect to Specific Company Information (Default) */
        $records = Companies::first();
        if($records != null){
            return redirect(route('company.edit', [$records->id]));
        }else{
            return redirect(route('company.create'));

        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = auth()->user();

        if($user == null)
        {
            return redirect('/home')->with('success', 'Please Login your account');
        }
        else 
        {
            $records = Companies::first();
            if($records != null){
                return redirect(route('company.edit', [$records->id]));
            }else{
                $countries = Country::orderBy('country_name', 'asc')
                ->get(array('country_name', 'id'));

                $date_format = array(
                    'm-d-Y'     => 'MM-DD-YYYY',
                    'Y-m-d'     => 'YYYY-MM-DD',
                    'd-m-Y'     => 'DD-MM-YYYY',
                );
                return view('company.create', ['countries' => $countries, 'date_format' => $date_format]);
            }
        }   
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

        $regex_url = '/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/';

        $rules = [
            'company_name' => 'required',
            'email' => 'required|email',
            'date_format' => 'nullable',
            'address' => 'required',
            'website' => 'nullable|regex:'.$regex_url,
            'city' => 'nullable',
            'zip_code' => 'nullable',
            'country_id' => 'required',
            'extra_address' => 'nullable',
            'phone' => 'nullable',
            'business_number' => 'nullable',
            'tax_number' => 'nullable',
        ];
        
        $custom_message = [
            'website.regex' => 'Please enter valid URL'
        ];

        $validatedData = $this->validate($request, $rules, $custom_message);
        // dd($validatedData);

        Companies::create($validatedData);
        return redirect('company')->with('success', 'Company successfully created');
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
     * @param  \App\Companies  $company
     * @return \Illuminate\Http\Response
     */
    public function edit(Companies $company)
    {
        $records = Companies::findOrFail($company->id);
        $countries = Country::orderBy('country_name', 'asc')
        ->get(array('country_name', 'id'));

        $date_format = array(
            'm-d-Y'     => 'MM-DD-YYYY',
            'Y-m-d'     => 'YYYY-MM-DD',
            'd-m-Y'     => 'DD-MM-YYYY',
        );

        
        return view('company.edit', ['r' => $records, 'countries' => $countries, 'date_format' => $date_format]);
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
        $regex_url = '/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/';

        $rules = [
            'company_name' => 'required',
            'email' => 'required|email',
            'address' => 'required',
            'website' => 'nullable|regex:'.$regex_url,
            'city' => 'nullable',
            'zip_code' => 'nullable',
            'country_id' => 'required',
            'extra_address' => 'nullable',
            'phone' => 'nullable',
            'business_number' => 'nullable',
            'tax_number' => 'nullable',
        ];
        
        $custom_message = [
            'website.regex' => 'Please enter valid URL'
        ];

        $validatedData = $this->validate($request, $rules, $custom_message);

        Companies::where('id', $id)->update($validatedData);

        // return redirect('company')->with('success', 'Company successfully updated');
        return redirect()->back()->with('success', 'Company successfully updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(Companies::destroy($id)){
            return redirect()->back()->with('success','Company deleted successfully!');
        } else {
            return redirect()->back()->with('error','Request Failed!');
        }
    }
}
