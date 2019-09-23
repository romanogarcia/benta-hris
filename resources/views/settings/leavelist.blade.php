@extends('layouts.master')
@section('title', 'Leaves')
@section('content')
<div class="content-wrapper">
  <div class="row">
    <div class="col-sm-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-header">
          Leave Lists
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

          <p class="card-description">
            @if(isset($editleave->id ))
              <h5>Edit Mode</h5>
              <form method="POST" action="{{ route('leave.update', $editleave->id ) }}" enctype="multipart/form-data">
              @method('PATCH')
              @csrf
              <div class="form-group">
              <div class="input-group">
                <input type="text" class="form-control form-control-sm" placeholder="Name" name="name"  value="{{ $editleave->name }}" autocomplete="off" autofocus onfocus="this.value = this.value;" style="text-transform: uppercase;">
                <div class="input-group-append">
                  <button class="btn btn-sm btn-success" type="submit"><i class="mdi mdi-lead-pencil"></i>&nbsp;&nbsp;&nbsp; UPDATE &nbsp;&nbsp;&nbsp;</button>
                </div>
              </div>
            </div>
              
            @else
				  @if((check_permission(Auth::user()->Employee->department_id,"Leaves","full")) || (check_permission(Auth::user()->Employee->department_id,"Leaves","ADD")))
              <form method="POST" action="{{ route('leave.store') }}" autocomplete="off">
              @csrf
              <div class="form-group">
              <div class="input-group">
                <input type="text" class="form-control form-control-sm" placeholder="Name" name="name" autocomplete="off" autofocus style="text-transform: uppercase;">
                <div class="input-group-append">
                  <button class="btn btn-sm btn-primary" type="submit"><i class="mdi mdi-library-plus"></i>&nbsp;&nbsp;&nbsp; ADD &nbsp;&nbsp;&nbsp;</button>
                </div>
              </div>
            </div>
				  @endif
            @endif
          
            </form>            
          </p> <!-- /card-description -->
			@if((check_permission(Auth::user()->Employee->department_id,"Leaves","full")) || (check_permission(Auth::user()->Employee->department_id,"Leaves","View")))
          <div class="table-responsive">
            <table class="table table-striped">
              <thead>
                <tr><th> Name </th> <th> Action </th> </tr>
              </thead>
              <tbody>
                @foreach($leaves as $leave)
                <tr>
                  <td width="85%">{{ $leave->name }}  </td>
                  <td>
					  @if((check_permission(Auth::user()->Employee->department_id,"Leaves","full")) || (check_permission(Auth::user()->Employee->department_id,"Leaves","Edit")))
                    <button type="button" class="btn btn-outline-secondary btn-rounded btn-icon btn-sm" onclick="window.location.href = '{{ route('leave.leave_edit', $leave->id)  }}';"><i class="mdi mdi-lead-pencil"></i></button>
					  @endif
                  </td>
                  <td>
					  @if((check_permission(Auth::user()->Employee->department_id,"Leaves","full")) || (check_permission(Auth::user()->Employee->department_id,"Leaves","Delete")))
                    <button href="javascript:;" type="submit" data-target="#DeleteModal{{ $leave->id }}" class='btn btn-outline-secondary btn-rounded btn-icon' data-toggle="modal"><i class="mdi mdi-delete"></i></button>
					  @endif
                  </td>
                </tr>
                <div id="DeleteModal{{ $leave->id }}" class="modal fade text-default" role="dialog">
                  <div class="modal-dialog ">
                    <!-- Modal content-->
                    <form action="{{ route("leave.destroy", [$leave->id]) }}" id="deleteForm" method="post">
                      <div class="modal-content">
                        <div class="modal-header bg-default">
                          <h4 class="modal-title text-center">REMOVE STATUS ?</h4>
                        </div>
                        <div class="modal-body">
                          {{ csrf_field() }}
                          {{ method_field('DELETE') }}
                          <p>Do you want to really remove? This request can't be removed anymore.</p>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-success" data-dismiss="modal">Cancel</button>
                          <button type="submit" name="" class="btn btn-danger">Yes, Remove</button>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
                @endforeach
              </tbody>
            </table>
          </div>
			@endif
        </div>
      </div>
    </div><!-- /col-lg-12 -->
  </div>
</div>
@endsection

<script type="text/javascript">
function deleteData(id)
{
   var id = id;
   var url = '{{ route("leave.destroy", ":id") }}';
   url = url.replace(':id', id);
   $("#deleteForm").attr('action', url);
}

function formSubmit()
{
   $("#deleteForm").submit();
}
</script>
