@extends('admin.layout')

@section('title', 'Edit Shift')

@section('content')
<div class="page-header">
    <h2>Edit Shift: {{ $shift->name }}</h2>
    <div class="actions">
        <a href="{{ route('admin.shifts.assign-form', $shift) }}" class="btn btn-success">👥 Assign Karyawan</a>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;align-items:start">
    <!-- Form -->
    <div class="card">
        <h3 style="margin:0 0 16px 0;color:#374151">📝 Detail Shift</h3>
        <form method="post" action="{{ route('admin.shifts.update',$shift) }}" id="shiftForm">
            @csrf
            @method('PUT')
            
            <label>
                Nama Shift *
                <input type="text" name="name" id="shiftName" value="{{ old('name',$shift->name) }}" required>
            </label>
            
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
                <label style="margin-top:16px">
                    Jam Mulai *
                    <input type="time" name="start_time" id="startTime" value="{{ old('start_time',$shift->start_time) }}" required onchange="calculateDuration()">
                </label>
                
                <label style="margin-top:16px">
                    Jam Selesai *
                    <input type="time" name="end_time" id="endTime" value="{{ old('end_time',$shift->end_time) }}" required onchange="calculateDuration()">
                </label>
            </div>
            
            <!-- Duration Display -->
            <div id="durationDisplay" style="margin-top:16px;padding:12px;background:#f0f9ff;border-radius:8px;border-left:4px solid #0ea5e9">
                <div style="font-size:13px;color:#0c4a6e;margin-bottom:4px">⏱️ <strong>Durasi Shift:</strong></div>
                <div style="font-size:24px;font-weight:bold;color:#0369a1" id="durationText">8 jam</div>
                <div style="font-size:12px;color:#6b7280;margin-top:4px" id="durationNote"></div>
            </div>
            
            <!-- Users assigned -->
            <div style="margin-top:16px;padding:12px;background:#fef3c7;border-radius:8px;border-left:4px solid #f59e0b">
                <div style="font-size:13px;color:#92400e">
                    👥 <strong>{{ $shift->users()->count() }} karyawan</strong> menggunakan shift ini
                </div>
            </div>
            
            <div style="margin-top:24px;display:flex;gap:8px">
                <button type="submit" class="btn btn-primary">💾 Update Shift</button>
                <a href="{{ route('admin.shifts.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
    
    <!-- Visual Timeline -->
    <div class="card" style="position:sticky;top:20px">
        <h3 style="margin:0 0 16px 0;color:#374151">📊 Visual Timeline</h3>
        
        <div style="background:#f9fafb;padding:20px;border-radius:8px">
            <div style="position:relative;height:300px;background:linear-gradient(to bottom, #1e3a8a 0%, #3b82f6 25%, #fbbf24 50%, #f59e0b 75%, #1e3a8a 100%);border-radius:12px;overflow:hidden">
                <!-- Time markers -->
                <div style="position:absolute;top:0;left:0;right:0;bottom:0;display:flex;flex-direction:column;justify-content:space-between;padding:8px 12px;color:white;font-size:11px;font-weight:600">
                    <div>00:00 🌙</div>
                    <div>06:00 🌅</div>
                    <div>12:00 ☀️</div>
                    <div>18:00 🌆</div>
                    <div>24:00 🌙</div>
                </div>
                
                <!-- Shift indicator -->
                <div id="shiftIndicator" style="position:absolute;left:16px;right:16px;background:rgba(16,185,129,0.8);border:3px solid white;border-radius:8px;box-shadow:0 4px 12px rgba(0,0,0,0.3);transition:all 0.3s">
                    <div style="padding:8px;text-align:center;font-size:13px;font-weight:bold;color:white" id="shiftLabel">
                        {{ $shift->start_time }} - {{ $shift->end_time }}
                    </div>
                </div>
            </div>
            
            <div style="margin-top:16px;padding:12px;background:white;border-radius:8px;border:2px solid #e5e7eb">
                <div style="font-size:12px;color:#6b7280;margin-bottom:8px">📍 Informasi Shift:</div>
                <div id="shiftInfo" style="font-size:13px;color:#374151">
                    <div style="margin-bottom:4px">• Nama: <strong id="infoName">{{ $shift->name }}</strong></div>
                    <div style="margin-bottom:4px">• Mulai: <strong id="infoStart">{{ $shift->start_time }}</strong></div>
                    <div style="margin-bottom:4px">• Selesai: <strong id="infoEnd">{{ $shift->end_time }}</strong></div>
                    <div>• Durasi: <strong id="infoDuration">-</strong></div>
                </div>
            </div>
        </div>
        
        @if($shift->users()->count() > 0)
        <div style="margin-top:16px;padding:16px;background:#f9fafb;border-radius:8px">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px">
                <h4 style="margin:0;font-size:14px;color:#374151">👥 Karyawan pada Shift Ini:</h4>
                <a href="{{ route('admin.shifts.assign-form', $shift) }}" class="btn btn-secondary" style="font-size:12px;padding:4px 10px">+ Tambah</a>
            </div>
            <div style="max-height:200px;overflow-y:auto">
                @foreach($shift->users as $user)
                <div style="padding:8px;margin-bottom:4px;background:white;border-radius:6px;font-size:13px;display:flex;justify-content:space-between;align-items:center">
                    <div style="flex:1">
                        <div style="font-weight:600">{{ $user->name }}</div>
                        <div style="font-size:11px;color:#6b7280">{{ $user->email }}</div>
                    </div>
                    <form method="post" action="{{ route('admin.shifts.remove-user', [$shift, $user]) }}" style="display:inline" onsubmit="return confirm('Hapus {{ $user->name }} dari shift ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" style="background:#fee2e2;color:#dc2626;border:none;padding:4px 8px;border-radius:4px;font-size:11px;cursor:pointer">Hapus</button>
                    </form>
                </div>
                @endforeach
            </div>
        </div>
        @else
        <div style="margin-top:16px;padding:16px;background:#f9fafb;border-radius:8px;text-align:center">
            <div style="font-size:32px;margin-bottom:8px">👤</div>
            <div style="font-size:13px;color:#6b7280;margin-bottom:12px">Belum ada karyawan di shift ini</div>
            <a href="{{ route('admin.shifts.assign-form', $shift) }}" class="btn btn-primary" style="font-size:13px;padding:8px 16px">👥 Assign Karyawan</a>
        </div>
        @endif
    </div>
