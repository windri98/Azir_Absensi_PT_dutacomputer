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

<!-- Statistik Keluhan (Technical Complaints) -->
<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-bottom:24px;">
    <a href="{{ route('complaints.technician', ['status' => 'pending']) }}" style="text-decoration:none">
        <div class="card" style="border-left:4px solid #ef4444;cursor:pointer;transition:transform .2s" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
            <h3 style="font-size:13px;color:#6b7280;margin-bottom:8px">💬 Keluhan Pending</h3>
            <div style="font-size:32px;font-weight:700;color:#ef4444">{{ $pendingComplaints }}</div>
            <small style="color:#6b7280">Keluhan perlu ditangani</small>
        </div>
    </a>
    <a href="{{ route('complaints.technician', ['status' => 'resolved']) }}" style="text-decoration:none">
        <div class="card" style="border-left:4px solid #10b981;cursor:pointer;transition:transform .2s" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
            <h3 style="font-size:13px;color:#6b7280;margin-bottom:8px">✅ Keluhan Resolved</h3>
            <div style="font-size:32px;font-weight:700;color:#10b981">{{ $resolvedComplaints }}</div>
            <small style="color:#6b7280">Keluhan terselesaikan</small>
        </div>
    </a>
    <a href="{{ route('complaints.technician', ['status' => 'closed']) }}" style="text-decoration:none">
        <div class="card" style="border-left:4px solid #6b7280;cursor:pointer;transition:transform .2s" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
            <h3 style="font-size:13px;color:#6b7280;margin-bottom:8px">🔒 Keluhan Closed</h3>
            <div style="font-size:32px;font-weight:700;color:#6b7280">{{ $closedComplaints }}</div>
            <small style="color:#6b7280">Keluhan ditutup</small>
        </div>
    </a>
</div>

<!-- Statistik Izin Kerja -->
<div style="display:grid;grid-template-columns:repeat(2,1fr);gap:16px;margin-bottom:24px;">
    <div class="card">
        <h3 style="font-size:13px;color:#6b7280;margin-bottom:8px">📋 Izin Kerja Hari Ini</h3>
        <div style="font-size:32px;font-weight:700;color:#3730a3">{{ $workLeaveToday }}</div>
        <small style="color:#6b7280">Karyawan izin hari ini</small>
    </div>
    <div class="card">
        <h3 style="font-size:13px;color:#6b7280;margin-bottom:8px">📊 Izin Kerja Bulan Ini</h3>
        <div style="font-size:32px;font-weight:700;color:#7c3aed">{{ $workLeaveThisMonth }}</div>
        <small style="color:#6b7280">Total izin bulan {{ now()->translatedFormat('F') }}</small>
    </div>
</div>

<!-- Quick Access Menu -->
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(250px,1fr));gap:16px;margin-bottom:24px;">
    <a href="{{ route('reports.users') }}" style="text-decoration:none">
        <div class="card" style="border-left:4px solid #1ec7e6;cursor:pointer;transition:transform .2s;padding:20px" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
            <div style="display:flex;align-items:center;gap:12px">
                <div style="font-size:32px">👥</div>
                <div>
                    <h3 style="font-size:16px;color:#1f2937;margin-bottom:4px">Laporan Per User</h3>
                    <p style="font-size:13px;color:#6b7280">Detail absensi setiap karyawan</p>
                </div>
            </div>
        </div>
    </a>
    <a href="{{ route('admin.users.index') }}" style="text-decoration:none">
        <div class="card" style="border-left:4px solid #10b981;cursor:pointer;transition:transform .2s;padding:20px" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
            <div style="display:flex;align-items:center;gap:12px">
                <div style="font-size:32px">👤</div>
                <div>
                    <h3 style="font-size:16px;color:#1f2937;margin-bottom:4px">Kelola User</h3>
                    <p style="font-size:13px;color:#6b7280">Manajemen pengguna sistem</p>
                </div>
            </div>
        </div>
    </a>
    <a href="{{ route('complaints.technician') }}" style="text-decoration:none">
        <div class="card" style="border-left:4px solid #ef4444;cursor:pointer;transition:transform .2s;padding:20px" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
            <div style="display:flex;align-items:center;gap:12px">
                <div style="font-size:32px">💬</div>
                <div>
                    <h3 style="font-size:16px;color:#1f2937;margin-bottom:4px">Kelola Keluhan</h3>
                    <p style="font-size:13px;color:#6b7280">Review & respond keluhan karyawan</p>
                </div>
            </div>
        </div>
    </a>
    <a href="{{ route('admin.complaints.index') }}" style="text-decoration:none">
        <div class="card" style="border-left:4px solid #f59e0b;cursor:pointer;transition:transform .2s;padding:20px" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
            <div style="display:flex;align-items:center;gap:12px">
                <div style="font-size:32px">📋</div>
                <div>
                    <h3 style="font-size:16px;color:#1f2937;margin-bottom:4px">Kelola Pengajuan</h3>
                    <p style="font-size:13px;color:#6b7280">Review izin dan cuti</p>
                </div>
            </div>
        </div>
    </a>
    <a href="{{ route('admin.work-leave.index') }}" style="text-decoration:none">
        <div class="card" style="border-left:4px solid #3730a3;cursor:pointer;transition:transform .2s;padding:20px" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
            <div style="display:flex;align-items:center;gap:12px">
                <div style="font-size:32px">🏢</div>
                <div>
                    <h3 style="font-size:16px;color:#1f2937;margin-bottom:4px">Kelola Izin Kerja</h3>
                    <p style="font-size:13px;color:#6b7280">Review & approve izin kerja</p>
                </div>
            </div>
        </div>
    </a>
