@extends('admin.layout')

@section('title', 'Assign Users ke Shift')

@section('content')
<div class="page-header">
    <h2>Assign Karyawan ke Shift: {{ $shift->name }}</h2>
</div>

<div style="display:grid;grid-template-columns:2fr 1fr;gap:20px">
    <!-- Form Assign -->
    <div class="card">
        <h3 style="margin:0 0 16px 0;color:#374151">👥 Pilih Karyawan</h3>
        
        <form method="post" action="{{ route('admin.shifts.assign-users', $shift) }}">
            @csrf
            
            <!-- Period (Optional) -->
            <div style="background:#fef3c7;padding:12px;border-radius:8px;margin-bottom:16px;border-left:4px solid #f59e0b">
                <h4 style="margin:0 0 12px 0;font-size:14px;color:#92400e">📅 Periode Shift (Opsional)</h4>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
                    <label style="margin:0">
                        Tanggal Mulai
                        <input type="date" name="start_date" value="{{ date('Y-m-d') }}">
                    </label>
                    <label style="margin:0">
                        Tanggal Selesai
                        <input type="date" name="end_date">
                    </label>
                </div>
                <p style="margin:8px 0 0 0;font-size:12px;color:#92400e">
                    💡 Kosongkan jika shift berlaku permanent (tidak ada batas waktu)
                </p>
            </div>
            
            <!-- Quick Filters -->
            <div style="margin-bottom:16px;display:flex;gap:8px;flex-wrap:wrap">
                <button type="button" onclick="selectAll()" class="btn btn-secondary" style="font-size:13px;padding:6px 12px">
                    ✓ Pilih Semua
                </button>
                <button type="button" onclick="deselectAll()" class="btn btn-secondary" style="font-size:13px;padding:6px 12px">
                    ✗ Batal Pilih
                </button>
                <button type="button" onclick="selectByRole('admin')" class="btn btn-secondary" style="font-size:13px;padding:6px 12px">
                    Admin Only
                </button>
                <button type="button" onclick="selectByRole('employee')" class="btn btn-secondary" style="font-size:13px;padding:6px 12px">
                    Employee Only
                </button>
            </div>
            
            <!-- Search -->
            <input type="text" id="searchUser" placeholder="🔍 Cari nama atau email..." style="margin-bottom:16px" onkeyup="filterUsers()">
            
            <!-- User List -->
            <div style="max-height:400px;overflow-y:auto;border:2px solid #e5e7eb;border-radius:8px;padding:8px">
                @foreach($users as $user)
                <label class="user-item" data-role="{{ $user->roles->pluck('name')->join(',') }}" data-name="{{ strtolower($user->name) }}" data-email="{{ strtolower($user->email) }}" style="display:flex;align-items:center;gap:12px;padding:12px;margin-bottom:4px;background:white;border-radius:8px;border:2px solid {{ in_array($user->id, $assignedUsers) ? '#10b981' : '#e5e7eb' }};cursor:pointer;transition:all 0.2s">
                    <input type="checkbox" name="user_ids[]" value="{{ $user->id }}" 
                           {{ in_array($user->id, $assignedUsers) ? 'checked' : '' }}
                           style="width:20px;height:20px;cursor:pointer"
                           onchange="this.parentElement.style.borderColor = this.checked ? '#10b981' : '#e5e7eb'">
                    
                    <div style="flex:1">
                        <div style="font-weight:600;color:#374151">{{ $user->name }}</div>
                        <div style="font-size:12px;color:#6b7280">{{ $user->email }}</div>
                    </div>
                    
                    <div style="display:flex;gap:4px">
                        @foreach($user->roles as $role)
                        <span style="background:#e0e7ff;color:#4338ca;padding:2px 8px;border-radius:4px;font-size:11px">
                            {{ $role->name }}
                        </span>
                        @endforeach
                    </div>
                    
                    @if(in_array($user->id, $assignedUsers))
                    <span style="background:#d1fae5;color:#059669;padding:4px 8px;border-radius:4px;font-size:11px;font-weight:600">
                        ✓ Sudah Assigned
                    </span>
                    @endif
                </label>
                @endforeach
            </div>
            
            <div style="margin-top:20px;display:flex;gap:8px">
                <button type="submit" class="btn btn-primary">💾 Assign Users</button>
                <a href="{{ route('admin.shifts.edit', $shift) }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
    
    <!-- Shift Info -->
    <div class="card" style="position:sticky;top:20px">
        <h3 style="margin:0 0 16px 0;color:#374151">📋 Info Shift</h3>
        
        <div style="background:#f9fafb;padding:16px;border-radius:8px;margin-bottom:16px">
            <div style="font-size:24px;font-weight:bold;color:#374151;margin-bottom:8px">{{ $shift->name }}</div>
            <div style="font-size:14px;color:#6b7280;margin-bottom:4px">
                🕐 Jam Kerja: <strong>{{ substr($shift->start_time, 0, 5) }} - {{ substr($shift->end_time, 0, 5) }}</strong>
            </div>
            @php
                $start = \Carbon\Carbon::parse($shift->start_time);
                $end = \Carbon\Carbon::parse($shift->end_time);
                if ($end->lessThan($start)) $end->addDay();
                $duration = $start->diffInHours($end, false);
            @endphp
            <div style="font-size:14px;color:#6b7280">
                ⏱️ Durasi: <strong>{{ $duration }} jam</strong>
            </div>
        </div>
        
        <div style="padding:12px;background:#e0f2fe;border-radius:8px;border-left:4px solid #0ea5e9">
            <div style="font-size:32px;font-weight:bold;color:#0369a1;text-align:center">
                {{ count($assignedUsers) }}
            </div>
            <div style="font-size:13px;color:#0c4a6e;text-align:center">
                User sudah assigned
            </div>
        </div>
        
        <div style="margin-top:16px;padding:12px;background:#fef3c7;border-radius:8px">
            <div style="font-size:13px;color:#92400e;margin-bottom:8px">💡 <strong>Tips:</strong></div>
            <ul style="margin:0;padding-left:20px;font-size:12px;color:#92400e">
                <li>Gunakan filter untuk assign by role</li>
                <li>User bisa punya multiple shift</li>
                <li>Set periode untuk shift temporary</li>
                <li>Centang hijau = sudah assigned</li>
            </ul>
        </div>
    </div>
</div>

<script>
function selectAll() {
    document.querySelectorAll('.user-item input[type="checkbox"]').forEach(cb => {
        if (cb.closest('.user-item').style.display !== 'none') {
            cb.checked = true;
            cb.parentElement.style.borderColor = '#10b981';
        }
    });
}

function deselectAll() {
    document.querySelectorAll('.user-item input[type="checkbox"]').forEach(cb => {
        cb.checked = false;
        cb.parentElement.style.borderColor = '#e5e7eb';
    });
}

function selectByRole(role) {
    deselectAll();
    document.querySelectorAll('.user-item').forEach(item => {
        if (item.style.display !== 'none' && item.dataset.role.includes(role)) {
            const cb = item.querySelector('input[type="checkbox"]');
            cb.checked = true;
            item.style.borderColor = '#10b981';
        }
    });
}

function filterUsers() {
    const search = document.getElementById('searchUser').value.toLowerCase();
    document.querySelectorAll('.user-item').forEach(item => {
        const name = item.dataset.name;
        const email = item.dataset.email;
        if (name.includes(search) || email.includes(search)) {
            item.style.display = 'flex';
        } else {
            item.style.display = 'none';
        }
    });
}
</script>
@endsection
