@extends('layouts.master')
@section('title', 'User Roles')
@section('content')
<div class="content-wrapper">
    <div class="content">
        <div class="container-fluid py-5">
            @include('includes.messages')
            <div class="row">
				  <div class="col-md-12 col-sm-12  col-xs-12 float-right" style="text-align:right;">
               		 <a class="btn btn-primary mb-3" href="{{ url('dashboard/userroles/create') }}"><i class="fas fa-pencil-square-o"></i> ADD NEW ROLE</a>  
            	  </div>
            </div>
            <div class="card">
                <div class="card-header">User Roles</div>
                <div class="card-body">
                    <table class="table" id="user_role_table">
                        <thead>
                            <th>Role Type</th>
                            <th>Access</th>
                            <th>Created By</th>
                            <th>Modified By</th>
                            <th>Action</th>
                        </thead>
                        <tbody>
                          @foreach($record as $r)
                            <tr>                           
                              <td>{{ $r->type}}</td>
                              <td>{{ $r->access}}</td>
                              <td>{{ $r->created_by}}</td>
                              <td>{{ $r->modified_by}}</td>
                              <td>
                                <div class="row col-lg-8">
                                  <div class="col">
                                  <a href="{{ url('dashboard/userroles/'.$r->id.'/edit') }}" class="btn btn-sm btn-success"><i class="fas fa-pencil-square"></i></a>
                                  </div>
                                  <div class="col">

                                   <?php /*<form action="{{ url('dashboard/userroles/'.$r->id.'/delete') }}" method="get">
                                    @method('DELETE')
                                    @csrf*/ ?>
                                    <button type="submit" class='btn btn-sm btn-danger delete_role' data-id = '{{ $r->id }}'><i class='fas fa-trash'></i></button>
                                   <!-- </form> -->
                                  

                                  </div>
                                </div>
                              </td>
                            </tr>
                          @endforeach
                        </tbody>
                        {{$record->links()}}
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalCenterTitle">Confirm Timeout?</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
       Are you sure. You want to Time-out ?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <form method="post" action="{{ route('proccessTimeout') }}">
        @csrf
        <input type="hidden" value="{{ Session::get('attendance_id') }}" name="att_id">
        <button type="submit" class="btn btn-danger">TIMEOUT</button>
        </form>
      </div>
    </div>
  </div>
</div>

@endsection

@section('javascript')



<!-- DataTables -->
<script src="/dist/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="/dist/plugins/datatables/dataTables.bootstrap4.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@8.13.0/dist/sweetalert2.all.min.js"></script>
<script type="text/javascript">
$(document).on('click','.delete_role',function(){
	var id = $(this).data('id');
swal.fire({
    title: "Are you sure?",
	text: "Are you sure to delete this record?",
    type: 'warning',
	  showCancelButton: true,
	  confirmButtonText: 'Yes, delete it!',
	  cancelButtonText: 'No, cancel!',
}).then((result) => {
  if (result.value) {
   	$.ajax({
		url:"{{ url('dashboard/userroles/delete') }}",
		data:{"id":id, "_token": "{{ csrf_token() }}",},
   		type:"POST",
		success:function(res){
			window.location.reload();
		}
   	});
  } else if (result.dismiss === Swal.DismissReason.cancel) {
   
  }
   
});
});
</script>

@endsection