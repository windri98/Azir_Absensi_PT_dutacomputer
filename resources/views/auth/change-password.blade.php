@extends('layouts.app')

@section('title', 'Ubah Password - Sistem Absensi')

@php
    $hideHeader = true;
@endphp

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endpush

@section('content')
    <div class="profile-header-section">
        <div class="max-w-5xl mx-auto relative">
            <button class="btn btn-secondary !p-2 !rounded-full absolute left-0 top-0 shadow-lg !bg-white/20 !text-white !border-transparent hover:!bg-white/30" onclick="history.back()">
                <i class="fas fa-arrow-left"></i>
            </button>
            
            <div class="profile-header-info">
                <h1 class="!mb-0">Keamanan</h1>
                <p>Ubah password akun Anda secara berkala</p>
            </div>
        </div>
    </div>

    <div class="profile-menu-container" style="margin-top: -3rem;">
        <div class="card p-8 bg-card border-color shadow-xl max-w-xl mx-auto">
            <div class="flex items-center gap-4 mb-8 pb-4 border-b border-color">
                <div class="w-12 h-12 bg-primary-light text-primary-color rounded-xl flex items-center justify-center text-xl">
                    <i class="fas fa-key"></i>
                </div>
                <div>
                    <h3 class="font-bold text-main">Ganti Password</h3>
                    <p class="text-xs text-muted">Gunakan minimal 8 karakter dengan kombinasi angka</p>
                </div>
            </div>

            <form id="changePasswordForm" method="POST" action="{{ route('password.update') }}" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label class="form-label">Password Lama</label>
                    <div class="input-wrapper">
                        <span class="input-icon"><i class="fas fa-lock-open"></i></span>
                        <input type="password" name="current_password" class="auth-input" placeholder="Masukkan password saat ini" required>
                    </div>
                    @error('current_password')
                        <span class="text-xs text-danger mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Password Baru</label>
                    <div class="input-wrapper">
                        <span class="input-icon"><i class="fas fa-lock"></i></span>
                        <input type="password" name="password" class="auth-input" placeholder="Masukkan password baru" required>
                    </div>
                    @error('password')
                        <span class="text-xs text-danger mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Konfirmasi Password Baru</label>
                    <div class="input-wrapper">
                        <span class="input-icon"><i class="fas fa-check-double"></i></span>
                        <input type="password" name="password_confirmation" class="auth-input" placeholder="Ulangi password baru" required>
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit" class="btn btn-primary w-full py-3 shadow-lg">
                        <i class="fas fa-save mr-2"></i> Perbarui Password
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
