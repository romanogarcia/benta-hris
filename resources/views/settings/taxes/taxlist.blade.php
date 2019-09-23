@extends('layouts.master')
@section('title', 'Tax')
@section('content')
<div class="content-wrapper">
    <div class="grid-margin stretch-card">
      <div class="card">
      <div class="card-header">Tax</div>
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
          <div class="row">
			  @if((check_permission(Auth::user()->Employee->department_id,"Tax","full")) || (check_permission(Auth::user()->Employee->department_id,"Tax","Add")))
			<div class="col-md-6 col-sm-6 col-xs-12">
				  <div class="form-group">
          		<a href="{{ route('tax.create') }}" class="btn btn-primary w-sm-100">
                <i class="mdi mdi-library-plus"></i> ADD NEW TAX
              </a>
				  </div>	  
            </div>
			  @endif
            <div class="col-md-6 col-sm-6 col-xs-12">
              	@include('includes.perpage')
            </div>
          </div>
			@if((check_permission(Auth::user()->Employee->department_id,"Tax","full")) || (check_permission(Auth::user()->Employee->department_id,"Tax","View")))
          <div class="table-responsive mt-3">
            <table class="table table-bordered">
              <thead>
                <tr>
                <th> Tax ID</th>
                <th> Compensation Level </th> 
                <th> Over </th>
                <th> Prescribe Minimum WH Tax </th> 
                <th> Additional Percentage over CL</th>
                <th colspan="2"> Action </th>
                </tr>
              </thead>
              <tbody>
                @foreach($rows as $row)
                <tr>
                  <td>
					  @if((check_permission(Auth::user()->Employee->department_id,"Tax","full")) || (check_permission(Auth::user()->Employee->department_id,"Tax","Edit")))
					  <a href="{{route('tax.edit',[$row->id])}}">{{$row->id}}</a>
					  @else
					  {{$row->id}}
					@endif
					</td>
                  <td>{{ $row->compensation_level }}</td>
                  <td>{{ $row->over }}</td>
                  <td>{{ $row->tax }}</td>
                  <td>+{{ $row->percentage }} %</td>
                  <td>
				@if((check_permission(Auth::user()->Employee->department_id,"Tax","full")) || (check_permission(Auth::user()->Employee->department_id,"Tax","Edit")))
                  <button type="button" class="btn btn-outline-secondary btn-rounded btn-icon btn-sm" onclick="window.location.href = '{{route('tax.edit',[$row->id])}}';"><i class="mdi mdi-lead-pencil"></i></button>
				@endif
                  </td>
                  <td>
					  @if((check_permission(Auth::user()->Employee->department_id,"Tax","full")) || (check_permission(Auth::user()->Employee->department_id,"Tax","Delete")))
                      <button type="button" data-target="#DeleteModal{{$row->id}}" data-toggle="modal" class="btn btn-outline-secondary btn-rounded btn-icon btn-sm"><i class="mdi mdi-delete"></i></button>
					  @endif
                  </td>
                </tr>
                <!-- DELETE -->
                <div id="DeleteModal{{$row->id}}" class="modal fade text-default" role="dialog">
                    <div class="modal-dialog ">
                        <!-- Modal content-->
                        <form action="{{ route('tax.destroy',[$row->id])}}" id="deleteForm" method="post">
                            <div class="modal-content">
                                <div class="modal-header bg-default">

                                    <h4 class="modal-title text-center">REMOVE TAX COMPENSATION?</h4>
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
            <div class="row mt-3">
              <div class="col-md-6 col-sm-6 col-xs-12">
                {!!  $entries !!}
              </div>
               <div class="col-md-6 col-sm-6 col-xs-12">
				  <div class="float-lg-right">
					{{$rows->links()}}
				  </div>
              </div>
            </div>
        </div>
      </div>
    </div><!-- /col-lg-12 -->
</div>

<div id="DeleteModal" class="modal fade text-default" role="dialog">
 <div class="modal-dialog ">
   <!-- Modal content-->
   <form action="" id="deleteForm" method="post">
       <div class="modal-content">
           <div class="modal-header bg-default">
               
               <h4 class="modal-title text-center">DELETE ROW ?</h4>
           </div>
           <div class="modal-body">
               {{ csrf_field() }}
               {{ method_field('DELETE') }}
               <p class="text-center">
                <center>
                   <button type="button" class="btn btn-success" data-dismiss="modal">Cancel</button>
                   <button type="submit" name="" class="btn btn-danger" data-dismiss="modal" onclick="formSubmit()">Yes, Delete</button>
               </center>
             </p>
           </div>
           <div class="modal-footer">
             
           </div>
       </div>
   </form>
 </div>
</div>
@endsection

<script type="text/javascript">
function deleteData(id)
{
   var id = id;
   var url = '{{ route("tax.destroy", ":id") }}';
   url = url.replace(':id', id);
   $("#deleteForm").attr('action', url);
}

function formSubmit()
{
   $("#deleteForm").submit();
}
</script>
