@extends('admin.layout')

@section('title', 'Admin Dashboard')

@section('content')
<div class="space-y-8 animate-fade-in-up">
    <!-- Welcome Section -->
    <div class="bg-gradient-vibrant rounded-2xl p-8 text-white shadow-lg hover:shadow-xl transition-all duration-300">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold mb-2 font-display">Admin Dashboard 🎯</h1>
                <p class="text-white/80">Kelola sistem absensi, karyawan, dan pengajuan dengan mudah</p>
            </div>
            <div class="hidden md:block text-6xl opacity-20 animate-float">
                <i class="fas fa-chart-line"></i>
            </div>
        </div>
    </div>

    <!-- MAIN KPI CARDS -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Users -->
        <div class="bg-gradient-to-br from-primary-50 to-primary-100 rounded-2xl p-6 shadow-soft hover:shadow-card-hover transition-all duration-300 border border-primary-200 animate-fade-in-up" style="animation-delay: 0.1s;">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-primary-700 uppercase tracking-wide">Total Pengguna</h3>
                <div class="bg-gradient-primary rounded-lg p-3 text-white shadow-glow">
                    <i class="fas fa-users text-lg"></i>
                </div>
            </div>
            <div class="text-4xl font-bold text-primary-900 mb-2">{{ $userCount }}</div>
            <p class="text-primary-700 text-sm font-medium">Karyawan terdaftar</p>
        </div>

        <!-- Total Admin -->
        <div class="bg-gradient-to-br from-success-50 to-success-100 rounded-2xl p-6 shadow-soft hover:shadow-card-hover transition-all duration-300 border border-success-200 animate-fade-in-up" style="animation-delay: 0.2s;">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-success-700 uppercase tracking-wide">Total Admin</h3>
                <div class="bg-gradient-success rounded-lg p-3 text-white shadow-glow-success">
                    <i class="fas fa-user-shield text-lg"></i>
                </div>
            </div>
            <div class="text-4xl font-bold text-success-900 mb-2">{{ $adminCount }}</div>
            <p class="text-success-700 text-sm font-medium">Admin aktif</p>
        </div>

        <!-- Attendance Today -->
        <div class="bg-gradient-to-br from-accent-50 to-accent-100 rounded-2xl p-6 shadow-soft hover:shadow-card-hover transition-all duration-300 border border-accent-200 animate-fade-in-up" style="animation-delay: 0.3s;">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-accent-700 uppercase tracking-wide">Hadir Hari Ini</h3>
                <div class="bg-gradient-accent rounded-lg p-3 text-white shadow-glow-accent">
                    <i class="fas fa-check-circle text-lg"></i>
                </div>
            </div>
            <div class="text-4xl font-bold text-accent-900 mb-2">{{ $attendanceToday }}</div>
            <p class="text-accent-700 text-sm font-medium">Karyawan hadir</p>
        </div>

        <!-- Late Today -->
        <div class="bg-gradient-to-br from-warning-50 to-warning-100 rounded-2xl p-6 shadow-soft hover:shadow-card-hover transition-all duration-300 border border-warning-200 animate-fade-in-up" style="animation-delay: 0.4s;">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-warning-700 uppercase tracking-wide">Terlambat Hari Ini</h3>
                <div class="bg-gradient-warning rounded-lg p-3 text-white shadow-glow">
                    <i class="fas fa-clock text-lg"></i>
                </div>
            </div>
            <div class="text-4xl font-bold text-warning-900 mb-2">{{ $lateToday }}</div>
            <p class="text-warning-700 text-sm font-medium">Kehadiran terlambat</p>
        </div>
    </div>

    <!-- QUICK MANAGEMENT ACTIONS -->
    <div class="animate-fade-in-up" style="animation-delay: 0.5s;">
        <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
            <i class="fas fa-bolt text-primary-600"></i>
            Manajemen Cepat
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Kelola User -->
            <a href="{{ route('admin.users.index') }}" class="group bg-white rounded-xl p-5 border border-gray-200 shadow-soft hover:shadow-lg hover:scale-105 transition-all duration-300 active:scale-95">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-lg bg-success-100 text-success-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="fas fa-user-plus text-lg"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900">Kelola User</h3>
                        <p class="text-xs text-gray-600">Manajemen karyawan</p>
                    </div>
                </div>
            </a>

            <!-- Kelola Shift -->
            <a href="{{ route('admin.shifts.index') }}" class="group bg-white rounded-xl p-5 border border-gray-200 shadow-soft hover:shadow-lg hover:scale-105 transition-all duration-300 active:scale-95">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-lg bg-accent-100 text-accent-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="fas fa-calendar-alt text-lg"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900">Jadwal Shift</h3>
                        <p class="text-xs text-gray-600">Atur jam kerja</p>
                    </div>
                </div>
            </a>

            <!-- Kelola Peran -->
            <a href="{{ route('admin.roles.index') }}" class="group bg-white rounded-xl p-5 border border-gray-200 shadow-soft hover:shadow-lg hover:scale-105 transition-all duration-300 active:scale-95">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-lg bg-primary-100 text-primary-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="fas fa-shield-alt text-lg"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900">Peran & Izin</h3>
                        <p class="text-xs text-gray-600">Kelola akses</p>
                    </div>
                </div>
            </a>

            <!-- Laporan -->
            <a href="{{ route('reports.users') }}" class="group bg-white rounded-xl p-5 border border-gray-200 shadow-soft hover:shadow-lg hover:scale-105 transition-all duration-300 active:scale-95">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-lg bg-info-100 text-info-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="fas fa-chart-bar text-lg"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900">Laporan</h3>
                        <p class="text-xs text-gray-600">Analisa data</p>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- LEAVE & REQUEST MANAGEMENT -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Leave Request Status -->
        <div class="bg-white rounded-2xl shadow-soft border border-gray-100 overflow-hidden animate-fade-in-up" style="animation-delay: 0.6s;">
            <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                    <i class="fas fa-file-signature text-primary-600"></i>
                    Status Pengajuan Izin & Cuti
                </h2>
            </div>
            <div class="p-6 space-y-3">
                <!-- Pending -->
                <a href="{{ route('admin.complaints.index', ['status' => 'pending']) }}" class="group block bg-gradient-to-r from-warning-50 to-warning-100 rounded-xl p-4 border border-warning-200 hover:shadow-lg hover:scale-105 transition-all duration-300 active:scale-95">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-bold text-warning-700 uppercase tracking-wide mb-1">Menunggu Persetujuan</p>
                            <p class="text-3xl font-bold text-warning-900">{{ $pendingLeaveRequests }}</p>
                        </div>
                        <div class="text-4xl text-warning-200 opacity-50 group-hover:opacity-100 transition-opacity animate-pulse">
                            <i class="fas fa-hourglass-half"></i>
                        </div>
                    </div>
                </a>

                <!-- Approved -->
                <a href="{{ route('admin.complaints.index', ['status' => 'approved']) }}" class="group block bg-gradient-to-r from-success-50 to-success-100 rounded-xl p-4 border border-success-200 hover:shadow-lg hover:scale-105 transition-all duration-300 active:scale-95">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-bold text-success-700 uppercase tracking-wide mb-1">Disetujui</p>
                            <p class="text-3xl font-bold text-success-900">{{ $approvedLeaveRequests }}</p>
                        </div>
                        <div class="text-4xl text-success-200 opacity-50 group-hover:opacity-100 transition-opacity">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                </a>

                <!-- Rejected -->
                <a href="{{ route('admin.complaints.index', ['status' => 'rejected']) }}" class="group block bg-gradient-to-r from-danger-50 to-danger-100 rounded-xl p-4 border border-danger-200 hover:shadow-lg hover:scale-105 transition-all duration-300 active:scale-95">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-bold text-danger-700 uppercase tracking-wide mb-1">Ditolak</p>
                            <p class="text-3xl font-bold text-danger-900">{{ $rejectedLeaveRequests }}</p>
                        </div>
                        <div class="text-4xl text-danger-200 opacity-50 group-hover:opacity-100 transition-opacity">
                            <i class="fas fa-times-circle"></i>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Work Leave Status -->
        <div class="bg-white rounded-2xl shadow-soft border border-gray-100 overflow-hidden animate-fade-in-up" style="animation-delay: 0.7s;">
            <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                    <i class="fas fa-briefcase text-accent-600"></i>
                    Izin Kerja
                </h2>
            </div>
            <div class="p-6 space-y-3">
                <!-- Work Leave Today -->
                <div class="bg-gradient-to-r from-primary-50 to-primary-100 rounded-xl p-4 border border-primary-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-bold text-primary-700 uppercase tracking-wide mb-1">Hari Ini</p>
                            <p class="text-3xl font-bold text-primary-900">{{ $workLeaveToday }}</p>
                        </div>
                        <div class="text-4xl text-primary-200 opacity-50">
                            <i class="fas fa-calendar-day"></i>
                        </div>
                    </div>
                </div>

                <!-- Work Leave This Month -->
                <a href="{{ route('admin.work-leave.index') }}" class="group block bg-gradient-to-r from-accent-50 to-accent-100 rounded-xl p-4 border border-accent-200 hover:shadow-lg hover:scale-105 transition-all duration-300 active:scale-95">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-bold text-accent-700 uppercase tracking-wide mb-1">Bulan Ini</p>
                            <p class="text-3xl font-bold text-accent-900">{{ $workLeaveThisMonth }}</p>
                        </div>
                        <div class="text-4xl text-accent-200 opacity-50 group-hover:opacity-100 transition-opacity">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                    </div>
                </a>

                <!-- View All Work Leave -->
                <a href="{{ route('admin.work-leave.index') }}" class="group block bg-white rounded-xl p-4 border border-gray-200 hover:shadow-lg hover:scale-105 transition-all duration-300 active:scale-95">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center group-hover:scale-110 transition-transform">
                                <i class="fas fa-list text-gray-600"></i>
                            </div>
                            <div>
                                <p class="font-bold text-gray-900">Lihat Semua Pengajuan</p>
                                <p class="text-xs text-gray-600">Kelola izin kerja</p>
                            </div>
                        </div>
                        <i class="fas fa-arrow-right text-gray-400 group-hover:text-gray-600 transition-colors"></i>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <!-- COMPLAINT STATISTICS -->
    <div class="bg-white rounded-2xl shadow-soft border border-gray-100 p-6 animate-fade-in-up" style="animation-delay: 0.8s;">
        <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
            <i class="fas fa-exclamation-circle text-warning-600"></i>
            Statistik Keluhan Teknis & Administrasi
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-gradient-to-br from-warning-50 to-warning-100 rounded-xl p-4 border border-warning-200">
                <p class="text-sm font-bold text-warning-700 uppercase tracking-wide mb-1">Pending</p>
                <p class="text-3xl font-bold text-warning-900">{{ $pendingComplaints }}</p>
                <p class="text-xs text-warning-700 mt-1">Menunggu ditangani</p>
            </div>

            <div class="bg-gradient-to-br from-info-50 to-info-100 rounded-xl p-4 border border-info-200">
                <p class="text-sm font-bold text-info-700 uppercase tracking-wide mb-1">Resolved</p>
                <p class="text-3xl font-bold text-info-900">{{ $resolvedComplaints }}</p>
                <p class="text-xs text-info-700 mt-1">Selesai ditangani</p>
            </div>

            <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl p-4 border border-gray-200">
                <p class="text-sm font-bold text-gray-700 uppercase tracking-wide mb-1">Closed</p>
                <p class="text-3xl font-bold text-gray-900">{{ $closedComplaints }}</p>
                <p class="text-xs text-gray-700 mt-1">Ditutup</p>
            </div>
        </div>
    </div>

    <!-- RECENT PENDING REQUESTS TABLE -->
    @if($recentComplaints->count() > 0)
    <div class="bg-white rounded-2xl shadow-soft border border-gray-100 overflow-hidden animate-fade-in-up" style="animation-delay: 0.9s;">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gradient-to-r from-gray-50 to-white">
            <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                <i class="fas fa-inbox text-warning-600"></i>
                Pengajuan Terbaru yang Menunggu ({{ $recentComplaints->total() }} Pending)
            </h2>
            <a href="{{ route('admin.complaints.index') }}" class="text-primary-600 hover:text-primary-700 text-sm font-bold transition-colors flex items-center gap-1">
                Lihat Semua <i class="fas fa-arrow-right"></i>
            </a>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">Karyawan</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">Jenis</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">Judul/Deskripsi</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wide">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($recentComplaints as $complaint)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 text-sm font-medium text-gray-600">
                            {{ $complaint->created_at->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-primary-100 text-primary-600 flex items-center justify-center text-xs font-bold">
                                    {{ strtoupper(substr($complaint->user->name ?? 'N/A', 0, 2)) }}
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900 text-sm">{{ $complaint->user->name ?? 'Unknown' }}</p>
                                    <p class="text-xs text-gray-500">{{ $complaint->user->employee_id ?? '-' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $badgeClass = match($complaint->category) {
                                    'cuti' => 'bg-info-100 text-info-700',
                                    'sakit' => 'bg-danger-100 text-danger-700',
                                    'izin' => 'bg-warning-100 text-warning-700',
                                    default => 'bg-success-100 text-success-700'
                                };
                            @endphp
                            <span class="inline-block px-3 py-1 rounded-full text-xs font-bold {{ $badgeClass }}">
                                {{ ucfirst(str_replace('_', ' ', $complaint->category)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            <p class="font-medium">{{ Str::limit($complaint->title, 50) }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ Str::limit($complaint->description, 60) }}</p>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <a href="{{ route('admin.complaints.show', $complaint->id) }}" class="inline-block bg-primary-100 text-primary-600 hover:bg-primary-200 px-3 py-2 rounded-lg transition-colors font-bold text-sm">
                                Lihat Detail
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($recentComplaints->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
            {{ $recentComplaints->links() }}
        </div>
        @endif
    </div>
    @else
    <div class="bg-white rounded-2xl shadow-soft border border-gray-100 p-8 text-center animate-fade-in-up" style="animation-delay: 0.9s;">
        <i class="fas fa-check-circle text-4xl text-success-400 mb-3"></i>
        <p class="text-gray-600 font-semibold">Tidak ada pengajuan yang menunggu persetujuan</p>
        <p class="text-sm text-gray-500 mt-1">Semua pengajuan sudah ditangani. Bagus!</p>
    </div>
    @endif
</div>
@endsection
