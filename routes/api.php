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
Route::get('stories',[IndexController::class,'stories']);
Route::get('getpostbyslug/{slug?}',[IndexController::class,'postbyslug']); 
Route::get('getreelbyslug/{slug?}',[IndexController::class,'reelbyslug']);

Route::get('postdownload/{slug?}',[IndexController::class,'postdownload']);
Route::get('postshare/{slug?}',[IndexController::class,'postshare']);
Route::get('postview/{slug?}',[IndexController::class,'postview']);
Route::get('postlike/{slug?}',[IndexController::class,'postlike']);
Route::get('postcomment/{slug?}',[IndexController::class,'postcomment']);

Route::post('updatedownload',[IndexController::class,'updatedownload']);
Route::post('updateshare',[IndexController::class,'updateshare']);
Route::post('updatelike',[IndexController::class,'updatelike']);
Route::post('updatedislike',[IndexController::class,'updatedislike']);


Route::get('post/{slug?}',[IndexController::class,'index']);
Route::get('search/{search}',[IndexController::class,'search']);

Route::post('hlike',[AuthController::class,'handlelike']);
Route::get('loadcomment/{postid}/{pageid}',[IndexController::class,'loadcomment']);
Route::get('detail/{id}',[IndexController::class,'detail']);

Route::middleware(['auth:api'])->group(function(){
	Route::get('check-auth',[AuthController::class,'checkauth']);
	Route::post('updatelike',[IndexController::class,'updatelike']);
	Route::post('updatedislike',[IndexController::class,'updatedislike']);
	Route::post('comment',[AuthController::class,'comment']);
	Route::post('deletecomment',[AuthController::class,'deletecomment']);
	Route::post('logout',[LoginController::class,'logout']);
});
