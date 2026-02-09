<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\OtpController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\DashboardController;
use App\Http\Controllers\Api\V1\GuruController;
use App\Http\Controllers\Api\V1\JurusanController;
use App\Http\Controllers\Api\V1\KelasController;
use App\Http\Controllers\Api\V1\KelasExtController;
use App\Http\Controllers\Api\V1\KontrolController;
use App\Http\Controllers\Api\V1\ProfilController;
use App\Http\Controllers\Api\V1\SiswaController;
use App\Http\Controllers\Api\V1\SkorController;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\WaliKelasController;

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
        Route::get('totalpoin', [DashboardController::class, 'totalpoin'])->middleware('auth:sanctum');
        Route::get('top10poin', [DashboardController::class, 'top10poin'])->middleware('auth:sanctum');
        Route::get('top10skor', [DashboardController::class, 'top10skor'])->middleware('auth:sanctum');
        Route::get('jenispoin', [DashboardController::class, 'jenispoin'])->middleware('auth:sanctum');
        Route::apiResource('siswa', SiswaController::class)->middleware('auth:sanctum');
        Route::get('siswa-list', [SiswaController::class, 'list'])->middleware('auth:sanctum');
        Route::apiResource('guru', GuruController::class)->middleware('auth:sanctum');
        Route::get('guru-list', [GuruController::class, 'list'])->middleware('auth:sanctum');
        Route::apiResource('walikelas', WaliKelasController::class)->middleware('auth:sanctum');
        Route::apiResource('kelas', KelasController::class)->middleware('auth:sanctum');
        Route::apiResource('kelas-ext', KelasExtController::class)->middleware('auth:sanctum');
        Route::apiResource('jurusan', JurusanController::class)->middleware('auth:sanctum');
        Route::apiResource('kontrol', KontrolController::class)->middleware('auth:sanctum');
        Route::apiResource('skor', SkorController::class)->middleware('auth:sanctum');
        Route::apiResource('user', UserController::class)->middleware('auth:sanctum');
        Route::get('rekapitulasi', [KontrolController::class, 'rekap'])->middleware('auth:sanctum');
        Route::apiResource('profil', ProfilController::class)->middleware('auth:sanctum');
});
