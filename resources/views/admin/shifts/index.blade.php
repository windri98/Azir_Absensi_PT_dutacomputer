@extends('admin.layout')

@section('title', 'Kelola Shift')

@section('content')
<div class="page-header">
    <h2>Kelola Shift</h2>
    <div class="actions">
        <a href="{{ route('admin.shifts.create') }}" class="btn btn-primary">+ Tambah Shift</a>
    </div>
</div>

<div class="card" style="margin-bottom:16px;background:#f0f9ff;border-left:4px solid #0ea5e9">
    <p style="margin:0;font-size:13px;color:#0c4a6e">
        💡 <strong>Info:</strong> Shift menentukan jam kerja karyawan. Setiap user bisa memiliki lebih dari 1 shift dengan periode waktu berbeda.
    </p>
</div>

<table>
    <thead>
        <tr>
            <th style="width:60px">ID</th>
            <th>Nama Shift</th>
            <th style="text-align:center">Jam Mulai</th>
            <th style="text-align:center">Jam Selesai</th>
            <th style="text-align:center">Durasi</th>
            <th style="text-align:center">Total User</th>
            <th style="text-align:center">Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse($shifts as $shift)
        <tr>
            <td>{{ $shift->id }}</td>
            <td>
                <strong>{{ $shift->name }}</strong>
                @php
                    $hour = intval(substr($shift->start_time, 0, 2));
                    if ($hour >= 5 && $hour < 12) {
                        $icon = '🌅';
                    } elseif ($hour >= 12 && $hour < 17) {
                        $icon = '☀️';
                    } elseif ($hour >= 17 && $hour < 21) {
                        $icon = '🌆';
                    } else {
                        $icon = '🌙';
                    }
                @endphp
                <span style="font-size:16px;margin-left:4px">{{ $icon }}</span>
            </td>
            <td style="text-align:center;font-weight:600;color:#059669">{{ substr($shift->start_time, 0, 5) }}</td>
            <td style="text-align:center;font-weight:600;color:#dc2626">{{ substr($shift->end_time, 0, 5) }}</td>
            <td style="text-align:center;font-weight:600;color:#2563eb">
                @php
                    $start = \Carbon\Carbon::parse($shift->start_time);
                    $end = \Carbon\Carbon::parse($shift->end_time);
                    if ($end->lessThan($start)) {
                        $end->addDay();
                    }
                    $duration = $start->diffInHours($end, false);
                @endphp
                {{ $duration }} jam
            </td>
            <td style="text-align:center">
                <span style="background:#e0e7ff;color:#4338ca;padding:4px 10px;border-radius:6px;font-size:12px;font-weight:600">
                    {{ $shift->users()->count() }} user
                </span>
            </td>
            <td style="text-align:center;white-space:nowrap">
                <a href="{{ route('admin.shifts.edit',$shift) }}" class="btn btn-secondary" style="padding:6px 10px;font-size:13px">Edit</a>
                <form method="post" action="{{ route('admin.shifts.destroy',$shift) }}" style="display:inline" onsubmit="return confirm('Hapus shift ini? User yang terhubung akan kehilangan shift ini.')">
                    @csrf @method('DELETE')
                    <button class="btn btn-danger" style="padding:6px 10px;font-size:13px">Hapus</button>
                </form>
            </td>
        </tr>
        @empty
        <tr><td colspan="7" style="text-align:center;padding:40px;color:#9ca3af">
            <div style="font-size:48px;margin-bottom:12px">⏰</div>
            <div style="font-size:16px;margin-bottom:8px">Belum ada shift</div>
            <div style="font-size:14px">Silakan tambahkan shift baru untuk mengatur jam kerja karyawan</div>
        </td></tr>
        @endforelse
    </tbody>
</table>

@if($shifts->hasPages())
<div class="pagination" style="margin-top:16px">{{ $shifts->links() }}</div>
@endif
@endsection
