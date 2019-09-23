@extends('layouts.master')
@section('title', 'User Roles')
@section('content')
<div class="content-wrapper">
    <div class="content">
        <div class="container-fluid p-5">
      		 @include('includes.messages')
            <div class="card col-lg-12 col-xs-12 col-md-12">
                <div class="card-header with-border">
                    <h3 class="box-title">Add User Role</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form role="form" method="post" action="{{ url('dashboard/userroles/save') }}"
                    aria-label="{{ __('User Roles') }}">
                    @csrf
                    <div class="card-body">
						<div class='row'>
							<div class="col-md-6 col-lg-6 col-xs-12">
								<div class="form-group">
									<label for="type">Role Type:</label>
									<input type="text" class="form-control @error('type') is-invalid @enderror" id="type" name="type"  value="{{ old('type') }}" placeholder="Role Type">
									@error('type')
									<div class="invalid-feedback">{{$message}}</div>
									@enderror
								</div>
							</div>	
							<div class="col-md-6 col-lg-6 col-xs-12">
								<div class="form-group">
									<label for="access">Access:</label>
									<input type="text" class="form-control  @error('access') is-invalid @enderror" id="access" placeholder="Access" name="access"
										value="{{ old('access') }}">
									@error('access')
									<div class="invalid-feedback">{{$message}}</div>
									@enderror
								</div>
							</div>	
						</div>	
						<div class='row'>
							<div class="col-md-6 col-lg-6 col-xs-12">
								<div class="form-group">
									<label for="created_by">Created By:</label>
									<input type="text" class="form-control  @error('created_by') is-invalid @enderror" id="created_by" placeholder="Created By" name="created_by" readonly
										value="{{ Auth::user()->name }}">
									@error('created_by')
									<div class="invalid-feedback">{{$message}}</div>
									@enderror
								</div>
							</div>	
							<div class="col-md-6 col-lg-6 col-xs-12">
								<div class="form-group">
									<label for="modified_by">Modified By:</label>
									<input type="text" class="form-control  @error('modified_by') is-invalid @enderror" id="modified_by" placeholder="Modified By" name="modified_by" readonly
										value="{{ Auth::user()->name }}">
									@error('modified_by')
									<div class="invalid-feedback">{{$message}}</div>
									@enderror
								</div>
							</div>		
						</div>	
                    </div>
                    <!-- /.box-body -->
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary w-sm-100"><i class="fa fa-save"> </i> SAVE</button>
                        <a href="{{ url()->previous() }}" class="btn btn-danger"><i class="fa fa-undo"> </i> CANCEL</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection