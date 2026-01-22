@extends('admin.layout')

@section('title', 'Data Mitra')

@section('content')
<div class="page-header">
    <h2>Data Mitra</h2>
    <div class="actions">
        <a href="{{ route('admin.partners.create') }}" class="btn btn-primary">+ Tambah Mitra</a>
    </div>
</div>

<form method="GET" class="card" style="margin-bottom:16px">
    <div style="display:flex;gap:12px;align-items:center">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama/alamat/PIC/telepon" class="input" style="flex:1">
        <button class="btn btn-secondary">Filter</button>
        <a href="{{ route('admin.partners.index') }}" class="btn btn-light">Reset</a>
    </div>
</form>

<table>
    <thead>
        <tr>
            <th style="width:60px">ID</th>
            <th>Nama Mitra</th>
            <th>PIC</th>
            <th>Telepon</th>
            <th>Alamat</th>
            <th style="text-align:center">Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse($partners as $partner)
        <tr>
            <td>{{ $partner->id }}</td>
            <td><strong>{{ $partner->name }}</strong></td>
            <td>{{ $partner->contact_person ?? '-' }}</td>
            <td>{{ $partner->phone ?? '-' }}</td>
            <td>{{ $partner->address ? \Illuminate\Support\Str::limit($partner->address, 60) : '-' }}</td>
            <td style="text-align:center;white-space:nowrap">
                <a href="{{ route('admin.partners.edit', $partner) }}" class="btn btn-secondary" style="padding:6px 10px;font-size:13px">Edit</a>
                <form method="post" action="{{ route('admin.partners.destroy', $partner) }}" style="display:inline" onsubmit="return confirm('Hapus mitra ini?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-danger" style="padding:6px 10px;font-size:13px">Hapus</button>
                </form>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="6" style="text-align:center;padding:40px;color:#9ca3af">
                <div style="font-size:48px;margin-bottom:12px">🏢</div>
                <div style="font-size:16px;margin-bottom:8px">Belum ada data mitra</div>
                <div style="font-size:14px">Tambahkan mitra untuk kebutuhan aktivitas teknisi</div>
            </td>
        </tr>
        @endforelse
    </tbody>
</table>

@if($partners->hasPages())
<div class="pagination" style="margin-top:16px">{{ $partners->links() }}</div>
@endif
@endsection
