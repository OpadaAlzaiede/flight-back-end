<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Trip\TripController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Comment\CommentController;
use App\Http\Controllers\Governorates\GovernorateController;

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
Route::group(['middleware' => ['auth:sanctum']], function () {
   Route::post('user/changePassword', [UserController::class, 'changePassword']);
   Route::post('/trips/{id}/cancel', [TripController::class, 'cancel']);
   Route::post('/trips/{id}/activate', [TripController::class, 'activate']);
   Route::post('/trips/{id}/reserve', [TripController::class, 'reserve']);
   Route::post('/trips/{id}/leave', [TripController::class, 'leave']);
   Route::post('/trips/{id}/check', [TripController::class, 'checkAsArrived']);
   Route::post('/trips/{id}/addComment', [CommentController::class, 'store']);
   Route::resource('/trips', TripController::class);
   Route::resource('/governorates', GovernorateController::class);
   Route::delete('/comments/{id}', [CommentController::class, 'destroy']);
   Route::put('/comments/{id}', [CommentController::class, 'update']);
});