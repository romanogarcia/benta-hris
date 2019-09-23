@extends('layouts.master')
@section('title', 'User Management')
@section('content')
<div class="content-wrapper">
    <div class="content">
		@include('includes.messages')
		<div class="row">
			<div class="col-md-6">
				<div class="card">
					<div class="card-header">
						Edit User
					</div>
					<!-- /.box-header -->
					<div class="card-body">
						<!-- form start -->
						<form method="POST" enctype="multipart/form-data" action="{{ route('users.update', $user->id ) }}">
							@method('PUT')
							@csrf
							
							<div class="row form-group">
								<label class="col-md-3 col-sm-4 col-xs-12" for="employee_number">Employee ID</label>
								<div class="col-md-9 col-sm-8 col-xs-12">
									<input id="employee_number" readonly="true" type="text" class="form-control @error('employee_number') is-invalid @enderror form-control-sm" placeholder="Employee Number" name="employee_number" value="{{ $employee->employee_number }}" autocomplete off>
									@error('employee_number')
										<span class="invalid-feedback" role="alert">
											<strong>{{ $message }}</strong>
										</span>
									@enderror
								</div>
							</div>	
							
							<div class="row form-group">
								<label class="col-md-3 col-sm-4 col-xs-12" for="email">Email</label>
								<div class="col-md-9 col-sm-8 col-xs-12">
									<input id="email" type="email" class="form-control @error('email') is-invalid @enderror form-control-sm" placeholder="Email" name="email" value="{{ $employee->email }}" autocomplete off>
									@error('email')
										<span class="invalid-feedback" role="alert">
											<strong>{{ $message }}</strong>
										</span>
									@enderror
								</div>
							</div>	

							<div class="row form-group">
								<label class="col-md-3 col-sm-4 col-xs-12" for="name">Name</label>
								<div class="col-md-9 col-sm-8 col-xs-12">
									<input id="name" type="text" class="form-control @error('name') is-invalid @enderror form-control-sm" placeholder="Name" name="name" value="{{ $user->name }}" autocomplete off>
									@error('name')
										<span class="invalid-feedback" role="alert">
											<strong>{{ $message }}</strong>
										</span>
									@enderror
								</div>
							</div>

							<div class="row form-group">
								<label class="col-md-3 col-sm-4 col-xs-12" for="username">Username</label>
								<div class="col-md-9 col-sm-8 col-xs-12">
									<input id="username" type="text" class="form-control @error('username') is-invalid @enderror form-control-sm" placeholder="Username" name="username" value="{{ $user->username }}" autocomplete off>
									@error('username')
										<span class="invalid-feedback" role="alert">
											<strong>{{ $message }}</strong>
										</span>
									@enderror
								</div>
							</div>

							<div class="row form-group">
								<label class="col-md-3 col-sm-4 col-xs-12" for="phone">Phone</label>
								<div class="col-md-9 col-sm-8 col-xs-12">
									<input id="phone" type="text" class="form-control @error('phone') is-invalid @enderror form-control-sm" placeholder="Phone" name="phone" value="{{ $employee->personal_phone }}" autocomplete off>
									@error('phone')
										<span class="invalid-feedback" role="alert">
											<strong>{{ $message }}</strong>
										</span>
									@enderror
								</div>
							</div>	

							<div class="row form-group">
								<label class="col-md-3 col-sm-4 col-xs-12" for="language">Language</label>
								<div class="col-md-9 col-sm-8 col-xs-12">
									<input id="language" type="text" class="form-control @error('language') is-invalid @enderror form-control-sm" placeholder="Language" name="language" value="{{ ($user->language != "")?$user->language:'English' }}" autocomplete off readonly>
									@error('language')
										<span class="invalid-feedback" role="alert">
											<strong>{{ $message }}</strong>
										</span>
									@enderror
								</div>
							</div>	
							<div class="row form-group">
								<label class="col-md-3 col-sm-4 col-xs-12" for="locale">Locale</label>
								<div class="col-md-9 col-sm-8 col-xs-12">
									<input id="locale" type="text" class="form-control @error('locale') is-invalid @enderror form-control-sm" placeholder="Locale" name="locale" value="{{ ($user->locale != "")?$user->locale:'Zurich GMT+01.00' }}" autocomplete off readonly>
									@error('locale')
										<span class="invalid-feedback" role="alert">
											<strong>{{ $message }}</strong>
										</span>
									@enderror
								</div>
							</div>

							<div class="row form-group">
								<label class="col-md-3 col-sm-4 col-xs-12" for="profile_photo">Profile Photo</label>
								<div class="col-md-9 col-sm-8 col-xs-12">
									<div class="form-group">
										<img src="{{ get_profile_picture($employee->user_id) }}" width="200" height="200" class="img-thumbnail" />
									</div>
									<input type="file" id="profile_photo" name="profile_photo"  class="form-control @error('profile_photo') is-invalid @enderror form-control-sm" accept='image/*' />
									@error('profile_photo')
										<span class="invalid-feedback" role="alert">
											<strong>{{ $message }}</strong>
										</span>
									@enderror
								</div>
							</div>
							@if($company->nx_login_failed==1)	
							<div class="row form-group">
								<label class="col-md-3 col-sm-4 col-xs-12" ></label>
								<div class="col-md-9 col-sm-8 col-xs-12">
									<div class="form-check form-check-flat form-check-primary">
										  <label class="form-check-label">
											<input id="is_locked" type="checkbox" name="is_locked" value="1" {{($user->is_locked==1)?"checked":""}} autocomplete="off">
											Locked
										  <i class="input-helper"></i><i class="input-helper"></i></label>
									</div>
								</div>	
							</div>	
							@endif
							<div class="text-right">
								<button type="submit" class="btn btn-success w-sm-100"><i class="mdi mdi-check"></i>&nbsp;UPDATE</button>
							</div>
						</form>
					</div>
					
				</div>
			</div>
			<div class="col-md-6 mrt-sm-5">
				<div class="card">
					<div class="card-header">
						Change Password
					</div>
					<!-- /.box-header -->
					<!-- form start -->
					<form method="POST" action="{{ route('user.create_new_password') }}">
						<!-- @method('PUT') -->
						<input type="hidden" value="<?php echo $user->id; ?>" name="user_id">
						@csrf
						<div class="card-body">
							<input type="hidden" value="{{$user->id}}" name="user_id">

							<div class="row form-group">
								<label class="col-md-3 col-sm-4 col-xs-12" for="new_password">New Password</label>
								<div class="col-md-9 col-sm-8 col-xs-12">
									<input id="new_password" type="password" class="form-control @error('new_password') is-invalid @enderror form-control-sm" placeholder="******" name="new_password">
									@error('new_password')
										<span class="invalid-feedback" role="alert">
											<strong>{{ $message }}</strong>
										</span>
									@enderror
								</div>
							</div>

							<div class="text-right">
								<button type="submit" class="btn btn-success w-sm-100"><i class="mdi mdi-check"></i>&nbsp;UPDATE</button>
							</div>
						
						</div>
						<!-- /.box-body -->
					</form>
				</div>
			</div>
		</div>
		
    </div>
</div>
@endsection