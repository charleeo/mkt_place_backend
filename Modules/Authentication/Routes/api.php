<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Authentication\Http\Controllers\AuthenticationController;

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

Route::middleware('auth:api')->get('/authentication', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => "auth"], function () {

    Route::post("/login", [AuthenticationController::class, "login"]);

    Route::post("/send/reset/link", [AuthenticationController::class, "sendResetLink"])->name('password.sent');

    Route::get("/reset/password", [AuthenticationController::class, "resetPassword"])->name('password.reset');
});
