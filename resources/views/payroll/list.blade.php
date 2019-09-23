@extends('layouts.master')
@section('title', 'Search Payroll')
@section('customcss')
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<style type="text/css">
    .mdi {
        line-height:7px;
        vertical-align: middle;
    }
</style>
@endsection
@section('content')
<div class="content-wrapper">
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-header">Payroll List</div>
                <div class="card-body table-responsive">
                    <div class="row">
                        <div class="col-md-8">
                            <a href="{{ url('/payrollledger/create') }}" class="btn btn-primary"><i class="mdi mdi-plus"></i>&nbsp;Generate Payroll</a>
                            <a href="{{ url('/bulk-payroll') }}" class="btn btn-primary"><i class="mdi mdi-plus"></i>&nbsp;Bulk Payroll</a>
                        </div>
                        
                        @if(count($payrolls) > 1)
                        <div class="col-md-4">
                            <div class="pull-right">
                                <form action="?" method="GET" class="form-inline">
                                    @csrf 
                                    @if(isset($employee_id))
                                    <input type="hidden" name="employee_id" value="{{ $employee_id }}">
                                    @endif
                                    @if(isset($payroll_number))
                                    <input type="hidden" name="payroll_number" value="{{ $payroll_number }}">
                                    @endif
                                    @if(isset($department_id))
                                    <input type="hidden" name="department_id" value="{{ $department_id }}">
                                    @endif
                                    @if(isset($period_from))
                                    <input type="hidden" name="from" value="{{ date('Y-m-d', strtotime($period_from)) }}">
                                    @endif
                                    @if(isset($period_to))
                                    <input type="hidden" name="to" value="{{ date('Y-m-d', strtotime($period_to)) }}">
                                    @endif
                                    @if(isset($bill_number))
                                    <input type="hidden" name="bill_number" value="{{ $bill_number }}">
                                    @endif
                                    @if($payrolls->currentPage() !== NULL)
                                    <input type="hidden" name="page" value="{{ $payrolls->currentPage() }}">
                                    @endif
                                    <label class="tbl_length_lbl">Show </label>
                                    <select class="form-control form-control-sm tbl_length_select" name="perpage" onchange="this.form.submit()">
                                        <option value="100" {{app('request')->input('perpage') == 100 ? 'selected':''}}>100</option>
                                        <option value="200" {{app('request')->input('perpage') == 200 ? 'selected':''}}>200</option>
                                        <option value="500" {{app('request')->input('perpage') == 500 ? 'selected':''}}>500</option>
                                        <option value="1000" {{app('request')->input('perpage') == 1000 ? 'selected':''}}>1000</option>
                                    </select>
                                    <label class="tbl_length_lbl">entries</label>
                                </form>
                            </div>
                        </div>
                        @endif
                    </div>

                    <br>

                    <table class="table table-bordered table-striped" id="data_table">
                        <thead>
                            <tr>
                                <th class="text-center">Employee</th>
                                <th class="text-center">Batch #</th>
                                <th class="text-center">Payroll #</th>
                                <th class="text-center">From</th>
                                <th class="text-center">To</th>
                                <th class="text-center">Payroll Date</th>
                                <th class="text-center">Paid</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php($fa = 0)
                            @php($ta = 0)
                            @php($ss = 0)
                            @php($ph = 0)
                            @php($pi = 0)
                            @php($np = 0)
                            @php($prev = '')

                            @if($result)
                                @foreach($payrolls as $p => $payroll)
                                    <tr>
                                        <td>
                                            {{ucwords($payroll->first_name.' '.$payroll->last_name)}}
                                        </td>
                                        <td class="text-center">{{ $payroll->billing_number }}</td>
                                        <td class="text-center"><a href="/payroll/preview/{{ $payroll->id }}" target="_self">{{ $payroll->payroll_number }}</a></td> <!-- should be payroll_number -->
                                        <td class="text-center">{{ date(get_date_format(), strtotime($payroll->period_from)) }}</td>
                                        <td class="text-center">{{ date(get_date_format(), strtotime($payroll->period_to)) }}</td>
                                        <td class="text-center">{{ date(get_date_format(), strtotime($payroll->payroll_date)) }}</td>
                                        <td class="text-center">
                                            {{ number_format($payroll->netpay, 2) }}
                                            @php($np += $payroll->netpay)
                                        </td>
                                    </tr>
                                @endforeach 
                           
                            @endif
                            
                        </tbody>
                       
                    </table>        

                    <br>
                    @if($result)
                        <div class="row">
                            <div class="col-md-6">
                                Showing {{ $payrolls->firstItem() }} to {{ $payrolls->lastItem() }} of {{ $payrolls->total() }} entries
                            </div>
                            <div class="col-md-6">
                                <div class="pull-right">
                                    {{ $payrolls->appends(request()->input())->links() }}
                                </div>
                            </div>
                        </div>
                    @endif
                    

                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@section('customjs')
<script type="text/javascript">

var SITEURL = '{{URL::to('')}}';
 $(document).ready( function () {
	  $('#data_table').DataTable({
	  	"bPaginate": false,
		"bLengthChange": false,
		"bFilter": false,
		"bInfo": false,
	  });
 });
</script>	
@endsection