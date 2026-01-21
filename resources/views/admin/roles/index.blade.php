@extends('admin.layout')

@section('title', 'Kelola Role')

@section('content')
<div class="space-y-5">
    <div class="bg-white rounded-2xl px-6 py-5 border border-gray-200 shadow-soft">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-bold text-gray-900">Kelola Role</h2>
                <p class="text-sm text-gray-500">Atur role dan akses pengguna di sistem.</p>
            </div>
            <a href="{{ route('admin.roles.create') }}" class="btn btn-primary">
                + Tambah Role
            </a>
        </div>
    </div>

    <div class="bg-primary-50 border border-primary-200 text-primary-900 rounded-xl px-5 py-4 text-sm">
        <div class="flex items-start gap-3">
            <div class="mt-0.5 text-primary-600">
                <i class="fas fa-circle-info"></i>
            </div>
            <div>
                <p class="font-semibold">Info Role</p>
                <p class="text-primary-800">Role menentukan hak akses pengguna dalam sistem. Role sistem (admin, manager, employee, supervisor) tidak dapat dihapus.</p>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="flash-message success shadow-sm">
            <i class="fas fa-check-circle"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="flash-message error shadow-sm">
            <i class="fas fa-exclamation-circle"></i>
            <span>Terjadi kesalahan:</span>
            <ul class="list-disc ml-5 text-sm">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-2xl border border-gray-200 shadow-soft overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-left text-[11px] font-semibold text-gray-600 uppercase tracking-wide">Nama Role</th>
                        <th class="px-6 py-4 text-left text-[11px] font-semibold text-gray-600 uppercase tracking-wide">Nama Tampilan</th>
                        <th class="px-6 py-4 text-left text-[11px] font-semibold text-gray-600 uppercase tracking-wide">Deskripsi</th>
                        <th class="px-6 py-4 text-center text-[11px] font-semibold text-gray-600 uppercase tracking-wide">Jumlah User</th>
                        <th class="px-6 py-4 text-center text-[11px] font-semibold text-gray-600 uppercase tracking-wide">Tipe</th>
                        <th class="px-6 py-4 text-center text-[11px] font-semibold text-gray-600 uppercase tracking-wide">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($roles as $role)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <span class="font-semibold text-primary-600 font-mono text-sm">{{ $role->name }}</span>
                        </td>
                        <td class="px-6 py-4 text-sm font-semibold text-gray-900">{{ $role->display_name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $role->description ?: '-' }}</td>
                        <td class="px-6 py-4 text-center">
                            <a href="{{ route('admin.roles.show', $role) }}" class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-primary-100 text-primary-700 hover:bg-primary-200 transition-colors">
                                {{ $role->users_count }} user
                            </a>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @php
                                $systemRoles = ['admin', 'manager', 'employee', 'supervisor'];
                                $isSystem = in_array($role->name, $systemRoles);
                            @endphp
                            @if($isSystem)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-700 border border-gray-200">
                                    Sistem
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700 border border-emerald-200">
                                    Custom
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="action-buttons">
                                <a href="{{ route('admin.roles.show', $role) }}" class="btn btn-secondary btn-xs btn-icon" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.roles.edit', $role) }}" class="btn btn-warning btn-xs btn-icon" title="Edit">
                                    <i class="fas fa-pen"></i>
                                </a>
                                @if(!$isSystem)
                                    <form method="POST" action="{{ route('admin.roles.destroy', $role) }}" class="action-form" onsubmit="return confirm('Yakin ingin menghapus role {{ $role->display_name }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-xs btn-icon" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-gray-500">
                            Belum ada role yang dibuat
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    .btn-xs {
        padding: 6px 8px;
        font-size: 12px;
        min-width: 34px;
        text-align: center;
        border-radius: 10px;
    }

    .btn-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
    }

    .action-buttons {
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .action-form {
        display: inline-flex;
        margin: 0;
    }
</style>
@endsection
