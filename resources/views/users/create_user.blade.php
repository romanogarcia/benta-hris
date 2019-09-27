@extends('layouts.master')
@section('title', 'User Management')
@section('content')
<div class="content-wrapper">
    <div class="content">
		@include('includes.messages')
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						Add User
					</div>
					<!-- /.box-header -->
					<div class="card-body">
						<!-- form start -->
						<form method="POST" enctype="multipart/form-data" action="{{ route('users.store') }}">
							@csrf
							<div class="row form-group">
								<label class="col-md-3 col-sm-4 col-xs-12" for="firstname">First Name</label>
								<div class="col-md-9 col-sm-8 col-xs-12">
									<input id="firstname" type="text" class="form-control @error('firstname') is-invalid @enderror form-control-sm" placeholder="First Name" name="firstname" value="{{old('firstname')}}" autocomplete off>
									@error('firstname')
										<span class="invalid-feedback" role="alert">
											<strong>{{ $message }}</strong>
										</span>
									@enderror
								</div>
							</div>
							<div class="row form-group">
								<label class="col-md-3 col-sm-4 col-xs-12" for="lastname">Last Name</label>
								<div class="col-md-9 col-sm-8 col-xs-12">
									<input id="lastname" type="text" class="form-control @error('lastname') is-invalid @enderror form-control-sm" placeholder="Last Name" name="lastname" value="{{old('lastname')}}" autocomplete off>
									@error('lastname')
										<span class="invalid-feedback" role="alert">
											<strong>{{ $message }}</strong>
										</span>
									@enderror
								</div>
							</div>
							<div class="row form-group">
								<label class="col-md-3 col-sm-4 col-xs-12" for="username">Username</label>
								<div class="col-md-9 col-sm-8 col-xs-12">
									<input id="username" type="text" class="form-control @error('username') is-invalid @enderror form-control-sm" placeholder="Username" name="username" value="{{old('username')}}" autocomplete off>
									@error('username')
										<span class="invalid-feedback" role="alert">
											<strong>{{ $message }}</strong>
										</span>
									@enderror
								</div>
							</div>	
							
							<div class="row form-group">
								<label class="col-md-3 col-sm-4 col-xs-12" for="email">Email</label>
								<div class="col-md-9 col-sm-8 col-xs-12">
									<input id="email" type="email" class="form-control @error('email') is-invalid @enderror form-control-sm" placeholder="Email" name="email" value="{{old('email')}}" autocomplete off>
									@error('email')
										<span class="invalid-feedback" role="alert">
											<strong>{{ $message }}</strong>
										</span>
									@enderror
								</div>
							</div>

							<div class="row form-group">
								<label class="col-md-3 col-sm-4 col-xs-12" for="phone">Phone</label>
								<div class="col-md-9 col-sm-8 col-xs-12">
									<input id="phone" type="text" class="form-control @error('phone') is-invalid @enderror form-control-sm" placeholder="Phone" name="phone" value="{{old('phone')}}" autocomplete off>
									@error('phone')
										<span class="invalid-feedback" role="alert">
											<strong>{{ $message }}</strong>
										</span>
									@enderror
								</div>
							</div>	
							<div class="row form-group">
								<label class="col-md-3 col-sm-4 col-xs-12" for="password">Password</label>
								<div class="col-md-9 col-sm-8 col-xs-12">
									<input id="password" type="password" class="form-control @error('password') is-invalid @enderror form-control-sm" placeholder="Password" name="password" value="{{old('password')}}" autocomplete off>
									@error('password')
										<span class="invalid-feedback" role="alert">
											<strong>{{ $message }}</strong>
										</span>
									@enderror
								</div>
							</div>
							<div class="row form-group">
								<label class="col-md-3 col-sm-4 col-xs-12" for="confirmpassword">Confirm Password</label>
								<div class="col-md-9 col-sm-8 col-xs-12">
									<input id="confirmpassword" type="password" class="form-control @error('confirmpassword') is-invalid @enderror form-control-sm" placeholder="Confirm Password" name="confirmpassword" value="{{old('confirmpassword')}}" autocomplete off>
									@error('confirmpassword')
										<span class="invalid-feedback" role="alert">
											<strong>{{ $message }}</strong>
										</span>
									@enderror
								</div>
							</div>

							<div class="row form-group">
								<label class="col-md-3 col-sm-4 col-xs-12" for="language">Language</label>
								<div class="col-md-9 col-sm-8 col-xs-12">
									<input id="language" type="text" class="form-control @error('language') is-invalid @enderror form-control-sm" placeholder="Language" name="language" value="English" autocomplete off readonly>
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
									<input id="locale" type="text" class="form-control @error('locale') is-invalid @enderror form-control-sm" placeholder="Locale" name="locale" value="Zurich GMT+01.00" autocomplete off readonly>
									@error('locale')
										<span class="invalid-feedback" role="alert">
											<strong>{{ $message }}</strong>
										</span>
									@enderror
								</div>
							</div>
							<div class="row form-group">
								<label class="col-md-3 col-sm-4 col-xs-12" for="department">Department:</label>
								<div class="col-md-9 col-sm-8 col-xs-12">
									<select name="department" id="department" class="form-control @error('department') is-invalid @enderror form-control-sm">
										<option value="" >Select</option>
										<?php if(!empty($departments)){
												foreach($departments as $department){
										?>
										<option value="{{ $department->id }}" {{ (old('department') ==  $department->id)?"selected":"" }} >{{ $department->name }}</option> 
										<?php }} ?>
									</select>
									@error('department')
										<span class="invalid-feedback" role="alert">
											<strong>{{ $message }}</strong>
										</span>
									@enderror
								</div>
							</div>
							<div class="row form-group">
								<label class="col-md-3 col-sm-4 col-xs-12" for="profile_photo">Profile Photo</label>
								<div class="col-md-9 col-sm-8 col-xs-12">
									<input type="file" id="profile_photo" name="profile_photo"  class="form-control @error('profile_photo') is-invalid @enderror form-control-sm" accept='image/*' value=""/>
									@error('profile_photo')
										<span class="invalid-feedback" role="alert">
											<strong>{{ $message }}</strong>
										</span>
									@enderror
								</div>
							</div>
							
							<div class="text-right">
								<button type="submit" class="btn btn-success w-sm-100"><i class="mdi mdi-check"></i>&nbsp;Add</button>
							</div>
						</form>
					</div>
					
				</div>
			</div>
			
		</div>
		
    </div>
</div>
@endsection