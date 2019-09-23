@extends('layouts.master')
@section('title', 'Attendance')
@section('content')
<div class="content-wrapper">
    <div class="content">
        <div class="card mb-2">
            <div class="card-header">
                Attendance
                <!-- <div class="row">
                    <div class="col-md-8" id="page-card_header">
                        Attendance
                    </div>
                    <div class="col-md-4">
                        <div class="custom-dataTables_length float-right" style="display: none;">
                            <label>
                                Show 
                                <select name="id-data_table_length" id="custom-data_table_length" aria-controls="id-data_table" class="form-control-sm">
                                    <option value="10">10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select> 
                                entries
                            </label>
                        </div>
                    </div>
                </div> -->
            </div>
            <div class="card-body">
                @if ($message = Session::get('success'))
                    <div class="alert alert-success" role="alert">
                        <i class="mdi mdi-alert-circle"></i>
                        <strong>{{ $message }}</strong>
                    </div>
                @endif
				@if((check_permission(Auth::user()->Employee->department_id,"Attendance","full")) || (check_permission(Auth::user()->Employee->department_id,"Attendance","ADD")))
            <div class="form-group">
                <a style="position: relative; z-index: 999;" class="btn btn-primary btn-icon-text btn-sm w-sm-100" href="{{ route ('attendance.create') }}"><i class="mdi mdi-plus"></i> Add New Record</a>  
            </div>
				@endif
            @csrf
            @if((check_permission(Auth::user()->Employee->department_id,"Attendance","full")) || (check_permission(Auth::user()->Employee->department_id,"Attendance","VIEW")))
            <div id="search_form_container">
                  <div class="row">
                      <div class="col-md-6">
                          <div class="form-group">
                            <select class="form-control form-control-sm" name="name" id="employee_id">
                                    <option value="">Employee</option>
                                  @foreach($employees as $employee)
                                    <?php $selected = (isset($filterdata['employee_id']) && $filterdata['employee_id'] == $employee->id)?"selected":""; ?>
                                      <option value="{{$employee->id}}" <?php echo $selected; ?> >
                                      {{ ucwords($employee->first_name) }} {{ ucwords($employee->last_name) }}
                                      </option>
                                  @endforeach
                              </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                              <select class="form-control form-control-sm" name="state_status" id="state_status">
                                      <option value="">Status</option>
                                      <option value="Pending">Pending</option>
                                      <option value="Approved">Approved</option>  
                                      <option value="Rescheduled">Rescheduled</option>
                                      <option value="Declined">Declined</option>
                                </select>
                              </div>
                          </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <input type="text" readonly class="form-control form-control-sm" name="date_range_pick" id="date_range_pick" placeholder="Date From - To" value="<?php echo (isset($filterdata['date_range']))?$filterdata['date_range']:""; ?>">
                            </div>

                            <div class="form-group text-right">
                              <button type="submit" id="search-btn" class="btn btn-primary btn-icon-text btn-sm w-sm-100">
                                  <i class="mdi mdi-account-search"></i>                                                    
                                  Search 
                              </button>
                            </div> 	
                        </div>
                  </div>
                </div>
			@endif
                <div class="table-responsive" id="search_result_container" style="display: none;">
                    <table class="table" id="id-data_table">
                        <thead>
                            <th>ID</th>
                            <th>Date</th>
                            <th>Name</th>
							<th>Status</th>
                            <th>Approved By</th>
                            <th>Time-In</th>
                            <th>Time-Out</th>
                            <th>Total</th>
                          	<th>Last Updated</th>
                            <th>Action</th>
                        </thead>
                    
                    </table>
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
            <button type="button" class="btn btn-secondary w-sm-100" data-dismiss="modal">Close</button>
            <form action="" id="deleteForm" method="post" class="w-sm-100">
                @method('DELETE')
                @csrf
                <button type="submit" class="btn btn-danger w-sm-100">Delete</button>
            </form>
        </div>
        </div>
    </div>
</div>
@endsection

@section('customjs')

