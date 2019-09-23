@extends('layouts.master')
@section('title', 'Role Management')
@section('customcss')
<style>
  .table th, .table td{
    padding: 2px 2px 2px 10px;
  }
</style>
@endsection

@section('content')
<div class="content-wrapper">
  <div class="row">
    <div class="col-lg-12 grid-margin strech-card">
        <div class="card">
            <div class="card-header">Add Role: <b>{{$department->name}}</b></div>
            <div class="card-body">
            @if ($message = Session::get('success')) 
            <div class="alert alert-success" role="alert">
                <i class="mdi mdi-alert-circle"></i> 
                <strong>{{ $message }}</strong>
            </div>
            @endif
                <form method="POST" action="{{ route('roles.store') }}" enctype="multipart/form-data" autocomplete="off">
                    @csrf
                    <input type="hidden" name="department_id" value="{{$department->id}}">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="page">Select Page</label>
                                <select class="form-control form-control-sm @error('page') is-invalid @enderror" name="page" id="add_page">
                                    <option value="">Select Page</option>
                                    @foreach($pages as $key => $page)
                                        <option {{ (old('page') == $key) ? 'selected':'' }} value="{{ $key }}">{{ strtoupper($page) }}</option>
                                    @endforeach
                                </select>
                                @error('page')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="role">Select Access</label>
                                <select class="form-control form-control-sm @error('role') is-invalid @enderror" name="role" id="add_role">
                                    <option value="">Select Access</option>
                                    @foreach($methods as $key => $method)
                                        <option {{ (old('role') == $key) ? 'selected':'' }} value="{{ $key }}">{{ ucfirst($method) }}</option>
                                    @endforeach
                                </select>
                                @error('role')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="is_active">Select Status</label>
                                <select class="form-control form-control-sm @error('is_active') is-invalid @enderror" name="is_active" id="add_is_active">
                                    <option value="">Select Status</option>
                                    <option {{ (old('is_active') == '1') ? 'selected':'' }} value="1">Active</option>
                                    <option {{ (old('is_active') == '0') ? 'selected':'' }} value="0">Inactive</option>
                                </select>
                                @error('is_active')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group text-right">
                                <button type="submit" class="btn btn-success btn-sm w-sm-100"><i class="mdi mdi-check"></i> SAVE </button> 
                            </div>
                        </div>
                    </div>
                </form>    
            </div>
        </div>
    </div>
  </div>

  <div class="row">
    <div class="col-sm-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-header">
          Role List: <b>{{ucwords($department->name)}}</b>
        </div>
        <div class="card-body">
          <div id="search_form_container" style="display: none;">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <select class="form-control form-control-sm" name="page" id="page">
                            <option value="">Select Page</option>
                            @foreach($pages as $key => $page)
                                <option value="{{ $key }}">{{ ucfirst($page) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <select class="form-control form-control-sm" name="role_access" id="role_access">
                            <option value="">Select Access</option>
                            @foreach($methods as $key => $method)
                                <option value="{{ $key }}">{{ ucfirst($method) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <select class="form-control form-control-sm" name="is_active" id="is_active">
                            <option value="">Select Active</option>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
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

          <div class="table-responsive" id="search_result_container">
            <table id="id-data_table" class="table">
             <thead>
                <tr>
                  <th>ID</th>
                  <th>Page</th>
                  <th>Role</th>
                </tr>
              </thead>
			<tbody>
				<?php $i=1;?>
				@foreach($pages as $key => $page)
				<tr>
					<td>{{$i}}</td>
					<td>{{$page}}</td>
					<td>
						@foreach($methods as $key => $method)
							<input type="checkbox" name="" value="{{$key}}">{{$method}} | 
						@endforeach
					</td>
				</tr>
				<?php $i++;?>
				@endforeach
			</tbody>
            </table>
          </div>

        </div>
      </div>
    </div><!-- /col-lg-12 -->
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

<script type="text/javascript">
    function deleteData(id){
        var id = id;
        var url = '{{ route("roles.destroy", ":id") }}';
        url = url.replace(':id', id);
        $("#DeleteModal").find('.delete-alert').html('Do you want to remove <span class="badge badge-primary">'+id+'</span> ? This role can\'t be restored anymore.');
        $("#deleteForm").attr('action', url);
    }

    function formSubmit(){
        $("#deleteForm").submit();
    }

    $(document).ready(function() {
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
                order: [[0, 'asc'],[1, 'asc']],
                dom   : "<'row'<'col-sm-12 float-right margin-bottom20'B>><'row'<'col-sm-12 col-xs-12 text-right'l>><'row'<'col-sm-12'tr>><'row'<'col-sm-5 col-xs-6'i><'col-sm-7 col-xs-6'p>>",
                ajax: {
                    url : "{{ route('role.edit_department_filter') }}",
                    type: 'GET',
                    data: function (f) {
                        f.page           = $('#page').val();
                        f.department_id     = '{{$department->id}}';
                        f.role_access     = $('#role_access').val();
                        f.is_active         = $("#is_active").val();
                    }
                },	  
                columns: [
                    { data: 'id', sortable:true },
                    { data: 'page', sortable:true },
                    { data: 'role', sortable:true },
                    { data: 'is_active', sortable:true },
                    { data: 'action', sortable:false }
                ],
            });
            
            $("#search_form_container").hide();
            // if(window.matchMedia("(max-width: 992px)").matches){
            //     $("#search_result_container").css("margin-top", "0px");
            // }else{
            //     $("#search_result_container").css("margin-top", "-70px");
            // }

            $("#id-data_table").on('click', '.btn-delete_row', function (){
                var id = $(this).data('id');
                var url = '{{ route("roles.destroy", ":id") }}';
                url = url.replace(':id', id);
                $("#DeleteModal").find('.delete-alert').html('Do you want to remove <span class="badge badge-primary">'+id+'</span> ? This role can\'t be restored anymore.');
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

        //load_datatable();
        $('#id-data_table').DataTable().draw(true);

        $('#search-btn').on('click', function (){
          $('#id-data_table').DataTable().draw(true);

          var cookie_array = {
              'page'     : $('#page').val(),
              'role_access' : $('#role_access').val(),
              'is_active'      : $('#is_active').val(),
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