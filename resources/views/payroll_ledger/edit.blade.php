@extends('layouts.master')
@section('title', 'Generate Payroll')
@section('customcss')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<!-- <link rel="stylesheet" type="text/css" href="https://code.jquery.com/ui/1.12.0/themes/smoothness/jquery-ui.css"> -->

<style>
    table {
        width: 100%;
        border-spacing: 3px;
        border-collapse: collapse;
    }
    table tr td {
        padding: 5px 7px;
    }
    .ledger .head-green {
        background: #68b114;
        color: white;
        font-weight: 700;
        text-align: center;
    }
    .ledger .head-lightblue {
        background: #85929E;
        color: white;
        font-weight: 700;
    }
    .ledger .head-blue {
        background: #3498DB;
        color: white;
        font-weight: 700;
    }
    .ledger .grey {
        background: #e1e1e1;
        color: black;
        
    }
    .ledger .light-grey {
        background: #f1f3f4;
        color: black;
    }
    /* .ledger input[type=text]{
        border: none;
        background: none;
    } */
    .ledger #ee_computed_gross,
    .ledger #ee_deductions,
    .ledger #ee_net,
    .ledger #er_computed_gross,
    .ledger #er_deductions,
    .ledger #er_net {
        border: none;
        background: none;
    }
    .ledger .total {
        color: white;
        font-weight: 700;
    }
    .td-label {
        font-size: 0.9em;
    }

    input[type="number"]::-webkit-outer-spin-button, input[type="number"]::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    
    input[type="number"] {
        -moz-appearance: textfield;
    }

    input[type="number"]:focus, textarea:focus {
        border: 1px solid rgba(81, 203, 238, 1);
    }

    #loading-bg {
        position:fixed;
        width:100%;
        left:0;right:0;top:0;bottom:0;
        background-color: rgba(255,255,255,0.7);
        z-index:9999;
        display:none;
    }

    @-webkit-keyframes spin {
        from {-webkit-transform:rotate(0deg);}
        to {-webkit-transform:rotate(360deg);}
    }

    @keyframes spin {
        from {transform:rotate(0deg);}
        to {transform:rotate(360deg);}
    }

    #loading-bg::after {
        content:'';
        display:block;
        position:absolute;
        left:48%;top:40%;
        width:40px;height:40px;
        border-style:solid;
        border-color:black;
        border-top-color:transparent;
        border-width: 4px;
        border-radius:50%;
        -webkit-animation: spin .8s linear infinite;
        animation: spin .8s linear infinite;
    }
    
</style>
@endsection

        

@section('content')
<div id="loading-bg"></div>

