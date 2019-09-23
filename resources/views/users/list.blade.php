@extends('layouts.master')
@section('title', 'User Management')
@section('content')
<div class="content-wrapper">
    <div class="content">
        <div class="container-fluid py-5">
            <div class="card">
                <div class="card-header">
					<h3> User List <a class="btn btn-primary mb-3 float-right" style="text-align:right;" href="{{ url('dashboard/users/create') }}"><i class="fas fa-pencil-square-o"></i> ADD NEW USER</a></h3>
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
                    <table class="table" id="users_table">
                        <thead>
                            <th>First Name </th>
							<th>Last Name </th>
                            <th>Email </th>
                            <th>User Role </th>
                            <th>Status</th>
                            <th>Action</th>
                        </thead>
                        <tbody>
                          @foreach($record as $r)
                            <tr>                           
                              <td>{{ $r->firstname}}</td>
                              <td>{{ $r->lastname}}</td>
                              <td>{{ $r->email}}</td>
							  <td>{{ $r->user_role['type'] }}</td>
                              <td><?php  if($r->status=='1'){ echo "Active"; }else if($r->status=='0'){ echo "Inactive"; }else if($r->status=='2'){ echo "Block"; }  ?></td>
                              <td>
                                <div class="row col-lg-8">
                                  <div class="col">
                                  <a href="{{ url('dashboard/users/'.$r->id.'/edit') }}" class="btn btn-sm btn-success"><i class="fas fa-pencil-square"></i></a>
                                  </div>
                                  <div class="col">
                                   <?php /*<form action="{{ url('dashboard/userroles/'.$r->id.'/delete') }}" method="get">
                                    @method('DELETE')
                                    @csrf*/ ?>
                                    <button type="submit" class='btn btn-sm btn-danger delete_user' data-id = '{{ $r->id }}'><i class='fas fa-trash'></i></button>
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

@endsection

@section('javascript')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@8.13.0/dist/sweetalert2.all.min.js"></script>
<script type="text/javascript">
$(document).on('click','.delete_user',function(){
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
		url:"{{ url('dashboard/users/delete') }}",
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