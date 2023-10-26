<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\VerifyEmailController;
use Illuminate\Http\Request;
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

Route::group(['prefix' => 'v1/user', 'namespace' => 'App\Http\Controllers\Api\V1'], function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::delete('destroy', [AuthController::class, 'destroy']);
    });

    // Email verification.
    Route::middleware(['throttle:6,1'])->group(function () {
        Route::get('email/verify/{id}/{hash}', [VerifyEmailController::class, 'validateEmail'])
            ->middleware(['signed'])
            ->name('verification.verify');

        Route::post('email/verify/resend', [VerifyEmailController::class, 'resendVerificationEmail'])
            ->middleware(['auth:sanctum'])
            ->name('verification.send');
    });
});


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('test', function () {
    return ['foo' => 'bar'];
});
