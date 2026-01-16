@extends('layouts.auth')

@section('title', 'Login - Sistem Absensi')

@section('content')
    <div class="auth-layout">
        <div class="auth-card">
            <!-- Brand -->
            <div class="auth-brand">
                <div class="auth-logo">
                    <i class="fas fa-fingerprint"></i>
                </div>
                <h1 class="auth-brand-name">Absensiku</h1>
                <p class="auth-brand-tagline">Sistem Absensi Masa Kini</p>
            </div>

            <!-- Title -->
            <div class="mb-8 text-center">
                <h2 class="auth-title">Selamat Datang Kembali</h2>
                <p class="auth-subtitle">Silakan masuk ke akun Anda</p>
            </div>

            @if($errors->any())
                <div class="flash-message error mb-6">
                    <i class="fas fa-exclamation-circle"></i>
                    <ul class="list-none text-sm">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login.post') }}">
                @csrf

                <div class="form-group mb-4">
                    <label for="email" class="input-label">Alamat Email</label>
                    <div class="input-wrapper">
                        <span class="input-icon">
                            <i class="fas fa-envelope"></i>
                        </span>
                        <input type="email" id="email" name="email" class="auth-input" placeholder="email@perusahaan.com" value="{{ old('email') }}" required autofocus />
                    </div>
                </div>

                <div class="form-group mb-4">
                    <label for="password" class="input-label">Kata Sandi</label>
                    <div class="input-wrapper">
                        <span class="input-icon">
                            <i class="fas fa-lock"></i>
                        </span>
                        <input type="password" id="password" name="password" class="auth-input" placeholder="••••••••" required />
                    </div>
                </div>

                <div class="remember-section mb-8">
                    <div class="remember-group">
                        <input type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }} />
                        <label for="remember" class="text-sm cursor-pointer">Ingat saya</label>
                    </div>
                    <a href="#" class="forgot-link" id="forgot-password-link">Lupa sandi?</a>
                </div>

                <button type="submit" class="auth-btn">
                    Masuk Sekarang
                </button>
            </form>

            <div class="auth-footer">
                <p class="text-xs text-muted">
                    Masalah dengan akun? <br>
                    <span class="font-bold text-main">Hubungi Tim IT Support</span>
                </p>
            </div>
        </div>
    </div>

    <!-- Forgot Password Popup -->
    <div id="forgot-password-popup" class="popup-overlay">
        <div class="forgot-popup">
            <div class="w-16 h-16 bg-primary-light text-primary-color rounded-full flex items-center justify-center mx-auto mb-4 text-2xl">
                <i class="fas fa-key"></i>
            </div>
            <h3 class="text-xl font-bold text-main mb-2">Lupa Kata Sandi?</h3>
            <p class="text-muted text-sm mb-6">Silakan hubungi Admin atau HRD untuk mengatur ulang kata sandi akun Anda.</p>
            <button id="forgot-popup-close" class="btn btn-primary w-full py-3">Mengerti</button>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const forgotLink = document.getElementById('forgot-password-link');
        const forgotPopup = document.getElementById('forgot-password-popup');
        const closeBtn = document.getElementById('forgot-popup-close');

        if (forgotLink && forgotPopup && closeBtn) {
            forgotLink.addEventListener('click', (e) => {
                e.preventDefault();
                forgotPopup.style.display = 'flex';
            });
            closeBtn.addEventListener('click', () => forgotPopup.style.display = 'none');
            forgotPopup.addEventListener('click', (e) => { if (e.target === forgotPopup) forgotPopup.style.display = 'none'; });
        }
    });
</script>
@endpush
