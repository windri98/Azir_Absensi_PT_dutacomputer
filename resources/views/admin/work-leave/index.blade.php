@extends('admin.layout')

@section('title', 'Kelola Izin Kerja')

@section('content')
<div class="page-header">
    <h2>🏢 Kelola Izin Kerja</h2>
    <p style="color:#6b7280">Menampilkan semua pengajuan izin kerja dari karyawan</p>
</div>

<!-- Filter & Search -->
<div class="card" style="margin-bottom:24px">
    <form method="GET" style="display:flex;gap:16px;align-items:end">
        <div style="flex:1">
            <label style="display:block;margin-bottom:6px;font-weight:600;color:#374151">Cari Karyawan</label>
            <input type="text" name="search" value="{{ request('search') }}" 
                   placeholder="Nama karyawan atau employee ID..." 
                   style="width:100%;padding:10px 12px;border:1px solid #d1d5db;border-radius:6px;font-size:14px">
        </div>
        <div style="width:180px">
            <label style="display:block;margin-bottom:6px;font-weight:600;color:#374151">Bulan</label>
            <select name="month" style="width:100%;padding:10px 12px;border:1px solid #d1d5db;border-radius:6px;font-size:14px">
                <option value="">Semua Bulan</option>
                @for($i = 1; $i <= 12; $i++)
                    <option value="{{ $i }}" {{ request('month') == $i ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}
                    </option>
                @endfor
            </select>
        </div>
        <div style="width:120px">
            <label style="display:block;margin-bottom:6px;font-weight:600;color:#374151">Tahun</label>
            <select name="year" style="width:100%;padding:10px 12px;border:1px solid #d1d5db;border-radius:6px;font-size:14px">
                @for($year = date('Y') - 1; $year <= date('Y') + 1; $year++)
                    <option value="{{ $year }}" {{ (request('year', date('Y')) == $year) ? 'selected' : '' }}>
                        {{ $year }}
                    </option>
                @endfor
            </select>
        </div>
        <button type="submit" class="btn-primary" style="padding:10px 20px">
            🔍 Filter
        </button>
    </form>
</div>

<!-- Statistik -->
<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-bottom:24px;">
    <div class="card">
        <h3 style="font-size:13px;color:#6b7280;margin-bottom:8px">📊 Total Izin Kerja</h3>
        <div style="font-size:32px;font-weight:700;color:#3730a3">{{ $workLeaves->total() }}</div>
        <small style="color:#6b7280">Semua pengajuan</small>
    </div>
    <div class="card">
        <h3 style="font-size:13px;color:#6b7280;margin-bottom:8px">📄 Dengan Dokumen</h3>
        <div style="font-size:32px;font-weight:700;color:#16a34a">{{ $workLeaves->where('document_filename', '!=', null)->count() }}</div>
        <small style="color:#6b7280">Ada lampiran</small>
    </div>
    <div class="card">
        <h3 style="font-size:13px;color:#6b7280;margin-bottom:8px">❗ Tanpa Dokumen</h3>
        <div style="font-size:32px;font-weight:700;color:#dc2626">{{ $workLeaves->where('document_filename', null)->count() }}</div>
        <small style="color:#6b7280">Tidak ada lampiran</small>
    </div>
</div>

<!-- Tabel Data -->
<div class="card">
    @if($workLeaves->count() > 0)
    <div style="overflow-x:auto">
        <table style="width:100%;border-collapse:collapse;font-size:14px">
            <thead style="background:#f8fafc">
                <tr>
                    <th style="padding:12px 8px;text-align:left;border-bottom:1px solid #e5e7eb">Karyawan</th>
                    <th style="padding:12px 8px;text-align:left;border-bottom:1px solid #e5e7eb">Tanggal</th>
                    <th style="padding:12px 8px;text-align:left;border-bottom:1px solid #e5e7eb">Keterangan</th>
                    <th style="padding:12px 8px;text-align:center;border-bottom:1px solid #e5e7eb">Dokumen</th>
                    <th style="padding:12px 8px;text-align:center;border-bottom:1px solid #e5e7eb">Waktu Pengajuan</th>
                    <th style="padding:12px 8px;text-align:center;border-bottom:1px solid #e5e7eb">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($workLeaves as $workLeave)
                <tr style="border-bottom:1px solid #f3f4f6">
                    <td style="padding:12px 8px">
                        <div style="display:flex;align-items:center;gap:10px">
                            <div style="width:40px;height:40px;background:#e0e7ff;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:16px;font-weight:600;color:#3730a3">
                                {{ strtoupper(substr($workLeave->user->name, 0, 1)) }}
                            </div>
                            <div>
                                <div style="font-weight:600;color:#1f2937">{{ $workLeave->user->name }}</div>
                                <div style="font-size:12px;color:#6b7280">{{ $workLeave->user->employee_id }}</div>
                                <div style="font-size:12px;color:#6b7280">{{ $workLeave->user->roles->first()->name ?? 'Employee' }}</div>
                            </div>
                        </div>
                    </td>
                    <td style="padding:12px 8px">
                        <div style="font-weight:600">{{ \Carbon\Carbon::parse($workLeave->date)->translatedFormat('d F Y') }}</div>
                        <div style="font-size:12px;color:#6b7280">{{ \Carbon\Carbon::parse($workLeave->date)->translatedFormat('l') }}</div>
                    </td>
                    <td style="padding:12px 8px;max-width:250px">
                        <div style="word-wrap:break-word;line-height:1.4">
                            {{ Str::limit($workLeave->notes, 100) }}
                        </div>
                    </td>
                    <td style="padding:12px 8px;text-align:center">
                        @if($workLeave->hasDocument())
                            <div style="margin-bottom:8px">
                                <span style="background:#dcfce7;color:#16a34a;padding:4px 8px;border-radius:4px;font-size:11px;font-weight:600">
                                    📎 Ada Dokumen
                                </span>
                            </div>
                            <div style="display:flex;gap:4px;justify-content:center">
                                <a href="{{ route('attendance.document.view', $workLeave) }}" 
                                   target="_blank" 
                                   style="padding:4px 8px;background:#dbeafe;color:#1e40af;border-radius:4px;text-decoration:none;font-size:11px">
                                    👁️ Lihat
                                </a>
                                <a href="{{ route('attendance.document.download', $workLeave) }}" 
                                   style="padding:4px 8px;background:#dcfce7;color:#16a34a;border-radius:4px;text-decoration:none;font-size:11px">
                                    💾 Download
                                </a>
                            </div>
                        @else
                            <span style="background:#fee2e2;color:#dc2626;padding:4px 8px;border-radius:4px;font-size:11px;font-weight:600">
                                📄 Tidak Ada
                            </span>
                        @endif
                    </td>
                    <td style="padding:12px 8px;text-align:center">
                        <div style="font-size:13px">{{ $workLeave->created_at->translatedFormat('d M Y') }}</div>
                        <div style="font-size:11px;color:#6b7280">{{ $workLeave->created_at->format('H:i') }}</div>
                        <div style="font-size:10px;color:#9ca3af">{{ $workLeave->created_at->diffForHumans() }}</div>
                    </td>
                    <td style="padding:12px 8px;text-align:center">
                        <a href="{{ route('admin.work-leave.detail', $workLeave->id) }}" 
                           class="btn-primary" 
                           style="padding:8px 16px;font-size:12px">
                            👁️ Detail & Review
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    <div style="margin-top:24px;display:flex;justify-content:center">
        {{ $workLeaves->appends(request()->query())->links() }}
    </div>
    @else
    <div style="text-align:center;padding:40px 20px">
        <div style="font-size:48px;margin-bottom:16px">📋</div>
        <h3 style="color:#6b7280;margin-bottom:8px">Belum Ada Pengajuan Izin Kerja</h3>
        <p style="color:#9ca3af">Pengajuan izin kerja dari karyawan akan muncul di sini</p>
    </div>
    @endif
</div>
@endsection
