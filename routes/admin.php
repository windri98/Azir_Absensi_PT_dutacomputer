<?php

use App\Http\Controllers\Admin\ComplaintController as AdminComplaintController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ActivityController as AdminActivityController;
use App\Http\Controllers\Admin\PartnerController;
use App\Http\Controllers\Admin\PermissionController as AdminPermissionController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\ShiftController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\PermissionController;
use App\Models\Attendance;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Panel Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])
        ->middleware('role_or_permission:dashboard.admin')
        ->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | User Management
    |--------------------------------------------------------------------------
    */
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
    Route::post('/users/{user}/roles', [UserController::class, 'assignRole'])
        ->middleware('role_or_permission:users.manage_roles')
        ->name('users.assign-role');
    Route::delete('/users/{user}/roles/{role}', [UserController::class, 'removeRole'])
        ->middleware('role_or_permission:users.manage_roles')
        ->name('users.remove-role');

    /*
    |--------------------------------------------------------------------------
    | Role Management
    |--------------------------------------------------------------------------
    */
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
    Route::middleware('role_or_permission:roles.view')->group(function () {
        Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
        Route::get('/roles/{role}', [RoleController::class, 'show'])->name('roles.show');
    });

    // Role user & permission assignment
    Route::post('/roles/{role}/assign-users', [RoleController::class, 'assignUsers'])
        ->middleware('role_or_permission:users.manage_roles')
        ->name('roles.assign-users');
    Route::delete('/roles/{role}/users/{user}', [RoleController::class, 'removeUser'])
        ->middleware('role_or_permission:users.manage_roles')
        ->name('roles.remove-user');

    Route::middleware('role_or_permission:roles.assign_permissions')->group(function () {
        Route::put('/roles/{role}/permissions', [RoleController::class, 'updatePermissions'])->name('roles.update-permissions');
        Route::post('/roles/{role}/permissions', [RoleController::class, 'assignPermission'])->name('roles.assign-permission');
        Route::delete('/roles/{role}/permissions/{permission}', [RoleController::class, 'removePermission'])->name('roles.remove-permission');
    });

    /*
    |--------------------------------------------------------------------------
    | Shift Management
    |--------------------------------------------------------------------------
    */
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
    Route::get('/shifts/{shift}/assign', [ShiftController::class, 'assignForm'])
        ->middleware('role_or_permission:shifts.assign_users')
        ->name('shifts.assign-form');
    Route::post('/shifts/{shift}/assign', [ShiftController::class, 'assignUsers'])
        ->middleware('role_or_permission:shifts.assign_users')
        ->name('shifts.assign-users');
    Route::delete('/shifts/{shift}/users/{user}', [ShiftController::class, 'removeUser'])
        ->middleware('role_or_permission:shifts.assign_users')
        ->name('shifts.remove-user');

    /*
    |--------------------------------------------------------------------------
    | Permission Management
    |--------------------------------------------------------------------------
    */
    Route::get('/permissions', [AdminPermissionController::class, 'index'])
        ->middleware('role_or_permission:roles.view')
        ->name('permissions.index');
    Route::get('/permissions/matrix', [AdminPermissionController::class, 'matrix'])
        ->middleware('role_or_permission:roles.view')
        ->name('permissions.matrix');
    Route::get('/permissions/capabilities', [AdminPermissionController::class, 'capabilities'])
        ->middleware('role_or_permission:roles.view')
        ->name('permissions.capabilities');

    /*
    |--------------------------------------------------------------------------
    | Complaints Management
    |--------------------------------------------------------------------------
    */
    Route::get('/complaints', [AdminComplaintController::class, 'index'])
        ->middleware('role_or_permission:complaints.view_all')
        ->name('complaints.index');
    Route::get('/complaints/{complaint}', [AdminComplaintController::class, 'show'])
        ->middleware('role_or_permission:complaints.view_all')
        ->name('complaints.show');
    Route::post('/complaints/{complaint}/approve', [AdminComplaintController::class, 'approve'])
        ->middleware('role_or_permission:complaints.manage')
        ->name('complaints.approve');
    Route::post('/complaints/{complaint}/reject', [AdminComplaintController::class, 'reject'])
        ->middleware('role_or_permission:complaints.manage')
        ->name('complaints.reject');

    /*
    |--------------------------------------------------------------------------
    | Work Leave Management
    |--------------------------------------------------------------------------
    */
    Route::get('/work-leave', [AdminDashboardController::class, 'workLeaveRequests'])
        ->middleware('role_or_permission:attendance.approve_leave')
        ->name('work-leave.index');
    Route::get('/work-leave/{attendance}/detail', [AdminDashboardController::class, 'workLeaveDetail'])
        ->middleware('role_or_permission:attendance.approve_leave')
        ->name('work-leave.detail');
    Route::post('/work-leave/{attendance}/approve', function (Attendance $attendance) {
        return app(AdminDashboardController::class)->workLeaveAction($attendance, 'approve');
    })->middleware('role_or_permission:attendance.approve_leave')->name('work-leave.approve');
    Route::post('/work-leave/{attendance}/reject', function (Attendance $attendance) {
        return app(AdminDashboardController::class)->workLeaveAction($attendance, 'reject');
    })->middleware('role_or_permission:attendance.approve_leave')->name('work-leave.reject');

    /*
    |--------------------------------------------------------------------------
    | Reports & Export
    |--------------------------------------------------------------------------
    */
    Route::get('/reports/export', [AdminReportController::class, 'exportForm'])
        ->middleware('role_or_permission:reports.export')
        ->name('reports.export');
    Route::get('/reports/preview', [AdminReportController::class, 'preview'])
        ->middleware('role_or_permission:reports.export')
        ->name('reports.preview');
    Route::get('/reports/download', [AdminReportController::class, 'download'])
        ->middleware('role_or_permission:reports.export')
        ->name('reports.download');
    Route::get('/export-attendance', [UserController::class, 'exportAttendance'])
        ->middleware('role_or_permission:reports.export')
        ->name('export-attendance');

    /*
    |--------------------------------------------------------------------------
    | Partner Management
    |--------------------------------------------------------------------------
    */
    Route::middleware('role_or_permission:partners.view')->group(function () {
        Route::get('/partners', [PartnerController::class, 'index'])->name('partners.index');
    });
    Route::get('/partners/create', [PartnerController::class, 'create'])
        ->middleware('role_or_permission:partners.create')
        ->name('partners.create');
    Route::post('/partners', [PartnerController::class, 'store'])
        ->middleware('role_or_permission:partners.create')
        ->name('partners.store');
    Route::get('/partners/{partner}/edit', [PartnerController::class, 'edit'])
        ->middleware('role_or_permission:partners.edit')
        ->name('partners.edit');
    Route::put('/partners/{partner}', [PartnerController::class, 'update'])
        ->middleware('role_or_permission:partners.edit')
        ->name('partners.update');
    Route::delete('/partners/{partner}', [PartnerController::class, 'destroy'])
        ->middleware('role_or_permission:partners.delete')
        ->name('partners.destroy');

    /*
    |--------------------------------------------------------------------------
    | Activity Approval
    |--------------------------------------------------------------------------
    */
    Route::middleware('role_or_permission:activities.view_all')->group(function () {
        Route::get('/activities', [AdminActivityController::class, 'index'])->name('activities.index');
        Route::get('/activities/{activity}', [AdminActivityController::class, 'show'])->name('activities.show');
    });
    Route::post('/activities/{activity}/approve', [AdminActivityController::class, 'approve'])
        ->middleware('role_or_permission:activities.approve')
        ->name('activities.approve');
    Route::post('/activities/{activity}/reject', [AdminActivityController::class, 'reject'])
        ->middleware('role_or_permission:activities.approve')
        ->name('activities.reject');
});

/*
|--------------------------------------------------------------------------
| Management Routes (Admin/Manager sidebar)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin,manager'])->group(function () {
    Route::get('/shift-management', [ShiftController::class, 'management'])->name('management.shift');
    Route::get('/pengaturan', function () {
        return view('management.pengaturan');
    })->name('management.pengaturan');

    // Permissions view
    Route::get('/permissions', [PermissionController::class, 'index'])->name('management.permissions');
    Route::get('/permissions/{permission}', [PermissionController::class, 'show'])->name('management.permissions.show');
});
