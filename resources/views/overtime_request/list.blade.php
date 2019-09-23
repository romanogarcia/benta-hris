@extends('layouts.master')
@section('title', 'Overtime Request')
@section('content')
<div class="content-wrapper">
    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <div class="row">
						 <div class="col-sm-12 col-xs-12" style="margin: auto;">
						 Overtime Request
						</div>
					   
					</div>	
                </div>
                <div class="card-body">
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success" role="alert">
                            <i class="mdi mdi-alert-circle"></i>
                            <strong>{{ $message }}</strong>
                        </div>
                    @endif
					<div class="form-group">
						 <a class="btn btn-primary btn-sm" href="{{ route ('overtime.create') }}" style="position: relative; z-index: 999;">
                            <i class="mdi mdi-plus"></i> Request New Overtime
                        </a>
					</div>

                    <div class="table-responsive"  id="search_result_container">
                        <table class="table" id="data_table">
                            <thead>
                                <th>Request ID&nbsp;</th> 
                                <th>Name&nbsp;</th>
                                <th>Date Start&nbsp;</th>
                                <th>Date End&nbsp;</th> 
                                <th>Type&nbsp;</th>
                                <th>Status&nbsp;</th>
                                <th>Approved By&nbsp;</th>
                                <th colspan="2">Action&nbsp;</th>
                            </thead>
                           
                        </table>
                    </div>
                </div>
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
            <span class="delete-alert"></span>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <form action="" id="deleteForm" method="post">
                @method('DELETE')
                @csrf
                <button type="submit" class="btn btn-danger">Delete</button>
            </form>
        </div>
        </div>
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

<script type="text/javascript">
    $(document).ready(function(){
        $("#data_table").DataTable();
    });
</script>
@endsection
@section('customjs')
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<script type="text/javascript">

var SITEURL = '{{URL::to('')}}';
 $(document).ready( function () {
    $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
      }
    });
    
    $('#date_range_pick[readonly]').css({'background-color':'#FFFFFF'});
     $('#date_range_pick').daterangepicker({
		showDropdowns: true,
		minYear: 1980,
		maxYear: parseInt(moment().format('YYYY')),
		locale: {
			  cancelLabel: 'Clear'
		},
		"autoUpdateInput": false,
		"autoApply":false,
		// maxDate:moment(), 
    });

    $('#date_range_pick').on('apply.daterangepicker', function(ev, picker) {
		 $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
	});
	load_datatable();
    function load_datatable(){
        $("#search_result_container").show();
        $('#data_table').DataTable({
            searching: false,
            processing: true,
            serverSide: true,
            responsive: true,
            autoWidth : false, 
            dom   : "<'row'<'col-sm-12 float-right margin-bottom20'B>><'row'<'col-sm-12 col-xs-12 text-right'l>><'row'<'col-sm-12'tr>><'row'<'col-sm-5 col-xs-6'i><'col-sm-7 col-xs-6'p>>",
            ajax: {
                url : "{{ route('overtime_requests.search_filter') }}",
                type: 'GET',
                data: function (f) {
                    f.request_id       = $('#request_id').val();
                    f.start_date   = $('#start_date').val();
                    f.end_date        = $('#end_date').val();
                    f.overtime_type      = $('#overtime_type').val();
					f.length = $("select[name='data_table_length']").val();
                }
            },	  
            columns: [
                { data: 'id', sortable:true },
                { data: 'name', sortable:true },
                { data: 'date_start', sortable:true },
                { data: 'date_end', sortable:true },
                { data: 'type', sortable:true },
                { data: 'status', sortable:false },
                { data: 'approved_by', sortable:false },
                { data: 'action', sortable:false }
            ],
        });
        $('#data_table').on( 'error.dt', function ( e, settings, techNote, message ) {
            console.log( 'An error has been reported by DataTables: ', message );
        } ) .DataTable();
        $.fn.dataTable.ext.errMode = 'none';
        $("#search_form_container").hide();
		if(window.matchMedia("(max-width: 992px)").matches){
            $("#search_result_container").css("margin-top", "0px");
        }else{
            $("#search_result_container").css("margin-top", "-70px");
        }


        $("#data_table").on('click', '.btn-delete_row', function (){
            var id = $(this).data('id');
            var url = '{{ route("overtime.destroy", ":id") }}';
            url = url.replace(':id', id);
            $("#DeleteModal").find('.delete-alert').html('Do you want to remove <span class="badge badge-primary">'+id+'</span> ? This overtime request can\'t be restored anymore.');
            $("#deleteForm").attr('action', url);
        });

        $("#btn-submit_delete_row").on('click', function (){
            $("#deleteForm").submit();
        });
    }
    

   
 });
 

</script>
@endsection