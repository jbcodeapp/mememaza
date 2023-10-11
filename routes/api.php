<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\IndexController;
use App\Http\Controllers\Api\AuthController;
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
/* 
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
}); */


Route::post('login',[LoginController::class,'login']);
Route::post('register',[LoginController::class,'register']);

Route::get('postbyslug/{slug?}',[IndexController::class,'postbyslug']);
Route::get('reelbyslug/{slug?}',[IndexController::class,'reelbyslug']);

Route::get('post/{slug?}',[IndexController::class,'index']);
Route::get('search/{search}',[IndexController::class,'search']);

Route::post('hlike',[AuthController::class,'handlelike']);
Route::get('loadcomment/{postid}/{pageid}',[IndexController::class,'loadcomment']);
Route::get('detail/{id}',[IndexController::class,'detail']);
Route::middleware(['auth:api'])->group(function(){
	//Route::get('post/{slug?}',[IndexController::class,'index']);
	Route::post('comment',[AuthController::class,'comment']);
	Route::post('logout',[LoginController::class,'logout']);
});
