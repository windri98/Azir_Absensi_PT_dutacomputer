@extends('admin.layout')

@section('title', 'Export Laporan')

@section('content')
<div class="space-y-5">
    <div class="bg-white rounded-2xl px-6 py-5 border border-gray-200 shadow-soft">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-bold text-gray-900">Export Laporan Absensi</h2>
                <p class="text-sm text-gray-500">Pilih filter untuk mengekspor laporan sesuai kebutuhan.</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-200 shadow-soft p-6 max-w-4xl">
        <h3 class="text-base font-semibold text-gray-800 mb-4">Filter Data Export</h3>
        
        <form method="get" action="{{ route('admin.reports.download') }}" class="space-y-4">
            <!-- Periode Waktu -->
            <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
                <h4 class="text-sm font-semibold text-gray-700 mb-3">Periode Waktu</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <label class="block text-sm text-gray-700">
                        Tanggal Mulai *
                        <input class="mt-1 w-full form-input" type="date" name="start_date" value="{{ old('start_date', date('Y-m-01')) }}" required>
                    </label>
                    <label class="block text-sm text-gray-700">
                        Tanggal Akhir *
                        <input class="mt-1 w-full form-input" type="date" name="end_date" value="{{ old('end_date', date('Y-m-d')) }}" required>
                    </label>
                </div>
                <div class="mt-3 flex flex-wrap gap-2">
                    <button type="button" onclick="setThisMonth()" class="btn btn-secondary btn-xs">Bulan Ini</button>
                    <button type="button" onclick="setLastMonth()" class="btn btn-secondary btn-xs">Bulan Lalu</button>
                    <button type="button" onclick="setThisYear()" class="btn btn-secondary btn-xs">Tahun Ini</button>
                </div>
            </div>
            
            <!-- Filter User -->
            <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
                <h4 class="text-sm font-semibold text-gray-700 mb-3">Filter User</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <label class="block text-sm text-gray-700">
                        User Spesifik (opsional)
                        <select class="mt-1 w-full form-select" name="user_id" id="userSelect">
                            <option value="">-- Semua User --</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                            @endforeach
                        </select>
                    </label>
                    
                    <label class="block text-sm text-gray-700">
                        Filter by Role
                        <select class="mt-1 w-full form-select" name="role_id">
                            <option value="">-- Semua Role --</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}">{{ ucfirst($role->name) }}</option>
                            @endforeach
                        </select>
                    </label>
                </div>
                <label class="block text-sm text-gray-700 mt-3">
                    Filter by Shift
                    <select class="mt-1 w-full form-select" name="shift_id">
                        <option value="">-- Semua Shift --</option>
                        @foreach($shifts as $shift)
                            <option value="{{ $shift->id }}">{{ $shift->name }} ({{ $shift->start_time }} - {{ $shift->end_time }})</option>
                        @endforeach
                    </select>
                </label>
            </div>
            
            <!-- Filter Status -->
            <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
                <h4 class="text-sm font-semibold text-gray-700 mb-3">Filter Status Kehadiran</h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2">
                    <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                        <input class="form-check" type="checkbox" name="status[]" value="present" checked>
                        Hadir
                    </label>
                    <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                        <input class="form-check" type="checkbox" name="status[]" value="late" checked>
                        Terlambat
                    </label>
                    <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                        <input class="form-check" type="checkbox" name="status[]" value="absent">
                        Tidak Hadir
                    </label>
                    <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                        <input class="form-check" type="checkbox" name="status[]" value="sick">
                        Sakit
                    </label>
                    <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                        <input class="form-check" type="checkbox" name="status[]" value="leave">
                        Cuti
                    </label>
                </div>
            </div>
            
            <!-- Format Export -->
            <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
                <h4 class="text-sm font-semibold text-gray-700 mb-3">Format Export</h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                    <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                        <input class="form-check" type="radio" name="format" value="csv" checked>
                        CSV (Excel)
                    </label>
                    <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                        <input class="form-check" type="radio" name="format" value="pdf">
                        PDF (Dokumen)
                    </label>
                </div>
            </div>
            
            <!-- Kolom yang Ditampilkan -->
            <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
                <h4 class="text-sm font-semibold text-gray-700 mb-3">Kolom yang Ditampilkan</h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2">
                    <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                        <input class="form-check" type="checkbox" name="columns[]" value="date" checked>
                        Tanggal
                    </label>
                    <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                        <input class="form-check" type="checkbox" name="columns[]" value="user_name" checked>
                        Nama User
                    </label>
                    <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                        <input class="form-check" type="checkbox" name="columns[]" value="check_in" checked>
                        Check In
                    </label>
                    <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                        <input class="form-check" type="checkbox" name="columns[]" value="check_out" checked>
                        Check Out
                    </label>
                    <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                        <input class="form-check" type="checkbox" name="columns[]" value="work_hours" checked>
                        Jam Kerja
                    </label>
                    <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                        <input class="form-check" type="checkbox" name="columns[]" value="status" checked>
                        Status
                    </label>
                    <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                        <input class="form-check" type="checkbox" name="columns[]" value="location">
                        Lokasi
                    </label>
                    <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                        <input class="form-check" type="checkbox" name="columns[]" value="notes">
                        Catatan
                    </label>
                </div>
            </div>
            
            <!-- Summary Options -->
            <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
                <h4 class="text-sm font-semibold text-gray-700 mb-3">Opsi Summary</h4>
                <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                    <input class="form-check" type="checkbox" name="include_summary" value="1" checked>
                    Sertakan ringkasan statistik (total hadir, terlambat, jam kerja)
                </label>
            </div>
            
            <!-- Actions -->
            <div class="flex flex-wrap gap-3 pt-2">
                <button type="submit" class="btn btn-primary">
                    Download Laporan
                </button>
                <button type="button" onclick="previewData()" class="btn btn-secondary">
                    Preview Data
                </button>
                <button type="reset" class="btn btn-secondary">
                    Reset Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Preview Section -->
    <div id="previewSection" class="hidden">
        <div class="bg-white rounded-2xl border border-gray-200 shadow-soft p-6">
            <h3 class="text-base font-semibold text-gray-800 mb-3">Preview Data</h3>
            <div id="previewContent" class="overflow-x-auto">
                <!-- Preview will be loaded here -->
            </div>
        </div>
    </div>
