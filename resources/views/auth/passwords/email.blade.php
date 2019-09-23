@extends('layouts.master')
@section('title', 'Reset Password')
@section('content')
<div class="content-wrapper d-flex align-items-stretch auth auth-img-bg">
    <div class="row flex-grow">
        <div class="col-lg-6 d-flex align-items-center justify-content-center">
        <div class="auth-form-transparent text-left p-3">
          <div class="brand-logo">
            <img src="{{ asset('images/bentach-big-1-1.png') }}" alt="bentacos-logo">
          </div>
          <h4>Reset Password</h4>
          <form class="pt-3" action="{{ route('password.email') }}" method="post">
            @csrf
              @if (session('status'))
                  <div class="alert alert-success" role="alert">
                      {{ session('status') }}
                  </div>
              @endif

              <div class="form-group">
              <label for="email">Email</label>
              <div class="input-group">
                <div class="input-group-prepend bg-transparent">
                  <span class="input-group-text bg-transparent border-right-0">
                    <i class="mdi mdi-account-outline text-primary"></i>
                  </span>
                </div>
                <input type="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }} form-control-lg border-left-0 @error('email') is-invalid @enderror " id="email" placeholder="E-Mail" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                
                @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror

              </div>
              
            
            </div>

            <div class="my-3">
                <button type="submit" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">Send password reset link</button>
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