<?php

use App\Http\Controllers\AttendanceController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Attendance Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    // View Routes
    Route::get('/absensi', [AttendanceController::class, 'showAbsensi'])->name('attendance.absensi');
    Route::get('/clock-in', [AttendanceController::class, 'showClockIn'])->name('attendance.clock-in');
    Route::get('/clock-out', [AttendanceController::class, 'showClockOut'])->name('attendance.clock-out');
    Route::get('/clock-overtime', [AttendanceController::class, 'showClockOvertime'])->name('attendance.clock-overtime');
    Route::get('/qr-scan', [AttendanceController::class, 'showQrScan'])->name('attendance.qr-scan');
    Route::get('/riwayat', [AttendanceController::class, 'index'])->name('attendance.riwayat');

    // Action Routes
    Route::post('/check-in', [AttendanceController::class, 'checkIn'])->name('attendance.check-in');
    Route::post('/check-out', [AttendanceController::class, 'checkOut'])->name('attendance.check-out');
    Route::post('/submit-leave', [AttendanceController::class, 'submitLeave'])->name('attendance.submit-leave');

    // API Routes
    Route::get('/today-status', [AttendanceController::class, 'todayStatus'])->name('attendance.today-status');
    Route::get('/statistics', [AttendanceController::class, 'statistics'])->name('attendance.statistics');

    // Document Management
    Route::get('/document/{attendance}/download', [AttendanceController::class, 'downloadDocument'])->name('attendance.document.download');
    Route::get('/document/{attendance}/view', [AttendanceController::class, 'viewDocument'])->name('attendance.document.view');
    Route::delete('/document/{attendance}', [AttendanceController::class, 'deleteDocument'])->name('attendance.document.delete');
});
