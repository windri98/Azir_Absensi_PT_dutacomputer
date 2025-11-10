@extends('admin.layout')

@section('title', 'Kelola Pengguna')

@section('content')
<div class="page-header">
    <h2>Kelola Pengguna</h2>
    <div class="actions">
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">+ Tambah User</a>
    </div>
</div>

<div class="card" style="margin-bottom:8px;padding:12px;background:#f0f9ff;border-left:4px solid #0ea5e9">
    <p style="margin:0;font-size:13px;color:#0c4a6e">
        💡 <strong>Info Absensi:</strong> Data menampilkan kehadiran bulan ini ({{ date('F Y') }})
    </p>
</div>

<div class="card" style="margin-bottom:16px">
    <form method="get" style="display:flex;gap:8px;align-items:center">
        <input type="text" name="q" placeholder="Cari nama/email/telepon..." value="{{ $search }}" style="flex:1;margin:0" />
        <button class="btn btn-secondary" type="submit">🔍 Cari</button>
        @if($search)
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Clear</a>
        @endif
    </form>
</div>

<div style="overflow-x:auto">
<table>
    <thead>
        <tr>
            <th>ID Card</th>
            <th>Nama</th>
            <th>Email</th>
            <th>Telepon</th>
            <th>Roles</th>
            <th>Shifts</th>
            <th style="text-align:center">Hadir Bulan Ini</th>
            <th style="text-align:center">Terlambat</th>
            <th style="text-align:center">Total Jam</th>
            <th>Terdaftar</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse($users as $user)
        <tr>
            <td><span style="font-weight:600;color:#0ea5e9">{{ $user->employee_id ?: '-' }}</span></td>
            <td><strong>{{ $user->name }}</strong></td>
            <td>{{ $user->email }}</td>
            <td>{{ $user->phone ?: '-' }}</td>
            <td><span style="font-size:12px;background:#e0e7ff;color:#4338ca;padding:2px 6px;border-radius:4px;display:inline-block">{{ $user->roles->pluck('name')->join(', ') ?: '-' }}</span></td>
            <td><span style="font-size:12px;background:#fef3c7;color:#92400e;padding:2px 6px;border-radius:4px;display:inline-block">{{ $user->shifts->pluck('name')->join(', ') ?: '-' }}</span></td>
            <td style="text-align:center">
                <span style="font-size:14px;font-weight:600;color:#059669;background:#d1fae5;padding:4px 8px;border-radius:6px;display:inline-block">
                    {{ $user->total_hadir ?? 0 }} hari
                </span>
            </td>
            <td style="text-align:center">
                <span style="font-size:14px;font-weight:600;color:#dc2626;background:#fee2e2;padding:4px 8px;border-radius:6px;display:inline-block">
                    {{ $user->total_terlambat ?? 0 }}x
                </span>
            </td>
            <td style="text-align:center">
                <span style="font-size:14px;font-weight:600;color:#2563eb;background:#dbeafe;padding:4px 8px;border-radius:6px;display:inline-block">
                    {{ number_format($user->total_jam_kerja ?? 0, 1) }} jam
                </span>
            </td>
            <td style="font-size:12px;color:#6b7280">{{ $user->created_at->format('d/m/Y') }}</td>
            <td style="white-space:nowrap">
                <a class="btn btn-secondary" href="{{ route('admin.users.attendance',$user) }}" style="padding:6px 10px;font-size:13px;background:#0ea5e9;color:white" title="Lihat Detail Absensi">📊</a>
                <a class="btn btn-secondary" href="{{ route('admin.users.edit',$user) }}" style="padding:6px 10px;font-size:13px">Edit</a>
                <form method="post" action="{{ route('admin.users.destroy',$user) }}" style="display:inline" onsubmit="return confirm('Hapus user ini?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger" style="padding:6px 10px;font-size:13px">Hapus</button>
                </form>
            </td>
        </tr>
        @empty
        <tr><td colspan="13" style="text-align:center;color:#9ca3af">Tidak ada data</td></tr>
        @endforelse
    </tbody>
</table>
</div>

<div class="pagination">{{ $users->withQueryString()->links() }}</div>
@endsection
