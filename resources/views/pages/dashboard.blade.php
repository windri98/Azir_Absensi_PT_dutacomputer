@extends('layouts.app')

@section('title', 'Dashboard - Sistem Absensi')

@section('content')
<div class="space-y-8 animate-fade-in-up">
    <!-- Welcome Section -->
    <div class="bg-gradient-vibrant rounded-2xl p-8 text-white shadow-lg hover:shadow-xl transition-all duration-300">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold mb-2 font-display">Selamat Datang, {{ $user->name }}! 👋</h1>
                <p class="text-white/80">{{ now()->format('l, d F Y') }}</p>
            </div>
            <div class="hidden md:block text-6xl opacity-20 animate-float">
                <i class="fas fa-fingerprint"></i>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Today's Status -->
        <div class="bg-white rounded-2xl p-6 shadow-soft hover:shadow-card-hover transition-all duration-300 border border-gray-100 animate-fade-in-up" style="animation-delay: 0.1s;">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Status Hari Ini</h3>
                <div class="bg-gradient-primary rounded-lg p-3 text-white shadow-glow">
                    <i class="fas fa-clock text-lg"></i>
                </div>
            </div>
            @if($todayAttendance)
                <div class="space-y-2">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600 text-sm">Check-in:</span>
                        <span class="font-semibold text-gray-900">{{ $todayAttendance->check_in ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600 text-sm">Check-out:</span>
                        <span class="font-semibold text-gray-900">{{ $todayAttendance->check_out ?? '-' }}</span>
                    </div>
                    <div class="pt-2 border-t border-gray-200">
                        <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold
                            @if($todayAttendance->status === 'present')
                                bg-success-100 text-success-700
                            @elseif($todayAttendance->status === 'late')
                                bg-warning-100 text-warning-700
                            @else
                                bg-gray-100 text-gray-700
                            @endif
                        ">
                            {{ ucfirst($todayAttendance->status) }}
                        </span>
                    </div>
                </div>
            @else
                <div class="text-center py-4">
                    <p class="text-gray-500 text-sm mb-3">Belum ada absensi hari ini</p>
                    <a href="{{ route('attendance.clock-in') }}" class="inline-block bg-primary-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-primary-700 transition-colors">
                        <i class="fas fa-sign-in-alt mr-2"></i>Check-in Sekarang
                    </a>
                </div>
            @endif
        </div>

        <!-- Present Days -->
        <div class="bg-gradient-to-br from-success-50 to-success-100 rounded-2xl p-6 shadow-soft hover:shadow-card-hover transition-all duration-300 border border-success-200 animate-fade-in-up" style="animation-delay: 0.2s;">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-success-700 uppercase tracking-wide">Hadir Bulan Ini</h3>
                <div class="bg-gradient-success rounded-lg p-3 text-white shadow-glow-success">
                    <i class="fas fa-check-circle text-lg"></i>
                </div>
            </div>
            <div class="text-4xl font-bold text-success-900 mb-2">{{ $monthlyStats['present'] }}</div>
            <p class="text-success-700 text-sm font-medium">Hari kerja</p>
        </div>

        <!-- Late Days -->
        <div class="bg-gradient-to-br from-warning-50 to-warning-100 rounded-2xl p-6 shadow-soft hover:shadow-card-hover transition-all duration-300 border border-warning-200 animate-fade-in-up" style="animation-delay: 0.3s;">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-warning-700 uppercase tracking-wide">Terlambat Bulan Ini</h3>
                <div class="bg-gradient-warning rounded-lg p-3 text-white shadow-glow">
                    <i class="fas fa-clock text-lg"></i>
                </div>
            </div>
            <div class="text-4xl font-bold text-warning-900 mb-2">{{ $monthlyStats['late'] }}</div>
            <p class="text-warning-700 text-sm font-medium">Kali terlambat</p>
        </div>

        <!-- Work Leave -->
        <div class="bg-gradient-to-br from-accent-50 to-accent-100 rounded-2xl p-6 shadow-soft hover:shadow-card-hover transition-all duration-300 border border-accent-200 animate-fade-in-up" style="animation-delay: 0.4s;">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-accent-700 uppercase tracking-wide">Izin Kerja</h3>
                <div class="bg-gradient-accent rounded-lg p-3 text-white shadow-glow-accent">
                    <i class="fas fa-file-signature text-lg"></i>
                </div>
            </div>
            <div class="text-4xl font-bold text-accent-900 mb-2">{{ $monthlyStats['work_leave'] }}</div>
            <p class="text-accent-700 text-sm font-medium">Pengajuan bulan ini</p>
        </div>
    </div>

    <!-- Admin Stats (if user is admin/manager) -->
    @if($overallStats)
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-gradient-to-br from-primary-50 to-primary-100 rounded-2xl p-6 border border-primary-200 shadow-soft hover:shadow-card-hover transition-all duration-300 animate-fade-in-up" style="animation-delay: 0.1s;">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-primary-600 text-sm font-semibold uppercase tracking-wide mb-2">Total Karyawan</p>
                    <p class="text-3xl font-bold text-primary-900">{{ $overallStats['total_users'] }}</p>
                </div>
                <div class="text-5xl text-primary-200 opacity-50 animate-float">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-success-50 to-success-100 rounded-2xl p-6 border border-success-200 shadow-soft hover:shadow-card-hover transition-all duration-300 animate-fade-in-up" style="animation-delay: 0.2s;">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-success-600 text-sm font-semibold uppercase tracking-wide mb-2">Hadir Hari Ini</p>
                    <p class="text-3xl font-bold text-success-900">{{ $overallStats['today_present'] }}</p>
                </div>
                <div class="text-5xl text-success-200 opacity-50 animate-float" style="animation-delay: 0.5s;">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-warning-50 to-warning-100 rounded-2xl p-6 border border-warning-200 shadow-soft hover:shadow-card-hover transition-all duration-300 animate-fade-in-up" style="animation-delay: 0.3s;">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-warning-600 text-sm font-semibold uppercase tracking-wide mb-2">Pengajuan Pending</p>
                    <p class="text-3xl font-bold text-warning-900">{{ $overallStats['pending_complaints'] }}</p>
                </div>
                <div class="text-5xl text-warning-200 opacity-50 animate-pulse">
                    <i class="fas fa-hourglass-half"></i>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Recent Attendance & Quick Actions -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Recent Attendance -->
        <div class="lg:col-span-2 animate-fade-in-up" style="animation-delay: 0.2s;">
            <div class="bg-white rounded-2xl shadow-soft hover:shadow-card-hover border border-gray-100 overflow-hidden transition-all duration-300">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gradient-to-r from-gray-50 to-white">
                    <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                        <i class="fas fa-history text-primary-600"></i>
                        Riwayat Absensi (7 Hari Terakhir)
                    </h2>
                    <a href="{{ route('attendance.riwayat') }}" class="text-primary-600 hover:text-primary-700 text-sm font-semibold transition-colors">
                        Lihat Semua <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-100">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">Tanggal</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">Check-in</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">Check-out</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($recentAttendances as $attendance)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 text-sm text-gray-900 font-medium">
                                    {{ $attendance->date->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    {{ $attendance->check_in ?? '-' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    {{ $attendance->check_out ?? '-' }}
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold
                                        @if($attendance->status === 'present')
                                            bg-success-100 text-success-700
                                        @elseif($attendance->status === 'late')
                                            bg-warning-100 text-warning-700
                                        @elseif($attendance->status === 'absent')
                                            bg-danger-100 text-danger-700
                                        @else
                                            bg-gray-100 text-gray-700
                                        @endif
                                    ">
                                        {{ ucfirst($attendance->status) }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                                    <i class="fas fa-inbox text-3xl mb-2 block opacity-50"></i>
                                    Belum ada data absensi
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="space-y-6 animate-fade-in-up" style="animation-delay: 0.3s;">
            <!-- Main Actions -->
            <div class="bg-white rounded-2xl shadow-soft hover:shadow-card-hover border border-gray-100 overflow-hidden transition-all duration-300">
                <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                    <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                        <i class="fas fa-bolt text-primary-600"></i>
                        Aksi Cepat
                    </h2>
                </div>
                <div class="p-6 space-y-3">
                    <a href="{{ route('attendance.clock-in') }}" class="block w-full bg-gradient-primary text-white px-4 py-3 rounded-xl font-semibold hover:shadow-glow hover:scale-105 transition-all duration-300 text-center active:scale-95">
                        <i class="fas fa-sign-in-alt mr-2"></i>Check-in
                    </a>
                    <a href="{{ route('attendance.clock-out') }}" class="block w-full bg-gradient-accent text-white px-4 py-3 rounded-xl font-semibold hover:shadow-glow-accent hover:scale-105 transition-all duration-300 text-center active:scale-95">
                        <i class="fas fa-sign-out-alt mr-2"></i>Check-out
                    </a>
                    <a href="{{ route('leave.index') }}" class="block w-full bg-gradient-success text-white px-4 py-3 rounded-xl font-semibold hover:shadow-glow-success hover:scale-105 transition-all duration-300 text-center active:scale-95">
                        <i class="fas fa-file-signature mr-2"></i>Ajukan Izin
                    </a>
                </div>
            </div>

            <!-- Pending Complaints -->
            @if($pendingComplaints > 0)
            <div class="bg-gradient-to-br from-warning-50 to-warning-100 rounded-2xl border border-warning-200 p-6 shadow-soft hover:shadow-medium transition-all duration-300 animate-fade-in-up" style="animation-delay: 0.4s;">
                <div class="flex items-start gap-4">
                    <div class="bg-gradient-warning rounded-lg p-3 flex-shrink-0 text-white shadow-glow">
                        <i class="fas fa-exclamation-triangle text-lg"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-warning-900 mb-1">Pengajuan Pending</h3>
                        <p class="text-warning-700 text-sm mb-3">Anda memiliki {{ $pendingComplaints }} pengajuan yang menunggu persetujuan.</p>
                        <a href="{{ route('complaints.history') }}" class="text-warning-600 hover:text-warning-700 text-sm font-semibold transition-colors">
                            Lihat Detail <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>
            </div>
            @endif

            <!-- Info Card -->
            <div class="bg-gradient-to-br from-primary-50 to-primary-100 rounded-2xl border border-primary-200 p-6 shadow-soft hover:shadow-medium transition-all duration-300 animate-fade-in-up" style="animation-delay: 0.5s;">
                <h3 class="font-bold text-primary-900 mb-3 flex items-center gap-2">
                    <i class="fas fa-lightbulb text-primary-600"></i>
                    Tips & Trik
                </h3>
                <ul class="space-y-2 text-sm text-primary-800">
                    <li class="flex items-start gap-2">
                        <i class="fas fa-check text-primary-600 mt-1 flex-shrink-0"></i>
                        <span>Jangan lupa check-in setiap hari kerja</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <i class="fas fa-check text-primary-600 mt-1 flex-shrink-0"></i>
                        <span>Gunakan QR code untuk check-in yang lebih cepat</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <i class="fas fa-check text-primary-600 mt-1 flex-shrink-0"></i>
                        <span>Ajukan izin sebelum tidak masuk kerja</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
