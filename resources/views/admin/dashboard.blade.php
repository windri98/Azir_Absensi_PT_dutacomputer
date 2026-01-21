@extends('admin.layout')

@section('title', 'Admin Dashboard')

@section('content')
<div class="space-y-5">
    <!-- Welcome Section -->
    <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-soft">
        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 mb-1">Admin Dashboard</h1>
                <p class="text-sm text-gray-600">Ringkasan operasional absensi dan pengajuan hari ini.</p>
            </div>
            <div class="flex items-center gap-2 text-sm text-gray-500">
                <span class="inline-flex items-center gap-2 px-3 py-2 rounded-full border border-gray-200 bg-gray-50">
                    <i class="fas fa-calendar-day text-primary-600"></i>
                    {{ now()->format('d M Y') }}
                </span>
            </div>
        </div>
    </div>

    <!-- MAIN KPI CARDS -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Total Users -->
        <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-soft min-h-[120px]">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Total Pengguna</h3>
                <div class="bg-primary-100 text-primary-600 rounded-lg p-2">
                    <i class="fas fa-users text-sm"></i>
                </div>
            </div>
            <div class="text-3xl font-bold text-gray-900">{{ $userCount }}</div>
            <p class="text-xs text-gray-500 mt-1">Karyawan terdaftar</p>
        </div>

        <!-- Total Admin -->
        <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-soft min-h-[120px]">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Total Admin</h3>
                <div class="bg-success-100 text-success-600 rounded-lg p-2">
                    <i class="fas fa-user-shield text-sm"></i>
                </div>
            </div>
            <div class="text-3xl font-bold text-gray-900">{{ $adminCount }}</div>
            <p class="text-xs text-gray-500 mt-1">Admin aktif</p>
        </div>

        <!-- Attendance Today -->
        <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-soft min-h-[120px]">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Hadir Hari Ini</h3>
                <div class="bg-accent-100 text-accent-600 rounded-lg p-2">
                    <i class="fas fa-check-circle text-sm"></i>
                </div>
            </div>
            <div class="text-3xl font-bold text-gray-900">{{ $attendanceToday }}</div>
            <p class="text-xs text-gray-500 mt-1">Karyawan hadir</p>
        </div>

        <!-- Late Today -->
        <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-soft min-h-[120px]">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Terlambat Hari Ini</h3>
                <div class="bg-warning-100 text-warning-600 rounded-lg p-2">
                    <i class="fas fa-clock text-sm"></i>
                </div>
            </div>
            <div class="text-3xl font-bold text-gray-900">{{ $lateToday }}</div>
            <p class="text-xs text-gray-500 mt-1">Kehadiran terlambat</p>
        </div>
    </div>

    <!-- QUICK MANAGEMENT ACTIONS -->
    <div>
        <div class="flex items-center justify-between mb-3">
            <h2 class="text-base font-semibold text-gray-900">Manajemen Cepat</h2>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Kelola User -->
            <a href="{{ route('admin.users.index') }}" class="group bg-white rounded-xl p-5 border border-gray-200 shadow-soft hover:shadow-md transition-all duration-300">
                <div class="flex items-center gap-4 min-h-[72px]">
                    <div class="w-11 h-11 rounded-lg bg-success-100 text-success-600 flex items-center justify-center">
                        <i class="fas fa-user-plus text-base"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900">Kelola User</h3>
                        <p class="text-xs text-gray-600">Manajemen karyawan</p>
                    </div>
                </div>
            </a>

            <!-- Kelola Shift -->
            <a href="{{ route('admin.shifts.index') }}" class="group bg-white rounded-xl p-5 border border-gray-200 shadow-soft hover:shadow-md transition-all duration-300">
                <div class="flex items-center gap-4 min-h-[72px]">
                    <div class="w-11 h-11 rounded-lg bg-accent-100 text-accent-600 flex items-center justify-center">
                        <i class="fas fa-calendar-alt text-base"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900">Jadwal Shift</h3>
                        <p class="text-xs text-gray-600">Atur jam kerja</p>
                    </div>
                </div>
            </a>

            <!-- Kelola Peran -->
            <a href="{{ route('admin.roles.index') }}" class="group bg-white rounded-xl p-5 border border-gray-200 shadow-soft hover:shadow-md transition-all duration-300">
                <div class="flex items-center gap-4 min-h-[72px]">
                    <div class="w-11 h-11 rounded-lg bg-primary-100 text-primary-600 flex items-center justify-center">
                        <i class="fas fa-shield-alt text-base"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900">Peran & Izin</h3>
                        <p class="text-xs text-gray-600">Kelola akses</p>
                    </div>
                </div>
            </a>

            <!-- Laporan -->
            <a href="{{ route('reports.users') }}" class="group bg-white rounded-xl p-5 border border-gray-200 shadow-soft hover:shadow-md transition-all duration-300">
                <div class="flex items-center gap-4 min-h-[72px]">
                    <div class="w-11 h-11 rounded-lg bg-warning-100 text-warning-600 flex items-center justify-center">
                        <i class="fas fa-chart-bar text-base"></i>
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
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Leave Request Status -->
        <div class="bg-white rounded-2xl shadow-soft border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h2 class="text-base font-semibold text-gray-900">Status Pengajuan Izin & Cuti</h2>
            </div>
            <div class="p-6 space-y-3">
                <!-- Pending -->
                <a href="{{ route('admin.complaints.index', ['status' => 'pending']) }}" class="group block bg-warning-50 rounded-xl p-4 border border-warning-200 hover:shadow-md transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-bold text-warning-700 uppercase tracking-wide mb-1">Menunggu Persetujuan</p>
                            <p class="text-3xl font-bold text-warning-900">{{ $pendingLeaveRequests }}</p>
                        </div>
                        <div class="text-3xl text-warning-300 group-hover:text-warning-500 transition-colors">
                            <i class="fas fa-hourglass-half"></i>
                        </div>
                    </div>
                </a>

                <!-- Approved -->
                <a href="{{ route('admin.complaints.index', ['status' => 'approved']) }}" class="group block bg-success-50 rounded-xl p-4 border border-success-200 hover:shadow-md transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-bold text-success-700 uppercase tracking-wide mb-1">Disetujui</p>
                            <p class="text-3xl font-bold text-success-900">{{ $approvedLeaveRequests }}</p>
                        </div>
                        <div class="text-3xl text-success-300 group-hover:text-success-500 transition-colors">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                </a>

                <!-- Rejected -->
                <a href="{{ route('admin.complaints.index', ['status' => 'rejected']) }}" class="group block bg-danger-50 rounded-xl p-4 border border-danger-200 hover:shadow-md transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-bold text-danger-700 uppercase tracking-wide mb-1">Ditolak</p>
                            <p class="text-3xl font-bold text-danger-900">{{ $rejectedLeaveRequests }}</p>
                        </div>
                        <div class="text-3xl text-danger-300 group-hover:text-danger-500 transition-colors">
                            <i class="fas fa-times-circle"></i>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Work Leave Status -->
        <div class="bg-white rounded-2xl shadow-soft border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h2 class="text-base font-semibold text-gray-900">Izin Kerja</h2>
            </div>
            <div class="p-6 space-y-3">
                <!-- Work Leave Today -->
                <div class="bg-primary-50 rounded-xl p-4 border border-primary-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-bold text-primary-700 uppercase tracking-wide mb-1">Hari Ini</p>
                            <p class="text-3xl font-bold text-primary-900">{{ $workLeaveToday }}</p>
                        </div>
                        <div class="text-3xl text-primary-300">
                            <i class="fas fa-calendar-day"></i>
                        </div>
                    </div>
                </div>

                <!-- Work Leave This Month -->
                <a href="{{ route('admin.work-leave.index') }}" class="group block bg-accent-50 rounded-xl p-4 border border-accent-200 hover:shadow-md transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-bold text-accent-700 uppercase tracking-wide mb-1">Bulan Ini</p>
                            <p class="text-3xl font-bold text-accent-900">{{ $workLeaveThisMonth }}</p>
                        </div>
                        <div class="text-3xl text-accent-300 group-hover:text-accent-500 transition-colors">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                    </div>
                </a>

                <!-- View All Work Leave -->
                <a href="{{ route('admin.work-leave.index') }}" class="group block bg-white rounded-xl p-4 border border-gray-200 hover:shadow-md transition-all duration-300">
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
    <div class="bg-white rounded-2xl shadow-soft border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-base font-semibold text-gray-900">Statistik Keluhan Teknis & Administrasi</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-warning-50 rounded-xl p-4 border border-warning-200">
                <p class="text-xs font-semibold text-warning-700 uppercase tracking-wide mb-1">Pending</p>
                <p class="text-3xl font-bold text-warning-900">{{ $pendingComplaints }}</p>
                <p class="text-xs text-warning-700 mt-1">Menunggu ditangani</p>
            </div>

            <div class="bg-primary-50 rounded-xl p-4 border border-primary-200">
                <p class="text-xs font-semibold text-primary-700 uppercase tracking-wide mb-1">Resolved</p>
                <p class="text-3xl font-bold text-primary-900">{{ $resolvedComplaints }}</p>
                <p class="text-xs text-primary-700 mt-1">Selesai ditangani</p>
            </div>

            <div class="bg-gray-50 rounded-xl p-4 border border-gray-200">
                <p class="text-xs font-semibold text-gray-700 uppercase tracking-wide mb-1">Closed</p>
                <p class="text-3xl font-bold text-gray-900">{{ $closedComplaints }}</p>
                <p class="text-xs text-gray-700 mt-1">Ditutup</p>
            </div>
        </div>
    </div>

    <!-- RECENT PENDING REQUESTS TABLE -->
    @if($recentComplaints->count() > 0)
    <div class="bg-white rounded-2xl shadow-soft border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <div>
                <h2 class="text-base font-semibold text-gray-900">Pengajuan Terbaru yang Menunggu</h2>
                <p class="text-xs text-gray-500 mt-1">{{ $recentComplaints->total() }} pengajuan pending</p>
            </div>
            <a href="{{ route('admin.complaints.index') }}" class="text-primary-600 hover:text-primary-700 text-sm font-semibold transition-colors flex items-center gap-1">
                Lihat Semua <i class="fas fa-arrow-right"></i>
            </a>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-[11px] font-semibold text-gray-600 uppercase tracking-wide">Tanggal</th>
                        <th class="px-6 py-3 text-left text-[11px] font-semibold text-gray-600 uppercase tracking-wide">Karyawan</th>
                        <th class="px-6 py-3 text-left text-[11px] font-semibold text-gray-600 uppercase tracking-wide">Jenis</th>
                        <th class="px-6 py-3 text-left text-[11px] font-semibold text-gray-600 uppercase tracking-wide">Judul/Deskripsi</th>
                        <th class="px-6 py-3 text-center text-[11px] font-semibold text-gray-600 uppercase tracking-wide">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($recentComplaints as $complaint)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 text-sm text-gray-600">
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
                                    'cuti' => 'bg-primary-100 text-primary-700',
                                    'sakit' => 'bg-danger-100 text-danger-700',
                                    'izin' => 'bg-warning-100 text-warning-700',
                                    default => 'bg-success-100 text-success-700'
                                };
                            @endphp
                            <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold {{ $badgeClass }}">
                                {{ ucfirst(str_replace('_', ' ', $complaint->category)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            <p class="font-medium">{{ Str::limit($complaint->title, 50) }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ Str::limit($complaint->description, 60) }}</p>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <a href="{{ route('admin.complaints.show', $complaint->id) }}" class="inline-flex items-center justify-center bg-primary-100 text-primary-700 hover:bg-primary-200 px-3 py-2 rounded-lg transition-colors font-semibold text-xs">
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
    <div class="bg-white rounded-2xl shadow-soft border border-gray-200 p-8 text-center">
        <div class="w-12 h-12 mx-auto rounded-full bg-success-100 text-success-600 flex items-center justify-center mb-3">
            <i class="fas fa-check"></i>
        </div>
        <p class="text-gray-700 font-semibold">Tidak ada pengajuan yang menunggu</p>
        <p class="text-sm text-gray-500 mt-1">Semua pengajuan sudah ditangani</p>
    </div>
    @endif
</div>
@endsection
