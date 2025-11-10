@extends('admin.layout')

@section('title', 'Tambah Shift')

@section('content')
<div class="page-header">
    <h2>Tambah Shift Baru</h2>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;align-items:start">
    <!-- Form -->
    <div class="card">
        <h3 style="margin:0 0 16px 0;color:#374151">📝 Detail Shift</h3>
        <form method="post" action="{{ route('admin.shifts.store') }}" id="shiftForm">
            @csrf
            
            <label>
                Nama Shift *
                <input type="text" name="name" id="shiftName" value="{{ old('name') }}" required placeholder="Contoh: Pagi, Siang, Malam">
            </label>
            
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
                <label style="margin-top:16px">
                    Jam Mulai *
                    <input type="time" name="start_time" id="startTime" value="{{ old('start_time', '08:00') }}" required onchange="calculateDuration()">
                </label>
                
                <label style="margin-top:16px">
                    Jam Selesai *
                    <input type="time" name="end_time" id="endTime" value="{{ old('end_time', '17:00') }}" required onchange="calculateDuration()">
                </label>
            </div>
            
            <!-- Duration Display -->
            <div id="durationDisplay" style="margin-top:16px;padding:12px;background:#f0f9ff;border-radius:8px;border-left:4px solid #0ea5e9">
                <div style="font-size:13px;color:#0c4a6e;margin-bottom:4px">⏱️ <strong>Durasi Shift:</strong></div>
                <div style="font-size:24px;font-weight:bold;color:#0369a1" id="durationText">8 jam</div>
                <div style="font-size:12px;color:#6b7280;margin-top:4px" id="durationNote"></div>
            </div>
            
            <div style="margin-top:24px;padding-top:16px;border-top:1px solid #e5e7eb">
                <h4 style="margin:0 0 12px 0;font-size:14px;color:#374151">⚡ Quick Preset</h4>
                <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:8px">
                    <button type="button" onclick="setPreset('Shift Pagi', '07:00', '15:00')" class="btn btn-secondary" style="font-size:13px;padding:8px">
                        🌅 Pagi (07:00-15:00)
                    </button>
                    <button type="button" onclick="setPreset('Shift Siang', '15:00', '23:00')" class="btn btn-secondary" style="font-size:13px;padding:8px">
                        ☀️ Siang (15:00-23:00)
                    </button>
                    <button type="button" onclick="setPreset('Shift Malam', '23:00', '07:00')" class="btn btn-secondary" style="font-size:13px;padding:8px">
                        🌙 Malam (23:00-07:00)
                    </button>
                    <button type="button" onclick="setPreset('Shift Reguler', '08:00', '17:00')" class="btn btn-secondary" style="font-size:13px;padding:8px">
                        💼 Reguler (08:00-17:00)
                    </button>
                </div>
            </div>
            
            <div style="margin-top:24px;display:flex;gap:8px">
                <button type="submit" class="btn btn-primary">💾 Simpan Shift</button>
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
                        08:00 - 17:00
                    </div>
                </div>
            </div>
            
            <div style="margin-top:16px;padding:12px;background:white;border-radius:8px;border:2px solid #e5e7eb">
                <div style="font-size:12px;color:#6b7280;margin-bottom:8px">📍 Informasi Shift:</div>
                <div id="shiftInfo" style="font-size:13px;color:#374151">
                    <div style="margin-bottom:4px">• Nama: <strong id="infoName">-</strong></div>
                    <div style="margin-bottom:4px">• Mulai: <strong id="infoStart">-</strong></div>
                    <div style="margin-bottom:4px">• Selesai: <strong id="infoEnd">-</strong></div>
                    <div>• Durasi: <strong id="infoDuration">-</strong></div>
                </div>
            </div>
        </div>
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

function setPreset(name, start, end) {
    document.getElementById('shiftName').value = name;
    document.getElementById('startTime').value = start;
    document.getElementById('endTime').value = end;
    calculateDuration();
}

function updateVisual() {
    const start = document.getElementById('startTime').value;
    const end = document.getElementById('endTime').value;
    const name = document.getElementById('shiftName').value || 'Shift Baru';
    
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