<script type="text/javascript">
  var SITEURL = '{{URL::to('')}}';
  
  $(document).ready(function(){
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
		var date_format = '{{get_date_format()}}';
		
		date_format = date_format.replace("m","MM");
		date_format = date_format.replace("d","DD");
		date_format = date_format.replace("Y","YYYY");
		
      $(this).val(picker.startDate.format(date_format) + ' - ' + picker.endDate.format(date_format));
    });

    function load_datatable(postbtn){
        $("#search_result_container").show();
        $('#id-data_table').DataTable({
            searching: false,
            processing: true,
            serverSide: true,
            responsive: true,
            autoWidth : false,
            "language": {
                "paginate": {
                  "previous": "<<",
                  "next" : ">>"
                }
            },
            order: [[ 0, "desc" ]],
            dom   : "<'row'<'col-sm-12 float-right margin-bottom20'B>><'row'<'col-sm-12 col-xs-12 text-right'l>><'row'<'col-sm-12'tr>><'row'<'col-sm-5 col-xs-6'i><'col-sm-7 col-xs-6'p>>",
            ajax: {
                url : "{{ route('attendance.search') }}",
                type: 'GET',
                data: function (f) {
                    f.employee_id       = $('#employee_id').val();
                    f.date_range_pick   = $('#date_range_pick').val();
                    f.status            = $('#state_status').val();
                    f.postbtn           = postbtn;
                    f.from_attandance_list = <?php  echo ($from_attandance_list)?1:0; ?>;
                }
            },	  
            columns: [
                { data: 'id', sortable:true },
                { data: 'at_date', sortable:true },
                { data: 'name', sortable:true },
                { data: 'state_status', sortable:true },
                { data: 'approved_by', sortable:false },
                { data: 'time_in', sortable:true },
                { data: 'time_out', sortable:true },
                { data: 'total', sortable:true },
                { data: 'last_updated', sortable:true },
                { data: 'action', sortable:false }
            ],

        });
        
        $.fn.DataTable.ext.pager.numbers_length = 5;

        // $('#id-data_table').on( 'error.dt', function ( e, settings, techNote, message ) {
        //     console.log( 'An error has been reported by DataTables: ', message );
        // } ) .DataTable();
        // $.fn.dataTable.ext.errMode = 'none';
        $("#search_form_container").hide();
        if(window.matchMedia("(max-width: 992px)").matches){
            $("#search_result_container").css("margin-top", "0px");
        }else{
            $("#search_result_container").css("margin-top", "-70px");
        }

        $("#id-data_table").on('click', '.btn-delete_row', function (){
            var id = $(this).data('id');
            var url = '{{ route("attendance.destroy", ":id") }}';
            url = url.replace(':id', id);
            $("#DeleteModal").find('.delete-alert').html('Do you want to remove <span class="badge badge-primary">'+id+'</span> ? This attendance can\'t be restored anymore.');
            $("#deleteForm").attr('action', url);
        });

        $("#btn-submit_delete_row").on('click', function (){
            $("#deleteForm").submit();
        });

        // $(".custom-dataTables_length").show();
        // $('#search_result_container').find("#id-data_table_length").css('visibility', 'hidden');
        // $("#custom-data_table_length").on('change', function (){
        //         var value = $(this).val();
        //         var data_table_select = $("#id-data_table_length select");
                
        //         data_table_select.val(value);
        //         data_table_select.change();
        // });
    }

    $('#search-btn').on('click', function (){
        load_datatable(1);
        $('#id-data_table').DataTable().draw(true);
    });
 

    @if(Session::has('flash_attendance'))
        var employee_id = "{{ Session::get('flash_attenda   nce')['employee_id'] }}";
        var from_date   = "{{ date('m/d/Y',strtotime(Session::get('flash_attendance')['date'])) }}";
        var to_date     = "{{ date('m/d/Y',strtotime(Session::get('flash_attendance')['date'])) }}";

        $('#employee_id option[value='+employee_id+']').prop('selected', true);
        $('#date_range_pick').val(from_date+' - '+to_date);
        $("#search-btn").click();
    @endif

 
    <?php if($from_attandance_list){ ?>
        load_datatable(0);
        $('#id-data_table').DataTable().draw(true);
    <?php } ?>
    
 
    @if(Session::has('attendance_updated'))
      $("#search-btn").click();
    @endif
  });


</script>
@endsection