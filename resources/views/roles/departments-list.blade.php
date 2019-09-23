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
    <div class="col-sm-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-header">
          User Roles
        </div>
        <div class="card-body">
          @if ($message = Session::get('success')) 
          <div class="alert alert-success" role="alert">
            <i class="mdi mdi-alert-circle"></i> 
            <strong>{{ $message }}</strong>
          </div>
          @endif

          @if ($errors->any())
          <div class="alert alert-danger">
              @foreach ($errors->all() as $error)        
              <i class="mdi mdi-alert-circle"></i>
              <strong>{{ $error }}</strong>
              @endforeach
          </div>
          @endif

          <div class="table-responsive" id="search_result_container">
            <table id="id-data_table" class="table">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Department</th>
                  <th>Action</th>
                </tr>
              </thead>
              
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
<script type="text/javascript" src="{{asset('plugins/jquery/jquery.cookie.min.js')}}"></script>

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
		load_datatable();
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
                    url : "{{ url('role_list_ajax') }}",
                    type: 'GET',
                    data: function (f) {
                        // f.role_access           = $('#role_access').val();
                        f.department_id     = $('#department_id').val();
                        // f.role_name     = $('#role_name').val();
                        // f.is_active         = $("#is_active").val();
                    }
                },	  
                columns: [
                    { data: 'id', sortable:true },
                    // { data: 'role_name', sortable:true },
                    { data: 'department', sortable:true },
                    // { data: 'page', sortable:true },
                    // { data: 'role', sortable:true },
                    // { data: 'active', sortable:true },
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

        $('#search-btn').on('click', function (){
          load_datatable();
          $('#id-data_table').DataTable().draw(true);

          var cookie_array = {
              'role_access'     : $('#role_access').val(),
              'role_name' : $('#role_name').val(),
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