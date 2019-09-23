@extends('layouts.master')
@section('title', 'Tardiness Report')
@section('content')
<link rel="stylesheet" type="text/css" href="{{ asset('plugins/timepicker/jquery.timepicker.min.css') }}">

<div class="content-wrapper">
    <div class="content">
        @include('includes.messages')
        <div class="card">

            <div class="card-header">
				<div class="text-md-left float-md-left text-sm-left float-sm-left">Tardiness Report </div>
				
			</div>

            <div class="card-body">
				<div class="row" id="tardiness-search-form">
					<div class="col-md-4 col-sm-6 col-xs-6">
					  <div class="form-group">
						<select style="position: relative; z-index: 999;" class="form-control form-control-sm" name="employee_id" id="search_employee_id">
							<option value="">Search Employee</option>
							@foreach($employees as $employee)
								<option value="{{ $employee->id }}">{{ ucwords($employee->first_name) }} {{ ucwords($employee->last_name) }}</option>
							@endforeach
						</select>
					  </div>
					</div>
					<div class="col-md-4 col-sm-6 col-xs-6">
					  <!-- <input type="text" class="form-control form-control-sm" placeholder="Search Employee ID" name="employee_id" id="search_employee_id"> -->
						<div class="form-group">	
					  		<input style="position: relative; z-index: 999;" id="date_range_picker" readonly type="text" placeholder="Choose Date Range" data-date-format="DD MMMM YYYY" class="form-control form-control-sm" name="date_range_picker"> 
						</div>	
					</div>
					<div class="col-md-4 col-sm-6 col-xs-6">
					  <!-- <input type="text" class="form-control form-control-sm" placeholder="Search Employee ID" name="employee_id" id="search_employee_id"> -->
						<div class="form-group">	
					  		<input style="position: relative; z-index: 999;" id="time_in"  type="text" placeholder="Choose Time In"  class="form-control form-control-sm timepicker" name="time_in"> 
						</div>	
					</div>
					<div class="col-md-4 col-sm-6 col-xs-6">
					  <!-- <input type="text" class="form-control form-control-sm" placeholder="Search Employee ID" name="employee_id" id="search_employee_id"> -->
						<div class="form-group">	
					  		<input style="position: relative; z-index: 999;" id="time_out"  type="text" placeholder="Choose Time Out"  class="form-control form-control-sm timepicker" name="time_out"> 
						</div>	
					</div>
					<div class="col-md-4 col-sm-6 col-xs-6">
					  <!-- <input type="text" class="form-control form-control-sm" placeholder="Search Employee ID" name="employee_id" id="search_employee_id"> -->
						<div class="form-group">	
					  		<input style="position: relative; z-index: 999;" id="total_hours"  type="text" placeholder="Total Hours (HH:MM:SS)"  class="form-control form-control-sm" name="total_hours"> 
						</div>	
					</div>
					<div class="col-md-12 col-sm-12 col-xs-12">
						<div class="form-group text-right">	
						  <a style="position: relative; z-index: 999;" href="javascript:void(0);" class="btn btn-primary btn-icon-text btn-submit-search btn-sm">
							<i class="mdi mdi-magnify"></i>                                                    
							Search
						  </a>
						</div>	
					</div>
					 	
				</div>	 
				<div class=" table-responsive" id="tardiness_datatables_container" style="display: none;">
                <table class="table" id="data_table_tardiness">
                    <thead>
                        <th>Date&nbsp;</th>
                        <th>Name&nbsp;</th>
                        <th>Time-In&nbsp;</th>
                        <th>Time-Out&nbsp;</th>
                        <th>Total&nbsp;</th>
                    </thead>
                   
                </table>
				</div>	
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
    <div id="ExportModal" class="modal fade text-default" role="dialog">
        <div class="modal-dialog ">
            <!-- Modal content-->
            <form action="" id="exportform" method="post">
                <div class="modal-content">
                    <div class="modal-header bg-default">

                        <h4 class="modal-title text-center">SELECT EXPORT TYPE </h4>
                    </div>
                    <div class="modal-body">
						<select name='export_type' id='export_type' class='form-control form-control-sm'>
							<option value='excel'>Excel</option>
							<option value='pdf'>PDF</option>
						</select>
                        <p class="text-center">
                        <center>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                            <button type="button" name="" class="btn btn-success btn-excel-download" data-dismiss="modal" >Export</button>
                        </center>
                        </p>
                    </div>
                    <div class="modal-footer">

                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection

