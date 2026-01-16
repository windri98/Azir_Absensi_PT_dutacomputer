@extends('layouts.app')

@section('title', 'Profil Saya - Sistem Absensi')

@php
    $hideHeader = true;
@endphp

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endpush

@section('content')
    <div class="profile-header-section">
        <div class="max-w-5xl mx-auto relative">
            <button class="btn btn-secondary !p-2 !rounded-full absolute left-0 top-0 shadow-lg !bg-white/20 !text-white !border-transparent hover:!bg-white/30" onclick="goBack()">
                <i class="fas fa-arrow-left"></i>
            </button>
            
            <div class="profile-avatar-container">
                <img src="{{ $user->photo ? asset('storage/' . $user->photo) : asset('assets/image/account-circle.svg') }}" 
                     class="profile-avatar-img" alt="{{ $user->name }}">
                <button class="edit-avatar-btn" onclick="window.location.href='{{ route('profile.edit') }}'">
                    <i class="fas fa-camera"></i>
                </button>
            </div>
            
            <div class="profile-header-info">
                <h1>{{ $user->name }}</h1>
                <p>{{ $user->roles->first()->display_name ?? 'Karyawan' }} • {{ $user->employee_id }}</p>
            </div>
        </div>
    </div>

    <!-- Stats Overlay -->
    <div class="profile-stats-container">
        <div class="profile-stats-card">
            <div class="profile-stat-item">
                <span class="profile-stat-value">{{ $stats['total_days'] ?? 0 }}</span>
                <span class="profile-stat-label">Hari Kerja</span>
            </div>
            <div class="profile-stat-item">
                <span class="profile-stat-value text-warning">{{ $stats['terlambat'] ?? 0 }}</span>
                <span class="profile-stat-label">Terlambat</span>
            </div>
            <div class="profile-stat-item">
                <span class="profile-stat-value text-info">{{ number_format($stats['total_hours'] ?? 0, 1) }}h</span>
                <span class="profile-stat-label">Total Jam</span>
            </div>
        </div>
    </div>

    <div class="profile-menu-container">
        <!-- Account Group -->
        <h3 class="menu-group-title">Pengaturan Akun</h3>
        <div class="menu-card">
            <a href="{{ route('profile.edit') }}" class="menu-link">
                <div class="menu-icon-box icon-blue">
                    <i class="fas fa-user-edit"></i>
                </div>
                <div class="menu-info">
                    <span class="menu-title">Edit Profil</span>
                    <span class="menu-desc">Update informasi data diri Anda</span>
                </div>
                <i class="fas fa-chevron-right menu-arrow"></i>
            </a>
            
            <a href="{{ route('profile.detail') }}" class="menu-link">
                <div class="menu-icon-box icon-purple">
                    <i class="fas fa-id-card"></i>
                </div>
                <div class="menu-info">
                    <span class="menu-title">Detail Karyawan</span>
                    <span class="menu-desc">Lihat informasi lengkap kepegawaian</span>
                </div>
                <i class="fas fa-chevron-right menu-arrow"></i>
            </a>

            <a href="{{ route('change-password') }}" class="menu-link">
                <div class="menu-icon-box icon-green">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <div class="menu-info">
                    <span class="menu-title">Keamanan</span>
                    <span class="menu-desc">Ganti password dan pengaturan keamanan</span>
                </div>
                <i class="fas fa-chevron-right menu-arrow"></i>
            </a>
        </div>

        <!-- App Group -->
        <h3 class="menu-group-title">Aktivitas & Laporan</h3>
        <div class="menu-card">
            <a href="{{ route('attendance.riwayat') }}" class="menu-link">
                <div class="menu-icon-box icon-orange">
                    <i class="fas fa-history"></i>
                </div>
                <div class="menu-info">
                    <span class="menu-title">Riwayat Kehadiran</span>
                    <span class="menu-desc">Lihat semua rekam jejak absensi</span>
                </div>
                <i class="fas fa-chevron-right menu-arrow"></i>
            </a>
            
            <a href="{{ route('leave.index') }}" class="menu-link">
                <div class="menu-icon-box icon-red">
                    <i class="fas fa-file-signature"></i>
                </div>
                <div class="menu-info">
                    <span class="menu-title">Pengajuan Izin</span>
                    <span class="menu-desc">Ajukan cuti, sakit, atau izin kerja</span>
                </div>
                <i class="fas fa-chevron-right menu-arrow"></i>
            </a>
        </div>

        <!-- Support Group -->
        <h3 class="menu-group-title">Lainnya</h3>
        <div class="menu-card">
            <a href="{{ route('help') }}" class="menu-link">
                <div class="menu-icon-box icon-gray">
                    <i class="fas fa-question-circle"></i>
                </div>
                <div class="menu-info">
                    <span class="menu-title">Pusat Bantuan</span>
                    <span class="menu-desc">FAQ dan panduan penggunaan aplikasi</span>
                </div>
                <i class="fas fa-chevron-right menu-arrow"></i>
            </a>
            
            <a href="#" class="menu-link" onclick="logout()">
                <div class="menu-icon-box icon-red" style="background: rgba(239, 68, 68, 0.1);">
                    <i class="fas fa-sign-out-alt"></i>
                </div>
                <div class="menu-info">
                    <span class="menu-title text-danger">Keluar</span>
                    <span class="menu-desc">Akhiri sesi Anda sekarang</span>
                </div>
                <i class="fas fa-chevron-right menu-arrow"></i>
            </a>
        </div>

        <div class="mt-8 text-center">
            <p class="text-[10px] font-bold text-light uppercase tracking-widest">Absensiku v1.0.0</p>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('components/popup.js') }}"></script>
    <script>
        function goBack() {
            if (window.history.length > 1) {
                window.history.back();
            } else {
                window.location.href = "{{ route('dashboard') }}";
            }
        }

        function logout() {
            if (typeof showWarningPopup !== 'undefined') {
                showWarningPopup({
                    title: 'Konfirmasi Keluar',
                    message: 'Apakah Anda yakin ingin keluar dari aplikasi?',
                    buttonText: 'Ya, Keluar',
                    onClose: () => {
                        document.getElementById('logout-form-profile').submit();
                    }
                });
            } else {
                if (confirm('Yakin ingin keluar?')) {
                    document.getElementById('logout-form-profile').submit();
                }
            }
        }
    </script>
    <form id="logout-form-profile" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
@endpush
