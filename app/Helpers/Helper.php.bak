<?php

function tableDropdown($table) {
    
    return DB::table($table)->pluck('name', 'id');
}

function allRoutes(){
	$app = app();
	$routes = $app->routes->getRoutes();
	$names = [];
    foreach ($routes as $route ){
        //dd($route);
    	// $array = explode(".", $route->getName());
        // $names[$array[0]] = $array[1];
        $names[] = $route->getName();
        // $names[] = $route->getActionMethod();
    
    }
    return $names;
}
function check_pages_for_department($user_dept_id,$page_names=array())
{
	$page_array = [];
    $pages = DB::table('roles')->where(['department_id'=>$user_dept_id,'role'=>'full'])->get();
  	if($pages){
   		foreach($pages as $page)
		{
			$page_array[] = $page->page;
		}
   	}
	$cnt = 0;
	$total_page_count = count($page_names);
	if($total_page_count > 0){
		foreach($page_names as $page1){
			if(!in_array($page1,$page_array)){
				
				$cnt++;
			}
				
		}	
	}	
	if($cnt!=0 && $total_page_count!= 0 && $cnt == $total_page_count){
		return "menu_hidden";
	}
	else
	{
		return "";
	}	
}

function getRouteNames(){
    $controllers = [];
    foreach (Route::getRoutes()->getRoutes() as $route)
    {
        $action = $route->getAction();
        if (array_key_exists('controller', $action))
        {
            $_action = explode('@',$action['controller']);
            $_namespaces_chunks = explode('\\',$_action[0]);
            $controllers['controller'][] = end($_namespaces_chunks);
            //$controllers[$i]['action'] = end($_action);
        }    
    }

    $routeNames = [];
    foreach($controllers as $key => $controller) {
        sort($controller);
        $controllers = array_values(array_unique($controller));
        $v = ['LoginController', 'RegisterController', 'ForgotPasswordController', 'ResetPasswordController'];
        $names = array_diff($controllers, $v);        
        $pagename = str_replace('Controller', '', $names);
        $pattern = '/(.*?[a-z]{1})([A-Z]{1}.*?)/';
        $replace = '${1} ${2}';
        $p = preg_replace($pattern, $replace, $pagename);
        $routeNames = array_combine($pagename, $p);
    }
    return $routeNames;
}

function getDefaultMethods() {
    return $methods = array(
        'create' => 'ADD',
        'show' => 'VIEW',
        'edit' => 'EDIT',
        'destroy' => 'DELETE'
    );
}

function getEmployeesDashboardReport(){
    $today = date('Y-m-d');
    
    $total_employees = \App\Employee::where('is_active','=','1')->count();
    $today_attendance = \App\Attendance::whereDate('at_date', $today )->count();
    $today_absent = $total_employees - $today_attendance;    
    $schedule_leave = \App\LeaveRequest::where('state_status', '=', 'Approved')->count();
 
    $data['total_employees'] = $total_employees;
    $data['attendance_today'] = get_percentage($total_employees, $today_attendance);
    $data['absents_today'] = get_percentage($total_employees, $today_absent);
    $data['employee_schedule_vacation']  = $schedule_leave;

    return ($data);
}

