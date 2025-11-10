@extends('admin.layout')

@section('title', 'Detail Absensi - ' . $user->name)

@section('content')
<div class="page-header">
    <h2>Detail Absensi: {{ $user->name }}</h2>
    <div class="actions">
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">← Kembali</a>
    </div>
</div>

<!-- Filter -->
<div class="card" style="margin-bottom:16px">
    <form method="get" style="display:flex;gap:12px;align-items:end;flex-wrap:wrap">
        <div style="flex:1;min-width:150px">
            <label style="display:block;margin-bottom:4px;font-size:13px;color:#6b7280">Bulan</label>
            <select name="month" style="width:100%;margin:0">
                @for($m = 1; $m <= 12; $m++)
                    <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                        {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                    </option>
                @endfor
            </select>
        </div>
        <div style="flex:1;min-width:150px">
            <label style="display:block;margin-bottom:4px;font-size:13px;color:#6b7280">Tahun</label>
            <select name="year" style="width:100%;margin:0">
                @for($y = date('Y'); $y >= date('Y') - 2; $y--)
                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
            </select>
        </div>
        <button type="submit" class="btn btn-primary" style="margin:0">Filter</button>
    </form>
</div>

<!-- Summary Stats -->
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:12px;margin-bottom:20px">
    <div class="card" style="text-align:center;background:#f0fdf4;border:2px solid #bbf7d0">
        <div style="font-size:32px;font-weight:bold;color:#16a34a">{{ $summary['total_hadir'] }}</div>
        <div style="font-size:14px;color:#6b7280;margin-top:4px">Total Hadir</div>
    </div>
    <div class="card" style="text-align:center;background:#fef2f2;border:2px solid #fecaca">
        <div style="font-size:32px;font-weight:bold;color:#dc2626">{{ $summary['total_terlambat'] }}</div>
        <div style="font-size:14px;color:#6b7280;margin-top:4px">Terlambat</div>
    </div>
    <div class="card" style="text-align:center;background:#eff6ff;border:2px solid #bfdbfe">
        <div style="font-size:32px;font-weight:bold;color:#2563eb">{{ number_format($summary['total_jam'], 1) }}</div>
        <div style="font-size:14px;color:#6b7280;margin-top:4px">Total Jam Kerja</div>
    </div>
    <div class="card" style="text-align:center;background:#fefce8;border:2px solid #fef08a">
        <div style="font-size:32px;font-weight:bold;color:#ca8a04">{{ $summary['avg_jam'] }}</div>
        <div style="font-size:14px;color:#6b7280;margin-top:4px">Rata-rata/Hari</div>
    </div>
</div>

<!-- Attendance List -->
<div class="card">
    <h3 style="margin:0 0 16px 0;color:#374151">Riwayat Absensi</h3>
    <div style="overflow-x:auto">
        <table>
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Hari</th>
                    <th>Clock In</th>
                    <th>Clock Out</th>
                    <th>Jam Kerja</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($attendances as $att)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($att->date)->format('d/m/Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($att->date)->locale('id')->isoFormat('dddd') }}</td>
                    <td>{{ $att->check_in ?? '-' }}</td>
                    <td>{{ $att->check_out ?? '-' }}</td>
                    <td style="font-weight:600;color:#2563eb">
                        {{ $att->work_hours ? number_format($att->work_hours, 2) . ' jam' : '-' }}
                    </td>
                    <td>
                        @if($att->status == 'present')
                            <span style="background:#d1fae5;color:#059669;padding:4px 8px;border-radius:4px;font-size:12px;font-weight:500">Hadir</span>
                        @elseif($att->status == 'late')
                            <span style="background:#fee2e2;color:#dc2626;padding:4px 8px;border-radius:4px;font-size:12px;font-weight:500">Terlambat</span>
                        @elseif($att->status == 'absent')
                            <span style="background:#f3f4f6;color:#6b7280;padding:4px 8px;border-radius:4px;font-size:12px;font-weight:500">Tidak Hadir</span>
                        @else
                            <span style="background:#fef3c7;color:#92400e;padding:4px 8px;border-radius:4px;font-size:12px;font-weight:500">{{ ucfirst($att->status) }}</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align:center;color:#9ca3af;padding:40px">
                        Tidak ada data absensi untuk periode ini
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($attendances->hasPages())
    <div class="pagination" style="margin-top:16px">
        {{ $attendances->appends(['month' => $month, 'year' => $year])->links() }}
    </div>
    @endif
</div>
@endsection
