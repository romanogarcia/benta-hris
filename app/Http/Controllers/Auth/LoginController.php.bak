<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Auth;
use App\User;
use App\Companies;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
	 protected function login(Request $request)
    {
        $this->validateLogin($request);
		$user = User::where(['email'=>$request->username])->first();
		$company = Companies::first(); 
        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
		 if(!empty($company) && $company->nx_login_failed == 1){
			if (method_exists($this, 'hasTooManyLoginAttempts') &&
				$this->hasTooManyLoginAttempts($request)) {
				$this->fireLockoutEvent($request);
				if(!empty($user)){
					$usr = User::findOrFail($user->id);
					$usr->is_locked = 1;
					$usr->save();
				}
				return $this->sendLockoutResponse($request);
			}
		 }
        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }
	
	protected function hasTooManyLoginAttempts(Request $request)
    {
		return $this->limiter()->tooManyAttempts(
			$this->throttleKey($request), 3
		);
    }
	 protected function sendLockoutResponse(Request $request)
    {
        $seconds = $this->limiter()->availableIn(
            $this->throttleKey($request)
        );

        throw ValidationException::withMessages([
            $this->username() => ['3 Login attempts failed. Your account has been locked. Please contact to support.'],
        ])->status(Response::HTTP_TOO_MANY_REQUESTS);
    }
	protected function authenticated(Request $request, $user)
	{
		//print_r($user->employee->is_active);die;
		//Check if user is approved
		$company = Companies::first(); 
		if(!$user->employee->is_active) {
			Auth::logout();
			return redirect()->back()->withErrors(['msg'=>'Sorry, Your account is deactivated.']);

		} 
		else if($company->nx_login_failed == 1 && $user->is_locked == 1) {
			Auth::logout();
			return redirect()->back()->withErrors(['msg'=>'Your account has been locked. Please contact to support.']);

		} 
		else {
			 //Redirect to the intended page after login.
		}
	}
    public function username (){
        $loginType = request()->input('username');
        $this->username = filter_var($loginType, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        request()->merge([$this->username => $loginType]);

        return property_exists($this, 'username') ? $this->username : 'email';
    }
}
