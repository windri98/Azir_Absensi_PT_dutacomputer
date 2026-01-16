<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DashboardRouterController;
use App\Http\Controllers\LocationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Routes are organized into separate files for better maintainability:
| - auth.php       : Authentication (login, logout, password)
| - attendance.php : Attendance management (clock in/out, history)
| - leave.php      : Leave & complaints management
| - profile.php    : Profile management
| - reports.php    : Reports & export
| - admin.php      : Admin panel routes
|
*/

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
| Dashboard Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    // Dashboard Router - redirects based on permissions
    Route::get('/dashboard', [DashboardRouterController::class, 'index'])->name('dashboard');

    // User Dashboard
    Route::get('/user/dashboard', [DashboardController::class, 'index'])
        ->middleware('role_or_permission:dashboard.view')
        ->name('user.dashboard');

    // Activities
    Route::get('/aktifitas', function () {
        $user = auth()->user();
        return view('activities.aktifitas', compact('user'));
    })->name('activities.aktifitas');

    // Legacy route - redirect to new location
    Route::get('/activities/izin', function () {
        return redirect()->route('leave.index');
    })->name('activities.izin');

    // Settings
    Route::get('/notification-settings', function () {
        return view('settings.notification-settings');
    })->name('settings.notifications');

    // API - Reverse Geocode
    Route::get('/reverse-geocode', [LocationController::class, 'reverseGeocode'])->name('reverse-geocode');
});

/*
|--------------------------------------------------------------------------
| Load Route Files
|--------------------------------------------------------------------------
*/

require __DIR__ . '/auth.php';
require __DIR__ . '/attendance.php';
require __DIR__ . '/leave.php';
require __DIR__ . '/profile.php';
require __DIR__ . '/reports.php';
require __DIR__ . '/admin.php';
