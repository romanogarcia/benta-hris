<?php

namespace App\Http\Controllers;

use App\Payroll;
use Illuminate\Http\Request;
Use App\Attendance;
use App\Employee;
use App\Holiday;
use App\Department;
Use App\OvertimeRequests as ot;
Use App\Companies;
use PDF;
use DB;
use Illuminate\Support\Facades\Input;
use Auth;
use App\SocialSecurity;
use App\Tax;
use DataTables;
use App\Philhealth;
use App\Pagibig;

class PayrollController extends Controller
{

    private $workingdays;

    public function __construct()
    {
        $this->middleware('auth');

        /**
         * workingdays : mon-fri or mon-sat
         * This is important as the payroll computation relies on working 
         * days of the company
         */
        $this->workingdays = 'mon-fri'; //this must be set according to company working days.
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $payroll = Payroll::List();
        $payroll = $payroll->toArray()['total'] == 0 ? false: $payroll;
        return view('payroll.index',[
            'payrolls'=>$payroll
        ]);
    }

    /**
     * Generate Payroll Data for all valid employees
     * @param $request - Date range to be generated
     * @return void
     */
    public function generate(Request $request)
    {
		$request->validate([
		'from'=>'required',
		'to'=>'required',
		]);
        $allowances=[];   
        $overtime=[];     
        $sss=[];          
        $philhealth=[];   
        $pagibig=[];      
        $tax=[];          
        $wdays=[];      
        if(!isset($request->bill_number)){
			
			$from = \Carbon\Carbon::createFromFormat(get_date_format(), $request->from)->format('Y-m-d');
			$to = \Carbon\Carbon::createFromFormat(get_date_format(), $request->to)->format('Y-m-d');
            $attendances = Attendance::Employees([$from,$to]);
            $employees = [];
            $total     = 0;
            if(empty($attendances->toArray())){
                return redirect()->back()->with('error','The specified period return an empty result. Please Check the payroll period.'); 
            }
			if(Auth::user()->employee->account_type == 0){
				$employees[] = Auth::user()->employee->id;
			}
			else{
				foreach($attendances as $r) {
					$employees[] = $r->employee_id;
				}
			}
				
            $bill_number = self::generateBillNumber();
            for($i = 0; $i < count($employees); $i++) {
                $days        = Attendance::Hours($employees[$i], [$from,$to]);
                $total_hours = $days['total_days'] * 8;
                $overtime = ot::Overtime($employees[$i], [$from,$to]);
                $attendances = Attendance::where('employee_id',$employees[$i])
                                                ->whereBetween('at_date',[$from,$to])->get();
                $payroll = computePayroll($employees[$i], $total_hours, $overtime, $attendances);
                if($payroll != null) { 
                    DB::table('payrolls')->insert([
                        'billing_number'  => $bill_number,
                        'payroll_number'  => $this->generateBillAndPayrollNumber()->payroll_number,
                        'employee_id'     => $employees[$i],
                        'name'            => $days['name'],
                        'period'          => "$from - $to",
                        'period_from'     => $from,
                        'period_to'       => $to,
                        'total_hours'     => $total_hours - $payroll['days']['rest_and_holiday_hours'],
                        'days'            => json_encode($payroll['days']),
                        'overtimes'       => json_encode($payroll['overtimes']),
                        'allowances'      => json_encode($payroll['allowances']),
                        'gross'           => $payroll['gross'],
                        'sss'             => json_encode($payroll['sss']),
                        'philhealth'      => json_encode($payroll['philhealth']),
                        'pagibig'         => json_encode($payroll['pagibig']),
                        'total_deduction' => $payroll['total_deduction'],
                        'basic_pay'       => $payroll['basic_pay'],
                        'tax'             => json_encode($payroll['tax']),
                        'netpay'          => $payroll['netpay'],
                        'payroll_date'    => now(),
                        'created_at'      => \Carbon\Carbon::now(),
                        'updated_at'      => \Carbon\Carbon::now()
                    ]);
                }
            }
            
            $payrolls = Payroll::where('billing_number', $bill_number)
                            ->orderBy('employee_id','asc')
                            ->orderBy('billing_number','asc')
                            ->orderBy('payroll_number','asc')
                            ->first();
			
            return redirect(route('payroll.search_filter').'?payroll_number='.$payrolls->payroll_number);
        } else {
            $payrolls = Payroll::where('billing_number', $request->bill_number)
                        ->first();
            return redirect(route('payroll.search_filter').'?payroll_number='.$payrolls->payroll_number);
        }
    }

    /**
     * Show payroll Summary of each employee
     * @param string $billing_number
     * @param string $employee_id
     * @return summary
     */
    public function summary($billing_number, $employee_id)
    {
        $payroll_data = Payroll::Summary($employee_id,$billing_number);  
        if($payroll_data->count() ==0) {
            return redirect('payroll')->with('error','Invalid request. This is due to invalid employee number or invalid payroll data.');
        }
        $allowances   = json_decode($payroll_data->allowances,true);
        $overtime     = json_decode($payroll_data->overtimes, true);
        $sss          = json_decode($payroll_data->sss,true);
        $philhealth   = json_decode($payroll_data->philhealth,true);
        $pagibig      = json_decode($payroll_data->pagibig,true);
        $tax          = json_decode($payroll_data->tax,true);
        $working_days = json_decode($payroll_data->days,true);

        return view('payroll.summary',[
            'payroll_data' => $payroll_data,
            'wdays'        => $working_days,
            'allowances'   => $allowances,
            'overtime'     => $overtime,
            'sss'          => $sss,
            'philhealth'   => $philhealth,
            'pagibig'      => $pagibig,
            'tax'          => $tax
        ]);
    }

