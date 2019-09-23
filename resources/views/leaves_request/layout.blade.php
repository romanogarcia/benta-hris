<!-- create.blade.php -->

@extends('layouts.master')
@section('title', 'Leave Request')
@section('content')
<style>
  .uper {
    margin-top: 40px;
  }
</style>
<div class="content-wrapper">
  
  <div class="row">
    <div class="col-sm-12">
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
      <div class="card">
        <div class="card-body">
			<a href="{{ url('/leaves_request/layout/create') }}" class="btn btn-primary btn-icon-text btn-sm">
              <i class="mdi mdi-plus"></i>                                                    
              ADD LEAVE REQUEST
            </a>
			<br />
			<br />
          <div class="table-responsive">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th> Status </th>
                  <th> Date Filed </th>
                  <th> Date Start </th>
                  <th> Date End </th>
                  <th> Type </th>
                  <th> Reason </th>
                  <th> File Path </th>
                  <th> Approved By</th>
                  <th> Approved Date</th>
                  <th> Remarks </th>
                  <th colspan="2">Action </th>
                </tr>
              </thead>
              <tbody>
                @foreach($leave_list as $row)
                <tr>
                  <td>{{ $row->state_status }}</td>
                  <td>{{ $row->date_filed }}</td>
                  <td>{{ $row->date_start }}</td>
                  <td>{{ $row->date_end }}</td>
                  <td>{{ $row->type }}</td>
                  <td>{{ $row->reason }}</td>
                  <td> 
                    @if(!empty($row->filepath)) 
                      <a title="View file" href="javascript:void(0);" data-toggle="modal" data-target="#view-image-{{$row->id}}"> 
                        {{ $row->filepath }}
                      </a>

                      <!-- Modal -->
                      <div id="view-image-{{$row->id}}" class="modal fade" role="dialog">
                        <div class="modal-dialog modal-lg">
                          <div class="modal-content" style="border: 0px; background: transparent;">
                            <div class="modal-body">
                              <div class="text-right">
                                <button type="button" class="btn btn-xs btn-danger" data-dismiss="modal">
                                  <i class="mdi mdi-close"></i>  
                                </button>
                              </div>
                              <img src="@if($_SERVER['REMOTE_ADDR'] == '127.0.0.1')
                                    {{asset('images/files')}}/{{ $row->filepath }}
                                  @else
                                    {{url('public/images/files')}}/{{ $row->filepath }}
                                  @endif" style="border-radius: 0px; display: block; margin-left: auto; margin-right: auto; height: auto; width: auto;">
                              <p class="text-center" style="margin-top: 10px;">
                                <a style="padding: 5px; background: #FFF;" title="Download File" href="@if($_SERVER['REMOTE_ADDR'] == '127.0.0.1')
                                    {{asset('images/files')}}/{{ $row->filepath }}
                                  @else
                                    {{url('public/images/files')}}/{{ $row->filepath }}
                                  @endif" download="{{ $row->filepath }}"><i class="mdi mdi-download"></i> 
                                  {{ $row->filepath }} 

                                  
                                </a>
                              </p>
                            </div>
                          </div>
                        </div>
                      </div>


                    @else
                      <i class="text-danger">No file found</i>
                    @endif
                  </td>
                  @if($row->approved_by != null)
                    <td>{{ $row->approved_by }}</td>
                    <td>{{ $row->approved_date }}</td>
                  @else
                    <td><i class="text-danger">Not found</i></td>
                    <td><i class="text-danger">Not found</i></td>
                  @endif

                  <td>{{ $row->comments }}</td>
                  <td>
                    <button type="button" onclick="window.location.href='{{ URL::to('leaves_request/layout/' . $row->id . '/edit') }}'" class="btn btn-outline-secondary btn-rounded btn-icon btn-sm"><i class="mdi mdi-lead-pencil"></i></button>
                  </td>
                  <td>
                    <button type="submit" href="javascript:;" data-toggle="modal"  data-target="#DeleteModal{{ $row->id }}" class="btn btn-outline-secondary btn-rounded btn-icon btn-sm"><i class="mdi mdi-delete"></i></button>
                  </td>
                </tr>

                <div id="DeleteModal{{ $row->id }}" class="modal fade text-default" role="dialog">
                    <div class="modal-dialog ">
                        <!-- Modal content-->
                        <form action="{{ route("leaves.leavedestroy",[$row->id]) }}" id="deleteForm" method="post">
                            <div class="modal-content">
                                <div class="modal-header bg-default">

                                    <h4 class="modal-title text-center">REMOVE LEAVE REQUEST ?</h4>
                                </div>
                                <div class="modal-body">
                                    {{ csrf_field() }}
                                    {{ method_field('DELETE') }}
                                    <p>"Do you want to really remove? This request can't be removed anymore."</p>
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
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

<script type="text/javascript">
function deleteData(id)
{
   var id = id;
   var url = '{{ route("leaves.leavedestroy", ":id") }}';
   url = url.replace(':id', id);
   $("#deleteForm").attr('action', url);
}

function formSubmit()
{
   $("#deleteForm").submit();
}
</script>