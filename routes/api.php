<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method, Authorization");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('login', function(Request $request){
	if(auth()->attempt(['email' => $request->input('email'), 'password' => $request->input('password')])) {
		// if authentication passed..
		$user = auth()->user;
		$user->api_token = str_random(60);
		$user->save();
		return $user;
	}return response()->json([
		'error' => 'Sino kang user ka?',
		'code' => 401,
	], 401);
});

Route::middleware('auth:api')->post('logout', function (Request $request) {
	if (auth()->user()) {
		$user = auth()->user();
		$user->api_token = null;
		$user->save();

		return response()->json([
			'message' => 'Logout Success',
		]);
	}

	return respons()->json([
		'error' => 'Logut failed',
		'code' => 401,
	], 401);
});