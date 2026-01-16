@extends('layouts.app')

@section('title', 'Riwayat Absensi - Sistem Absensi')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/riwayat.css') }}">
@endpush

@section('content')
    <div class="px-4 py-8 lg:px-8 max-w-5xl mx-auto">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8">
            <div class="flex items-center gap-4">
                <button class="btn btn-secondary !p-2 shadow-sm" onclick="history.back()">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <div>
                    <h1 class="text-2xl font-bold text-main">Riwayat Kehadiran</h1>
                    <p class="text-sm text-muted">Pantau rekam jejak presensi Anda</p>
                </div>
            </div>
            
            <form method="GET" action="{{ route('attendance.riwayat') }}" id="filterForm">
                <div class="filter-tabs shadow-sm">
                    <button type="button" class="filter-tab {{ !request('period') || request('period') == 'week' ? 'active' : '' }}" onclick="filterBy('week')">Minggu</button>
                    <button type="button" class="filter-tab {{ request('period') == 'month' ? 'active' : '' }}" onclick="filterBy('month')">Bulan</button>
                    <button type="button" class="filter-tab {{ request('period') == 'custom' ? 'active' : '' }}" onclick="filterBy('custom')">Custom</button>
                </div>
                <input type="hidden" name="period" id="periodInput" value="{{ request('period', 'week') }}">
            </form>
        </div>

        @if(request('period') == 'custom')
        <div class="modern-card mb-8">
            <form method="GET" action="{{ route('attendance.riwayat') }}" class="grid grid-cols-1 md:grid-cols-3 items-end gap-4">
                <input type="hidden" name="period" value="custom">
                <div class="flex flex-col gap-1">
                    <label class="text-[10px] font-bold text-light uppercase tracking-widest">Mulai</label>
                    <input type="date" name="start_date" class="date-input" value="{{ request('start_date') }}">
                </div>
                <div class="flex flex-col gap-1">
                    <label class="text-[10px] font-bold text-light uppercase tracking-widest">Sampai</label>
                    <input type="date" name="end_date" class="date-input" value="{{ request('end_date') }}">
                </div>
                <button type="submit" class="btn btn-primary py-2.5">Filter Data</button>
            </form>
        </div>
        @endif

        <!-- Summary Grid -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
            <div class="modern-card p-6 flex flex-col items-center justify-center text-center">
                <span class="text-3xl font-black text-primary-color">{{ $stats['total_days'] ?? 0 }}</span>
                <span class="text-[10px] font-bold text-light uppercase tracking-widest mt-1">Total Hari</span>
            </div>
            <div class="modern-card p-6 flex flex-col items-center justify-center text-center">
                <span class="text-3xl font-black text-success">{{ $stats['present_days'] ?? 0 }}</span>
                <span class="text-[10px] font-bold text-light uppercase tracking-widest mt-1">Hadir</span>
            </div>
            <div class="modern-card p-6 flex flex-col items-center justify-center text-center">
                <span class="text-3xl font-black text-warning">{{ $stats['late_days'] ?? 0 }}</span>
                <span class="text-[10px] font-bold text-light uppercase tracking-widest mt-1">Terlambat</span>
            </div>
            <div class="modern-card p-6 flex flex-col items-center justify-center text-center">
                <span class="text-3xl font-black text-info">{{ number_format($stats['total_hours'] ?? 0, 1) }}h</span>
                <span class="text-[10px] font-bold text-light uppercase tracking-widest mt-1">Total Jam</span>
            </div>
        </div>

        <!-- History List -->
        <div class="space-y-6">
            <h3 class="text-lg font-bold text-main">Detail Aktivitas</h3>
            <div class="flex flex-col gap-4">
                @forelse($attendances as $attendance)
                    <div class="history-card shadow-sm">
                        <div class="history-header">
                            <div class="history-date">
                                <i class="far fa-calendar-alt text-primary-color"></i>
                                {{ \Carbon\Carbon::parse($attendance->date)->translatedFormat('l, d F Y') }}
                            </div>
                            <span class="status-badge {{ 
                                $attendance->status === 'present' ? 'status-present' : 
                                ($attendance->status === 'late' ? 'status-late' : 
                                ($attendance->status === 'work_leave' ? 'status-work_leave' : 'status-absent')) 
                            }}">
                                {{ 
                                    $attendance->status === 'present' ? 'Hadir' : 
                                    ($attendance->status === 'late' ? 'Terlambat' : 
                                    ($attendance->status === 'work_leave' ? 'Izin Kerja' : 'Alpa')) 
                                }}
                            </span>
                        </div>
                        <div class="history-details">
                            <div class="detail-item">
                                <span class="detail-label">Check In</span>
                                <span class="detail-value text-success">{{ $attendance->check_in ? \Carbon\Carbon::parse($attendance->check_in)->format('H:i') : '--:--' }}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Check Out</span>
                                <span class="detail-value {{ $attendance->check_out ? 'text-success' : 'text-light' }}">{{ $attendance->check_out ? \Carbon\Carbon::parse($attendance->check_out)->format('H:i') : '--:--' }}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Durasi</span>
                                <span class="detail-value text-primary-color">
                                    @if($attendance->check_in && $attendance->check_out)
                                        @php
                                            $start = \Carbon\Carbon::parse($attendance->check_in);
                                            $end = \Carbon\Carbon::parse($attendance->check_out);
                                            $diff = $end->diff($start);
                                        @endphp
                                        {{ $diff->h }}j {{ $diff->i }}m
                                    @else
                                        --
                                    @endif
                                </span>
                            </div>

                            @if($attendance->hasDocument())
                            <div class="notes-item flex justify-between items-center bg-body p-3 rounded-lg border border-color">
                                <span class="text-[10px] font-bold text-main uppercase">{{ $attendance->getDocumentTypeLabel() }}</span>
                                <div class="flex gap-2">
                                    <a href="{{ route('attendance.document.view', $attendance) }}" class="doc-btn view-btn" target="_blank">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('attendance.document.download', $attendance) }}" class="doc-btn download-btn">
                                        <i class="fas fa-download"></i>
                                    </a>
                                </div>
                            </div>
                            @endif

                            @if($attendance->notes)
                            <div class="notes-item">
                                <span class="detail-label">Catatan</span>
                                <p class="notes-text mt-1 italic">"{{ $attendance->notes }}"</p>
                            </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <i class="fas fa-calendar-times empty-state-icon"></i>
                        <h3 class="font-bold text-main mb-1">Tidak ada riwayat</h3>
                        <p class="text-sm text-muted">Belum ada data absensi untuk periode ini.</p>
                    </div>
                @endforelse
            </div>
        </div>
        
        <div class="mt-8">
            {{ $attendances->links() }}
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function filterBy(period) {
            document.getElementById('periodInput').value = period;
            if (period === 'custom') {
                const currentUrl = new URL(window.location.href);
                currentUrl.searchParams.set('period', 'custom');
                window.location.href = currentUrl.toString();
            } else {
                document.getElementById('filterForm').submit();
            }
        }
    </script>
@endpush
