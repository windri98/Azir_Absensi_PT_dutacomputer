@extends('admin.layout')

@section('title', 'Permission Matrix - Role vs Permissions')

@section('content')
<div class="page-header">
    <h2>Permission Matrix - Role vs Permissions</h2>
    <div class="actions">
        <a href="{{ route('admin.permissions.index') }}" class="btn btn-secondary">📋 All Permissions</a>
        <a href="{{ route('admin.permissions.capabilities') }}" class="btn btn-primary">🎯 Capabilities</a>
    </div>
</div>

<div class="card" style="margin-bottom:16px;padding:12px;background:#f0f9ff;border-left:4px solid #0ea5e9">
    <p style="margin:0;font-size:13px;color:#0c4a6e">
        📊 <strong>Permission Matrix:</strong> Tabel ini menampilkan mapping antara role dan permissions dalam format matrix. 
        ✓ = memiliki akses, - = tidak memiliki akses.
    </p>
</div>

@foreach($permissionsByCategory as $category => $categoryPermissions)
<div class="card" style="margin-bottom:24px">
    <h3 style="margin-bottom:16px;color:#1f2937;display:flex;align-items:center;gap:8px">
        @switch($category)
            @case('dashboard')
                📊 Dashboard Functions
                @break
            @case('users') 
                👥 User Management
                @break
            @case('roles')
                🛡️ Role Management
                @break
            @case('attendance')
                ⏰ Attendance Management
                @break
            @case('shifts')
                🕐 Shift Management
                @break
            @case('reports')
                📊 Reports & Analytics
                @break
            @case('complaints')
                📝 Complaints & Leave
                @break
            @case('profile')
                👤 Profile Management
                @break
            @case('system')
                ⚙️ System Administration
                @break
            @default
                📋 {{ ucfirst($category) }}
        @endswitch
        <span style="background:#e5e7eb;color:#374151;padding:4px 8px;border-radius:6px;font-size:12px">
            {{ $categoryPermissions->count() }} permissions
        </span>
    </h3>

    <div style="overflow-x:auto">
        <table style="width:100%;min-width:600px">
            <thead>
                <tr>
                    <th style="text-align:left;min-width:250px;padding:12px 8px">Permission</th>
                    @foreach($roles as $role)
                        <th style="text-align:center;min-width:80px;padding:12px 8px">
                            <div style="display:flex;flex-direction:column;align-items:center;gap:4px">
                                @switch($role->name)
                                    @case('admin')
                                        <span style="background:#dc2626;color:white;padding:4px 8px;border-radius:6px;font-size:11px">
                                            👑 Admin
                                        </span>
                                        @break
                                    @case('manager')
                                        <span style="background:#059669;color:white;padding:4px 8px;border-radius:6px;font-size:11px">
                                            👔 Manager
                                        </span>
                                        @break
                                    @case('supervisor')
                                        <span style="background:#ea580c;color:white;padding:4px 8px;border-radius:6px;font-size:11px">
                                            👨‍💼 Supervisor
                                        </span>
                                        @break
                                    @case('employee')
                                        <span style="background:#6366f1;color:white;padding:4px 8px;border-radius:6px;font-size:11px">
                                            👤 Employee
                                        </span>
                                        @break
                                    @default
                                        <span style="background:#6b7280;color:white;padding:4px 8px;border-radius:6px;font-size:11px">
                                            {{ $role->display_name ?? ucfirst($role->name) }}
                                        </span>
                                @endswitch
                            </div>
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($categoryPermissions as $permission)
                <tr style="border-top:1px solid #e5e7eb">
                    <td style="padding:12px 8px;vertical-align:top">
                        <div>
                            <strong style="font-size:13px;color:#1f2937">{{ $permission->display_name }}</strong>
                            @if($permission->description)
                                <div style="font-size:11px;color:#6b7280;margin-top:2px;line-height:1.4">
                                    {{ Str::limit($permission->description, 80) }}
                                </div>
                            @endif
                            <code style="font-size:10px;background:#f3f4f6;padding:1px 4px;border-radius:3px;color:#6b7280;margin-top:4px;display:inline-block">
                                {{ $permission->name }}
                            </code>
                        </div>
                    </td>
                    @foreach($roles as $role)
                        <td style="text-align:center;padding:12px 8px;vertical-align:middle">
                            @if($role->hasPermission($permission))
                                <span style="color:#059669;font-size:16px;font-weight:bold" title="{{ $role->display_name ?? ucfirst($role->name) }} has this permission">✓</span>
                            @else
                                <span style="color:#d1d5db;font-size:16px" title="{{ $role->display_name ?? ucfirst($role->name) }} does not have this permission">-</span>
                            @endif
                        </td>
                    @endforeach
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endforeach

<!-- Summary Statistics -->
<div class="card" style="background:#f9fafb">
    <h3 style="margin-bottom:16px">📊 Ringkasan Matrix</h3>
    
    <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(200px, 1fr));gap:16px">
        @foreach($roles as $role)
            <div style="text-align:center;padding:16px;background:white;border-radius:8px;border-left:4px solid #{{ $role->name === 'admin' ? 'dc2626' : ($role->name === 'manager' ? '059669' : ($role->name === 'supervisor' ? 'ea580c' : '6366f1')) }}">
                <div style="font-weight:600;margin-bottom:4px">{{ $role->display_name ?? ucfirst($role->name) }}</div>
                <div style="font-size:24px;font-weight:bold;color:#{{ $role->name === 'admin' ? 'dc2626' : ($role->name === 'manager' ? '059669' : ($role->name === 'supervisor' ? 'ea580c' : '6366f1')) }}">
                    {{ $role->permissions->count() }}
                </div>
                <div style="font-size:12px;color:#6b7280">
                    permissions ({{ number_format(($role->permissions->count() / $permissions->count()) * 100, 1) }}%)
                </div>
            </div>
        @endforeach
    </div>
</div>

<style>
table {
    border-collapse: collapse;
}

table th, table td {
    border: 1px solid #e5e7eb;
}

table th {
    background: #f9fafb;
    font-weight: 600;
    color: #374151;
}

table tbody tr:hover {
    background: #f9fafb;
}

@media (max-width: 768px) {
    .page-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 12px;
    }
    
    .actions {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }
}
</style>
@endsection