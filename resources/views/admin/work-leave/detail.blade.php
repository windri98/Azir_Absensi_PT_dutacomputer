@extends('admin.layout')

@section('title', 'Detail Izin Kerja')

@section('content')
<div class="page-header">
    <div style="display:flex;align-items:center;gap:16px">
        <a href="{{ route('admin.work-leave.index') }}" class="btn-secondary" style="padding:8px 12px">
            ← Kembali
        </a>
        <div>
            <h2>🏢 Detail Izin Kerja</h2>
            <p style="color:#6b7280">Review dan kelola pengajuan izin kerja karyawan</p>
        </div>
    </div>
</div>

<div style="display:grid;grid-template-columns:2fr 1fr;gap:24px">
    <!-- Detail Pengajuan -->
    <div class="card">
        <h3 style="margin-bottom:20px;display:flex;align-items:center;gap:8px">
            📋 Detail Pengajuan
        </h3>
        
        <!-- Info Karyawan -->
        <div style="background:#f8fafc;border-radius:8px;padding:16px;margin-bottom:20px">
            <div style="display:flex;align-items:center;gap:16px">
                <div style="width:60px;height:60px;background:#e0e7ff;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:24px;font-weight:600;color:#3730a3">
                    {{ strtoupper(substr($attendance->user->name, 0, 1)) }}
                </div>
                <div style="flex:1">
                    <h4 style="font-size:18px;margin-bottom:4px">{{ $attendance->user->name }}</h4>
                    <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:8px;font-size:13px;color:#6b7280">
                        <div><strong>Employee ID:</strong> {{ $attendance->user->employee_id }}</div>
                        <div><strong>Role:</strong> {{ $attendance->user->roles->first()->name ?? 'Employee' }}</div>
                        <div><strong>Email:</strong> {{ $attendance->user->email }}</div>
                        <div><strong>Phone:</strong> {{ $attendance->user->phone ?? '-' }}</div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Detail Izin -->
        <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:20px;margin-bottom:20px">
            <div>
                <label style="display:block;font-weight:600;color:#374151;margin-bottom:6px">Tanggal Izin</label>
                <div style="padding:12px;background:#f9fafb;border:1px solid #e5e7eb;border-radius:6px">
                    <div style="font-size:16px;font-weight:600">{{ \Carbon\Carbon::parse($attendance->date)->translatedFormat('d F Y') }}</div>
                    <div style="font-size:13px;color:#6b7280">{{ \Carbon\Carbon::parse($attendance->date)->translatedFormat('l') }}</div>
                </div>
            </div>
            <div>
                <label style="display:block;font-weight:600;color:#374151;margin-bottom:6px">Status</label>
                <div style="padding:12px;background:#f9fafb;border:1px solid #e5e7eb;border-radius:6px">
                    <span style="background:#e0e7ff;color:#3730a3;padding:6px 12px;border-radius:4px;font-size:14px;font-weight:600">
                        🏢 Izin Kerja
                    </span>
                </div>
            </div>
        </div>
        
        <!-- Keterangan -->
        <div style="margin-bottom:20px">
            <label style="display:block;font-weight:600;color:#374151;margin-bottom:6px">Keterangan/Alasan</label>
            <div style="padding:16px;background:#f9fafb;border:1px solid #e5e7eb;border-radius:6px;line-height:1.6">
                {{ $attendance->notes }}
            </div>
        </div>
        
        <!-- Waktu Pengajuan -->
        <div style="margin-bottom:20px">
            <label style="display:block;font-weight:600;color:#374151;margin-bottom:6px">Waktu Pengajuan</label>
            <div style="padding:12px;background:#f9fafb;border:1px solid #e5e7eb;border-radius:6px">
                <div style="font-size:14px">{{ $attendance->created_at->translatedFormat('d F Y, H:i') }} WIB</div>
                <div style="font-size:12px;color:#6b7280">{{ $attendance->created_at->diffForHumans() }}</div>
            </div>
        </div>
    </div>
    
    <!-- Sidebar Actions -->
    <div>
        <!-- Dokumen Lampiran -->
        <div class="card" style="margin-bottom:20px">
            <h4 style="margin-bottom:16px;display:flex;align-items:center;gap:8px">
                📎 Dokumen Lampiran
            </h4>
            
            @if($attendance->hasDocument())
                <div style="text-align:center">
                    <div style="padding:20px;background:#dcfce7;border:2px dashed #16a34a;border-radius:8px;margin-bottom:16px">
                        <div style="font-size:32px;margin-bottom:8px">📄</div>
                        <div style="font-weight:600;color:#16a34a;margin-bottom:4px">
                            {{ $attendance->getDocumentTypeLabel() }}
                        </div>
                        <div style="font-size:12px;color:#6b7280">
                            {{ $attendance->document_filename }}
                        </div>
                        @if($attendance->document_uploaded_at)
                        <div style="font-size:11px;color:#6b7280;margin-top:4px">
                            Upload: {{ \Carbon\Carbon::parse($attendance->document_uploaded_at)->translatedFormat('d M Y, H:i') }}
                        </div>
                        @endif
                    </div>
                    
                    <div style="display:flex;gap:8px">
                        <a href="{{ route('attendance.document.view', $attendance) }}" 
                           target="_blank"
                           class="btn-secondary" 
                           style="flex:1;text-align:center;padding:10px">
                            👁️ Lihat Dokumen
                        </a>
                        <a href="{{ route('attendance.document.download', $attendance) }}" 
                           class="btn-primary" 
                           style="flex:1;text-align:center;padding:10px">
                            💾 Download
                        </a>
                    </div>
                </div>
            @else
                <div style="text-align:center;padding:20px">
                    <div style="padding:20px;background:#fee2e2;border:2px dashed #dc2626;border-radius:8px">
                        <div style="font-size:32px;margin-bottom:8px">❌</div>
                        <div style="font-weight:600;color:#dc2626;margin-bottom:4px">
                            Tidak Ada Dokumen
                        </div>
                        <div style="font-size:12px;color:#6b7280">
                            Karyawan tidak melampirkan dokumen pendukung
                        </div>
                    </div>
                </div>
            @endif
        </div>
        
        <!-- Status Approval -->
        @if($attendance->approval_status)
        <div class="card" style="margin-bottom:20px">
            <h4 style="margin-bottom:16px">📋 Status Persetujuan</h4>
            
            @if($attendance->approval_status === 'approved')
            <div style="padding:12px;background:#dcfce7;border:1px solid #16a34a;border-radius:6px;text-align:center">
                <div style="font-size:18px;margin-bottom:8px">✅</div>
                <div style="font-weight:600;color:#16a34a;margin-bottom:4px">Disetujui</div>
                @if($attendance->admin_approved_at)
                <div style="font-size:12px;color:#6b7280">
                    {{ $attendance->admin_approved_at->translatedFormat('d F Y, H:i') }} WIB
                </div>
                @endif
                @if($attendance->approvedBy)
                <div style="font-size:12px;color:#6b7280">
                    oleh {{ $attendance->approvedBy->name }}
                </div>
                @endif
            </div>
            @elseif($attendance->approval_status === 'rejected')
            <div style="padding:12px;background:#fef2f2;border:1px solid #dc2626;border-radius:6px;text-align:center">
                <div style="font-size:18px;margin-bottom:8px">❌</div>
                <div style="font-weight:600;color:#dc2626;margin-bottom:4px">Ditolak</div>
                @if($attendance->admin_rejected_at)
                <div style="font-size:12px;color:#6b7280">
                    {{ $attendance->admin_rejected_at->translatedFormat('d F Y, H:i') }} WIB
                </div>
                @endif
                @if($attendance->approvedBy)
                <div style="font-size:12px;color:#6b7280">
                    oleh {{ $attendance->approvedBy->name }}
                </div>
                @endif
            </div>
            @endif
        </div>
        @endif

        <!-- Actions -->
        @if(!$attendance->approval_status || $attendance->approval_status === 'pending')
        <div class="card">
            <h4 style="margin-bottom:16px">⚡ Quick Actions</h4>
            
            <div style="display:flex;flex-direction:column;gap:12px">
                <button onclick="approveRequest()" class="btn-primary" style="width:100%;padding:12px;display:flex;align-items:center;justify-content:center;gap:8px">
                    ✅ Setujui Izin
                </button>
                
                <button onclick="rejectRequest()" class="btn-danger" style="width:100%;padding:12px;display:flex;align-items:center;justify-content:center;gap:8px;background:#dc2626;color:white">
                    ❌ Tolak Izin
                </button>
                
                <hr style="margin:8px 0">
                
                <a href="{{ route('admin.users.attendance', $attendance->user->id) }}" 
                   class="btn-secondary" 
                   style="width:100%;padding:12px;text-align:center;display:block">
                    📊 Lihat Riwayat Absensi
                </a>
                
                <a href="mailto:{{ $attendance->user->email }}" 
                   class="btn-secondary" 
                   style="width:100%;padding:12px;text-align:center;display:block">
                    ✉️ Kirim Email
                </a>
            </div>
        </div>
        @else
        <div class="card">
            <h4 style="margin-bottom:16px">📋 Status Final</h4>
            <div style="font-size:14px;color:#6b7280;text-align:center;padding:20px">
                Pengajuan izin kerja ini sudah diproses.
            </div>
        </div>
        @endif
        
        <!-- Info Timeline -->
        <div class="card" style="margin-top:20px">
            <h4 style="margin-bottom:16px">⏱️ Timeline</h4>
            <div style="font-size:12px;line-height:1.6">
                <div style="display:flex;justify-content:space-between;margin-bottom:8px">
                    <span style="color:#6b7280">Pengajuan dibuat:</span>
                    <span>{{ $attendance->created_at->translatedFormat('d M, H:i') }}</span>
                </div>
                @if($attendance->document_uploaded_at)
                <div style="display:flex;justify-content:space-between;margin-bottom:8px">
                    <span style="color:#6b7280">Dokumen diupload:</span>
                    <span>{{ \Carbon\Carbon::parse($attendance->document_uploaded_at)->translatedFormat('d M, H:i') }}</span>
                </div>
                @endif
                <div style="display:flex;justify-content:space-between">
                    <span style="color:#6b7280">Last update:</span>
                    <span>{{ $attendance->updated_at->translatedFormat('d M, H:i') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function approveRequest() {
    if (confirm('Apakah Anda yakin ingin menyetujui pengajuan izin kerja ini?')) {
        fetch('{{ route("admin.work-leave.approve", $attendance->id) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => {
            console.log('Response status:', response.status);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data);
            if (data.success) {
                alert(data.message);
                location.reload();
            } else {
                alert('Error: ' + (data.message || 'Terjadi kesalahan yang tidak diketahui'));
            }
        })
        .catch(error => {
            console.error('Fetch error:', error);
            alert('Terjadi kesalahan saat memproses permintaan. Detail: ' + error.message);
        });
    }
}

function rejectRequest() {
    if (confirm('Apakah Anda yakin ingin menolak pengajuan izin kerja ini?')) {
        fetch('{{ route("admin.work-leave.reject", $attendance->id) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => {
            console.log('Response status:', response.status);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data);
            if (data.success) {
                alert(data.message);
                location.reload();
            } else {
                alert('Error: ' + (data.message || 'Terjadi kesalahan yang tidak diketahui'));
            }
        })
        .catch(error => {
            console.error('Fetch error:', error);
            alert('Terjadi kesalahan saat memproses permintaan. Detail: ' + error.message);
        });
    }
}
</script>
@endsection
