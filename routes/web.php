<?php

use App\Http\Controllers\Admin\ComplaintController as AdminComplaintController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\PermissionController as AdminPermissionController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\ShiftController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DashboardRouterController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\PermissionController;
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
    Route::middleware(['role_or_permission:users.create'])->group(function () {
        Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
        Route::post('/register', [AuthController::class, 'register'])->name('register.post');
    });

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard Router - redirects to appropriate dashboard based on permissions
    Route::get('/dashboard', [DashboardRouterController::class, 'index'])->name('dashboard');
    
    // User Dashboard
    Route::get('/user/dashboard', [DashboardController::class, 'index'])
        ->middleware('role_or_permission:dashboard.view')
        ->name('user.dashboard');

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
        
        // File Management Routes
        Route::get('/document/{attendance}/download', [AttendanceController::class, 'downloadDocument'])->name('document.download');
        Route::get('/document/{attendance}/view', [AttendanceController::class, 'viewDocument'])->name('document.view');
        Route::delete('/document/{attendance}', [AttendanceController::class, 'deleteDocument'])->name('document.delete');
    });

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

    /*
    |--------------------------------------------------------------------------
    | Reports Management
    |--------------------------------------------------------------------------
    */

    Route::prefix('reports')->name('reports.')->group(function () {
        // User Routes
        Route::get('/history', [ReportController::class, 'history'])
            ->middleware('role_or_permission:reports.view_own')
            ->name('history');

        // Admin/Manager Routes - using permission-based middleware
        Route::middleware('role_or_permission:reports.view')->group(function () {
            Route::get('/', [ReportController::class, 'index'])->name('index');
            Route::get('/customer', [ReportController::class, 'customerReport'])->name('customer');
            Route::get('/summary', [ReportController::class, 'summary'])->name('summary');
            Route::get('/users', [ReportController::class, 'users'])->name('users');
            Route::get('/user/{userId}', [ReportController::class, 'userDetail'])->name('user-detail');
        });

        Route::get('/export', [ReportController::class, 'export'])
            ->middleware('role_or_permission:reports.export')
            ->name('export');
    });

    /*
    |--------------------------------------------------------------------------
    | Activities Routes
    |--------------------------------------------------------------------------
    */

    Route::prefix('activities')->name('activities.')->group(function () {
        Route::get('/aktifitas', function () {
            $user = auth()->user();
            return view('activities.aktifitas', compact('user'));
        })->name('aktifitas');

        Route::get('/izin', [ComplaintController::class, 'showIzinPage'])->name('izin');
    });

    /*
    |--------------------------------------------------------------------------
    | Management Routes
    |--------------------------------------------------------------------------
    */

    Route::middleware(['role:admin,manager'])->prefix('management')->name('management.')->group(function () {
        Route::get('/shift', [ShiftController::class, 'management'])->name('shift');
        Route::get('/pengaturan', function () {
            return view('management.pengaturan');
        })->name('pengaturan');
        
        // Permissions Management
        Route::get('/permissions', [PermissionController::class, 'index'])->name('permissions');
        Route::get('/permissions/{permission}', [PermissionController::class, 'show'])->name('permissions.show');
    });

    /*
    |--------------------------------------------------------------------------
    | Admin Panel Routes
    |--------------------------------------------------------------------------
    */
    Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
        // Dashboard overview
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])
            ->middleware('role_or_permission:dashboard.admin')
            ->name('dashboard');

        // User management
        Route::middleware('role_or_permission:users.view')->group(function () {
            Route::get('/users', [UserController::class, 'index'])->name('users.index');
            Route::get('/users/{user}/attendance', [UserController::class, 'showAttendance'])->name('users.attendance');
        });

        Route::get('/users/create', [UserController::class, 'create'])
            ->middleware('role_or_permission:users.create')
            ->name('users.create');
        Route::post('/users', [UserController::class, 'store'])
            ->middleware('role_or_permission:users.create')
            ->name('users.store');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])
            ->middleware('role_or_permission:users.edit')
            ->name('users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])
            ->middleware('role_or_permission:users.edit')
            ->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])
            ->middleware('role_or_permission:users.delete')
            ->name('users.destroy');

        // Role assignment
        Route::post('/users/{user}/roles', [UserController::class, 'assignRole'])->name('users.assign-role');
        Route::delete('/users/{user}/roles/{role}', [UserController::class, 'removeRole'])->name('users.remove-role');

        // Reports & Export
        Route::get('/reports/export', [AdminReportController::class, 'exportForm'])->name('reports.export');
        Route::get('/reports/preview', [AdminReportController::class, 'preview'])->name('reports.preview');
        Route::get('/reports/download', [AdminReportController::class, 'download'])->name('reports.download');
        Route::get('/export-attendance', [UserController::class, 'exportAttendance'])->name('export-attendance');

        // Shift management
        Route::middleware('role_or_permission:shifts.view')->group(function () {
            Route::get('/shifts', [ShiftController::class, 'index'])->name('shifts.index');
        });

        Route::get('/shifts/create', [ShiftController::class, 'create'])
            ->middleware('role_or_permission:shifts.create')
            ->name('shifts.create');
        Route::post('/shifts', [ShiftController::class, 'store'])
            ->middleware('role_or_permission:shifts.create')
            ->name('shifts.store');
        Route::get('/shifts/{shift}/edit', [ShiftController::class, 'edit'])
            ->middleware('role_or_permission:shifts.edit')
            ->name('shifts.edit');
        Route::put('/shifts/{shift}', [ShiftController::class, 'update'])
            ->middleware('role_or_permission:shifts.edit')
            ->name('shifts.update');
        Route::delete('/shifts/{shift}', [ShiftController::class, 'destroy'])
            ->middleware('role_or_permission:shifts.delete')
            ->name('shifts.destroy');

        // Shift assignment
        Route::get('/shifts/{shift}/assign', [ShiftController::class, 'assignForm'])->name('shifts.assign-form');
        Route::post('/shifts/{shift}/assign', [ShiftController::class, 'assignUsers'])->name('shifts.assign-users');
        Route::delete('/shifts/{shift}/users/{user}', [ShiftController::class, 'removeUser'])->name('shifts.remove-user');

        // Role Management
        Route::middleware('role_or_permission:roles.view')->group(function () {
            Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
            Route::get('/roles/{role}', [RoleController::class, 'show'])->name('roles.show');
        });

        Route::get('/roles/create', [RoleController::class, 'create'])
            ->middleware('role_or_permission:roles.create')
            ->name('roles.create');
        Route::post('/roles', [RoleController::class, 'store'])
            ->middleware('role_or_permission:roles.create')
            ->name('roles.store');
        Route::get('/roles/{role}/edit', [RoleController::class, 'edit'])
            ->middleware('role_or_permission:roles.edit')
            ->name('roles.edit');
        Route::put('/roles/{role}', [RoleController::class, 'update'])
            ->middleware('role_or_permission:roles.edit')
            ->name('roles.update');
        Route::delete('/roles/{role}', [RoleController::class, 'destroy'])
            ->middleware('role_or_permission:roles.delete')
            ->name('roles.destroy');
        
        // Role user assignment
        Route::post('/roles/{role}/assign-users', [RoleController::class, 'assignUsers'])->name('roles.assign-users');
        Route::delete('/roles/{role}/users/{user}', [RoleController::class, 'removeUser'])->name('roles.remove-user');

        // Role permission assignment
        Route::middleware('role_or_permission:roles.assign_permissions')->group(function () {
            Route::put('/roles/{role}/permissions', [RoleController::class, 'updatePermissions'])->name('roles.update-permissions');
            Route::post('/roles/{role}/permissions', [RoleController::class, 'assignPermission'])->name('roles.assign-permission');
            Route::delete('/roles/{role}/permissions/{permission}', [RoleController::class, 'removePermission'])->name('roles.remove-permission');
        });

        // Permission Management
        Route::get('/permissions', [AdminPermissionController::class, 'index'])->name('permissions.index');
        Route::get('/permissions/matrix', [AdminPermissionController::class, 'matrix'])->name('permissions.matrix');
        Route::get('/permissions/capabilities', [AdminPermissionController::class, 'capabilities'])->name('permissions.capabilities');

        // Complaints/Izin Management
        Route::get('/complaints', [AdminComplaintController::class, 'index'])->name('complaints.index');
        Route::get('/complaints/{complaint}', [AdminComplaintController::class, 'show'])->name('complaints.show');
        Route::post('/complaints/{complaint}/approve', [AdminComplaintController::class, 'approve'])->name('complaints.approve');
        Route::post('/complaints/{complaint}/reject', [AdminComplaintController::class, 'reject'])->name('complaints.reject');
        
        // Work Leave Management
        Route::prefix('work-leave')->name('work-leave.')->group(function () {
            Route::get('/', [AdminDashboardController::class, 'workLeaveRequests'])->name('index');
            Route::get('/{attendance}/detail', [AdminDashboardController::class, 'workLeaveDetail'])->name('detail');
            Route::post('/{attendance}/approve', function($attendance) {
                $attendanceModel = \App\Models\Attendance::findOrFail($attendance);
                return app(AdminDashboardController::class)->workLeaveAction($attendanceModel, 'approve');
            })->name('approve');
            Route::post('/{attendance}/reject', function($attendance) {
                $attendanceModel = \App\Models\Attendance::findOrFail($attendance);
                return app(AdminDashboardController::class)->workLeaveAction($attendanceModel, 'reject');
            })->name('reject');
        });
    });

    /*
    |--------------------------------------------------------------------------
    | Settings Routes
    |--------------------------------------------------------------------------
    */

    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/notifications', function () {
            return view('settings.notification-settings');
        })->name('notifications');
    });

    /*
    |--------------------------------------------------------------------------
    | API Routes (Internal)
    |--------------------------------------------------------------------------
    */

    Route::prefix('api')->name('api.')->group(function () {
        Route::get('/profile', [ProfileController::class, 'getProfile'])->name('profile');
    });

    // Reverse Geocode Proxy
    Route::get('/reverse-geocode', [LocationController::class, 'reverseGeocode'])->name('reverse-geocode');
});