@section('javascript')

<!-- OPTIONAL SCRIPTS -->
<script src="/dist/plugins/chart.js/Chart.min.js"></script>
<script src="/dist/js/demo.js"></script>
<script src="/dist/js/pages/dashboard3.js"></script>


<!-- DataTables -->
<script src="/dist/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="/dist/plugins/datatables/dataTables.bootstrap4.js"></script>
@endsection
@section('customjs')
<script src="{{asset('plugins/timepicker/jquery.timepicker.min.js')}}"></script>

<script type="text/javascript">
	var export_options = "<select name='export_type' id='export_type' class='form-control form-control-sm'><option value='excel'>Excel</option><option value='pdf'>PDF</option></select>";
$(document).ready(function(){
	
	$('input.timepicker').timepicker({
		step : 1,
		'timeFormat': 'H:i'
	});
	
});	
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
 function load_data_tables(){
	 	$("#tardiness_datatables_container").show();
  $('#data_table_tardiness').DataTable({
    searching: false,
    processing: true,
    serverSide: true,
	responsive: true,
	autoWidth : false, 
    dom   : "<'row'<'col-sm-6 col-xs-6 '<'row xls_export'>><'col-sm-6 col-xs-6 text-right'l>><'row'<'col-sm-12 float-right margin-bottom20'B>><'row'<'col-sm-12'tr>><'row'<'col-sm-5 col-xs-6'i><'col-sm-7 col-xs-6'p>>",
    ajax: {
        url : "{{ route('dtr.tardiness_list') }}",
        type: 'GET',
        data: function (f) {
          f.employee_id = $('#search_employee_id').val();
          f.date_range_picker = $('#date_range_picker').val();
          f.time_in = $('#time_in').val();
          f.time_out = $('#time_out').val();
		  f.total_hours = $("#total_hours").val();	
        }
    },	  
    columns: [
        { data: 'date', sortable:false },
        { data: 'employee_name', sortable:true },
        { data: 'time_in', sortable:false },
        { data: 'time_out', sortable:false },
        { data: 'total', sortable:false }
    ]
	});
	 $('#data_table_tardiness').on( 'error.dt', function ( e, settings, techNote, message ) {
            console.log( 'An error has been reported by DataTables: ', message );
        } ) .DataTable();
        $.fn.dataTable.ext.errMode = 'none';
	 $("#tardiness-search-form").hide();
 }
  /*if(window.matchMedia("(max-width: 992px)").matches){
      $("#tardiness_datatables_container").css("margin-top", "0px");
  }else{
      $("#tardiness_datatables_container").css("margin-top", "-75px");
  }*/

  $('#date_range_picker').on('apply.daterangepicker', function(ev, picker) {
	     var date_format = '{{get_date_format()}}';
		
		date_format = date_format.replace("m","MM");
		date_format = date_format.replace("d","DD");
		date_format = date_format.replace("Y","YYYY");	
		 $(this).val(picker.startDate.format(date_format) + ' - ' + picker.endDate.format(date_format));
	});
  $(".btn-submit-search").on('click', function (){
	  load_data_tables();
    $('#data_table_tardiness').DataTable().draw(true);
	  
	  $("div.xls_export").html('<div class="col-md-6 col-sm-8 col-xs-12"><div class="form-group" ><a style="position: relative; z-index: 999;" href="javascript:void(0);" class="btn btn-success btn-icon-text btn-block"  data-toggle="modal" data-target="#ExportModal"><i class="mdi mdi-arrow-down-bold-circle-outline"></i>Export</a></div></div>');
  });
 $(document).on('click','.btn-excel-download',function(){
    var query = {
        search_employee_id: $('#search_employee_id').val(),
        date_range_picker: $('#date_range_picker').val(),
        time_in: $('#time_in').val(),
        time_out: $('#time_out').val(),
		total_hours: $("#total_hours").val(),
		export_type: $("#export_type").val(),
    }
    var url = "{{URL::to('dtr/excel_tardiness')}}?" + $.param(query)

   window.location = url;
});

</script>
@endsection