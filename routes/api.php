<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Trip\TripController;
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
   Route::post('/trips/{id}/cancel', [TripController::class, 'cancel']);
   Route::post('/trips/{id}/activate', [TripController::class, 'activate']);
   Route::resource('/trips', TripController::class);
   Route::resource('/governorates', GovernorateController::class);
});