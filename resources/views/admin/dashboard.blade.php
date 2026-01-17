@extends('admin.layout')

@section('title', 'Admin Dashboard')

@section('content')
<div class="space-y-8 animate-fade-in-up">
    <!-- Welcome Section -->
    <div class="bg-gradient-vibrant rounded-2xl p-8 text-white shadow-lg hover:shadow-xl transition-all duration-300">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold mb-2 font-display">Admin Dashboard 🎯</h1>
                <p class="text-white/80">Kelola sistem absensi dan data karyawan dengan mudah</p>
            </div>
            <div class="hidden md:block text-6xl opacity-20 animate-float">
                <i class="fas fa-chart-line"></i>
            </div>
        </div>
    </div>

    <!-- Main Stats Grid -->
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
                <h3 class="text-sm font-semibold text-warning-700 uppercase tracking-wide">Terlambat</h3>
                <div class="bg-gradient-warning rounded-lg p-3 text-white shadow-glow">
                    <i class="fas fa-clock text-lg"></i>
                </div>
            </div>
            <div class="text-4xl font-bold text-warning-900 mb-2">{{ $lateToday }}</div>
            <p class="text-warning-700 text-sm font-medium">Karyawan terlambat</p>
        </div>
    </div>

    <!-- Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Quick Access Section -->
        <div class="lg:col-span-2 animate-fade-in-up" style="animation-delay: 0.2s;">
            <div class="bg-white rounded-2xl shadow-soft hover:shadow-card-hover border border-gray-100 overflow-hidden transition-all duration-300">
                <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                    <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                        <i class="fas fa-bolt text-primary-600"></i>
                        Akses Cepat
                    </h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Kelola User -->
                        <a href="{{ route('admin.users.index') }}" class="group bg-gradient-to-br from-success-50 to-success-100 rounded-xl p-4 border border-success-200 hover:shadow-lg hover:scale-105 transition-all duration-300 active:scale-95">
                            <div class="flex items-center gap-4">
                                <div class="bg-gradient-success rounded-lg p-3 group-hover:scale-110 transition-transform text-white shadow-glow-success">
                                    <i class="fas fa-user-plus text-lg"></i>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-900">Kelola User</h3>
                                    <p class="text-sm text-gray-600">Manajemen pengguna</p>
                                </div>
                            </div>
                        </a>

                        <!-- Pengajuan Izin -->
                        <a href="{{ route('admin.complaints.index') }}" class="group bg-gradient-to-br from-warning-50 to-warning-100 rounded-xl p-4 border border-warning-200 hover:shadow-lg hover:scale-105 transition-all duration-300 active:scale-95">
                            <div class="flex items-center gap-4">
                                <div class="bg-gradient-warning rounded-lg p-3 group-hover:scale-110 transition-transform text-white shadow-glow">
                                    <i class="fas fa-file-signature text-lg"></i>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-900">Pengajuan Izin</h3>
                                    <p class="text-sm text-gray-600">Review izin & cuti</p>
                                </div>
                            </div>
                        </a>

                        <!-- Laporan Kerja -->
                        <a href="{{ route('reports.users') }}" class="group bg-gradient-to-br from-primary-50 to-primary-100 rounded-xl p-4 border border-primary-200 hover:shadow-lg hover:scale-105 transition-all duration-300 active:scale-95">
                            <div class="flex items-center gap-4">
                                <div class="bg-gradient-primary rounded-lg p-3 group-hover:scale-110 transition-transform text-white shadow-glow">
                                    <i class="fas fa-chart-pie text-lg"></i>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-900">Laporan Kerja</h3>
                                    <p class="text-sm text-gray-600">Analisa data harian</p>
                                </div>
                            </div>
                        </a>

                        <!-- Jadwal Shift -->
                        <a href="{{ route('admin.shifts.index') }}" class="group bg-gradient-to-br from-accent-50 to-accent-100 rounded-xl p-4 border border-accent-200 hover:shadow-lg hover:scale-105 transition-all duration-300 active:scale-95">
                            <div class="flex items-center gap-4">
                                <div class="bg-gradient-accent rounded-lg p-3 group-hover:scale-110 transition-transform text-white shadow-glow-accent">
                                    <i class="fas fa-calendar-alt text-lg"></i>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-900">Jadwal Shift</h3>
                                    <p class="text-sm text-gray-600">Atur jam kerja</p>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Leave Status Section -->
        <div class="bg-white rounded-2xl shadow-soft hover:shadow-card-hover border border-gray-100 overflow-hidden transition-all duration-300 animate-fade-in-up" style="animation-delay: 0.3s;">
            <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                    <i class="fas fa-file-alt text-primary-600"></i>
                    Status Izin & Cuti
                </h2>
            </div>
            <div class="p-6 space-y-3">
                <!-- Pending -->
                <a href="{{ route('admin.complaints.index', ['status' => 'pending']) }}" class="block bg-gradient-to-r from-warning-50 to-warning-100 rounded-xl p-4 border border-warning-200 hover:shadow-lg hover:scale-105 transition-all duration-300 active:scale-95">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-warning-700 font-semibold">Pending</p>
                            <p class="text-2xl font-bold text-warning-600">{{ $pendingLeaveRequests }}</p>
                        </div>
                        <i class="fas fa-hourglass-half text-warning-300 text-2xl animate-pulse"></i>
                    </div>
                </a>

                <!-- Approved -->
                <a href="{{ route('admin.complaints.index', ['status' => 'approved']) }}" class="block bg-gradient-to-r from-success-50 to-success-100 rounded-xl p-4 border border-success-200 hover:shadow-lg hover:scale-105 transition-all duration-300 active:scale-95">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-success-700 font-semibold">Disetujui</p>
                            <p class="text-2xl font-bold text-success-600">{{ $approvedLeaveRequests }}</p>
                        </div>
                        <i class="fas fa-check-circle text-success-300 text-2xl"></i>
                    </div>
                </a>

                <!-- Rejected -->
                <a href="{{ route('admin.complaints.index', ['status' => 'rejected']) }}" class="block bg-gradient-to-r from-danger-50 to-danger-100 rounded-xl p-4 border border-danger-200 hover:shadow-lg hover:scale-105 transition-all duration-300 active:scale-95">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-danger-700 font-semibold">Ditolak</p>
                            <p class="text-2xl font-bold text-danger-600">{{ $rejectedLeaveRequests }}</p>
                        </div>
                        <i class="fas fa-times-circle text-danger-300 text-2xl"></i>
                    </div>
                </a>

                <!-- Total This Month -->
                <div class="bg-gradient-primary rounded-xl p-4 text-white mt-4 shadow-glow hover:shadow-glow-lg transition-all duration-300">
                    <p class="text-sm opacity-90 font-semibold mb-1">Total Izin Bulan Ini</p>
                    <p class="text-3xl font-bold">{{ $workLeaveThisMonth }}</p>
                    <p class="text-sm opacity-75 mt-1">Pengajuan</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Complaints Table -->
    @if($recentComplaints->count() > 0)
    <div class="bg-white rounded-2xl shadow-soft hover:shadow-card-hover border border-gray-100 overflow-hidden transition-all duration-300 animate-fade-in-up" style="animation-delay: 0.4s;">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gradient-to-r from-gray-50 to-white">
            <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                <i class="fas fa-inbox text-primary-600"></i>
                Pengajuan Terbaru (Pending)
            </h2>
            <a href="{{ route('admin.complaints.index') }}" class="text-primary-600 hover:text-primary-700 text-sm font-semibold transition-colors">
                Lihat Semua <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">Karyawan</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">Kategori</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">Judul</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wide">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($recentComplaints as $complaint)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 text-sm text-gray-600 font-medium">
                            {{ $complaint->created_at->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-primary-100 text-primary-600 flex items-center justify-center text-xs font-bold">
                                    {{ strtoupper(substr($complaint->user->name, 0, 2)) }}
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900 text-sm">{{ $complaint->user->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $complaint->user->employee_id }}</p>
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
                            <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold {{ $badgeClass }}">
                                {{ ucfirst($complaint->category) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ Str::limit($complaint->title, 40) }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <a href="{{ route('admin.complaints.show', $complaint->id) }}" class="inline-block bg-primary-100 text-primary-600 hover:bg-primary-200 p-2 rounded-lg transition-colors">
                                <i class="fas fa-external-link-alt"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>
@endsection
