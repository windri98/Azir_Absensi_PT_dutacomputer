<?php

use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Reports Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    // Personal report history
    Route::get('/report-history', [ReportController::class, 'history'])
        ->middleware('role_or_permission:reports.view_own')
        ->name('reports.history');

    // Admin/Manager reports
    Route::middleware('role_or_permission:reports.view_all')->group(function () {
        Route::get('/laporan', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/customer-report', [ReportController::class, 'customerReport'])->name('reports.customer');
        Route::get('/report-summary', [ReportController::class, 'summary'])->name('reports.summary');
        Route::get('/report-users', [ReportController::class, 'users'])->name('reports.users');
        Route::get('/report-user/{userId}', [ReportController::class, 'userDetail'])->name('reports.user-detail');
    });

    // Export
    Route::get('/report-export', [ReportController::class, 'export'])
        ->middleware('role_or_permission:reports.export')
        ->name('reports.export');
});
