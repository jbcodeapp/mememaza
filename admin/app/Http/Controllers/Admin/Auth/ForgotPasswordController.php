<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request; 
use DB; 
use Carbon\Carbon;
use Mail; 
use Hash;
use Illuminate\Support\Str;
//use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */
	//https://www.itsolutionstuff.com/post/laravel-custom-forgot-reset-password-exampleexample.html
    //use SendsPasswordResetEmails;
	
	public function showForgetPasswordForm()
	{
		return view('admin.auth.forgetPassword');
	}
	
	public function submitForgetPasswordForm(Request $request)
	{
		  $request->validate([
			  'email' => 'required|email|exists:admins',
		  ]);

		  $token = Str::random(64);

		  DB::table('password_resets')->insert([
			  'email' => $request->email, 
			  'token' => $token, 
			  'created_at' => Carbon::now()
			]);

		  Mail::send('admin.auth.forgetPasswordLinkMail', ['token' => $token], function($message) use($request){
			  $message->to($request->email);
			  $message->subject('Reset Password');
		  });

		  return back()->with('message', 'We have e-mailed your password reset link!');
	}
	
	public function showResetPasswordForm($token)
	{
		$obj = DB::table('password_resets')->where('token', $token)->first();
		return view('admin.auth.forgetPasswordLink', ['token' => $token, 'obj' => $obj]);
	}
	
	public function submitResetPasswordForm(Request $request)
    {
          $request->validate([
              'email' => 'required|email|exists:admins',
              'password' => 'required|string|min:6|confirmed',
              'password_confirmation' => 'required'
          ]);
  
          $updatePassword = DB::table('password_resets')
                              ->where([
                                'email' => $request->email, 
                                'token' => $request->token
                              ])
                              ->first();
  
          if(!$updatePassword){
              return back()->withInput()->with('error', 'Invalid token!');
          }
  
          $user = DB::table('admins')->where('email', $request->email)
                      ->update(['password' => Hash::make($request->password)]);
 
          DB::table('password_resets')->where(['email'=> $request->email])->delete();
  
          return redirect('/')->with('message', 'Your password has been changed!');
    }
}
