<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel')</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f5f7fa; }
        
        /* Topbar */
        .topbar {
            background: linear-gradient(135deg, #0ea5e9, #06b6d4);
            color: white;
            padding: 16px 24px;
            box-shadow: 0 2px 10px rgba(0,0,0,.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
        }
        .topbar h1 { font-size: 20px; font-weight: 600; }
        .topbar-right { display: flex; gap: 12px; align-items: center; }
        .topbar-right a {
            color: white;
            text-decoration: none;
            padding: 8px 14px;
            background: rgba(255,255,255,.15);
            border-radius: 6px;
            font-size: 14px;
            transition: background .2s;
        }
        .topbar-right a:hover { background: rgba(255,255,255,.25); }
        
        /* Hamburger Menu Button (Mobile Only) */
        .menu-toggle {
            display: none;
            background: none;
            border: none;
            color: white;
            font-size: 24px;
            cursor: pointer;
            padding: 8px;
            margin-right: 12px;
        }
        
        /* Sidebar */
        .layout { display: flex; min-height: calc(100vh - 60px); }
        .sidebar {
            width: 240px;
            background: white;
            box-shadow: 2px 0 8px rgba(0,0,0,.06);
            padding: 20px 0;
            transition: transform 0.3s ease;
        }
        .sidebar a {
            display: block;
            padding: 12px 24px;
            color: #374151;
            text-decoration: none;
            font-size: 14px;
            transition: all .2s;
            border-left: 3px solid transparent;
        }
        .sidebar a:hover { background: #f3f4f6; }
        .sidebar a.active {
            background: #eff6ff;
            color: #0ea5e9;
            border-left-color: #0ea5e9;
            font-weight: 600;
        }
        
        /* Mobile Responsive */
        @media (max-width: 768px) {
            .menu-toggle { display: block; }
            
            .topbar h1 { font-size: 16px; }
            .topbar { padding: 12px 16px; }
            
            .sidebar {
                position: fixed;
                left: 0;
                top: 52px;
                bottom: 0;
                z-index: 999;
                transform: translateX(-100%);
                width: 280px;
                box-shadow: 2px 0 20px rgba(0,0,0,.2);
            }
            .sidebar.open {
                transform: translateX(0);
            }
            
            .content {
                padding: 16px;
                width: 100%;
            }
            
            .page-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
            }
            
            .topbar-right {
                gap: 6px;
            }
            .topbar-right a {
                padding: 6px 10px;
                font-size: 12px;
            }
            
            /* Overlay when sidebar open */
            body::after {
                content: '';
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0,0,0,.5);
                z-index: 998;
                opacity: 0;
                pointer-events: none;
                transition: opacity .3s;
            }
            body.sidebar-open::after {
                opacity: 1;
                pointer-events: all;
            }
        }
        
        /* Content */
        .content { flex: 1; padding: 24px; }
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }
        .page-header h2 { font-size: 24px; color: #1f2937; }
        .actions { display: flex; gap: 8px; }
        
        /* Flash messages */
        .flash {
            padding: 14px 18px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            animation: slideIn .3s ease;
        }
        @keyframes slideIn { from { opacity: 0; transform: translateY(-10px); } }
        .flash.success { background: #d1fae5; color: #065f46; border-left: 4px solid #10b981; }
        .flash.error { background: #fee2e2; color: #991b1b; border-left: 4px solid #ef4444; }
        .flash-close {
            background: none;
            border: none;
            font-size: 20px;
            cursor: pointer;
            color: inherit;
            opacity: .6;
        }
        .flash-close:hover { opacity: 1; }
        
        /* Cards */
        .card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 12px rgba(0,0,0,.06);
            margin-bottom: 20px;
        }
        
        /* Buttons */
        .btn {
            display: inline-block;
            padding: 10px 16px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            border: none;
            transition: all .2s;
        }
        .btn-primary { background: #0ea5e9; color: white; }
        .btn-primary:hover { background: #0284c7; transform: translateY(-1px); }
        .btn-secondary { background: #f3f4f6; color: #374151; }
        .btn-secondary:hover { background: #e5e7eb; }
        .btn-danger { background: #ef4444; color: white; }
        .btn-danger:hover { background: #dc2626; }
        .btn-success { background: #10b981; color: white; }
        .btn-success:hover { background: #059669; }
        
        /* Tables */
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 12px rgba(0,0,0,.06);
        }
        th, td {
            padding: 14px 16px;
            text-align: left;
            border-bottom: 1px solid #f3f4f6;
        }
        th {
            background: #f9fafb;
            color: #6b7280;
            font-weight: 600;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: .5px;
        }
        td { font-size: 14px; color: #374151; }
        tr:last-child td { border-bottom: none; }
        tr:hover td { background: #f9fafb; }
        
        /* Forms */
        label {
            display: block;
            margin-top: 16px;
            margin-bottom: 6px;
            font-size: 14px;
            font-weight: 500;
            color: #374151;
        }
        input, select, textarea {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 14px;
            transition: border-color .2s;
        }
        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: #0ea5e9;
            box-shadow: 0 0 0 3px rgba(14,165,233,.1);
        }
        
        /* Pagination */
        .pagination {
            display: flex;
            gap: 6px;
            margin-top: 20px;
            align-items: center;
        }
        .pagination a, .pagination span {
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 14px;
            text-decoration: none;
            color: #374151;
            background: white;
            border: 1px solid #e5e7eb;
        }
        .pagination a:hover { background: #f3f4f6; }
        .pagination .active { background: #0ea5e9; color: white; border-color: #0ea5e9; }
    </style>
    @stack('styles')
</head>
<body>
    <div class="topbar">
        <button class="menu-toggle" onclick="toggleSidebar()">☰</button>
        <h1>🛠️ Admin Panel</h1>
        <div class="topbar-right">
            <span>{{ auth()->user()->name }}</span>
            @if(auth()->user()->hasPermission('dashboard.view'))
                <a href="{{ route('dashboard') }}?type=user">Dashboard User</a>
            @else
                <a href="{{ route('dashboard') }}">Dashboard</a>
            @endif
            <form method="post" action="{{ route('logout') }}" style="display:inline">
                @csrf
                <button type="submit" class="btn btn-secondary" style="padding:8px 14px">Logout</button>
            </form>
        </div>
    </div>
    
    <div class="layout">
        <aside class="sidebar" id="sidebar">
            @if(auth()->user()->hasPermission('dashboard.admin'))
                <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    📊 Dashboard
                </a>
            @endif
            
            @if(auth()->user()->hasPermission('users.view'))
                <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    👥 Kelola Pengguna
                </a>
            @endif
            
            @if(auth()->user()->hasPermission('roles.view'))
                <a href="{{ route('admin.roles.index') }}" class="{{ request()->routeIs('admin.roles.*') ? 'active' : '' }}">
                    🛡️ Kelola Role
                </a>
            @endif
            
            @if(auth()->user()->hasPermission('permissions.view'))
                <a href="{{ route('admin.permissions.capabilities') }}" class="{{ request()->routeIs('admin.permissions.*') ? 'active' : '' }}">
                    🎯 Hak Akses & Fungsi
                </a>
            @endif
            
            @if(auth()->user()->hasPermission('shifts.view'))
                <a href="{{ route('admin.shifts.index') }}" class="{{ request()->routeIs('admin.shifts.*') ? 'active' : '' }}">
                    🕐 Kelola Shift
                </a>
            @endif
            
            @if(auth()->user()->hasPermission('complaints.view_all'))
                <a href="{{ route('admin.complaints.index') }}" class="{{ request()->routeIs('admin.complaints.*') ? 'active' : '' }}">
                    📝 Kelola Pengajuan
                </a>
            @endif
            
            @if(auth()->user()->hasPermission('reports.export'))
                <a href="{{ route('admin.reports.export') }}" class="{{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                    📥 Export Laporan
                </a>
            @endif
            
            @if(auth()->user()->hasPermission('users.create'))
                <a href="{{ route('register') }}">
                    ➕ Tambah User
                </a>
            @endif
        </aside>
        
        <main class="content">
            @if(session('success'))
                <div class="flash success">
                    <span>✓ {{ session('success') }}</span>
                    <button class="flash-close" onclick="this.parentElement.remove()">×</button>
                </div>
            @endif
            
            @if(session('error'))
                <div class="flash error">
                    <span>✗ {{ session('error') }}</span>
                    <button class="flash-close" onclick="this.parentElement.remove()">×</button>
                </div>
            @endif
            
            @if($errors->any())
                <div class="flash error">
                    <div>
                        <strong>Terjadi kesalahan:</strong>
                        <ul style="margin:8px 0 0 20px">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    <button class="flash-close" onclick="this.parentElement.remove()">×</button>
                </div>
            @endif
            
            @yield('content')
        </main>
    </div>
    
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const body = document.body;
            
            sidebar.classList.toggle('open');
            body.classList.toggle('sidebar-open');
        }
        
        // Close sidebar when clicking overlay
        document.addEventListener('click', (e) => {
            if (document.body.classList.contains('sidebar-open') && 
                !e.target.closest('.sidebar') && 
                !e.target.closest('.menu-toggle')) {
                toggleSidebar();
            }
        });
        
        // Close flash messages
        document.querySelectorAll('.flash-close').forEach(btn => {
            btn.addEventListener('click', function() {
                this.parentElement.remove();
            });
        });
    </script>
    
    @stack('scripts')
</body>
</html>
