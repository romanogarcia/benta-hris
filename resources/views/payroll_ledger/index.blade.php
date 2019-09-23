@extends('layouts.master')
@section('title', 'Generate Payroll')
@section('content')
<div class="content-wrapper">
    <div class="content">
    @include('includes.messages')
        <div class="card">
            <div class="card-header">
                Generate Payroll
            </div>
            <div class="card-body">
                <!-- <form method="post" action="{{ url('/payrollledger/generate') }}" id="generateForm"> -->
                <form method="get" action="{{ route('payrollledger.create') }}" id="generateForm">
                <!-- <form method="post" action="#" id="generatePayroll"> -->
                @csrf
                    <div class="row">
                        <div class="col-md-3 col-sm-4 col-xs-12">
                            <label>EMPLOYEE</label>
                            <select class="form-control form-control-sm" name="employee_id" id="employee_id" required>
                                <option value="">Employee</option>
                                @foreach($employees as $key => $employee)
                                    <option value="{{ $employee->user_id }}" >{{ strtoupper($employee->last_name . ', ' . $employee->first_name) }}</option>
                                @endforeach
                            </select>	
                        </div>
                        <div class="col-md-3 col-sm-4 col-xs-12">
                            <label>DEPARTMENT</label>
                            <select class="form-control form-control-sm" name="department_id" id="department_id">
                                <option value="">Department</option>
                                @foreach($departments as $key => $department)
                                    <option value="{{ $department->id }}">{{ strtoupper($department->name) }}</option>
                                @endforeach
                            </select>
                        </div>	
                        <div class="col-md-3 col-sm-4 col-xs-12">
                            <label for="payroll_date">PAYROLL DATE</label>
                            <input class="form-control is_datefield" name="payroll_date" id="payroll_date" type="text" required>
                        </div>
                    </div>	

                    <br>

                    <div class="row">
                        <div class="col-lg">
                            <label for="">PAYROLL PERIOD</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3 col-sm-4 col-xs-12">
                            <div class="form-group">
                                <label>FROM</label>
                                <input class="form-control is_datefield" name="from" id="period_from" type="text" required>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-4 col-xs-12">
                            <div class="form-group">
                                <label>TO</label>
                                <input class="form-control is_datefield" name="to" id="period_to" type="text" required>
                            </div>
                        </div>
                        <!-- <div class="col-lg pt-2">
                            <button type="submit" class="btn btn-primary mt-4">Generate</button>
                        </div> -->
                    </div>

                    

                    <div class="row">
                        <div class="col-md-12 col-lg-12">
                            <div class="pull-right">
                                <button type="submit" id="frmSubmit" class="btn btn-primary mt-4">Generate</button>
                            </div>
                        </div>
                    </div>
                
                </form>
                
            </div>
        </div>

        <br>

        {{-- <div class="card">
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th>Billing Number</th>
                        <th>Employee ID</th>
                        <th>Name</th>
                        <th>Period</th>
                        <th class="text-center">Action</th>
                    </tr>
                    @foreach($payrolls as $key => $payroll)
                        <tr>
                            <td>{{ $payroll->billing_number }}</td>
                            <td>{{ $payroll->employee_id }}</td>
                            <td>{{ $payroll->name }}</td>
                            <td>{{ $payroll->period }}</td>
                            <td class="text-center">
                                <button type="button" class="btn btn-outline-secondary btn-rounded btn-icon btn-sm" onclick="window.location.href = '{{ route('payrollledger.show', $payroll->id) }}';"><i class="mdi mdi-eye"></i></button>
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div> --}}
    </div>
</div>
@endsection


@section('customjs')
<script>
	var date_format = '{{get_date_format()}}';
    var asiaTime = new Date().toLocaleString("en-US", {timeZone: "Asia/Manila"});
    var date = new Date(asiaTime);
  
	$('#payroll_date').datepicker('update', new Date());
	$('#period_from').datepicker('update', new Date(date.getFullYear(), date.getMonth(), 1));
	$('#period_to').datepicker('update', new Date());

	$("#period_from").datepicker().datepicker("setEndDate", new Date());
	$("#period_to").datepicker().datepicker("setEndDate", new Date());
	
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $(document).ready (function(){
        $('select[name="employee_id"]').on('change', function(){
            var employee_id = $(this).val();
            if(employee_id) {
                $.ajax({
                    url: "{{ url('/payrollledger/getDepartment')}}/"+employee_id,
                    type:"GET",
                    dataType:"json",
                    beforeSend: function(){
                        // 
                    },
                    success:function(data) { 
                        console.log(data);
                        $('select[name="department_id"]')[0].selectedIndex = data['id'];
                    },
                    fail: function(xhr, textStatus, errorThrown){
                        console.log("request failed")
                    },
                    complete: function(){
                        console.log("request success")
                    }
                });
            } 
        });


        // LISTEN TO GENERATE BUTTON
        // $("#frmSubmit").click(function(){        
        //     $("#generatePayroll").submit(); 
        // });
    });
</script>
@endsection