function getEmployeesLeaveBalance(){
     
    if (isset(Auth::user()->id)){
        $id = Auth::user()->id;
    }
    
    $leaves = \App\Employee::where('user_id', $id)->first();
    
    $v_leaves = \App\LeaveRequest::where('user_id', $id)
                ->where('state_status', 'Approved')
                ->where('type', 'VACATION LEAVE')
                ->get();
    $s_leaves = \App\LeaveRequest::where('user_id', $id)
                ->where('state_status', 'Approved')
                ->where('type', 'SICK LEAVE')
                ->get();
    $v_days = 0;
    $s_days = 0;
    if($s_leaves->count() > 0){
		foreach($s_leaves as $sl)
		{
			//$start_date = new DateTime($sl->date_start);
			//$end_date = new DateTime($sl->date_end);
			for ($i=strtotime($sl->date_start); $i<=strtotime($sl->date_end); $i+=86400) {  
				$day = date('D', $i);
				//$day = $datetime->format('D');
				if($day!= 'Sun' && $day!= 'Sat'){
					if($sl->half_day)
					$s_days = $s_days + 0.5;
					else 
					$s_days = $s_days + 1;	
				}
			} 
			
		}
	} 
	if($v_leaves->count() > 0){
		foreach($v_leaves as $vl)
		{
			//$start_date = new DateTime($vl->date_start);
			//$end_date = new DateTime($vl->date_end);
			for ($i=strtotime($vl->date_start); $i<=strtotime($vl->date_end); $i+=86400) {  
				$day = date('D', $i);
				//$day = $datetime->format('D');
				if($day!= 'Sun' && $day!= 'Sat'){
					if($vl->half_day)
					$v_days = $v_days + 0.5;
					else
					$v_days = $v_days + 1;
				}
			} 
			
			
		}
	} 
    $leaves->sick_leave = ($leaves->sick_leave == NULL) ? 5 : $leaves->sick_leave;
    $leaves->vacation_leave = ($leaves->vacation_leave == NULL) ? 10 : $leaves->vacation_leave;
    
    $leavesObject = new stdClass();
    $leavesObject->vacation_leave =  $leaves->vacation_leave - $v_days . ' / ' . $leaves->vacation_leave;
    $leavesObject->sick_leave = $leaves->sick_leave - $s_days . ' / ' . $leaves->sick_leave ;
    
    return $leavesObject;
}

function setToMoneyFormat($data){
    return number_format($data, 2); 
}

function get_access($user, $page, $method)
{
    $methods = array(
        1 => 'full',
        2 => 'create',
        3 => 'view',
        4 => 'edit',
        5 => 'delete',
    );
    $method = array_search($method, $methods);

    $method = \App\Role::where('name', $user)
                ->where('page', $page)
                ->where('is_active', 1)
                ->whereIn('role', [$method, 1])
                ->first();
    if(isset($method->role) && $method->role != ''){
        return true;
    } else {
        //abort(403, 'Unauthorized action. Sorry you are not allowed to access this page.');
        abort(403);
    }
}


/**
 * Compute the overtime base on approved data from overtime request model
 * @param array $overtimes - data from ovetime request table
 * @param double $perHour - Rate per hour of the employee
 * @return array
 */
function computeOvertime($overtimes, $perHour)
{
    $workingdays = config('payroll.working_days');
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
        $ot_data = checkHolidayAndRestday($ot['date'],$workingdays);
        $otpay = ($perHour * $ot_data['ot_multiplier']) * $total_ot;
        $total_ot_pay += $otpay;
        if($ot_data['type'] == 'special') {
            $special_holiday += $total_ot;
            $special_holiday_pay = ($perHour * $ot_data['ot_multiplier']) * $total_ot;
        } else if($ot_data['type'] == 'regular') {
            $regular_holiday += $total_ot;
            $regular_holiday_pay = ($perHour * $ot_data['ot_multiplier']) * $total_ot;
        } else if ($ot_data['type'] == 'restday') {
            $rest_days += $total_ot;
            $rest_days_pay = ($perHour * $ot_data['ot_multiplier']) * $total_ot;
        }
    }
    
    return array (
        'total_hours' => $ot_hours ?? 0.00,
        'special_ot_pay' => number_format($special_holiday_pay,2,'.',''),
        'regular_ot_pay' => number_format($regular_holiday_pay,2,'.',''),
        'restday_ot_pay' => number_format($rest_days_pay,2,'.',''),
        'total_overtime_pay' => number_format($total_ot_pay,2,'.',''),
        'special_holiday_hours' => $special_holiday ?? 0.00,
        'regular_holiday_hours' => $regular_holiday ?? 0.00,
        'rest_day_hours' => $rest_days ?? 0.00
    );
}

/**
 * Compute SSS monthly contribution
 * @param double $salary - total hours x hours work + allowances of employee
 * @return array
 */
function computeSSS($salary)
{
    /* $sss_data_array = array(
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
        ); */
        $sss_table = \App\SocialSecurity::all();
        $sss_table = $sss_table->toArray();
        if(empty($sss_table)) {
            return array(
                'EE'                 => 0.00,
                'ER'                 => 0.00,
                'total_contribution' => 0.00
            );
        }
        
        $sss_data = [];
        foreach($sss_table as $val) {
            $sss_data[$val['min'].'-'.$val['max']] = array (
                'ER' => $val['total_contribution_er'],
                'EE' => $val['total_contribution_ee']
            );
        }

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
    return array(
        'EE'                 => number_format($ee,2,'.',''),
        'ER'                 => number_format($er,2,'.',''),
        'total_contribution' => number_format($ee+$er,2,'.','')
    );
        /* ----------------------------------------------------------- */
}

