<?php

use App\Http\Controllers\ComplaintController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Leave & Complaints Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    // Leave/Izin Page
    Route::get('/izin', [ComplaintController::class, 'showIzinPage'])->name('leave.index');
    
    // Complaint/Leave Form
    Route::get('/complaint-form', [ComplaintController::class, 'showForm'])->name('complaints.form');
    Route::post('/complaints', [ComplaintController::class, 'store'])->name('complaints.store');
    Route::get('/complaint-history', [ComplaintController::class, 'history'])->name('complaints.history');
    Route::delete('/complaints/{id}', [ComplaintController::class, 'destroy'])->name('complaints.destroy');

    // Technician/Admin Routes
    Route::middleware(['role:admin,manager'])->group(function () {
        Route::get('/technician-complaints', [ComplaintController::class, 'technicianComplaints'])->name('complaints.technician');
        Route::post('/complaints/{id}/respond', [ComplaintController::class, 'respond'])->name('complaints.respond');
    });
});

// Legacy route alias
Route::middleware('auth')->get('/activities/izin', function () {
    return redirect()->route('leave.index');
});
