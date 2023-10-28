<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
//use App\User;
use Validator, Auth, DB;

class LoginController extends Controller
{

	public function login(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'email' => 'required|email',
			'password' => 'required',
		]);

		if ($validator->fails()) {
			return response()->json(['statuscode' => false, 'errors' => $validator->errors()->all()]);
		}
		$credentials = ['email' => $request->email, 'password' => $request->password];

		if (auth()->attempt($credentials)) {
			$userobj = auth()->user();
			$count = DB::table('oauth_access_tokens')->where('user_id', $userobj->id)->count();
			if ($count > 0) {
				DB::table('oauth_access_tokens')->where('user_id', $userobj->id)->delete();
			}
			$rand = Hash::make($userobj->id . 'Reels' . $userobj->email);
			$token = auth()->user()->createToken($rand)->accessToken;

			return response()->json(['statuscode' => true, 'token' => $token, 'user' => $userobj, 'message' => 'Successfully Login'], 200);
		}

		return response()->json(['statuscode' => false, 'errors' => ['Invalid Credentials']]);
	}

	public function register(Request $request)
	{
		try {
			$validator = Validator::make($request->all(), [
				'name' => 'required|string|max:255',
				'email' => 'required|unique:users|email',
				'password' => 'required'
			]);

			if ($validator->fails()) {
				return response()->json(['errors' => $validator->errors()->all()])->setStatusCode(200);
			}

			$regionManager = \App\Components\Api\CommonManager::getInstance();

			$params = [
				'name' => $request->name,
				'email' => $request->email,
				'token' => rand(0, 40) . time(),
				'status' => 1,
				'password' => Hash::make($request->password),
			];

			//echo '<pre>'; print_r($params); die;

			$user = \App\Models\User::create($params);

			if ($user) {

				return response()->json(['statuscode' => true, 'message' => 'Congratulations! your account is registered.']);
			}

			return response()->json(['errors' => ['Try again']])->setStatusCode(400);
		} catch (Exception $e) {
			return response()->json(['errors' => ['Try again']])->setStatusCode(500);
		}
	}

	public function logout()
	{
		$user = auth()->user();
		//return response()->json(array('statuscode' => true, 'message' => 'successfully logout'), 200);
		$accessToken = $user->token();
		$accessToken->revoke();
		DB::table('oauth_access_tokens')->where('user_id', $user->id)->delete();
		return response()->json(array('statuscode' => true, 'message' => 'successfully logout'), 200);
	}
}
