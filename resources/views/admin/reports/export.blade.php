@extends('admin.layout')

@section('title', 'Export Laporan')

@section('content')
<div class="page-header">
    <h2>📊 Export Laporan Absensi</h2>
</div>

<div class="card" style="max-width:800px">
    <h3 style="margin:0 0 20px 0;color:#374151">Filter Data Export</h3>
    
    <form method="get" action="{{ route('admin.reports.download') }}" style="display:grid;gap:16px">
        
        <!-- Periode Waktu -->
        <div style="background:#f9fafb;padding:16px;border-radius:8px;border:2px solid #e5e7eb">
            <h4 style="margin:0 0 12px 0;color:#374151;font-size:15px">📅 Periode Waktu</h4>
            
            <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:12px">
                <label style="margin:0">
                    Tanggal Mulai *
                    <input type="date" name="start_date" value="{{ old('start_date', date('Y-m-01')) }}" required>
                </label>
                <label style="margin:0">
                    Tanggal Akhir *
                    <input type="date" name="end_date" value="{{ old('end_date', date('Y-m-d')) }}" required>
                </label>
            </div>
            
            <div style="margin-top:12px;display:flex;gap:8px;flex-wrap:wrap">
                <button type="button" onclick="setThisMonth()" class="btn btn-secondary" style="font-size:12px;padding:6px 12px">Bulan Ini</button>
                <button type="button" onclick="setLastMonth()" class="btn btn-secondary" style="font-size:12px;padding:6px 12px">Bulan Lalu</button>
                <button type="button" onclick="setThisYear()" class="btn btn-secondary" style="font-size:12px;padding:6px 12px">Tahun Ini</button>
            </div>
        </div>
        
        <!-- Filter User -->
        <div style="background:#f9fafb;padding:16px;border-radius:8px;border:2px solid #e5e7eb">
            <h4 style="margin:0 0 12px 0;color:#374151;font-size:15px">👥 Filter User</h4>
            
            <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:12px">
                <label style="margin:0">
                    User Spesifik (opsional)
                    <select name="user_id" id="userSelect">
                        <option value="">-- Semua User --</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                        @endforeach
                    </select>
                </label>
                
                <label style="margin:0">
                    Filter by Role
                    <select name="role_id">
                        <option value="">-- Semua Role --</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}">{{ ucfirst($role->name) }}</option>
                        @endforeach
                    </select>
                </label>
            </div>
            
            <label style="margin-top:12px">
                Filter by Shift
                <select name="shift_id">
                    <option value="">-- Semua Shift --</option>
                    @foreach($shifts as $shift)
                        <option value="{{ $shift->id }}">{{ $shift->name }} ({{ $shift->start_time }} - {{ $shift->end_time }})</option>
                    @endforeach
                </select>
            </label>
        </div>
        
        <!-- Filter Status -->
        <div style="background:#f9fafb;padding:16px;border-radius:8px;border:2px solid #e5e7eb">
            <h4 style="margin:0 0 12px 0;color:#374151;font-size:15px">📋 Filter Status Kehadiran</h4>
            
            <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:12px">
                <label style="display:flex;align-items:center;gap:8px;margin:0;cursor:pointer">
                    <input type="checkbox" name="status[]" value="present" checked style="width:auto;cursor:pointer">
                    <span style="font-size:13px">Hadir</span>
                </label>
                <label style="display:flex;align-items:center;gap:8px;margin:0;cursor:pointer">
                    <input type="checkbox" name="status[]" value="late" checked style="width:auto;cursor:pointer">
                    <span style="font-size:13px">Terlambat</span>
                </label>
                <label style="display:flex;align-items:center;gap:8px;margin:0;cursor:pointer">
                    <input type="checkbox" name="status[]" value="absent" style="width:auto;cursor:pointer">
                    <span style="font-size:13px">Tidak Hadir</span>
                </label>
                <label style="display:flex;align-items:center;gap:8px;margin:0;cursor:pointer">
                    <input type="checkbox" name="status[]" value="sick" style="width:auto;cursor:pointer">
                    <span style="font-size:13px">Sakit</span>
                </label>
                <label style="display:flex;align-items:center;gap:8px;margin:0;cursor:pointer">
                    <input type="checkbox" name="status[]" value="leave" style="width:auto;cursor:pointer">
                    <span style="font-size:13px">Cuti</span>
                </label>
            </div>
        </div>
        
        <!-- Format Export -->
        <div style="background:#f9fafb;padding:16px;border-radius:8px;border:2px solid #e5e7eb">
            <h4 style="margin:0 0 12px 0;color:#374151;font-size:15px">📄 Format Export</h4>
            
            <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:12px">
                <label style="display:flex;align-items:center;gap:8px;margin:0;cursor:pointer">
                    <input type="radio" name="format" value="csv" checked style="width:auto;cursor:pointer">
                    <span style="font-size:13px">CSV (Excel)</span>
                </label>
                <label style="display:flex;align-items:center;gap:8px;margin:0;cursor:pointer">
                    <input type="radio" name="format" value="pdf" style="width:auto;cursor:pointer">
                    <span style="font-size:13px">PDF (Dokumen)</span>
                </label>
            </div>
        </div>
        
        <!-- Kolom yang Ditampilkan -->
        <div style="background:#f9fafb;padding:16px;border-radius:8px;border:2px solid #e5e7eb">
            <h4 style="margin:0 0 12px 0;color:#374151;font-size:15px">📊 Kolom yang Ditampilkan</h4>
            
            <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:12px">
                <label style="display:flex;align-items:center;gap:8px;margin:0;cursor:pointer">
                    <input type="checkbox" name="columns[]" value="date" checked style="width:auto;cursor:pointer">
                    <span style="font-size:13px">Tanggal</span>
                </label>
                <label style="display:flex;align-items:center;gap:8px;margin:0;cursor:pointer">
                    <input type="checkbox" name="columns[]" value="user_name" checked style="width:auto;cursor:pointer">
                    <span style="font-size:13px">Nama User</span>
                </label>
                <label style="display:flex;align-items:center;gap:8px;margin:0;cursor:pointer">
                    <input type="checkbox" name="columns[]" value="check_in" checked style="width:auto;cursor:pointer">
                    <span style="font-size:13px">Check In</span>
                </label>
                <label style="display:flex;align-items:center;gap:8px;margin:0;cursor:pointer">
                    <input type="checkbox" name="columns[]" value="check_out" checked style="width:auto;cursor:pointer">
                    <span style="font-size:13px">Check Out</span>
                </label>
                <label style="display:flex;align-items:center;gap:8px;margin:0;cursor:pointer">
                    <input type="checkbox" name="columns[]" value="work_hours" checked style="width:auto;cursor:pointer">
                    <span style="font-size:13px">Jam Kerja</span>
                </label>
                <label style="display:flex;align-items:center;gap:8px;margin:0;cursor:pointer">
                    <input type="checkbox" name="columns[]" value="status" checked style="width:auto;cursor:pointer">
                    <span style="font-size:13px">Status</span>
                </label>
                <label style="display:flex;align-items:center;gap:8px;margin:0;cursor:pointer">
                    <input type="checkbox" name="columns[]" value="location" style="width:auto;cursor:pointer">
                    <span style="font-size:13px">Lokasi</span>
                </label>
                <label style="display:flex;align-items:center;gap:8px;margin:0;cursor:pointer">
                    <input type="checkbox" name="columns[]" value="notes" style="width:auto;cursor:pointer">
                    <span style="font-size:13px">Catatan</span>
                </label>
            </div>
        </div>
        
        <!-- Summary Options -->
        <div style="background:#f9fafb;padding:16px;border-radius:8px;border:2px solid #e5e7eb">
            <h4 style="margin:0 0 12px 0;color:#374151;font-size:15px">📈 Opsi Summary</h4>
            
            <label style="display:flex;align-items:center;gap:8px;margin:0;cursor:pointer">
                <input type="checkbox" name="include_summary" value="1" checked style="width:auto;cursor:pointer">
                <span style="font-size:13px">Sertakan ringkasan statistik (total hadir, terlambat, jam kerja)</span>
            </label>
        </div>
        
        <!-- Actions -->
        <div style="display:flex;gap:12px;padding-top:12px">
            <button type="submit" class="btn btn-primary" style="font-size:15px">
                📥 Download Laporan
            </button>
            <button type="button" onclick="previewData()" class="btn btn-secondary">
                👁️ Preview Data
            </button>
            <button type="reset" class="btn btn-secondary">
                🔄 Reset Filter
            </button>
        </div>
    </form>
</div>

<!-- Preview Section -->
<div id="previewSection" style="display:none;margin-top:20px">
    <div class="card">
        <h3 style="margin:0 0 16px 0;color:#374151">Preview Data</h3>
        <div id="previewContent" style="overflow-x:auto">
            <!-- Preview will be loaded here -->
        </div>
    </div>
</div>

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
    document.getElementById('previewSection').style.display = 'block';
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
