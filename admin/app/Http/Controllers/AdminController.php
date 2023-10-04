<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Auth, Validator, Redirect, Hash;
class AdminController extends Controller
{
	protected $guard = 'admin';
    use AuthenticatesUsers;
	protected $redirectTo = '/dashboard';
    public function __construct()
    {
		$this->middleware('guest:admin')->except('logout');
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('admin.auth.login');
    }
	
	protected function validator($data) {
        return Validator::make($data, [
            'email' => 'required|email|max:255',
            'password' => 'required',
        ]);
    }
	
	protected function hasTooManyLoginAttempts(Request $request) {
		$maxLoginAttempts = 5;
		$lockoutTime = 1; // In minutes
		return $this->limiter()->tooManyAttempts(
			$this->throttleKey($request), $maxLoginAttempts, $lockoutTime
		);
	}
	
	public function login(Request $request) {
		$this->validate($request, [
            'email'   => 'required|email',
            'password' => 'required|min:6'
        ]);
		
		$this->validateLogin($request);
		if($request->ajax()){
			// If the class is using the ThrottlesLogins trait, we can automatically throttle
			// the login attempts for this application. We'll key this by the username and
			// the IP address of the client making these requests into this application.
			if ($this->hasTooManyLoginAttempts($request)) {
				$this->fireLockoutEvent($request);
				//too many attatemps error message
				//return ['status' => 'a', 'redirect' => $this->sendLockoutResponse($request)];
				return $this->sendLockoutResponse($request);
			}
			
			if ($this->attemptLogin($request)) {
				//login success
				//return ['status' => 'b', 'redirect' => $this->sendLoginResponse($request)];
				return $this->sendLoginResponse($request);
			}
			// If the login attempt was unsuccessful we will increment the number of attempts
			// to login and redirect the user back to the login form. Of course, when this
			// user surpasses their maximum number of attempts they will get locked out.
			
			$this->incrementLoginAttempts($request);
			//return response()->json(['status' => 'error', 'message' => 'These credentials do not match our records.']);
			//These credentials do not match our records.
			//return ['status' => 'c', 'redirect' => $this->sendFailedLoginResponse($request)];
			return $this->sendFailedLoginResponse($request);
		} else {
			// If the class is using the ThrottlesLogins trait, we can automatically throttle
			// the login attempts for this application. We'll key this by the username and
			// the IP address of the client making these requests into this application.
			if ($this->hasTooManyLoginAttempts($request)) {
				$this->fireLockoutEvent($request);
				return $this->sendLockoutResponse($request);
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
	}
	
	protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();

        $this->clearLoginAttempts($request);

        return $this->authenticated($request, $this->guard()->user())
                ?: redirect()->intended('dashboard');
    }
	//$this->redirectPath()
	protected function validateLogin(Request $request) {
        $this->validate($request, [
            'email' => 'required|string',
            'password' => 'required|string',
        ]);
    }
	
	protected function guard() {
        return Auth::guard('admin');
    }

	protected function attemptLogin(Request $request)
    {
        return $this->guard()->attempt(
            $this->credentials($request), $request->filled('remember')
        );
    }
	
	public function logset() {
		return redirect('login');
	}
	
	public function logout(Request $request)
    {
		$this->guard()->logout();
		return redirect('/');
		
    }
}
