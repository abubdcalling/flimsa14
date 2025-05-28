<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ContactMessageController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\PackageOrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\SeoController;
use App\Http\Controllers\SettingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GoogleReviewController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/* create by abu sayed (start)*/


// Auth routes
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::get('me', [AuthController::class, 'me'])->middleware('auth:api');
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:api');
// Route::post('refresh', [AuthController::class, 'refresh'])->middleware('auth:api');



Route::post('password/email', [AuthController::class, 'sendResetEmailLink']);
Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.reset');




//package info bronze(backend) which is namely packages
Route::middleware('auth:api')->group(function () {

    Route::post('/packageinfo/bronze', [PackageController::class, 'storeOrUpdateBronze']);
});


Route::get('/packageinfo/bronze', [PackageController::class, 'BronzeShow']);

//package info silver(backend) which is namely packages
Route::middleware('auth:api')->group(function () {

    Route::post('/packageinfo/silver', [PackageController::class, 'storeOrUpdateSilver']);
});

Route::get('/packageinfo/silver', [PackageController::class, 'SilverShow']);

//package info gold(backend) which is namely packages
Route::middleware('auth:api')->group(function () {

    Route::post('/packageinfo/gold', [PackageController::class, 'storeOrUpdateGold']);
});

Route::get('/packageinfo/gold', [PackageController::class, 'goldShow']);


//package order inserted from frontend which is namely pricing plan
Route::post('/package-order/{slug}', [PackageOrderController::class, 'store']);

//notification get from submitted from package orders which is namely notification in backend
Route::get('/notification', [PackageOrderController::class, 'index'])->middleware('auth:api');
Route::put('/notification', [PackageOrderController::class, 'update'])->middleware('auth:api');


//contact message get from frontend
Route::post('/contactMessage', [ContactMessageController::class, 'store']);


//packages(backend) which is namely Booking
Route::middleware('auth:api')->group(function () {
    Route::get('/package-order-shows', [PackageOrderController::class, 'allShow']);
});




//settings(backend) which is namely settings
Route::middleware('auth:api')->group(function () {
    Route::post('newSettings/email', [SettingController::class, 'updateEmail']);
    Route::post('newSettings/password', [SettingController::class, 'updatePassword']);
});

Route::get('/email', [AuthController::class, 'sendEmail']);


//blogs (backend) which is namely blogs
Route::middleware('auth:api')->group(function () {
    Route::apiResource('blogs', BlogController::class);
});
Route::get('/blog-data-front', [BlogController::class, 'getBlogData']);

Route::middleware('auth:api')->group(function () {
    Route::put('/blogsupdate/{id}', [BlogController::class, 'updates']);
});



Route::get('/google-reviews', [GoogleReviewController::class, 'getGoogleReviews']);



/* create by abu sayed (end)*/