    /**
     * Generate Bill number
     * @return string $bill_number
     */
    private function generateBillNumber()
    {
        $bill             = Payroll::orderby('id', 'desc')->first();
        $bill_number_init = $bill != null ? explode('-',$bill->billing_number) : array(0=>'BITS',1=>0);
        $bill_number_new  = $bill_number_init[1] + 1;
        $bill_number      = "$bill_number_init[0]-$bill_number_new";
        return $bill_number;
    }

    /**
     * Compute payroll summary
     * @param double $total - Total number of hours
     * @param $employeeID - The employee id
     * @return array $payroll
     */
    private function compute($employeeID, $total, $overtimes) 
    {
        
        /** simulate sss table data */
        $employee_info = Employee::where('employee_number',$employeeID)->get();
        $emp           = $employee_info->toArray();

        $factor = 313; // monday - friday // 313  mon - sat
        $perHour = number_format(((($emp[0]['basic_salary']*12) / $factor) / 8),2,'.','');
        if(empty($emp)) {
            return null;
        } 
        $allowances = $emp[0]['food_allowance'] + $emp[0]['transportation_allowance'] + $emp[0]['personal_allowance'];

        $special_holiday = 0;
        $special_holiday_pay = 0;
        $regular_holiday = 0;
        $regular_holiday_pay = 0;
        $rest_days = 0;
        $rest_days_pay = 0;
        $ot_hours = 0;
        $total_ot_pay=0;
        
        foreach($overtimes as $ot) {
            $total_ot = (strtotime($ot['time_end']) - strtotime($ot['time_start']))/3600;
            $ot_hours += $total_ot;
            $ot_data = self::checkHoliday($ot['date']);
            $otpay = ($perHour * $ot_data['multiplier']) * $total_ot;
            $total_ot_pay += $otpay;
            if($ot_data['type'] == 'special') {
                $special_holiday += $total_ot;
                $special_holiday_pay = ($perHour * $ot_data['multiplier']) * $total_ot;
            } else if($ot_data['type'] == 'regular') {
                $regular_holiday += $total_ot;
                $regular_holiday_pay = ($perHour * $ot_data['multiplier']) * $total_ot;
            } else if ($ot_data['type'] == 'restday') {
                $rest_days += $total_ot;
                $rest_days_pay = ($perHour * $ot_data['multiplier']) * $total_ot;
            }
        }
        
        $overtimes_array = array (
            'total_hours' => $ot_hours ?? 0.00,
            'special_ot_pay' => $special_holiday_pay,
            'regular_ot_pay' => $regular_holiday_pay,
            'restday_ot_pay' => $rest_days_pay,
            'total_overtime_pay' => $total_ot_pay ?? 0.00,
            'special_holiday_hours' => $special_holiday ?? 0.00,
            'regular_holiday_hours' => $regular_holiday ?? 0.00,
            'rest_day_hours' => $rest_days ?? 0.00
        );
        $salary = ($total*$perHour) + $allowances;
        $sss_data = array(
        '0-2250'         => array('ER' => 170, 'EE' => 80),
        '2250-2749.99'   => array('ER' => 210, 'EE' => 100),
        '2750-3249.99'   => array('ER' => 250, 'EE' => 120),
        '3250-3749.99'   => array('ER' => 290, 'EE' => 140),
        '3750-4249.99'   => array('ER' => 330, 'EE' => 160),
        '4250-4749.99'   => array('ER' => 370, 'EE' => 180),
        '4750-5249.99'   => array('ER' => 410, 'EE' => 200),
        '5250-5749.99'   => array('ER' => 450, 'EE' => 220),
        '5750-6249.99'   => array('ER' => 490, 'EE' => 240),
        '6250-6749.99'   => array('ER' => 530, 'EE' => 260),
        '6750-7249.99'   => array('ER' => 570, 'EE' => 280),
        '7250-7749.99'   => array('ER' => 610, 'EE' => 300),
        '7750-8249.99'   => array('ER' => 650, 'EE' => 320),
        '8250-8749.99'   => array('ER' => 690, 'EE' => 340),
        '8750-9249.99'   => array('ER' => 730, 'EE' => 360),
        '9250-9749.99'   => array('ER' => 770, 'EE' => 380),
        '9750-10249.99'  => array('ER' => 810, 'EE' => 400),
        '10250-10749.99' => array('ER' => 850, 'EE' => 420),
        '10750-11249.99' => array('ER' => 890, 'EE' => 440),
        '10250-11749.99' => array('ER' => 930, 'EE' => 460),
        '11750-12249.99' => array('ER' => 970, 'EE' => 480),
        '12250-12749.99' => array('ER' => 1010, 'EE' => 500),
        '12750-13249.99' => array('ER' => 1050, 'EE' => 520),
        '13250-13749.99' => array('ER' => 1090, 'EE' => 540),
        '13750-14249.99' => array('ER' => 1130, 'EE' => 560),
        '14250-14749.99' => array('ER' => 1170, 'EE' => 580),
        '14750-15249.99' => array('ER' => 1230, 'EE' => 600),
        '15250-15749.99' => array('ER' => 1270, 'EE' => 620),
        '15750-16249.99' => array('ER' => 1310, 'EE' => 640),
        '16250-16749.99' => array('ER' => 1350, 'EE' => 660),
        '16750-17249.99' => array('ER' => 1390, 'EE' => 680),
        '17250-17749.99' => array('ER' => 1430, 'EE' => 700),
        '17750-18249.99' => array('ER' => 1470, 'EE' => 720),
        '18250-18749.99' => array('ER' => 1510, 'EE' => 740),
        '18750-19249.99' => array('ER' => 1550, 'EE' => 760),
        '19250-19749.99' => array('ER' => 1590, 'EE' => 780),
        '19750'          => array('ER' => 1630, 'EE' => 800)
        );

        $er        = null;
        $ee        = null;
        $total     = 0;
        $comprange = null;
        foreach($sss_data as $key => $comp) {
            if(!preg_match('/-$/i', $key) == 0){
                $range = explode('-',$key);
                if($salary >= $range[0] && $salary <= $range[1])
                {
                    $comprange = $key;
                    $er        = $comp['ER'];
                    $ee        = $comp['EE'];
                    $total     = $er + $ee;
                }
            } else {
                if($salary >= $key )
                {
                    $comprange = $key;
                    $er        = $comp['ER'];
                    $ee        = $comp['EE'];
                    $total     = $er + $ee;
                }
            }
        }
        /* ----------------------------------------------------------- */

        /**
         * Sample data computation from PhilHealth 2019 table
         */
        $rate = 2.75;
        $share = null;

        if($salary <= 10000 && $salary != 0) {
            $share = 275 / 2;
        } else if($salary >= 40000){
            $share = 1100 / 2;
        } else {
            $share = (($rate / 100 ) * $salary) / 2;
        }
        /* ---------------------------------------------------------------------- */

        /**
         * Pag-ibig contribution sample computation :
         */
        $ee_share = null;
        $er_share = null;
        if($salary <= 1500) {
            $ee_share = 1;
            $er_share = 2;
        } else {
            $ee_share = 2;
            $er_share = 2;
        }
        $ee_contribution = ($ee_share / 100) * $salary;
        $er_contribution = ($er_share / 100) * $salary;
        if($ee_contribution > 100 || $er_contribution > 100) {
            $ee_contribution = 100;
            $er_contribution = 100;
        }
        $total_contrib = $ee_contribution + $er_contribution;
        /*----------------------------------------------------------*/

        $taxable_income =$salary - ($ee + $share + $ee_contribution + $allowances);
        /** Simulate Monthly tax table data
         *  Monthly
         */
        $compensation_range = array(
        '0-20833'       => array('tax' => 0, 'pwt' => 0),
        '20833-33332'   => array('tax' => 0, 'pwt' => 20),
        '33333-66666'   => array('tax' => 2500, 'pwt' => 25),       // +25% over P33,333
        '66667-166666'  => array('tax' => 10833.33, 'pwt' => 30),
        '166667-666666' => array('tax' => 40833.33, 'pwt' => 32),
        '666667-999999' => array('tax'=> 200833.33, 'pwt' => 35)
        );

        $totaltax=0;

        foreach($compensation_range as $key => $val) {
            $c_range = explode('-',$key);
            if($taxable_income >= $c_range[0] && $taxable_income <= $c_range[1]) {
                if($taxable_income > $c_range[0]) {
                    $excess          = $taxable_income - $c_range[0];   // if salary > beginning of compensation range compute the excess
                    $plus_percentage = ($val['pwt']/100) * $excess;     //then multiply the excess to prescribe withholding tax percentage.
                    $totaltax        = $val['tax'] + $plus_percentage;
                    $tax             = $val['tax'];
                } else {
                    $tax      = $val['tax'];
                    $totaltax = $val['tax'];
                }
            }
        }
        $allowances_array = array(
            'food_allowance'           => $emp[0]['food_allowance'],
            'transportation_allowance' => $emp[0]['transportation_allowance'],
            'personal_allowance'       => $emp[0]['personal_allowance'],
            'total_allowances'         => $emp[0]['food_allowance'] + $emp[0]['transportation_allowance'] + $emp[0]['personal_allowance']
        );
        $sss_array = array(
            'EE'                 => $ee,
            'ER'                 => $er,
            'total_contribution' => $ee+$er
        );
        $philhealth_array = array (
            'EE'                 => number_format($share,2,'.',''),
            'ER'                 => number_format($share,2,'.',''),
            'total_contribution' => $share * 2
        );
        $pagibig_array = array(
            'EE'                 => $ee_contribution,
            'ER'                 => $er_contribution,
            'total_contribution' => $ee_contribution + $er_contribution
        );
        $tax_array = array(
            'non_taxable_income' => number_format($ee + $share + $ee_contribution + $allowances + $total_ot_pay,2,'.',''),
            'taxable_income'     => number_format($taxable_income,2,'.','' ),
            'witholding_tax'     => number_format($totaltax,2,'.','' ),
        );
        return array (
            'gross'           => $salary,
            'allowances'      => $allowances_array,
            'overtimes'       => $overtimes_array,
            'sss'             => $sss_array,
            'philhealth'      => $philhealth_array,
            'pagibig'         => $pagibig_array,
            'total_deduction' => number_format($ee + $share + $ee_contribution,2,'.',''),
            'basic_pay'       => number_format($salary - ($ee + $share + $ee_contribution + $allowances),2,'.','' ),
            'tax'             => $tax_array,
            'netpay'          => number_format(($taxable_income-$totaltax) + $total_ot_pay,2,'.','')
        );
    }

