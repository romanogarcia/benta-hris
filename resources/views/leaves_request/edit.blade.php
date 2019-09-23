@extends('layouts.master')
@section('title', 'Leave Request')
@section('customcss')
<style>
  .uper {
    margin-top: 40px;
  }
</style>
@endsection

@section('content')
<div class="content-wrapper">
  <div class="row">
    <div class="col grid-margin stretch-card">
      <div class="card">
        <div class="card-header">Edit Leave Request</div>
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

          @if(isset($editleaverequest->id ))
          <form method="POST" action="{{ route('leave.leaveupdate', $editleaverequest->id ) }}" enctype="multipart/form-data">
            <div class="form-group">
              @method('PATCH')
              @csrf

              <div class="col-md-12">
                <div class="form-group row">
                  <input type="hidden" name="user_id" value="{{ $editleaverequest->user_id }}">
                  <!-- <label class="col-sm-3 col-form-label">Status</label> -->
                </div>
              </div>
              <div class="row">
                <div class="col-md-4">
                  <label class="col-sm-12">Date Filed</label>
                  <div class="form-group row">
                    <div class="col">
                      <div class="form-control">{{ $editleaverequest->date_filed }}</div>
                    </div>
                  </div>
                </div>
              
                <div class="col-md-4">
                  <label class="col-sm-12">Date Start</label>
                  <div class="form-group row">
                    <div class="col">
                      <div class="form-control">{{ $editleaverequest->date_start }}</div>
                    </div>
                  </div>
                </div>

                <div class="col-md-4">
                  <label class="col-sm-12">Date End</label>
                  <div class="form-group row">
                    <div class="col">
                      <div class="form-control">{{ $editleaverequest->date_end }}</div>
                    </div>
                  </div>
                </div>
			</div>	  
				<div class="row">
				  <div class="col-md-12 col-sm-12">
					<div class="form-check form-check-flat form-check-primary">
						  <label class="form-check-label">
							<input id="half_day" type="checkbox" name="half_day" value="1" autocomplete="off" {{ $editleaverequest->half_day ?
							  'checked':'' }}>
							Half-Day 
						  <i class="input-helper"></i><i class="input-helper"></i></label>
					</div>
				  </div>
				</div>	
				<div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="reason">Reasons</label>
                    <textarea class="form-control" value="{{ $editleaverequest->reason ?: $editleaverequest }}" style="height: 150px;" aria-label="Reasons" name="reason" readonly>{{$editleaverequest->reason}}</textarea>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="comments">Comments</label>
                    <textarea placeholder="Add comments here.." class="form-control" value="{{ $editleaverequest->comments }}" style="height: 150px;" aria-label="Comments" name="comments">{{ $editleaverequest->comments }}</textarea>
                  </div>
                </div>
                <div class="col-md-6">
                  <br />
                  <label for="state_status">Status</label>
                  <select class="form-control" name="state_status">
                    @foreach($state_status as $row)
                    <option value="{{ $row }}"  {{$row == $editleaverequest->state_status ? 'selected':''}}>{{ $row }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="form-group mt-5" style="display: none;">
                <div class="input-group">
                  <input type="file" class="form-control form-control-sm" placeholder="Name" name="select_file" autocomplete="off" style="text-transform: uppercase;">
                </div>
              </div>

              <!-- <div class="form-group">
                <div class="input-group">
                  <input type="file" class="form-control form-control-sm" value="" placeholder="Name" name="select_file" autocomplete="off" style="text-transform: uppercase;">
                  <div class="input-group-append">
                  </div>
                </div>
              </div> -->
              <br />
              <button class="btn btn-success float-right w-sm-100" type="submit"><i class="mdi mdi-content-save"></i> SAVE</button>
            </div>
          </form>
          @else
          <form class="sample-form" method="POST" action="{{ route('leaves.leaverequest') }}" enctype="multipart/form-data">
            <div class="form-group">
              @csrf
              <div class="row">
                <div class="col-md-6 d-none">
                  <div class="form-group row">
                    <input type="number" name="user_id" value="{{ $user_id }}">
                    <label class="col-sm-3 col-form-label">Status</label>
                    <div class="col-sm-9">
                      <select class="form-control" name="state_status">
                        @foreach($state_status as $row)
                        <option value="{{ $row }}">{{ $row }}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                </div>
                <div class="col-md-6 d-none">
                  <div class="form-group row">
                    <label class="col-sm-3">Date</label>
                    <div class="col-sm-9">
                      <input type="text" id="date_file" value="{{ $today }}" class="form-control" name="date_filed" readonly>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group row">
                    <label class="col-sm-3">Date Start</label>
                    <div class="col-sm-9">
                      <input type="date" id="date_start" data-date="" data-date-format="DD MMMM YYYY" class="form-control form-control-sm" name="date_start">
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group row">
                    <label class="col-sm-3">Date End</label>
                    <div class="col-sm-9">
                      <input type="date" id="date_end" data-date="" data-date-format="DD MMMM YYYY" class="form-control form-control-sm" name="date_end">
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-9">
                  <label for="name">Type of Leave</label>
                  <select class="form-control" name="type">
                    @foreach($leave_data as $row)
                    <option value="{{ $row->name }}">{{ $row->name }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <br />
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text">Reasons</span>
                </div>
                <textarea class="form-control" style="height: 150px;" aria-label="Reasons" name="reason"></textarea>
              </div>
              <br />
              <div class="form-group">
                <div class="input-group">
                  <input type="file" class="form-control form-control-sm" placeholder="Name" name="select_file" autocomplete="off" style="text-transform: uppercase;">
                  <div class="input-group-append">
                  </div>
                </div>
              </div>
              <br />
                <button class="btn btn-success float-right w-sm-100" type="submit"><i class="mdi mdi-content-save"></i> SAVE</button>
            </div>
          </form>
          @endif
        </div>
      </div>
    </div>
  <!--  <div class="col-sm-4 grid-margin stretch-card">
      <div class="card">
        <div class="row">
          <div class='col-sm-12'>
            <img style="width:100%" src="@if($_SERVER['REMOTE_ADDR'] == '127.0.0.1')
                                    {{asset('images/files')}}/{{ $editleaverequest->filepath }}
                                  @else
                                    {{url('public/images/files')}}/{{ $editleaverequest->filepath }}
                                  @endif" alt="Leave file upload">
            <p class="text-center">
              <a title="Download File" href="@if($_SERVER['REMOTE_ADDR'] == '127.0.0.1')
                          {{asset('images/files')}}/{{ $editleaverequest->filepath }}
                        @else
                          {{url('public/images/files')}}/{{ $editleaverequest->filepath }}
                        @endif" download="{{ $editleaverequest->filepath }}"><i class="mdi mdi-download"></i> 
                        {{ $editleaverequest->filepath }} 

                        
                      </a>
            </p>
          
          </div>
        </div>
      </div>
    </div> -->
  </div>
</div>


<div id="DeleteModal" class="modal fade text-default" role="dialog">
 <div class="modal-dialog ">
   <!-- Modal content-->
   <form action="" id="deleteForm" method="post">
       <div class="modal-content">
           <div class="modal-header bg-default">
               
               <h4 class="modal-title text-center">Leave Request ?</h4>
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

@section('customjs')
<script type="text/javascript">
    function deleteData(id)
    {
       var id = id;
       var url = '{{ route("leave.leavedestroy", ":id") }}';
       url = url.replace(':id', id);
       $("#deleteForm").attr('action', url);
    }
    
    function formSubmit()
    {
       $("#deleteForm").submit();
    }
</script>
@endsection