<?php
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\VideoUploadController;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\IndexController;
use App\Http\Controllers\Admin\Auth\ForgotPasswordController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/* Route::get('/', function () {
    return view('welcome');
}); */

Auth::routes();

Route::get('/upload', [VideoUploadController::class, 'showUploadForm']);
Route::post('/upload', [VideoUploadController::class, 'storeUploads']);

Route::get('/home', [DashboardController::class, 'dashboard'])->name('home');
Route::get('/register', [AdminController::class, 'logset']);
Route::get('/login', [AdminController::class, 'logset']);
// Admin Routes

Route::get('/forget-password', [ForgotPasswordController::class, 'showForgetPasswordForm'])->name('forget.password.get');
Route::post('/forget-password', [ForgotPasswordController::class, 'submitForgetPasswordForm'])->name('forget.password.post'); 
Route::get('/reset-password/{token}', [ForgotPasswordController::class, 'showResetPasswordForm'])->name('reset.password.get');
Route::post('/reset-password', [ForgotPasswordController::class, 'submitResetPasswordForm'])->name('reset.password.post');

//Route::group(['prefix' => ADMIN_PREFIX], function() {
//Route::group(['prefix' => 'admin'], function() {
	
	Route::get('/', [AdminController::class, 'index'])->name('login');
	Route::post('/alogin', [AdminController::class, 'login'])->name('admin.login');
	Route::get('/dashboard', [DashboardController::class, 'dashboard']);
	
	Route::get('/categories', [DashboardController::class, 'categories']);
	Route::get('/category_ajax', [DashboardController::class, 'category_ajax']);
	Route::get('/category_form/{id}', [DashboardController::class, 'category_form']);
	Route::post('/category_handle', [DashboardController::class, 'category_handle']);
	
	Route::get('/reels', [IndexController::class, 'index']);
	Route::get('/reel_ajax', [IndexController::class, 'reel_ajax']);
	Route::get('/reel_form/{id}', [IndexController::class, 'reel_form']);
	Route::post('/reel_handle', [IndexController::class, 'reel_handle']);
	
	Route::get('/post', [IndexController::class, 'post']);
	Route::get('/post_ajax', [IndexController::class, 'post_ajax']);
	Route::get('/post_form/{id}', [IndexController::class, 'post_form']);
	Route::post('/post_handle', [IndexController::class, 'post_handle']);
	
	Route::get('/banner', [IndexController::class, 'banner']);
	Route::get('/banner_ajax', [IndexController::class, 'banner_ajax']);
	Route::get('/banner_form/{id}', [IndexController::class, 'banner_form']);
	Route::post('/banner_handle', [IndexController::class, 'banner_handle']);
	
	Route::get('/story', [IndexController::class, 'story']);
	Route::get('/story_ajax', [IndexController::class, 'story_ajax']);
	Route::get('/story_form/{id}', [IndexController::class, 'story_form']);
	Route::post('/story_handle', [IndexController::class, 'story_handle']);
	
	Route::get('/changepassword', [DashboardController::class, 'changepassword']);
	Route::post('/updatechangepassword', [DashboardController::class, 'updatechangepassword']);
	
	Route::post('/logout', [AdminController::class, 'logout'])->name('admin.logout');
	/* 
	
	
	
	
	
	
	
	Route::post('/category_handle_attribute', [DashboardController::class, 'category_handle_attribute']);
	Route::get('/get_category_level/{category_id}/{level}', [DashboardController::class, 'get_category_level']); */
	

//});
