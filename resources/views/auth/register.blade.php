@extends('layouts.auth')

@section('title', 'Register - Sistem Absensi')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4 py-12">
    <div class="w-full max-w-2xl">
        <!-- Card -->
        <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-3xl p-8 md:p-12 shadow-2xl animate-fade-in-up">
            <!-- Brand -->
            <div class="text-center mb-8">
                <div class="inline-block mb-4">
                    <div class="bg-gradient-primary rounded-2xl p-4 shadow-lg">
                        <i class="fas fa-user-plus text-4xl text-white"></i>
                    </div>
                </div>
                <h1 class="text-3xl font-bold text-white font-display mb-2">Azir Absensi</h1>
                <p class="text-primary-100">Daftar Akun Baru</p>
            </div>

            <!-- Title -->
            <div class="mb-8 text-center">
                <h2 class="text-2xl font-bold text-white mb-2 font-display">Bergabunglah dengan Kami</h2>
                <p class="text-primary-100">Buat akun untuk mulai menggunakan sistem absensi</p>
            </div>

            <!-- Error Messages -->
            @if($errors->any())
                <div class="bg-danger-500/20 border border-danger-500/50 rounded-xl p-4 mb-6 backdrop-blur-sm animate-fade-in-down">
                    <div class="flex items-start gap-3">
                        <i class="fas fa-exclamation-circle text-danger-400 text-lg mt-1 flex-shrink-0"></i>
                        <div>
                            <p class="text-danger-200 font-semibold mb-2">Pendaftaran Gagal</p>
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

            <!-- Success Message -->
            @if(session('success'))
                <div class="bg-success-500/20 border border-success-500/50 rounded-xl p-4 mb-6 backdrop-blur-sm animate-fade-in-down">
                    <div class="flex items-start gap-3">
                        <i class="fas fa-check-circle text-success-400 text-lg mt-1 flex-shrink-0"></i>
                        <div>
                            <p class="text-success-200 font-semibold">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Form -->
            <form method="POST" action="{{ route('register.post') }}" class="space-y-5">
                @csrf

                <!-- Row 1: Employee ID & Name -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <!-- Employee ID Field -->
                    <div>
                        <label for="employee_id" class="block text-sm font-semibold text-white mb-2">
                            <i class="fas fa-id-card mr-2 text-primary-400"></i>ID Karyawan
                        </label>
                        <input 
                            type="text" 
                            id="employee_id" 
                            name="employee_id" 
                            class="w-full px-4 py-3 rounded-xl bg-white/10 border border-white/20 text-white placeholder-primary-200 focus:outline-none focus:border-primary-400 focus:bg-white/20 transition-all duration-300 @error('employee_id') border-danger-400 @enderror"
                            placeholder="Masukkan ID Karyawan" 
                            value="{{ old('employee_id') }}" 
                            required 
                        />
                        @error('employee_id')
                            <p class="text-danger-300 text-xs mt-1 flex items-center gap-1">
                                <i class="fas fa-exclamation-circle"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Name Field -->
                    <div>
                        <label for="name" class="block text-sm font-semibold text-white mb-2">
                            <i class="fas fa-user mr-2 text-primary-400"></i>Nama Lengkap
                        </label>
                        <input 
                            type="text" 
                            id="name" 
                            name="name" 
                            class="w-full px-4 py-3 rounded-xl bg-white/10 border border-white/20 text-white placeholder-primary-200 focus:outline-none focus:border-primary-400 focus:bg-white/20 transition-all duration-300 @error('name') border-danger-400 @enderror"
                            placeholder="Nama Lengkap Anda" 
                            value="{{ old('name') }}" 
                            required 
                        />
                        @error('name')
                            <p class="text-danger-300 text-xs mt-1 flex items-center gap-1">
                                <i class="fas fa-exclamation-circle"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                <!-- Row 2: Email & Phone -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <!-- Email Field -->
                    <div>
                        <label for="email" class="block text-sm font-semibold text-white mb-2">
                            <i class="fas fa-envelope mr-2 text-primary-400"></i>Email
                        </label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            class="w-full px-4 py-3 rounded-xl bg-white/10 border border-white/20 text-white placeholder-primary-200 focus:outline-none focus:border-primary-400 focus:bg-white/20 transition-all duration-300 @error('email') border-danger-400 @enderror"
                            placeholder="email@perusahaan.com" 
                            value="{{ old('email') }}" 
                            required 
                        />
                        @error('email')
                            <p class="text-danger-300 text-xs mt-1 flex items-center gap-1">
                                <i class="fas fa-exclamation-circle"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Phone Field -->
                    <div>
                        <label for="phone" class="block text-sm font-semibold text-white mb-2">
                            <i class="fas fa-phone mr-2 text-primary-400"></i>Nomor Telepon
                        </label>
                        <input 
                            type="text" 
                            id="phone" 
                            name="phone" 
                            class="w-full px-4 py-3 rounded-xl bg-white/10 border border-white/20 text-white placeholder-primary-200 focus:outline-none focus:border-primary-400 focus:bg-white/20 transition-all duration-300"
                            placeholder="08xxxxxxxxxx" 
                            value="{{ old('phone') }}" 
                        />
                    </div>
                </div>

                <!-- Row 3: Password & Gender -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <!-- Password Field -->
                    <div>
                        <label for="password" class="block text-sm font-semibold text-white mb-2">
                            <i class="fas fa-lock mr-2 text-primary-400"></i>Kata Sandi
                        </label>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            class="w-full px-4 py-3 rounded-xl bg-white/10 border border-white/20 text-white placeholder-primary-200 focus:outline-none focus:border-primary-400 focus:bg-white/20 transition-all duration-300 @error('password') border-danger-400 @enderror"
                            placeholder="••••••••" 
                            required 
                            autocomplete="new-password"
                        />
                        @error('password')
                            <p class="text-danger-300 text-xs mt-1 flex items-center gap-1">
                                <i class="fas fa-exclamation-circle"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Gender Field -->
                    <div>
                        <label for="gender" class="block text-sm font-semibold text-white mb-2">
                            <i class="fas fa-venus-mars mr-2 text-primary-400"></i>Jenis Kelamin
                        </label>
                        <select 
                            id="gender" 
                            name="gender" 
                            class="w-full px-4 py-3 rounded-xl bg-white/10 border border-white/20 text-white focus:outline-none focus:border-primary-400 focus:bg-white/20 transition-all duration-300 @error('gender') border-danger-400 @enderror"
                            required
                        >
                            <option value="" class="bg-slate-900">Pilih Jenis Kelamin</option>
                            <option value="Laki-laki" class="bg-slate-900" {{ old('gender') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="Perempuan" class="bg-slate-900" {{ old('gender') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                        @error('gender')
                            <p class="text-danger-300 text-xs mt-1 flex items-center gap-1">
                                <i class="fas fa-exclamation-circle"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                <!-- Row 4: Address & Birth Date -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <!-- Address Field -->
                    <div>
                        <label for="address" class="block text-sm font-semibold text-white mb-2">
                            <i class="fas fa-map-marker-alt mr-2 text-primary-400"></i>Alamat
                        </label>
                        <input 
                            type="text" 
                            id="address" 
                            name="address" 
                            class="w-full px-4 py-3 rounded-xl bg-white/10 border border-white/20 text-white placeholder-primary-200 focus:outline-none focus:border-primary-400 focus:bg-white/20 transition-all duration-300"
                            placeholder="Alamat Lengkap" 
                            value="{{ old('address') }}" 
                        />
                    </div>

                    <!-- Birth Date Field -->
                    <div>
                        <label for="birth_date" class="block text-sm font-semibold text-white mb-2">
                            <i class="fas fa-calendar mr-2 text-primary-400"></i>Tanggal Lahir
                        </label>
                        <input 
                            type="date" 
                            id="birth_date" 
                            name="birth_date" 
                            class="w-full px-4 py-3 rounded-xl bg-white/10 border border-white/20 text-white focus:outline-none focus:border-primary-400 focus:bg-white/20 transition-all duration-300"
                            value="{{ old('birth_date') }}" 
                        />
                    </div>
                </div>

                <!-- Submit Button -->
                <button 
                    type="submit" 
                    class="w-full bg-gradient-primary text-white font-semibold py-3 rounded-xl hover:shadow-glow transition-all duration-300 transform hover:scale-105 flex items-center justify-center gap-2 mt-8"
                >
                    <i class="fas fa-user-check"></i>Daftar Sekarang
                </button>
            </form>

            <!-- Footer -->
            <div class="mt-8 pt-8 border-t border-white/10 text-center">
                <p class="text-primary-200 text-sm">
                    Sudah punya akun? 
                    <a href="{{ route('login') }}" class="text-primary-100 font-semibold hover:text-white transition-colors">
                        Masuk di sini
                    </a>
                </p>
            </div>
        </div>

        <!-- Additional Info -->
        <div class="mt-8 text-center">
            <p class="text-primary-200 text-sm">
                <i class="fas fa-shield-alt mr-2 text-success-400"></i>
                Data Anda dilindungi dengan enkripsi tingkat enterprise
            </p>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');
        const inputs = form.querySelectorAll('input, select');

        // Add real-time validation feedback
        inputs.forEach(input => {
            input.addEventListener('blur', function() {
                if (this.value.trim() === '' && this.hasAttribute('required')) {
                    this.classList.add('border-danger-400');
                } else {
                    this.classList.remove('border-danger-400');
                }
            });

            input.addEventListener('focus', function() {
                this.classList.remove('border-danger-400');
            });
        });

        // Form submission animation
        form.addEventListener('submit', function(e) {
            const submitBtn = form.querySelector('button[type="submit"]');
            submitBtn.innerHTML = '<i class="fas fa-spinner animate-spin mr-2"></i>Mendaftar...';
            submitBtn.disabled = true;
        });
    });
</script>
@endpush
@endsection
