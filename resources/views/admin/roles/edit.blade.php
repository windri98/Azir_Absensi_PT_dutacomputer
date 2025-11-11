@extends('admin.layout')

@section('title', 'Edit Role: ' . $role->display_name)

@section('content')
<div class="page-header">
    <h2>Edit Role: {{ $role->display_name }}</h2>
    <div class="actions">
        <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">← Kembali</a>
        <a href="{{ route('admin.roles.show', $role) }}" class="btn btn-secondary">👁️ Detail</a>
    </div>
</div>

@php
    $systemRoles = ['admin', 'manager', 'employee', 'supervisor'];
    $isSystem = in_array($role->name, $systemRoles);
@endphp

@if($isSystem)
<div class="card" style="margin-bottom:16px;padding:12px;background:#fef2f2;border-left:4px solid #ef4444">
    <p style="margin:0;font-size:13px;color:#991b1b">
        🔒 <strong>Role Sistem:</strong> Ini adalah role sistem bawaan. Perubahan hanya terbatas pada deskripsi untuk menjaga stabilitas sistem.
    </p>
</div>
@else
<div class="card" style="margin-bottom:16px;padding:12px;background:#fef3c7;border-left:4px solid #f59e0b">
    <p style="margin:0;font-size:13px;color:#92400e">
        ⚠️ <strong>Perhatian:</strong> Mengubah nama role dapat mempengaruhi sistem yang sudah ada. Pastikan perubahan tidak merusak fungsionalitas.
    </p>
</div>
@endif

@if($errors->any())
    <div class="alert alert-error">
        <ul style="margin:0;padding-left:20px">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card">
    <form method="POST" action="{{ route('admin.roles.update', $role) }}">
        @csrf
        @method('PUT')
        
        <div class="form-group">
            <label for="name">Nama Role</label>
            <input type="text" id="name" name="name" value="{{ old('name', $role->name) }}" 
                   {{ $isSystem ? 'readonly' : '' }}
                   placeholder="Contoh: customer_service, field_agent" required 
                   pattern="[a-z0-9_-]+" 
                   title="Hanya huruf kecil, angka, dash, dan underscore"
                   style="font-family: monospace; {{ $isSystem ? 'background:#f3f4f6;' : '' }}">
            <small style="color:#6b7280">
                @if($isSystem)
                    Nama role sistem tidak dapat diubah
                @else
                    Nama role dalam sistem (huruf kecil, tanpa spasi)
                @endif
            </small>
        </div>

        <div class="form-group">
            <label for="display_name">Nama Tampilan</label>
            <input type="text" id="display_name" name="display_name" value="{{ old('display_name', $role->display_name) }}" 
                   {{ $isSystem ? 'readonly' : '' }}
                   placeholder="Contoh: Customer Service, Field Agent" required
                   style="{{ $isSystem ? 'background:#f3f4f6;' : '' }}">
            <small style="color:#6b7280">
                @if($isSystem)
                    Nama tampilan role sistem tidak dapat diubah
                @else
                    Nama role yang akan ditampilkan kepada user
                @endif
            </small>
        </div>

        <div class="form-group">
            <label for="description">Deskripsi</label>
            <textarea id="description" name="description" rows="3" 
                      placeholder="Jelaskan fungsi dan tanggung jawab role ini...">{{ old('description', $role->description) }}</textarea>
            <small style="color:#6b7280">Deskripsi singkat tentang role ini</small>
        </div>

        <div style="display:flex;gap:8px;margin-top:24px">
            <button type="submit" class="btn btn-primary">💾 Perbarui Role</button>
            <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>

@if(!$isSystem)
<script>
// Auto-convert to lowercase and replace spaces with underscores
document.getElementById('name').addEventListener('input', function(e) {
    let value = e.target.value;
    value = value.toLowerCase();
    value = value.replace(/\s+/g, '_');
    value = value.replace(/[^a-z0-9_-]/g, '');
    e.target.value = value;
});
</script>
@endif
@endsection