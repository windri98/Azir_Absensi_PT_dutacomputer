<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\AttendanceController;
use App\Http\Controllers\Api\V1\ReportController;
use App\Http\Controllers\Api\V1\UserController;

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

Route::prefix('v1')->group(function () {
    // Public routes
    Route::post('/auth/login', [AuthController::class, 'login']);

    // Protected routes
    Route::middleware('auth:sanctum')->group(function () {
        // Auth routes
        Route::get('/auth/me', [AuthController::class, 'me']);
        Route::post('/auth/logout', [AuthController::class, 'logout']);

        // User routes
        Route::prefix('users')->group(function () {
            Route::get('/profile', [UserController::class, 'profile']);
            Route::put('/profile', [UserController::class, 'updateProfile']);
            Route::post('/change-password', [UserController::class, 'changePassword']);
            Route::post('/upload-photo', [UserController::class, 'uploadPhoto']);
            Route::get('/', [UserController::class, 'index']);
            Route::get('/{id}', [UserController::class, 'show']);
            Route::post('/', [UserController::class, 'store']);
            Route::put('/{id}', [UserController::class, 'update']);
            Route::delete('/{id}', [UserController::class, 'destroy']);
        });

        // Attendance routes
        Route::prefix('attendances')->group(function () {
            Route::get('/', [AttendanceController::class, 'index']);
            Route::get('/today', [AttendanceController::class, 'today']);
            Route::get('/statistics', [AttendanceController::class, 'statistics']);
            Route::post('/check-in', [AttendanceController::class, 'checkIn']);
            Route::post('/check-out', [AttendanceController::class, 'checkOut']);
            Route::get('/{id}', [AttendanceController::class, 'show']);
            Route::put('/{id}', [AttendanceController::class, 'update']);
        });

        // Report routes
        Route::prefix('reports')->group(function () {
            Route::get('/personal', [ReportController::class, 'personalReport']);
            Route::get('/all-users', [ReportController::class, 'allUsersReport']);
            Route::get('/user/{userId}', [ReportController::class, 'userReport']);
            Route::get('/export/personal/pdf', [ReportController::class, 'exportPersonalPdf']);
            Route::get('/export/personal/csv', [ReportController::class, 'exportPersonalCsv']);
            Route::get('/export/all/pdf', [ReportController::class, 'exportAllPdf']);
            Route::get('/export/all/csv', [ReportController::class, 'exportAllCsv']);
        });
    });
});
