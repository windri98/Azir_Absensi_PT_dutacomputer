<?php

use Illuminate\Support\Facades\Route;

// Pages Routes
Route::get('/', function () {
    return view('pages.welcome');
})->name('welcome');

Route::get('/dashboard', function () {
    return view('pages.dashboard');
})->name('dashboard');

Route::get('/about', function () {
    return view('pages.about');
})->name('about');

Route::get('/contact', function () {
    return view('pages.contact');
})->name('contact');

Route::get('/help', function () {
    return view('pages.help');
})->name('help');

// Auth Routes
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::get('/change-password', function () {
    return view('auth.change-password');
})->name('change-password');

// Profile Routes
Route::get('/profile', function () {
    return view('profile.profile');
})->name('profile');

Route::get('/profil', function () {
    return view('profile.profil');
})->name('profil');

Route::get('/edit-profile', function () {
    return view('profile.edit-profile');
})->name('edit-profile');

Route::get('/profile-detail', function () {
    return view('profile.profile-detail');
})->name('profile-detail');

// Attendance Routes
Route::get('/absensi', function () {
    return view('attendance.absensi');
})->name('absensi');

Route::get('/clock-in', function () {
    return view('attendance.clock-in');
})->name('clock-in');

Route::get('/clock-out', function () {
    return view('attendance.clock-out');
})->name('clock-out');

Route::get('/clock-overtime', function () {
    return view('attendance.clock-overtime');
})->name('clock-overtime');

Route::get('/qr-scan', function () {
    return view('attendance.qr-scan');
})->name('qr-scan');

Route::get('/riwayat', function () {
    return view('attendance.riwayat');
})->name('riwayat');

// Reports Routes
Route::get('/laporan', function () {
    return view('reports.laporan');
})->name('laporan');

Route::get('/report-history', function () {
    return view('reports.report-history');
})->name('report-history');

Route::get('/customer-report', function () {
    return view('reports.customer-report');
})->name('customer-report');

// Complaints Routes
Route::get('/complaint-form', function () {
    return view('complaints.complaint-form');
})->name('complaint-form');

Route::get('/complaint-history', function () {
    return view('complaints.complaint-history');
})->name('complaint-history');

Route::get('/technician-complaints', function () {
    return view('complaints.technician-complaints');
})->name('technician-complaints');

// Management Routes
Route::get('/shift-management', function () {
    return view('management.shift-management');
})->name('shift-management');

Route::get('/pengaturan', function () {
    return view('management.pengaturan');
})->name('pengaturan');

// Activities Routes
Route::get('/aktifitas', function () {
    return view('activities.aktifitas');
})->name('aktifitas');

Route::get('/izin', function () {
    return view('activities.izin');
})->name('izin');

// Settings Routes
Route::get('/notification-settings', function () {
    return view('settings.notification-settings');
})->name('notification-settings');
