@extends('layouts.master')
@section('title', 'PhilHealth')
@section('content')
<div class="content-wrapper">
  <div class="row">
    <div class="col-sm-12 grid-margin stretch-card">
      <div class="card">
      <div class="card-header">PhilHealth 2019</div>
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
				@if((check_permission(Auth::user()->Employee->department_id,"PhilHealth","full")) || (check_permission(Auth::user()->Employee->department_id,"PhilHealth","Add")))
              <div class="col-md-6 col-sm-6 col-xs-12">
      				  <div class="form-group">
                     		 <a href="{{ route('philhealth.create') }}" class="btn btn-primary w-sm-100"><i class="mdi mdi-library-plus"></i> Add new Philhealth</a>
      				  </div>	  
              </div>
				@endif
              <div class="col-md-6 col-sm-6 col-xs-12">
                      @include('includes.perpage')
              </div>
            </div>
			@if((check_permission(Auth::user()->Employee->department_id,"PhilHealth","full")) || (check_permission(Auth::user()->Employee->department_id,"PhilHealth","View")))
            <div class="table-responsive pt-3">
            <table class="table table-bordered">
              <thead>
                <tr>
                <th> ID</th>
                <th> Salary Bracket </th>
                <th> Salary Min </th>
                <th> Salary Max </th> 
                <th> Total Monthly Premium </th> 
                <th> Employee Share </th> 
                <th> Employer Share </th> 
                <th colspan="2"> Action </th> 
                </tr>
              </thead>
              <tbody>
                @foreach($rows as $row)
                <tr>
                  <td><a href="{{route('philhealth.edit',[$row->id])}}"> {{$row->id}} </a></td>
                  <td>{{ '₱ '.str_replace('-',' to ₱ ', $row->salary_bracket )}}</td>
                  <td>{{ $row->salary_min }}</td>
                  <td>{{ $row->salary_max }}</td>
                  <td>{{ $row->total_monthly_premium }}</td>
                  <td>{{ $row->employee_share }}</td>
                  <td>{{ $row->employer_share }}</td>
                  <td>
				@if((check_permission(Auth::user()->Employee->department_id,"PhilHealth","full")) || (check_permission(Auth::user()->Employee->department_id,"PhilHealth","Edit")))
                  <button type="button" class="btn btn-outline-secondary btn-rounded btn-icon btn-sm" onclick="window.location.href = '{{route('philhealth.edit',[$row->id])}}';"><i class="mdi mdi-lead-pencil"></i></button>
				@endif
                  </td>
                  <td>
				@if((check_permission(Auth::user()->Employee->department_id,"PhilHealth","full")) || (check_permission(Auth::user()->Employee->department_id,"PhilHealth","Delete")))
                  <button type="button" data-target="#DeleteModal{{$row->id}}" data-toggle="modal" class="btn btn-outline-secondary btn-rounded btn-icon btn-sm"><i class="mdi mdi-delete"></i></button>
				@endif
                  </td>
                </tr>
                <!-- DELETE -->
                <div id="DeleteModal{{$row->id}}" class="modal fade text-default" role="dialog">
                    <div class="modal-dialog ">
                        <!-- Modal content-->
                        <form action="{{ route('philhealth.destroy',[$row->id])}}" id="deleteForm" method="post">
                            <div class="modal-content">
                                <div class="modal-header bg-default">

                                    <h4 class="modal-title text-center">REMOVE SALARY BRACKET?</h4>
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
              <div class="col-md-6 col-sm-6 col-xs-12 ">
                <div class="float-lg-right">
                  {{$rows->links()}}
                </div>
              </div>
            </div>
        </div>
      </div>
    </div><!-- /col-lg-12 -->
  </div>
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
   var url = '{{ route("philhealth.destroy", ":id") }}';
   url = url.replace(':id', id);
   $("#deleteForm").attr('action', url);
}

function formSubmit()
{
   $("#deleteForm").submit();
}
</script>
