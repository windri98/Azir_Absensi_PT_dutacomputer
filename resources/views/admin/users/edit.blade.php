@extends('admin.layout')

@section('title', 'Edit Pengguna')

@section('content')
<div class="page-header">
    <h2>Edit Pengguna: {{ $user->name }}</h2>
</div>

<!-- Statistik Absensi Bulan Ini -->
<div class="card" style="max-width:700px;margin-bottom:20px;background:#f9fafb">
    <h3 style="margin:0 0 16px 0;color:#374151;font-size:16px">📊 Statistik Absensi Bulan Ini ({{ date('F Y') }})</h3>
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:12px">
        <div style="background:white;padding:16px;border-radius:8px;text-align:center;border:2px solid #d1fae5">
            <div style="font-size:24px;font-weight:bold;color:#059669">{{ $attendanceStats['total_hadir'] }}</div>
            <div style="font-size:13px;color:#6b7280;margin-top:4px">Hari Hadir</div>
        </div>
        <div style="background:white;padding:16px;border-radius:8px;text-align:center;border:2px solid #fee2e2">
            <div style="font-size:24px;font-weight:bold;color:#dc2626">{{ $attendanceStats['total_terlambat'] }}</div>
            <div style="font-size:13px;color:#6b7280;margin-top:4px">Terlambat</div>
        </div>
        <div style="background:white;padding:16px;border-radius:8px;text-align:center;border:2px solid #dbeafe">
            <div style="font-size:24px;font-weight:bold;color:#2563eb">{{ number_format($attendanceStats['total_jam_kerja'], 1) }}</div>
            <div style="font-size:13px;color:#6b7280;margin-top:4px">Total Jam</div>
        </div>
    </div>
</div>

<div class="card" style="max-width:700px">
    <form method="post" action="{{ route('admin.users.update',$user) }}">
        @csrf
        @method('PUT')
        
        <h3 style="margin-bottom:16px;color:#374151">Informasi Dasar</h3>
        <label>ID Card / Employee ID *<input type="text" name="employee_id" value="{{ old('employee_id',$user->employee_id) }}" placeholder="EMP001" required></label>
        <label>Nama *<input type="text" name="name" value="{{ old('name',$user->name) }}" required></label>
        <label>Email *<input type="email" name="email" value="{{ old('email',$user->email) }}" required></label>
        <label>Password (kosongkan jika tidak diubah)<input type="password" name="password"></label>
        
        <hr style="margin:24px 0;border:none;border-top:1px solid #e5e7eb">
        
        <h3 style="margin-bottom:16px;color:#374151">Informasi Pribadi</h3>
        <label>Telepon<input type="text" name="phone" value="{{ old('phone',$user->phone) }}" placeholder="08123456789"></label>
        <label>Alamat<textarea name="address" rows="3" placeholder="Alamat lengkap">{{ old('address',$user->address) }}</textarea></label>
        <label>Tanggal Lahir<input type="date" name="birth_date" value="{{ old('birth_date',$user->birth_date) }}"></label>
        
        <hr style="margin:24px 0;border:none;border-top:1px solid #e5e7eb">
        
        <h3 style="margin-bottom:16px;color:#374151">Role & Shift</h3>
        <label>Roles (tahan Ctrl untuk pilih multiple)
            <select name="roles[]" multiple size="5">
                @foreach($roles as $role)
                    <option value="{{ $role->id }}" {{ in_array($role->id,$assigned) ? 'selected' : '' }}>
                        {{ $role->name }} - {{ $role->description }}
                    </option>
                @endforeach
            </select>
        </label>
        
        <label>Shift (tahan Ctrl untuk pilih multiple)
            <select name="shifts[]" multiple size="5">
                @foreach($shifts as $shift)
                    <option value="{{ $shift->id }}" {{ in_array($shift->id,$assignedShifts) ? 'selected' : '' }}>
                        {{ $shift->name }} ({{ $shift->start_time }} - {{ $shift->end_time }})
                    </option>
                @endforeach
            </select>
        </label>
        
        <hr style="margin:24px 0;border:none;border-top:1px solid #e5e7eb">
        
        <h3 style="margin-bottom:16px;color:#374151">Jatah Cuti Tahunan</h3>
        
        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-bottom:20px">
            <label>Cuti Tahunan (hari)
                <input type="number" name="annual_leave_quota" value="{{ $user->annual_leave_quota }}" min="0" max="30" required>
            </label>
            
            <label>Cuti Sakit (hari)
                <input type="number" name="sick_leave_quota" value="{{ $user->sick_leave_quota }}" min="0" max="30" required>
            </label>
            
            <label>Cuti Khusus (hari)
                <input type="number" name="special_leave_quota" value="{{ $user->special_leave_quota }}" min="0" max="30" required>
            </label>
        </div>
        
        <hr style="margin:24px 0;border:none;border-top:1px solid #e5e7eb">
        
        <div style="background:#f3f4f6;padding:12px;border-radius:8px;margin-bottom:20px">
            <p style="margin:0;font-size:13px;color:#6b7280">
                <strong>Terdaftar:</strong> {{ $user->created_at->format('d/m/Y H:i') }}<br>
                <strong>Terakhir Update:</strong> {{ $user->updated_at->format('d/m/Y H:i') }}
            </p>
        </div>
        
        <div style="margin-top:20px;display:flex;gap:8px">
            <button type="submit" class="btn btn-primary">💾 Update</button>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection
