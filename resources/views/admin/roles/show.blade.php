@extends('admin.layout')

@section('title', 'Detail Role: ' . $role->display_name)

@section('content')
<div class="page-header">
    <h2>Detail Role: {{ $role->display_name }}</h2>
    <div class="actions">
        <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">← Kembali</a>
        <a href="{{ route('admin.roles.edit', $role) }}" class="btn btn-warning">✏️ Edit</a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
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

<div class="row" style="display:grid;grid-template-columns:1fr 2fr;gap:16px;margin-bottom:16px">
    <!-- Role Info -->
    <div class="card">
        <h3 style="margin-bottom:16px">Informasi Role</h3>
        
        <div style="margin-bottom:12px">
            <strong style="color:#374151">Nama Role:</strong><br>
            <span style="font-family:monospace;background:#f3f4f6;padding:2px 6px;border-radius:4px;font-size:14px">{{ $role->name }}</span>
        </div>

        <div style="margin-bottom:12px">
            <strong style="color:#374151">Nama Tampilan:</strong><br>
            <span style="font-size:16px;font-weight:600">{{ $role->display_name }}</span>
        </div>

        <div style="margin-bottom:12px">
            <strong style="color:#374151">Deskripsi:</strong><br>
            <span style="color:#6b7280">{{ $role->description ?: 'Tidak ada deskripsi' }}</span>
        </div>

        <div style="margin-bottom:12px">
            <strong style="color:#374151">Jumlah User:</strong><br>
            <span style="font-size:18px;font-weight:600;color:#0ea5e9">{{ $role->users->count() }} user</span>
        </div>

        <div style="margin-bottom:12px">
            <strong style="color:#374151">Jumlah Permissions:</strong><br>
            <span style="font-size:16px;font-weight:600;color:#059669">{{ $role->permissions->count() }} permissions</span>
        </div>

        @php
            $systemRoles = ['admin', 'manager', 'employee', 'supervisor'];
            $isSystem = in_array($role->name, $systemRoles);
        @endphp

        <div style="margin-bottom:12px">
            <strong style="color:#374151">Tipe:</strong><br>
            @if($isSystem)
                <span style="background:#f3f4f6;color:#374151;padding:4px 8px;border-radius:6px;font-size:12px;font-weight:500">
                    🔒 Role Sistem
                </span>
            @else
                <span style="background:#dcfce7;color:#166534;padding:4px 8px;border-radius:6px;font-size:12px;font-weight:500">
                    ✨ Role Custom
                </span>
            @endif
        </div>
    </div>

    <!-- Users with this role -->
    <div class="card">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px">
            <h3 style="margin:0">User dengan Role Ini</h3>
            @if($role->users->count() > 0)
                <span style="background:#e0e7ff;color:#3730a3;padding:4px 8px;border-radius:6px;font-size:12px;font-weight:500">
                    {{ $role->users->count() }} user
                </span>
            @endif
        </div>

        @if($role->users->count() > 0)
            <div style="overflow-x:auto">
                <table style="margin-bottom:0">
                    <thead>
                        <tr>
                            <th>ID Card</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Role Lain</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($role->users as $user)
                        <tr>
                            <td>
                                <span style="font-weight:600;color:#0ea5e9">{{ $user->employee_id ?: '-' }}</span>
                            </td>
                            <td><strong>{{ $user->name }}</strong></td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @php
                                    $otherRoles = $user->roles->where('id', '!=', $role->id);
                                @endphp
                                @if($otherRoles->count() > 0)
                                    @foreach($otherRoles as $otherRole)
                                        <span style="background:#f3f4f6;color:#374151;padding:2px 6px;border-radius:4px;font-size:11px;margin-right:4px">
                                            {{ $otherRole->display_name }}
                                        </span>
                                    @endforeach
                                @else
                                    <span style="color:#9ca3af">-</span>
                                @endif
                            </td>
                            <td>
                                <form method="POST" action="{{ route('admin.roles.remove-user', [$role, $user]) }}" style="display:inline" 
                                    onsubmit="return confirm('Yakin ingin menghapus {{ $user->name }} dari role {{ $role->display_name }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Hapus dari role">🗑️</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div style="text-align:center;padding:40px;color:#6b7280;background:#f9fafb;border-radius:8px">
                👤 Belum ada user dengan role ini
            </div>
        @endif
    </div>
</div>

