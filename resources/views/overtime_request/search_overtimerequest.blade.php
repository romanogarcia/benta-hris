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
					@if((check_permission(Auth::user()->Employee->department_id,"Overtime Request","full")) || (check_permission(Auth::user()->Employee->department_id,"Overtime Request","ADD")))
					<div class="form-group">
						 <a style="position: relative; z-index: 999;" class="btn btn-primary btn-sm" href="{{ route ('overtime.create') }}" >
							<i class="mdi mdi-plus"></i> Request New Overtime
						</a>
					</div>
					@endif
					@if((check_permission(Auth::user()->Employee->department_id,"Overtime Request","full")) || (check_permission(Auth::user()->Employee->department_id,"Overtime Request","VIEW")))	 
					<div id="search_form_container">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                     <input type="text" class="form-control form-control-sm" placeholder="Request ID" name="request_id" id="request_id" />
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                     <input type="text" class="form-control form-control-sm is_datefield" placeholder="Start Date" name="start_date" id="start_date" data-placeholder="available"/>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                     <input type="text" class="form-control form-control-sm is_datefield" placeholder="End Date" name="end_date" id="end_date" data-placeholder="available"/>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <select class="form-control form-control-sm" name="overtime_type" id="overtime_type">
                                        <option value="">- Type of overtime -</option>
                                        <option value="Regular Overtime" >Regular Overtime</option>
                                        <option value="Holiday Overtime" >Holiday Overtime</option>
                                        <option value="Sunday Overtime" >Sunday Overtime</option>
                                    </select>
                                </div>
                            </div>

                            <div class="clearfix"></div>
                    		<div class="col-md-12">
								<div class="form-group text-right">
									<button class="btn btn-sm btn-primary float-right w-sm-100" id="search_overtime" type="submit">
									   <i class="mdi mdi-magnify"></i>
										Search
									</button>
								</div>
							</div>	
                        </div>
					</div>	 
					@endif

                    <div class="table-responsive"  id="search_result_container" style="display: none;">
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
<script type="text/javascript" src="{{asset('plugins/jquery/jquery.cookie.min.js')}}"></script>


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
        // $('#data_table').on( 'error.dt', function ( e, settings, techNote, message ) {
        //     console.log( 'An error has been reported by DataTables: ', message );
        // } ) .DataTable();
        // $.fn.dataTable.ext.errMode = 'none';
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
    
     /* 
    get_cookie_search() //string
    datatables id default id is 'id-data_table'
    */
    get_cookie_search(); //customize function to get the cookie search backflow

    $('#search_overtime').on('click', function (){
        load_datatable();
        $('#data_table').DataTable().draw(true);
        
        var cookie_array = {
            'request_id'     : $('#request_id').val(),
            'start_date' : $('#start_date').val(),
            'end_date'      : $('#end_date').val(),
            'overtime_type'    : $('#overtime_type').val(),
        };

        /*
        put_cookie_search(fields_id_and_value_array_format) 
        customize function put the field value in search for backflow
        NOTE: Key is same the Input ID
        */
        put_cookie_search(cookie_array); 
    });


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