</div>

<script>
function calculateDuration() {
    const start = document.getElementById('startTime').value;
    const end = document.getElementById('endTime').value;
    
    if (!start || !end) return;
    
    const startTime = new Date('2000-01-01 ' + start);
    let endTime = new Date('2000-01-01 ' + end);
    
    // Handle overnight shift
    if (endTime <= startTime) {
        endTime = new Date('2000-01-02 ' + end);
    }
    
    const diff = (endTime - startTime) / (1000 * 60 * 60);
    
    document.getElementById('durationText').textContent = diff.toFixed(1) + ' jam';
    
    if (diff > 12) {
        document.getElementById('durationNote').innerHTML = '⚠️ Shift lebih dari 12 jam';
        document.getElementById('durationNote').style.color = '#dc2626';
    } else if (diff < 4) {
        document.getElementById('durationNote').innerHTML = '⚠️ Shift kurang dari 4 jam';
        document.getElementById('durationNote').style.color = '#f59e0b';
    } else {
        document.getElementById('durationNote').innerHTML = '✓ Durasi shift normal';
        document.getElementById('durationNote').style.color = '#059669';
    }
    
    updateVisual();
}

function updateVisual() {
    const start = document.getElementById('startTime').value;
    const end = document.getElementById('endTime').value;
    const name = document.getElementById('shiftName').value || 'Shift';
    
    if (!start || !end) return;
    
    // Calculate position (0-24 hours = 0-100%)
    const startHour = parseInt(start.split(':')[0]) + parseInt(start.split(':')[1]) / 60;
    let endHour = parseInt(end.split(':')[0]) + parseInt(end.split(':')[1]) / 60;
    
    if (endHour <= startHour) {
        endHour += 24;
    }
    
    const startPercent = (startHour / 24) * 100;
    const endPercent = Math.min((endHour / 24) * 100, 100);
    
    const indicator = document.getElementById('shiftIndicator');
    indicator.style.top = startPercent + '%';
    indicator.style.height = (endPercent - startPercent) + '%';
    
    document.getElementById('shiftLabel').textContent = start + ' - ' + end;
    document.getElementById('infoName').textContent = name;
    document.getElementById('infoStart').textContent = start;
    document.getElementById('infoEnd').textContent = end;
    
    const startTime = new Date('2000-01-01 ' + start);
    let endTime = new Date('2000-01-01 ' + end);
    if (endTime <= startTime) endTime = new Date('2000-01-02 ' + end);
    const duration = (endTime - startTime) / (1000 * 60 * 60);
    document.getElementById('infoDuration').textContent = duration.toFixed(1) + ' jam';
}

// Initialize on load
document.addEventListener('DOMContentLoaded', function() {
    calculateDuration();
    document.getElementById('shiftName').addEventListener('input', updateVisual);
});
</script>
@endsection
