@extends('admin.layout')

@section('title', 'Tambah Pengguna')

@section('content')
<div class="page-header">
    <h2>Tambah Pengguna</h2>
</div>

<div class="card" style="max-width:700px">
    <form method="post" action="{{ route('admin.users.store') }}">
        @csrf
        
        <h3 style="margin-bottom:16px;color:#374151">Informasi Dasar</h3>
        <label>ID Card / Employee ID *<input type="text" name="employee_id" value="{{ old('employee_id') }}" placeholder="EMP001" required></label>
        <label>Nama *<input type="text" name="name" value="{{ old('name') }}" required></label>
        <label>Email *<input type="email" name="email" value="{{ old('email') }}" required></label>
        <label>Password *<input type="password" name="password" required></label>
        
        <hr style="margin:24px 0;border:none;border-top:1px solid #e5e7eb">
        
        <h3 style="margin-bottom:16px;color:#374151">Informasi Pribadi</h3>
        <label>Telepon<input type="text" name="phone" value="{{ old('phone') }}" placeholder="08123456789"></label>
        <label>Alamat<textarea name="address" rows="3" placeholder="Alamat lengkap">{{ old('address') }}</textarea></label>
        <label>Tanggal Lahir<input type="date" name="birth_date" value="{{ old('birth_date') }}"></label>
        
        <hr style="margin:24px 0;border:none;border-top:1px solid #e5e7eb">
        
        <h3 style="margin-bottom:16px;color:#374151">Role & Shift</h3>
        <label>Roles (tahan Ctrl untuk pilih multiple)
            <select name="roles[]" multiple size="5">
                @foreach($roles as $role)
                    <option value="{{ $role->id }}">{{ $role->name }} - {{ $role->description }}</option>
                @endforeach
            </select>
        </label>
        
        <label>Shift (tahan Ctrl untuk pilih multiple)
            <select name="shifts[]" multiple size="5">
                @foreach($shifts as $shift)
                    <option value="{{ $shift->id }}">{{ $shift->name }} ({{ $shift->start_time }} - {{ $shift->end_time }})</option>
                @endforeach
            </select>
        </label>
        
        <div style="margin-top:20px;display:flex;gap:8px">
            <button type="submit" class="btn btn-primary">💾 Simpan</button>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection
