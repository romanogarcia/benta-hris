<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://fonts.googleapis.com/css?family=Roboto&display=swap" rel="stylesheet">
    <title>PAYROLL</title>
    <style type="text/css">
        /* @import url('https://fonts.googleapis.com/css?family=Roboto&display=swap'); */
        body, body * {
            font-family: 'Roboto', sans-serif;
            font-size: 12px;
        }
        .company_info {
            margin-bottom: 20px;
        }
        .company_name,
        .company_address {
            text-decoration: underline;
            margin: 5px 15px;
        }
        .employee_info {
            margin: 0 15px;
        }
        .employee_address {
            width: 25%;
        }
        .title {
            margin-top: 55px;
            margin-bottom: 20px;
        }
        .sub-title {
            font-weight: 600;
            float: left;
            margin-right: 25px;
        }
        .sub-title-right{
            float: right;
        }
        .letter-spacing {
            letter-spacing: 5px;
        }
        .table {
            width: 100%;
        }
        table {
            border-collapse: collapse;
        }
        table td {
            border: 1px solid rgba(0,0,0,0.8);
            padding: 1px 7px;
        }
        .thead {
            font-weight: 600;
            font-size: 13px;
        }
        .table2 {
            margin-top: 40px;
        }
        .table2 .head1{
            width: 52%;
        }
        .table2 .head2{
            width: 12%;
        }
        .tr {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .row{
            display: block;
        }
    </style>
</head>
<body>
    <div class="content-wrapper">
        <div class="content">
            <div class="card">
                <div class="card-body">
                    @if($company->company_logo != '')
                        <p><img src="{{ public_path(get_logo()) }}" alt="LOGO" height="100px" style="margin-left: -40px;"></p>
                    @endif
                    <div class="row">
                        <div class="company_info">
                            <div class="company_name">
                                {{ $company->company_name }}
                            </div>
                            <div class="company_address">
                                {{ $company->address }}
                            </div>
                        </div>
                    </div>

                    <div class="employee_info">
                        <div>{{ $employee['gender'] = 'male' ? 'Mr.' : 'Ms.' }}</div>
                        <div>{{ ucwords($employee['first_name'] . " " . $employee['middle_name'] . " " . $employee['last_name']) }}</div>
                        <div class="employee_address">{{ $employee['address'] }}</div>
                        <div class="employee_address">{{ $employee['zipcode'] . ' ' . $employee['city'] }}</div>
                        <div class="">{{$employee['country_name']}}</div> <!-- this should have been dynamic -->
                    </div>


                    <div class="title">
                        <div class="row">
                            <div class="sub-title letter-spacing">PAYROLL</div>
                            <div class="sub-title">{{$payroll['description']}}</div>
                        </div>
                        <div style="clear:both;"></div>
                    </div>

                    <div class="table1">
                        <table class="table">
                            <tr>
                                <td class="thead" style="width:50%">Billing-Number</td>
                                <td class="thead" style="width:50%">Billing-Period</td>
                            </tr>
                            <tr>
                                <td>{{ $payroll['billing_number'] }}</td>
                                <td>
                                    @if($payroll['period_from'] != '' && $payroll['period_to'] != '')
                                        {{ date(get_date_format(), strtotime($payroll['period_from'])) }} 
                                        to 
                                        {{ date(get_date_format(), strtotime($payroll['period_to'])) }} 
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>





                    <div class="table2">
                        <table class="table">
                            <tr>
                                <td class="thead head1">Description</td>
                                <td class="thead head2" style="text-align:center">Quantity</td>
                                <td class="thead head2" style="text-align:right">Base</td>
                                <td class="thead head2" style="text-align:right">PHP</td>
                                <td class="thead head2" style="text-align:center">Total PHP</td>
                            </tr>

                            @if($payroll['basic_pay'] > 0)
                                <tr>
                                    <td>Basic Pay</td>
                                    <td class="tr">1</td>
                                    <td class="tr">{{number_format($payroll['basic_pay'], 2)}}</td>
                                    <td class="tr">{{number_format($payroll['basic_pay'], 2)}}</td>
                                    <td class="tr"></td>
                                </tr>
                            @endif
                            

                            @if($payroll['food_allowance'] > 0)
                            <tr>
                                <td>Food Allowance</td>
                                <td class="tr">1</td>
                                <td class="tr">{{number_format($payroll['food_allowance'], 2)}}</td>
                                <td class="tr">{{number_format($payroll['food_allowance'], 2)}}</td>
                                <td class="tr"></td>
                            </tr>
                            @endif

                            @if($payroll['transportation_allowance'] > 0)
                            <tr>
                                <td>Transportation Allowance</td>
                                <td class="tr">1</td>
                                <td class="tr">{{number_format($payroll['transportation_allowance'], 2)}}</td>
                                <td class="tr">{{number_format($payroll['transportation_allowance'], 2)}}</td>
                                <td class="tr"></td>
                            </tr>
                            @endif

                            @if($payroll['personal_allowance'] > 0)
                            <tr>
                                <td>Personal Allowance</td>
                                <td class="tr">1</td>
                                <td class="tr">{{number_format($payroll['personal_allowance'], 2)}}</td>
                                <td class="tr">{{number_format($payroll['personal_allowance'], 2)}}</td>
                                <td class="tr"></td>
                            </tr>
                            @endif

                            @if($payroll['regular_holiday'] > 0)
                            <tr>
                                <td>Regular Holiday</td>
                                <td class="tr">1</td>
                                <td class="tr">{{number_format($payroll['regular_holiday'], 2)}}</td>
                                <td class="tr">{{number_format($payroll['regular_holiday'], 2)}}</td>
                                <td class="tr"></td>
                            </tr>
                            @endif

                            @if($payroll['special_holiday'] > 0)
                            <tr>
                                <td>Special Holiday</td>
                                <td class="tr">1</td>
                                <td class="tr">{{number_format($payroll['special_holiday'], 2)}}</td>
                                <td class="tr">{{number_format($payroll['special_holiday'], 2)}}</td>
                                <td class="tr"></td>
                            </tr>
                            @endif

                            @if($payroll['restday'] > 0)
                            <tr>
                                <td>Restday</td>
                                <td class="tr">1</td>
                                <td class="tr">{{number_format($payroll['restday'], 2)}}</td>
                                <td class="tr">{{number_format($payroll['restday'], 2)}}</td>
                                <td class="tr"></td>
                            </tr>
                            @endif

                            @if($payroll['ot_regular_holiday'] > 0)
                            <tr>
                                <td>Overtime Regular Holiday</td>
                                <td class="tr">1</td>
                                <td class="tr">{{number_format($payroll['ot_regular_holiday'], 2)}}</td>
                                <td class="tr">{{number_format($payroll['ot_regular_holiday'], 2)}}</td>
                                <td class="tr"></td>
                            </tr>
                            @endif

                            @if($payroll['ot_special_holiday'] > 0)
                            <tr>
                                <td>Overtime Special Holiday</td>
                                <td class="tr">1</td>
                                <td class="tr">{{number_format($payroll['ot_special_holiday'], 2)}}</td>
                                <td class="tr">{{number_format($payroll['ot_special_holiday'], 2)}}</td>
                                <td class="tr"></td>
                            </tr>
                            @endif

                            @if($payroll['ot_restday'] > 0)
                            <tr>
                                <td>Overtime Restday</td>
                                <td class="tr">1</td>
                                <td class="tr">{{number_format($payroll['ot_restday'], 2)}}</td>
                                <td class="tr">{{number_format($payroll['ot_restday'], 2)}}</td>
                                <td class="tr"></td>
                            </tr>
                            @endif

                            @if($payroll['gross_pay'] > 0)
                            <tr>
                                <td><b>Total Gross</b></td>
                                <td class="tr"></td>
                                <td class="tr"></td>
                                <td class="tr"></td>
                                <td class="tr">{{number_format($payroll['gross_pay'], 2)}}</td>
                            </tr>
                            @endif

                            @if($payroll['sss'] > 0)
                            <tr>
                                <td>SSS</td>
                                <td class="tr"></td>
                                <td class="tr"></td>
                                <td class="tr">-{{ number_format($payroll['sss'], 2) }}</td>
                                <td class="tr"></td>
                            </tr>
                            @endif

                            @if($payroll['philhealth'] > 0)
                            <tr>
                                <td>Philhealth</td>
                                <td class="tr"></td>
                                <td class="tr"></td>
                                <td class="tr">-{{ number_format($payroll['philhealth'], 2) }}</td>
                                <td class="tr"></td>
                            </tr>
                            @endif

                            @if($payroll['pagibig'] > 0)
                            <tr>
                                <td>Pagibig</td>
                                <td class="tr"></td>
                                <td class="tr"></td>
                                <td class="tr">-{{ number_format($payroll['pagibig'], 2) }}</td>
                                <td class="tr"></td>
                            </tr>
                            @endif

                            @if($payroll['hmo'] != 0)
                            <tr>
                                <td>HMO</td>
                                <td class="tr"></td>
                                <td class="tr"></td>
                                <td class="tr">-{{ number_format($payroll['hmo'], 2) }}</td>
                                <td class="tr"></td>
                            </tr>
                            @endif

                            @if($payroll['witholding_tax'] > 0)
                            <tr>
                                <td>Witholding Tax</td>
                                <td class="tr"></td>
                                <td class="tr"></td>
                                <td class="tr">-{{ number_format($payroll['witholding_tax'], 2) }}</td>
                                <td class="tr"></td>
                            </tr>
                            @endif

                            @if($payroll['total_deductions'] > 0)
                            <tr>
                                <td><b>Total Deduction</b></td>
                                <td class="tr"></td>
                                <td class="tr"></td> 
                                <td class="tr"></td>
                                <td class="tr">-{{ number_format($payroll['total_deductions'], 2) }}</td>
                            </tr>
                            @endif
                            
                            @if($payroll['total_netpay'] > 0)
                            <tr>
                                <td class="thead">Net Pay</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td class="thead tr">
                                {{ number_format($payroll['total_netpay'], 2) }}
                                </td>
                            </tr>
                            @endif
                            
                        </table>
                    </div>

                    <br>
                    <p>
                    The Salary will sent at {{ date(get_date_format(), strtotime($payroll['payroll_date'])) }}: 
                    @if($employee['bank_name'] != '')
                        {{ $employee['bank_name'] }} 
                        @if($employee['account_number'] != '')
                            , Account No.: {{ $employee['account_number'] }}
                        @endif
                    @else
                        No bank information available.
                    @endif
                    
                    </p>
                    
                </div>
            </div>
        </div>
    </div>
</body>
</html>
