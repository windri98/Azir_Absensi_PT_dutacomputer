@extends('admin.layout')

@section('title', 'Kelola Pengajuan Izin/Cuti')

@section('content')
<div class="page-header">
    <h2>Kelola Pengajuan Izin & Cuti</h2>
    <a href="{{ route('admin.dashboard') }}" class="btn-secondary">← Kembali</a>
</div>

<!-- Filter & Search -->
<div class="card" style="margin-bottom:20px">
    <form method="GET" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:12px">
        <div>
            <label style="font-size:13px;color:#6b7280;display:block;margin-bottom:4px">Status</label>
            <select name="status" class="form-input" style="width:100%">
                <option value="">Semua Status</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Disetujui</option>
                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
            </select>
        </div>
        
        <div>
            <label style="font-size:13px;color:#6b7280;display:block;margin-bottom:4px">Kategori</label>
            <select name="category" class="form-input" style="width:100%">
                <option value="">Semua Kategori</option>
                <option value="cuti" {{ request('category') == 'cuti' ? 'selected' : '' }}>Cuti</option>
                <option value="sakit" {{ request('category') == 'sakit' ? 'selected' : '' }}>Sakit</option>
                <option value="izin" {{ request('category') == 'izin' ? 'selected' : '' }}>Izin</option>
                <option value="lainnya" {{ request('category') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
            </select>
        </div>
        
        <div>
            <label style="font-size:13px;color:#6b7280;display:block;margin-bottom:4px">Karyawan</label>
            <select name="user_id" class="form-input" style="width:100%">
                <option value="">Semua Karyawan</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                        {{ $user->name }}
                    </option>
                @endforeach
            </select>
        </div>
        
        <div>
            <label style="font-size:13px;color:#6b7280;display:block;margin-bottom:4px">Cari</label>
            <input type="text" name="search" class="form-input" placeholder="Cari judul..." value="{{ request('search') }}" style="width:100%">
        </div>
        
        <div style="display:flex;align-items:end;gap:8px">
            <button type="submit" class="btn-primary" style="flex:1">Filter</button>
            <a href="{{ route('admin.complaints.index') }}" class="btn-secondary">Reset</a>
        </div>
    </form>
</div>

<!-- Statistics -->
<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-bottom:24px">
    <div class="card" style="border-left:4px solid #f59e0b">
        <h3 style="font-size:13px;color:#6b7280;margin-bottom:8px">Menunggu</h3>
        <div style="font-size:28px;font-weight:700;color:#f59e0b">
            {{ $complaints->where('status', 'pending')->count() }}
        </div>
    </div>
    <div class="card" style="border-left:4px solid #10b981">
        <h3 style="font-size:13px;color:#6b7280;margin-bottom:8px">Disetujui</h3>
        <div style="font-size:28px;font-weight:700;color:#10b981">
            {{ $complaints->where('status', 'approved')->count() }}
        </div>
    </div>
    <div class="card" style="border-left:4px solid #ef4444">
        <h3 style="font-size:13px;color:#6b7280;margin-bottom:8px">Ditolak</h3>
        <div style="font-size:28px;font-weight:700;color:#ef4444">
            {{ $complaints->where('status', 'rejected')->count() }}
        </div>
    </div>
</div>

<!-- Complaints Table -->
<div class="card">
    @if($complaints->count() > 0)
        <div style="overflow-x:auto">
            <table style="width:100%;border-collapse:collapse">
                <thead>
                    <tr style="border-bottom:2px solid #e5e7eb">
                        <th style="text-align:left;padding:12px 8px;font-size:13px;color:#6b7280">Tanggal</th>
                        <th style="text-align:left;padding:12px 8px;font-size:13px;color:#6b7280">Karyawan</th>
                        <th style="text-align:left;padding:12px 8px;font-size:13px;color:#6b7280">Kategori</th>
                        <th style="text-align:left;padding:12px 8px;font-size:13px;color:#6b7280">Judul</th>
                        <th style="text-align:left;padding:12px 8px;font-size:13px;color:#6b7280">Prioritas</th>
                        <th style="text-align:center;padding:12px 8px;font-size:13px;color:#6b7280">Status</th>
                        <th style="text-align:center;padding:12px 8px;font-size:13px;color:#6b7280">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($complaints as $complaint)
                    <tr style="border-bottom:1px solid #f3f4f6">
                        <td style="padding:12px 8px;font-size:13px">
                            {{ $complaint->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td style="padding:12px 8px;font-size:13px">
                            <strong>{{ $complaint->user->name }}</strong><br>
                            <small style="color:#6b7280">{{ $complaint->user->email }}</small>
                        </td>
                        <td style="padding:12px 8px;font-size:13px">
                            @php
                                $categoryBadge = [
                                    'cuti' => ['bg' => '#dbeafe', 'text' => '#1e40af', 'icon' => '🏖️'],
                                    'sakit' => ['bg' => '#fee2e2', 'text' => '#991b1b', 'icon' => '🤒'],
                                    'izin' => ['bg' => '#fef3c7', 'text' => '#92400e', 'icon' => '📝'],
                                    'lainnya' => ['bg' => '#f3f4f6', 'text' => '#374151', 'icon' => '💬'],
                                ];
                                $badge = $categoryBadge[$complaint->category] ?? $categoryBadge['lainnya'];
                            @endphp
                            <span style="background:{{ $badge['bg'] }};color:{{ $badge['text'] }};padding:4px 8px;border-radius:4px;font-size:12px;font-weight:600">
                                {{ $badge['icon'] }} {{ ucfirst($complaint->category) }}
                            </span>
                        </td>
                        <td style="padding:12px 8px;font-size:13px">{{ Str::limit($complaint->title, 40) }}</td>
                        <td style="padding:12px 8px;font-size:13px">
                            @php
                                $priorityBadge = [
                                    'low' => ['bg' => '#d1fae5', 'text' => '#065f46', 'label' => 'Rendah'],
                                    'medium' => ['bg' => '#fef3c7', 'text' => '#92400e', 'label' => 'Sedang'],
                                    'normal' => ['bg' => '#fef3c7', 'text' => '#92400e', 'label' => 'Normal'],
                                    'high' => ['bg' => '#fee2e2', 'text' => '#991b1b', 'label' => 'Tinggi'],
                                ];
                                $priority = $priorityBadge[$complaint->priority] ?? $priorityBadge['medium'];
                            @endphp
                            <span style="background:{{ $priority['bg'] }};color:{{ $priority['text'] }};padding:4px 8px;border-radius:4px;font-size:11px;font-weight:600">
                                {{ $priority['label'] }}
                            </span>
                        </td>
                        <td style="padding:12px 8px;text-align:center">
                            @if($complaint->status == 'pending')
                                <span style="background:#fef3c7;color:#92400e;padding:4px 12px;border-radius:12px;font-size:12px;font-weight:600">
                                    ⏳ Pending
                                </span>
                            @elseif($complaint->status == 'approved')
                                <span style="background:#d1fae5;color:#065f46;padding:4px 12px;border-radius:12px;font-size:12px;font-weight:600">
                                    ✅ Disetujui
                                </span>
                            @else
                                <span style="background:#fee2e2;color:#991b1b;padding:4px 12px;border-radius:12px;font-size:12px;font-weight:600">
                                    ❌ Ditolak
                                </span>
                            @endif
                        </td>
                        <td style="padding:12px 8px;text-align:center">
                            <a href="{{ route('admin.complaints.show', $complaint->id) }}" class="btn-primary" style="padding:6px 12px;font-size:12px">
                                👁️ Detail
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div style="margin-top:20px">
            {{ $complaints->links() }}
        </div>
    @else
        <div style="text-align:center;padding:40px;color:#6b7280">
            <div style="font-size:48px;margin-bottom:16px;opacity:0.5">📝</div>
            <div>Tidak ada pengajuan ditemukan</div>
        </div>
    @endif
</div>
@endsection
