@extends('layouts.master')
@section('title', 'Departments')
@section('content')
<div class="content-wrapper">
  <div class="row">
    <div class="col-sm-12 grid-margin stretch-card">
      <div class="card">
          <div class="card-header">
              Department Lists
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


          <p class="card-description">
            @if(isset($editdepartment->id ))
              <h5>Edit Mode</h5>
              <form method="POST" action="{{ route('departments.update', $editdepartment->id ) }}" enctype="multipart/form-data">
              @method('PATCH')
              @csrf
              <div class="form-group">
              <div class="input-group">
                <input type="text" class="form-control form-control-sm" placeholder="Name" name="name"  value="{{ $editdepartment->name }}" autocomplete="off" autofocus style="text-transform: uppercase;">
                <div class="input-group-append">
                  <button class="btn btn-sm btn-success" type="submit"><i class="mdi mdi-lead-pencil"></i>&nbsp;&nbsp;&nbsp; UPDATE &nbsp;&nbsp;&nbsp;</button>
                </div>
              </div>
            </div>
              
            @else
				 @if((check_permission(Auth::user()->Employee->department_id,"Departments","full")) || (check_permission(Auth::user()->Employee->department_id,"Departments","ADD")))
              <form method="POST" action="{{ route('departments.store') }}" autocomplete="off">
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
			@if((check_permission(Auth::user()->Employee->department_id,"Departments","full")) || (check_permission(Auth::user()->Employee->department_id,"Departments","View")))
          <div class="table-responsive">
            <table class="table table-striped">
              <thead>
                <tr><th> Name </th> <th> Action </th> </tr>
              </thead>
              <tbody>
                @foreach($departments as $department)
                <tr>
                  <td width="85%">{{ $department->name }}  </td>
                  <td>
					  @if((check_permission(Auth::user()->Employee->department_id,"Departments","full")) || (check_permission(Auth::user()->Employee->department_id,"Departments","Edit")))
                    <button type="button" class="btn btn-outline-secondary btn-rounded btn-icon btn-sm" onclick="window.location.href = '{{ URL::to('departments/' . $department->id . '/edit') }}';"><i class="mdi mdi-lead-pencil"></i></button>
					  @endif
                  </td>
                  <td>
					  @if((check_permission(Auth::user()->Employee->department_id,"Departments","full")) || (check_permission(Auth::user()->Employee->department_id,"Departments","Delete")))
                    <button href="javascript:;" type="submit" data-target="#DeleteModal{{$department->id}}" class='btn btn-outline-secondary btn-rounded btn-icon' data-toggle="modal"><i class="mdi mdi-delete"></i></button>
					  @endif
                  </td>
                </tr>
                <!-- DELETE -->
                <div id="DeleteModal{{$department->id}}" class="modal fade text-default" role="dialog">
                    <div class="modal-dialog ">
                        <!-- Modal content-->
                        <form action="{{ route("departments.destroy",[$department->id]) }}" id="deleteForm" method="post">
                            <div class="modal-content">
                                <div class="modal-header bg-default">

                                    <h4 class="modal-title text-center">REMOVE DEPARTMENT ?</h4>
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


<!-- CREATE -->
<div id="CreateModal" class="modal fade text-default" role="dialog">
 <div class="modal-dialog ">
   <!-- Modal content-->
   <form action="" id="deleteForm" method="post">
       <div class="modal-content">
           <div class="modal-header bg-default">
               
               <h4 class="modal-title text-center">CREATE DEPARTMENT ?</h4>
           </div>
           <div class="modal-body">
               {{ csrf_field() }}
               {{ method_field('CREATE') }}
               <p class="text-center">
                <center>
                   <button type="button" class="btn btn-success" data-dismiss="modal">Cancel</button>
                   <button type="submit" name="" class="btn btn-danger" data-dismiss="modal" onclick="formSubmit()">Yes, Save</button>
               </center>
             </p>
           </div>
           <div class="modal-footer">
             
           </div>
       </div>
   </form>
 </div>
</div>

<!-- UPDATE -->
<div id="UpdateModal" class="modal fade text-default" role="dialog">
 <div class="modal-dialog ">
   <!-- Modal content-->
   <form action="" id="deleteForm" method="post">
       <div class="modal-content">
           <div class="modal-header bg-default">
               <h4 class="modal-title text-center">UPDATE DEPARTMENT ?</h4>
           </div>
           <div class="modal-body">
               {{ csrf_field() }}
               {{ method_field('PATCH') }}
               <p class="text-center">
                <center>
                   <button type="button" class="btn btn-success" data-dismiss="modal">Cancel</button>
                   <button type="submit" name="" class="btn btn-danger" data-dismiss="modal" onclick="formSubmit()">Yes, Update</button>
               </center>

           </div>
           <div class="modal-footer">
             
           </div>
       </div>
   </form>
 </div>
</div>
@endsection

@section('customjs')
<script type="text/javascript">
  function deleteData(id)
  {
     var id = id;
     var url = '{{ route("departments.destroy", ":id") }}';
     url = url.replace(':id', id);
     $("#deleteForm").attr('action', url);
  }

  function formSubmit()
  {
     $("#deleteForm").submit();
  }
</script>
@endsection