</div>

<style>
    .form-input,
    .form-select {
        border: 1px solid #e5e7eb;
        border-radius: 0.75rem;
        padding: 0.6rem 0.75rem;
        background: #ffffff;
        color: #111827;
        transition: box-shadow 0.2s ease, border-color 0.2s ease;
    }

    .form-input:focus,
    .form-select:focus {
        outline: none;
        border-color: #93c5fd;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
    }

    .form-check {
        width: auto;
        cursor: pointer;
    }

    .btn-xs {
        font-size: 12px;
        padding: 6px 12px;
    }
</style>

<script>
function setThisMonth() {
    const now = new Date();
    const firstDay = new Date(now.getFullYear(), now.getMonth(), 1);
    const lastDay = new Date(now.getFullYear(), now.getMonth() + 1, 0);
    
    document.querySelector('input[name="start_date"]').value = formatDate(firstDay);
    document.querySelector('input[name="end_date"]').value = formatDate(lastDay);
}

function setLastMonth() {
    const now = new Date();
    const firstDay = new Date(now.getFullYear(), now.getMonth() - 1, 1);
    const lastDay = new Date(now.getFullYear(), now.getMonth(), 0);
    
    document.querySelector('input[name="start_date"]').value = formatDate(firstDay);
    document.querySelector('input[name="end_date"]').value = formatDate(lastDay);
}

function setThisYear() {
    const now = new Date();
    const firstDay = new Date(now.getFullYear(), 0, 1);
    const lastDay = new Date(now.getFullYear(), 11, 31);
    
    document.querySelector('input[name="start_date"]').value = formatDate(firstDay);
    document.querySelector('input[name="end_date"]').value = formatDate(lastDay);
}

function formatDate(date) {
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    return `${year}-${month}-${day}`;
}

function previewData() {
    // Get form data
    const form = document.querySelector('form');
    const formData = new FormData(form);
    
    // Build query string
    const params = new URLSearchParams();
    for (let [key, value] of formData.entries()) {
        params.append(key, value);
    }
    
    // Show loading
    document.getElementById('previewSection').classList.remove('hidden');
    document.getElementById('previewContent').innerHTML = '<p style="text-align:center;padding:40px;color:#6b7280">Loading preview...</p>';
    
    // Fetch preview data
    fetch(`{{ route('admin.reports.preview') }}?${params.toString()}`)
        .then(response => response.text())
        .then(html => {
            document.getElementById('previewContent').innerHTML = html;
        })
        .catch(error => {
            document.getElementById('previewContent').innerHTML = '<p style="text-align:center;padding:40px;color:#ef4444">Error loading preview</p>';
        });
}

// Set default date on load
document.addEventListener('DOMContentLoaded', function() {
    // Already set via PHP date() functions
});
</script>
@endsection