    /**
     * Check if date is a valid holiday
     * @param date @date - date to be tested
     * @return boolean
     */
    private function checkHoliday($date)
    {
        $holidays = Holiday::where('holiday_date', $date)->get()->toArray();
        $type = null;
        $multiplier = null;
        $is_restday = false;
        $day = date('D',strtotime($date));
        $holiday_count = 0;
        if($day == 'Sat' || $day == 'Sun') {
            $is_restday = true;
        }
        
        if($holidays) {
            foreach($holidays as $holiday) {
                $holiday_count++;
                if($holiday['status'] == 1 && $holiday['holiday_date'] == $date) {
                    $type = $holiday['type'] == 'regular' ? 'regular':'special';
                    if($type == 'regular' && $is_restday == false) {
                        $multiplier = 2.60; // On Regular Holiday Overtime
                    } else if($type == 'regular' && $is_restday == true) {
                        $multiplier = 3.38; // On Regular Holiday and at the same time Rest day Overtime
                    } else if($type == 'special' && $is_restday == false) {
                        $multiplier = 1.69; // On Special Holiday Overtime
                    } else if($type == 'special' && $is_restday == true) {
                        $multiplier = 1.95; // On Special Holiday and at the same time rest day overtime
                    } else if($is_restday == false && $holiday_count == 2) {
                        $multiplier = 3.90; // On Double Holiday Overtime
                    } else if($is_restday == true && $holiday_count == 2) {
                        $multiplier = 5.07; // On Double Holiday and at the same time Rest day Overtime
                    }
                } else {
                    return false;
                }
            }
            return array (
                'count' => $holiday_count,
                'type' => $type,
                'multiplier' =>$multiplier
            );
        } else {
            $multiplier = $is_restday == true ? 1.69 : 1.25;
            return array (
                'count' => $holiday_count,
                'type' => $is_restday == true ? 'restday':'ordinary',
                'multiplier' =>$multiplier
            );
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Payroll  $payroll
     * @return \Illuminate\Http\Response
     */
    public function show($payroll)
    {
        //dd($payroll);
        $payrolls = Payroll::join('employees', 'employees.id','=','payrolls.employee_id')
            ->where('billing_number', $payroll)
            ->where('payroll_type',0)
            ->paginate(100,['payrolls.*','employees.employee_number']);
        $info     = $payrolls->toArray();
        $period   = !empty($info['data']) ? $info['data'][0]['period'] : null;
        return view('payroll.show',['payrolls'=>$payrolls,'period'=>$period]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Payroll  $payroll
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // dd($id);
        $payroll = Payroll::findOrFail($id);
        $payroll->delete();
        return redirect(route('payroll.search'))->with('success', 'Payroll successfully deleted');
    }

    public function search(){
		$employees = [];
		if(Auth::user()->employee->account_type == 1){
        $employees = Employee::orderBy('first_name', 'ASC')->orderBy('last_name', 'ASC')->get();
		}	
        $departments = Department::orderBy('name', 'ASC')->get();

        return view('payroll.search', compact('employees', 'departments'));
    }

    public function search_view_billing_number(Request $request){
        $payroll_id = $request->payroll_id;

        $payroll_data = Payroll::where(['payrolls.id'=>$payroll_id])
        ->join('employees', 'payrolls.employee_id', '=' , 'employees.id')
        ->first();

        $allowances   = json_decode($payroll_data->allowances,true);
        $overtime     = json_decode($payroll_data->overtimes, true);
        $sss          = json_decode($payroll_data->sss,true);
        $philhealth   = json_decode($payroll_data->philhealth,true);
        $pagibig      = json_decode($payroll_data->pagibig,true);
        $tax          = json_decode($payroll_data->tax,true);

        $result = '
            <div class="card-body">
            
            <p><img src="'.asset('images/bentach-big-1-1.png').'" height="100px" style="margin-left: -40px"></p>
            <p>'.ucwords($payroll_data->name).' <a style="float: right" class=" btn btn-primary btn-sm" target="_blank" href="'.route('payroll.generate_pdf', ['payroll_id'=>$payroll_data->id]).'"><i class="mdi mdi-arrow-down-bold-circle-outline"></i> Generate PDF</a></p>
            <p>'.$payroll_data->employee_number.'</p>
            <p>'.$payroll_data->address.'</p>
            <hr>
          <h4>Payroll information:</h4>
          <table class="table table-sm table-bordered">
            <thead>
              <tr><th>Billing Number:</th>
              <th>Payroll Period:</th>
            </tr></thead>
            <tbody>
              <tr>
                <td>'.$payroll_data->billing_number.'</td>
                <td>'.$payroll_data->period.'</td>
              </tr>
              <tr>
                <td>Rate:</td>
                <td>'.number_format($payroll_data->basic_salary,2,'.',',').'</td>
              </tr>
            </tbody>
          </table>
          <div class="mt-5">
            <table class="table table-sm table-bordered">
              <thead>
                <tr>
                    <th colspan="2">Description</th>
                    <th class="text-right">Amount</th>
                    <th class="text-right">Total</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                    <td colspan="2">Regular Hours:</td>
                    <td class="text-right">'.$payroll_data->total_hours.' hrs</td>
                    <td class="text-right">'.$payroll_data->gross.'</td>
                </tr>
                <tr>
                    <td colspan="2">Food allowance:</td>
                    <td class="text-right">'.number_format($allowances['food_allowance'],2).'</td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="2">Transportation Allowance:</td>
                    <td class="text-right">'.number_format($allowances['transportation_allowance'],2).'</td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="2">Personal Allowance:</td>
                    <td class="text-right">'.number_format($allowances['personal_allowance'],2).'</td>
                    <td class="text-right">'.number_format($allowances['total_allowances'],2).'</td>
                </tr>
                
                <tr>
                    <td colspan="2">Regular Holiday:</td>
                    <td class="text-right">'.number_format($overtime['regular_holiday_hours'],2).'</td>
                    <td class="text-right">'.number_format($overtime['regular_ot_pay'],2).'</td>
                </tr>
                <tr>
                    <td colspan="2">Special Holiday:</td>
                    <td class="text-right">'.number_format($overtime['special_holiday_hours'],2).'</td>
                    <td class="text-right">'.number_format($overtime['special_ot_pay'],2).'</td>
                </tr>
                <tr>
                    <td colspan="2">Restday:</td>
                    <td class="text-right">'.number_format($overtime['rest_day_hours'],2).'</td>
                    <td class="text-right">'.number_format($overtime['restday_ot_pay'],2).'</td>
                </tr>
                <tr>
                    <td colspan="2">Total:</td>
                    <td class="text-right">'.number_format($overtime['total_hours'],2).'</td>
                    <td class="text-right">'.number_format($overtime['total_overtime_pay'],2).'</td>
                </tr>
                <tr>
                    <td colspan="2">SSS:</td>
                    <td class="text-right">'. number_format($sss['EE'],2) .'</td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="2">PhilHealth:</td>
                    <td class="text-right">'. number_format($philhealth['EE'],2) .'</td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="2">PAG-IBIG:</td>
                    <td class="text-right">'. number_format($pagibig['EE'],2) .'</td>
                    <td class="text-right">Less '. number_format($payroll_data->total_deduction ,2).'</td>
                </tr>
                <tr>
                    <td colspan="2">Non-Taxable Income:</td>
                    <td class="text-right">'. number_format($tax['non_taxable_income'],2).'</td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="2">Taxable Income:</td>
                    <td class="text-right">'. number_format($tax['taxable_income'],2).'</td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="2">Witholding Tax:</td>
                    <td class="text-right">'. number_format($tax['witholding_tax'],2) .'</td>
                    <td class="text-right">'. number_format($tax['witholding_tax'],2).'</td>
                </tr>
                <tr>
                    <td colspan="3"><h3>Payout</h3></td>
                    <td class="text-right"><h3>₱ '.number_format($payroll_data->netpay ,2).'</h3></td>
                </tr>
              </tbody>
            </table>
            <br><br>
            <p>The Salary will sent at : BANK NAME, Account: XXXX XXXX XXXX XXXX</p>
          </div>
      </div>
            ';

        echo $result;
    }

    // public function search_filter(Request $request){
    //     // dd($request);
        
    //     $billing_number = $request->billing_number;
    //     $employee_id = $request->employee_id;
    //     $department_id = $request->department_id; //disregarded
    //     $date_range = $request->date_range_pick;

    //     $filters = [];
    //     if($billing_number!=''){
    //         $filters['billing_number'] = $billing_number;
    //     }
    //     if($employee_id!=''){
    //         $filters['employee_id'] = $employee_id;
    //     } 

    //     $select_qry = array(
    //         'payrolls.*',
    //         'employees.*',
    //         'payrolls.created_at as p_created_at',
    //         'employees.created_at as e_created_at',
    //     );

    //     $data = Payroll::where($filters)
    //     ->join('employees', 'payrolls.employee_id', '=' , 'employees.id')
    //     ->paginate(100, $select_qry);

    //     if($date_range != ''){
	// 		$date_array  = explode(" - ",$date_range);
	// 		$from = date('Y-m-d',strtotime($date_array[0]));
    //         $to = date('Y-m-d',strtotime($date_array[1]));
    //         $data = Payroll::where($filters
    //         )->whereBetween('payrolls.created_at',[$from,$to])
    //         ->join('employees', 'payrolls.employee_id', '=' , 'employees.id')
    //         ->paginate(100, $select_qry);
    //     }

    //     $result = '';
    //     if($data == null){
    //         $result  = '<h3 class="text-center text-danger"><i class="mdi mdi-close"></i> No result found.</h3>';
    //     }else{
            
    //         $result = '<table class="table table-hover">
    //         <thead>
    //             <tr>
    //                 <th>Billing number</th>
    //                 <th>Payroll Period</th>
    //                 <th>Generated on</th>
    //             </tr>
    //         </thead>
    //         <tbody>';
    //         foreach($data as $payroll_data){
    //             $result .= '
    //                 <tr>
    //                     <td><a class="badge badge-primary view-payroll-billing" data-id="'.$payroll_data->id.'" href="javascript:void(0);">'.$payroll_data->billing_number.'</a></td>
    //                     <td>'.$payroll_data->period.'</td>
    //                     <td>'.$payroll_data->p_created_at.'</td>
    //                 </tr>
    //             ';
    //         }

    //         $result .= '</tbody>
    //         </table>';

    //     }
        
    //     echo $result;

    // }

    
    public function search_filter(Request $request)
    {
        // dd($request);
		$period_from = '';
		$period_to = '';
        $payroll_number     = $request->payroll_number;
        $employee_id        = $request->employee_id;
		if(Auth::user()->employee->account_type == 0){
			if(!empty($employee_id) && ($employee_id==Auth::user()->employee->id)){
				$employee_id = Auth::user()->employee->id;
			}
			else if(!empty($employee_id) && ($employee_id != Auth::user()->employee->id)){
				$employee_id =-1;
			}
			else{
				$employee_id = Auth::user()->employee->id;
			}
        }
        $department_id      = $request->department_id;
		if($request->from!=''){
        	$period_from        = date("Y-m-d",strtotime(str_replace("-","/",$request->from)));
		}
		if($request->to!=''){
       	 	$period_to          = date("Y-m-d",strtotime(str_replace("-","/",$request->to)));
		}	
        $period             = $period_from . ' - ' .  $period_to;
        $perpage            = $request->query('perpage',100);
        // $perpage = 10;

        $payrolls           = [];
        $allowances         = [];   
        $overtime           = [];     
        $sss                = [];          
        $philhealth         = [];   
        $pagibig            = [];      
        $tax                = [];          
        $wdays              = [];
        
        $result             = false;


        // if($period_from != '' && $period_to != ''){
            $employees = new Employee;
            if($employee_id && $department_id == NULL){
                $employees = Employee::where('user_id', $employee_id)->select('user_id')->get()->toArray();
            } else if($department_id && $employee_id == NULL){
                $employees = Employee::where('department_id', $department_id)->select('user_id')->get()->toArray();
            } else if($employee_id && $department_id){
                $employees = Employee::where('user_id', $employee_id)
                    ->where('department_id', $department_id)->select('user_id')->get()->toArray();
            } else {
                $employees = Employee::select('user_id')->get()->toArray();
            }
        
            if($employees){
                $to_select = array(
                    'payrolls.*',
                    'employees.first_name',
                    'employees.last_name'
                );
                $payrolls = Payroll::
                        where(function($query) use ($payroll_number){
                            if($payroll_number){
                                $query->where('payrolls.payroll_number', $payroll_number);
                            }
                        })
                        ->where(function($query) use ($employees){
                            if($employees){
                                $query->whereIn('payrolls.employee_id', $employees);
                            }
                        })
                        ->where(function($query) use ($period_from){
                            if($period_from != ''){
                                $query->where('payrolls.period_from', '>=', $period_from);
                            }
                        })
                        ->where(function($query) use ($period_to){
                            if($period_to != ''){
                                $query->where('payrolls.period_to', '<=', $period_to);
                            }
                        })
                        ->join('employees', 'employees.id', '=', 'payrolls.employee_id')
                        ->orderBy('payrolls.employee_id','asc')
                        ->orderBy('payrolls.billing_number','asc')
                        ->orderBy('payrolls.payroll_number','asc')
                        ->paginate($perpage, $to_select);
                        // dd($payrolls);  

                if(!$payrolls->isEmpty()){        
                    
                    $result = true;
                    
                    foreach($payrolls as $payroll){
                        $allowances[]   = json_decode($payroll->allowances,true);
                        $overtime[]     = json_decode($payroll->overtimes,true);
                        $sss[]          = json_decode($payroll->sss,true);
                        $philhealth[]   = json_decode($payroll->philhealth,true);
                        $pagibig[]      = json_decode($payroll->pagibig,true);
                        $tax[]          = json_decode($payroll->tax,true);
                        $wdays[]        = json_decode($payroll->days,true);
                    }   
                    return view('payroll.list', compact('result', 'payrolls', 'allowances', 'overtime', 'sss', 'philhealth', 'pagibig', 'tax', 'wdays', 'payroll_number', 'employee_id', 'department_id', 'period_from', 'period_to'));
                }
                else {
                    return view('payroll.list', compact('result', 'payrolls', 'allowances', 'overtime', 'sss', 'philhealth', 'pagibig', 'tax', 'wdays', 'payroll_number', 'employee_id', 'department_id', 'period_from', 'period_to'));
                }
            }else{
                return view('payroll.list', compact('result', 'payrolls', 'allowances', 'overtime', 'sss', 'philhealth', 'pagibig', 'tax', 'wdays', 'payroll_number', 'employee_id', 'department_id', 'period_from', 'period_to'));
            }
        // }else{
        //     return view('payroll.list', compact('result', 'payrolls', 'allowances', 'overtime', 'sss', 'philhealth', 'pagibig', 'tax', 'wdays', 'payroll_number', 'employee_id', 'department_id', 'period_from', 'period_to'));
        // }
        
    }


    public function generate_pdf($payroll_id){
        $to_select = array(
            'payrolls.*',
            'e.first_name',
            'e.last_name',
            'e.middle_name',
            'e.gender',
            'e.address',
            'e.zipcode',
            'e.city',
            'eb.account_number',
            'eb.bank_name',
            'c.country_name'
        );

        $payroll_q = Payroll::where('payrolls.id', $payroll_id)
                            ->join('employees as e', 'payrolls.employee_id', '=' , 'e.id')
                            ->leftJoin('employee_banks as eb', 'eb.id', '=', 'payrolls.employee_id')
                            ->leftJoin('countries as c', 'e.country_id', '=', 'c.id')
                            ->firstOrFail($to_select);

        $employee = [];
        $employee['first_name']         = $payroll_q->first_name;
        $employee['last_name']          = $payroll_q->last_name;
        $employee['middle_name']        = $payroll_q->middle_name;
        $employee['gender']             = $payroll_q->gender;
        $employee['address']            = $payroll_q->address;
        $employee['zipcode']            = $payroll_q->zipcode;
        $employee['city']               = $payroll_q->city;
        $employee['account_number']     = $payroll_q->account_number;
        $employee['bank_name']          = $payroll_q->bank_name;
        $employee['country_name']       = $payroll_q->country_name;

        $payroll = [];
        
        // Payroll Information
        $payroll['description']                = $payroll_q->description;
        $payroll['billing_number']             = $payroll_q->billing_number;
        $payroll['period_from']                = $payroll_q->period_from;
        $payroll['period_to']                  = $payroll_q->period_to;
        $payroll['payroll_date']               = $payroll_q->payroll_date;
        
        // Basic pay, Allowances and Overtimes
        $payroll['basic_pay']                  = number_format($payroll_q->basic_pay, 2, '.', '');

        $allowances                             = json_decode($payroll_q->allowances, true);
        $payroll['personal_allowance']         = number_format($allowances['personal_allowance'], 2, '.', '');
        $payroll['food_allowance']             = number_format($allowances['food_allowance'], 2, '.', '');
        $payroll['transportation_allowance']   = number_format($allowances['transportation_allowance'], 2, '.', '');
        
        $overtime_days                         = json_decode($payroll_q->days, true);
        $payroll['regular_holiday']            = number_format($overtime_days['regular_holiday_pay'], 2, '.', '');
        $payroll['special_holiday']            = number_format($overtime_days['special_holiday_pay'], 2, '.', '');
        $payroll['restday']                    = number_format($overtime_days['restday_days_pay'], 2, '.', '');

        $overtimes                             = json_decode($payroll_q->overtimes, true);
        $payroll['ot_regular_holiday']         = number_format($overtimes['regular_ot_pay'], 2, '.', '');
        $payroll['ot_special_holiday']         = number_format($overtimes['special_ot_pay'], 2, '.', '');
        $payroll['ot_restday']                 = number_format($overtimes['restday_ot_pay'], 2, '.', '');
        
        // Deducations
        $sss_s                                 = json_decode($payroll_q->sss, true);
        $payroll['sss']                        = abs(number_format($sss_s['EE'], 2, '.', ''));

        $philhealth_s                          = json_decode($payroll_q->philhealth, true);
        $payroll['philhealth']                 = abs(number_format($philhealth_s['EE'], 2, '.', ''));
        
        $pagibig_s                             = json_decode($payroll_q->pagibig, true);
        $payroll['pagibig']                    = abs(number_format($pagibig_s['EE'], 2, '.', ''));
        
        $witholding_tax_s                      = json_decode($payroll_q->tax, true);
        $payroll['witholding_tax']             = abs(number_format($witholding_tax_s['witholding_tax'], 2, '.', ''));
        
        $witholding_tax_s                      = json_decode($payroll_q->tax, true);
        $payroll['witholding_tax']             = abs(number_format($witholding_tax_s['witholding_tax'], 2, '.', ''));
        
        $payroll['hmo']                        = abs(number_format($payroll_q->hmo, 2, '.', ''));

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
        $payroll['total_netpay']               = $payroll['gross_pay'] - $payroll['total_deductions']; 


        // dd($payroll);




        $company = Companies::first();


      //  return view('payroll.generate-pdf', compact('payroll', 'company', 'employee'));

        $pdf = PDF::loadView('payroll.generate-pdf', compact('payroll', 'company', 'employee'));
        return $pdf->download('Payroll-'.$payroll['billing_number'].'-'.ucwords($employee['first_name'].'_'.$employee['last_name']).'.pdf');
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
	public function payroll_settings()
	{
		return view('payroll.payroll_settings',[]);
	}	
    public function get_sss_table(){
		
		$select_qry = array(
            'id',
            'min',
            'max',
            'salary',
            'sss_ec_er',
            'total_contribution_er',
            'total_contribution_ee',
            'total_contribution_total',
        );
       
       $data = SocialSecurity::orderBy('salary','asc')->get($select_qry);        

        $data_tables = Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('id', function($row){
				if((check_permission(Auth::user()->Employee->department_id,"SSS","full")) || (check_permission(Auth::user()->Employee->department_id,"SSS","Edit"))){
                $url_edit = route('sss.edit', [$row->id]);
                $response = '<a href="'.$url_edit.'">'.$row->id.'</a>';
                return $response;
				}
				else{
				return $row->id;
				}
            })
            ->addIndexColumn()
            ->addColumn('min', function($row){
                return $row->min;
            })
            ->addIndexColumn()
            ->addColumn('max', function($row){
                 return $row->min;
            })
			->addIndexColumn()
            ->addColumn('salary', function($row){
                return $row->salary;
            })
			->addIndexColumn()
            ->addColumn('sss_ec_er', function($row){
                return $row->sss_ec_er;
            })
			->addIndexColumn()
            ->addColumn('total_contribution_er', function($row){
                return $row->total_contribution_er;
            })
			->addIndexColumn()
            ->addColumn('total_contribution_ee', function($row){
                return $row->total_contribution_ee;
            })
            ->addIndexColumn()
            ->addColumn('total_contribution_total', function($row){
              
                return $row->total_contribution_total;
            })
            ->addIndexColumn()
            ->addColumn('action', function($row){
                $url_edit = route('sss.edit', [$row->id]);
				$action="";
				if((check_permission(Auth::user()->Employee->department_id,"SSS","full")) || (check_permission(Auth::user()->Employee->department_id,"SSS","Edit"))){
                $action = '<button type="button" onclick="window.location.href=\''.$url_edit.'\'" class="btn btn-outline-secondary btn-rounded btn-icon btn-sm"><i style="margin-left: -6px;" class="mdi mdi-lead-pencil"></i></button> ';
				}
				if((check_permission(Auth::user()->Employee->department_id,"SSS","full")) || (check_permission(Auth::user()->Employee->department_id,"SSS","Delete"))){
                $action .= '  <button type="button" data-toggle="modal" data-id="'.$row->id.'" data-target="#DeleteSSSModal" class="btn btn-outline-secondary btn-rounded btn-icon btn-sm btn-delete_row ml-2" onclick="deletesss('.$row->id.')"><i style="margin-left: -7px;" class="mdi mdi-delete"></i></button>';
				}
                return $action;
            })
            ->rawColumns(['id','min','max','salary','sss_ec_er','total_contribution_er','total_contribution_ee','total_contribution_total','action'])
            ->make(true);
        
        return $data_tables;
	}
	public function get_tax_table(){
		
		$select_qry = array(
            'id',
            'compensation_level',
            'over',
            'tax',
            'percentage',
        );
       
       $data = Tax::latest()->get($select_qry);        

        $data_tables = Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('id', function($row){
				if((check_permission(Auth::user()->Employee->department_id,"Task","full")) || (check_permission(Auth::user()->Employee->department_id,"Task","Edit"))){
                $url_edit = route('tax.edit', [$row->id]);
                $response = '<a href="'.$url_edit.'">'.$row->id.'</a>';
                return $response;
				}
				else{
					return $row->id;
				}
            })
            ->addIndexColumn()
            ->addColumn('compensation_level', function($row){
                return $row->compensation_level;
            })
            ->addIndexColumn()
            ->addColumn('over', function($row){
                 return $row->over;
            })
			->addIndexColumn()
            ->addColumn('tax', function($row){
                return $row->tax;
            })
			->addIndexColumn()
            ->addColumn('percentage', function($row){
                return '+'.$row->percentage.'%';
            })
            ->addIndexColumn()
            ->addColumn('action', function($row){
                $url_edit = route('tax.edit', [$row->id]);
				$action="";
				if((check_permission(Auth::user()->Employee->department_id,"Tax","full")) || (check_permission(Auth::user()->Employee->department_id,"Tax","Edit"))){
                $action = '<button type="button" onclick="window.location.href=\''.$url_edit.'\'" class="btn btn-outline-secondary btn-rounded btn-icon btn-sm"><i style="margin-left: -6px;" class="mdi mdi-lead-pencil"></i></button> ';
				}
				if((check_permission(Auth::user()->Employee->department_id,"Tax","full")) || (check_permission(Auth::user()->Employee->department_id,"Tax","Delete"))){
                $action .= '  <button type="button" data-toggle="modal" data-id="'.$row->id.'" data-target="#DeleteTaxModal" class="btn btn-outline-secondary btn-rounded btn-icon btn-sm btn-delete_row ml-2" onclick="deletetax('.$row->id.')"><i style="margin-left: -7px;" class="mdi mdi-delete"></i></button>';
				}
                return $action;
            })
            ->rawColumns(['id','compensation_level','over','tax','percentage','action'])
            ->make(true);
        
        return $data_tables;
	}
	public function get_philhealth_table()
	{
		// Philhealth;
		$select_qry = array(
            'id',
            'salary_bracket',
            'salary_min',
            'salary_max',
			'total_monthly_premium',
			'employee_share',
			'employer_share',
        );
       
       $data = Philhealth::latest()->get($select_qry);        

        $data_tables = Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('id', function($row){
				if((check_permission(Auth::user()->Employee->department_id,"Philhealth","full")) || (check_permission(Auth::user()->Employee->department_id,"Philhealth","Edit"))){
                $url_edit = route('philhealth.edit', [$row->id]);
                $response = '<a href="'.$url_edit.'">'.$row->id.'</a>';
                return $response;
				}
				else{
				return $row->id;
				}
            })
            ->addIndexColumn()
            ->addColumn('salary_bracket', function($row){
				return '₱ '.str_replace('-',' to ₱ ', $row->salary_bracket);
            })
            ->addIndexColumn()
            ->addColumn('salary_min', function($row){
                 return $row->salary_min;
            })
			->addIndexColumn()
            ->addColumn('salary_max', function($row){
                return $row->salary_max;
            })
			->addIndexColumn()
            ->addColumn('total_monthly_premium', function($row){
                return $row->total_monthly_premium;
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
            ->addColumn('action', function($row){
                $url_edit = route('philhealth.edit', [$row->id]);
				$action="";
				if((check_permission(Auth::user()->Employee->department_id,"Philhealth","full")) || (check_permission(Auth::user()->Employee->department_id,"Philhealth","Edit"))){
                $action = '<button type="button" onclick="window.location.href=\''.$url_edit.'\'" class="btn btn-outline-secondary btn-rounded btn-icon btn-sm"><i style="margin-left: -6px;" class="mdi mdi-lead-pencil"></i></button> ';
				}
				if((check_permission(Auth::user()->Employee->department_id,"Philhealth","full")) || (check_permission(Auth::user()->Employee->department_id,"Philhealth","Delete"))){
                $action .= '  <button type="button" data-toggle="modal" data-id="'.$row->id.'" data-target="#DeletePhilhealthModal" class="btn btn-outline-secondary btn-rounded btn-icon btn-sm btn-delete_row ml-2" onclick="deletephilhealth('.$row->id.')"><i style="margin-left: -7px;" class="mdi mdi-delete"></i></button>';
				}
                return $action;
            })
            ->rawColumns(['id','salary_bracket','salary_min','salary_max','total_monthly_premium','employee_share','employer_share','action'])
            ->make(true);
        
        return $data_tables;
	}	
	
}
