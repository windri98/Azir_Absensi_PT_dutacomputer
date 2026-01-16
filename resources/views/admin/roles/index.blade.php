@extends('admin.layout')

@section('title', 'Kelola Role')

@section('content')
<div class="page-header">
    <h2>Kelola Role</h2>
    <div class="actions">
        <a href="{{ route('admin.roles.create') }}" class="btn btn-primary">+ Tambah Role</a>
    </div>
</div>

<div class="card" style="margin-bottom:8px;padding:12px;background:#f0f9ff;border-left:4px solid #0ea5e9">
    <p style="margin:0;font-size:13px;color:#0c4a6e">
        💡 <strong>Info Role:</strong> Role menentukan hak akses pengguna dalam sistem. Role sistem (admin, manager, employee, supervisor) tidak dapat dihapus.
    </p>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if($errors->any())
    <div class="alert alert-error">
        <ul style="margin:0;padding-left:20px">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div style="overflow-x:auto">
<table>
    <thead>
        <tr>
            <th>Nama Role</th>
            <th>Nama Tampilan</th>
            <th>Deskripsi</th>
            <th style="text-align:center">Jumlah User</th>
            <th style="text-align:center">Tipe</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse($roles as $role)
        <tr>
            <td>
                <span style="font-weight:600;color:#0ea5e9;font-family:monospace">{{ $role->name }}</span>
            </td>
            <td><strong>{{ $role->display_name }}</strong></td>
            <td>{{ $role->description ?: '-' }}</td>
            <td style="text-align:center">
                <a href="{{ route('admin.roles.show', $role) }}" class="link-badge">
                    {{ $role->users_count }} user
                </a>
            </td>
            <td style="text-align:center">
                @php
                    $systemRoles = ['admin', 'manager', 'employee', 'supervisor'];
                    $isSystem = in_array($role->name, $systemRoles);
                @endphp
                @if($isSystem)
                    <span style="background:#f3f4f6;color:#374151;padding:2px 8px;border-radius:12px;font-size:11px;font-weight:500">
                        🔒 Sistem
                    </span>
                @else
                    <span style="background:#dcfce7;color:#166534;padding:2px 8px;border-radius:12px;font-size:11px;font-weight:500">
                        ✨ Custom
                    </span>
                @endif
            </td>
            <td>
                <div class="action-buttons">
                    <a href="{{ route('admin.roles.show', $role) }}" class="btn btn-sm btn-secondary" title="Detail">👁️</a>
                    <a href="{{ route('admin.roles.edit', $role) }}" class="btn btn-sm btn-warning" title="Edit">✏️</a>
                    @if(!$isSystem)
                        <form method="POST" action="{{ route('admin.roles.destroy', $role) }}" style="display:inline" 
                              onsubmit="return confirm('Yakin ingin menghapus role {{ $role->display_name }}?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" title="Hapus">🗑️</button>
                        </form>
                    @endif
                </div>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="6" style="text-align:center;padding:40px;color:#6b7280">
                📝 Belum ada role yang dibuat
            </td>
        </tr>
        @endforelse
    </tbody>
</table>
</div>

<style>
.link-badge {
    display: inline-block;
    padding: 4px 8px;
    background: #e0e7ff;
    color: #3730a3;
    border-radius: 6px;
    text-decoration: none;
    font-size: 12px;
    font-weight: 500;
}

.link-badge:hover {
    background: #c7d2fe;
    color: #312e81;
}

.action-buttons {
    display: flex;
    gap: 4px;
    align-items: center;
}

.btn-sm {
    padding: 4px 8px;
    font-size: 12px;
    min-width: 32px;
    text-align: center;
}
</style>
@endsection
