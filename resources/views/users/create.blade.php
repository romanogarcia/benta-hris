@extends('layouts.master')
@section('title', 'User Management')
@section('content')
<div class="content-wrapper">
    <div class="content">
        <div class="container-fluid p-5">
      		 @include('includes.messages')
            <div class="card col-lg-12 col-xs-12 col-md-12">
                <div class="card-header with-border">
                    <h3 class="box-title">Add User</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form role="form" method="post" action="{{ url('dashboard/users/save') }}"
                    aria-label="{{ __('User') }}">
                    @csrf
                    <div class="card-body">
						<div class='row'>
							<div class="col-md-6 col-lg-6 col-xs-12">
								<div class="form-group">
									<label for="firstname">First Name:</label>
									<input type="text" class="form-control @error('firstname') is-invalid @enderror" id="firstname" name="firstname"  value="{{ old('firstname') }}" placeholder="First Name">
									@error('firstname')
									<div class="invalid-feedback">{{$message}}</div>
									@enderror
								</div>
							</div>	
							<div class="col-md-6 col-lg-6 col-xs-12">
								<div class="form-group">
									<label for="lastname">Last Name:</label>
									<input type="text" class="form-control  @error('lastname') is-invalid @enderror" id="lastname" placeholder="Last Name" name="lastname" value="{{ old('lastname') }}">
									@error('lastname')
									<div class="invalid-feedback">{{$message}}</div>
									@enderror
								</div>
							</div>	
						</div>	
						<div class='row'>
							<div class="col-md-6 col-lg-6 col-xs-12">
								<div class="form-group">
									<label for="username">User Name:</label>
									<input type="text" class="form-control @error('username') is-invalid @enderror" id="username" name="username"  value="{{ old('username') }}" placeholder="User Name">
									@error('username')
									<div class="invalid-feedback">{{$message}}</div>
									@enderror
								</div>
							</div>	
							<div class="col-md-6 col-lg-6 col-xs-12">
								<div class="form-group">
									<label for="email">Email:</label>
									<input type="email" class="form-control  @error('email') is-invalid @enderror" id="email" placeholder="Email" name="email"
										value="{{ old('email') }}">
									@error('email')
									<div class="invalid-feedback">{{$message}}</div>
									@enderror
								</div>
							</div>	
						</div>	
						<div class='row'>
							<div class="col-md-6 col-lg-6 col-xs-12">
								<div class="form-group">
									<label for="password">Password:</label>
									<input type="password" class="form-control  @error('password') is-invalid @enderror" id="password" placeholder="Password" name="password" >
									@error('password')
									<div class="invalid-feedback">{{$message}}</div>
									@enderror
								</div>
							</div>	
							<div class="col-md-6 col-lg-6 col-xs-12">
								<div class="form-group">
									<label for="confirm_password">Confirm Password:</label>
									<input type="password" class="form-control  @error('confirm_password') is-invalid @enderror" id="confirm_password" placeholder="Confirm Password" name="confirm_password" >
									@error('confirm_password')
									<div class="invalid-feedback">{{$message}}</div>
									@enderror
								</div>
							</div>		
						</div>	
						<div class='row'>
							<div class="col-md-6 col-lg-6 col-xs-12">
								<div class="form-group">
									<label for="user_roles">User Role:</label>
									<select name="user_roles" id="user_roles" class="form-control  @error('user_roles') is-invalid @enderror">
										<option value="" >Select</option>
										<?php if(!empty($roles)){
												foreach($roles as $role){
										?>
										<option value="{{ $role->id }}" {{ (old('user_roles') ==  $role->id)?"selected":"" }} >{{ $role->type }}</option> 
										<?php }} ?>
									</select>
									@error('user_roles')
									<div class="invalid-feedback">{{$message}}</div>
									@enderror
								</div>
							</div>
							<div class="col-md-6 col-lg-6 col-xs-12">
								<div class="form-group">
									<label for="status">Status:</label>
									<select name="status" id="status" class="form-control  @error('status') is-invalid @enderror">
										<option value="" >Select</option>
										<option value="1" {{ (old('status') ==  1)?"selected":"" }} >Active</option>
										<option value="0" {{ (old('status') ==  '0')?"selected":"" }} >Inactive</option>
										<option value="2" {{ (old('status') ==  2)?"selected":"" }} >Block</option>
									</select>
									@error('status')
									<div class="invalid-feedback">{{$message}}</div>
									@enderror
								</div>
							</div>	
						</div>	
                    </div>
                    <!-- /.box-body -->
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"> </i> SAVE</button>
                        <a href="{{ url()->previous() }}" class="btn btn-danger"><i class="fa fa-undo"> </i> CANCEL</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection