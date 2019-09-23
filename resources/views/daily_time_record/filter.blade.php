@extends('layouts.master')
@section('title', 'Filter')
@section('content')
<div class="content-wrapper">
  <div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
      <div class="card">
      <div class="card-header">Search</div>
        <div class="card-body">
          @if ($message = Session::get('success')) 
          <div class="alert alert-success" role="alert">
            <i class="mdi mdi-alert-circle"></i>
            <strong>{{ $message }}</strong>
          </div>
          @endif
          
			<div class="row">
        <div class="col-md-3 col-sm-6 col-xs-6">
          <div class="form-group">
            <select style="position: relative; z-index: 999;" class="form-control form-control-sm" name="employee_id" id="search_employee_id">
                <option value="">-Search Employee-</option>
                @foreach($employees as $employee)
                    <option value="{{ $employee->id }}">{{ ucwords($employee->first_name) }} {{ ucwords($employee->last_name) }}</option>
                @endforeach
            </select>
          </div>
        </div>
        <div class="col-md-3 col-sm-6 col-xs-6">
          <!-- <input type="text" class="form-control form-control-sm" placeholder="Search Employee ID" name="employee_id" id="search_employee_id"> -->
          <input style="position: relative; z-index: 999;" id="date_range_picker" readonly type="text" placeholder="-Choose Date Range-" data-date-format="DD MMMM YYYY" class="form-control form-control-sm" name="date_range_picker">  
        </div>
        <div class="col-md-2 col-sm-6 col-xs-12">
          <a style="position: relative; z-index: 999;" href="javascript:void(0);" class="btn btn-primary btn-icon-text btn-block btn-submit-search btn-sm">
            <i class="mdi mdi-magnify"></i>                                                    
            Search
          </a>
        </div>
		 <div class="col-md-2 col-sm-6 col-xs-12">
          <a style="position: relative; z-index: 999;" href="javascript:void(0);" class="btn btn-success btn-icon-text btn-block btn-sm btn-excel-download">
            <i class="mdi mdi-arrow-down-bold-circle-outline"></i>                                                    
            Export
          </a>
        </div>		
      </div>																					
																										
      <div class="table-responsive" id="laravel_datatables_container">			  
			 <table class="table" id="laravel_datatable">
			   <thead>
				    <tr>
              <th>Date</th>
              <th>Employee No.</th>
              <th>Name</th>
              <th>Time-In</th>
              <th>Time-Out</th>
              <th>Total</th>
				    </tr>
			   </thead>
			</table> 
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection

@section('customjs')
<script type="text/javascript">
  var SITEURL = '{{URL::to('')}}';
  $('#date_range_picker[readonly]').css({'background-color':'#FFFFFF'});
  $('#date_range_picker').daterangepicker({
		showDropdowns: true,
		minYear: 1980,
		maxYear: parseInt(moment().format('YYYY')),
		locale: {
			  cancelLabel: 'Clear'
		},
		"autoUpdateInput": false,
		"autoApply":false,
		maxDate:moment(), 
  });

  $('#laravel_datatable').DataTable({
    searching: false,
    processing: true,
    serverSide: true,
	responsive: true,
	autoWidth : false,   
    dom   : "<'row'<'col-sm-12 float-right margin-bottom20'B>><'row'<'col-sm-12 col-xs-12 text-right'l>><'row'<'col-sm-12'tr>><'row'<'col-sm-5 col-xs-6'i><'col-sm-7 col-xs-6'p>>",
    ajax: {
        url : "{{ route('dtr.filter_list') }}",
        type: 'GET',
        data: function (f) {
          f.employee_id = $('#search_employee_id').val();
          f.date_range_picker = $('#date_range_picker').val();
        }
    },	  
    columns: [
        { data: 'date', sortable:false },
        { data: 'employee_id', sortable:true },
        { data: 'employee_name', sortable:true },
        { data: 'time_in', sortable:false },
        { data: 'time_out', sortable:false },
        { data: 'total', sortable:false }
    ]
	});

  if(window.matchMedia("(max-width: 992px)").matches){
      $("#laravel_datatables_container").css("margin-top", "0px");
  }else{
      $("#laravel_datatables_container").css("margin-top", "-75px");
  }

  $('#date_range_picker').on('apply.daterangepicker', function(ev, picker) {
		 $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
	});

  $(".btn-submit-search").on('click', function (){
    $('#laravel_datatable').DataTable().draw(true);
  });
 $('.btn-excel-download').on('click',function(){
    var query = {
        search_employee_id: $('#search_employee_id').val(),
        date_range_picker: $('#date_range_picker').val(),
    }
    var url = "{{URL::to('dtr/download_filter_export')}}?" + $.param(query)

   window.location = url;
});

</script>
@endsection