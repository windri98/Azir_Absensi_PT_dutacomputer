@extends('layouts.app')

@section('title', 'Dashboard - Sistem Absensi')

@section('content')
    <div class="px-4 py-8 lg:px-8">
        <!-- Dashboard Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div>
                <h1 class="text-2xl font-bold text-main">
                    @php
                        $hour = date('H');
                        if ($hour >= 5 && $hour < 11) echo 'Selamat Pagi';
                        elseif ($hour >= 11 && $hour < 15) echo 'Selamat Siang';
                        elseif ($hour >= 15 && $hour < 18) echo 'Selamat Sore';
                        else echo 'Selamat Malam';
                    @endphp, 
                    {{ explode(' ', $user->name)[0] }}!
                </h1>
                <p class="text-muted text-sm mt-1">Hari ini {{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM YYYY') }}</p>
            </div>
            <div class="relative">
                <button class="btn btn-secondary shadow-sm dropdown-btn" onclick="toggleDropdown()">
                    <i class="fas fa-ellipsis-v mr-2"></i> Menu Cepat
                </button>
                <div class="dropdown-content" id="dropdownMenu">
                    <a href="{{ route('profile.show') }}" class="dropdown-item">
                        <i class="fas fa-user-circle text-muted"></i> Profil Saya
                    </a>
                    @if(auth()->check() && auth()->user()->hasPermission('dashboard.admin'))
                        <a href="{{ route('admin.dashboard') }}" class="dropdown-item">
                            <i class="fas fa-user-shield text-muted"></i> Panel Admin
                        </a>
                    @endif
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item text-danger" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt"></i> Keluar
                    </a>
                </div>
            </div>
        </div>

        <!-- Attendance Status Overview -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <!-- Main Action Card -->
            <div class="lg:col-span-2 modern-card flex flex-col sm:flex-row p-0 overflow-hidden">
                <div class="p-6 flex-1 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center gap-2 mb-4">
                            <span class="w-2 h-2 rounded-full {{ $todayAttendance ? 'bg-success' : 'bg-danger animate-pulse' }}"></span>
                            <span class="text-xs font-semibold uppercase tracking-wider text-light">Status Kehadiran</span>
                        </div>
                        <h2 class="text-xl font-bold text-main mb-1">
                            {{ $todayAttendance ? ($todayAttendance->check_out ? 'Sudah Selesai Kerja' : 'Sedang Bekerja') : 'Belum Absen Masuk' }}
                        </h2>
                        <p class="text-sm text-muted">
                            {{ $todayAttendance ? 'Tercatat masuk pada ' . \Carbon\Carbon::parse($todayAttendance->check_in)->format('H:i') : 'Silakan lakukan absensi masuk untuk mulai bekerja.' }}
                        </p>
                    </div>
                    <div class="mt-8 flex flex-wrap gap-3">
                        @if(!$todayAttendance)
                            <a href="{{ route('attendance.clock-in') }}" class="btn btn-primary shadow-lg">
                                <i class="fas fa-sign-in-alt"></i> Absen Masuk
                            </a>
                        @elseif(!$todayAttendance->check_out)
                            <a href="{{ route('attendance.clock-out') }}" class="btn btn-danger shadow-lg">
                                <i class="fas fa-sign-out-alt"></i> Absen Keluar
                            </a>
                        @endif
                        <a href="{{ route('attendance.riwayat') }}" class="btn btn-secondary">
                            <i class="fas fa-history"></i> Riwayat
                        </a>
                    </div>
                </div>
                <div class="bg-body p-6 border-t sm:border-t-0 sm:border-l border-color min-w-[200px] flex flex-col justify-center gap-6">
                    <div class="text-center">
                        <p class="text-xs font-medium text-light uppercase">Jam Masuk</p>
                        <p class="text-xl font-bold text-main">{{ $todayAttendance ? \Carbon\Carbon::parse($todayAttendance->check_in)->format('H:i') : '--:--' }}</p>
                    </div>
                    <div class="text-center">
                        <p class="text-xs font-medium text-light uppercase">Jam Pulang</p>
                        <p class="text-xl font-bold text-main">{{ $todayAttendance && $todayAttendance->check_out ? \Carbon\Carbon::parse($todayAttendance->check_out)->format('H:i') : '--:--' }}</p>
                    </div>
                    <div class="text-center">
                        <p class="text-xs font-medium text-light uppercase">Durasi Kerja</p>
                        <p class="text-xl font-bold text-main">{{ $todayAttendance && $todayAttendance->work_hours ? number_format($todayAttendance->work_hours, 1) . ' Jam' : '0 Jam' }}</p>
                    </div>
                </div>
            </div>

            <!-- Stats Mini Cards -->
            <div class="grid grid-cols-2 gap-4">
                <div class="modern-card stats-card flex flex-col justify-center items-center text-center p-4" style="background-color: var(--info-light); border-color: #bfdbfe;">
                    <div class="w-10 h-10 rounded-full bg-info text-white flex items-center justify-center mb-2 shadow-sm">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <p class="text-2xl font-bold" style="color: var(--info);">{{ $monthlyStats['present'] + $monthlyStats['late'] }}</p>
                    <p class="text-xs font-medium" style="color: #2563eb;">Hadir</p>
                    <i class="fas fa-calendar-check stats-card-bg"></i>
                </div>
                <div class="modern-card stats-card flex flex-col justify-center items-center text-center p-4" style="background-color: var(--warning-light); border-color: #fde68a;">
                    <div class="w-10 h-10 rounded-full bg-warning text-white flex items-center justify-center mb-2 shadow-sm">
                        <i class="fas fa-clock"></i>
                    </div>
                    <p class="text-2xl font-bold" style="color: var(--warning);">{{ $monthlyStats['late'] }}</p>
                    <p class="text-xs font-medium" style="color: #d97706;">Terlambat</p>
                    <i class="fas fa-clock stats-card-bg"></i>
                </div>
                <div class="modern-card stats-card flex flex-col justify-center items-center text-center p-4" style="background-color: var(--success-light); border-color: #bbf7d0;">
                    <div class="w-10 h-10 rounded-full bg-success text-white flex items-center justify-center mb-2 shadow-sm">
                        <i class="fas fa-envelope-open-text"></i>
                    </div>
                    <p class="text-2xl font-bold" style="color: var(--success);">{{ $monthlyStats['work_leave'] ?? 0 }}</p>
                    <p class="text-xs font-medium" style="color: #059669;">Izin/Cuti</p>
                    <i class="fas fa-envelope-open-text stats-card-bg"></i>
                </div>
                <div class="modern-card stats-card flex flex-col justify-center items-center text-center p-4" style="background-color: #eef2ff; border-color: #e0e7ff;">
                    <div class="w-10 h-10 rounded-full bg-primary-dark text-white flex items-center justify-center mb-2 shadow-sm">
                        <i class="fas fa-business-time"></i>
                    </div>
                    <p class="text-2xl font-bold text-primary-dark">{{ number_format($monthlyStats['work_hours'] ?? 0, 0) }}</p>
                    <p class="text-xs font-medium text-primary-color">Total Jam</p>
                    <i class="fas fa-business-time stats-card-bg"></i>
                </div>
            </div>
        </div>

        <!-- Features Grid -->
        <h3 class="text-lg font-bold text-main mb-4 px-1">Layanan Cepat</h3>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-8">
            <a href="{{ route('attendance.absensi') }}" class="action-card">
                <div class="action-card-icon bg-primary-light text-primary-color">
                    <i class="fas fa-fingerprint"></i>
                </div>
                <span class="action-card-title">Presensi</span>
            </a>
            <a href="{{ route('activities.aktifitas') }}" class="action-card">
                <div class="action-card-icon bg-warning-light text-warning">
                    <i class="fas fa-tasks"></i>
                </div>
                <span class="action-card-title">Aktivitas</span>
            </a>
            <a href="{{ route('leave.index') }}" class="action-card">
                <div class="action-card-icon bg-danger-light text-danger">
                    <i class="fas fa-file-alt"></i>
                </div>
                <span class="action-card-title">Izin & Cuti</span>
            </a>
            <a href="{{ route('reports.index') }}" class="action-card">
                <div class="action-card-icon bg-info-light text-info">
                    <i class="fas fa-chart-bar"></i>
                </div>
                <span class="action-card-title">Laporan</span>
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- QR Code Card -->
            <div class="modern-card flex flex-col sm:flex-row items-center gap-8">
                <div class="qr-container border-4 border-body rounded-2xl overflow-hidden shrink-0 shadow-sm">
                    {!! $user->getQRCode() !!}
                </div>
                <div class="text-center sm:text-left">
                    <h3 class="text-lg font-bold text-main mb-2">QR Code Saya</h3>
                    <p class="text-sm text-muted mb-4">Gunakan QR Code ini untuk verifikasi kehadiran atau keperluan administratif kantor.</p>
                    <div class="flex flex-wrap gap-2 mb-4 justify-center sm:justify-start">
                        <span class="badge badge-info">{{ $user->employee_id }}</span>
                        <span class="badge badge-success">{{ strtoupper($user->roles->first()->name ?? 'Karyawan') }}</span>
                    </div>
                    <button onclick="downloadQRCode()" class="btn btn-secondary text-xs">
                        <i class="fas fa-download"></i> Download QR
                    </button>
                </div>
            </div>

            <!-- Support Card -->
            <div class="modern-card bg-main text-white relative overflow-hidden flex flex-col justify-center" style="background-color: var(--text-main);">
                <div class="relative z-10">
                    <span class="px-2 py-1 bg-white/10 text-white text-[10px] rounded font-bold uppercase tracking-widest mb-4 inline-block">Support System</span>
                    <h3 class="text-xl font-bold mb-2">Butuh Bantuan?</h3>
                    <p class="text-light text-sm mb-6 opacity-80">Jika Anda mengalami kendala saat melakukan absensi atau menemukan bug pada sistem, silakan hubungi tim IT.</p>
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('help') }}" class="btn btn-primary bg-white text-main hover:bg-white/90">
                            Pusat Bantuan
                        </a>
                        <a href="{{ route('about') }}" class="btn bg-white/10 text-white border-white/20 hover:bg-white/20">
                            Tentang App
                        </a>
                    </div>
                </div>
                <!-- Abstract Background Pattern -->
                <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-primary-color opacity-10 rounded-full" style="filter: blur(64px);"></div>
                <div class="absolute -right-20 -top-20 w-60 h-60 bg-info opacity-10 rounded-full" style="filter: blur(64px);"></div>
            </div>
        </div>
    </div>

    <!-- Hidden logout form -->
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
@endsection

