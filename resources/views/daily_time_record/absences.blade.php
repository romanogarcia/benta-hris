@extends('layouts.master')
@section('title', 'Absenses Report')
@section('content')
<div class="content-wrapper">
    <div class="content">
        @include('includes.messages')
        <div class="card">
            <div class="card-header">Absenses Report</div>
            <div class="card-body ">
                <div class="row" id="absence-search-form">
                            <div class="col-md-4 col-sm-6 col-xs-6">
                                <div class="form-group">
                                    <select style="position: relative;" class="form-control form-control-sm" name="employee_id" id="search_employee_id">
                                        <option value="">Search Employee</option>
                                        @foreach($employees as $employee)
                                            <option value="{{ $employee->id }}">{{ ucwords($employee->first_name) }} {{ ucwords($employee->last_name) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4 col-sm-6 col-xs-6">
                                <div class="form-group">	
                                    <input style="position: relative; " id="date_range_picker" readonly type="text" placeholder="Choose Date Range" data-date-format="DD MMMM YYYY" class="form-control form-control-sm" name="date_range_picker">  
                                </div>	 
                            </div>

                            <div class="col-md-4 col-sm-6 col-xs-6">
                                <div class="form-group">
                                    <input style="position: relative;" id="date" type="text" class="form-control form-control-sm is_datefield" name="date" placeholder="Date filed" data-placeholder="available">
                                </div>
                            </div>
                   			<div class="col-md-4 col-sm-6 col-xs-6">
								<div class="form-group">
								 <select class="form-control form-control-sm" name="type" id="type">
									<option value="">Type</option> 
									@foreach($leave_data as $row)
									  <option value="{{ $row->name }}">{{ $row->name }}</option>
									@endforeach
								  </select>
								</div>	
							</div>	
							 <div class="col-md-4 col-sm-6 col-xs-6">
                                <div class="form-group">
                                    <select style="position: relative;" class="form-control form-control-sm" name="approveby" id="approveby">
                                        <option value="">Approved by</option>
                                        @foreach($employees as $employee)
                                            <option value="{{ ucwords($employee->first_name) }} {{ ucwords($employee->last_name) }}">{{ ucwords($employee->first_name) }} {{ ucwords($employee->last_name) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
							<div class="col-md-4 col-sm-6 col-xs-6">
                                <div class="form-group">
                                    <input style="position: relative; " id="approved_date" type="text" class="form-control form-control-sm is_datefield" name="approved_date" placeholder="Approved Date" data-placeholder="available">
                                </div>
                            </div>
                        	<div class="col-md-12 col-sm-12 col-xs-12">
								<div class="form-group text-right">	
									<a style="position: relative;" href="javascript:void(0);" class="btn btn-primary btn-icon-text  btn-submit-search btn-sm">
										<i class="mdi mdi-magnify"></i>                                                    
										Search
									</a>
								</div>	
							</div>	
                            <!--<div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="form-group">	 
                                    <a style="position: relative; z-index: 999;" href="javascript:void(0);" class="btn btn-success btn-icon-text btn-block btn-sm btn-excel-download">
                                        <i class="mdi mdi-arrow-down-bold-circle-outline"></i>                                                    
                                        Export
                                    </a>
                                </div>	  
                            </div>-->
                        
                </div>
                
				<div class="table-responsive" id="table_container" style="display: none;" >
                <table class="table" id="data_table_absence">
                    <thead>
                        <tr>
                            <th> Name&nbsp; </th>
                            <th> Date Filed&nbsp; </th>
                            <th> Date Start&nbsp; </th>
                            <th> Date End&nbsp; </th>
                            <th> Type&nbsp; </th>
                            <th> Approved By&nbsp;</th>
                            <th> Approved Date&nbsp;</th>
                        </tr>
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
function load_data_tables(){
	$("#table_container").show();
  $('#data_table_absence').DataTable({
    searching: false,
    processing: true,
    serverSide: true,
	responsive: true,
	autoWidth : false, 
    dom   : "<'row'<'col-sm-12 float-right margin-bottom20'B>><'row'<'col-sm-6 col-xs-12 col-md-6 '<'row xls_export'>><'col-sm-6 col-xs-12 col-md-6 text-right'l>><'row'<'col-sm-12'tr>><'row'<'col-sm-5 col-xs-6'i><'col-sm-7 col-xs-6 justify-content-sm-center'p>>",
    ajax: {
        url : "{{ route('dtr.absence_list') }}",
        type: 'GET',
        data: function (f) {
          f.search_employee_id = $('#search_employee_id').val();
          f.date_range_picker = $('#date_range_picker').val();
          f.approveby = $('#approveby').val();
          f.datefiled = $('#date').val();
		  f.type = $("#type").val();	
		  f.approved_date = $("#approved_date").val();	
        }
    },	  
    columns: [
        { data: 'name', sortable:true },
        { data: 'date_filed', sortable:true },
        { data: 'date_start', sortable:true },
        { data: 'date_end', sortable:true },
        { data: 'type', sortable:false },
        { data: 'approved_by', sortable:false },
        { data: 'approved_date', sortable:false },
    ]
	});
	$("#absence-search-form").hide();
}
  

  $('#date_range_picker').on('apply.daterangepicker', function(ev, picker) {
	  	var date_format = '{{get_date_format()}}';
		
		date_format = date_format.replace("m","MM");
		date_format = date_format.replace("d","DD");
		date_format = date_format.replace("Y","YYYY");	
		 $(this).val(picker.startDate.format(date_format) + ' - ' + picker.endDate.format(date_format));
	});

  $(".btn-submit-search").on('click', function (){
	  load_data_tables();
    $('#data_table_absence').DataTable().draw(true);
	   $("div.xls_export").html('<div class="col-md-6 col-sm-6 col-xs-12"><div class="form-group"><a style="position: relative; z-index: 999;" href="javascript:void(0);" class="btn btn-success btn-icon-text btn-block btn-sm"  data-toggle="modal" data-target="#ExportModal"><i class="mdi mdi-arrow-down-bold-circle-outline"></i>Export</a></div></div>');
  });
 $(document).on('click','.btn-excel-download',function(){
    var query = {
        search_employee_id: $('#search_employee_id').val(),
        date_range_picker: $('#date_range_picker').val(),
		approveby : $('#approveby').val(),
        datefiled : $('#date').val(),
		type : $("#type").val(),
		approved_date : $("#approved_date").val(),
		export_type: $("#export_type").val(),
    }
    var url = "{{URL::to('dtr/download_absence_excel')}}?" + $.param(query)

   window.location = url;
});

</script>
@endsection