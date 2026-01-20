@extends('admin.layout')

@section('title', 'Detail Pengajuan')

@section('content')
<div class="page-header">
    <h2>Detail Pengajuan Izin/Cuti</h2>
    <a href="{{ route('admin.complaints.index') }}" class="btn-secondary">← Kembali</a>
</div>

<div style="display:grid;grid-template-columns:2fr 1fr;gap:20px">
    <!-- Detail Pengajuan -->
    <div class="card">
        <h3 style="margin-bottom:16px">Informasi Pengajuan</h3>
        
        <div style="display:grid;gap:16px">
            <div>
                <label style="font-size:13px;color:#6b7280;display:block;margin-bottom:4px">Judul</label>
                <div style="font-size:16px;font-weight:600">{{ $complaint->title }}</div>
            </div>
            
            <div>
                <label style="font-size:13px;color:#6b7280;display:block;margin-bottom:4px">Kategori</label>
                @php
                    $categoryBadge = [
                        'cuti' => ['bg' => '#dbeafe', 'text' => '#1e40af', 'icon' => '🏖️'],
                        'sakit' => ['bg' => '#fee2e2', 'text' => '#991b1b', 'icon' => '🤒'],
                        'izin' => ['bg' => '#fef3c7', 'text' => '#92400e', 'icon' => '📝'],
                        'lainnya' => ['bg' => '#f3f4f6', 'text' => '#374151', 'icon' => '💬'],
                    ];
                    $badge = $categoryBadge[$complaint->category] ?? $categoryBadge['lainnya'];
                @endphp
                <span style="background:{{ $badge['bg'] }};color:{{ $badge['text'] }};padding:6px 12px;border-radius:6px;font-size:14px;font-weight:600">
                    {{ $badge['icon'] }} {{ ucfirst($complaint->category) }}
                </span>
            </div>
            
            <div>
                <label style="font-size:13px;color:#6b7280;display:block;margin-bottom:4px">Alasan</label>
                <div style="padding:12px;background:#f9fafb;border-radius:8px;white-space:pre-line">{{ $complaint->description }}</div>
            </div>

            @if($complaint->notes)
            <div>
                <label style="font-size:13px;color:#6b7280;display:block;margin-bottom:4px">
                    📝 Catatan untuk Admin
                    @if($complaint->category == 'sakit')
                        <span style="color:#dc2626;font-weight:600">(Kondisi Medis)</span>
                    @endif
                </label>
                <div style="padding:12px;background:#fef3c7;border-left:4px solid #f59e0b;border-radius:8px;white-space:pre-line">{{ $complaint->notes }}</div>
            </div>
            @endif

            @if($complaint->attachment)
            <div>
                <label style="font-size:13px;color:#6b7280;display:block;margin-bottom:4px">
                    📎 Lampiran
                    @if($complaint->category == 'sakit')
                        <span style="color:#dc2626;font-weight:600">(Surat MC/Dokter)</span>
                    @elseif($complaint->category == 'mendadak')
                        <span style="color:#f59e0b;font-weight:600">(Bukti Pendukung)</span>
                    @endif
                </label>
                <div style="padding:12px;background:#f0f9ff;border:1px solid #0ea5e9;border-radius:8px">
                    @php
                        $attachmentPath = 'public/' . $complaint->attachment;
                        $extension = pathinfo($complaint->attachment, PATHINFO_EXTENSION);
                        $fileName = basename($complaint->attachment);
                        $isImage = in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif']);
                        $fileExists = Storage::exists($attachmentPath);
                    @endphp
                    
                    @if($isImage && $fileExists)
                        <div style="margin-bottom:8px">
                            <img src="{{ asset('storage/' . $complaint->attachment) }}" 
                                 alt="Lampiran" 
                                 style="max-width:100%;height:auto;border-radius:6px;cursor:pointer"
                                 onclick="window.open('{{ asset('storage/' . $complaint->attachment) }}', '_blank')">
                        </div>
                    @endif
                    
                    <div style="display:flex;align-items:center;justify-content:space-between">
                        <div>
                            <strong>{{ $fileName }}</strong><br>
                            @if($fileExists)
                                <small style="color:#6b7280">{{ strtoupper($extension) }} • {{ number_format(Storage::size($attachmentPath) / 1024, 1) }} KB</small>
                            @else
                                <small style="color:#b91c1c">File tidak ditemukan di server</small>
                            @endif
                        </div>
                        @if($fileExists)
                            <a href="{{ asset('storage/' . $complaint->attachment) }}" 
                               target="_blank" 
                               style="background:#0ea5e9;color:white;padding:8px 12px;border-radius:6px;text-decoration:none;font-size:12px">
                                📂 Lihat File
                            </a>
                        @endif
                    </div>
                </div>
            </div>
            @endif
            
            <div>
                <label style="font-size:13px;color:#6b7280;display:block;margin-bottom:4px">Tanggal Pengajuan</label>
                <div>{{ $complaint->created_at->format('d F Y, H:i') }}</div>
            </div>
            
            @if($complaint->response)
                <div>
                    <label style="font-size:13px;color:#6b7280;display:block;margin-bottom:4px">Tanggapan Admin</label>
                    <div style="padding:12px;background:#f0f9ff;border-left:4px solid #0ea5e9;border-radius:8px">
                        {{ $complaint->response }}
                    </div>
                    @if($complaint->responder)
                        <small style="color:#6b7280;margin-top:4px;display:block">
                            Oleh: {{ $complaint->responder->name }} • {{ $complaint->responded_at->format('d F Y, H:i') }}
                        </small>
                    @endif
                </div>
            @endif
        </div>
    </div>
    
    <!-- Sidebar -->
    <div>
        <!-- Status Card -->
        <div class="card" style="margin-bottom:16px">
            <h4 style="margin-bottom:12px;font-size:14px">Status</h4>
            @if($complaint->status == 'pending')
                <div style="background:#fef3c7;color:#92400e;padding:12px;border-radius:8px;text-align:center;font-weight:600">
                    ⏳ Menunggu Persetujuan
                </div>
            @elseif($complaint->status == 'approved')
                <div style="background:#d1fae5;color:#065f46;padding:12px;border-radius:8px;text-align:center;font-weight:600">
                    ✅ Disetujui
                </div>
            @else
                <div style="background:#fee2e2;color:#991b1b;padding:12px;border-radius:8px;text-align:center;font-weight:600">
                    ❌ Ditolak
                </div>
            @endif
        </div>
        
        <!-- Karyawan Info -->
        <div class="card" style="margin-bottom:16px">
            <h4 style="margin-bottom:12px;font-size:14px">Informasi Karyawan</h4>
            <div style="display:grid;gap:8px">
                <div>
                    <small style="color:#6b7280">Nama</small>
                    <div style="font-weight:600">{{ $complaint->user->name }}</div>
                </div>
                <div>
                    <small style="color:#6b7280">Email</small>
                    <div>{{ $complaint->user->email }}</div>
                </div>
                @if($complaint->user->phone)
                    <div>
                        <small style="color:#6b7280">Telepon</small>
                        <div>{{ $complaint->user->phone }}</div>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Actions -->
        @if($complaint->status == 'pending')
            <div class="card">
                <h4 style="margin-bottom:12px;font-size:14px">Tindakan</h4>
                
                <!-- Approve Form -->
                <form method="POST" action="{{ route('admin.complaints.approve', $complaint->id) }}" style="margin-bottom:12px">
                    @csrf
                    <textarea name="response" class="form-input" placeholder="Catatan persetujuan (opsional)" rows="3" style="margin-bottom:8px"></textarea>
                    <button type="submit" class="btn-primary" style="width:100%;background:#10b981" onclick="return confirm('Setujui pengajuan ini?')">
                        ✅ Setujui
                    </button>
                </form>
                
                <!-- Reject Form -->
                <form method="POST" action="{{ route('admin.complaints.reject', $complaint->id) }}">
                    @csrf
                    <textarea name="response" class="form-input" placeholder="Alasan penolakan (wajib)" rows="3" style="margin-bottom:8px" required></textarea>
                    <button type="submit" class="btn-primary" style="width:100%;background:#ef4444" onclick="return confirm('Tolak pengajuan ini?')">
                        ❌ Tolak
                    </button>
                </form>
            </div>
        @endif
    </div>
</div>
@endsection