/*
|--------------------------------------------------------------------------
| Legacy Routes (Backward Compatibility)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    // Attendance
    Route::get('/absensi', fn () => redirect()->route('attendance.absensi'))->name('absensi');
    Route::get('/clock-in', fn () => redirect()->route('attendance.clock-in'));
    Route::get('/clock-out', fn () => redirect()->route('attendance.clock-out'));
    Route::get('/clock-overtime', fn () => redirect()->route('attendance.clock-overtime'));
    Route::get('/qr-scan', fn () => redirect()->route('attendance.qr-scan'));
    Route::get('/riwayat', fn () => redirect()->route('attendance.riwayat'));

    // Complaints
    Route::get('/complaint-form', fn () => redirect()->route('complaints.form'));
    Route::get('/complaint-history', fn () => redirect()->route('complaints.history'));
    Route::get('/technician-complaints', fn () => redirect()->route('complaints.technician'))->middleware(['role:admin,manager']);

    // Reports
    Route::get('/report-history', fn () => redirect()->route('reports.history'));
    Route::get('/laporan', fn () => redirect()->route('reports.index'))->middleware('role_or_permission:reports.view');
    Route::get('/customer-report', fn () => redirect()->route('reports.customer'))->middleware('role_or_permission:reports.view');

    // Activities
    Route::get('/aktifitas', fn () => redirect()->route('activities.aktifitas'));
    Route::get('/izin', fn () => redirect()->route('activities.izin'));

    // Management
    Route::get('/shift-management', fn () => redirect()->route('management.shift'))->middleware(['role:admin,manager']);
    Route::get('/pengaturan', fn () => redirect()->route('management.pengaturan'))->middleware(['role:admin,manager']);

    // Settings
    Route::get('/notification-settings', fn () => redirect()->route('settings.notifications'));
});
