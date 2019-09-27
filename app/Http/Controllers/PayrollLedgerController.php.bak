<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Employee;
use App\Department;
use App\Payroll;
Use App\Attendance;
use App\Holiday;
use App\Companies;
Use App\OvertimeRequests as ot;
use DB;
use Session;
use Illuminate\Support\Facades\Input;
use PDF;
use DateTime;
use Auth;

class PayrollLedgerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		$employees = [];
		if(Auth::user()->employee->account_type == 1){
        $employees = Employee::all();
		}
		else{
			 $employees = Employee::where(['id'=>Auth::user()->employee->id])->get();
		}
        $departments = Department::all();

        //return payrolls based on payroll_type = 1
        $payrolls = Payroll::where('payroll_type', 1)
                        ->orderBy('id', 'desc')
                        ->paginate(100);
                        
        return view('payroll_ledger.index', compact('employees', 'departments', 'payrolls'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        // dd($request);
        $employees = Employee::orderBy('last_name')
                        ->select('id', 'employee_number', 'first_name', 'middle_name', 'last_name')
                        ->get();

        $payroll_number = $this->generateBillAndPayrollNumber()->payroll_number;
        return view('payroll_ledger.preview', compact('employees','payroll_number'));
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
            'employee_id' => 'required',
            'updated_payroll_date' => 'required',
            'updated_period_to' => 'required',
            'updated_period_from' => 'required',
        ]);
		
        $er_details = array(
            "basic"                     => ($request->basic) ? $request->basic:'0.00',
            "food_allowance"            => ($request->er_food_allowance) ? $request->er_food_allowance:'0.00',
            "transportation_allowance"  => ($request->er_transportation_allowance) ? $request->er_transportation_allowance:'0.00',
            "personal_allowance"        => ($request->er_personal_allowance) ? $request->er_personal_allowance:'0.00',
            "regular_holiday_pay"       => ($request->er_regular_holiday_pay) ? $request->er_regular_holiday_pay:'0.00',
            "special_holiday_pay"       => ($request->er_special_holiday_pay) ? $request->er_special_holiday_pay:'0.00',
            "restday_days_pay"          => ($request->er_restday_days_pay) ? $request->er_restday_days_pay:'0.00',
            "regular_ot_pay"            => ($request->er_regular_ot_pay) ? $request->er_regular_ot_pay:'0.00',
            "special_ot_pay"            => ($request->er_special_ot_pay) ? $request->er_special_ot_pay:'0.00',
            "restday_ot_pay"            => ($request->er_restday_ot_pay) ? $request->er_restday_ot_pay:'0.00',
            "sss"                       => ($request->er_sss) ? $request->er_sss:'0.00',
            "philhealth"                => ($request->er_philhealth) ? $request->er_philhealth:'0.00',
            "pagibig"                   => ($request->er_pagibig) ? $request->er_pagibig:'0.00',
            "hmo"                       => ($request->er_hmo) ? $request->er_hmo:'0.00',
            "witholding_tax"            => ($request->er_witholding_tax) ? $request->er_witholding_tax:'0.00',
        );
        $er_details = json_encode($er_details);
        // dd($er_details);
		
        $perpage = $request->query('perpage',10);

        $data['employee_id']        = $request->updated_employee_id ?? $request->employee_id;
        $data['name']               = $request->employee_name;
        if(!$request->total_hours){
            $request->total_hours   = 0;
        }
        $data['total_hours']        = $request->total_hours;

        $data['period']             = ($request->updated_period_from != '' && $request->updated_period_to != '') ? \Carbon\Carbon::createFromFormat(get_date_format(), $request->updated_period_from)->format('Y-m-d'). ' - ' .\Carbon\Carbon::createFromFormat(get_date_format(), $request->updated_period_to)->format('Y-m-d'): $request->period;
        $data['period_from']        = \Carbon\Carbon::createFromFormat(get_date_format(), $request->updated_period_from)->format('Y-m-d'); //$request->updated_period_from ?? $request->period_from;
        $data['period_to']          = \Carbon\Carbon::createFromFormat(get_date_format(), $request->updated_period_to)->format('Y-m-d'); //$request->updated_period_to ?? $request->period_to;
        $data['gross']              = $request->ee_gross;

        $data['days'] = array(
            'regular_holidays'          => $request->regular_holidays,
            'regular_holiday_pay'       => number_format($request->ee_regular_holiday_pay,2,'.',''),
            'special_holidays'          => $request->special_holidays,
            'special_holiday_pay'       => number_format($request->ee_special_holiday_pay,2,'.',''),
            'restday_days'              => $request->restday_days,
            'restday_days_pay'          => number_format($request->ee_restday_days_pay,2,'.',''),
            'rest_and_holiday_hours'    => $request->rest_and_holiday_hours
        );
        $data['allowances'] = array(
            'food_allowance'            => $fa = number_format($request->ee_food_allowance,2,'.',''),
            'transportation_allowance'  => $ta = number_format($request->ee_transportation_allowance,2,'.',''),
            'personal_allowance'        => $pa = number_format($request->ee_personal_allowance,2,'.',''),
            'total_allowances'          => number_format($fa + $ta + $pa,2,'.',''),
        );
        $data['overtimes'] = array(
            'total_hours'               => $request->ot_total_hours,
            'special_ot_pay'            => number_format($request->ee_special_ot_pay,2,'.',''),
            'regular_ot_pay'            => number_format($request->ee_regular_ot_pay,2,'.',''),
            'restday_ot_pay'            => number_format($request->ee_restday_ot_pay,2,'.',''),
            'total_overtime_pay'        => number_format($request->total_overtime_pay,2,'.',''),
            'special_holiday_hours'     => $request->special_holiday_hours,
            'regular_holiday_hours'     => $request->regular_holiday_hours,
            'rest_day_hours'            => $request->rest_day_hours
        );
        $data['sss'] = array(
            'EE'                        => $ee = number_format($request->ee_sss * -1,2,'.',''), // -1 is representation only on preview
            'ER'                        => $er = number_format($request->er_sss,2,'.',''),
            'total_contribution'        => number_format($ee + $er,2,'.','')
        );
        $data['philhealth'] = array(
            'EE'                        => $ee = number_format($request->ee_philhealth * -1,2,'.',''), // -1 is representation only on preview
            'ER'                        => $er = number_format($request->er_philhealth,2,'.',''),
            'total_contribution'        => number_format($ee + $er,2,'.','')
        );
        $data['pagibig'] = array(
            'EE'                        => $ee = number_format($request->ee_pagibig * -1,2,'.',''), // -1 is representation only on preview
            'ER'                        => $er = number_format($request->er_pagibig,2,'.',''),
            'total_contribution'        => number_format($ee + $er,2,'.','')
        );
        $data['total_deduction']        = number_format($request->ee_deductions,2,'.','');
        $data['netpay']                 = number_format($request->ee_net,2,'.','');
        $data['tax'] = array(
            'non_taxable_income'        => number_format($request->ee_non_taxable_income,2,'.',''),
            'taxable_income'            => number_format($request->ee_taxable_income,2,'.',''),
            'witholding_tax'            => number_format($request->ee_witholding_tax,2,'.','')
        );
        $data['payroll_type']           = 1;
        $data['payroll_date']           = $request->updated_payroll_date ? \Carbon\Carbon::createFromFormat(get_date_format(), $request->updated_payroll_date)->format('Y-m-d'):$request->updated_payroll_date;
        $data['is_paid']                = $request->is_paid == NULL ? 0 : 1;
        $data['description']            = $request->description;
        $data['notes']                  = $request->notes;


        $nr                             = new Payroll;
        $nr->billing_number             = $this->generateBillAndPayrollNumber()->billing_number;
        $nr->payroll_number             = $this->generateBillAndPayrollNumber()->payroll_number;
        $nr->employee_id                = $data['employee_id'];
        $nr->name                       = $data['name'];
        $nr->er_details                 = $er_details;
        $nr->period                     = $data['period'];
        $nr->period_from                = $data['period_from'];
        $nr->period_to                  = $data['period_to'];
        $nr->total_hours                = $data['total_hours'];
        $nr->days                       = json_encode($data['days']);
        $nr->overtimes                  = json_encode($data['overtimes']);
        $nr->allowances                 = json_encode($data['allowances']);
        $nr->gross                      = $data['gross'];
        $nr->sss                        = json_encode($data['sss']);
        $nr->philhealth                 = json_encode($data['philhealth']);
        $nr->pagibig                    = json_encode($data['pagibig']);
        $nr->hmo                        = $request->ee_hmo;
        $nr->total_deduction            = $data['total_deduction'];
        $nr->basic_pay                  = $data['gross'];
        $nr->tax                        = json_encode($data['tax']);
        $nr->netpay                     = $data['netpay'];
        $nr->payroll_type               = $data['payroll_type'];
        $nr->payroll_date               = $data['payroll_date'];
        $nr->description                = $data['description'];
        $nr->notes                      = $data['notes'];
        $nr->is_paid                    = $data['is_paid'];
        if($nr->save()){
            $perpage        = $request->perpage ?? 100;
            $payrolls       = Payroll::where('payroll_number', $nr->payroll_number)->where('billing_number', $nr->billing_number)->paginate($perpage);
            $allowances     = [];   
            $overtime       = [];     
            $sss            = [];          
            $philhealth     = [];   
            $pagibig        = [];      
            $tax            = [];          
            $wdays          = [];   
            foreach($payrolls as $payroll){
                $allowances[]   = json_decode($payroll->allowances,true);
                $overtime[]     = json_decode($payroll->overtimes,true);
                $sss[]          = json_decode($payroll->sss,true);
                $philhealth[]   = json_decode($payroll->philhealth,true);
                $pagibig[]      = json_decode($payroll->pagibig,true);
                $tax[]          = json_decode($payroll->tax,true);
                $wdays[]        = json_decode($payroll->days,true);
            }
            
        Session(['payroll_number' => $nr->payroll_number]);
        // return redirect()->route('payrollledger.create')->withInput(Input::except('payroll_number'))->with('success', 'Payroll successfully saved.');
        return redirect()->back()->with('success', 'Payroll ' . Session('payroll_number') . ' saved!')->withInput();
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
        $payroll = Payroll::join('employees', 'employees.id','=','payrolls.employee_id')
                    ->where('payrolls.id', $id)
                    ->where('payrolls.payroll_type', 1)
                    ->select('payrolls.*', 'employees.employee_number', 'employees.basic_salary', 'employees.address')
                    ->firstOrFail();

        $allowances   = json_decode($payroll->allowances,true);
        $overtime     = json_decode($payroll->overtimes,true);
        $sss          = json_decode($payroll->sss,true);
        $philhealth   = json_decode($payroll->philhealth,true);
        $pagibig      = json_decode($payroll->pagibig,true);
        $tax          = json_decode($payroll->tax,true);
        $wdays        = json_decode($payroll->days,true);

        //dd($payroll);

        return view('payroll_ledger.show', compact('payroll','allowances','overtime','sss','philhealth','pagibig','tax','days'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $payroll = Payroll::where('id', $id)->firstOrFail();
        
        $employee = Employee::where('id', $payroll->employee_id)->firstOrFail();

        $employees = Employee::orderBy('last_name')
                        ->select('id', 'employee_number', 'first_name', 'middle_name', 'last_name', 'address')
                        ->get();

        $allowances   = json_decode($payroll->allowances,true);
        $overtime     = json_decode($payroll->overtimes,true);
        $sss          = json_decode($payroll->sss,true);
        $philhealth   = json_decode($payroll->philhealth,true);
        $pagibig      = json_decode($payroll->pagibig,true);
        $tax          = json_decode($payroll->tax,true);
        $days         = json_decode($payroll->days,true);
        $er_details   = json_decode($payroll->er_details, true);
        // dd($er_details);
        // dd($er_details['personal_allowance']);
        
        return view('payroll_ledger.edit', compact('er_details', 'employee', 'payroll', 'employees', 'allowances','overtime','sss','philhealth','pagibig','tax','days'));
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
        
        $er_details = array(
            "basic"                     => ($request->basic) ? $request->basic:'0.00',
            "food_allowance"            => ($request->er_food_allowance) ? $request->er_food_allowance:'0.00',
            "transportation_allowance"  => ($request->er_transportation_allowance) ? $request->er_transportation_allowance:'0.00',
            "personal_allowance"        => ($request->er_personal_allowance) ? $request->er_personal_allowance:'0.00',
            "regular_holiday_pay"       => ($request->er_regular_holiday_pay) ? $request->er_regular_holiday_pay:'0.00',
            "special_holiday_pay"       => ($request->er_special_holiday_pay) ? $request->er_special_holiday_pay:'0.00',
            "restday_days_pay"          => ($request->er_restday_days_pay) ? $request->er_restday_days_pay:'0.00',
            "regular_ot_pay"            => ($request->er_regular_ot_pay) ? $request->er_regular_ot_pay:'0.00',
            "special_ot_pay"            => ($request->er_special_ot_pay) ? $request->er_special_ot_pay:'0.00',
            "restday_ot_pay"            => ($request->er_restday_ot_pay) ? $request->er_restday_ot_pay:'0.00',
            "sss"                       => ($request->er_sss) ? $request->er_sss:'0.00',
            "philhealth"                => ($request->er_philhealth) ? $request->er_philhealth:'0.00',
            "pagibig"                   => ($request->er_pagibig) ? $request->er_pagibig:'0.00',
            "hmo"                       => ($request->er_hmo) ? $request->er_hmo:'0.00',
            "witholding_tax"            => ($request->er_witholding_tax) ? $request->er_witholding_tax:'0.00',
        );
        $er_details = json_encode($er_details);
        // dd($request);

        $data['employee_id']        = $request->employee_id;
        $data['name']               = $request->employee_name;
        $data['total_hours']        = $request->total_hours;
        $data['period']             = ($request->updated_period_from != '' && $request->updated_period_to != '') ?\Carbon\Carbon::createFromFormat(get_date_format(), $request->updated_period_from)->format('Y-m-d'). ' - ' . \Carbon\Carbon::createFromFormat(get_date_format(), $request->updated_period_to)->format('Y-m-d') : $request->period;
        $data['period_from']        = ($request->updated_period_from != '') ? \Carbon\Carbon::createFromFormat(get_date_format(), $request->updated_period_from)->format('Y-m-d') : \Carbon\Carbon::createFromFormat(get_date_format(), $request->period_from)->format('Y-m-d');
        $data['period_to']          = ($request->updated_period_to != '') ? \Carbon\Carbon::createFromFormat(get_date_format(), $request->updated_period_to)->format('Y-m-d'): \Carbon\Carbon::createFromFormat(get_date_format(), $request->period_to)->format('Y-m-d');
        $data['gross']              = $request->ee_gross;

        $data['days'] = array(
            'regular_holidays'       => $request->regular_holidays,
            'regular_holiday_pay'    => number_format($request->ee_regular_holiday_pay,2,'.',''),
            'special_holidays'       => $request->special_holidays,
            'special_holiday_pay'    => number_format($request->ee_special_holiday_pay,2,'.',''),
            'restday_days'           => $request->restday_days,
            'restday_days_pay'       => number_format($request->ee_restday_days_pay,2,'.',''),
            'rest_and_holiday_hours' => $request->rest_and_holiday_hours
        );
        $data['allowances'] = array(
            'food_allowance'            => $fa = number_format($request->ee_food_allowance,2,'.',''),
            'transportation_allowance'  => $ta = number_format($request->ee_transportation_allowance,2,'.',''),
            'personal_allowance'        => $pa = number_format($request->ee_personal_allowance,2,'.',''),
            'total_allowances'          => number_format($fa + $ta + $pa,2,'.',''),
        );
        $data['overtimes'] = array(
            'total_hours'               => $request->ot_total_hours,
            'special_ot_pay'            => number_format($request->ee_special_ot_pay,2,'.',''),
            'regular_ot_pay'            => number_format($request->ee_regular_ot_pay,2,'.',''),
            'restday_ot_pay'            => number_format($request->ee_restday_ot_pay,2,'.',''),
            'total_overtime_pay'        => number_format($request->total_overtime_pay,2,'.',''),
            'special_holiday_hours'     => $request->special_holiday_hours,
            'regular_holiday_hours'     => $request->regular_holiday_hours,
            'rest_day_hours'            => $request->rest_day_hours
        );
        $data['sss'] = array(
            'EE'                        => $ee = number_format($request->ee_sss * -1,2,'.',''), // -1 is representation only on preview
            'ER'                        => $er = number_format($request->er_sss,2,'.',''),
            'total_contribution'        => number_format($ee + $er,2,'.','')
        );
        $data['philhealth'] = array(
            'EE'                        => $ee = number_format($request->ee_philhealth * -1,2,'.',''), // -1 is representation only on preview
            'ER'                        => $er = number_format($request->er_philhealth,2,'.',''),
            'total_contribution'        => number_format($ee + $er,2,'.','')
        );
        $data['pagibig'] = array(
            'EE'                        => $ee = number_format($request->ee_pagibig * -1,2,'.',''), // -1 is representation only on preview
            'ER'                        => $er = number_format($request->er_pagibig,2,'.',''),
            'total_contribution'        => number_format($ee + $er,2,'.','')
        );
        $data['total_deduction']    = number_format($request->ee_deductions,2,'.','');
        $data['netpay']             = number_format($request->ee_net,2,'.','');

        $data['tax'] = array(
            'non_taxable_income'    => number_format($request->ee_non_taxable_income,2,'.',''),
            'taxable_income'        => number_format($request->ee_taxable_income,2,'.',''),
            'witholding_tax'        => number_format($request->ee_witholding_tax,2,'.','')
        );
        $data['payroll_type']   = 1;
        $data['payroll_date']   = $request->updated_payroll_date? \Carbon\Carbon::createFromFormat(get_date_format(), $request->updated_payroll_date)->format('Y-m-d'): \Carbon\Carbon::createFromFormat(get_date_format(), $request->payroll_date)->format('Y-m-d');
        $data['is_paid']        = $request->is_paid == NULL ? 0 : 1;
        $data['description']    = $request->description;
        $data['notes']          = $request->notes;

        $nr                     = Payroll::findOrFail($id);
        $nr->er_details         = $er_details;
        $nr->period             = $data['period'];
        $nr->period_from        = $data['period_from'];
        $nr->period_to          = $data['period_to'];
        $nr->payroll_date       = $data['payroll_date'];
        $nr->days               = json_encode($data['days']);
        $nr->overtimes          = json_encode($data['overtimes']);
        $nr->allowances         = json_encode($data['allowances']);
        $nr->gross              = $data['gross'];
        $nr->sss                = json_encode($data['sss']);
        $nr->philhealth         = json_encode($data['philhealth']);
        $nr->pagibig            = json_encode($data['pagibig']);
        $nr->hmo                = $request->ee_hmo;
        $nr->total_deduction    = $data['total_deduction'];
        $nr->basic_pay          = $data['gross'];
        $nr->tax                = json_encode($data['tax']);
        $nr->netpay             = $data['netpay'];
        $nr->description        = $data['description'];
        $nr->notes              = $data['notes'];
        $nr->is_paid            = $data['is_paid'];
        $nr->payment_date       = $request->payment_date;
        $nr->revision           = $nr->revision + 1;
        $nr->save();

        return redirect()->back()->with('success','Payroll ' . $nr->payroll_number . ' updated.'); 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // dd($id);
        $payroll = Payroll::findOrFail($id);
        $payroll->delete();
        return redirect(route('payroll.search'))->with('success', 'Payroll successfully deleted');
    }

    /**
     * Generate Payroll Data for all valid employees
     * @param $request - Date range to be generated
     * @return void
     */
    public function generate(Request $request)
    {
        // query first employees table to verify employee and department
        $employee = Employee::where('user_id', $request->employee_id);
                        if($request->has('department_id') && $request->department_id != ''){
                            $employee = $employee->where('department_id', $request->department_id);
                        }
                            $employee = $employee->first();
        //return list of all employees for dropdown only
        $employees = Employee::orderBy('last_name')
                        ->select('id', 'employee_number', 'first_name', 'middle_name', 'last_name', 'address')
                        ->get();
        // if true, query the attendance table
        if($employee) {
			$from = \Carbon\Carbon::createFromFormat(get_date_format(),$request->from)->format('Y-m-d');
			$to = \Carbon\Carbon::createFromFormat(get_date_format(),$request->to)->format('Y-m-d');
			
            $days        = Attendance::Hours($request->employee_id, [$from,$to]);
			//print_r($days);die;
            $total_hours = $days['total_days'];
            $overtime = ot::Overtime($request->employee_id, [$from,$to]);
            $attendances = Attendance::where('employee_id',$request->employee_id)
                            ->whereBetween('at_date',[$from,$to])->get();
            $payroll = computePayroll($request->employee_id, $total_hours, $overtime, $attendances);
			//print_r($payroll);die;
            //additional
            $payroll['total_hours'] = $total_hours;
            $payroll['payroll_date'] =  \Carbon\Carbon::createFromFormat(get_date_format(),$request->payroll_date)->format('Y-m-d');
            $payroll['period_from'] = $from;
            $payroll['period_to'] = $to;
            $payroll['exists'] = 0;
            $payroll['payroll_number'] = $this->generateBillAndPayrollNumber()->payroll_number;
			$payroll['payroll_description'] = "Salary from ".$request->from." - ".$request->to;
            if ($request->ajax()) {
                return response()->json(array($payroll, $employee, $employees));
            } else {
                return view('payroll_ledger.preview', compact('employee', 'payroll', 'employees'));
            }
        } else {
            return redirect()->back()->with('error','Employee record not found'); 
        }
    }


    /**
     * Generate Bill number
     * @return string $bill_number
     */
    private function generateBillNumber($new=null)
    {
        $bill             = Payroll::orderby('id', 'desc')->first();
        $bill_number_init = $bill != null ? explode('-',$bill->billing_number) : array(0=>'BITS',1=>0);
        $bill_number_new  = $new == null ? $bill_number_init[1] + 1 : $bill_number_init[1];
        $bill_number      = "$bill_number_init[0]-$bill_number_new";
        return $bill_number;
    }


    public function getDepartment($id)
    {
        $departments = Department::findOrFail($id);
        $departments->employee_id = $id;
        return json_encode($departments);
    }

    private function generateBillAndPayrollNumber()
    {
        $bp = Payroll::orderBy('id', 'desc')->first();
        //dd($bp);
        $prefix = 'BITS';
        $middle = date('ym');        
        
        // PAYROLL NUMBER
        if($bp == NULL || $bp->payroll_number == NULL){
            $bp = new Payroll;
            $bp->payroll_number = $prefix . '-' . $middle . '-0001';
        } else { 
            do {
                $bp = Payroll::orderBy('id', 'desc')->first();
                $exploaded = explode('-', $bp->payroll_number);
                $last_digit = $exploaded[2] + 1;
                if($exploaded[2] == 9999){ 
                    $new_last_number = '0001';
                } else {
                    $new_last_number = str_pad($last_digit, 4, '0', STR_PAD_LEFT);            
                }
                $bp->payroll_number = $prefix . '-' . $middle . '-' . $new_last_number;
                $chk = Payroll::where('payroll_number', $bp->payroll_number)->first();
            } 
            while($bp->payroll_number == $chk);
        }
        
        // BILLING NUMBER
        if($bp->billing_number != null) {
            $exploaded = explode('-', $bp->billing_number);
            $bp->billing_number = $prefix . '-' . ($exploaded[1] + 1);
        } else {
            $bp->billing_number = $prefix . '-' . 1;
        }
        return $bp;
    }

    public function previewUpdate($payroll_number)
    {
        // dd($payroll_number);
        if($payroll_number != NULL){
            $payroll = Payroll::where('payroll_number', $payroll_number)
                        ->first()->toArray();

            //explode period
            $date = explode(' - ', $payroll['period']);

            $payroll['period_from'] = $date[0];
            $payroll['period_to'] = $date[1];
            
            //allowances
            $payroll['allowances'] = json_decode($payroll['allowances'],true);
            $payroll['days'] = json_decode($payroll['days'],true);
            $payroll['overtimes'] = json_decode($payroll['overtimes'],true);
            $payroll['sss'] = json_decode($payroll['sss'],true);
            $payroll['philhealth'] = json_decode($payroll['philhealth'],true);
            $payroll['pagibig'] = json_decode($payroll['pagibig'],true);
            $payroll['tax'] = json_decode($payroll['tax'],true);

            $payroll['exists'] = 1;
                        
            $employee = Employee::where('user_id', $payroll['employee_id'])->first();

            $employees = Employee::all();

            return view('payroll_ledger.preview', compact('employee', 'payroll', 'employees'));
        }
    }

    public function preview_pdf(Request $request){

        // Payroll Data Container
        $payroll = [];

        // Employee Information
        $to_select_employee = array(
            'employees.first_name',
            'employees.middle_name',
            'employees.first_name',
            'employees.gender',
            'employees.address',
            'employees.zipcode',
            'employees.city',
            'c.country_name',
            'eb.bank_name',
            'eb.account_number',
        );
        $employee_id                           = $request->employee_id;
        $employee                              = Employee::where('employees.id', $employee_id)
                                                    ->leftJoin('countries as c', 'c.id', '=', 'employees.country_id')
                                                    ->leftJoin('employee_banks as eb', 'eb.id', '=', 'employees.employee_bank_id')
                                                    ->firstOrFail($to_select_employee);

        // Payroll Information
        $payroll['billing_number']             = $this->generateBillAndPayrollNumber()->billing_number;
        
        $period_from                           = $request->period_from;
        $period_from                           = str_replace('-', '/', $period_from);
        $payroll['period_from']                = $period_from;
        
        $period_to                             = $request->period_to;
        $period_to                             = str_replace('-', '/', $period_to);
        $payroll['period_to']                  = $period_to;
        
        $payroll_date                          = $request->payroll_date;
        $payroll_date                          = str_replace('-', '/', $payroll_date);
        $payroll['payroll_date']               = $payroll_date;


        $payroll['description']                = $request->description;

        // Basic pay, Allowances and Overtimes
        $payroll['basic_pay']                  = number_format($request->basic, 2, '.', '');
        $payroll['personal_allowance']         = number_format($request->personal_allowance, 2, '.', '');
        $payroll['food_allowance']             = number_format($request->food_allowance, 2, '.', '');
        $payroll['transportation_allowance']   = number_format($request->transportation_allowance, 2, '.', '');
        $payroll['regular_holiday']            = number_format($request->regular_holiday, 2, '.', '');
        $payroll['special_holiday']            = number_format($request->special_holiday, 2, '.', '');
        $payroll['restday']                    = number_format($request->restday, 2, '.', '');
        $payroll['ot_regular_holiday']         = number_format($request->ot_regular_holiday, 2, '.', '');
        $payroll['ot_special_holiday']         = number_format($request->ot_special_holiday, 2, '.', '');
        $payroll['ot_restday']                 = number_format($request->ot_restday, 2, '.', '');

        // Deducations
        $payroll['sss']                        = abs(number_format($request->sss, 2, '.', ''));
        $payroll['philhealth']                 = abs(number_format($request->philhealth, 2, '.', ''));
        $payroll['pagibig']                    = abs(number_format($request->pagibig, 2, '.', ''));
        $payroll['witholding_tax']             = abs(number_format($request->witholding_tax, 2, '.', ''));
        $payroll['hmo']                        = abs(number_format($request->hmo, 2, '.', ''));

        // Total Allowance
        $payroll['total_allowances']           = $payroll['basic_pay'] + $payroll['food_allowance'] + $payroll['transportation_allowance'] + $payroll['personal_allowance']; 
                
        // Total Overtimes
        $payroll['total_overtime_day']         = $payroll['regular_holiday'] + $payroll['special_holiday'] + $payroll['restday'];
        $payroll['total_overtime']             = $payroll['ot_regular_holiday'] + $payroll['ot_special_holiday'] + $payroll['ot_restday'];
        
        // Total Gross Pay
        $payroll['gross_pay']                  = $payroll['total_allowances'] + $payroll['total_overtime_day'] + $payroll['total_overtime'];

        // Total Deductions
        $payroll['total_deductions']           = $payroll['sss'] + $payroll['philhealth'] + $payroll['pagibig'] + $payroll['witholding_tax'] + $payroll['hmo'];
        
        // Total Net Pay
        $payroll['total_netpay']                = $payroll['gross_pay'] - $payroll['total_deductions']; 

        // Company Information
        $company                               = Companies::first();
        

        /* Testing in View only, uncomment to view*/
        // dd($payroll);
        // dd($employee);
        // dd($request);
        // return view('payroll_ledger.preview-pdf', compact('payroll', 'company', 'employee'));

        // Generate PDF
        $pdf = PDF::loadView('payroll_ledger.preview-pdf', compact('payroll', 'company', 'employee'));
        return $pdf->download('Payroll-'.$payroll['billing_number'].'-'.ucwords($employee->first_name.'_'.$employee->last_name).'.pdf');
    }
}
