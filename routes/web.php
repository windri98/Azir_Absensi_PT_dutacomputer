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
    // Route::middleware(['role_or_permission:users.create'])->group(function () {
    //     Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    //     Route::post('/register', [AuthController::class, 'register'])->name('register.post');
    // });

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

    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/detail', [ProfileController::class, 'showDetail'])->name('profile.detail');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/update-photo', [ProfileController::class, 'updatePhoto'])->name('profile.update-photo');
    Route::delete('/profile/delete-photo', [ProfileController::class, 'deletePhoto'])->name('profile.delete-photo');

    // Change Password
    Route::get('/change-password', [AuthController::class, 'showChangePassword'])->name('change-password');
    Route::post('/change-password', [AuthController::class, 'changePassword'])->name('change-password.post');

    /*
    |--------------------------------------------------------------------------
    | Attendance Management
    |--------------------------------------------------------------------------
    */

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
    
    // File Management Routes
    Route::get('/document/{attendance}/download', [AttendanceController::class, 'downloadDocument'])->name('attendance.document.download');
    Route::get('/document/{attendance}/view', [AttendanceController::class, 'viewDocument'])->name('attendance.document.view');
    Route::delete('/document/{attendance}', [AttendanceController::class, 'deleteDocument'])->name('attendance.document.delete');

    /*
    |--------------------------------------------------------------------------
    | Complaints Management
    |--------------------------------------------------------------------------
    */

    // User Routes
    Route::get('/complaint-form', [ComplaintController::class, 'showForm'])->name('complaints.form');
    Route::post('/complaints', [ComplaintController::class, 'store'])->name('complaints.store');
    Route::get('/complaint-history', [ComplaintController::class, 'history'])->name('complaints.history');
    Route::delete('/complaints/{id}', [ComplaintController::class, 'destroy'])->name('complaints.destroy');

    // Technician Routes (Admin/Manager only)
    Route::middleware(['role:admin,manager'])->group(function () {
        Route::get('/technician-complaints', [ComplaintController::class, 'technicianComplaints'])->name('complaints.technician');
        Route::post('/complaints/{id}/respond', [ComplaintController::class, 'respond'])->name('complaints.respond');
    });

    /*
    |--------------------------------------------------------------------------
    | Reports Management
    |--------------------------------------------------------------------------
    */

    // User Routes
    Route::get('/report-history', [ReportController::class, 'history'])
        ->middleware('role_or_permission:reports.view_own')
        ->name('reports.history');

    // Admin/Manager Routes - using permission-based middleware
    Route::middleware('role_or_permission:reports.view')->group(function () {
        Route::get('/laporan', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/customer-report', [ReportController::class, 'customerReport'])->name('reports.customer');
        Route::get('/report-summary', [ReportController::class, 'summary'])->name('reports.summary');
        Route::get('/report-users', [ReportController::class, 'users'])->name('reports.users');
        Route::get('/report-user/{userId}', [ReportController::class, 'userDetail'])->name('reports.user-detail');
    });

    Route::get('/report-export', [ReportController::class, 'export'])
        ->middleware('role_or_permission:reports.export')
        ->name('reports.export');

    /*
    |--------------------------------------------------------------------------
    | Activities Routes
    |--------------------------------------------------------------------------
    */

    Route::get('/aktifitas', function () {
        $user = auth()->user();
        return view('activities.aktifitas', compact('user'));
    })->name('activities.aktifitas');

    Route::get('/izin', [ComplaintController::class, 'showIzinPage'])->name('activities.izin');

    /*
    |--------------------------------------------------------------------------
    | Management Routes
    |--------------------------------------------------------------------------
    */

    Route::middleware(['role:admin,manager'])->group(function () {
        Route::get('/shift-management', [ShiftController::class, 'management'])->name('management.shift');
        Route::get('/pengaturan', function () {
            return view('management.pengaturan');
        })->name('management.pengaturan');
        
        // Permissions Management
        Route::get('/permissions', [PermissionController::class, 'index'])->name('management.permissions');
        Route::get('/permissions/{permission}', [PermissionController::class, 'show'])->name('management.permissions.show');
    });

    /*
    |--------------------------------------------------------------------------
    | Admin Panel Routes
    |--------------------------------------------------------------------------
    */
    Route::middleware(['auth'])->group(function () {
        // Dashboard overview
        Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])
            ->middleware('role_or_permission:dashboard.admin')
            ->name('admin.dashboard');

        // User management
        Route::middleware('role_or_permission:users.view')->group(function () {
            Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users.index');
            Route::get('/admin/users/{user}/attendance', [UserController::class, 'showAttendance'])->name('admin.users.attendance');
        });

        Route::get('/admin/users/create', [UserController::class, 'create'])
            ->middleware('role_or_permission:users.create')
            ->name('admin.users.create');
        Route::post('/admin/users', [UserController::class, 'store'])
            ->middleware('role_or_permission:users.create')
            ->name('admin.users.store');
        Route::get('/admin/users/{user}/edit', [UserController::class, 'edit'])
            ->middleware('role_or_permission:users.edit')
            ->name('admin.users.edit');
        Route::put('/admin/users/{user}', [UserController::class, 'update'])
            ->middleware('role_or_permission:users.edit')
            ->name('admin.users.update');
        Route::delete('/admin/users/{user}', [UserController::class, 'destroy'])
            ->middleware('role_or_permission:users.delete')
            ->name('admin.users.destroy');

        // Role assignment
        Route::post('/admin/users/{user}/roles', [UserController::class, 'assignRole'])->name('admin.users.assign-role');
        Route::delete('/admin/users/{user}/roles/{role}', [UserController::class, 'removeRole'])->name('admin.users.remove-role');

        // Reports & Export
        Route::get('/admin/reports/export', [AdminReportController::class, 'exportForm'])->name('admin.reports.export');
        Route::get('/admin/reports/preview', [AdminReportController::class, 'preview'])->name('admin.reports.preview');
        Route::get('/admin/reports/download', [AdminReportController::class, 'download'])->name('admin.reports.download');
        Route::get('/admin/export-attendance', [UserController::class, 'exportAttendance'])->name('admin.export-attendance');

        // Shift management
        Route::middleware('role_or_permission:shifts.view')->group(function () {
            Route::get('/admin/shifts', [ShiftController::class, 'index'])->name('admin.shifts.index');
        });

        Route::get('/admin/shifts/create', [ShiftController::class, 'create'])
            ->middleware('role_or_permission:shifts.create')
            ->name('admin.shifts.create');
        Route::post('/admin/shifts', [ShiftController::class, 'store'])
            ->middleware('role_or_permission:shifts.create')
            ->name('admin.shifts.store');
        Route::get('/admin/shifts/{shift}/edit', [ShiftController::class, 'edit'])
            ->middleware('role_or_permission:shifts.edit')
            ->name('admin.shifts.edit');
        Route::put('/admin/shifts/{shift}', [ShiftController::class, 'update'])
            ->middleware('role_or_permission:shifts.edit')
            ->name('admin.shifts.update');
        Route::delete('/admin/shifts/{shift}', [ShiftController::class, 'destroy'])
            ->middleware('role_or_permission:shifts.delete')
            ->name('admin.shifts.destroy');

        // Shift assignment
        Route::get('/admin/shifts/{shift}/assign', [ShiftController::class, 'assignForm'])->name('admin.shifts.assign-form');
        Route::post('/admin/shifts/{shift}/assign', [ShiftController::class, 'assignUsers'])->name('admin.shifts.assign-users');
        Route::delete('/admin/shifts/{shift}/users/{user}', [ShiftController::class, 'removeUser'])->name('admin.shifts.remove-user');

        // Role Management
        Route::middleware('role_or_permission:roles.view')->group(function () {
            Route::get('/admin/roles', [RoleController::class, 'index'])->name('admin.roles.index');
            Route::get('/admin/roles/{role}', [RoleController::class, 'show'])->name('admin.roles.show');
        });

        Route::get('/admin/roles/create', [RoleController::class, 'create'])
            ->middleware('role_or_permission:roles.create')
            ->name('admin.roles.create');
        Route::post('/admin/roles', [RoleController::class, 'store'])
            ->middleware('role_or_permission:roles.create')
            ->name('admin.roles.store');
        Route::get('/admin/roles/{role}/edit', [RoleController::class, 'edit'])
            ->middleware('role_or_permission:roles.edit')
            ->name('admin.roles.edit');
        Route::put('/admin/roles/{role}', [RoleController::class, 'update'])
            ->middleware('role_or_permission:roles.edit')
            ->name('admin.roles.update');
        Route::delete('/admin/roles/{role}', [RoleController::class, 'destroy'])
            ->middleware('role_or_permission:roles.delete')
            ->name('admin.roles.destroy');
        
        // Role user assignment
        Route::post('/admin/roles/{role}/assign-users', [RoleController::class, 'assignUsers'])->name('admin.roles.assign-users');
        Route::delete('/admin/roles/{role}/users/{user}', [RoleController::class, 'removeUser'])->name('admin.roles.remove-user');

        // Role permission assignment
        Route::middleware('role_or_permission:roles.assign_permissions')->group(function () {
            Route::put('/admin/roles/{role}/permissions', [RoleController::class, 'updatePermissions'])->name('admin.roles.update-permissions');
            Route::post('/admin/roles/{role}/permissions', [RoleController::class, 'assignPermission'])->name('admin.roles.assign-permission');
            Route::delete('/admin/roles/{role}/permissions/{permission}', [RoleController::class, 'removePermission'])->name('admin.roles.remove-permission');
        });

        // Permission Management
        Route::get('/admin/permissions', [AdminPermissionController::class, 'index'])->name('admin.permissions.index');
        Route::get('/admin/permissions/matrix', [AdminPermissionController::class, 'matrix'])->name('admin.permissions.matrix');
        Route::get('/admin/permissions/capabilities', [AdminPermissionController::class, 'capabilities'])->name('admin.permissions.capabilities');

        // Complaints/Izin Management
        Route::get('/admin/complaints', [AdminComplaintController::class, 'index'])->name('admin.complaints.index');
        Route::get('/admin/complaints/{complaint}', [AdminComplaintController::class, 'show'])->name('admin.complaints.show');
        Route::post('/admin/complaints/{complaint}/approve', [AdminComplaintController::class, 'approve'])->name('admin.complaints.approve');
        Route::post('/admin/complaints/{complaint}/reject', [AdminComplaintController::class, 'reject'])->name('admin.complaints.reject');
        
        // Work Leave Management
        Route::get('/admin/work-leave', [AdminDashboardController::class, 'workLeaveRequests'])->name('admin.work-leave.index');
        Route::get('/admin/work-leave/{attendance}/detail', [AdminDashboardController::class, 'workLeaveDetail'])->name('admin.work-leave.detail');
        Route::post('/admin/work-leave/{attendance}/approve', function($attendance) {
            $attendanceModel = \App\Models\Attendance::findOrFail($attendance);
            return app(AdminDashboardController::class)->workLeaveAction($attendanceModel, 'approve');
        })->name('admin.work-leave.approve');
        Route::post('/admin/work-leave/{attendance}/reject', function($attendance) {
            $attendanceModel = \App\Models\Attendance::findOrFail($attendance);
            return app(AdminDashboardController::class)->workLeaveAction($attendanceModel, 'reject');
        })->name('admin.work-leave.reject');
    });

    /*
    |--------------------------------------------------------------------------
    | Settings Routes
    |--------------------------------------------------------------------------
    */

    Route::get('/notification-settings', function () {
        return view('settings.notification-settings');
    })->name('settings.notifications');

    /*
    |--------------------------------------------------------------------------
    | API Routes (Internal)
    |--------------------------------------------------------------------------
    */

    Route::get('/api/profile', [ProfileController::class, 'getProfile'])->name('api.profile');

    // Reverse Geocode Proxy
    Route::get('/reverse-geocode', [LocationController::class, 'reverseGeocode'])->name('reverse-geocode');
});

/*
|--------------------------------------------------------------------------
| Legacy Routes (Backward Compatibility) - REMOVED
| Sudah tidak diperlukan karena route utama sudah tanpa prefix
|--------------------------------------------------------------------------
*/