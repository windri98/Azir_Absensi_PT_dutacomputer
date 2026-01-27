<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\ActivityController;
use App\Http\Controllers\Api\V1\AttendanceController;
use App\Http\Controllers\Api\V1\PartnerController;
use App\Http\Controllers\Api\V1\ReportController;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\HealthController;

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

// Health check routes - no auth required, not logged
Route::middleware('throttle:health')->group(function () {
    Route::get('/health', [HealthController::class, 'check']);
    Route::get('/health/ready', [HealthController::class, 'readiness']);
    Route::get('/health/live', [HealthController::class, 'liveness']);
    Route::get('/health/ping', [HealthController::class, 'simple']);
});

Route::prefix('v1')->group(function () {
    // Public routes - with rate limiting
    Route::post('/auth/login', [AuthController::class, 'login'])
        ->middleware('throttle:5,1'); // 5 attempts per minute

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
            Route::post('/push-token', [UserController::class, 'storePushToken']);
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
            Route::put('/{id}', [AttendanceController::class, 'update'])
                ->middleware('role_or_permission:attendance.edit_all');
        });

        // Partner routes
        Route::get('/partners', [PartnerController::class, 'index'])
            ->middleware('role_or_permission:partners.view');

        // Activity routes
        Route::prefix('activities')->group(function () {
            Route::get('/', [ActivityController::class, 'index'])
                ->middleware('role_or_permission:activities.view_own');
            Route::post('/', [ActivityController::class, 'store'])
                ->middleware('role_or_permission:activities.create');
            Route::get('/{id}', [ActivityController::class, 'show'])
                ->middleware('role_or_permission:activities.view_own');
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
