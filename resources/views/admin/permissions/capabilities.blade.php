@extends('admin.layout')

@section('title', 'Role Capabilities - Apa yang Bisa Diakses')

@section('content')
<div class="page-header">
    <h2>Role Capabilities - Apa yang Bisa Diakses</h2>
    <div class="actions">
        <a href="{{ route('admin.permissions.index') }}" class="btn btn-secondary">📋 All Permissions</a>
        <a href="{{ route('admin.permissions.matrix') }}" class="btn btn-secondary">📊 Matrix View</a>
    </div>
</div>

<div class="card" style="margin-bottom:16px;padding:12px;background:#f0f9ff;border-left:4px solid #0ea5e9">
    <p style="margin:0;font-size:13px;color:#0c4a6e">
        🎯 <strong>Role Capabilities:</strong> Halaman ini menampilkan fungsi apa saja yang bisa diakses oleh setiap role dalam sistem.
    </p>
</div>

@foreach($roles as $role)
    @php
        $permissionsByCategory = $role->permissions->groupBy('category');
    @endphp
    
    <div class="card" style="margin-bottom:24px">
        <div class="role-header" style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;padding-bottom:16px;border-bottom:2px solid #f3f4f6">
            <div>
                <h3 style="margin:0;display:flex;align-items:center;gap:12px">
                    @switch($role->name)
                        @case('admin')
                            <span style="background:#dc2626;color:white;padding:8px 12px;border-radius:8px;font-size:14px">
                                👑 {{ $role->display_name ?? 'Administrator' }}
                            </span>
                            @break
                        @case('manager')
                            <span style="background:#059669;color:white;padding:8px 12px;border-radius:8px;font-size:14px">
                                👔 {{ $role->display_name ?? 'Manager' }}
                            </span>
                            @break
                        @case('supervisor')
                            <span style="background:#ea580c;color:white;padding:8px 12px;border-radius:8px;font-size:14px">
                                👨‍💼 {{ $role->display_name ?? 'Supervisor' }}
                            </span>
                            @break
                        @case('employee')
                            <span style="background:#6366f1;color:white;padding:8px 12px;border-radius:8px;font-size:14px">
                                👤 {{ $role->display_name ?? 'Employee' }}
                            </span>
                            @break
                        @default
                            <span style="background:#6b7280;color:white;padding:8px 12px;border-radius:8px;font-size:14px">
                                🏷️ {{ $role->display_name ?? ucfirst($role->name) }}
                            </span>
                    @endswitch
                </h3>
                @if($role->description)
                    <p style="margin:8px 0 0 0;color:#6b7280;font-size:14px">{{ $role->description }}</p>
                @endif
            </div>
            <div style="text-align:right">
                <div style="font-size:18px;font-weight:600;color:#0ea5e9">{{ $role->permissions->count() }}</div>
                <div style="font-size:12px;color:#6b7280">permissions</div>
                <div style="font-size:14px;font-weight:500;color:#374151;margin-top:4px">{{ $role->users->count() }} users</div>
            </div>
        </div>

        @if($role->permissions->count() > 0)
            <div class="capabilities-grid" style="display:grid;grid-template-columns:repeat(auto-fit, minmax(280px, 1fr));gap:16px">
                @foreach($permissionsByCategory as $category => $categoryPermissions)
                    <div class="capability-category" style="background:#f9fafb;border-radius:8px;padding:16px;border-left:4px solid #{{ $role->name === 'admin' ? 'dc2626' : ($role->name === 'manager' ? '059669' : ($role->name === 'supervisor' ? 'ea580c' : '6366f1')) }}">
                        <h4 style="margin:0 0 12px 0;color:#1f2937;display:flex;align-items:center;gap:8px">
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
                            <span style="background:#e5e7eb;color:#374151;padding:2px 6px;border-radius:4px;font-size:11px">
                                {{ $categoryPermissions->count() }}
                            </span>
                        </h4>
                        
                        <ul style="margin:0;padding:0;list-style:none">
                            @foreach($categoryPermissions as $permission)
                                <li style="margin-bottom:6px;display:flex;align-items:flex-start;gap:6px">
                                    <span style="color:#059669;font-size:12px;margin-top:1px">✓</span>
                                    <div>
                                        <div style="font-size:13px;color:#374151;font-weight:500">
                                            {{ $permission->display_name }}
                                        </div>
                                        @if($permission->description)
                                            <div style="font-size:11px;color:#6b7280;margin-top:1px">
                                                {{ Str::limit($permission->description, 60) }}
                                            </div>
                                        @endif
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </div>
            
            <div style="margin-top:16px;padding:12px;background:#f0f9ff;border-radius:6px;border-left:4px solid #{{ $role->name === 'admin' ? 'dc2626' : ($role->name === 'manager' ? '059669' : ($role->name === 'supervisor' ? 'ea580c' : '6366f1')) }}">
                <strong style="color:#0c4a6e">Ringkasan Akses:</strong>
                <span style="color:#0c4a6e">
                    Role {{ $role->display_name ?? ucfirst($role->name) }} memiliki akses ke {{ $role->permissions->count() }} fungsi 
                    dalam {{ $permissionsByCategory->count() }} kategori berbeda.
                </span>
                
                @if($role->name === 'admin')
                    <div style="margin-top:8px;color:#7c2d12;font-size:12px">
                        <strong>⚠️ Super Admin:</strong> Memiliki akses penuh ke seluruh sistem termasuk manajemen user, role, dan pengaturan sistem.
                    </div>
                @elseif($role->name === 'manager')
                    <div style="margin-top:8px;color:#14532d;font-size:12px">
                        <strong>👔 Manager:</strong> Dapat mengelola user dan melihat laporan, tetapi tidak dapat mengubah pengaturan sistem.
                    </div>
                @elseif($role->name === 'supervisor')
                    <div style="margin-top:8px;color:#9a3412;font-size:12px">
                        <strong>👨‍💼 Supervisor:</strong> Fokus pada pengelolaan absensi dan pengawasan tim dengan akses terbatas.
                    </div>
                @elseif($role->name === 'employee')
                    <div style="margin-top:8px;color:#3730a3;font-size:12px">
                        <strong>👤 Employee:</strong> Akses dasar untuk absensi, melihat shift, dan mengelola profil sendiri.
                    </div>
                @endif
            </div>
        @else
            <div style="text-align:center;padding:40px;color:#6b7280;background:#f9fafb;border-radius:8px">
                🔒 Role ini belum memiliki permissions yang diberikan
            </div>
        @endif
    </div>
@endforeach

<style>
.capabilities-grid {
    margin-bottom: 16px;
}

.capability-category {
    min-height: 120px;
}

@media (max-width: 768px) {
    .capabilities-grid {
        grid-template-columns: 1fr;
    }
    
    .role-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 12px;
    }
    
    .role-header > div:last-child {
        text-align: left;
    }
}
</style>
@endsection
