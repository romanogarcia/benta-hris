@extends('layouts.master')
@section('title', 'HRIS - Login')
@section('content')

<div class="content-wrapper d-flex align-items-stretch auth auth-img-bg">
    <div class="row flex-grow">
        <div class="col-lg-6 d-flex align-items-center justify-content-center">
        <div class="auth-form-transparent text-left p-3">
          <div class="brand-logo">
            <img src="{{ asset('images/bentach-big-1-1.png') }}" alt="bentacos-logo">
          </div>
            
			
          <h4>Welcome back!</h4>
			@if(@$message)
			<div class="alert alert-danger alert-block">
				<strong>{{ $message }}</strong>
			</div>
			@endif
          <form class="pt-3" action="{{ route('login') }}" method="post">
            @csrf

            <div class="form-group">
              <label for="username">Username / Email</label>
              <div class="input-group">
                <div class="input-group-prepend bg-transparent">
                  <span class="input-group-text bg-transparent border-right-0">
                    <i class="mdi mdi-account-outline text-primary"></i>
                  </span>
                </div>
                <input type="text" class="form-control form-control-lg border-left-0 @error('username') is-invalid @enderror" id="username" placeholder="Username/Email" name="username" value="{{ old('username') }}">
                
                @error('username')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
				@if($errors->any())
				  <span class="invalid-feedback" role="alert" style="display:block !important;">
                    <strong>{{$errors->first()}}</strong>
                  </span>
				@endif  
              </div>
            </div>

            <div class="form-group">
              <label for="password">Password</label>
              <div class="input-group">
                <div class="input-group-prepend bg-transparent">
                  <span class="input-group-text bg-transparent border-right-0">
                    <i class="mdi mdi-lock-outline text-primary"></i>
                  </span>
                </div>
                <input type="password" class="form-control form-control-lg border-left-0" id="password" placeholder="Password" name="password" required>
                
                    @error('password'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @endif
                                     
              </div>
            </div>

            <div class="my-2 d-flex justify-content-between align-items-center">
              <div class="form-check">
                <label class="form-check-label text-muted">
                  <input type="checkbox" class="form-check-input">
                  Keep me signed in
                </label>
              </div>
              <a href="{{ route('password.request') }}" class="auth-link text-black">Forgot password?</a>
            </div>

            <div class="my-3">
                <button type="submit" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">Login</button>
            </div>
          </form>
        </div>
      </div>
      <div class="col-lg-6 login-half-bg d-flex flex-row img-overlay">
        <p class="text-white font-weight-medium text-center flex-grow align-self-end">Copyright &copy; <?php echo date('Y') ?>  Bentacos Intelligent Solution</p>
      </div>
    </div>
</div>
@endsection