@extends('layouts.master')
@section('title', 'Search Payroll')
@section('customcss')
<style type="text/css">
    .mdi {
        line-height:7px;
        vertical-align: middle;
    }
</style>
@endsection
@section('content')
<div class="content-wrapper">
@include('includes.messages')
  <div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-header">Search Payroll</div>
        <div class="card-body">
            @if((check_permission(Auth::user()->Employee->department_id,"Search Payroll","full")) || (check_permission(Auth::user()->Employee->department_id,"Search Payroll","ADD")))
            <div class="row">
                <div class="col-md-12">
                    <!-- <a href="{{ url('/payrollledger') }}" class="btn btn-primary"><i class="mdi mdi-plus"></i>&nbsp;Add New Payroll</a> -->
                    <a href="{{ route('payrollledger.create') }}" class="btn btn-primary"><i class="mdi mdi-plus"></i>&nbsp;Generate Payroll</a>
                    <a href="{{ route('payroll.index') }}" class="btn btn-primary"><i class="mdi mdi-plus"></i>&nbsp;Bulk Payroll</a>
                </div>
            </div>
			@endif

            <br>

            {{-- <form id="payroll_search_form" action="{{ route('payroll.search_filter') }}" method="GET">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <input id="billing_number" class="form-control form-control-sm" type="text" name="billing_number" placeholder="Billing Number">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <select class="form-control form-control-sm" name="employee_id" id="employee_id">
                                <option value="">-Select Employee-</option>
                                @foreach($employees as $r)
                                    <option value="{{ $r->id }}">{{ ucwords($r->first_name) }} {{ ucwords($r->last_name) }}</option>
                                @endforeach
                            </select>    
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <select class="form-control form-control-sm" name="department_id" id="department_id">
                                <option value="">-Select Department-</option>
                                @foreach($departments as $r)
                                    <option value="{{ $r->id }}">{{ ucfirst($r->name) }}</option>
                                @endforeach
                            </select>    
                        </div>
                    </div>
                    <div class="col-md-6">
                        <input type="text" readonly class="form-control form-control-sm" name="date_range_pick" id="date_range_pick" placeholder="Date From - To">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 text-right">
                        <div class="form-group">
                            <button type="submit" id="search_payroll" name="search-payroll-btn" class="btn btn-primary btn-icon-text btn-sm float-right">
                                <i class="mdi mdi-account-search"></i> Search Payroll
                            </button>
                        </div> 
                    </div>
                </div>																						
            </form>
            <div id="search_result_container">

            </div> --}}            
		@if((check_permission(Auth::user()->Employee->department_id,"Search Payroll","full")) || (check_permission(Auth::user()->Employee->department_id,"Search Payroll","View")))
            <form id="payroll_search_form" action="{{ route('payroll.search_filter') }}" method="GET">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="">Payroll Number</label>
                            <input type="text" class="form-control form-control-sm" name="payroll_number" id="payroll_number" placeholder="Payroll Number">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="">Department </label>
                            <select class="form-control form-control-sm" name="department_id" id="department_id">
                                <option value="">Select Department</option>
                                @foreach($departments as $r)
                                    <option value="{{ $r->id }}">{{ ucfirst($r->name) }}</option>
                                @endforeach
                            </select>    
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="">Employee </label>
                            <select class="form-control form-control-sm" name="employee_id" id="employee_id">
                                <option value="">Select Employee</option>
                                @foreach($employees as $r)
                                    <option value="{{ $r->id }}">{{ strtoupper($r->last_name) . ', ' . strtoupper($r->first_name) }}</option>
                                @endforeach
                            </select>    
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>From</label>
                            <input class="form-control form-control-sm is_datefield" name="from" id="period_from" type="text">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>To</label>
                            <input class="form-control form-control-sm is_datefield" name="to" id="period_to" type="text">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="pull-right">
                            <div class="form-group">
                                <button type="submit" id="search_payroll" name="search-payroll-btn" class="btn btn-primary btn-icon-text">
                                    <i class="mdi mdi-account-search"></i> Search
                                </button>
                            </div> 
                        </div>
                    </div>
                </div>	
            </form>
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
    // $('#date_range_pick[readonly]').css({'background-color':'#FFFFFF'});
    // $('#date_range_pick').daterangepicker({
	// 	showDropdowns: true,
	// 	minYear: 1980,
	// 	maxYear: parseInt(moment().format('YYYY')),
	// 	locale: {
	// 		  cancelLabel: 'Clear'
	// 	},
	// 	"autoUpdateInput": false,
	// 	"autoApply":false,
	// 	maxDate:moment(), 
    // });

    // $.ajaxSetup({
    //   headers: {
    //       'X-CSRF-TOKEN': '{{ csrf_token() }}'
    //   }
    // });

    // $('#date_range_pick').on('apply.daterangepicker', function(ev, picker) {
	// 	 $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
	// });

    // $("#payroll_search_form").on('submit', function (e){
    //     e.preventDefault();
    //     var f = $(this);
    //     load_search_container();
    //     f.hide();
    //     $.ajax({
    //         url: f.attr('action'),
    //         type: f.attr('method'),
    //         data: f.serialize(),
    //         success: function (response){
    //             $("#search_result_container").html(response);

    //             $(".view-payroll-billing").on('click', function (){
    //                 var ff = $(this);
    //                 var payroll_id = ff.data('id');
    //                 load_search_container();
    //                 $.ajax({
    //                     url: "{{ route('payroll.search_view_billing_number', ['payroll_id' => "+payroll_id+"]) }}",
    //                     type: "GET",
    //                     data: {payroll_id:payroll_id},
    //                     success:function (response){
    //                         $("#search_result_container").html(response);
    //                     }
    //                 });
    //             });
    //         }
    //     });
    // });

    // function load_search_container(){
    //     $("#search_result_container").html('<br> <h3 class="text-muted text-center"><i class="mdi mdi-refresh mdi-spin"></i> Loading...</h3>');
    // }



    $(function(){
        // var asiaTime = new Date().toLocaleString("en-US", {timeZone: "Asia/Manila"});
        // var date = new Date(asiaTime);
        // $('#period_from')[0].valueAsNumber = new Date(date.getFullYear(), date.getMonth(), 2); // 1
        // $('#period_to')[0].valueAsNumber = new Date(date.getFullYear(), date.getMonth() + 1, 1); // 0
        
        // var dtToday = new Date();
        // var year = dtToday.getFullYear();
        // var month = dtToday.getMonth() + 1;
        // var day = dtToday.getDate(); 
        // if(month < 10)
        //     month = '0' + month.toString();
        // if(day < 10)
        //     day = '0' + day.toString();
        // var maxDate = year + '-' + month + '-' + day;    
        // $('#period_from').attr('max', maxDate);
        // $('#period_to').attr('max', maxDate);
    });
   
 });
 

</script>
@endsection

