@extends('layouts.master')
@section('title', 'Asset Supplier')
@section('content')
<div class="content-wrapper">
  <div class="content">
    <div class="card">
      <div class="card-header">
        Asset Supplier
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

		 @if((check_permission(Auth::user()->Employee->department_id,"Suppliers","full")) || (check_permission(Auth::user()->Employee->department_id,"Suppliers","ADD")))
        <div class="form-group">
          <a style="position: relative; z-index: 999;" href="{{ route('asset_supplier.create') }}" class="btn btn-primary btn-icon-text btn-sm"><i class="mdi mdi-plus"></i> Add New Supplier</a>
        </div>
		  @endif
		@if((check_permission(Auth::user()->Employee->department_id,"Suppliers","full")) || (check_permission(Auth::user()->Employee->department_id,"Suppliers","VIEW")))
        <div class="table-responsive" id="search_result_container">
        
          <table id="id-data_table" class="table">
            <thead>
              <tr>
                <th>ID</th>
                <th>Type</th>
                <th>Supplier</th>
                <th>Phone No.</th>
                <th>Mobile No.</th>
                <th>Address</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              
            </tbody>
          </table>
        </div>
		  @endif

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
<script type="text/javascript">
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
                    url : "{{ route('asset_supplier.search_filter') }}",
                    type: 'GET',
                    data: function (f) {
                        
                    }
                },	  
                columns: [
                    { data: 'id', sortable:true },
                    { data: 'type', sortable:true },
                    { data: 'supplier', sortable:true },
                    { data: 'phone', sortable:false },
                    { data: 'mobile', sortable:false },
                    { data: 'address', sortable:true },
                    { data: 'action', sortable:false }
                ],
            });
            
            $("#search_form_container").hide();
            if(window.matchMedia("(max-width: 992px)").matches){
                $("#search_result_container").css("margin-top", "0px");
            }else{
                $("#search_result_container").css("margin-top", "-70px");
            }

            $("#id-data_table").on('click', '.btn-delete_row', function (){
                var id = $(this).data('id');
                var url = '{{ route("asset_supplier.destroy", ":id") }}';
                url = url.replace(':id', id);
                $("#DeleteModal").find('.delete-alert').html('Do you want to remove <span class="badge badge-primary">'+id+'</span> ? This supplier can\'t be restored anymore.');
                $("#deleteForm").attr('action', url);
            });

            $("#btn-submit_delete_row").on('click', function (){
                $("#deleteForm").submit();
            });

        }
        
        load_datatable();
        $('#search-btn').on('click', function (){
          load_datatable();
          $('#id-data_table').DataTable().draw(true);
        });

    });
    
</script>
@endsection