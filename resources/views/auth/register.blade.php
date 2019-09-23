@extends('layouts.master')

@section('content')
  <div class="content-wrapper d-flex align-items-stretch auth auth-img-bg">
    <div class="row flex-grow">
      <div class="col-lg-6 d-flex align-items-center justify-content-center">
        <div class="auth-form-transparent text-left p-3">
          <div class="brand-logo">
            <img src="{{ asset('images/bentach-big-1-1.png') }}" alt="bentacos-logo">
          </div>
          <h4>New here?</h4>
          <h6 class="font-weight-light">Join us today! It takes only few steps</h6>
          <br/>
          <form action="{{ route('register') }}" method="post">
            @csrf  
            
            <div class="form-group">
              <label>Name</label>
              <div class="input-group">
                <div class="input-group-prepend bg-transparent">
                  <span class="input-group-text bg-transparent border-right-0">
                    <i class="mdi mdi-account-outline text-primary"></i>
                  </span>
                </div>
                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror form-control-lg border-left-0" name="name" value="{{ old('name') }}" required autocomplete="off" placeholder="Name">

                @error('name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
              </div>
            </div>

            <div class="form-group">
              <label for="email">{{ __('E-Mail Address') }}</label>
              <div class="input-group">
                <div class="input-group-prepend bg-transparent">
                  <span class="input-group-text bg-transparent border-right-0">
                    <i class="mdi mdi-email-outline text-primary"></i>
                  </span>
                </div>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror  form-control-lg border-left-0" name="email" value="{{ old('email') }}" required autocomplete="off" placeholder="Email">

                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
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
                <input type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }} form-control-lg border-left-0" id="password" placeholder="Password" name="password" required>
                    @error('password'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @endif              
              </div>
            </div>

            <div class="form-group">
              <label for="password-confirm">Confirm Password</label>
              <div class="input-group">
                <div class="input-group-prepend bg-transparent">
                  <span class="input-group-text bg-transparent border-right-0">
                    <i class="mdi mdi-lock-outline text-primary"></i>
                  </span>
                </div>
                <input type="password" class="form-control form-control-lg border-left-0" id="password" placeholder="Confirm Password" name="password_confirmation" required>         
              </div>
            </div>

            <div class="mb-4">
              <div class="form-check">
                <label class="form-check-label text-muted">
                  <input type="checkbox" class="form-check-input">
                  I agree to all Terms & Conditions
                </label>
              </div>
            </div>

            <div class="mt-3">
              <button type="submit" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">SIGN UP</button>
            </div>

            <div class="text-center mt-4 font-weight-light">
              Already have an account? <a href="{{ route('login') }}" class="text-primary">Login</a>
            </div>

          </form>
        </div>
      </div>
      <div class="col-lg-6 login-half-bg d-flex flex-row img-overlay">
        <p class="text-white font-weight-medium text-center flex-grow align-self-end">Copyright &copy; <?php echo date('Y') ?>  Bentacos Intelligent Solution</p>
      </div>
  </div>
</div><!-- content-wrapper ends -->
@endsection