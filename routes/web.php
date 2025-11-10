<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('pages.welcome');
})->name('home');

Route::get('/welcome', function () {
    return view('pages.welcome');
})->name('welcome');

Route::get('/about', function () {
    return view('pages.about');
})->name('about');

Route::get('/contact', function () {
    return view('pages.contact');
})->name('contact');

Route::get('/help', function () {
    return view('pages.help');
})->name('help');

/*
|--------------------------------------------------------------------------
| Guest Routes (Auth)
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {
    // Admin-only Registration (create users)
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
        Route::post('/register', [AuthController::class, 'register'])->name('register.post');
    });

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | Profile Management
    |--------------------------------------------------------------------------
    */

    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'show'])->name('show');
        Route::get('/detail', [ProfileController::class, 'showDetail'])->name('detail');
        Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
        Route::post('/update', [ProfileController::class, 'update'])->name('update');
        Route::post('/update-photo', [ProfileController::class, 'updatePhoto'])->name('update-photo');
        Route::delete('/delete-photo', [ProfileController::class, 'deletePhoto'])->name('delete-photo');
    });

    // Change Password
    Route::get('/change-password', [AuthController::class, 'showChangePassword'])->name('change-password');
    Route::post('/change-password', [AuthController::class, 'changePassword'])->name('change-password.post');

    /*
    |--------------------------------------------------------------------------
    | Attendance Management
    |--------------------------------------------------------------------------
    */

    Route::prefix('attendance')->name('attendance.')->group(function () {
        // View Routes
        Route::get('/absensi', [AttendanceController::class, 'showAbsensi'])->name('absensi');
        Route::get('/clock-in', [AttendanceController::class, 'showClockIn'])->name('clock-in');
        Route::get('/clock-out', [AttendanceController::class, 'showClockOut'])->name('clock-out');
        Route::get('/clock-overtime', [AttendanceController::class, 'showClockOvertime'])->name('clock-overtime');
        Route::get('/qr-scan', [AttendanceController::class, 'showQrScan'])->name('qr-scan');
        Route::get('/riwayat', [AttendanceController::class, 'index'])->name('riwayat');

        // Action Routes
        Route::post('/check-in', [AttendanceController::class, 'checkIn'])->name('check-in');
        Route::post('/check-out', [AttendanceController::class, 'checkOut'])->name('check-out');
        Route::post('/submit-leave', [AttendanceController::class, 'submitLeave'])->name('submit-leave');

        // API Routes
        Route::get('/today-status', [AttendanceController::class, 'todayStatus'])->name('today-status');
        Route::get('/statistics', [AttendanceController::class, 'statistics'])->name('statistics');
    });

    // Legacy attendance routes (untuk backward compatibility)
    Route::get('/absensi', fn () => redirect()->route('attendance.absensi'));
    Route::get('/clock-in', fn () => redirect()->route('attendance.clock-in'));
    Route::get('/clock-out', fn () => redirect()->route('attendance.clock-out'));
    Route::get('/clock-overtime', fn () => redirect()->route('attendance.clock-overtime'));
    Route::get('/qr-scan', fn () => redirect()->route('attendance.qr-scan'));
    Route::get('/riwayat', fn () => redirect()->route('attendance.riwayat'));

    /*
    |--------------------------------------------------------------------------
    | Complaints Management
    |--------------------------------------------------------------------------
    */

    Route::prefix('complaints')->name('complaints.')->group(function () {
        // User Routes
        Route::get('/form', [ComplaintController::class, 'showForm'])->name('form');
        Route::post('/', [ComplaintController::class, 'store'])->name('store');
        Route::get('/history', [ComplaintController::class, 'history'])->name('history');
        Route::delete('/{id}', [ComplaintController::class, 'destroy'])->name('destroy');

        // Technician Routes (Admin/Manager only)
        Route::middleware(['role:admin,manager'])->group(function () {
            Route::get('/technician', [ComplaintController::class, 'technicianComplaints'])->name('technician');
            Route::post('/{id}/respond', [ComplaintController::class, 'respond'])->name('respond');
        });
    });

    // Activities - Pengajuan Izin
    Route::get('/activities/izin', [ComplaintController::class, 'showIzinPage'])->name('activities.izin');

    // Legacy complaint routes (untuk backward compatibility)
    Route::get('/complaint-form', fn () => redirect()->route('complaints.form'));
    Route::get('/complaint-history', fn () => redirect()->route('complaints.history'));
    Route::get('/technician-complaints', fn () => redirect()->route('complaints.technician'))->middleware(['role:admin,manager']);

    /*
    |--------------------------------------------------------------------------
    | Reports Management
    |--------------------------------------------------------------------------
    */

    Route::prefix('reports')->name('reports.')->group(function () {
        // User Routes
        Route::get('/history', [ReportController::class, 'history'])->name('history');

        // Admin/Manager Routes
        Route::middleware(['role:admin,manager'])->group(function () {
            Route::get('/', [ReportController::class, 'index'])->name('index');
            Route::get('/customer', [ReportController::class, 'customerReport'])->name('customer');
            Route::get('/export', [ReportController::class, 'export'])->name('export');
            Route::get('/summary', [ReportController::class, 'summary'])->name('summary');
        });
    });

    // Legacy report routes (untuk backward compatibility)
    Route::get('/report-history', fn () => redirect()->route('reports.history'));
    Route::middleware(['role:admin,manager'])->group(function () {
        Route::get('/laporan', fn () => redirect()->route('reports.index'));
        Route::get('/customer-report', fn () => redirect()->route('reports.customer'));
    });

    /*
    |--------------------------------------------------------------------------
    | Management Routes (Future Implementation)
    |--------------------------------------------------------------------------
    */

    Route::middleware(['role:admin,manager'])->prefix('management')->name('management.')->group(function () {
        Route::get('/shift', function () {
            return view('management.shift-management');
        })->name('shift');

        Route::get('/pengaturan', function () {
            return view('management.pengaturan');
        })->name('pengaturan');
    });

    /*
    |--------------------------------------------------------------------------
    | Admin Panel Routes
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        // Dashboard overview
        Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

        // User management
        Route::get('/users', [\App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [\App\Http\Controllers\Admin\UserController::class, 'create'])->name('users.create');
        Route::post('/users', [\App\Http\Controllers\Admin\UserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}/edit', [\App\Http\Controllers\Admin\UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [\App\Http\Controllers\Admin\UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [\App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('users.destroy');
        Route::get('/users/{user}/attendance', [\App\Http\Controllers\Admin\UserController::class, 'showAttendance'])->name('users.attendance');

        // Role assignment
        Route::post('/users/{user}/roles', [\App\Http\Controllers\Admin\UserController::class, 'assignRole'])->name('users.assign-role');
        Route::delete('/users/{user}/roles/{role}', [\App\Http\Controllers\Admin\UserController::class, 'removeRole'])->name('users.remove-role');

        // Reports & Export
        Route::get('/reports/export', [\App\Http\Controllers\Admin\ReportController::class, 'exportForm'])->name('reports.export');
        Route::get('/reports/preview', [\App\Http\Controllers\Admin\ReportController::class, 'preview'])->name('reports.preview');
        Route::get('/reports/download', [\App\Http\Controllers\Admin\ReportController::class, 'download'])->name('reports.download');

        // Legacy export route
        Route::get('/export-attendance', [\App\Http\Controllers\Admin\UserController::class, 'exportAttendance'])->name('export-attendance');

        // Shift management (placeholder, will implement after migration)
        Route::get('/shifts', [\App\Http\Controllers\Admin\ShiftController::class, 'index'])->name('shifts.index');
        Route::get('/shifts/create', [\App\Http\Controllers\Admin\ShiftController::class, 'create'])->name('shifts.create');
        Route::post('/shifts', [\App\Http\Controllers\Admin\ShiftController::class, 'store'])->name('shifts.store');
        Route::get('/shifts/{shift}/edit', [\App\Http\Controllers\Admin\ShiftController::class, 'edit'])->name('shifts.edit');
        Route::put('/shifts/{shift}', [\App\Http\Controllers\Admin\ShiftController::class, 'update'])->name('shifts.update');
        Route::delete('/shifts/{shift}', [\App\Http\Controllers\Admin\ShiftController::class, 'destroy'])->name('shifts.destroy');

        // Shift assignment
        Route::get('/shifts/{shift}/assign', [\App\Http\Controllers\Admin\ShiftController::class, 'assignForm'])->name('shifts.assign-form');
        Route::post('/shifts/{shift}/assign', [\App\Http\Controllers\Admin\ShiftController::class, 'assignUsers'])->name('shifts.assign-users');
        Route::delete('/shifts/{shift}/users/{user}', [\App\Http\Controllers\Admin\ShiftController::class, 'removeUser'])->name('shifts.remove-user');

        // Complaints/Izin Management
        Route::get('/complaints', [\App\Http\Controllers\Admin\ComplaintController::class, 'index'])->name('complaints.index');
        Route::get('/complaints/{complaint}', [\App\Http\Controllers\Admin\ComplaintController::class, 'show'])->name('complaints.show');
        Route::post('/complaints/{complaint}/approve', [\App\Http\Controllers\Admin\ComplaintController::class, 'approve'])->name('complaints.approve');
        Route::post('/complaints/{complaint}/reject', [\App\Http\Controllers\Admin\ComplaintController::class, 'reject'])->name('complaints.reject');
    });

    // Legacy management routes
    Route::get('/shift-management', fn () => redirect()->route('management.shift'))->middleware(['role:admin,manager']);
    Route::get('/pengaturan', fn () => redirect()->route('management.pengaturan'))->middleware(['role:admin,manager']);

    /*
    |--------------------------------------------------------------------------
    | Activities Routes (Future Implementation)
    |--------------------------------------------------------------------------
    */

    Route::prefix('activities')->name('activities.')->group(function () {
        Route::get('/aktifitas', function () {
            return view('activities.aktifitas');
        })->name('aktifitas');

        // Route izin sudah didefinisikan di atas (line 140)
    });

    // Legacy activity routes
    Route::get('/aktifitas', fn () => redirect()->route('activities.aktifitas'));
    Route::get('/izin', fn () => redirect()->route('activities.izin'));

    /*
    |--------------------------------------------------------------------------
    | Settings Routes (Future Implementation)
    |--------------------------------------------------------------------------
    */

    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/notifications', function () {
            return view('settings.notification-settings');
        })->name('notifications');
    });

    // Legacy settings routes
    Route::get('/notification-settings', fn () => redirect()->route('settings.notifications'));

    /*
    |--------------------------------------------------------------------------
    | API Routes (Internal)
    |--------------------------------------------------------------------------
    */

    Route::prefix('api')->name('api.')->group(function () {
        Route::get('/profile', [ProfileController::class, 'getProfile'])->name('profile');
    });

    // Reverse Geocode Proxy
    Route::get('/reverse-geocode', [\App\Http\Controllers\LocationController::class, 'reverseGeocode']);
});
