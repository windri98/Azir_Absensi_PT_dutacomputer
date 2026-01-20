@extends('layouts.app')

@section('title', 'Presensi - PT DUTA COMPUTER')

@section('content')
    <div class="px-4 py-8 lg:px-8 max-w-6xl mx-auto">
        <!-- Page Header -->
        <div class="flex items-center gap-4 mb-8">
            <button class="btn btn-secondary !p-2 shadow-sm" onclick="goBack()">
                <i class="fas fa-chevron-left"></i>
            </button>
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Presensi Kerja</h1>
                <p class="text-sm text-gray-600">Kelola kehadiran dan jam kerja Anda</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- MAIN CLOCK & ACTIONS -->
            <div class="lg:col-span-2">
                <!-- Digital Clock -->
                <div class="bg-white rounded-2xl shadow-soft border border-gray-100 p-10 mb-6 text-center">
                    <span class="inline-block px-4 py-1 rounded-full text-xs font-bold bg-primary-100 text-primary-700 uppercase tracking-wide mb-6">Waktu Saat Ini</span>
                    <h2 class="text-7xl font-black text-gray-900 mb-3 tracking-tighter font-mono" id="currentTime">00:00:00</h2>
                    <p class="text-lg text-gray-600 font-semibold mb-8" id="currentDate">Memuat tanggal...</p>
                    
                    <!-- Shift Info -->
                    <div class="grid grid-cols-2 gap-4 p-6 bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl mb-8">
                        <div class="text-left">
                            <span class="text-xs font-bold text-gray-500 uppercase tracking-widest block mb-2">Shift Anda</span>
                            <span class="text-lg font-bold text-gray-900">{{ $userShift ? $userShift->name : 'Reguler' }}</span>
                        </div>
                        <div class="text-right border-l border-gray-200 pl-4">
                            <span class="text-xs font-bold text-gray-500 uppercase tracking-widest block mb-2">Jadwal Kerja</span>
                            <span class="text-lg font-bold text-gray-900">
                                {{ $userShift ? \Carbon\Carbon::parse($userShift->start_time)->format('H:i') . ' - ' . \Carbon\Carbon::parse($userShift->end_time)->format('H:i') : '08:00 - 17:00' }}
                            </span>
                        </div>
                    </div>

                    <!-- Attendance Actions -->
                    <div class="space-y-3">
                        @if(!$todayAttendance || !$todayAttendance->check_in)
                            <!-- Not Checked In Yet -->
                            <div class="grid grid-cols-3 gap-3">
                                <a href="{{ route('attendance.clock-in') }}" class="group bg-gradient-success text-white px-6 py-4 rounded-xl font-bold text-lg shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-300 active:scale-95 flex items-center justify-center gap-2">
                                    <i class="fas fa-sign-in-alt text-2xl"></i>
                                    <span>Check-in</span>
                                </a>
                                <button class="bg-gray-200 text-gray-600 px-6 py-4 rounded-xl font-bold hover:bg-gray-300 transition-colors disabled opacity-50 cursor-not-allowed" disabled>
                                    <i class="fas fa-sign-out-alt text-2xl"></i>
                                </button>
                                <button class="bg-gray-200 text-gray-600 px-6 py-4 rounded-xl font-bold hover:bg-gray-300 transition-colors disabled opacity-50 cursor-not-allowed" disabled>
                                    <i class="fas fa-briefcase text-2xl"></i>
                                </button>
                            </div>
                            <p class="text-sm text-gray-600">Mulai dengan Check-in untuk mencatat waktu masuk Anda</p>
                        @elseif($todayAttendance && $todayAttendance->check_in && !$todayAttendance->check_out)
                            <!-- Checked In, Not Checked Out -->
                            <div class="grid grid-cols-3 gap-3">
                                <button class="bg-success-100 text-success-700 px-6 py-4 rounded-xl font-bold disabled opacity-50 cursor-not-allowed flex items-center justify-center gap-2" disabled>
                                    <i class="fas fa-check-circle text-2xl"></i>
                                    <span>Checked In</span>
                                </button>
                                <a href="{{ route('attendance.clock-out') }}" class="group bg-gradient-danger text-white px-6 py-4 rounded-xl font-bold text-lg shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-300 active:scale-95 flex items-center justify-center gap-2">
                                    <i class="fas fa-sign-out-alt text-2xl"></i>
                                    <span>Check-out</span>
                                </a>
                                <a href="{{ route('attendance.clock-overtime') }}" class="group bg-gradient-warning text-white px-6 py-4 rounded-xl font-bold shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-300 active:scale-95 flex items-center justify-center gap-2">
                                    <i class="fas fa-business-time text-2xl"></i>
                                    <span>Lembur</span>
                                </a>
                            </div>
                            <p class="text-sm text-success-700 font-semibold"><i class="fas fa-clock mr-2"></i>Anda sudah check-in. Jangan lupa untuk check-out!</p>
                        @else
                            <!-- Checked In & Out -->
                            <div class="p-8 bg-gradient-to-br from-success-50 to-success-100 rounded-xl border border-success-200">
                                <div class="flex items-center gap-3 justify-center mb-3">
                                    <i class="fas fa-check-circle text-success-600 text-4xl"></i>
                                </div>
                                <p class="text-lg font-bold text-success-900">Tugas Hari Ini Selesai</p>
                                <p class="text-sm text-success-700 mt-2">Anda sudah melakukan check-in dan check-out hari ini.</p>
                            </div>
                        @endif
                    </div>

                    <!-- Leave Application Button -->
                    <a href="{{ route('leave.index') }}" class="w-full mt-6 block bg-primary-100 text-primary-700 hover:bg-primary-200 px-6 py-3 rounded-xl font-semibold transition-colors">
                        <i class="fas fa-file-signature mr-2"></i> Ajukan Izin / Cuti
                    </a>
                </div>
            </div>

            <!-- SIDEBAR: STATUS & HISTORY -->
            <div class="flex flex-col gap-6">
                <!-- TODAY'S DETAILED STATUS -->
                <div class="bg-white rounded-2xl shadow-soft border border-gray-100 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2">
                        <i class="fas fa-chart-bar text-primary-600"></i>
                        Status Hari Ini
                    </h3>

                    @if($todayAttendance)
                        <div class="space-y-4">
                            <!-- Check-in Time -->
                            <div class="flex items-start gap-4 p-4 bg-success-50 rounded-xl border border-success-200">
                                <div class="w-12 h-12 rounded-lg bg-success-100 text-success-600 flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-sign-in-alt text-xl"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-xs font-bold text-success-700 uppercase tracking-wide">Check-in</p>
                                    <p class="text-2xl font-bold text-success-900">{{ $todayAttendance->check_in ? \Carbon\Carbon::parse($todayAttendance->check_in)->format('H:i') : '--:--' }}</p>
                                    @if($todayAttendance->check_in)
                                        <p class="text-xs text-success-600 mt-1"><i class="fas fa-check-circle mr-1"></i>Recorded</p>
                                    @endif
                                </div>
                            </div>

                            <!-- Check-out Time -->
                            <div class="flex items-start gap-4 p-4 @if($todayAttendance->check_out) bg-accent-50 border border-accent-200 @else bg-gray-50 border border-gray-200 @endif rounded-xl">
                                <div class="w-12 h-12 rounded-lg @if($todayAttendance->check_out) bg-accent-100 text-accent-600 @else bg-gray-100 text-gray-400 @endif flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-sign-out-alt text-xl"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-xs font-bold @if($todayAttendance->check_out) text-accent-700 @else text-gray-600 @endif uppercase tracking-wide">Check-out</p>
                                    <p class="text-2xl font-bold @if($todayAttendance->check_out) text-accent-900 @else text-gray-400 @endif">{{ $todayAttendance->check_out ? \Carbon\Carbon::parse($todayAttendance->check_out)->format('H:i') : '--:--' }}</p>
                                    @if($todayAttendance->check_out)
                                        <p class="text-xs text-accent-600 mt-1"><i class="fas fa-check-circle mr-1"></i>Recorded</p>
                                    @else
                                        <p class="text-xs text-gray-500 mt-1"><i class="fas fa-clock mr-1"></i>Pending</p>
                                    @endif
                                </div>
                            </div>

                            <!-- Work Duration -->
                            <div class="flex items-start gap-4 p-4 bg-primary-50 rounded-xl border border-primary-200">
                                <div class="w-12 h-12 rounded-lg bg-primary-100 text-primary-600 flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-hourglass-half text-xl"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-xs font-bold text-primary-700 uppercase tracking-wide">Durasi Kerja</p>
                                    <p class="text-2xl font-bold text-primary-900">
                                        @if($todayAttendance->work_hours)
                                            {{ number_format($todayAttendance->work_hours, 1) }} jam
                                        @else
                                            0.0 jam
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <i class="fas fa-inbox text-4xl text-gray-300 mb-3"></i>
                            <p class="text-gray-600 font-semibold">Belum ada data hari ini</p>
                            <p class="text-xs text-gray-500 mt-1">Mulai dengan check-in untuk mencatat kehadiran Anda</p>
                        </div>
                    @endif
                </div>

                <!-- RECENT HISTORY -->
                <div class="bg-white rounded-2xl shadow-soft border border-gray-100 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-history text-primary-600"></i>
                            Riwayat Terbaru
                        </h3>
                        <a href="{{ route('attendance.riwayat') }}" class="text-xs font-bold text-primary-600 uppercase tracking-wide hover:text-primary-700">Lihat Semua</a>
                    </div>
                    <div class="flex flex-col gap-3">
                        @forelse($attendances->take(5) as $attendance)
                            <div class="flex items-center justify-between p-3 bg-gray-50 hover:bg-primary-50 rounded-lg transition-colors border border-gray-100 hover:border-primary-200">
                                <div class="flex items-center gap-3 flex-1">
                                    <div class="w-10 h-10 rounded-lg 
                                        @if($attendance->status === 'late') bg-warning-100 text-warning-600
                                        @elseif($attendance->status === 'present') bg-success-100 text-success-600
                                        @else bg-gray-100 text-gray-600
                                        @endif
                                        flex items-center justify-center flex-shrink-0">
                                        <i class="fas @if($attendance->status === 'late') fa-exclamation-circle @else fa-check-circle @endif"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-gray-900">{{ \Carbon\Carbon::parse($attendance->date)->translatedFormat('d MMM Y') }}</p>
                                        <p class="text-xs text-gray-600">{{ $attendance->check_in ? \Carbon\Carbon::parse($attendance->check_in)->format('H:i') : '--:--' }} - {{ $attendance->check_out ? \Carbon\Carbon::parse($attendance->check_out)->format('H:i') : '--:--' }}</p>
                                    </div>
                                </div>
                                <span class="text-xs font-bold px-3 py-1 rounded-full
                                    @if($attendance->status === 'late') bg-warning-100 text-warning-700
                                    @elseif($attendance->status === 'present') bg-success-100 text-success-700
                                    @else bg-gray-100 text-gray-700
                                    @endif
                                ">
                                    @if($attendance->status === 'present') Hadir
                                    @elseif($attendance->status === 'late') Terlambat
                                    @else {{ ucfirst($attendance->status) }}
                                    @endif
                                </span>
                            </div>
                        @empty
                            <p class="text-center text-sm text-gray-500 py-6">Belum ada riwayat absensi</p>
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

    // Update clock setiap detik
    function updateClock() {
        const now = new Date();
        const timeString = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
        const dateString = now.toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
        
        document.getElementById('currentTime').textContent = timeString;
        document.getElementById('currentDate').textContent = dateString;
    }

    // Update immediately and then every second
    updateClock();
    setInterval(updateClock, 1000);
</script>
@endpush