</div>

<!-- Statistik Pengajuan Izin/Cuti -->
<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-bottom:24px;">
    <a href="{{ route('admin.complaints.index', ['status' => 'pending']) }}" style="text-decoration:none">
        <div class="card" style="border-left:4px solid #f59e0b;cursor:pointer;transition:transform .2s" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
            <h3 style="font-size:13px;color:#6b7280;margin-bottom:8px">⏳ Menunggu Persetujuan</h3>
            <div style="font-size:32px;font-weight:700;color:#f59e0b">{{ $pendingLeaveRequests }}</div>
            <small style="color:#6b7280">Pengajuan pending</small>
        </div>
    </a>
    <a href="{{ route('admin.complaints.index', ['status' => 'approved']) }}" style="text-decoration:none">
        <div class="card" style="border-left:4px solid #10b981;cursor:pointer;transition:transform .2s" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
            <h3 style="font-size:13px;color:#6b7280;margin-bottom:8px">✅ Disetujui</h3>
            <div style="font-size:32px;font-weight:700;color:#10b981">{{ $approvedLeaveRequests }}</div>
            <small style="color:#6b7280">Total disetujui</small>
        </div>
    </a>
    <a href="{{ route('admin.complaints.index', ['status' => 'rejected']) }}" style="text-decoration:none">
        <div class="card" style="border-left:4px solid #ef4444;cursor:pointer;transition:transform .2s" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
            <h3 style="font-size:13px;color:#6b7280;margin-bottom:8px">❌ Ditolak</h3>
            <div style="font-size:32px;font-weight:700;color:#ef4444">{{ $rejectedLeaveRequests }}</div>
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
                                    <th style="text-align:left;padding:12px 8px;font-size:13px;color:#6b7280">Jenis Kelamin</th>
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
                        {{ $complaint->user->gender ?? '-' }}
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
    
    <!-- Pagination for Complaints -->
    @if($recentComplaints->hasPages())
    <div style="margin-top:16px;display:flex;justify-content:center">
        {{ $recentComplaints->links() }}
    </div>
    @endif
</div>
@endif

<!-- Pengajuan Izin Kerja Terbaru -->
@if($recentWorkLeave->count() > 0)
<div class="card" style="margin-bottom:24px">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px">
        <h3>🏢 Pengajuan Izin Kerja Terbaru</h3>
        <a href="{{ route('admin.work-leave.index') }}" class="btn-secondary" style="padding:6px 12px;font-size:13px">Lihat Semua</a>
    </div>
    
    <div style="overflow-x:auto">
        <table style="width:100%;border-collapse:collapse;font-size:14px">
            <thead style="background:#f8fafc">
                <tr>
                    <th style="padding:12px 8px;text-align:left;border-bottom:1px solid #e5e7eb">Karyawan</th>
                    <th style="padding:12px 8px;text-align:left;border-bottom:1px solid #e5e7eb">Tanggal</th>
                    <th style="padding:12px 8px;text-align:left;border-bottom:1px solid #e5e7eb">Keterangan</th>
                    <th style="padding:12px 8px;text-align:left;border-bottom:1px solid #e5e7eb">Dokumen</th>
                    <th style="padding:12px 8px;text-align:center;border-bottom:1px solid #e5e7eb">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentWorkLeave as $workLeave)
                <tr style="border-bottom:1px solid #f3f4f6">
                    <td style="padding:12px 8px">
                        <div style="display:flex;align-items:center;gap:8px">
                            <div style="width:32px;height:32px;background:#e0e7ff;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:14px;font-weight:600;color:#3730a3">
                                {{ strtoupper(substr($workLeave->user->name, 0, 1)) }}
                            </div>
                            <div>
                                <div style="font-weight:600">{{ $workLeave->user->name }}</div>
                                <div style="font-size:12px;color:#6b7280">{{ $workLeave->user->employee_id }}</div>
                            </div>
                        </div>
                    </td>
                    <td style="padding:12px 8px;font-size:13px">
                        {{ \Carbon\Carbon::parse($workLeave->date)->translatedFormat('d F Y') }}
                    </td>
                    <td style="padding:12px 8px;font-size:13px">
                        {{ Str::limit($workLeave->notes, 50) }}
                    </td>
                    <td style="padding:12px 8px;font-size:13px">
                        @if($workLeave->hasDocument())
                            <span style="background:#dcfce7;color:#16a34a;padding:4px 8px;border-radius:4px;font-size:11px;font-weight:600">
                                📎 Ada Dokumen
                            </span>
                        @else
                            <span style="background:#fee2e2;color:#dc2626;padding:4px 8px;border-radius:4px;font-size:11px;font-weight:600">
                                📄 Tidak Ada
                            </span>
                        @endif
                    </td>
                    <td style="padding:12px 8px;text-align:center">
                        <a href="{{ route('admin.work-leave.detail', $workLeave->id) }}" class="btn-primary" style="padding:6px 12px;font-size:12px">
                            👁️ Review
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <!-- Pagination for Work Leave -->
    @if($recentWorkLeave->hasPages())
    <div style="margin-top:16px;display:flex;justify-content:center">
        {{ $recentWorkLeave->links() }}
    </div>
    @endif
</div>
@endif

<div class="card">
    <h3 style="margin-bottom:12px">Selamat Datang di Panel Admin</h3>
    <p style="color:#6b7280">Gunakan menu sidebar untuk mengelola sistem absensi.</p>
</div>
@endsection
