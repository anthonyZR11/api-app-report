<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::middleware('throttle:auth')->group(function () {
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware('api.auth', 'throttle:api')->group(function () {
  Route::post('/generate-report', [ReportController::class, 'generate']);
  Route::get('/list-reports', [ReportController::class, 'index']);
  Route::get('/get-report/{report_id}', [ReportController::class, 'download']);
});
