@extends('layouts.master')
@section('title', 'Reset Password')
@section('content')
    <div class="content-wrapper d-flex align-items-stretch auth auth-img-bg">
        <div class="row flex-grow">
            <div class="col-lg-6 d-flex align-items-center justify-content-center">
                <div class="auth-form-transparent text-left p-lg-3">
                    <div class="brand-logo">
                        <img src="{{ asset('images/bentach-big-1-1.png') }}" alt="bentacos-logo">
                    </div>
                    <h4>Create New Password</h4>
                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf
                        <input type="hidden" name="token" value="{{ $token }}">
                        <div class="form-group">
                            <label for="email" class="text-md-right">{{ __('E-Mail Address') }}</label>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                   name="email" value="{{ $email ?? old('email') }}" required autocomplete="email"
                                   readonly>
                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="password" class=" text-md-right">{{ __('New Password') }}</label>
                            <input id="password" type="password"
                                   class="form-control @error('password') is-invalid @enderror" name="password" required
                                   autocomplete="new-password">
                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="password-confirm" class="text-md-right">{{ __('Confirm New Password') }}</label>
                            <input id="password-confirm" type="password" class="form-control"
                                   name="password_confirmation" required autocomplete="new-password">
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                {{ __('Reset Password') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-lg-6 login-half-bg d-flex flex-row img-overlay">
                <p class="text-white font-weight-medium text-center flex-grow align-self-end">Copyright
                    &copy; <?php echo date('Y') ?> Bentacos Intelligent Solution</p>
            </div>
        </div>
    </div>
@endsection
