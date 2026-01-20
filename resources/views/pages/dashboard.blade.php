@extends('layouts.app')

@section('title', 'Dashboard - PT DUTA COMPUTER')

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

    <!-- TODAY'S STATUS - PROMINENT HIGHLIGHT -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden animate-fade-in-up" style="animation-delay: 0.05s;">
        <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-primary-50 to-white flex items-center justify-between">
            <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                <i class="fas fa-calendar-check text-primary-600"></i>
                Status Absensi Hari Ini
            </h2>
            <span class="text-xs font-bold text-primary-600 uppercase tracking-wide">{{ now()->format('d M Y') }}</span>
        </div>
        <div class="p-6">
            @if($todayAttendance)
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Check-in Status -->
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 rounded-2xl bg-success-100 flex items-center justify-center text-success-600">
                            <i class="fas fa-sign-in-alt text-2xl"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-500 uppercase tracking-wide mb-1">Check-in</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $todayAttendance->check_in ?? '--:--' }}</p>
                            <p class="text-xs text-gray-600 mt-1">
                                @if($todayAttendance->check_in)
                                    <i class="fas fa-check-circle text-success-600 mr-1"></i>Sudah check-in
                                @else
                                    <i class="fas fa-times-circle text-gray-400 mr-1"></i>Belum check-in
                                @endif
                            </p>
                        </div>
                    </div>

                    <!-- Check-out Status -->
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 rounded-2xl @if($todayAttendance->check_out) bg-success-100 @else bg-gray-100 @endif flex items-center justify-center @if($todayAttendance->check_out) text-success-600 @else text-gray-400 @endif">
                            <i class="fas fa-sign-out-alt text-2xl"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-500 uppercase tracking-wide mb-1">Check-out</p>
                            <p class="text-2xl font-bold @if($todayAttendance->check_out) text-gray-900 @else text-gray-400 @endif">{{ $todayAttendance->check_out ?? '--:--' }}</p>
                            <p class="text-xs @if($todayAttendance->check_out) text-gray-600 @else text-gray-500 @endif mt-1">
                                @if($todayAttendance->check_out)
                                    <i class="fas fa-check-circle text-success-600 mr-1"></i>Sudah check-out
                                @else
                                    <i class="fas fa-clock text-gray-400 mr-1"></i>Belum check-out
                                @endif
                            </p>
                        </div>
                    </div>

                    <!-- Status Badge -->
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 rounded-2xl 
                            @if($todayAttendance->status === 'present') bg-success-100 text-success-600
                            @elseif($todayAttendance->status === 'late') bg-warning-100 text-warning-600
                            @else bg-gray-100 text-gray-600
                            @endif
                            flex items-center justify-center">
                            <i class="fas @if($todayAttendance->status === 'present') fa-check-circle @elseif($todayAttendance->status === 'late') fa-exclamation-circle @else fa-question-circle @endif text-2xl"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-500 uppercase tracking-wide mb-1">Status</p>
                            <span class="inline-block px-4 py-1 rounded-full text-sm font-bold
                                @if($todayAttendance->status === 'present') bg-success-100 text-success-700
                                @elseif($todayAttendance->status === 'late') bg-warning-100 text-warning-700
                                @else bg-gray-100 text-gray-700
                                @endif
                            ">
                                @if($todayAttendance->status === 'present') Hadir Tepat Waktu
                                @elseif($todayAttendance->status === 'late') Hadir Terlambat
                                @else {{ ucfirst($todayAttendance->status) }}
                                @endif
                            </span>
                        </div>
                    </div>
                </div>
            @else
                <div class="text-center py-8">
                    <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-user-clock text-3xl text-gray-400"></i>
                    </div>
                    <p class="text-gray-600 font-semibold mb-4">Belum ada aktivitas absensi hari ini</p>
                    <div class="flex gap-3 justify-center flex-wrap">
                        <a href="{{ route('attendance.clock-in') }}" class="inline-block bg-success-600 text-white px-6 py-3 rounded-xl font-semibold hover:bg-success-700 transition-all duration-200 active:scale-95">
                            <i class="fas fa-sign-in-alt mr-2"></i>Check-in Sekarang
                        </a>
                        <a href="{{ route('leave.index') }}" class="inline-block bg-primary-600 text-white px-6 py-3 rounded-xl font-semibold hover:bg-primary-700 transition-all duration-200 active:scale-95">
                            <i class="fas fa-file-signature mr-2"></i>Ajukan Izin
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- MONTHLY STATISTICS CARDS -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Present Days -->
        <div class="bg-gradient-to-br from-success-50 to-success-100 rounded-2xl p-6 shadow-soft hover:shadow-card-hover transition-all duration-300 border border-success-200 animate-fade-in-up" style="animation-delay: 0.2s;">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-success-700 uppercase tracking-wide">Hadir Bulan Ini</h3>
                <div class="bg-gradient-success rounded-lg p-3 text-white shadow-glow-success">
                    <i class="fas fa-check-circle text-lg"></i>
                </div>
            </div>
            <div class="text-4xl font-bold text-success-900 mb-1">{{ $monthlyStats['present'] }}</div>
            <p class="text-success-700 text-sm font-medium">Hari kerja produktif</p>
        </div>

        <!-- Late Days -->
        <div class="bg-gradient-to-br from-warning-50 to-warning-100 rounded-2xl p-6 shadow-soft hover:shadow-card-hover transition-all duration-300 border border-warning-200 animate-fade-in-up" style="animation-delay: 0.3s;">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-warning-700 uppercase tracking-wide">Terlambat Bulan Ini</h3>
                <div class="bg-gradient-warning rounded-lg p-3 text-white shadow-glow">
                    <i class="fas fa-clock text-lg"></i>
                </div>
            </div>
            <div class="text-4xl font-bold text-warning-900 mb-1">{{ $monthlyStats['late'] }}</div>
            <p class="text-warning-700 text-sm font-medium">Kehadiran terlambat</p>
        </div>

        <!-- Work Leave -->
        <div class="bg-gradient-to-br from-accent-50 to-accent-100 rounded-2xl p-6 shadow-soft hover:shadow-card-hover transition-all duration-300 border border-accent-200 animate-fade-in-up" style="animation-delay: 0.4s;">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-accent-700 uppercase tracking-wide">Izin Kerja</h3>
                <div class="bg-gradient-accent rounded-lg p-3 text-white shadow-glow-accent">
                    <i class="fas fa-file-signature text-lg"></i>
                </div>
            </div>
            <div class="text-4xl font-bold text-accent-900 mb-1">{{ $monthlyStats['work_leave'] }}</div>
            <p class="text-accent-700 text-sm font-medium">Pengajuan disetujui</p>
        </div>
    </div>

    <!-- QUICK ACTIONS - PROMINENT -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 animate-fade-in-up" style="animation-delay: 0.5s;">
        <a href="{{ route('attendance.clock-in') }}" class="group bg-gradient-to-br from-success-50 to-success-100 rounded-2xl p-6 border border-success-200 shadow-soft hover:shadow-lg hover:scale-105 transition-all duration-300 active:scale-95">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-xl bg-gradient-success text-white flex items-center justify-center group-hover:scale-110 transition-transform shadow-glow-success">
                    <i class="fas fa-sign-in-alt text-xl"></i>
                </div>
                <div>
                    <h3 class="font-bold text-gray-900 text-lg">Check-in</h3>
                    <p class="text-sm text-gray-600">Mulai hari kerja</p>
                </div>
            </div>
        </a>

        <a href="{{ route('attendance.clock-out') }}" class="group bg-gradient-to-br from-accent-50 to-accent-100 rounded-2xl p-6 border border-accent-200 shadow-soft hover:shadow-lg hover:scale-105 transition-all duration-300 active:scale-95">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-xl bg-gradient-accent text-white flex items-center justify-center group-hover:scale-110 transition-transform shadow-glow-accent">
                    <i class="fas fa-sign-out-alt text-xl"></i>
                </div>
                <div>
                    <h3 class="font-bold text-gray-900 text-lg">Check-out</h3>
                    <p class="text-sm text-gray-600">Akhiri hari kerja</p>
                </div>
            </div>
        </a>

        <a href="{{ route('leave.index') }}" class="group bg-gradient-to-br from-primary-50 to-primary-100 rounded-2xl p-6 border border-primary-200 shadow-soft hover:shadow-lg hover:scale-105 transition-all duration-300 active:scale-95">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-xl bg-gradient-primary text-white flex items-center justify-center group-hover:scale-110 transition-transform shadow-glow">
                    <i class="fas fa-file-signature text-xl"></i>
                </div>
                <div>
                    <h3 class="font-bold text-gray-900 text-lg">Ajukan Izin</h3>
                    <p class="text-sm text-gray-600">Cuti, sakit, izin</p>
                </div>
            </div>
        </a>
    </div>

    <!-- PENDING NOTIFICATIONS -->
    @if($pendingComplaints > 0)
    <div class="bg-gradient-to-r from-warning-50 to-warning-100 rounded-2xl border border-warning-300 p-6 shadow-soft hover:shadow-medium transition-all duration-300 animate-fade-in-up" style="animation-delay: 0.6s;">
        <div class="flex items-start gap-4">
            <div class="bg-gradient-warning rounded-lg p-3 flex-shrink-0 text-white shadow-glow animate-pulse">
                <i class="fas fa-bell text-lg"></i>
            </div>
            <div class="flex-1">
                <h3 class="font-bold text-warning-900 mb-1 text-lg">⚠️ Pengajuan Menunggu Persetujuan</h3>
                <p class="text-warning-800 mb-3">Anda memiliki <strong>{{ $pendingComplaints }} pengajuan</strong> yang masih menunggu persetujuan dari admin. Silakan pantau status pengajuan Anda.</p>
                <a href="{{ route('complaints.history') }}" class="inline-block bg-warning-600 hover:bg-warning-700 text-white px-4 py-2 rounded-lg font-semibold transition-colors">
                    Lihat Detail Pengajuan <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
        </div>
    </div>
    @endif

    <!-- ADMIN STATS (if user is admin/manager) -->
    @if($overallStats)
    <div class="animate-fade-in-up" style="animation-delay: 0.65s;">
        <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
            <i class="fas fa-chart-pie text-primary-600"></i>
            Statistik Perusahaan
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-gradient-to-br from-primary-50 to-primary-100 rounded-2xl p-6 border border-primary-200 shadow-soft hover:shadow-card-hover transition-all duration-300">
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

            <div class="bg-gradient-to-br from-success-50 to-success-100 rounded-2xl p-6 border border-success-200 shadow-soft hover:shadow-card-hover transition-all duration-300">
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

            <div class="bg-gradient-to-br from-warning-50 to-warning-100 rounded-2xl p-6 border border-warning-200 shadow-soft hover:shadow-card-hover transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-warning-600 text-sm font-semibold uppercase tracking-wide mb-2">Perlu Diproses</p>
                        <p class="text-3xl font-bold text-warning-900">{{ $overallStats['pending_complaints'] }}</p>
                    </div>
                    <div class="text-5xl text-warning-200 opacity-50 animate-pulse">
                        <i class="fas fa-hourglass-half"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Recent Attendance History -->
    <div class="bg-white rounded-2xl shadow-soft border border-gray-100 overflow-hidden animate-fade-in-up" style="animation-delay: 0.7s;">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gradient-to-r from-gray-50 to-white">
            <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                <i class="fas fa-history text-primary-600"></i>
                Riwayat Absensi 7 Hari Terakhir
            </h2>
            <a href="{{ route('attendance.riwayat') }}" class="text-primary-600 hover:text-primary-700 text-sm font-semibold transition-colors flex items-center gap-1">
                Lihat Semua <i class="fas fa-arrow-right"></i>
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">Check-in</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">Check-out</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">Durasi</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($recentAttendances as $attendance)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">
                            {{ $attendance->date->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ $attendance->check_in ?? '-' }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ $attendance->check_out ?? '-' }}
                        </td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">
                            @if($attendance->work_hours)
                                {{ number_format($attendance->work_hours, 1) }} jam
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <span class="inline-block px-3 py-1 rounded-full text-xs font-bold
                                @if($attendance->status === 'present') bg-success-100 text-success-700
                                @elseif($attendance->status === 'late') bg-warning-100 text-warning-700
                                @elseif($attendance->status === 'absent') bg-danger-100 text-danger-700
                                @elseif($attendance->status === 'work_leave') bg-primary-100 text-primary-700
                                @else bg-gray-100 text-gray-700
                                @endif
                            ">
                                @if($attendance->status === 'present') Hadir
                                @elseif($attendance->status === 'late') Terlambat
                                @elseif($attendance->status === 'absent') Absen
                                @elseif($attendance->status === 'work_leave') Izin Kerja
                                @else {{ ucfirst($attendance->status) }}
                                @endif
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-500">
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
@endsection
