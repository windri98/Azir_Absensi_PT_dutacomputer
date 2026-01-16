@extends('layouts.app')

@section('title', 'Aktivitas - Sistem Absensi')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/activities.css') }}">
@endpush

@section('content')
    <div class="px-4 py-8 lg:px-8 max-w-5xl mx-auto">
        <!-- Page Header -->
        <div class="flex items-center gap-4 mb-8">
            <button class="w-10 h-10 rounded-xl bg-white border border-gray-200 flex items-center justify-center text-gray-600 hover:bg-gray-50 transition-colors" onclick="history.back()">
                <i class="fas fa-chevron-left"></i>
            </button>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Pusat Aktivitas</h1>
                <p class="text-sm text-gray-500">Akses cepat ke semua layanan dan fitur.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
            <!-- Attendance History -->
            <a href="{{ route('attendance.riwayat') }}" class="activity-card hover:scale-[1.02]">
                <div class="activity-header">
                    <div class="activity-icon bg-blue-100 text-blue-600">
                        <i class="fas fa-history"></i>
                    </div>
                    <div>
                        <h3 class="activity-title">Riwayat Absensi</h3>
                        <p class="activity-meta">Lihat rekam jejak kehadiran</p>
                    </div>
                </div>
                <div class="w-full h-1 bg-gray-50 rounded-full overflow-hidden">
                    <div class="w-full h-full bg-blue-500"></div>
                </div>
            </a>
            
            <!-- Leave Request -->
            <a href="{{ route('leave.index') }}" class="activity-card hover:scale-[1.02]">
                <div class="activity-header">
                    <div class="activity-icon bg-red-100 text-red-600">
                        <i class="fas fa-file-signature"></i>
                    </div>
                    <div>
                        <h3 class="activity-title">Pengajuan Izin</h3>
                        <p class="activity-meta">Cuti, sakit, dan izin kerja</p>
                    </div>
                </div>
                <div class="w-full h-1 bg-gray-50 rounded-full overflow-hidden">
                    <div class="w-2/3 h-full bg-red-500"></div>
                </div>
            </a>
            
            <!-- Reports -->
            @if(auth()->check() && (auth()->user()->hasAnyRole(['admin', 'manager']) || auth()->user()->can('reports.view')))
            <a href="{{ route('reports.index') }}" class="activity-card hover:scale-[1.02]">
                <div class="activity-header">
                    <div class="activity-icon bg-emerald-100 text-emerald-600">
                        <i class="fas fa-chart-pie"></i>
                    </div>
                    <div>
                        <h3 class="activity-title">Laporan</h3>
                        <p class="activity-meta">Analisa dan ekspor data</p>
                    </div>
                </div>
                <div class="w-full h-1 bg-gray-50 rounded-full overflow-hidden">
                    <div class="w-full h-full bg-emerald-500"></div>
                </div>
            </a>
            @endif
            
            <!-- Complaints -->
            <a href="{{ route('complaints.form') }}" class="activity-card hover:scale-[1.02]">
                <div class="activity-header">
                    <div class="activity-icon bg-amber-100 text-amber-600">
                        <i class="fas fa-exclamation-circle"></i>
                    </div>
                    <div>
                        <h3 class="activity-title">Pusat Keluhan</h3>
                        <p class="activity-meta">Laporkan kendala sistem</p>
                    </div>
                </div>
                <div class="w-full h-1 bg-gray-50 rounded-full overflow-hidden">
                    <div class="w-1/3 h-full bg-amber-500"></div>
                </div>
            </a>

            <!-- Profile -->
            <a href="{{ route('profile.show') }}" class="activity-card hover:scale-[1.02]">
                <div class="activity-header">
                    <div class="activity-icon bg-indigo-100 text-indigo-600">
                        <i class="fas fa-user-circle"></i>
                    </div>
                    <div>
                        <h3 class="activity-title">Profil Saya</h3>
                        <p class="activity-meta">Kelola informasi personal</p>
                    </div>
                </div>
                <div class="w-full h-1 bg-gray-50 rounded-full overflow-hidden">
                    <div class="w-full h-full bg-indigo-500"></div>
                </div>
            </a>

            <!-- Shift Management (Admin/Manager Only) -->
            @if(auth()->check() && auth()->user()->hasAnyRole(['admin', 'manager']))
            <a href="{{ route('management.shift') }}" class="activity-card hover:scale-[1.02]">
                <div class="activity-header">
                    <div class="activity-icon bg-purple-100 text-purple-600">
                        <i class="fas fa-business-time"></i>
                    </div>
                    <div>
                        <h3 class="activity-title">Manajemen Shift</h3>
                        <p class="activity-meta">Kelola jadwal karyawan</p>
                    </div>
                </div>
                <div class="w-full h-1 bg-gray-50 rounded-full overflow-hidden">
                    <div class="w-full h-full bg-purple-500"></div>
                </div>
            </a>
            @endif
        </div>

        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-bold text-gray-900">Aktivitas Terakhir</h3>
            <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Update Real-time</span>
        </div>

        <div class="modern-card bg-white p-0 overflow-hidden border-gray-100 shadow-sm">
            <div id="activityList" class="divide-y divide-gray-50">
                <!-- Will be populated by JavaScript -->
                <div class="p-12 text-center text-gray-400">
                    <i class="fas fa-circle-notch fa-spin mr-2"></i> Memuat aktivitas...
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function loadRecentActivity() {
            const activityList = document.getElementById('activityList');
            const activities = [];
            
            // Mock data representing recent system events
            const today = new Date();
            const yesterday = new Date(today);
            yesterday.setDate(yesterday.getDate() - 1);
            
            activities.push({
                type: 'absen',
                icon: 'fa-sign-in-alt',
                color: 'text-emerald-500',
                bg: 'bg-emerald-50',
                title: 'Presensi Masuk',
                desc: 'Anda telah berhasil melakukan absen masuk hari ini.',
                time: today.toISOString()
            });
            
            activities.push({
                type: 'izin',
                icon: 'fa-file-alt',
                color: 'text-red-500',
                bg: 'bg-red-50',
                title: 'Pengajuan Izin',
                desc: 'Izin sakit sedang menunggu verifikasi HRD.',
                time: yesterday.toISOString()
            });
            
            if (activities.length === 0) {
                activityList.innerHTML = `
                    <div class="p-12 text-center">
                        <p class="text-gray-400 text-sm">Belum ada aktivitas terbaru</p>
                    </div>
                `;
            } else {
                activityList.innerHTML = activities.map(activity => `
                    <div class="p-4 hover:bg-gray-50 transition-colors flex gap-4">
                        <div class="w-10 h-10 rounded-xl ${activity.bg} ${activity.color} flex items-center justify-center shrink-0">
                            <i class="fas ${activity.icon}"></i>
                        </div>
                        <div class="flex-1">
                            <div class="flex justify-between items-start mb-1">
                                <h4 class="text-sm font-bold text-gray-800">${activity.title}</h4>
                                <span class="text-[10px] font-medium text-gray-400 uppercase">${formatDateTime(activity.time)}</span>
                            </div>
                            <p class="text-xs text-gray-500">${activity.desc}</p>
                        </div>
                    </div>
                `).join('');
            }
        }
        
        function formatDateTime(dateString) {
            const date = new Date(dateString);
            const now = new Date();
            const diff = now - date;
            const minutes = Math.floor(diff / (1000 * 60));
            const hours = Math.floor(diff / (1000 * 60 * 60));
            
            if (minutes < 1) return 'Baru saja';
            if (minutes < 60) return `${minutes}m yang lalu`;
            if (hours < 24) return `${hours}j yang lalu`;
            
            return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'short' });
        }
        
        window.addEventListener('DOMContentLoaded', loadRecentActivity);
    </script>
@endpush
