@extends('layouts.app')

@section('title', 'Detail Profil - Sistem Absensi')

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
            
            <div class="profile-avatar-container">
                <img src="{{ $user->photo ? asset('storage/' . $user->photo) : asset('assets/image/account-circle.svg') }}" 
                     class="profile-avatar-img" alt="{{ $user->name }}">
            </div>
            
            <div class="profile-header-info">
                <h1>Informasi Detail</h1>
                <p>Data Lengkap Kepegawaian</p>
            </div>
        </div>
    </div>

    <div class="profile-menu-container" style="margin-top: -2rem;">
        <div class="card p-8 bg-card border-color shadow-xl">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Data Personal -->
                <div>
                    <h3 class="text-sm font-bold text-primary-color uppercase tracking-widest mb-6 pb-2 border-b border-primary-light">Data Personal</h3>
                    <div class="flex flex-col gap-6">
                        <div class="info-item">
                            <span class="info-label">Nama Lengkap</span>
                            <span class="info-value">{{ $user->name }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Email</span>
                            <span class="info-value">{{ $user->email }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Nomor Telepon</span>
                            <span class="info-value">{{ $user->phone ?? '-' }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Tanggal Lahir</span>
                            <span class="info-value">{{ $user->birth_date ? \Carbon\Carbon::parse($user->birth_date)->translatedFormat('d F Y') : '-' }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Alamat</span>
                            <span class="info-value">{{ $user->address ?? '-' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Data Pekerjaan -->
                <div>
                    <h3 class="text-sm font-bold text-primary-color uppercase tracking-widest mb-6 pb-2 border-b border-primary-light">Data Pekerjaan</h3>
                    <div class="flex flex-col gap-6">
                        <div class="info-item">
                            <span class="info-label">ID Karyawan</span>
                            <span class="info-value font-mono">{{ $user->employee_id }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Jabatan / Role</span>
                            <span class="info-value">
                                <span class="badge badge-info">{{ $user->roles->first()->display_name ?? 'Karyawan' }}</span>
                            </span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Departemen</span>
                            <span class="info-value">{{ $user->department ?? 'General' }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Status Akun</span>
                            <span class="info-value text-success">
                                <i class="fas fa-check-circle mr-1"></i> Aktif
                            </span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Bergabung Sejak</span>
                            <span class="info-value">{{ $user->created_at->translatedFormat('d F Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-10 pt-6 border-t border-color flex justify-center">
                <a href="{{ route('profile.edit') }}" class="btn btn-primary px-8 py-3">
                    <i class="fas fa-user-edit mr-2"></i> Edit Informasi
                </a>
            </div>
        </div>
    </div>
@endsection
