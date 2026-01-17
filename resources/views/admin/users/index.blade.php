@extends('admin.layout')

@section('title', 'Kelola Pengguna')

@section('content')
<div class="space-y-6">
    <div class="card !p-4 border-l-4 border-primary-color flex items-center gap-3" style="background: var(--primary-light);">
        <i class="fas fa-info-circle text-primary-color"></i>
        <p class="text-xs font-bold text-primary-dark">
            Data menampilkan statistik kehadiran bulan ini ({{ date('F Y') }})
        </p>
    </div>

    <div class="modern-card">
        <form method="get" class="flex gap-3">
            <div class="flex-1">
                <input type="text" name="q" placeholder="Cari nama, email, atau nomor telepon..." value="{{ $search }}" class="form-input">
            </div>
            <button class="btn btn-primary" type="submit">
                <i class="fas fa-search mr-1"></i> Cari
            </button>
            @if($search)
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Reset</a>
            @endif
            <a href="{{ route('admin.users.create') }}" class="btn btn-success ml-auto">
                <i class="fas fa-plus mr-1"></i> Tambah User
            </a>
        </form>
    </div>

    <div class="table-container shadow-sm">
        <table class="table">
            <thead>
                <tr>
                    <th>ID Card</th>
                    <th>Nama & Email</th>
                    <th>Telepon</th>
                    <th>Role</th>
                    <th>Shift</th>
                    <th class="text-center">Statistik (Bulan Ini)</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td><span class="font-mono font-bold text-primary-color">{{ $user->employee_id ?: '-' }}</span></td>
                    <td>
                        <div class="flex flex-col">
                            <span class="font-bold text-main">{{ $user->name }}</span>
                            <span class="text-[10px] text-muted">{{ $user->email }}</span>
                        </div>
                    </td>
                    <td><span class="text-sm text-muted">{{ $user->phone ?: '-' }}</span></td>
                    <td>
                        <span class="badge badge-info">{{ $user->roles->pluck('display_name')->join(', ') ?: 'Karyawan' }}</span>
                    </td>
                    <td>
                        <span class="badge badge-warning">{{ $user->shifts->pluck('name')->join(', ') ?: '-' }}</span>
                    </td>
                    <td>
                        <div class="flex justify-center gap-2">
                            <div class="flex flex-col items-center p-2 bg-success-light rounded-lg min-w-[60px]">
                                <span class="text-[10px] font-bold text-success uppercase">Hadir</span>
                                <span class="text-xs font-black text-success">{{ $user->total_hadir ?? 0 }}d</span>
                            </div>
                            <div class="flex flex-col items-center p-2 bg-danger-light rounded-lg min-w-[60px]">
                                <span class="text-[10px] font-bold text-danger uppercase">Telat</span>
                                <span class="text-xs font-black text-danger">{{ $user->total_terlambat ?? 0 }}x</span>
                            </div>
                            <div class="flex flex-col items-center p-2 bg-primary-light rounded-lg min-w-[60px]">
                                <span class="text-[10px] font-bold text-primary-color uppercase">Jam</span>
                                <span class="text-xs font-black text-primary-color">{{ number_format($user->total_jam_kerja ?? 0, 0) }}h</span>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="flex gap-2">
                            <a class="btn btn-secondary !p-2" href="{{ route('admin.users.attendance',$user) }}" title="Log Absensi">
                                <i class="fas fa-calendar-alt text-primary-color"></i>
                            </a>
                            <a class="btn btn-secondary !p-2" href="{{ route('admin.users.edit',$user) }}" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form method="post" action="{{ route('admin.users.destroy',$user) }}" onsubmit="return confirm('Hapus user ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-secondary !p-2 !text-danger hover:!bg-danger-light" title="Hapus">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="py-12 text-center text-light">
                        <i class="fas fa-user-slash text-3xl mb-2"></i>
                        <p>Tidak ada data pengguna ditemukan</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($users->hasPages())
    <div class="mt-6">
        {{ $users->withQueryString()->links() }}
    </div>
    @endif
</div>
@endsection
