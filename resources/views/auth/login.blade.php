@extends('layouts.auth')

@section('title', 'Login - PT DUTA COMPUTER')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4 py-12">
    <div class="w-full max-w-md">
        <!-- Card -->
        <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-3xl p-8 md:p-12 shadow-2xl animate-fade-in-up">
            <!-- Brand -->
            <div class="text-center mb-8">
                <div class="inline-block mb-4">
                    <div class="bg-gradient-primary rounded-2xl p-4 shadow-lg">
                        <i class="fas fa-fingerprint text-4xl text-white"></i>
                    </div>
                </div>
                <h1 class="text-3xl font-bold text-white font-display mb-2">PT DUTA COMPUTER</h1>
                <p class="text-primary-100">Sistem Manajemen Absensi</p>
            </div>

            <!-- Title -->
            <div class="mb-8 text-center">
                <h2 class="text-2xl font-bold text-white mb-2 font-display">Selamat Datang Kembali</h2>
                <p class="text-primary-100">Silakan masuk ke akun Anda untuk melanjutkan</p>
            </div>

            <!-- Error Messages -->
            @if($errors->any())
                <div class="bg-danger-500/20 border border-danger-500/50 rounded-xl p-4 mb-6 backdrop-blur-sm animate-fade-in-down">
                    <div class="flex items-start gap-3">
                        <i class="fas fa-exclamation-circle text-danger-400 text-lg mt-1 flex-shrink-0"></i>
                        <div>
                            <p class="text-danger-200 font-semibold mb-2">Login Gagal</p>
                            <ul class="list-none text-sm text-danger-100 space-y-1">
                                @foreach($errors->all() as $error)
                                    <li class="flex items-center gap-2">
                                        <i class="fas fa-times text-xs"></i>
                                        {{ $error }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Form -->
            <form method="POST" action="{{ route('login.post') }}" class="space-y-5">
                @csrf

                <!-- Email Field -->
                <div class="animate-fade-in-left" style="animation-delay: 0.1s;">
                    <label for="email" class="block text-sm font-semibold text-white mb-2">
                        <i class="fas fa-envelope mr-2 text-primary-400"></i>Alamat Email
                    </label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        class="w-full px-4 py-3 rounded-xl bg-white/10 border border-white/20 text-white placeholder-primary-200 focus:outline-none focus:border-primary-400 focus:bg-white/20 transition-all duration-300 focus:shadow-glow"
                        placeholder="email@perusahaan.com" 
                        value="{{ old('email') }}" 
                        required 
                        autofocus 
                    />
                </div>

                <!-- Password Field -->
                <div class="animate-fade-in-left" style="animation-delay: 0.2s;">
                    <label for="password" class="block text-sm font-semibold text-white mb-2">
                        <i class="fas fa-lock mr-2 text-primary-400"></i>Kata Sandi
                    </label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        class="w-full px-4 py-3 rounded-xl bg-white/10 border border-white/20 text-white placeholder-primary-200 focus:outline-none focus:border-primary-400 focus:bg-white/20 transition-all duration-300 focus:shadow-glow"
                        placeholder="••••••••" 
                        required 
                    />
                </div>

                <!-- Remember & Forgot -->
                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 cursor-pointer group">
                        <input 
                            type="checkbox" 
                            id="remember" 
                            name="remember" 
                            {{ old('remember') ? 'checked' : '' }}
                            class="w-4 h-4 rounded border-white/20 bg-white/10 text-primary-500 focus:ring-primary-500 cursor-pointer"
                        />
                        <span class="text-sm text-primary-100 group-hover:text-white transition-colors">Ingat saya</span>
                    </label>
                    <button 
                        type="button" 
                        id="forgot-password-link" 
                        class="text-sm text-primary-300 hover:text-primary-100 transition-colors"
                    >
                        Lupa sandi?
                    </button>
                </div>

                <!-- Submit Button -->
                <button 
                    type="submit" 
                    id="login-btn"
                    class="w-full bg-gradient-primary text-white font-semibold py-3 rounded-xl hover:shadow-glow transition-all duration-300 transform hover:scale-105 active:scale-95 flex items-center justify-center gap-2 mt-8 disabled:opacity-75 disabled:cursor-not-allowed"
                >
                    <i class="fas fa-sign-in-alt"></i>Masuk Sekarang
                </button>
            </form>

            <!-- Footer -->
            <div class="mt-8 pt-8 border-t border-white/10 text-center">
                <p class="text-xs text-primary-200">
                    <i class="fas fa-info-circle mr-2"></i>
                    Masalah dengan akun? <br>
                    <span class="text-primary-100 font-semibold">Hubungi Admin</span>
                </p>
            </div>
        </div>

        <!-- Additional Info -->
        <div class="mt-8 text-center">
            <p class="text-primary-200 text-sm">
                <i class="fas fa-shield-alt mr-2 text-success-400"></i>
                Koneksi Anda dilindungi dengan enkripsi tingkat enterprise
            </p>
        </div>
    </div>
</div>

<!-- Forgot Password Modal -->
<div id="forgot-password-popup" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 p-4">
    <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl p-8 max-w-sm w-full shadow-2xl animate-slide-up">
        <div class="text-center">
            <div class="inline-block mb-4">
                <div class="bg-gradient-warning rounded-full p-4">
                    <i class="fas fa-key text-2xl text-white"></i>
                </div>
            </div>
            <h3 class="text-xl font-bold text-white mb-2 font-display">Lupa Kata Sandi?</h3>
            <p class="text-primary-100 text-sm mb-6">
                Silakan hubungi Admin atau HRD untuk mengatur ulang kata sandi akun Anda.
            </p>
            <button 
                id="forgot-popup-close" 
                class="w-full bg-gradient-primary text-white font-semibold py-3 rounded-xl hover:shadow-glow transition-all duration-300"
            >
                <i class="fas fa-check mr-2"></i>Mengerti
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const forgotLink = document.getElementById('forgot-password-link');
        const forgotPopup = document.getElementById('forgot-password-popup');
        const closeBtn = document.getElementById('forgot-popup-close');
        const loginForm = document.querySelector('form');
        const loginBtn = document.getElementById('login-btn');

        // Forgot Password Modal
        if (forgotLink && forgotPopup && closeBtn) {
            forgotLink.addEventListener('click', (e) => {
                e.preventDefault();
                forgotPopup.classList.remove('hidden');
                forgotPopup.classList.add('animate-scale-in');
            });
            closeBtn.addEventListener('click', () => {
                forgotPopup.classList.add('hidden');
            });
            forgotPopup.addEventListener('click', (e) => {
                if (e.target === forgotPopup) {
                    forgotPopup.classList.add('hidden');
                }
            });
        }

        // Form submission with loading state
        if (loginForm && loginBtn) {
            loginForm.addEventListener('submit', function(e) {
                loginBtn.disabled = true;
                const originalText = loginBtn.innerHTML;
                loginBtn.innerHTML = '<i class="fas fa-spinner animate-spin mr-2"></i>Memproses...';
                
                // Restore button after 3 seconds if form doesn't submit
                setTimeout(() => {
                    if (loginBtn.disabled) {
                        loginBtn.disabled = false;
                        loginBtn.innerHTML = originalText;
                    }
                }, 3000);
            });
        }

        // Input focus animations
        const inputs = document.querySelectorAll('input[type="email"], input[type="password"]');
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.classList.add('animate-scale-in');
            });
        });
    });
</script>
@endpush