<!-- Add Users to Role -->
@if($role->users->count() < \App\Models\User::count())
<div class="card">
    <h3 style="margin-bottom:16px">Tambah User ke Role</h3>
    
    <form method="POST" action="{{ route('admin.roles.assign-users', $role) }}">
        @csrf
        
        <div class="form-group">
            <label>Pilih User untuk ditambahkan ke role <strong>{{ $role->display_name }}</strong>:</label>
            
            @php
                $availableUsers = \App\Models\User::whereNotIn('id', $role->users->pluck('id'))->orderBy('name')->get();
            @endphp
            
            @if($availableUsers->count() > 0)
                <div style="max-height:200px;overflow-y:auto;border:1px solid #d1d5db;border-radius:6px;padding:8px;background:#f9fafb">
                    @foreach($availableUsers as $user)
                    <div style="margin-bottom:8px">
                        <label style="display:flex;align-items:center;gap:8px;cursor:pointer;padding:4px;border-radius:4px" 
                               onmouseover="this.style.background='#e5e7eb'" onmouseout="this.style.background='transparent'">
                            <input type="checkbox" name="user_ids[]" value="{{ $user->id }}">
                            <div style="flex:1">
                                <strong>{{ $user->name }}</strong> 
                                <span style="color:#6b7280">({{ $user->email }})</span>
                                @if($user->employee_id)
                                    <span style="color:#0ea5e9;font-weight:500">{{ $user->employee_id }}</span>
                                @endif
                            </div>
                            @if($user->roles->count() > 0)
                                <div style="font-size:11px">
                                    @foreach($user->roles as $userRole)
                                        <span style="background:#e5e7eb;color:#374151;padding:1px 4px;border-radius:3px;margin-left:2px">
                                            {{ $userRole->display_name }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif
                        </label>
                    </div>
                    @endforeach
                </div>
                
                <div style="margin-top:16px">
                    <button type="submit" class="btn btn-primary">➕ Tambahkan User Terpilih</button>
                </div>
            @else
                <div style="text-align:center;padding:20px;color:#6b7280;background:#f9fafb;border-radius:8px">
                    ✅ Semua user sudah memiliki role ini
                </div>
            @endif
        </div>
    </form>
</div>
@endif

<!-- Manage Role Permissions -->
<div class="card">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px">
        <h3 style="margin:0">Kelola Permissions Role</h3>
        <button onclick="togglePermissionManager()" class="btn btn-primary" id="togglePermissionBtn">
            ⚙️ Atur Permissions
        </button>
    </div>
    
    <div id="permissionManager" style="display:none;margin-bottom:24px;padding:16px;background:#f9fafb;border-radius:8px;border-left:4px solid #0ea5e9">
        <h4 style="margin:0 0 16px 0;color:#1f2937">Assign/Remove Permissions</h4>
        
        @php
            $allPermissions = \App\Models\Permission::orderBy('category')->orderBy('name')->get();
            $allPermissionsByCategory = $allPermissions->groupBy('category');
        @endphp
        
        <form method="POST" action="{{ route('admin.roles.update-permissions', $role) }}" id="permissionForm">
            @csrf
            @method('PUT')
            
            <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(300px, 1fr));gap:16px;margin-bottom:16px">
                @foreach($allPermissionsByCategory as $category => $categoryPermissions)
                    <div style="background:white;border-radius:8px;padding:16px;border:1px solid #e5e7eb">
                        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px">
                            <h5 style="margin:0;color:#1f2937;display:flex;align-items:center;gap:8px">
                                @switch($category)
                                    @case('dashboard') 📊 Dashboard @break
                                    @case('users') 👥 User Management @break
                                    @case('roles') 🛡️ Role Management @break
                                    @case('attendance') ⏰ Attendance @break
                                    @case('shifts') 🕐 Shifts @break
                                    @case('reports') 📊 Reports @break
                                    @case('complaints') 📝 Complaints @break
                                    @case('profile') 👤 Profile @break
                                    @case('system') ⚙️ System @break
                                    @default 📋 {{ ucfirst($category) }}
                                @endswitch
                            </h5>
                            <div>
                                <button type="button" onclick="selectAllInCategory('{{ $category }}')" 
                                        class="btn btn-sm" style="font-size:10px;padding:2px 6px">All</button>
                                <button type="button" onclick="deselectAllInCategory('{{ $category }}')" 
                                        class="btn btn-sm btn-secondary" style="font-size:10px;padding:2px 6px">None</button>
                            </div>
                        </div>
                        
                        @foreach($categoryPermissions as $permission)
                            <div style="margin-bottom:8px">
                                <label style="display:flex;align-items:flex-start;gap:8px;cursor:pointer;padding:4px;border-radius:4px" 
                                       class="permission-item" data-category="{{ $category }}"
                                       onmouseover="this.style.background='#f3f4f6'" 
                                       onmouseout="this.style.background='transparent'">
                                    <input type="checkbox" 
                                           name="permissions[]" 
                                           value="{{ $permission->id }}" 
                                           {{ $role->hasPermission($permission) ? 'checked' : '' }}
                                           style="margin-top:2px">
                                    <div style="flex:1">
                                        <div style="font-size:12px;font-weight:500;color:#374151">
                                            {{ $permission->display_name }}
                                        </div>
                                        @if($permission->description)
                                            <div style="font-size:10px;color:#6b7280;margin-top:1px">
                                                {{ Str::limit($permission->description, 60) }}
                                            </div>
                                        @endif
                                    </div>
                                </label>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
            
            <div style="display:flex;gap:8px;justify-content:flex-end;padding-top:16px;border-top:1px solid #e5e7eb">
                <button type="button" onclick="selectAllPermissions()" class="btn btn-secondary">
                    ✓ Select All
                </button>
                <button type="button" onclick="deselectAllPermissions()" class="btn btn-secondary">
                    ✗ Deselect All
                </button>
                <button type="submit" class="btn btn-primary">
                    💾 Update Permissions
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Role Permissions Display -->
<div class="card">
    <h3 style="margin-bottom:16px">Current Permissions / Hak Akses Role</h3>
    
    @if($role->permissions->count() > 0)
        @php
            $permissionsByCategory = $role->permissions->groupBy('category');
        @endphp
        
        <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(300px, 1fr));gap:16px">
            @foreach($permissionsByCategory as $category => $permissions)
                <div style="background:#f9fafb;border-radius:8px;padding:16px;border-left:4px solid #0ea5e9">
                    <h4 style="margin:0 0 12px 0;color:#1f2937;text-transform:capitalize;display:flex;align-items:center;gap:8px">
                        @switch($category)
                            @case('dashboard')
                                📊 Dashboard
                                @break
                            @case('users') 
                                👥 User Management
                                @break
                            @case('roles')
                                🛡️ Role Management
                                @break
                            @case('attendance')
                                ⏰ Attendance
                                @break
                            @case('shifts')
                                🕐 Shifts
                                @break
                            @case('reports')
                                📊 Reports
                                @break
                            @case('complaints')
                                📝 Complaints/Leave
                                @break
                            @case('profile')
                                👤 Profile
                                @break
                            @case('system')
                                ⚙️ System
                                @break
                            @default
                                📋 {{ ucfirst($category) }}
                        @endswitch
                    </h4>
                    
                    <div>
                        @foreach($permissions as $permission)
                            <div style="margin-bottom:8px;display:flex;align-items:center;gap:8px">
                                <span style="color:#059669;font-size:12px">✓</span>
                                <div>
                                    <strong style="font-size:13px;color:#374151">{{ $permission->display_name }}</strong>
                                    @if($permission->description)
                                        <div style="font-size:11px;color:#6b7280;margin-top:2px">
                                            {{ $permission->description }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
        
        <div style="margin-top:16px;padding:12px;background:#f0f9ff;border-radius:6px;border-left:4px solid #0ea5e9">
            <p style="margin:0;font-size:13px;color:#0c4a6e">
                💡 <strong>Total:</strong> Role ini memiliki <strong>{{ $role->permissions->count() }} permissions</strong> 
                yang terbagi dalam <strong>{{ $permissionsByCategory->count() }} kategori</strong>
            </p>
        </div>
    @else
        <div style="text-align:center;padding:40px;color:#6b7280;background:#f9fafb;border-radius:8px">
            🔒 Role ini belum memiliki permissions yang diberikan
        </div>
    @endif
</div>

<style>
.btn-sm {
    padding: 4px 8px;
    font-size: 12px;
    min-width: 32px;
    text-align: center;
}

.row {
    margin-bottom: 16px;
}

@media (max-width: 768px) {
    .row {
        grid-template-columns: 1fr !important;
    }
}
</style>

<script>
function togglePermissionManager() {
    const manager = document.getElementById('permissionManager');
    const btn = document.getElementById('togglePermissionBtn');
    
    if (manager.style.display === 'none') {
        manager.style.display = 'block';
        btn.innerHTML = '❌ Tutup Pengaturan';
        btn.className = 'btn btn-secondary';
    } else {
        manager.style.display = 'none';
        btn.innerHTML = '⚙️ Atur Permissions';
        btn.className = 'btn btn-primary';
    }
}

function selectAllInCategory(category) {
    const checkboxes = document.querySelectorAll(`.permission-item[data-category="${category}"] input[type="checkbox"]`);
    checkboxes.forEach(checkbox => checkbox.checked = true);
}

function deselectAllInCategory(category) {
    const checkboxes = document.querySelectorAll(`.permission-item[data-category="${category}"] input[type="checkbox"]`);
    checkboxes.forEach(checkbox => checkbox.checked = false);
}

function selectAllPermissions() {
    const checkboxes = document.querySelectorAll('#permissionForm input[type="checkbox"]');
    checkboxes.forEach(checkbox => checkbox.checked = true);
}

function deselectAllPermissions() {
    const checkboxes = document.querySelectorAll('#permissionForm input[type="checkbox"]');
    checkboxes.forEach(checkbox => checkbox.checked = false);
}

// Auto-submit confirmation
document.getElementById('permissionForm').addEventListener('submit', function(e) {
    const checkedBoxes = document.querySelectorAll('#permissionForm input[type="checkbox"]:checked');
    const roleName = '{{ $role->display_name ?? $role->name }}';
    
    if (!confirm(`Yakin ingin mengupdate permissions untuk role ${roleName}?\n\nPermissions terpilih: ${checkedBoxes.length} permissions`)) {
        e.preventDefault();
    }
});
</script>
@endsection