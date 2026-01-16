@extends('layouts.app')

@section('title', 'Presensi - Sistem Absensi')

@section('content')
    <div class="px-4 py-8 lg:px-8 max-w-5xl mx-auto">
        <!-- Page Header -->
        <div class="flex items-center gap-4 mb-8">
            <button class="btn btn-secondary !p-2 shadow-sm" onclick="goBack()">
                <i class="fas fa-chevron-left"></i>
            </button>
            <div>
                <h1 class="text-2xl font-bold text-main">Presensi Kerja</h1>
                <p class="text-sm text-muted">Lakukan pencatatan kehadiran harian Anda</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Clock Card -->
            <div class="lg:col-span-2">
                <div class="modern-card flex flex-col items-center text-center p-10 bg-card border-color">
                    <span class="badge badge-info mb-4">Waktu Saat Ini</span>
                    <h2 class="text-5xl font-black text-main mb-2 tracking-tighter" id="currentTime">00:00:00</h2>
                    <p class="text-muted font-medium mb-10" id="currentDate">Memuat tanggal...</p>
                    
                    <div class="w-full flex justify-between p-4 bg-body rounded-xl mb-10">
                        <div class="text-left">
                            <span class="text-[10px] font-bold text-light uppercase tracking-widest block mb-1">Shift</span>
                            <span class="text-sm font-bold text-main">{{ $userShift ? $userShift->name : 'Reguler' }}</span>
                        </div>
                        <div class="text-right">
                            <span class="text-[10px] font-bold text-light uppercase tracking-widest block mb-1">Jadwal</span>
                            <span class="text-sm font-bold text-main">
                                {{ $userShift ? \Carbon\Carbon::parse($userShift->start_time)->format('H:i') . ' - ' . \Carbon\Carbon::parse($userShift->end_time)->format('H:i') : '08:00 - 17:00' }}
                            </span>
                        </div>
                    </div>

                    <div class="w-full flex flex-col gap-4">
                        @if(!$todayAttendance || !$todayAttendance->check_in)
                            <div class="flex gap-3">
                                <button class="btn btn-primary flex-1 py-4 text-lg shadow-lg" onclick="goToClockIn()">
                                    <i class="fas fa-sign-in-alt"></i> Absen Masuk
                                </button>
                                <button class="btn btn-secondary px-5" onclick="scanQR()" title="Scan QR">
                                    <i class="fas fa-qrcode text-xl"></i>
                                </button>
                            </div>
                        @elseif($todayAttendance && $todayAttendance->check_in && !$todayAttendance->check_out)
                            <button class="btn btn-danger py-4 text-lg shadow-lg" onclick="goToClockOut()">
                                <i class="fas fa-sign-out-alt"></i> Absen Keluar
                            </button>
                            <button class="btn btn-secondary py-3" onclick="goToOvertime()">
                                <i class="fas fa-business-time"></i> Mulai Lembur
                            </button>
                        @else
                            <div class="p-6 bg-success-light border border-success rounded-2xl text-success font-bold flex flex-col items-center gap-2">
                                <i class="fas fa-check-circle text-3xl"></i>
                                <span>Tugas Hari Ini Selesai</span>
                            </div>
                        @endif
                        
                        <a href="{{ route('leave.index') }}" class="btn btn-secondary py-3 text-muted border-dashed">
                            <i class="fas fa-file-signature"></i> Ajukan Izin / Cuti
                        </a>
                    </div>
                </div>
            </div>

            <!-- Status & History -->
            <div class="flex flex-col gap-6">
                <!-- Current Status -->
                <div class="modern-card bg-main text-white p-6" style="background-color: var(--text-main);">
                    <h3 class="text-xs font-bold uppercase tracking-widest opacity-60 mb-6">Status Hari Ini</h3>
                    @if($todayAttendance)
                        <div class="space-y-4">
                            <div class="flex justify-between items-center">
                                <span class="text-sm opacity-70">Jam Masuk</span>
                                <span class="font-bold text-primary-color">{{ \Carbon\Carbon::parse($todayAttendance->check_in)->format('H:i') }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm opacity-70">Jam Keluar</span>
                                <span class="font-bold {{ $todayAttendance->check_out ? 'text-success' : 'opacity-30' }}">
                                    {{ $todayAttendance->check_out ? \Carbon\Carbon::parse($todayAttendance->check_out)->format('H:i') : '--:--' }}
                                </span>
                            </div>
                            <div class="pt-4 border-t border-white/10 flex justify-between items-center">
                                <span class="text-sm opacity-70">Durasi Kerja</span>
                                <span class="font-bold text-warning">{{ $todayAttendance->work_hours ? number_format($todayAttendance->work_hours, 1) . ' Jam' : '0.0 Jam' }}</span>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-6 opacity-40">
                            <i class="fas fa-user-clock text-4xl mb-3"></i>
                            <p class="text-xs">Belum ada aktivitas hari ini</p>
                        </div>
                    @endif
                </div>

                <!-- History -->
                <div class="modern-card p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-sm font-bold text-main">Riwayat Terakhir</h3>
                        <a href="{{ route('attendance.riwayat') }}" class="text-[10px] font-bold text-primary-color uppercase tracking-widest hover:underline">Semua</a>
                    </div>
                    <div class="flex flex-col gap-3">
                        @forelse($attendances->take(4) as $attendance)
                            <div class="flex items-center justify-between p-3 bg-body rounded-xl hover:bg-primary-light transition-colors">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg {{ $attendance->status === 'late' ? 'bg-warning-light text-warning' : 'bg-success-light text-success' }} flex items-center justify-center text-[10px]">
                                        <i class="fas {{ $attendance->status === 'late' ? 'fa-clock' : 'fa-check' }}"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs font-bold text-main">{{ \Carbon\Carbon::parse($attendance->date)->translatedFormat('d M') }}</p>
                                        <p class="text-[10px] text-muted">{{ $attendance->check_in ? \Carbon\Carbon::parse($attendance->check_in)->format('H:i') : '--:--' }}</p>
                                    </div>
                                </div>
                                <span class="text-[9px] font-extrabold uppercase tracking-tighter px-2 py-0.5 rounded {{ $attendance->status === 'late' ? 'bg-warning text-white' : 'bg-success text-white' }}">
                                    {{ $attendance->status }}
                                </span>
                            </div>
                        @empty
                            <p class="text-center text-xs text-light py-4">Belum ada data</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function goBack() {
        if (window.history.length > 1) {
            window.history.back();
        } else {
            window.location.href = "{{ route('dashboard') }}";
        }
    }

    function goToClockIn() { window.location.href = "{{ route('attendance.clock-in') }}"; }
    function goToClockOut() { window.location.href = "{{ route('attendance.clock-out') }}"; }
    function scanQR() { window.location.href = "{{ route('attendance.qr-scan') }}"; }
    
    function goToOvertime() {
        if (typeof showInfoPopup !== 'undefined') {
            showInfoPopup({
                title: 'Scan QR Code',
                message: 'Silakan scan QR code di lokasi untuk memulai lembur.',
                buttonText: 'Mulai Scan',
                onClose: () => { window.location.href = "{{ route('attendance.qr-scan') }}"; }
            });
        } else {
            window.location.href = "{{ route('attendance.qr-scan') }}";
        }
    }

    function updateTime() {
        const now = new Date();
        const timeStr = now.toLocaleTimeString('id-ID', { hour12: false, hour: '2-digit', minute: '2-digit', second: '2-digit' });
        const dateStr = now.toLocaleDateString('id-ID', { weekday: 'long', day: '2-digit', month: 'long', year: 'numeric' });
        
        document.getElementById('currentTime').textContent = timeStr;
        document.getElementById('currentDate').textContent = dateStr;
    }

    setInterval(updateTime, 1000);
    updateTime();
</script>
<script src="{{ asset('components/popup.js') }}"></script>
@endpush
