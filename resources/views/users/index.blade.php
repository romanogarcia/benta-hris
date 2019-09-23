@extends('layouts.master')
@section('title', 'User Management')
@section('customcss')

@endsection

@section('content')
<div class="content-wrapper">
    <div class="content">
        <div class="card mb-2">
            <div class="card-header">
                User Management
            </div>
            <div class="card-body">
            @if ($message = Session::get('success'))
                    <div class="alert alert-success" role="alert">
                        <i class="mdi mdi-alert-circle"></i>
                        <strong>{{ $message }}</strong>
                    </div>
            @elseif($message = Session::get('error'))
                <div class="alert alert-danger" role="alert">
                    <i class="mdi mdi-alert-circle"></i>
                    <strong>{{ $message }}</strong>
                </div>
            @endif
				@if((check_permission(Auth::user()->Employee->department_id,"User Management","full")) || (check_permission(Auth::user()->Employee->department_id,"User Management","View")))
              <div id="search_form_container">
                <div class="row">
                  <div class="col-lg-4  col-md-6 col-sm-6">
                    <div class="form-group">
                      <select class="form-control form-control-sm" name="user_id" id="user_id">
                          <option value="">Select User</option>
                          @foreach($employees as $employee)
                              <option value="{{$employee->user_id}}">
                              {{ ucwords($employee->first_name) }} {{ ucwords($employee->last_name) }}
                              </option>
                          @endforeach
                      </select>
                    </div>
                  </div>
                  <div class="col-lg-4  col-md-6 col-sm-6">
                    <div class="form-group">
                        <select class="form-control form-control-sm" name="department_id" id="department_id">
                            <option value="">Select Department</option>
                            @foreach($departments as $department)
                                <option value="{{$department->id}}">
                                {{ ucfirst($department->name) }}
                                </option>
                            @endforeach
                        </select>
                      </div>
                  </div>
                  <div class="col-lg-4">
                    <div class="row">
                      <div class="col-lg-6">
                          <div class="form-group">
                              <div class="form-check">
                              <label class="form-check-label text-muted">
                                      <input class="form-check-input is_active" type="radio" name="is_active" value="1"> Active
                                  </label>
                              </div>
                          </div>
                      </div>
                      <div class="col-lg-6">
                          <div class="form-group">
                              <div class="form-check">
                              <label class="form-check-label text-muted">
                                      <input class="form-check-input is_active" type="radio" name="is_active" value="0"> Not Active
                                  </label>
                              </div>
                          </div>
                      </div>
                    </div>
                    
                  </div>
                </div>
                <div class="row">
                  <div class="col-lg-12">
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
                        <tr>
                          <th>Employee ID</th>
                          <th>Name</th>
                          <th>Email</th>
                          <th>Department</th>
                          <th>Active</th>
                          <th>Action</th>
                        </tr>
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
            <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
            <form action="" id="deleteForm" method="post">
                <!-- @method('DELETE') -->
                @csrf
                <button type="submit" class="btn btn-danger">Yes</button>
            </form>
        </div>
        </div>
    </div>
</div>
@endsection

@section('customjs')

<script type="text/javascript" src="{{asset('plugins/jquery/jquery.cookie.min.js')}}"></script>
@if ($message = Session::get('success'))
<script type="text/javascript">
$(document).ready(function(){
    setTimeout(function(){
        $('#search-btn').trigger('click');
    }, 100);
});
</script>
@endif
<script type="text/javascript">
  var SITEURL = '{{URL::to('')}}';
  
  $(document).ready(function(){
    $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    });

    function load_datatable(){
        $("#search_result_container").show();
        $('#id-data_table').DataTable({
            searching: false,
            processing: true,
            serverSide: true,
            responsive: true,
            autoWidth : false,
            order: [[ 0, "desc" ]], 
            dom   : "<'row'<'col-sm-12 float-right margin-bottom20'B>><'row'<'col-sm-12 col-xs-12 text-right'l>><'row'<'col-sm-12'tr>><'row'<'col-sm-5 col-xs-6'i><'col-sm-7 col-xs-6'p>>",
            ajax: {
                url : "{{ route('user.search_filter') }}",
                type: 'GET',
                data: function (f) {
                    f.user_id           = $('#user_id').val();
                    f.department_id     = $('#department_id').val();
                    f.is_active         = $("input[name='is_active']:checked").val();
                }
            },	  
            columns: [
                { data: 'id', sortable:true },
                { data: 'name', sortable:true },
                { data: 'email', sortable:true },
                { data: 'department', sortable:true },
                { data: 'is_active', sortable:true },
                { data: 'action', sortable:false }
            ],
        });
        // $('#id-data_table').on( 'error.dt', function ( e, settings, techNote, message ) {
        //     console.log( 'An error has been reported by DataTables: ', message );
        // } ) .DataTable();
        // $.fn.dataTable.ext.errMode = 'none';
        $("#search_form_container").hide();
        // if(window.matchMedia("(max-width: 992px)").matches){
        //     $("#search_result_container").css("margin-top", "0px");
        // }else{
        //     $("#search_result_container").css("margin-top", "-70px");
        // }

        $("#id-data_table").on('click', '.btn-delete_row', function (){
            var id = $(this).data('id');
            var employee_id = $(this).data('employee_id');
            var action = $(this).data('action');
            var url = $(this).data('url'); //'{{ route("user.deactivate_user", ":id") }}';
            url = url.replace(':id', id);
            if(action == 'Deactivate')
              var action_p = 'deactivated';
            else
              var action_p = 'activated';

            $("#DeleteModal").find('.delete-alert').html('Do you want to '+action+' <span class="badge badge-primary">'+employee_id+'</span> ? This user will be '+action_p+'.');
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
              'user_id'     : $('#user_id').val(),
              'department_id' : $('#department_id').val(),
              'is_active'      : $("input[name='is_active']:checked").val(),
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