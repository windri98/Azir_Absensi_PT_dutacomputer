@extends('layouts.app')

@section('title', 'Edit Profil - Sistem Absensi')

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
                <h1 class="!mb-0">Edit Profil</h1>
                <p>Perbarui informasi akun Anda</p>
            </div>
        </div>
    </div>

    <div class="profile-menu-container" style="margin-top: -3rem;">
        <form id="editProfileForm" method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            
            <div class="card p-8 bg-card border-color shadow-xl">
                <div class="flex flex-col items-center mb-10">
                    <div class="profile-avatar-container !m-0">
                        <img src="{{ $user->photo ? asset('storage/' . $user->photo) : asset('assets/image/account-circle.svg') }}" 
                             id="profilePreview" class="profile-avatar-img" alt="{{ $user->name }}">
                        <button type="button" class="edit-avatar-btn" onclick="document.getElementById('photoInput').click()">
                            <i class="fas fa-camera"></i>
                        </button>
                    </div>
                    <input type="file" id="photoInput" name="photo" accept="image/*" class="hidden" onchange="previewImage(event)">
                    <p class="mt-4 text-xs text-muted">Format: JPG, PNG. Max: 2MB</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-group">
                        <label class="form-label">ID Karyawan</label>
                        <input type="text" class="form-input" value="{{ $user->employee_id }}" disabled style="background: var(--bg-body); cursor: not-allowed;">
                        <span class="text-[10px] text-light italic mt-1 block">* ID tidak dapat diubah</span>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-input" name="name" value="{{ old('name', $user->name) }}" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Alamat Email</label>
                        <input type="email" class="form-input" name="email" value="{{ old('email', $user->email) }}" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Nomor Telepon</label>
                        <input type="tel" class="form-input" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="Contoh: 0812...">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Tanggal Lahir</label>
                        <input type="date" class="form-input" name="birth_date" value="{{ old('birth_date', $user->birth_date) }}">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Departemen</label>
                        <input type="text" class="form-input" value="{{ $user->department ?? '-' }}" disabled style="background: var(--bg-body); cursor: not-allowed;">
                    </div>

                    <div class="form-group md:col-span-2">
                        <label class="form-label">Alamat Lengkap</label>
                        <textarea class="form-textarea" name="address" rows="3" placeholder="Masukkan alamat tinggal saat ini...">{{ old('address', $user->address) }}</textarea>
                    </div>
                </div>

                <div class="mt-10 pt-6 border-t border-color flex flex-col sm:flex-row gap-4">
                    <button type="submit" class="btn btn-primary flex-1 py-3">
                        <i class="fas fa-save mr-2"></i> Simpan Perubahan
                    </button>
                    <button type="button" class="btn btn-secondary flex-1 py-3" onclick="history.back()">
                        Batal
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function() {
                const output = document.getElementById('profilePreview');
                output.src = reader.result;
            }
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
@endpush
