<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\OtpController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\DashboardController;
use App\Http\Controllers\Api\V1\GuruController;
use App\Http\Controllers\Api\V1\KontrolController;
use App\Http\Controllers\Api\V1\SiswaController;
use App\Http\Controllers\Api\V1\SkorController;
use App\Http\Controllers\Api\V1\UserController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/request-otp', [OtpController::class, 'requestOtp']);
Route::post('/verify-otp', [OtpController::class, 'verifyOtp']);
Route::prefix('v1')->group(function () {
        Route::post('login', [AuthController::class, 'login']);
        // Route::post('logout', [AuthController::class, 'logout']);
        Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);
        Route::post('register', [AuthController::class, 'register']);
        Route::post('lupa-password', [AuthController::class, 'lupa']);
        Route::apiResource('dashboard', DashboardController::class)->middleware('auth:sanctum');
        Route::apiResource('siswa', SiswaController::class)->middleware('auth:sanctum');
        Route::apiResource('guru', GuruController::class)->middleware('auth:sanctum');
        Route::apiResource('kontrol', KontrolController::class)->middleware('auth:sanctum');
        Route::apiResource('skor', SkorController::class)->middleware('auth:sanctum');
        Route::apiResource('user', UserController::class)->middleware('auth:sanctum');
        Route::get('rekapitulasi', [KontrolController::class, 'rekap'])->middleware('auth:sanctum');
});
