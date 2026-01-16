@extends('admin.layout')

@section('title', 'Fungsi & Hak Akses System')

@section('content')
<div class="page-header">
    <h2>Fungsi & Hak Akses System</h2>
    <div class="actions">
        <a href="{{ route('admin.permissions.matrix') }}" class="btn btn-secondary">📊 Matrix View</a>
        <a href="{{ route('admin.permissions.capabilities') }}" class="btn btn-primary">🎯 Capabilities</a>
    </div>
</div>

<div class="card" style="margin-bottom:16px;padding:12px;background:#f0f9ff;border-left:4px solid #0ea5e9">
    <p style="margin:0;font-size:13px;color:#0c4a6e">
        📋 <strong>Info:</strong> Halaman ini menampilkan semua fungsi yang tersedia dalam sistem dan role mana saja yang memiliki akses.
    </p>
</div>

<div class="stats-grid" style="display:grid;grid-template-columns:repeat(auto-fit, minmax(200px, 1fr));gap:16px;margin-bottom:24px">
    <div class="stat-card" style="background:#f0fdf4;border-left:4px solid #22c55e;padding:16px;border-radius:8px">
        <div style="font-size:24px;font-weight:bold;color:#15803d">{{ $permissions->count() }}</div>
        <div style="color:#166534;font-size:14px">Total Permissions</div>
    </div>
    <div class="stat-card" style="background:#fef3c7;border-left:4px solid #f59e0b;padding:16px;border-radius:8px">
        <div style="font-size:24px;font-weight:bold;color:#d97706">{{ $permissionsByCategory->count() }}</div>
        <div style="color:#92400e;font-size:14px">Kategori</div>
    </div>
    <div class="stat-card" style="background:#e0e7ff;border-left:4px solid #6366f1;padding:16px;border-radius:8px">
        <div style="font-size:24px;font-weight:bold;color:#4f46e5">{{ $permissions->where('is_system', true)->count() }}</div>
        <div style="color:#3730a3;font-size:14px">System Permissions</div>
    </div>
    <div class="stat-card" style="background:#fce7f3;border-left:4px solid #ec4899;padding:16px;border-radius:8px">
        <div style="font-size:24px;font-weight:bold;color:#be185d">{{ $permissions->where('is_system', false)->count() }}</div>
        <div style="color:#9d174d;font-size:14px">Custom Permissions</div>
    </div>
</div>

@foreach($permissionsByCategory as $category => $categoryPermissions)
<div class="card" style="margin-bottom:20px">
    <div style="display:flex;align-items:center;gap:12px;margin-bottom:16px;padding-bottom:12px;border-bottom:2px solid #f3f4f6">
        <h3 style="margin:0;color:#1f2937;display:flex;align-items:center;gap:8px">
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
        </h3>
        <span style="background:#e5e7eb;color:#374151;padding:4px 8px;border-radius:6px;font-size:12px;font-weight:500">
            {{ $categoryPermissions->count() }} permissions
        </span>
    </div>

    <div style="display:grid;grid-template-columns:repeat(auto-fill, minmax(320px, 1fr));gap:12px">
        @foreach($categoryPermissions as $permission)
            <div style="border:1px solid #e5e7eb;border-radius:8px;padding:12px;background:#fafafa">
                <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:8px">
                    <div style="flex:1">
                        <h4 style="margin:0 0 4px 0;font-size:14px;font-weight:600;color:#1f2937">
                            {{ $permission->display_name }}
                        </h4>
                        <code style="font-size:11px;background:#f3f4f6;padding:2px 4px;border-radius:3px;color:#6b7280">
                            {{ $permission->name }}
                        </code>
                    </div>
                    @if($permission->is_system)
                        <span style="background:#ddd6fe;color:#5b21b6;padding:2px 6px;border-radius:4px;font-size:10px;font-weight:500">
                            System
                        </span>
                    @endif
                </div>

                @if($permission->description)
                    <p style="margin:0 0 8px 0;font-size:12px;color:#6b7280;line-height:1.4">
                        {{ $permission->description }}
                    </p>
                @endif

                <div style="margin-top:8px">
                    <strong style="font-size:11px;color:#374151">Roles yang memiliki akses:</strong>
                    <div style="margin-top:4px;display:flex;flex-wrap:wrap;gap:4px">
                        @forelse($permission->roles as $role)
                            <span style="background:#{{ $role->name === 'admin' ? 'dc2626' : ($role->name === 'manager' ? '059669' : ($role->name === 'supervisor' ? 'ea580c' : '6366f1')) }};color:white;padding:2px 6px;border-radius:4px;font-size:10px;font-weight:500">
                                {{ $role->display_name ?? ucfirst($role->name) }}
                            </span>
                        @empty
                            <span style="color:#9ca3af;font-size:11px;font-style:italic">Tidak ada role</span>
                        @endforelse
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endforeach

<style>
.stats-grid {
    margin-bottom: 24px;
}

.stat-card {
    text-align: center;
}

@media (max-width: 768px) {
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .actions {
        flex-direction: column;
        gap: 8px;
    }
}
</style>
@endsection