@push('scripts')
<script>
    function toggleDropdown() {
        const dropdown = document.getElementById('dropdownMenu');
        dropdown.classList.toggle('show');
    }

    // Close dropdown when clicking outside
    window.onclick = function(event) {
        if (!event.target.matches('.dropdown-btn') && !event.target.closest('.dropdown-btn')) {
            const dropdowns = document.getElementsByClassName('dropdown-content');
            for (let i = 0; i < dropdowns.length; i++) {
                const openDropdown = dropdowns[i];
                if (openDropdown.classList.contains('show')) {
                    openDropdown.classList.remove('show');
                }
            }
        }
    }

    // Download QR Code function
    function downloadQRCode() {
        const svgElement = document.querySelector('.qr-container svg');
        if (!svgElement) {
            alert('QR Code tidak ditemukan');
            return;
        }

        const canvas = document.createElement('canvas');
        const ctx = canvas.getContext('2d');
        const svgData = new XMLSerializer().serializeToString(svgElement);
        const img = new Image();

        img.onload = function() {
            canvas.width = img.width;
            canvas.height = img.height;
            ctx.fillStyle = 'white';
            ctx.fillRect(0, 0, canvas.width, canvas.height);
            ctx.drawImage(img, 0, 0);

            canvas.toBlob(function(blob) {
                const url = URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = 'qrcode_{{ $user->employee_id }}.png';
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
                URL.revokeObjectURL(url);
            });
        };

        img.src = 'data:image/svg+xml;base64,' + btoa(unescape(encodeURIComponent(svgData)));
    }
</script>
@endpush
