@if($includeSummary && isset($summary))
<div style="background:#f0f9ff;padding:16px;border-radius:8px;margin-bottom:16px;border-left:4px solid #0ea5e9">
    <h4 style="margin:0 0 12px 0;color:#0c4a6e">📊 Ringkasan Data</h4>
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:12px">
        <div>
            <div style="font-size:12px;color:#6b7280">Total Records</div>
            <div style="font-size:20px;font-weight:bold;color:#374151">{{ $summary['total_records'] }}</div>
        </div>
        <div>
            <div style="font-size:12px;color:#6b7280">Total Hadir</div>
            <div style="font-size:20px;font-weight:bold;color:#059669">{{ $summary['total_hadir'] }}</div>
        </div>
        <div>
            <div style="font-size:12px;color:#6b7280">Terlambat</div>
            <div style="font-size:20px;font-weight:bold;color:#dc2626">{{ $summary['total_terlambat'] }}</div>
        </div>
        <div>
            <div style="font-size:12px;color:#6b7280">Tidak Hadir</div>
            <div style="font-size:20px;font-weight:bold;color:#6b7280">{{ $summary['total_absent'] }}</div>
        </div>
        <div>
            <div style="font-size:12px;color:#6b7280">Total Jam Kerja</div>
            <div style="font-size:20px;font-weight:bold;color:#2563eb">{{ number_format($summary['total_jam_kerja'], 1) }}</div>
        </div>
        <div>
            <div style="font-size:12px;color:#6b7280">Rata-rata/Hari</div>
            <div style="font-size:20px;font-weight:bold;color:#7c3aed">{{ number_format($summary['avg_jam_kerja'], 1) }}</div>
        </div>
    </div>
</div>
@endif

<p style="margin-bottom:12px;color:#6b7280;font-size:13px">
    Menampilkan {{ $attendances->count() }} data absensi
</p>

<table style="width:100%;font-size:13px">
    <thead>
        <tr>
            <th style="text-align:left">Tanggal</th>
            <th style="text-align:left">Nama</th>
            <th style="text-align:left">Email</th>
            <th style="text-align:center">Check In</th>
            <th style="text-align:center">Check Out</th>
            <th style="text-align:center">Jam Kerja</th>
            <th style="text-align:center">Status</th>
        </tr>
    </thead>
    <tbody>
        @forelse($attendances->take(50) as $att)
        <tr>
            <td>{{ \Carbon\Carbon::parse($att->date)->format('d/m/Y') }}</td>
            <td>{{ $att->user->name }}</td>
            <td style="font-size:12px;color:#6b7280">{{ $att->user->email }}</td>
            <td style="text-align:center">{{ $att->check_in ?? '-' }}</td>
            <td style="text-align:center">{{ $att->check_out ?? '-' }}</td>
            <td style="text-align:center;font-weight:600;color:#2563eb">
                {{ $att->work_hours ? number_format($att->work_hours, 2) : '0' }}
            </td>
            <td style="text-align:center">
                @if($att->status == 'present')
                    <span style="background:#d1fae5;color:#059669;padding:4px 8px;border-radius:4px;font-size:11px">Hadir</span>
                @elseif($att->status == 'late')
                    <span style="background:#fee2e2;color:#dc2626;padding:4px 8px;border-radius:4px;font-size:11px">Terlambat</span>
                @elseif($att->status == 'absent')
                    <span style="background:#f3f4f6;color:#6b7280;padding:4px 8px;border-radius:4px;font-size:11px">Tidak Hadir</span>
                @elseif($att->status == 'sick')
                    <span style="background:#fef3c7;color:#92400e;padding:4px 8px;border-radius:4px;font-size:11px">Sakit</span>
                @elseif($att->status == 'leave')
                    <span style="background:#e0e7ff;color:#4338ca;padding:4px 8px;border-radius:4px;font-size:11px">Cuti</span>
                @endif
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="7" style="text-align:center;padding:40px;color:#9ca3af">
                Tidak ada data yang sesuai dengan filter
            </td>
        </tr>
        @endforelse
    </tbody>
</table>

@if($attendances->count() > 50)
<p style="margin-top:12px;padding:12px;background:#fef3c7;border-radius:6px;color:#92400e;font-size:13px">
    ⚠️ Preview menampilkan 50 data pertama. Total {{ $attendances->count() }} data akan di-export.
</p>
@endif
