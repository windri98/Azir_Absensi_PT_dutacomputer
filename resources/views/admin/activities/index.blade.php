@extends('admin.layout')

@section('title', 'Verifikasi Aktivitas')

@section('content')
<div class="page-header">
    <h2>Verifikasi Aktivitas</h2>
</div>

<form method="GET" class="card" style="margin-bottom:16px">
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:12px;align-items:end">
        <div>
            <label class="text-sm text-gray-600">Status</label>
            <select name="status" class="input">
                <option value="">Semua</option>
                <option value="pending" @selected(request('status') === 'pending')>Pending</option>
                <option value="signed" @selected(request('status') === 'signed')>Signed</option>
                <option value="approved" @selected(request('status') === 'approved')>Approved</option>
                <option value="rejected" @selected(request('status') === 'rejected')>Rejected</option>
            </select>
        </div>
        <div>
            <label class="text-sm text-gray-600">Mitra</label>
            <select name="partner_id" class="input">
                <option value="">Semua</option>
                @foreach($partners as $partner)
                    <option value="{{ $partner->id }}" @selected((string)$partner->id === request('partner_id'))>{{ $partner->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="text-sm text-gray-600">Teknisi</label>
            <select name="user_id" class="input">
                <option value="">Semua</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" @selected((string)$user->id === request('user_id'))>{{ $user->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="text-sm text-gray-600">Cari</label>
            <input type="text" name="search" value="{{ request('search') }}" class="input" placeholder="Judul/Deskripsi">
        </div>
        <div style="display:flex;gap:8px">
            <button class="btn btn-secondary">Filter</button>
            <a href="{{ route('admin.activities.index') }}" class="btn btn-light">Reset</a>
        </div>
    </div>
</form>

<table>
    <thead>
        <tr>
            <th style="width:80px">Tanggal</th>
            <th>Teknisi</th>
            <th>Mitra</th>
            <th>Judul</th>
            <th style="text-align:center">Status</th>
            <th style="text-align:center">Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse($activities as $activity)
        <tr>
            <td>{{ $activity->created_at->format('d/m/Y') }}</td>
            <td>{{ $activity->user->name ?? '-' }}</td>
            <td>{{ $activity->partner->name ?? '-' }}</td>
            <td>{{ $activity->title }}</td>
            <td style="text-align:center">
                <span class="badge
                    @if($activity->status === 'approved') badge-success
                    @elseif($activity->status === 'rejected') badge-danger
                    @elseif($activity->status === 'signed') badge-info
                    @else badge-warning
                    @endif">
                    {{ strtoupper($activity->status) }}
                </span>
            </td>
            <td style="text-align:center">
                <a href="{{ route('admin.activities.show', $activity) }}" class="btn btn-secondary" style="padding:6px 10px;font-size:13px">Detail</a>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="6" style="text-align:center;padding:40px;color:#9ca3af">
                <div style="font-size:48px;margin-bottom:12px">🛠️</div>
                <div style="font-size:16px;margin-bottom:8px">Belum ada aktivitas</div>
                <div style="font-size:14px">Aktivitas teknisi akan muncul di halaman ini</div>
            </td>
        </tr>
        @endforelse
    </tbody>
</table>

@if($activities->hasPages())
<div class="pagination" style="margin-top:16px">{{ $activities->links() }}</div>
@endif
@endsection
