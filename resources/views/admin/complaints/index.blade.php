@extends('admin.layout')

@section('title', 'Kelola Pengajuan Izin/Cuti')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-2">
        <h2 class="text-2xl font-bold text-main">Daftar Pengajuan</h2>
        <div class="flex gap-2">
            <div class="modern-card !p-2 !px-4 flex items-center gap-3 bg-warning-light border-warning/20">
                <span class="text-xs font-bold text-warning uppercase">Menunggu</span>
                <span class="text-lg font-black text-warning">{{ $complaints->where('status', 'pending')->count() }}</span>
            </div>
            <div class="modern-card !p-2 !px-4 flex items-center gap-3 bg-success-light border-success/20">
                <span class="text-xs font-bold text-success uppercase">Disetujui</span>
                <span class="text-lg font-black text-success">{{ $complaints->where('status', 'approved')->count() }}</span>
            </div>
        </div>
    </div>

    <!-- Filter & Search -->
    <div class="modern-card">
        <form method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
            <div class="form-group !m-0">
                <label class="text-[10px] font-bold text-light uppercase tracking-widest mb-1 block">Status</label>
                <select name="status" class="form-input">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Disetujui</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>
            
            <div class="form-group !m-0">
                <label class="text-[10px] font-bold text-light uppercase tracking-widest mb-1 block">Kategori</label>
                <select name="category" class="form-input">
                    <option value="">Semua Kategori</option>
                    <option value="cuti" {{ request('category') == 'cuti' ? 'selected' : '' }}>Cuti</option>
                    <option value="sakit" {{ request('category') == 'sakit' ? 'selected' : '' }}>Sakit</option>
                    <option value="izin" {{ request('category') == 'izin' ? 'selected' : '' }}>Izin</option>
                    <option value="lainnya" {{ request('category') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                </select>
            </div>
            
            <div class="form-group !m-0 lg:col-span-2">
                <label class="text-[10px] font-bold text-light uppercase tracking-widest mb-1 block">Cari Pengajuan</label>
                <input type="text" name="search" class="form-input" placeholder="Cari berdasarkan judul atau deskripsi..." value="{{ request('search') }}">
            </div>
            
            <div class="flex items-end gap-2">
                <button type="submit" class="btn btn-primary flex-1 py-2.5">
                    <i class="fas fa-filter mr-1"></i> Filter
                </button>
                <a href="{{ route('admin.complaints.index') }}" class="btn btn-secondary py-2.5" title="Reset">
                    <i class="fas fa-undo"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Complaints Table -->
    <div class="table-container shadow-sm">
        @if($complaints->count() > 0)
            <table class="table">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Karyawan</th>
                        <th>Kategori</th>
                        <th>Judul Pengajuan</th>
                        <th>Prioritas</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($complaints as $complaint)
                    <tr>
                        <td class="text-xs font-bold text-muted">{{ $complaint->created_at->format('d/m/Y') }}</td>
                        <td>
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-primary-light text-primary-color flex items-center justify-center text-[10px] font-bold">
                                    {{ strtoupper(substr($complaint->user->name, 0, 2)) }}
                                </div>
                                <div class="flex flex-col">
                                    <span class="font-bold text-sm text-main">{{ $complaint->user->name }}</span>
                                    <span class="text-[10px] text-light">{{ $complaint->user->employee_id }}</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            @php
                                $catBadge = match($complaint->category) {
                                    'cuti' => 'badge-info',
                                    'sakit' => 'badge-danger',
                                    'izin' => 'badge-warning',
                                    default => 'badge-success'
                                };
                            @endphp
                            <span class="badge {{ $catBadge }}">{{ ucfirst($complaint->category) }}</span>
                        </td>
                        <td class="text-sm text-main">{{ Str::limit($complaint->title, 35) }}</td>
                        <td>
                            @php
                                $priBadge = match($complaint->priority) {
                                    'high', 'urgent' => 'text-danger font-black',
                                    'medium' => 'text-warning font-bold',
                                    default => 'text-muted'
                                };
                            @endphp
                            <span class="text-[10px] uppercase tracking-tighter {{ $priBadge }}">
                                {{ $complaint->priority ?: 'Normal' }}
                            </span>
                        </td>
                        <td class="text-center">
                            @php
                                $statusBadge = match($complaint->status) {
                                    'pending' => 'badge-warning',
                                    'approved' => 'badge-success',
                                    'rejected' => 'badge-danger',
                                    default => 'badge-info'
                                };
                            @endphp
                            <span class="badge {{ $statusBadge }}">
                                {{ ucfirst($complaint->status) }}
                            </span>
                        </td>
                        <td class="text-center">
                            <a href="{{ route('admin.complaints.show', $complaint->id) }}" class="btn btn-secondary !p-2 !rounded-lg hover:!text-primary-color">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="empty-state">
                <i class="fas fa-file-invoice empty-state-icon"></i>
                <p class="empty-state-text">Tidak ada pengajuan izin yang ditemukan</p>
            </div>
        @endif
    </div>
    
    @if($complaints->hasPages())
    <div class="mt-6">
        {{ $complaints->links() }}
    </div>
    @endif
</div>
@endsection
