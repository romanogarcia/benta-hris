@extends('layouts.master')
@section('title', 'User Settings')

@section('content')
<div class="content-wrapper">
	<div class="content">
		<div class="row">
			
			@if ($message = Session::get('success')) 
				<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="alert alert-success" role="alert">
					<i class="mdi mdi-alert-circle"></i>
					<strong>{{ $message }}</strong>
				</div>
				</div>	   
			@endif
			@if ($message = Session::get('error')) 
				<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="alert alert-danger" role="alert">
					<i class="mdi mdi-alert-circle"></i>
					<strong>{{ $message }}</strong>
				</div>
				</div>	   
			@endif
			
			<div class="col-md-6 col-sm-6 col-xs-12">
				<div class="card">
					<div class="card-header">Update Profile</div>
					<div class="card-body">

					<form method="POST" action="{{ url('/user/update_profile') }}" enctype="multipart/form-data" autocomplete="off">
						@csrf
						<input type="hidden" name="record_id" value="{{ Auth::user()->id }}">
					
						<!-- <div class="row form-group">
							<label class="col-md-3 col-sm-4 col-xs-12" for="full_name">Full Name</label>
							<div class="col-md-9 col-sm-8 col-xs-12">
								<input id="full_name" type="text" class="form-control @error('full_name') is-invalid @enderror form-control-sm" placeholder="Full Name" name="full_name" value="{{ Auth::user()->name }}" autocomplete off>
								@error('full_name')
									<span class="invalid-feedback" role="alert">
										<strong>{{ $message }}</strong>
									</span>
								@enderror
							</div>
						</div>	 -->
						<div class="row form-group">
							<label class="col-md-3 col-sm-4 col-xs-12" for="employee_number">Employee ID</label>
							<div class="col-md-9 col-sm-8 col-xs-12">
								<input id="employee_number" readonly="true" type="text" class="form-control @error('employee_number') is-invalid @enderror form-control-sm" placeholder="Employee Number" name="employee_number" value="{{ Auth::user()->employee->employee_number }}" autocomplete off>
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
								<input id="email" type="email" class="form-control @error('email') is-invalid @enderror form-control-sm" placeholder="Email" name="email" value="{{ Auth::user()->email }}" autocomplete off>
								@error('email')
									<span class="invalid-feedback" role="alert">
										<strong>{{ $message }}</strong>
									</span>
								@enderror
							</div>
						</div>	
						<div class="row form-group">
							<label class="col-md-3 col-sm-4 col-xs-12" for="username">Username</label>
							<div class="col-md-9 col-sm-8 col-xs-12">
								<input id="username" type="text" class="form-control @error('username') is-invalid @enderror form-control-sm" placeholder="Username" name="username" value="{{ Auth::user()->username }}" autocomplete off>
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
								<input id="phone" type="text" class="form-control @error('phone') is-invalid @enderror form-control-sm" placeholder="Phone" name="phone" value="{{ Auth::user()->employee['personal_phone'] }}" autocomplete off>
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
								<input id="language" type="text" class="form-control @error('language') is-invalid @enderror form-control-sm" placeholder="Language" name="language" value="{{ (Auth::user()->language != "")?Auth::user()->language:'English' }}" autocomplete off readonly>
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
								<input id="locale" type="text" class="form-control @error('locale') is-invalid @enderror form-control-sm" placeholder="Locale" name="locale" value="{{ (Auth::user()->locale != "")?Auth::user()->locale:'Zurich GMT+01.00' }}" autocomplete off readonly>
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
									<?php if(Auth::user()->employee['employee_image']){ ?>
										<img src="{{ get_profile_picture() }}" width="200" height="200" class="img-thumbnail" />
									<?php }else{ ?>
										<svg class="bd-placeholder-img img-thumbnail" width="200" height="200" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice" focusable="false" role="img"><rect width="100%" height="100%" fill="#868e96"></rect></svg>
										<rect width="100%" height="100%" fill="#868e96"></rect>
									<?php } ?>
								</div>
								<input type="file" id="profile_photo" name="profile_photo"  class="form-control @error('profile_photo') is-invalid @enderror form-control-sm" accept='image/*' />
								@error('profile_photo')
									<span class="invalid-feedback" role="alert">
										<strong>{{ $message }}</strong>
									</span>
								@enderror
							</div>
						</div>
						
						<div class="text-right">
							<button type="submit" class="btn btn-success"><i class="mdi mdi-check"></i>&nbsp;UPDATE</button>
						</div>
					
					</form>   
					</div>
				</div>
			</div>
			<div class="col-md-6 col-sm-6 col-xs-12 mrt-sm-5">
				<div class="card">
				<div class="card-header">Change Password</div>
				<div class="card-body">

				<form method="POST" action="{{ url('/user/change_password') }}" enctype="multipart/form-data" autocomplete="off">
					@csrf
					
					<div class="row form-group">
						<label class="col-md-3 col-sm-4 col-xs-12" for="phone">New Password</label>
						<div class="col-md-9 col-sm-8 col-xs-12">
							<input id="new_password" type="password" class="form-control @error('new_password') is-invalid @enderror form-control-sm" placeholder="New Password" name="new_password" value="{{ old('new_password') }}" autocomplete off>
							@error('new_password')
								<span class="invalid-feedback" role="alert">
									<strong>{{ $message }}</strong>
								</span>
							@enderror
						</div>
					</div>	
					<div class="row form-group">
						<label class="col-md-3 col-sm-4 col-xs-12" for="confirm_password">Confirm Password</label>
						<div class="col-md-9 col-sm-8 col-xs-12">
							<input id="confirm_password" type="password" class="form-control @error('confirm_password') is-invalid @enderror form-control-sm" placeholder="Confirm Password" name="confirm_password" value="{{ old('confirm_password') }}" autocomplete off>
							@error('confirm_password')
								<span class="invalid-feedback" role="alert">
									<strong>{{ $message }}</strong>
								</span>
							@enderror
						</div>
					</div>						
					<div class="text-right">
						<button type="submit" class="btn btn-success"><i class="mdi mdi-check"></i>&nbsp;UPDATE</button>
					</div>
				
				</form>  
				
				</div>
			</div>
			</div>	
		</div>
	</div> 
</div>
@endsection

@section('customjs')

@endsection


