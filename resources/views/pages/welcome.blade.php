@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-900 via-primary-900 to-slate-900 relative overflow-hidden">
    <!-- Animated Background Elements -->
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-primary-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-pulse-soft"></div>
        <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-accent-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-pulse-soft" style="animation-delay: 2s;"></div>
        <div class="absolute top-1/2 left-1/2 w-80 h-80 bg-success-500 rounded-full mix-blend-multiply filter blur-3xl opacity-10 animate-pulse-soft" style="animation-delay: 4s;"></div>
    </div>

    <!-- Content -->
    <div class="relative z-10">
        <!-- Hero Section -->
        <div class="container mx-auto px-4 py-20 md:py-32">
            <div class="text-center mb-16 animate-fade-in">
                <div class="inline-block mb-6">
                    <div class="bg-gradient-primary rounded-2xl p-4 shadow-lg">
                        <i class="fas fa-fingerprint text-4xl text-white"></i>
                    </div>
                </div>
                <h1 class="text-5xl md:text-7xl font-bold text-white mb-6 font-display">
                    PT DUTA COMPUTER
                </h1>
                <p class="text-xl md:text-2xl text-primary-100 mb-8 max-w-2xl mx-auto">
                    Sistem Manajemen Absensi Karyawan yang Modern, Aman, dan Terpercaya
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    @auth
                        <a href="{{ route('user.dashboard') }}" class="inline-block bg-gradient-primary text-white px-8 py-4 rounded-xl font-semibold hover:shadow-glow transition-all duration-300 transform hover:scale-105">
                            <i class="fas fa-arrow-right mr-2"></i>Ke Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="inline-block bg-gradient-primary text-white px-8 py-4 rounded-xl font-semibold hover:shadow-glow transition-all duration-300 transform hover:scale-105">
                            <i class="fas fa-sign-in-alt mr-2"></i>Login Sekarang
                        </a>
                        <a href="{{ route('about') }}" class="inline-block bg-white/10 backdrop-blur-md text-white px-8 py-4 rounded-xl font-semibold border border-white/20 hover:bg-white/20 transition-all duration-300">
                            <i class="fas fa-info-circle mr-2"></i>Pelajari Lebih Lanjut
                        </a>
                    @endauth
                </div>
            </div>

            <!-- Features Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-16 animate-slide-up">
                <!-- Feature 1 -->
                <div class="group bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl p-8 hover:bg-white/20 transition-all duration-300 hover:shadow-xl hover:border-primary-400/50">
                    <div class="bg-gradient-primary rounded-xl p-4 w-16 h-16 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <i class="fas fa-chart-line text-2xl text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3">Real-time Tracking</h3>
                    <p class="text-primary-100">Monitor kehadiran dengan fitur clock in/out real-time dan pelacakan lokasi GPS yang akurat.</p>
                </div>

                <!-- Feature 2 -->
                <div class="group bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl p-8 hover:bg-white/20 transition-all duration-300 hover:shadow-xl hover:border-accent-400/50">
                    <div class="bg-gradient-accent rounded-xl p-4 w-16 h-16 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <i class="fas fa-mobile-alt text-2xl text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3">Mobile Friendly</h3>
                    <p class="text-primary-100">Akses sistem dari perangkat apa pun dengan antarmuka yang responsif dan dioptimalkan untuk mobile.</p>
                </div>

                <!-- Feature 3 -->
                <div class="group bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl p-8 hover:bg-white/20 transition-all duration-300 hover:shadow-xl hover:border-success-400/50">
                    <div class="bg-gradient-success rounded-xl p-4 w-16 h-16 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <i class="fas fa-shield-alt text-2xl text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3">Secure & Reliable</h3>
                    <p class="text-primary-100">Keamanan tingkat enterprise dengan enkripsi data dan audit logging yang komprehensif.</p>
                </div>
            </div>

            <!-- Key Features Section -->
            <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-3xl p-12 mb-16">
                <h2 class="text-4xl font-bold text-white mb-12 text-center font-display">Fitur Unggulan</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Feature Item 1 -->
                    <div class="flex gap-4 group">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center h-12 w-12 rounded-lg bg-gradient-primary text-white group-hover:scale-110 transition-transform">
                                <i class="fas fa-qrcode"></i>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-white mb-2">QR Code Scanning</h3>
                            <p class="text-primary-100">Teknologi QR code untuk pencatatan kehadiran yang cepat dan akurat tanpa kesalahan manual.</p>
                        </div>
                    </div>

                    <!-- Feature Item 2 -->
                    <div class="flex gap-4 group">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center h-12 w-12 rounded-lg bg-gradient-accent text-white group-hover:scale-110 transition-transform">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-white mb-2">Leave Management</h3>
                            <p class="text-primary-100">Sistem manajemen cuti yang streamlined dengan workflow persetujuan yang efisien.</p>
                        </div>
                    </div>

                    <!-- Feature Item 3 -->
                    <div class="flex gap-4 group">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center h-12 w-12 rounded-lg bg-gradient-success text-white group-hover:scale-110 transition-transform">
                                <i class="fas fa-chart-bar"></i>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-white mb-2">Comprehensive Reports</h3>
                            <p class="text-primary-100">Laporan detail dan analitik mendalam untuk monitoring performa kehadiran karyawan.</p>
                        </div>
                    </div>

                    <!-- Feature Item 4 -->
                    <div class="flex gap-4 group">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center h-12 w-12 rounded-lg bg-gradient-warning text-white group-hover:scale-110 transition-transform">
                                <i class="fas fa-lock"></i>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-white mb-2">Role-Based Access</h3>
                            <p class="text-primary-100">Sistem permission yang fleksibel untuk berbagai peran pengguna dengan kontrol akses granular.</p>
                        </div>
                    </div>

                    <!-- Feature Item 5 -->
                    <div class="flex gap-4 group">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center h-12 w-12 rounded-lg bg-gradient-danger text-white group-hover:scale-110 transition-transform">
                                <i class="fas fa-bell"></i>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-white mb-2">Smart Notifications</h3>
                            <p class="text-primary-100">Notifikasi real-time untuk reminder absensi dan update penting lainnya.</p>
                        </div>
                    </div>

                    <!-- Feature Item 6 -->
                    <div class="flex gap-4 group">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center h-12 w-12 rounded-lg bg-primary-500 text-white group-hover:scale-110 transition-transform">
                                <i class="fas fa-sync-alt"></i>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-white mb-2">Data Synchronization</h3>
                            <p class="text-primary-100">Sinkronisasi data otomatis untuk memastikan konsistensi informasi di semua perangkat.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Section -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-16">
                <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl p-8 text-center hover:bg-white/20 transition-all">
                    <div class="text-4xl font-bold text-primary-400 mb-2">100%</div>
                    <p class="text-primary-100">Uptime Guarantee</p>
                </div>
                <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl p-8 text-center hover:bg-white/20 transition-all">
                    <div class="text-4xl font-bold text-accent-400 mb-2">10K+</div>
                    <p class="text-primary-100">Active Users</p>
                </div>
                <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl p-8 text-center hover:bg-white/20 transition-all">
                    <div class="text-4xl font-bold text-success-400 mb-2">50+</div>
                    <p class="text-primary-100">Companies</p>
                </div>
                <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl p-8 text-center hover:bg-white/20 transition-all">
                    <div class="text-4xl font-bold text-warning-400 mb-2">24/7</div>
                    <p class="text-primary-100">Support</p>
                </div>
            </div>

            <!-- CTA Section -->
            <div class="bg-gradient-primary rounded-3xl p-12 text-center text-white">
                <h2 class="text-3xl md:text-4xl font-bold mb-4 font-display">Siap Memulai?</h2>
                <p class="text-lg text-primary-100 mb-8 max-w-2xl mx-auto">Bergabunglah dengan ribuan perusahaan yang telah mempercayai Azir Absensi untuk mengelola kehadiran karyawan mereka.</p>
                @auth
                    <a href="{{ route('user.dashboard') }}" class="inline-block bg-white text-primary-600 px-8 py-4 rounded-xl font-semibold hover:bg-primary-50 transition-all duration-300 transform hover:scale-105">
                        <i class="fas fa-arrow-right mr-2"></i>Buka Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="inline-block bg-white text-primary-600 px-8 py-4 rounded-xl font-semibold hover:bg-primary-50 transition-all duration-300 transform hover:scale-105">
                        <i class="fas fa-sign-in-alt mr-2"></i>Login Sekarang
                    </a>
                @endauth
            </div>
        </div>
    </div>
</div>
@endsection