/**
 * Compute PhilHealth Contribution
 * @param double $salary - total hours x hours work + allowances of employee
 * @return array
 */
function computePhilHealth($salary)
{
        $rate = config('payroll.philhealth_rate');
        $share = null;
        $ph = \App\Philhealth::all();
        $ph = $ph->toArray();
        if(empty($ph)){
            return array (
                'EE'                 => number_format(0,2,'.',''),
                'ER'                 => number_format(0,2,'.',''),
                'total_contribution' => number_format(0,2,'.','')
            );
        }
        $compensation_range =[];
        foreach($ph as $val) {
            $compensation_range[$val['salary_bracket']] = array (
                'monthly' => $val['total_monthly_premium'],
                'ee' => $val['employee_share'],
                'er' => $val['employer_share']
            );
        }
        foreach($compensation_range as $key => $comp)
        {
            $range = explode('-',$key);
            if($salary >= $range[0] && $salary <= $range[1])
            {
                $share = $comp['monthly'] / 2;
            } 
        } 
        $share = $share == null ? (($rate / 100 ) * $salary) / 2 : $share;
        return array (
            'EE'                 => number_format($share,2,'.',''),
            'ER'                 => number_format($share,2,'.',''),
            'total_contribution' => number_format($share * 2,2,'.','')
        );
}

/**
 * Compute Pag-Ibig Contribution
 * @param double $salary - total hours x hours work + allowances of employee
 * @return array
 */
function computePagIbig($salary)
{

    $pagibig = \App\Pagibig::all();
    $ee_share = null;
    $er_share = null;
    $pagibig = $pagibig->toArray();
    $compensation_range =[];
    foreach($pagibig as $val){
        $compensation_range[$val['monthly_compensation']] = array (
            'ee' => $val['employee_share'],
            'er' => $val['employer_share']
        );
    }
    foreach($compensation_range as $key => $comp) {
        $range = explode('-',$key);
        if($salary >= $range[0] && $salary <= $range[1])
        {
            $ee_share = $comp['ee'];
            $er_share = $comp['er'];
        } 
    }
    $ee_contribution = ($ee_share / 100) * $salary;
    $er_contribution = ($er_share / 100) * $salary;
        if($ee_contribution > 100 || $er_contribution > 100) {
            $ee_contribution = 100;
            $er_contribution = 100;
        }
    $total_contrib = $ee_contribution + $er_contribution;
    return array(
        'EE'                 => number_format($ee_contribution,2,'.',''),
        'ER'                 => number_format($er_contribution,2,'.',''),
        'total_contribution' => number_format($ee_contribution + $er_contribution,2,'.','')
    );
}

/**
 * Compute Witholding TAX
 * @param double $salary - total hours x hours work
 * @return array
 */
function computeTax($salary, $non_taxable)
{
    $taxable_income = $salary - $non_taxable;
        /** Simulate Monthly tax table data
        *  Monthly
        *   $compensation_range = array(
        *  '0-20833'       => array('tax' => 0, 'pwt' => 0),
        *    '20833-33332'   => array('tax' => 0, 'pwt' => 20),
        *    '33333-66666'   => array('tax' => 2500, 'pwt' => 25),       // +25% over P33,333
        *    '66667-166666'  => array('tax' => 10833.33, 'pwt' => 30),
        *    '166667-666666' => array('tax' => 40833.33, 'pwt' => 32),
        *    '666667-999999' => array('tax'=> 200833.33, 'pwt' => 35)
        *    );
        */
        $totaltax=0;
        $compensation_range =[];
        $tax = \App\Tax::all();
        $tax = $tax->toArray();
        if(empty($tax)){
            return array(
                'non_taxable_income' => 0,
                'taxable_income'     => number_format($taxable_income,2,'.','' ),
                'witholding_tax'     => 0,
            );
        }
        foreach($tax as $val){
            $compensation_range[$val['compensation_level'].'-'.$val['over']] = array (
                'tax' => $val['tax'],
                'pwt' => $val['percentage']
            );
        }
        
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
        return array(
            'non_taxable_income' => number_format($non_taxable,2,'.','') <= 0 ? 0 : number_format($non_taxable,2,'.','' ),
            'taxable_income'     => number_format($taxable_income,2,'.','' ) <= 0 ? 0 : number_format($taxable_income,2,'.','' ),
            'witholding_tax'     => number_format($totaltax,2,'.','' ) <= 0 ? 0 : number_format($totaltax,2,'.','' ),
        );
}