<div class="content-wrapper">
  <div class="content">
  @include('includes.messages')
    <div class="card">
      <div class="card-header">
      Update Payroll: {{ $payroll->payroll_number }}
      </div>
      <div class="card-body">
      <form method="POST" action="{{ route('payrollledger.update', $payroll['id']) }}" enctype="multipart/form-data" autocomplete="off">
        @method('PATCH')
        @csrf
        
            <input type="hidden" name="employee_id" value="{{ $employee['employee_id'] }}">
            <input type="hidden" name="department_id" value="{{ $employee['department_id'] }}">
            <input type="hidden" name="payroll_date" value="{{ $payroll['payroll_date'] }}">
            
            <div class="row">
                <div class="col-md-4 form-group">
                    <label for="employee_id">Name</label>
                    <select class="form-control form-control-sm" name="employee_id" id="employee_id">
                        <option value="{{ $employee->id }}" selected>{{ strtoupper($employee->last_name . ', ' . $employee->first_name . ' ' . $employee->middle_name) }}</option>
                    </select> 
                </div>
                <div class="col-md-4 form-group">
                    <label for="payroll_number">Payroll Number</label>   
                    <input type="text" class="form-control form-control-sm" name="payroll_number" id="payroll_number" value="{{ $payroll['payroll_number'] }}"> 
                </div>
                <div class="col-md-4 form-group">
                    <label for="payroll_date">Payroll Date</label>
                    <input type="text" class="form-control form-control-sm is_datefield" name="payroll_date" id="payroll_date" value="{{ $payroll['payroll_date'] }}">
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-4 form-group">
                    <label for="employee_number">Employee Number</label>
                    <input type="text" class="form-control form-control-sm" name="employee_number" id="employee_number" value="{{ $employee['employee_number'] }}">
                </div>
                <div class="col-md-4 form-group">
                    <label for="from">Period From</label>
                    <input type="text" class="form-control form-control-sm is_datefield" name="period_from" id="period_from" value="{{ $payroll['period_from'] }}">
                </div>
                <div class="col-md-4 form-group">
                    <label for="to">Period To</label>
                    <input type="text" class="form-control form-control-sm is_datefield" name="period_to" id="period_to" value="{{ $payroll['period_to'] }}">
                </div>
            </div>


        <br>

        

            <input type="hidden" name="employee_id" id="employee_id" value="{{ $employee->user_id }}">
            <input type="hidden" name="employee_name" value="{{ $employee->first_name . ' ' . $employee->last_name }}">
            <input type="hidden" name="period" value="{{ $payroll['period_from'] . ' - ' . $payroll['period_to'] }}">
            <input type="hidden" name="total_hours" value="{{ $payroll['total_hours'] }}">
            <input type="hidden" name="payroll_date" value="{{ $payroll['payroll_date'] }}">
            <input type="hidden" name="updated_period_from" id="updated_period_from">
            <input type="hidden" name="updated_period_to" id="updated_period_to">
            <input type="hidden" name="updated_payroll_date" id="updated_payroll_date">
            

            <!-- js usage -->
            <!-- <input type="hidden" name="period_from" id="period_from" value="{{ $payroll['period_from'] }}">
            <input type="hidden" name="period_to" id="period_to" value="{{ $payroll['period_to'] }}"> -->
            <!-- end of js usage -->







            <div>
                <div class="row">
                    <div class="col-xs-12 col-sm-12">
                        <div class="form-group">
                            <label for="description">Description</label>
                            <input type="text" class="form-control form-control-sm" name="description" id="description" value="{{ $payroll['description'] }}">
                        </div>
                    </div>
                    <!-- <div class="col-md-4 form-group"> -->
                        <!-- <label for="payment_date">Payment Date</label> -->
                        <input type="hidden" class="form-control form-control-sm" name="payment_date" id="payment_date" value="{{ ($payroll['payment_date']) ? $payroll['payment_date']:date('Y-m-d')  }}">
                    <!-- </div> -->
                </div>

                <!-- EMPLOYEE -->
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                        <table class="ledger">
                            <tr>
                                <td colspan="2" class="head-green">EMPLOYEE</td>
                            </tr>
                            <tr>
                                <td class="text-right" style="width:55px; max-width:55px">
                                    <input class="text-right" type="number" step="any" name="ee_gross" id="ee_gross" value="{{ number_format($payroll['gross'],2,'.','') }}" onchange="compute_ee_gross()">
                                </td>
                                <td class="td-label">{{ $employee->position == NULL ? 'Basic' : strtoupper($employee->position) }}</td>
                            </tr>
                            <tr>
                                <td class="text-right">
                                    <input class="text-right" type="number" step="any" name="ee_food_allowance" id="ee_food_allowance" value="{{ number_format($allowances['food_allowance'],2,'.','') }}" onchange="compute_ee_gross()">
                                </td>
                                <td class="td-label">Food Allowance</td>
                            </tr>
                            <tr>
                                <td class="text-right">
                                    <input class="text-right" type="number" step="any" name="ee_transportation_allowance" id="ee_transportation_allowance" value="{{ number_format($allowances['transportation_allowance'],2,'.','') }}" onchange="compute_ee_gross()">
                                </td>
                                <td class="td-label">Transportation Allowance</td>
                            </tr>
                            <tr>
                                <td class="text-right">
                                    <input class="text-right" type="number" step="any" name="ee_personal_allowance" id="ee_personal_allowance" value="{{ number_format($allowances['personal_allowance'],2,'.','') }}" onchange="compute_ee_gross()">
                                </td>
                                <td class="td-label">Personal Allowance</td>
                            </tr>

                            
                            <!-- overtime -->
                            <input type="hidden" name="regular_holidays" value="{{ $days['regular_holidays'] }}">
                            <input type="hidden" name="special_holidays" value="{{ $days['special_holidays'] }}">
                            <input type="hidden" name="restday_days" value="{{ $days['restday_days'] }}">
                            <input type="hidden" name="rest_and_holiday_hours" value="{{ $days['rest_and_holiday_hours'] }}">
                            
                            <tr>
                                <td class="text-right light-grey">
                                    <input class="text-right" type="number" step="any" name="ee_regular_holiday_pay" id="ee_regular_holiday_pay" value="{{ number_format($days['regular_holiday_pay'],2,'.','') }}" onchange="compute_ee_gross()">
                                </td>
                                <td class="light-grey td-label">Regular Holiday</td>
                            </tr>
                            <tr>
                                <td class="text-right light-grey">
                                    <input class="text-right" type="number" step="any" name="ee_special_holiday_pay" id="ee_special_holiday_pay" value="{{ number_format($days['special_holiday_pay'],2,'.','') }}" onchange="compute_ee_gross()">
                                </td>
                                <td class="light-grey td-label">Special Holiday</td>
                            </tr>
                            <tr>
                                <td class="text-right light-grey">
                                    <input class="text-right" type="number" step="any" name="ee_restday_days_pay" id="ee_restday_days_pay" value="{{ number_format($days['restday_days_pay'],2,'.','') }}" onchange="compute_ee_gross()">
                                </td>
                                <td class="light-grey td-label">Restday</td>
                            </tr>


                            <!-- overtime -->
                            <input type="hidden" name="ot_total_hours" value="{{ $overtime['total_hours'] }}">
                            <input type="hidden" name="special_holiday_hours" value="{{ $overtime['special_holiday_hours'] }}">
                            <input type="hidden" name="regular_holiday_hours" value="{{ $overtime['regular_holiday_hours'] }}">
                            <input type="hidden" name="rest_day_hours" value="{{ $overtime['rest_day_hours'] }}">
                            <input type="hidden" name="total_overtime_pay" value="{{ number_format($overtime['total_overtime_pay'],2,'.','') }}">

                            <tr>
                                <td class="text-right grey">
                                    <input class="text-right" type="number" step="any" name="ee_regular_ot_pay" id="ee_regular_ot_pay" value="{{ number_format($overtime['regular_ot_pay'],2,'.','') }}" onchange="compute_ee_gross()">
                                </td>
                                <td class="grey td-label">Overtime Regular Holiday</td>
                            </tr>
                            <tr>
                                <td class="text-right grey">
                                    <input class="text-right" type="number" step="any" name="ee_special_ot_pay" id="ee_special_ot_pay" value="{{ number_format($overtime['special_ot_pay'],2,'.','') }}" onchange="compute_ee_gross()">
                                </td>
                                <td class="grey td-label">Overtime Special Holiday</td>
                            </tr>
                            <tr>
                                <td class="text-right grey">
                                    <input class="text-right" type="number" step="any" name="ee_restday_ot_pay" id="ee_restday_ot_pay" value="{{ number_format($overtime['restday_ot_pay'],2,'.','') }}" onchange="compute_ee_gross()">
                                </td>
                                <td class="grey td-label">Overtime Restday</td>
                            </tr>



                            <tr>
                                <td class="text-right head-lightblue">
                                    <input class="text-right total" type="number" step="any" name="ee_computed_gross" id="ee_computed_gross" readonly>
                                </td>
                                <td class="head-lightblue">Gross</td>
                            </tr>



                            <tr>
                                <td class="text-right">
                                    <input class="text-right" type="number" step="any" name="ee_sss" id="ee_sss" value="{{ number_format($sss['EE'] * -1,2,'.','') }}" onchange="compute_ee_deductions()">
                                </td>
                                <td class="td-label">SSS</td>
                            </tr>
                            <tr>
                                <td class="text-right">
                                    <input class="text-right" type="number" step="any" name="ee_philhealth" id="ee_philhealth" value="{{ number_format($philhealth['EE'] * -1,2,'.','') }}" onchange="compute_ee_deductions()">
                                </td>
                                <td class="td-label">Philhealth</td>
                            </tr>
                            <tr>
                                <td class="text-right">
                                    <input class="text-right" type="number" step="any" name="ee_pagibig" id="ee_pagibig" value="{{ number_format($pagibig['EE'] * -1,2,'.','') }}" onchange="compute_ee_deductions()">
                                </td>
                                <td class="td-label">Pag-Ibig</td>
                            </tr>
                            <tr>
                                <td class="text-right">
                                    <input class="text-right" type="number" step="any" name="ee_hmo" id="ee_hmo" value="{{ number_format($payroll->hmo, 2,'.','') }}" onchange="compute_ee_deductions()">
                                </td>
                                <td class="td-label">HMO</td>
                            </tr>



                            <!-- tax -->
                            <tr>
                                <td class="text-right light-grey">
                                    <input class="text-right" type="number" step="any" name="ee_witholding_tax" id="ee_witholding_tax" value="{{ number_format($tax['witholding_tax'] * -1, 2,'.','') }}" onchange="compute_ee_deductions()">
                                </td>
                                <td class="light-grey td-label">Witholding Tax</td>
                            </tr>



                            <tr>
                                <td class="text-right head-lightblue">
                                    <input class="text-right total" type="number" step="any" name="ee_deductions" id="ee_deductions" readonly>
                                </td>
                                <td class="head-lightblue">Deductions</td>
                            </tr>
                            <tr>
                                <td class="text-right head-blue">
                                    <input class="text-right total" type="number" step="any" name="ee_net" id="ee_net" readonly>
                                </td>
                                <td class="head-blue">Net</td>
                            </tr>
                        </table>
                    </div>
                





                    <!-- EMPLOYER -->
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                        <table class="ledger">
                            <tr>
                                <td colspan="3" class="head-green">EMPLOYER</td>
                            </tr>
                            <tr>
                                <td class="text-right" style="width:55px; max-width:55px">
                                    <input class="text-right" type="number" step="any" name="basic" id="basic" value="{{ number_format($er_details['basic'],2,'.','') }}" onchange="compute_er_gross()">
                                </td>
                                <td class="td-label">{{ $employee->position == NULL ? 'Basic' : strtoupper($employee->position) }}</td>
                            </tr>
                            <tr>
                                <td class="text-right">
                                    <input class="text-right" type="number" step="any" name="er_food_allowance" id="er_food_allowance" value="{{ number_format($er_details['food_allowance'], 2, '.', '') }}" onchange="compute_er_gross()">
                                </td>
                                <td class="td-label">Food Allowance</td>
                            </tr>
                            <tr>
                                <td class="text-right">
                                    <input class="text-right" type="number" step="any" name="er_transportation_allowance" id="er_transportation_allowance" value="{{ number_format($er_details['transportation_allowance'], 2, '.', '') }}" onchange="compute_er_gross()">
                                </td>
                                <td class="td-label">Transportation Allowance</td>
                            </tr>
                            <tr>
                                <td class="text-right">
                                    <input class="text-right" type="number" step="any" name="er_personal_allowance" id="er_personal_allowance" value="{{ number_format($er_details['personal_allowance'], 2, '.', '') }}" onchange="compute_er_gross()">
                                </td>
                                <td class="td-label">Personal Allowance</td>
                            </tr>

                            <!-- overtime -->
                            <tr>
                                <td class="text-right light-grey">
                                    <input class="text-right" type="number" step="any" name="er_regular_holiday_pay" id="er_regular_holiday_pay" value="{{ number_format($er_details['regular_holiday_pay'], 2, '.', '') }}" onchange="compute_er_gross()">
                                </td>
                                <td class="light-grey td-label">Regular Holiday</td>
                            </tr>
                            <tr>
                                <td class="text-right light-grey">
                                    <input class="text-right" type="number" step="any" name="er_special_holiday_pay" id="er_special_holiday_pay" value="{{ number_format($er_details['special_holiday_pay'], 2, '.', '') }}" onchange="compute_er_gross()">
                                </td>
                                <td class="light-grey td-label">Special Holiday</td>
                            </tr>
                            <tr>
                                <td class="text-right light-grey">
                                    <input class="text-right" type="number" step="any" name="er_restday_days_pay" id="er_restday_days_pay" value="{{ number_format($er_details['restday_days_pay'], 2, '.', '') }}" onchange="compute_er_gross()">
                                </td>
                                <td class="light-grey td-label">Restday</td>
                            </tr>

                            <tr>
                                <td class="text-right grey">
                                    <input class="text-right" type="number" step="any" name="er_regular_ot_pay" id="er_regular_ot_pay" value="{{ number_format($er_details['regular_ot_pay'], 2, '.', '') }}" onchange="compute_er_gross()">
                                </td>
                                <td class="grey td-label">Overtime Regular Holiday</td>
                            </tr>
                            <tr>
                                <td class="text-right grey">
                                    <input class="text-right" type="number" step="any" name="er_special_ot_pay" id="er_special_ot_pay" value="{{ number_format($er_details['special_ot_pay'], 2, '.', '') }}" onchange="compute_er_gross()">
                                </td>
                                <td class="grey td-label">Overtime Special Holiday</td>
                            </tr>
                            <tr>
                                <td class="text-right grey">
                                    <input class="text-right" type="number" step="any" name="er_restday_ot_pay" id="er_restday_ot_pay" value="{{ number_format($er_details['restday_ot_pay'], 2, '.', '') }}" onchange="compute_er_gross()">
                                </td>
                                <td class="grey td-label">Overtime Restday</td>
                            </tr>

                            <tr>
                                <td class="text-right head-lightblue">
                                    <input class="text-right total" type="number" step="any" name="er_computed_gross" id="er_computed_gross" disabled>
                                </td>
                                <td class="head-lightblue">Gross</td>
                            </tr>



                            <tr>
                                <td class="text-right">
                                    <input class="text-right" type="number" step="any" name="er_sss" id="er_sss" value="{{ $sss['ER'] }}" onchange="compute_er_deductions()">
                                </td>
                                <td class="td-label">SSS</td>
                            </tr>
                            <tr>
                                <td class="text-right">
                                    <input class="text-right" type="number" step="any" name="er_philhealth" id="er_philhealth" value="{{ $philhealth['ER'] }}" onchange="compute_er_deductions()">
                                </td>
                                <td class="td-label">Philhealth</td>
                            </tr>
                            <tr>
                                <td class="text-right">
                                    <input class="text-right" type="number" step="any" name="er_pagibig" id="er_pagibig" value="{{ $pagibig['ER'] }}" onchange="compute_er_deductions()">
                                </td>
                                <td class="td-label">Pag-Ibig</td>
                            </tr>
                            <tr>
                                <td class="text-right">
                                    <input class="text-right" type="number" step="any" name="er_hmo" id="er_hmo" value="{{ number_format($er_details['hmo'], 2, '.', '') }}" onchange="compute_er_deductions()">
                                </td>
                                <td class="td-label">HMO</td>
                            </tr>



                            <!-- tax -->
                            <tr>
                                <td class="text-right light-grey">
                                    <input class="text-right" type="number" step="any" name="er_witholding_tax" id="er_witholding_tax" value="{{ number_format($er_details['witholding_tax'], 2) }}" onchange="compute_er_deductions()">
                                </td>
                                <td class="light-grey td-label">Witholding Tax</td>
                            </tr>

                            <tr>
                                <td class="text-right head-lightblue">
                                    <input class="text-right total" type="number" step="any" name="er_deductions" id="er_deductions" disabled>
                                </td>
                                <td class="head-lightblue">Non Wage Labour Costs</td>
                            </tr>
                            <tr>
                                <td class="text-right head-blue">
                                    <input class="text-right total" type="number" step="any" name="er_net" id="er_net" disabled>
                                </td>
                                <td class="head-blue">Net</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <br>

                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                        <div class="form-group">
                            @php($chk = ($payroll['is_paid'] == 1) ? 'checked' : '')
                            <input type="checkbox" name="is_paid" id="is_paid" {{ $chk }}>&nbsp; Is Paid
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="form-group">
                            <label for="notes">Notes</label>
                            <textarea class="form-control form-control-sm" name="notes" id="notes" cols="30" rows="5">{{ $payroll['notes'] }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="form-group text-center">
                            <button class="btn btn-success" type="submit"><i class="mdi mdi-pencil"></i> &nbsp;Update</button>
                            <a href="{{ route('payroll.generate_pdf', $payroll['id']) }}" class="btn btn-primary" target="_blank"><i class="mdi mdi-book-open"></i> &nbsp;Preview PDF</a>
                            <button class="btn btn-success" type="submit"><i class="mdi mdi-content-save"></i> &nbsp;Save</button>
							<a href="javascript:void(0);" data-toggle="modal" data-target="#DeleteModal" class="btn btn-danger"><i class="mdi mdi-delete"></i> &nbsp;Remove</a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>



<!-- Modal -->
<div class="modal fade" id="DeleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalCenterTitle">Are you sure?</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <span class="delete-alert">Do you want to remove <span class="badge badge-primary">{{$payroll['payroll_number']}}</span> ? This payroll can't be restored anymore.</span>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <form method="POST" action="{{ route('payroll.destroy', $payroll['id']) }}" >
                {{ csrf_field() }}
                {{ method_field('DELETE') }}
                <button class="btn btn-danger" type="submit">&nbsp;Delete</button>
            </form>
        </div>
        </div>
    </div>
</div>
@endsection



@section('customjs')
<!--<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.js"></script>-->
<!-- <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script> -->
<script type="text/javascript">

    $('#loading-bg').hide();
    $('#payroll_details').hide();

    compute_ee_gross();
    compute_ee_deductions();
    compute_ee_net();
    compute_non_wage_costs();
    compute_er_gross();
    compute_er_net();


    // ONCHANGE EMPLOYEE
    $('#ee_sss').keyup(function() {
        compute_ee_deductions();
        compute_ee_net();
    });
    $('#ee_philhealth').keyup(function() {
        compute_ee_deductions();
        compute_ee_net();
    });
    $('#ee_pagibig').keyup(function() {
        compute_ee_deductions();
        compute_ee_net();
    });
    $('#ee_hmo').keyup(function() {
        compute_ee_deductions();
        compute_ee_net();
    });
    $('#ee_witholding_tax').keyup(function() {
        compute_ee_deductions();
        compute_ee_net();
    });
    $('#ee_gross').keyup(function(){
        compute_ee_gross();
        compute_ee_net();
    });
    $('#ee_food_allowance').keyup(function(){
        compute_ee_gross();
        compute_ee_net();
    });
    $('#ee_transportation_allowance').keyup(function(){
        compute_ee_gross();
        compute_ee_net();
    });
    $('#ee_personal_allowance').keyup(function(){
        compute_ee_gross();
        compute_ee_net();
    });
    $('#ee_regular_holiday_pay').keyup(function(){
        compute_ee_gross();
        compute_ee_net();
    });
    $('#ee_special_holiday_pay').keyup(function(){
        compute_ee_gross();
        compute_ee_net();
    });
    $('#ee_restday_days_pay').keyup(function(){
        compute_ee_gross();
        compute_ee_net();
    });
    $('#ee_regular_ot_pay').keyup(function(){
        compute_ee_gross();
        compute_ee_net();
    });
    $('#ee_special_ot_pay').keyup(function(){
        compute_ee_gross();
        compute_ee_net();
    });
    $('#ee_restday_ot_pay').keyup(function(){
        compute_ee_gross();
        compute_ee_net();
    });

// ONCHANGE EMPLOYEE
    $('#er_sss').keyup(function() {
        compute_er_deductions();
        compute_er_net();
    });
    $('#er_philhealth').keyup(function() {
        compute_er_deductions();
        compute_er_net();
    });
    $('#er_pagibig').keyup(function() {
        compute_er_deductions();
        compute_er_net();
    });
    $('#er_hmo').keyup(function() {
        compute_er_deductions();
        compute_er_net();
    });
    $('#er_witholding_tax').keyup(function() {
        compute_er_deductions();
        compute_er_net();
    });
    $('#er_gross').keyup(function(){
        compute_er_gross();
        compute_er_net();
    });
    $('#basic').keyup(function(){
        compute_er_gross();
        compute_er_net();
    });
    $('#er_food_allowance').keyup(function(){
        compute_er_gross();
        compute_er_net();
    });
    $('#er_transportation_allowance').keyup(function(){
        compute_er_gross();
        compute_er_net();
    });
    $('#er_personal_allowance').keyup(function(){
        compute_er_gross();
        compute_er_net();
    });
    $('#er_regular_holiday_pay').keyup(function(){
        compute_er_gross();
        compute_er_net();
    });
    $('#er_special_holiday_pay').keyup(function(){
        compute_er_gross();
        compute_er_net();
    });
    $('#er_restday_days_pay').keyup(function(){
        compute_er_gross();
        compute_er_net();
    });
    $('#er_regular_ot_pay').keyup(function(){
        compute_er_gross();
        compute_er_net();
    });
    $('#er_special_ot_pay').keyup(function(){
        compute_er_gross();
        compute_er_net();
    });
    $('#er_restday_ot_pay').keyup(function(){
        compute_er_gross();
        compute_er_net();
    });
	$('#period_from').change(function (){
        $("#updated_period_from").val($(this).val());
    });
    $('#period_to').change(function (){
        $("#updated_period_to").val($(this).val());
    });
    $('#payroll_date').change(function (){
        $("#updated_payroll_date").val($(this).val());
    });

    // CHECKBOX 
    function chk(chk_name, chk_value, er_value) {        
        var chk_name = (chk_name.name == '') ? chk_name.id : chk_name.name;
        ee_textbox_name = chk_name.replace('chk_','ee_');
        er_textbox_name = chk_name.replace('chk_','er_');

        if(document.getElementById(chk_name).checked) {
            document.getElementById(ee_textbox_name).value = chk_value.toFixed(2);
            document.getElementById(er_textbox_name).value = (er_value*-1).toFixed(2);
        } else {
            document.getElementById(ee_textbox_name).value = Number(0).toFixed(2);
            document.getElementById(er_textbox_name).value = Number(0).toFixed(2);
        }
        compute_ee_gross();
        compute_ee_deductions();
        compute_ee_net();
        compute_non_wage_costs();
        compute_er_net();
    }


    // COMPUTE EMPLOYEE VALUES
    function compute_ee_gross() {
        var bs = $('#ee_gross').val() < 0 ? 0 : $('#ee_gross').val();
        var fa = $('#ee_food_allowance').val() < 0 ? 0 : $('#ee_food_allowance').val();
        var ta = $('#ee_transportation_allowance').val() < 0 ? 0 : $('#ee_transportation_allowance').val();
        var pa = $('#ee_personal_allowance').val() < 0 ? 0 : $('#ee_personal_allowance').val();
        var rh = $('#ee_regular_holiday_pay').val() < 0 ? 0 : $('#ee_regular_holiday_pay').val();
        var sh = $('#ee_special_holiday_pay').val() < 0 ? 0 : $('#ee_special_holiday_pay').val();
        var rd = $('#ee_restday_days_pay').val() < 0 ? 0 : $('#ee_restday_days_pay').val();
        var orh = $('#ee_regular_ot_pay').val() < 0 ? 0 : $('#ee_regular_ot_pay').val();
        var osh = $('#ee_special_ot_pay').val() < 0 ? 0 : $('#ee_special_ot_pay').val();
        var ord = $('#ee_restday_ot_pay').val() < 0 ? 0 : $('#ee_restday_ot_pay').val();
        var ee_computed_gross = document.getElementById('ee_computed_gross');
        // var er_computed_gross = document.getElementById('er_computed_gross');
        // do not return this: only for displaying
        // er_computed_gross.value = (Number(bs) + Number(fa) + Number(ta) + Number(pa) + Number(rh) + Number(sh) + Number(rd) + Number(orh) + Number(osh) + Number(ord)).toFixed(2);

        $('#ee_gross').val((bs*1).toFixed(2));
        $('#ee_food_allowance').val((fa*1).toFixed(2));
        $('#ee_transportation_allowance').val((ta*1).toFixed(2));
        $('#ee_personal_allowance').val((pa*1).toFixed(2));
        $('#ee_regular_holiday_pay').val((rh*1).toFixed(2));
        $('#ee_special_holiday_pay').val((sh*1).toFixed(2));
        $('#ee_restday_days_pay').val((rd*1).toFixed(2));
        $('#ee_regular_ot_pay').val((orh*1).toFixed(2));
        $('#ee_special_ot_pay').val((osh*1).toFixed(2));
        $('#ee_restday_ot_pay').val((ord*1).toFixed(2));
        
        return ee_computed_gross.value = Number(Number(bs) + Number(fa) + Number(ta) + Number(pa) + Number(rh) + Number(sh) + Number(rd) + Number(orh) + Number(osh) + Number(ord)).toFixed(2);
    }

    function compute_ee_deductions() {
        var sss = $('#ee_sss').val() < 0 ? $('#ee_sss').val(): $('#ee_sss').val() * -1;
        var philhealth = $('#ee_philhealth').val() < 0 ? $('#ee_philhealth').val(): $('#ee_philhealth').val() * -1;
        var pagibig = $('#ee_pagibig').val() < 0 ? $('#ee_pagibig').val(): $('#ee_pagibig').val() * -1;
        var hmo = $('#ee_hmo').val() < 0 ? $('#ee_hmo').val() : $('#ee_hmo').val() * -1;
        var wtax = $('#ee_witholding_tax').val() < 0 ? $('#ee_witholding_tax').val(): $('#ee_witholding_tax').val() * -1;
        var deductions = document.getElementById('ee_deductions');

        var deductions_amount = (Number(sss) + Number(philhealth) + Number(pagibig) + Number(hmo) + Number(wtax)).toFixed(2);
        var non_taxable_amount = (Number(sss) + Number(philhealth) + Number(pagibig)).toFixed(2);

        // make deductions negative
        $('#ee_sss').val((sss * 1).toFixed(2));
        $('#ee_philhealth').val((philhealth * 1).toFixed(2));
        $('#ee_pagibig').val((pagibig * 1).toFixed(2));
        $('#ee_hmo').val((hmo * 1).toFixed(2));
        $('#ee_witholding_tax').val((wtax * 1).toFixed(2));

        // update employee non-taxable
        $('#ee_non_taxable_income').val((non_taxable_amount * 1).toFixed(2));

        return deductions.value = deductions_amount;
    }

    function compute_ee_net() {
        var ee_dd       = $("#ee_deductions").val();
        var ee_gross    = $("#ee_computed_gross").val();
        ee_gross        = parseFloat(ee_gross);
        ee_dd           = parseFloat(ee_dd);
        ee_dd           = Math.abs(ee_dd);
        var ee_net      = 0.00;
        if(ee_gross > 0){
            ee_net      = ee_gross - ee_dd;
            ee_net      = ee_net.toFixed(2);
        }
        ee_net          = (ee_net <= 0) ? '0.00' : ee_net;

        $('#ee_net').val(ee_net);
    }










    // COMPUTE EMPLOYER VALUES
    function compute_er_gross() {
        var bs = $('#basic').val() < 0 ? 0 : $('#basic').val();
        var fa = $('#er_food_allowance').val() < 0 ? 0 : $('#er_food_allowance').val();
        var ta = $('#er_transportation_allowance').val() < 0 ? 0 : $('#er_transportation_allowance').val();
        var pa = $('#er_personal_allowance').val() < 0 ? 0 : $('#er_personal_allowance').val();
        var rh = $('#er_regular_holiday_pay').val() < 0 ? 0 : $('#er_regular_holiday_pay').val();
        var sh = $('#er_special_holiday_pay').val() < 0 ? 0 : $('#er_special_holiday_pay').val();
        var rd = $('#er_restday_days_pay').val() < 0 ? 0 : $('#er_restday_days_pay').val();
        var orh = $('#er_regular_ot_pay').val() < 0 ? 0 : $('#er_regular_ot_pay').val();
        var osh = $('#er_special_ot_pay').val() < 0 ? 0 : $('#er_special_ot_pay').val();
        var ord = $('#er_restday_ot_pay').val() < 0 ? 0 : $('#er_restday_ot_pay').val();
        var er_computed_gross = document.getElementById('er_computed_gross');
        
        // remove comma
        // br = parseFloat(br.replace(/,/g, ''));
        // fa = parseFloat(fa.replace(/,/g, ''));
        // pa = parseFloat(pa.replace(/,/g, ''));
        // pa = parseFloat(br.replace(/,/g, ''));
        // br = parseFloat(br.replace(/,/g, ''));
        // br = parseFloat(br.replace(/,/g, ''));
        // br = parseFloat(br.replace(/,/g, ''));
        // br = parseFloat(br.replace(/,/g, ''));

        // do not return this: only for displaying
        er_computed_gross.value = (Number(bs) + Number(fa) + Number(ta) + Number(pa) + Number(rh) + Number(sh) + Number(rd) + Number(orh) + Number(osh) + Number(ord)).toFixed(2);

        $('#basic').val((bs*1).toFixed(2));
        $('#er_food_allowance').val((fa*1).toFixed(2));
        $('#er_transportation_allowance').val((ta*1).toFixed(2));
        $('#er_personal_allowance').val((pa*1).toFixed(2));
        $('#er_regular_holiday_pay').val((rh*1).toFixed(2));
        $('#er_special_holiday_pay').val((sh*1).toFixed(2));
        $('#er_restday_days_pay').val((rd*1).toFixed(2));
        $('#er_regular_ot_pay').val((orh*1).toFixed(2));
        $('#er_special_ot_pay').val((osh*1).toFixed(2));
        $('#er_restday_ot_pay').val((ord*1).toFixed(2));

        return er_computed_gross.value = Number(Number(bs) + Number(fa) + Number(ta) + Number(pa) + Number(rh) + Number(sh) + Number(rd) + Number(orh) + Number(osh) + Number(ord)).toFixed(2);
    }



    function compute_er_deductions() {
        var sss = $('#er_sss').val() < 0 ? $('#er_sss').val(): $('#er_sss').val() * -1;
        var philhealth = $('#er_philhealth').val() < 0 ? $('#er_philhealth').val(): $('#er_philhealth').val() * -1;
        var pagibig = $('#er_pagibig').val() < 0 ? $('#er_pagibig').val(): $('#er_pagibig').val() * -1;
        var hmo = $('#er_hmo').val() < 0 ? $('#er_hmo').val(): $('#er_hmo').val() * -1;
        var wtax = $('#er_witholding_tax').val() < 0 ? $('#er_witholding_tax').val() : $('#er_witholding_tax').val() * -1;
        // var deductions = document.getElementById('er_deductions');

        // make deductions negative
        $('#er_sss').val((sss *1).toFixed(2));
        $('#er_philhealth').val((philhealth *1).toFixed(2));
        $('#er_pagibig').val((pagibig  *1).toFixed(2));
        $('#er_hmo').val((hmo *1).toFixed(2));
        $('#er_witholding_tax').val((wtax *1).toFixed(2));
        var deductions = document.getElementById('er_deductions');

        return deductions.value = (Number(sss) + Number(philhealth) + Number(pagibig) + Number(hmo) + Number(wtax)).toFixed(2);
    }


    function compute_non_wage_costs() {
        var sss = $('#er_sss').val();
        var philhealth = $('#er_philhealth').val();
        var pagibig = $('#er_pagibig').val();
        var hmo = $('#er_hmo').val();
        var tax = $('#er_witholding_tax').val();
        var non_wage = document.getElementById('er_deductions');

        return non_wage.value = (Number(sss) + Number(philhealth) + Number(pagibig) + Number(hmo) + Number(tax)).toFixed(2);
    }

    function compute_er_net() {
        var er_dd       = $("#er_deductions").val();
        var er_gross    = $("#er_computed_gross").val();
        er_gross        = parseFloat(er_gross);
        er_dd           = parseFloat(er_dd);
        er_dd           = Math.abs(er_dd);
        var ee_net      = 0.00;
        if(er_gross > 0){
            er_net      = er_gross + er_dd;
            er_net      = er_net.toFixed(2);
        }
        er_net          = (er_net <= 0) ? '0.00' : er_net;

        
        $('#er_net').val(er_net);
    }


    // DATE TIME ASSIGNMENT
    function date_assignment() {
        var asiaTime = new Date().toLocaleString("en-US", {timeZone: "Asia/Manila"});
        var date = new Date(asiaTime);
        var current_dateTime = new Date().getTime();
        var first_date = new Date(date.getFullYear(), date.getMonth(), 1); // 1
        var last_date = new Date(date.getFullYear(), date.getMonth() + 1, 0); // 0
		$("#payroll_date").datepicker().datepicker("setDate", date);
        $("#period_from").datepicker().datepicker("setDate", first_date);
        $("#period_to").datepicker().datepicker("setDate", last_date);

        


        // let today = new Date(asiaTime).toISOString().substr(0, 10);
        // let today = new Date(asiaTime).toString().substr(4, 11);
        // console.log(today.toISOString());
        // document.getElementById("payroll_date").value = '2019-08-02';
    }


    // REQUEST
    $('#employee_id').on('change', function(){
        // date_assignment();
        recompute(true);
    });
    $('#period_from').on("input change", function() {
        recompute();
    });
    $('#period_to').on("input change", function() {
        recompute();
    });



    // AUTOMATE THE COMPUTATION BASED ON THE CHANGES OF FIELD ABOVE (ONLY FOR CREATING PAYROLL)
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#preview_payroll').click(function(){
        var employee_id = $('#employee_id').val();
        var period_from = $('#period_from').val();
        var period_to = $('#period_to').val();

        $.ajax({
            url: "{{ url('/payrollledger/generate') }}",
            type:"POST",
            dataType:"json",
            data: {
                employee_id: employee_id,
                from: period_from,
                to: period_to
            },
            beforeSend:function(data){
                //console.log(data); 
            },
            success:function(data) {
                //console.log(data);
                window.location.href = "{{ url('/payrollledger/preview') }}";
            },
            fail: function(xhr, textStatus, errorThrown){
                //console.log("request failed");
            },
        });  
    }); 

    

    // RECOMPUTE AND RENDER SCREEN AGAIN
    
    var recompute_times = 1; //create default 1 to stop autofill

    function recompute(is_employee_change = false) {
        $('#payroll_details').show();

        var id = $('#employee_id').val();
        var period_from = $('#period_from').val();
        var period_to = $('#period_to').val();
        var payroll_date = $('#payroll_date').val();

        if(id) {
            $('#loading-bg').show();
            $.ajax({
                url: "{{ url('/payrollledger/generate') }}",
                type:"POST",
                dataType:"json",
                data: {
                    employee_id: id,
                    from: period_from,
                    to: period_to,
					payroll_date : payroll_date
                },
                beforeSend:function(xhr, data){

                },
                success:function(data) {
                    $('#loading-bg').hide();
                    // console.log(data);
					$("#description").val(data[0]['payroll_description']);
                    if(recompute_times <= 0 || is_employee_change == true){
                       // recompute_times = 1;
                        
                        $('#employee_number').val(data[1]['employee_number']);

                        $('#ee_gross').val(positiveNumberFormat(data[0]['gross'])); // basic pay
                        $('#ee_transportation_allowance').val(positiveNumberFormat(data[0]['allowances']['transportation_allowance']));
                        $('#ee_personal_allowance').val(positiveNumberFormat(data[0]['allowances']['personal_allowance']));

                        $('#ee_regular_holiday_day').val(positiveNumberFormat(data[0]['days']['regular_holiday_pay']));
                        $('#ee_special_holiday_day').val(positiveNumberFormat(data[0]['days']['special_holiday_pay']));
                        $('#ee_restday_days_pay').val(data[0]['days']['restday_days_pay']);

                        $('#ee_regular_ot_pay').val(positiveNumberFormat(data[0]['overtimes']['ee_regular_ot_pay']));
                        $('#ee_special_ot_pay').val(positiveNumberFormat(data[0]['overtimes']['ee_special_ot_pay']));
                        $('#ee_restday_ot_pay').val(positiveNumberFormat(data[0]['overtimes']['ee_restday_ot_pay']));

                        $('#ee_computed_gross').val(positiveNumberFormat(compute_ee_gross()));

                        $('#ee_sss').val(negativeNumberFormat(data[0]['sss']['EE']));
                        $('#ee_philhealth').val(negativeNumberFormat(data[0]['philhealth']['EE']));
                        $('#ee_pagibig').val(negativeNumberFormat(data[0]['pagibig']['EE']));
                        $('#ee_hmo').val(negativeNumberFormat(data[1]['hmo']));

                        $('#ee_witholding_tax').val(positiveNumberFormat(data[0]['tax']['witholding_tax']));
                        $('#ee_non_taxable_income').val(positiveNumberFormat(data[0]['tax']['non_taxable_income']));
                        $('#ee_taxable_income').val(positiveNumberFormat(data[0]['tax']['taxable_income']));
                        
                        $('#ee_deductions').val(positiveNumberFormat(data[0]['total_deduction']));

                        $('#ee_net').val(positiveNumberFormat(data[0]['netpay']));
						
						$('#er_food_allowance').val(positiveNumberFormat(data[0]['allowances']['food_allowance']));
                        $('#er_transportation_allowance').val(positiveNumberFormat(data[0]['allowances']['transportation_allowance']));
                        $('#er_personal_allowance').val(positiveNumberFormat(data[0]['allowances']['personal_allowance']));
						
                        $('#er_regular_holiday_pay').val(positiveNumberFormat(data[0]['days']['regular_holiday_pay']));
                        $('#er_special_holiday_pay').val(positiveNumberFormat(data[0]['days']['special_holiday_pay']));
                        $('#er_restday_days_pay').val(positiveNumberFormat(data[0]['days']['restday_days_pay']));
                        $('#er_regular_ot_pay').val(positiveNumberFormat(data[0]['overtimes']['ee_regular_ot_pay']));
                        $('#er_special_ot_pay').val(positiveNumberFormat(data[0]['overtimes']['ee_special_ot_pay']));
                        $('#er_restday_ot_pay').val(positiveNumberFormat(data[0]['overtimes']['ee_restday_ot_pay']));
                        

                        // EMPLOYER
                        $('#basic').val(positiveNumberFormat(data[0]['gross'])); // basic pay

                        er_sss = positiveNumberFormat(data[0]['sss']['ER']);
                        er_phi = positiveNumberFormat(data[0]['philhealth']['ER']);
                        er_pag = positiveNumberFormat(data[0]['pagibig']['ER']);
                        er_hmo = positiveNumberFormat(data[1]['hmo']);
                        er_tax = positiveNumberFormat(0);
                        
                        $('#er_sss').val(er_sss);
                        $('#er_philhealth').val(er_phi);
                        $('#er_pagibig').val(er_pag);
                        $('#er_hmo').val(er_hmo);
                        $('#er_witholding_tax').val(er_tax);
                        
                        er_deductions = Number(Number(er_sss) + Number(er_phi) + Number(er_pag) + Number(er_hmo) + Number(er_tax)).toFixed(2);
                        $('#er_deductions').val(er_deductions);

                        er_computed_gross = $('#er_computed_gross').val();
                        er_net = er_computed_gross - er_deductions;

                        $('#er_net').val(er_net);
                    }

                    // UPDATE PARAMETERS
                    $('#updated_period_from').val(period_from);
                    $('#updated_period_to').val(period_to);
                    $('#updated_payroll_date').val(payroll_date);

                    compute_ee_gross();
                    compute_ee_deductions();
                    compute_ee_net();
                    compute_non_wage_costs();
                    compute_er_net();

                },
                error: function(errorThrown){
                    $('#loading-bg').hide();
                },
                fail: function(xhr, textStatus, errorThrown){
                    $('#loading-bg').hide();
                },
                complete: function(){
                    $('#loading-bg').hide();
                }
            });
        }
    }

    function positiveNumberFormat(amount){
        if(amount == null || amount == 'NAN'){
            new_amount = parseFloat(0).toFixed(2);
        } else {
            new_amount = parseFloat(amount).toFixed(2);
        }
        return new_amount;
    }

    function negativeNumberFormat(amount){
        if(amount == null || amount == 'NAN'){
            new_amount = parseFloat(0).toFixed(2);
        } else {
            new_amount = parseFloat(amount * -1).toFixed(2);
        }
        return new_amount;
    }

    // PREVENT ENTER KEY FROM SUBMITTING FORM
    $(document).ready(function() {
        $(window).keydown(function(event){
            if(event.keyCode == 13) {
                event.preventDefault();
                return false;
            }
        });
    });

</script>
@endsection