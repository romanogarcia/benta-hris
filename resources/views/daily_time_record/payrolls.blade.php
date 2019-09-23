@extends('layouts.master')
@section('title', 'Payroll Register Report')
@section('content')


<link rel="stylesheet" type="text/css" href="{{ asset('plugins/timepicker/jquery.timepicker.min.css') }}">
<!--<link rel="stylesheet" type="text/css" href="{{ asset('plugins/multiselect/css/multiple-select.css') }}">-->
<link rel="stylesheet" type="text/css" href="{{ asset('plugins/multiselect/fSelect.css') }}">
<style>
	div[id^=multiple-select-container-] .dropdown-select{
		z-index : 1000;
	}
	div[id^=multiple-select-container-] .dropdown-select ul.list-group{
		max-height : 300px;
	}
	.fs-wrap {
		width: 100%;
    	height: 2.575rem;
   		font-size: 0.875rem;
    	line-height: 2.0;
	}
	.fs-wrap .fs-label-wrap{
		 border-radius: 0.2rem;
	}
</style>
<div class="content-wrapper">
    <div class="content">
        @include('includes.messages')
        <div class="card">
            <div class="card-header">
            Payroll Register Report

            </div>
            <div class="card-body">
                <div class="row" id="payrolls-search-form">
                    <div class="col-md-4 col-sm-6 col-xs-6">
                        <div class="form-group">
                            <select style="position: relative; z-index: 999;" class="form-control form-control-sm multi_example" name="employee_id" id="search_employee_id" multiple  >
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}">{{ ucwords($employee->first_name) }} {{ ucwords($employee->last_name) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6 col-xs-6">
                        <div class="form-group">
                            <input style="position: relative; z-index: 999;" id="date_range_picker" readonly type="text" placeholder="Choose Date Range" data-date-format="DD MMMM YYYY" class="form-control form-control-sm" name="date_range_picker">
                        </div>
                    </div>
					 <div class="col-md-4 col-sm-6 col-xs-6">
                        <div class="form-group">
                            <select class="form-control form-control-sm" name="department_id" id="department_id">
                                <option value="">-Select Department-</option>
                                @foreach($departments as $r)
                                    <option value="{{ $r->id }}">{{ ucfirst($r->name) }}</option>
                                @endforeach
                            </select>    
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
                <div class="table-responsive" id="table_container" style="display: none;">
                    <table class="table" id="id-data_table">
                        <thead>
                            <th>Employee</th>
                            <th>Batch #</th>
                            <th>Payroll #</th>
                            <th>From</th>
                            <th>To</th>
                            <th>Payroll Date</th>
                            <th>Paid</th>
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
@section('customjs')
<script src="{{asset('plugins/timepicker/jquery.timepicker.min.js')}}"></script>
<!--<script src="{{asset('plugins/multiselect/js/multiple-select.js')}}"></script>-->
<script src="{{asset('plugins/multiselect/fSelect.js')}}"></script>

<script type="text/javascript">
	/*let singleSelect = new MultipleSelect('.multi_example', {
		placeholder: 'Select Employees'
	});*/
	
  $(document).ready(function(){
	 $(".multi_example").fSelect();
	  $('input.timepicker').timepicker({
		step : 1,
		'timeFormat': 'H:i'
	});
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
      $('#date_range_picker').on('apply.daterangepicker', function(ev, picker) {
		  var date_format = '{{get_date_format()}}';
		
		date_format = date_format.replace("m","MM");
		date_format = date_format.replace("d","DD");
		date_format = date_format.replace("Y","YYYY");
          $(this).val(picker.startDate.format(date_format) + ' - ' + picker.endDate.format(date_format));
      });
    $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    });

	 
    //$('#id-data_table').DataTable().draw(true);
		$("div.xls_export").html('<div class="col-md-6 col-sm-8 col-xs-12"><div class="form-group" ><a style="position: relative; z-index: 999;" href="javascript:void(0);" class="btn btn-success btn-icon-text btn-block " data-toggle="modal" data-target="#ExportModal"><i class="mdi mdi-arrow-down-bold-circle-outline"></i>Export</a></div></div>'); 
    $('.btn-submit-search').on('click',function(){
		load_data_tables();
        $('#id-data_table').DataTable().draw(true);
		$("div.xls_export").html('<div class="col-md-6 col-sm-8 col-xs-12"><div class="form-group" ><a style="position: relative; z-index: 999;" href="javascript:void(0);" class="btn btn-success btn-icon-text btn-block " data-toggle="modal" data-target="#ExportModal"><i class="mdi mdi-arrow-down-bold-circle-outline"></i>Export</a></div></div>'); 
    });
   

    $.fn.DataTable.ext.pager.numbers_length = 5;

      $(document).on('click','.btn-excel-download',function(){
          let query = {
              employee_id: $('#search_employee_id').val(),
              date: $('#date_range_picker').val(),
			  department_id:$("#department_id").val(),
			  export_type: $("#export_type").val(),
          };
          let uri = "{{URL::to('dtr/download_payroll_report')}}?" + $.param(query);

          window.location = uri;
      });
  });
	function load_data_tables(){
	 	$("#table_container").show();
   		 $('#id-data_table').DataTable({
        searching: false,
        processing: true,
        serverSide: true,
        responsive: true,
        autoWidth : false,
        language: {
            "paginate": {
              "previous": "<<",
              "next" : ">>"
            }
        },
        dom   : "<'row'<'col-sm-6 col-xs-12 col-md-6 '<'row xls_export'>><'col-sm-6 col-xs-12 col-md-6 text-right'l>><'row'<'col-sm-12'tr>><'row'<'col-sm-5 col-xs-6'i><'col-sm-7 col-xs-6 'p>>",
        ajax: {
            url : "{{ route('dtr.get_payrolls') }}",
            type: 'GET',
            data: function (f) {
                f.employee_id = $('#search_employee_id').val();
                f.date = $('#date_range_picker').val();
				f.department_id = $("#department_id").val();
            }
        },	  
        columns: [
            { data: 'employee', sortable:true },
            { data: 'batch', sortable:true },
            { data: 'payroll', sortable:true },
            { data: 'from', sortable:true },
            { data: 'to', sortable:true },
            { data: 'payroll_date', sortable:true },
            { data: 'paid', sortable:true },
        ],
    });
		 $("#payrolls-search-form").hide();
	 }	 	  
</script>


@endsection