/**
 * Compute payroll summary
 * @param double $total - Total number of hours must not include overtimes
 * @param string $employeeID - The employee id
 * @param $attendances = All attendance of the employee in the given date range
 * @return array $payroll
 */
function computePayroll($employeeID, $total, $overtimes, $attendances = [])
{

        $workingdays =config('payroll.working_days');
        /** simulate sss table data */
        $employee_info = \App\Employee::where('user_id',$employeeID)->first();
        $emp           = $employee_info->toArray();
        if(empty($emp)) {
            return null;
        } 
        $factor = $workingdays == 'mon-fri' ? 216 : 313;
        $perHour = number_format((($emp['basic_salary']) / 8),2,'.','');
        $allowances = $emp['food_allowance'] + $emp['transportation_allowance'] + $emp['personal_allowance'];

        $holiday_count = 0;
        $special_holiday = 0;
        $special_holiday_pay = 0;
        $regular_holiday = 0;
        $regular_holiday_pay = 0;
        $rest_days = 0;
        $rest_days_pay = 0;
        $ot_hours = 0;
        $total_ot_pay=0;
        $holiday_hours=0;
        foreach($attendances->toArray() as $att) {
            $day = checkHolidayAndRestday($att['at_date'], $workingdays);
            $total_hr =  $att['total'];
            $holiday_count += $day['count'];
            if($day['type'] == 'regular') {
                $holiday_hours += $total_hr;
                $regular_holiday += 1;
                $regular_holiday_pay += ($perHour * $day['multiplier']) * $total_hr; 
            } else if($day['type'] == 'special') {
                $holiday_hours += $total_hr;
                $special_holiday += 1;
                $special_holiday_pay += ($perHour * $day['multiplier']) * $total_hr; 
            } else if($day['type'] == 'restday') {
                $holiday_hours += $total_hr;
                $rest_days += 1;
                $rest_days_pay += ($perHour * $day['multiplier']) * $total_hr; 
            }
        }
        $holiday_pay = $regular_holiday_pay + $special_holiday_pay + $rest_days_pay;
        $days_array = array (
            'regular_holidays' => $regular_holiday,
            'regular_holiday_pay' => number_format($regular_holiday_pay,2,'.',''),
            'special_holidays' => $special_holiday,
            'special_holiday_pay' => number_format($special_holiday_pay,2,'.',''),
            'restday_days' => $rest_days,
            'restday_days_pay' => number_format($rest_days_pay,2,'.',''),
            'rest_and_holiday_hours' => $holiday_hours
        );        

        $overtimes_array = computeOvertime($overtimes, $perHour);
		//echo 'total hours :'.$total.'<br />';
		//echo 'Per hours :'.$perHour.'<br />';
		//echo 'Allowance :'.$allowances.'<br />';
		//echo 'holiday hours :'.$holiday_hours.'<br />';
        $salary = (($total-$holiday_hours)*$perHour);
		//echo 'salary :'.$salary.'<br />';die;
        $sss_array = computeSSS($salary);

        $philhealth_array = computePhilHealth($salary);

        $pagibig_array = computePagIbig($salary);

        $non_taxable = $sss_array['EE'] + $philhealth_array['EE'] + $pagibig_array['EE'] + $allowances;

        $tax_array = computeTax($salary, $non_taxable);
        
        
        /* ---------------------------------------------------------------------- */


        
        $allowances_array = array(
            'food_allowance'           => number_format($emp['food_allowance'],2,'.',''),
            'transportation_allowance' => number_format($emp['transportation_allowance'],2,'.',''),
            'personal_allowance'       => number_format($emp['personal_allowance'],2,'.',''),
            'total_allowances'         => number_format($emp['food_allowance'] + $emp['transportation_allowance'] + $emp['personal_allowance'],2,'.','')
        );

        
        $other_benifits_array = array(
            'HMO' => $emp['hmo']
        );
        return array (
            'gross'           => number_format($salary,2,'.',''),
            'days'            => $days_array,
            'allowances'      => $allowances_array,
            'overtimes'       => $overtimes_array,
            'sss'             => $sss_array,
            'philhealth'      => $philhealth_array,
            'pagibig'         => $pagibig_array,
            'total_deduction' => number_format($sss_array['EE'] + $philhealth_array['EE'] + $pagibig_array['EE'],2,'.',''),
            'basic_pay'       => number_format(($salary + $holiday_pay) - ($sss_array['EE'] + $philhealth_array['EE'] + $pagibig_array['EE'] + $allowances),2,'.','' ),
            'tax'             => $tax_array,
            'netpay'          => number_format(($tax_array['taxable_income']-$tax_array['witholding_tax']) + $overtimes_array['total_overtime_pay'] + $holiday_pay,2,'.','')
        );
}


