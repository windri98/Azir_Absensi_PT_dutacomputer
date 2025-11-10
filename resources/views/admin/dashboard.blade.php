@extends('admin.layout')

@section('title', 'Admin Dashboard')

@section('content')
<div class="page-header">
    <h2>Dashboard</h2>
</div>

<!-- Statistik Utama -->
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:24px;">
    <div class="card">
        <h3 style="font-size:13px;color:#6b7280;margin-bottom:8px">Total User</h3>
        <div style="font-size:32px;font-weight:700;color:#0ea5e9">{{ $userCount }}</div>
    </div>
    <div class="card">
        <h3 style="font-size:13px;color:#6b7280;margin-bottom:8px">Total Admin</h3>
        <div style="font-size:32px;font-weight:700;color:#10b981">{{ $adminCount }}</div>
    </div>
    <div class="card">
        <h3 style="font-size:13px;color:#6b7280;margin-bottom:8px">Absensi Hari Ini</h3>
        <div style="font-size:32px;font-weight:700;color:#8b5cf6">{{ $attendanceToday }}</div>
    </div>
    <div class="card">
        <h3 style="font-size:13px;color:#6b7280;margin-bottom:8px">Terlambat Hari Ini</h3>
        <div style="font-size:32px;font-weight:700;color:#ef4444">{{ $lateToday }}</div>
    </div>
</div>

<!-- Statistik Pengajuan Izin/Cuti -->
<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-bottom:24px;">
    <a href="{{ route('admin.complaints.index', ['status' => 'pending']) }}" style="text-decoration:none">
        <div class="card" style="border-left:4px solid #f59e0b;cursor:pointer;transition:transform .2s" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
            <h3 style="font-size:13px;color:#6b7280;margin-bottom:8px">⏳ Menunggu Persetujuan</h3>
            <div style="font-size:32px;font-weight:700;color:#f59e0b">{{ $pendingComplaints }}</div>
            <small style="color:#6b7280">Pengajuan pending</small>
        </div>
    </a>
    <a href="{{ route('admin.complaints.index', ['status' => 'approved']) }}" style="text-decoration:none">
        <div class="card" style="border-left:4px solid #10b981;cursor:pointer;transition:transform .2s" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
            <h3 style="font-size:13px;color:#6b7280;margin-bottom:8px">✅ Disetujui</h3>
            <div style="font-size:32px;font-weight:700;color:#10b981">{{ $approvedComplaints }}</div>
            <small style="color:#6b7280">Total disetujui</small>
        </div>
    </a>
    <a href="{{ route('admin.complaints.index', ['status' => 'rejected']) }}" style="text-decoration:none">
        <div class="card" style="border-left:4px solid #ef4444;cursor:pointer;transition:transform .2s" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
            <h3 style="font-size:13px;color:#6b7280;margin-bottom:8px">❌ Ditolak</h3>
            <div style="font-size:32px;font-weight:700;color:#ef4444">{{ $rejectedComplaints }}</div>
            <small style="color:#6b7280">Total ditolak</small>
        </div>
    </a>
</div>

<!-- Pengajuan Terbaru yang Pending -->
@if($recentComplaints->count() > 0)
<div class="card" style="margin-bottom:24px">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px">
        <h3>🔔 Pengajuan Baru (Menunggu Persetujuan)</h3>
        <a href="{{ route('admin.complaints.index') }}" class="btn-secondary" style="padding:6px 12px;font-size:13px">Lihat Semua</a>
    </div>
    
    <div style="overflow-x:auto">
        <table style="width:100%;border-collapse:collapse">
            <thead>
                <tr style="border-bottom:2px solid #e5e7eb">
                    <th style="text-align:left;padding:12px 8px;font-size:13px;color:#6b7280">Tanggal</th>
                    <th style="text-align:left;padding:12px 8px;font-size:13px;color:#6b7280">Karyawan</th>
                    <th style="text-align:left;padding:12px 8px;font-size:13px;color:#6b7280">Kategori</th>
                    <th style="text-align:left;padding:12px 8px;font-size:13px;color:#6b7280">Judul</th>
                    <th style="text-align:left;padding:12px 8px;font-size:13px;color:#6b7280">Prioritas</th>
                    <th style="text-align:center;padding:12px 8px;font-size:13px;color:#6b7280">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentComplaints as $complaint)
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
                                'high' => ['bg' => '#fee2e2', 'text' => '#991b1b', 'label' => 'Tinggi'],
                            ];
                            $priority = $priorityBadge[$complaint->priority] ?? $priorityBadge['medium'];
                        @endphp
                        <span style="background:{{ $priority['bg'] }};color:{{ $priority['text'] }};padding:4px 8px;border-radius:4px;font-size:11px;font-weight:600">
                            {{ $priority['label'] }}
                        </span>
                    </td>
                    <td style="padding:12px 8px;text-align:center">
                        <a href="{{ route('admin.complaints.show', $complaint->id) }}" class="btn-primary" style="padding:6px 12px;font-size:12px">
                            👁️ Lihat & Review
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

<div class="card">
    <h3 style="margin-bottom:12px">Selamat Datang di Panel Admin</h3>
    <p style="color:#6b7280">Gunakan menu sidebar untuk mengelola sistem absensi.</p>
</div>
@endsection
