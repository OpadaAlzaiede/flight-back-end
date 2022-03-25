<?php

use App\Http\Controllers\Admin\AdminController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Trip\TripController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Comment\CommentController;
use App\Http\Controllers\Governorate\GovernorateController;
use App\Http\Controllers\Role\RoleController;

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

// public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// protected routes
Route::group(['middleware' => ['auth:sanctum', 'isApproved']], function () {

   Route::get('/profile', [UserController::class, 'profile']);
   Route::put('/profile/update', [UserController::class, 'updateProfile']);

   // User routes
   Route::post('user/changePassword', [UserController::class, 'changePassword']);

   // Role routes
   Route::resource('/roles', RoleController::class)->only(['index']);

   // Trip routes
   Route::post('/trips/{id}/cancel', [TripController::class, 'cancel']);
   Route::post('/trips/{id}/activate', [TripController::class, 'activate']);
   Route::post('/trips/{id}/reserve', [TripController::class, 'reserve']);
   Route::post('/trips/{id}/leave', [TripController::class, 'leave']);
   Route::post('/trips/{id}/check', [TripController::class, 'checkAsArrived']);
   Route::post('/trips/{id}/addComment', [CommentController::class, 'store']);
   Route::post('/trips/{id}/unreserve/{seat}', [TripController::class, 'unReserveSeat']);
   Route::resource('/trips', TripController::class);
   
   // Governorates routes
   Route::resource('/governorates', GovernorateController::class)->only(['index']);

   // Comments routes
   Route::delete('/comments/{id}', [CommentController::class, 'destroy']);
   Route::put('/comments/{id}', [CommentController::class, 'update']);

   // admin routes
   Route::prefix('admin')->group(function () {
      Route::group(['middleware' => 'isAdmin'], function() {
         Route::post('reset-user-data/{id}', [AdminController::class, 'resetUserData']);
         Route::post('approve-user/{id}', [AdminController::class, 'approveUser']);
         Route::post('disapprove-user/{id}', [AdminController::class, 'disApproveUser']);
         Route::get('/users', [AdminController::class, 'getAllUsers']);
      });
   });

});