/**
 * Check if overtime date is a valid holiday or date is a rest day.
 * @param date @date - date to be tested
 * @return boolean
 */
function checkHolidayAndRestday($date, $workingdays)
{

    $holidays = \App\Holiday::where('holiday_date', $date)->get()->toArray();
    $type = null;
    $ot_multiplier = null;
    $is_restday = false;
    $day = date('D',strtotime($date));
    $holiday_count = 0;

    if($workingdays == 'mon-fri') {
        if($day == 'Sat' || $day == 'Sun') {
            $is_restday = true;
        }
    } else {
        if($day == 'Sun') {
            $is_restday = true;
        }
    }
    
    if($holidays) {
        foreach($holidays as $holiday) {
            $holiday_count++;
            if($holiday['status'] == 1 && $holiday['holiday_date'] == $date) {
                $type = $holiday['type'] == 'regular' ? 'regular':'special';
                if($type == 'regular' && $is_restday == false) {
                    $ot_multiplier = 2.60; // On Regular Holiday Overtime
                    $multiplier = 2;
                } else if($type == 'regular' && $is_restday == true) {
                    $ot_multiplier = 3.38; // On Regular Holiday and at the same time Rest day Overtime
                    $multiplier = 2.60;
                } else if($type == 'special' && $is_restday == false) {
                    $ot_multiplier = 1.69; // On Special Holiday Overtime
                    $multiplier = 1.30;
                } else if($type == 'special' && $is_restday == true) {
                    $ot_multiplier = 1.95; // On Special Holiday and at the same time rest day overtime
                    $multiplier = 1.50;
                } else if($is_restday == false && $holiday_count == 2) {
                    $ot_multiplier = 3.90; // On Double Holiday Overtime
                    $multiplier = 3;
                } else if($is_restday == true && $holiday_count == 2) {
                    $ot_multiplier = 5.07; // On Double Holiday and at the same time Rest day Overtime
                    $multiplier = 3;
                }
            } else {
                return false;
            }
        }
        return array (
            'count' => $holiday_count,
            'type' => $type,
            'ot_multiplier' =>$ot_multiplier,
            'multiplier' => $multiplier
        );
    } else {
        $ot_multiplier = $is_restday == true ? 1.69 : 1.25;
        $multiplier = $is_restday == true ? 1.30 : 1;
        return array (
            'count' => $holiday_count,
            'type' => $is_restday == true ? 'restday':'ordinary',
            'ot_multiplier' =>$ot_multiplier,
            'multiplier' => $multiplier
        );
    }
}

