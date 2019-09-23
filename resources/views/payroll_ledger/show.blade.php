@extends('layouts.master')

@section('content')
<div class="content-wrapper">
    <div class="content">
        <div class="card">
            <div class="card-body">
                
                <p>{{ $payroll->name}}</p>
                <p>{{ $payroll->employee_number}}</p>
                <p>{{ $payroll->address}}</p>
                
                <hr>
                <h4>Payroll information:</h4>
                <table class="table table-sm table-bordered">
                    <thead>
                        <th>Billing Number:</th>
                        <th>Payroll Period:</th>
                    </thead>
                    <tbody>
                    <tr>
                        <td>{{ $payroll->billing_number}}</td>
                        <td>{{ $payroll->period}}</td>
                    </tr>
                    <tr>
                        <td>Rate:</td>
                        <td>{{ number_format($payroll->basic_salary,2,'.',',') }}</td>
                    </tr>
                    </tbody>
                </table>

                <div class="mt-5">
                    <table class="table table-sm table-bordered table-striped">
                        <thead>
                            <th colspan="2">Description</th>
                            <th>Amount</th>
                            <th>Total</th>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="2">Regular Hours:</td>
                                <td>{{ $payroll->total_hours}} hrs</td>
                                <td class="text-right">{{ $payroll->gross }}</td>
                            </tr>
                            <tr>
                                <td rowspan="3">Working Days</td>
                                <td>Rest Day:</td>
                                <td>{{ $wdays['restday_days']}}</td>
                                <td>{{ $wdays['restday_days_pay']}}</td>
                            </tr>
                            <tr>
                                <td>Regular Holiday:</td>
                                <td>{{ $wdays['regular_holidays']}}</td>
                                <td>{{ $wdays['regular_holiday_pay']}}</td>
                            </tr>
                            <tr>
                                <td>Special Holiday:</td>
                                <td>{{ $wdays['special_holidays']}}</td>
                                <td>{{ $wdays['special_holiday_pay']}}</td>
                            </tr>
                            <tr>
                                <td rowspan="3">Allowances</td>
                                <td>Food allowance:</td>
                                <td>{{ $allowances['food_allowance']}}</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Transportation Allowance:</td>
                                <td>{{ $allowances['transportation_allowance']}}</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Personal Allowance:</td>
                                <td>{{ $allowances['personal_allowance']}}</td>
                                <td class="text-right">{{ $allowances['total_allowances']}}</td>
                            </tr>
                            
                            <tr>
                                <td rowspan="4">Overtimes</td>
                                <td>Regular Holiday:</td>
                                <td>{{ $overtime['regular_holiday_hours']}}</td>
                                <td>{{ $overtime['regular_ot_pay']}}</td>
                            </tr>
                            <tr>
                                <td>Special Holiday:</td>
                                <td>{{ $overtime['special_holiday_hours']}}</td>
                                <td>{{ $overtime['special_ot_pay']}}</td>
                            </tr>
                            <tr>
                                <td>Restday:</td>
                                <td>{{ $overtime['rest_day_hours']}}</td>
                                <td>{{ $overtime['restday_ot_pay']}}</td>
                            </tr>
                            <tr>
                                <td>Total:</td>
                                <td>{{ $overtime['total_hours']}}</td>
                                <td class="text-right">{{ $overtime['total_overtime_pay']}}</td>
                            </tr>
                            <tr>
                                <td rowspan="3">Deductions</td>
                                <td>SSS:</td>
                                <td>{{ $sss['EE'] }}</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>PhilHealth:</td>
                                <td>{{ $philhealth['EE'] }}</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>PAG-IBIG:</td>
                                <td>{{ $pagibig['EE'] }}</td>
                                <td class="text-right">Less {{ $payroll->total_deduction }}</td>
                            </tr>
                            <tr>
                                <td rowspan="3">TAX</td>
                                <td>Non-Taxable Income:</td>
                                <td>{{ $tax['non_taxable_income']}}</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Taxable Income:</td>
                                <td>{{ $tax['taxable_income']}}</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Witholding Tax:</td>
                                <td>{{ $tax['witholding_tax'] }}</td>
                                <td class="text-right">{{ $tax['witholding_tax'] }}</td>
                            </tr>
                            <tr>
                                <td colspan="3"><h3>Payout</h3></td>
                                <td class="text-right"><h3>₱ {{ $payroll->netpay}}</h3></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 