<?php

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\RegisterController;
use Illuminate\Support\Facades\Route;

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
Route::group(['prefix' => 'auth'], function () {
  Route::post('login', [AuthenticationController::class, 'login']);
  Route::post('register', [RegisterController::class, 'register']);

  Route::group(['middleware' => 'api'], function () {
    Route::get('user', [AuthenticationController::class, 'getAuthenticatedUser']);
    Route::post('logout', [AuthenticationController::class, 'logout']);
  });
});