function get_profile_picture($user_id = null){
    if($user_id == null){
        $user_id = Auth::user()->id;
    }
    
    $employee = \App\Employee::where('user_id', $user_id)->first(array('employee_image'));
    
    $image = $employee['employee_image'];

    $folder_path = public_path('images/faces').'/';
    $default_folder_path = public_path('images').'/';

    if($image)
        $path = $folder_path.$image;
    else
        $path = $default_folder_path.'default-user.png';

    //Get extension
    $type = pathinfo($path, PATHINFO_EXTENSION);
    $data = file_get_contents($path);
    // Encode to base64
    $image_url_base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);

    return $image_url_base64;
}
function get_emp_cv($user_id = null){
    if($user_id == null){
        $user_id = Auth::user()->id;
    }
    
    $employee = \App\Employee::where('user_id', $user_id)->first(array('employee_cv','employee_number'));
    
    $doc = $employee['employee_cv'];

    $folder_path = 'documents/'.$employee['employee_number'].'/';

    if($doc!=null)
        $path = url($folder_path.$doc);
    else
        $path = '#';

    //Get extension
    

    return $path;
}
function get_work_agreement($user_id = null){
    if($user_id == null){
        $user_id = Auth::user()->id;
    }
    $employee = \App\Employee::where('user_id', $user_id)->first(array('work_agreement','employee_number'));
    
    $doc = $employee['work_agreement'];

    $folder_path = 'documents/'.$employee['employee_number'].'/';

    if($doc!=null)
        $path = url($folder_path.$doc);
    else
        $path = '#';

    //Get extension
    

    return $path;
}

function get_percentage($total, $number)
{
    if ( $total > 0 ) {
        return round($number / ($total / 100),2);
    } else {
        return 0;
    }
}

function get_favicon()
{
    
    $logo = \App\Companies::where('browser_icon','!=','null')->first();
   
    if (!isset($logo ))
        return 'images/' . 'favico.png';
    
    return 'images/' . $logo->browser_icon;
}

function get_logo()
{
    
    $logo = \App\Companies::where('company_logo','!=','null')->first();
   
    if (!isset($logo))
        return '';//asset('images/default-logo/no-logo.png');
    
    return 'images/' . $logo->company_logo;
}
function get_mobile_logo()
{
    
    $logo = \App\Companies::where('company_mobile_logo','!=','null')->first();
   
    if (!isset($logo))
        return '';//asset('images/default-logo/no-logo.png');
    
    return 'images/' . $logo->company_mobile_logo;
}
function get_date_format(){
    $get_date_format = \App\Companies::first(array('date_format'));
    if($get_date_format != ''){
        return $get_date_format->date_format;
    }else{
        return 'm-d-Y';
    }
}
function get_parents_menu()
	{
		$result = [];
		$data = DB::table('page_roles')
            ->select('page_roles.*','modules_tables.*')
            ->join('modules_tables', 'modules_tables.id', '=', 'page_roles.module_id');
		$data = $data->where("modules_tables.parent", 0)->where("modules_tables.status", 1);
		$data = $data->OrderBy('modules_tables.priority',"ASC");
		$data = $data->get();
		
		if(count($data) > 0){
			foreach($data as $dt)
			{
				$result[] = $dt;
			}
			return $result;
		}
		else
		{
			return false;
		}
	}
function get_child_menu($parent_id)
	{
		$result = [];
		$data = DB::table('page_roles')
            ->select('page_roles.*','modules_tables.*')
            ->join('modules_tables', 'modules_tables.id', '=', 'page_roles.module_id');
		$data = $data->where("modules_tables.parent", $parent_id)->where("modules_tables.status", 1);
	$data = $data->OrderBy('modules_tables.priority',"ASC");
		$data = $data->get();
		
		if(count($data) > 0){
			foreach($data as $dt)
			{
				$result[] = $dt;
			}
			return $result;
		}
		else
		{
			return false;
		}
	}
function get_parents_menu_role()
	{
		$result = [];
		$data = DB::table('page_roles')
            ->select('page_roles.*','modules_tables.*')
            ->join('modules_tables', 'modules_tables.id', '=', 'page_roles.module_id');
		$data = $data->where("modules_tables.parent", 0);
		$data = $data->OrderBy('modules_tables.priority',"ASC");
		$data = $data->get();
		
		if(count($data) > 0){
			foreach($data as $dt)
			{
				$result[] = $dt;
			}
			return $result;
		}
		else
		{
			return false;
		}
	}
function get_child_menu_role($parent_id)
	{
		$result = [];
		$data = DB::table('page_roles')
            ->select('page_roles.*','modules_tables.*')
            ->join('modules_tables', 'modules_tables.id', '=', 'page_roles.module_id');
		$data = $data->where("modules_tables.parent", $parent_id);
		$data = $data->OrderBy('modules_tables.priority',"ASC");
		$data = $data->get();
		
		if(count($data) > 0){
			foreach($data as $dt)
			{
				$result[] = $dt;
			}
			return $result;
		}
		else
		{
			return false;
		}
	}
