@extends('layouts.app')

@section('title', 'Laporan - Sistem Absensi')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/reports.css') }}">
@endpush

@section('content')
    <div class="px-4 py-8 lg:px-8 max-w-6xl mx-auto">
        <!-- Page Header -->
        <div class="flex items-center gap-4 mb-8">
            <button class="btn btn-secondary !p-2 shadow-sm" onclick="history.back()">
                <i class="fas fa-chevron-left"></i>
            </button>
            <div>
                <h1 class="text-2xl font-bold text-main">Laporan Absensi</h1>
                <p class="text-sm text-muted">Analisa kehadiran dan performa kerja tim</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Filter Sidebar -->
            <div class="lg:col-span-1">
                <div class="modern-card">
                    <h3 class="text-sm font-bold text-main mb-6 flex items-center gap-2">
                        <i class="fas fa-filter text-primary-color"></i> Filter Data
                    </h3>
                    
                    <form action="{{ route('reports.index') }}" method="GET" class="space-y-6">
                        <div class="filter-group">
                            <label class="filter-label">Periode</label>
                            <select name="period" class="filter-select" id="periodFilter">
                                <option value="today" {{ request('period') == 'today' ? 'selected' : '' }}>Hari Ini</option>
                                <option value="week" {{ request('period') == 'week' ? 'selected' : '' }}>Minggu Ini</option>
                                <option value="month" {{ !request('period') || request('period') == 'month' ? 'selected' : '' }}>Bulan Ini</option>
                                <option value="custom" {{ request('period') == 'custom' ? 'selected' : '' }}>Custom Range</option>
                            </select>
                        </div>

                        <div id="dateRangeGroup" style="{{ request('period') == 'custom' ? '' : 'display: none;' }}">
                            <div class="flex flex-col gap-4">
                                <div class="filter-group">
                                    <label class="filter-label">Mulai</label>
                                    <input type="date" name="start_date" class="filter-input" value="{{ request('start_date') }}">
                                </div>
                                <div class="filter-group">
                                    <label class="filter-label">Selesai</label>
                                    <input type="date" name="end_date" class="filter-input" value="{{ request('end_date') }}">
                                </div>
                            </div>
                        </div>

                        <div class="filter-group">
                            <label class="filter-label">Status</label>
                            <select name="status" class="filter-select">
                                <option value="">Semua Status</option>
                                <option value="present" {{ request('status') == 'present' ? 'selected' : '' }}>Hadir</option>
                                <option value="late" {{ request('status') == 'late' ? 'selected' : '' }}>Terlambat</option>
                                <option value="leave" {{ request('status') == 'leave' ? 'selected' : '' }}>Izin / Cuti</option>
                                <option value="absent" {{ request('status') == 'absent' ? 'selected' : '' }}>Alpa</option>
                            </select>
                        </div>

                        <div class="pt-4 flex flex-col gap-3">
                            <button type="submit" class="btn btn-primary w-full py-3 shadow-md">
                                Terapkan
                            </button>
                            <a href="{{ route('reports.index') }}" class="btn btn-secondary w-full py-3">
                                Reset
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Main Content Area -->
            <div class="lg:col-span-3">
                <!-- Summary Stats -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                    <div class="modern-card !p-4 border-l-4 border-primary-color">
                        <span class="text-[10px] font-bold text-light uppercase tracking-widest block mb-1">Hadir</span>
                        <span class="text-2xl font-black text-main">{{ $attendances->where('status', 'present')->count() }}</span>
                    </div>
                    <div class="modern-card !p-4 border-l-4 border-warning">
                        <span class="text-[10px] font-bold text-light uppercase tracking-widest block mb-1">Telat</span>
                        <span class="text-2xl font-black text-main">{{ $attendances->where('status', 'late')->count() }}</span>
                    </div>
                    <div class="modern-card !p-4 border-l-4 border-info">
                        <span class="text-[10px] font-bold text-light uppercase tracking-widest block mb-1">Izin</span>
                        <span class="text-2xl font-black text-main">{{ $attendances->where('status', 'leave')->count() }}</span>
                    </div>
                    <div class="modern-card !p-4 border-l-4 border-danger">
                        <span class="text-[10px] font-bold text-light uppercase tracking-widest block mb-1">Alpa</span>
                        <span class="text-2xl font-black text-main">{{ $attendances->where('status', 'absent')->count() }}</span>
                    </div>
                </div>

                <!-- Table -->
                <div class="modern-card !p-0 overflow-hidden shadow-sm">
                    <div class="p-6 border-b border-color flex items-center justify-between">
                        <h3 class="text-sm font-bold text-main">Data Presensi</h3>
                        <a href="{{ route('reports.export', ['format' => 'csv'] + request()->all()) }}" class="btn btn-secondary !py-2 !px-4 !text-xs font-bold text-success hover:!bg-success-light">
                            <i class="fas fa-file-csv mr-2"></i> Export CSV
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="report-table">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Nama Pegawai</th>
                                    <th>Masuk</th>
                                    <th>Keluar</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($attendances as $attendance)
                                <tr>
                                    <td class="font-bold text-xs">{{ \Carbon\Carbon::parse($attendance->date)->translatedFormat('d M Y') }}</td>
                                    <td>
                                        <div class="flex flex-col">
                                            <span class="font-semibold text-main">{{ $attendance->user->name }}</span>
                                            <span class="text-[10px] text-muted">{{ $attendance->user->employee_id }}</span>
                                        </div>
                                    </td>
                                    <td class="text-success font-bold">{{ $attendance->check_in ? date('H:i', strtotime($attendance->check_in)) : '--:--' }}</td>
                                    <td class="{{ $attendance->check_out ? 'text-success font-bold' : 'text-light' }}">{{ $attendance->check_out ? date('H:i', strtotime($attendance->check_out)) : '--:--' }}</td>
                                    <td>
                                        <span class="status-badge {{ 
                                            $attendance->status === 'present' ? 'status-present' : 
                                            ($attendance->status === 'late' ? 'status-late' : 
                                            ($attendance->status === 'leave' ? 'status-work_leave' : 'status-absent')) 
                                        }}">
                                            {{ 
                                                $attendance->status === 'present' ? 'Hadir' : 
                                                ($attendance->status === 'late' ? 'Terlambat' : 
                                                ($attendance->status === 'leave' ? 'Izin' : 'Alpa')) 
                                            }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="py-12 text-center">
                                        <div class="empty-state">
                                            <i class="fas fa-folder-open empty-state-icon"></i>
                                            <p class="empty-state-text">Tidak ada data untuk filter ini</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    @if($attendances->hasPages())
                    <div class="p-6 border-t border-color">
                        {{ $attendances->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.getElementById('periodFilter').addEventListener('change', function() {
            const dateRangeGroup = document.getElementById('dateRangeGroup');
            dateRangeGroup.style.display = (this.value === 'custom') ? 'block' : 'none';
        });
    </script>
@endpush
