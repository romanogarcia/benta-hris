@extends('layouts.master')
@section('title', 'Search Leave Request')
@section('content')
<div class="content-wrapper">
  <div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-header">
            Leave Request
        </div>
        <div class="card-body">
          @if ($message = Session::get('success')) 
          <div class="alert alert-success" role="alert">
            <i class="mdi mdi-alert-circle"></i>
            <strong>{{ $message }}</strong>
          </div>
          @endif
			@if((check_permission(Auth::user()->Employee->department_id,"Leave Request","full")) || (check_permission(Auth::user()->Employee->department_id,"Leave Request","ADD")))
            <div class="form-group">
                <a style="position: relative; z-index: 999;" href="{{ route('leave.leavecreate') }}" class="btn btn-primary btn-icon-text btn-sm">
                    <i class="mdi mdi-plus"></i>
                    Request New Leave
                </a>
            </div>
			@endif
            @if((check_permission(Auth::user()->Employee->department_id,"Leave Request","full")) || (check_permission(Auth::user()->Employee->department_id,"Leave Request","View")))
            <div id="search_form_container">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <select class="form-control form-control-sm" name="employee_id" id="employee_id">
                                <option value="">Select Employee</option>
                                @foreach($employees as $r)
                                    @if(Session::get('leaves_request_user_id') == $r->user_id)
                                    <option value="{{ $r->user_id }}" selected="selected">{{ ucwords($r->first_name) }} {{ ucwords($r->last_name) }}</option>
                                    @else
                                    <option value="{{ $r->user_id }}">{{ ucwords($r->first_name) }} {{ ucwords($r->last_name) }}</option>
                                    @endif
                                @endforeach
                            </select>    
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <select class="form-control" name="leave_type" id="leave_type">
                                    <option value="">Select Leave Type</option>
                                @foreach($leave_data as $row)
                                @if(Session::get('leaves_request_type') == $row->name)
                                <option value="{{ $row->name }}" selected="selected">{{ $row->name }}</option>
                                @else
                                <option value="{{ $row->name }}">{{ $row->name }}</option>
                                @endif
                                @endforeach
                            </select>
                        </div>
                    </div>	
                    <div class="col-md-6">
                        <div class="form-group">
                            <select class="form-control" name="state_status" id="state_status">
                                <option value="">Select Status</option>
                                @foreach($state_status as $row)
                                @if(Session::get('leaves_request_state_status') == $row)
                                <option value="{{ $row }}" selected="selected">{{ $row }}</option>
                                @else
                                <option value="{{ $row }}">{{ $row }}</option>
                                @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                            <div class="form-group">
                                <input type="text" class="form-control form-control-sm" name="approved_by" id="approved_by" placeholder="Approved By">
                            </div>	  
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <input type="text" readonly class="form-control form-control-sm" name="date_range_pick" id="date_range_pick" placeholder="Date From - To">
                        </div>	
                    </div>
                    
                </div>
                <div class="row">
                    <div class="col-md-12 text-right">
                        <div class="form-group">
                            <button type="button" id="search-btn" name="search-payroll-btn" class="btn btn-primary btn-icon-text btn-sm float-right w-sm-100">
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
                        <th>Name</th>
                        <th>Status</th>
                        <th>Approved By</th>
                        <th>From Date</th>
                        <th>To Date</th>
                        <th colspan="2">Action</th>
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