function get_child_child_menu_role($parent_id)
	{
		$result = [];
		$data = DB::table('page_roles')
            ->select('page_roles.*','modules_tables.*')
            ->join('modules_tables', 'modules_tables.id', '=', 'page_roles.module_id');
		$data = $data->where("modules_tables.parent", $parent_id);
		$data = $data->OrderBy('modules_tables.priority',"ASC");
		$data = $data->get();
		
		if(count($data) > 0){
			foreach($data as $dt)
			{
				$result[] = $dt;
			}
			return $result;
		}
		else
		{
			return false;
		}
	}
function module_permission($department_id,$module_id){
		$result = [];
		$data = DB::table("module_permissions");
		$data = $data->where("department_id", $department_id)->where("module_id", $module_id);
		$data = $data->get();
		
		if(count($data) > 0){
			foreach($data as $dt)
			{
				$result[] = $dt;
			}
			return $result;
		}
		else
		{
			return false;
		}
}
function permission_get_child_menu($id)
	{
		$result = [];
			$data = DB::table("page_roles");
		$data = $data->where("module_id", $id);
		$data = $data->get();
		if(count($data) > 0){
			foreach($data as $dt)
			{
				$result[] = $dt;
			}
			return $result;
		}
		else
		{
			return false;
		}
	}
function check_permission($deptid,$modulename,$permission){
	$data = DB::table("module_permissions")
		->select('module_permissions.*','page_roles.*')
		->join('page_roles', 'page_roles.id', '=', 'module_permissions.module_id');
	$data = $data->where("page_roles.page_name", $modulename)
		->where("module_permissions.department_id", $deptid)
		->where("module_permissions.".$permission, 1);
	$data = $data->get();
		if(count($data) > 0){
			return true;
		}
		else
		{
			return false;
		}
}
function checkreports($name,$dept_id)
{
	$data = DB::table("reports");
	$data = $data->where("report_name", $name)->where("department_id", $dept_id);
	$data = $data->get();
	if(count($data) > 0){
		return true;
	}
	else
	{
		return false;
	}
}

function generate_unique_token($lenght = 8) {
    // uniqid gives 13 chars, but you could adjust it to your needs.
    if (function_exists("random_bytes")) {
        $bytes = random_bytes(ceil($lenght / 2));
    } elseif (function_exists("openssl_random_pseudo_bytes")) {
        $bytes = openssl_random_pseudo_bytes(ceil($lenght / 2));
    } else {
        throw new Exception("no cryptographically secure random function available");
    }
    return substr(bin2hex($bytes), 0, $lenght);
}


function create_new_upload_dir($new_folder){
    //Public directory/folder
    $root = public_path();

    //If uploads folder not exists, create new
    $new_uploads_folder = "uploads";
    if (!File::exists($root.'/'.$new_uploads_folder)) {
        File::makeDirectory($root.'/'.$new_uploads_folder);
    }

    // If employees folder not exist in uploads folder create new
    if (!File::exists($root.'/'.$new_uploads_folder.'/'.$new_folder)) {
        File::makeDirectory($root.'/'.$new_uploads_folder.'/'.$new_folder);
    }

    return $root.'/'.$new_uploads_folder.'/'.$new_folder.'/';
}

function get_scanned_barcode_upload_path($file){
    return public_path('uploads/scanned-barcode-upload/'.$file);
}

function get_server_datetime($time_zone='Asia/Manila', $format="F d, Y H:i:s"){
    if($time_zone != 'default')
        date_default_timezone_set($time_zone);
    
    return date($format, time());
}

function get_date_format_label(){
    $date_format    = get_date_format();
    $date_label     = explode('-', $date_format);
    $response       = '';
    
    foreach($date_label as $date){
        if($date == 'Y')
            $response .= strtoupper($date).strtoupper($date).strtoupper($date).strtoupper($date).'-';        
        else
            $response .= strtoupper($date).strtoupper($date).'-';
    }

    $response = substr($response, 0, -1);

    return $response;
}

function get_company(){
    return \App\Companies::first();
}