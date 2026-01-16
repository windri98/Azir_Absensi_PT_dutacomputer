@extends('admin.layout')

@section('title', 'Tambah Role Baru')

@section('content')
<div class="page-header">
    <h2>Tambah Role Baru</h2>
    <div class="actions">
        <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">← Kembali</a>
    </div>
</div>

<div class="card" style="margin-bottom:16px;padding:12px;background:#fef3c7;border-left:4px solid #f59e0b">
    <p style="margin:0;font-size:13px;color:#92400e">
        ⚠️ <strong>Perhatian:</strong> Nama role hanya boleh menggunakan huruf kecil, angka, dash (-), dan underscore (_). Contoh: customer_service, field_agent
    </p>
</div>

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
    <form method="POST" action="{{ route('admin.roles.store') }}">
        @csrf
        
        <div class="form-group">
            <label for="name">Nama Role</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" 
                   placeholder="Contoh: customer_service, field_agent" required 
                   pattern="[a-z0-9_-]+" 
                   title="Hanya huruf kecil, angka, dash, dan underscore"
                   style="font-family: monospace;">
            <small style="color:#6b7280">Nama role dalam sistem (huruf kecil, tanpa spasi)</small>
        </div>

        <div class="form-group">
            <label for="display_name">Nama Tampilan</label>
            <input type="text" id="display_name" name="display_name" value="{{ old('display_name') }}" 
                   placeholder="Contoh: Customer Service, Field Agent" required>
            <small style="color:#6b7280">Nama role yang akan ditampilkan kepada user</small>
        </div>

        <div class="form-group">
            <label for="description">Deskripsi (Opsional)</label>
            <textarea id="description" name="description" rows="3" 
                      placeholder="Jelaskan fungsi dan tanggung jawab role ini...">{{ old('description') }}</textarea>
            <small style="color:#6b7280">Deskripsi singkat tentang role ini</small>
        </div>

        <div style="display:flex;gap:8px;margin-top:24px">
            <button type="submit" class="btn btn-primary">💾 Simpan Role</button>
            <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>

<script>
// Auto-generate display name from role name
document.getElementById('name').addEventListener('input', function(e) {
    const name = e.target.value;
    const displayNameField = document.getElementById('display_name');
    
    // Only auto-generate if display name is empty
    if (!displayNameField.value) {
        // Convert snake_case to Title Case
        const displayName = name
            .replace(/[_-]/g, ' ')
            .replace(/\b\w/g, l => l.toUpperCase());
        displayNameField.value = displayName;
    }
});

// Auto-convert to lowercase and replace spaces with underscores
document.getElementById('name').addEventListener('input', function(e) {
    let value = e.target.value;
    value = value.toLowerCase();
    value = value.replace(/\s+/g, '_');
    value = value.replace(/[^a-z0-9_-]/g, '');
    e.target.value = value;
});
</script>
@endsection