@section('customjs')
@if (Session::get('success'))
<script type="text/javascript">
$(document).ready(function(){
    setTimeout(function(){
        $('#search-btn').trigger('click');
    }, 100);
});
</script>
@endif
<script type="text/javascript" src="{{asset('plugins/jquery/jquery.cookie.min.js')}}"></script>
<script src="{{asset('js/custom-backflow.js')}}"></script>
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
		var date_format = '{{get_date_format()}}';
		
		date_format = date_format.replace("m","MM");
		date_format = date_format.replace("d","DD");
		date_format = date_format.replace("Y","YYYY");
		 $(this).val(picker.startDate.format(date_format) + ' - ' + picker.endDate.format(date_format));
	});

    function load_datatable(){
        $("#search_result_container").show();
        $('#id-data_table').DataTable({
            searching: false,
            processing: true,
            serverSide: true,
            responsive: true,
            autoWidth : false, 
            dom   : "<'row'<'col-sm-12 float-right margin-bottom20'B>><'row'<'col-sm-12 col-xs-12 text-right'l>><'row'<'col-sm-12'tr>><'row'<'col-sm-5 col-xs-6'i><'col-sm-7 col-xs-6'p>>",
            ajax: {
                url : "{{ route('leave.leaves_search_filter') }}",
                type: 'GET',
                data: function (f) {
                    f.employee_id       = $('#employee_id').val();
                    f.date_range_pick   = $('#date_range_pick').val();
                    f.leave_type        = $('#leave_type').val();
                    f.state_status      = $('#state_status').val();
                    f.approved_by       = $('#approved_by').val();
                }
            },	  
            columns: [
                { data: 'id', sortable:true },
                { data: 'name', sortable:true },
                { data: 'state_status', sortable:true },
                { data: 'approved_by', sortable:true },
                { data: 'from_date', sortable:true },
                { data: 'to_date', sortable:true },
                { data: 'action', sortable:false }
            ],
        });
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
            var url = '{{ route("leave.leavedestroy", ":id") }}';
            url = url.replace(':id', id);
            $("#DeleteModal").find('.delete-alert').html('Do you want to remove <span class="badge badge-primary">'+id+'</span> ? This leave request can\'t be restored anymore.');
            $("#deleteForm").attr('action', url);
        });

        $("#btn-submit_delete_row").on('click', function (){
            $("#deleteForm").submit();
        });
    }
    
    /* 
    get_cookie_search() //string
    datatables id default id is 'id-data_table'
    */
    get_cookie_search(); //customize function to get the cookie search backflow

    $('#search-btn').on('click', function (){
        load_datatable();
        $('#id-data_table').DataTable().draw(true);

        var cookie_array = {
            'employee_id'     : $('#employee_id').val(),
            'date_range_pick' : $('#date_range_pick').val(),
            'leave_type'      : $('#leave_type').val(),
            'state_status'    : $('#state_status').val(),
            'approved_by'     : $('#approved_by').val(),
        };

        /*
        put_cookie_search(fields_id_and_value_array_format) 
        customize function put the field value in search for backflow
        NOTE: Key is same the Input ID
        */
        put_cookie_search(cookie_array); 
    });
    
    <?php if(isset($_GET['scheds'])){ ?>
     load_datatable();
     $('#id-data_table').DataTable().draw(true);
     <?php }?>

     // CUSTOM FUNCTION FOR BACKFLOW
    function put_cookie_search(cookie_array=[]){
        var cookie_array_list = [];
        $.each(cookie_array, function(key, value){
            var obj = {};
            obj[key] = value;
            cookie_array_list.push(obj);
        });
        cookie_array_list.push({'last_url' : '{{url()->current()}}'});
        $.cookie('backflow_cookie', JSON.stringify(cookie_array_list), { expires: 1 });  // expires after 1 day
    }
    function get_cookie_search(){
        if(performance.navigation.type == 2){
            if (typeof $.cookie('backflow_cookie') !== 'undefined'){
                var cookie_stringify = $.cookie('backflow_cookie');
                var cookie_array_parse = JSON.parse(cookie_stringify);
                var last_url = cookie_array_parse[cookie_array_parse.length-1]['last_url'];
                if(last_url == '{{url()->current()}}'){
                    for(var key in cookie_array_parse){
                        for(var key_2 in cookie_array_parse[key]){
                            if(key_2 != 'last_url'){
                                $('#'+key_2).val(cookie_array_parse[key][key_2]);
                            }
                        }
                    }
                    load_datatable();
                    $('#id-data_table').DataTable().draw(true);
                    $.removeCookie('backflow_cookie');
                }
            }
        }
    }
 });
</script>



@endsection




