<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sistem Absensi')</title>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Custom Styles -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components.css') }}">
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">

    @stack('styles')
</head>
<body class="min-h-screen">
    <!-- Mobile Header -->
    @unless(isset($hideHeader))
    <header class="mobile-header lg:hidden">
        <div class="mobile-header-content">
            <button id="mobile-menu-btn" class="btn btn-secondary !p-2 !rounded-lg">
                <i class="fas fa-bars text-xl"></i>
            </button>
            <div class="flex items-center gap-2">
                <div class="bg-primary-color w-8 h-8 rounded-lg flex items-center justify-center text-white shadow-sm">
                    <i class="fas fa-fingerprint text-sm"></i>
                </div>
                <span class="sidebar-brand-text">Absensiku</span>
            </div>
            <a href="{{ route('profile.show') }}" class="btn btn-secondary !p-2 !rounded-lg">
                <i class="fas fa-user text-xl"></i>
            </a>
        </div>
    </header>
    @endunless

    <!-- Main Layout -->
    <div class="app-layout">
        <!-- Desktop Sidebar -->
        <aside class="desktop-sidebar">
            <div class="sidebar-brand">
                <div class="bg-primary-color w-10 h-10 rounded-xl flex items-center justify-center text-white shadow-lg shadow-primary-color/20">
                    <i class="fas fa-fingerprint text-xl"></i>
                </div>
                <span class="sidebar-brand-text">Absensiku</span>
            </div>

            <nav class="sidebar-nav-container">
                <div class="sidebar-nav-group">
                    <p class="sidebar-nav-label">Menu Utama</p>
                    <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard', 'user.dashboard') ? 'active' : '' }}">
                        <span class="sidebar-link-icon"><i class="fas fa-th-large"></i></span>
                        <span>Dashboard</span>
                    </a>
                    <a href="{{ route('attendance.absensi') }}" class="sidebar-link {{ request()->routeIs('attendance.absensi', 'attendance.clock-in', 'attendance.clock-out') ? 'active' : '' }}">
                        <span class="sidebar-link-icon"><i class="fas fa-clock"></i></span>
                        <span>Absensi</span>
                    </a>
                    <a href="{{ route('attendance.riwayat') }}" class="sidebar-link {{ request()->routeIs('attendance.riwayat') ? 'active' : '' }}">
                        <span class="sidebar-link-icon"><i class="fas fa-history"></i></span>
                        <span>Riwayat</span>
                    </a>
                </div>

                <div class="sidebar-nav-group">
                    <p class="sidebar-nav-label">Layanan</p>
                    <a href="{{ route('leave.index') }}" class="sidebar-link {{ request()->routeIs('leave.*', 'complaints.*') ? 'active' : '' }}">
                        <span class="sidebar-link-icon"><i class="fas fa-file-signature"></i></span>
                        <span>Izin & Cuti</span>
                    </a>
                    <a href="{{ route('reports.index') }}" class="sidebar-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                        <span class="sidebar-link-icon"><i class="fas fa-chart-pie"></i></span>
                        <span>Laporan</span>
                    </a>
                </div>

                @if(auth()->check() && auth()->user()->hasPermission('dashboard.admin'))
                <div class="sidebar-nav-group">
                    <p class="sidebar-nav-label">Administrasi</p>
                    <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.*') ? 'active' : '' }}">
                        <span class="sidebar-link-icon"><i class="fas fa-user-shield"></i></span>
                        <span>Admin Panel</span>
                    </a>
                </div>
                @endif
            </nav>

            @if(auth()->check())
            <div class="sidebar-profile">
                @if(auth()->user()->photo)
                    <img src="{{ asset('storage/' . auth()->user()->photo) }}" class="sidebar-profile-img" alt="Profile">
                @else
                    <div class="sidebar-profile-img bg-primary-light flex items-center justify-center">
                        <i class="fas fa-user text-primary-color"></i>
                    </div>
                @endif
                <div class="sidebar-profile-info">
                    <p class="sidebar-profile-name">{{ auth()->user()->name }}</p>
                    <p class="sidebar-profile-role">{{ auth()->user()->roles->first()->display_name ?? 'Karyawan' }}</p>
                </div>
                <form method="POST" action="{{ route('logout') }}" id="desktop-logout-form">
                    @csrf
                    <button type="submit" class="text-light hover:text-danger">
                        <i class="fas fa-sign-out-alt"></i>
                    </button>
                </form>
            </div>
            @endif
        </aside>

        <!-- Mobile Sidebar Overlay -->
        <div id="mobile-sidebar-overlay" class="mobile-sidebar-overlay"></div>

        <!-- Mobile Sidebar -->
        <aside id="mobile-sidebar" class="mobile-sidebar">
            <div class="mobile-sidebar-header">
                <span class="mobile-sidebar-title">Menu</span>
                <button id="close-sidebar-btn" class="mobile-sidebar-close">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <nav class="mobile-sidebar-nav">
                <a href="{{ route('dashboard') }}" class="sidebar-nav-item {{ request()->routeIs('dashboard', 'user.dashboard') ? 'active' : '' }}">
                    <i class="sidebar-nav-icon fas fa-home"></i>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('attendance.absensi') }}" class="sidebar-nav-item {{ request()->routeIs('attendance.absensi') ? 'active' : '' }}">
                    <i class="sidebar-nav-icon fas fa-clock"></i>
                    <span>Absensi</span>
                </a>

                <a href="{{ route('attendance.riwayat') }}" class="sidebar-nav-item {{ request()->routeIs('attendance.riwayat') ? 'active' : '' }}">
                    <i class="sidebar-nav-icon fas fa-history"></i>
                    <span>Riwayat</span>
                </a>

                <a href="{{ route('leave.index') }}" class="sidebar-nav-item {{ request()->routeIs('leave.*', 'complaints.*') ? 'active' : '' }}">
                    <i class="sidebar-nav-icon fas fa-file-alt"></i>
                    <span>Izin & Cuti</span>
                </a>

                <a href="{{ route('reports.index') }}" class="sidebar-nav-item {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                    <i class="sidebar-nav-icon fas fa-chart-bar"></i>
                    <span>Laporan</span>
                </a>

                <a href="{{ route('profile.show') }}" class="sidebar-nav-item {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                    <i class="sidebar-nav-icon fas fa-user"></i>
                    <span>Profil</span>
                </a>

                @if(auth()->check() && auth()->user()->hasPermission('dashboard.admin'))
                <div class="mt-4 pt-4 border-t" style="border-color: var(--border-color);">
                    <p class="px-4 text-xs font-bold text-light uppercase mb-2">Admin</p>
                    <a href="{{ route('admin.dashboard') }}" class="sidebar-nav-item {{ request()->routeIs('admin.*') ? 'active' : '' }}">
                        <i class="sidebar-nav-icon fas fa-cog"></i>
                        <span>Admin Panel</span>
                    </a>
                </div>
                @endif
            </nav>

            <!-- User Info Mobile -->
            @if(auth()->check())
            <div class="mobile-sidebar-user">
                <div class="sidebar-user">
                    @if(auth()->user()->photo)
                        <img src="{{ asset('storage/' . auth()->user()->photo) }}" class="sidebar-user-avatar" alt="Profile">
                    @else
                        <div class="sidebar-user-avatar flex items-center justify-center" style="background: var(--primary-light); color: var(--primary-color);">
                            <i class="fas fa-user"></i>
                        </div>
                    @endif
                    <div class="sidebar-user-info">
                        <p class="sidebar-user-name">{{ auth()->user()->name }}</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="mobile-sidebar-logout">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
            @endif
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Flash Messages -->
            @if(session('success'))
            <div class="flash-message success mx-4 mt-4" id="flash-success">
                <i class="fas fa-check-circle"></i>
                <span style="flex: 1;">{{ session('success') }}</span>
                <button onclick="this.parentElement.remove()" style="background:none; border:none; cursor:pointer; color:currentColor; opacity:0.5;">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            @endif

            @if(session('error'))
            <div class="flash-message error mx-4 mt-4" id="flash-error">
                <i class="fas fa-exclamation-circle"></i>
                <span style="flex: 1;">{{ session('error') }}</span>
                <button onclick="this.parentElement.remove()" style="background:none; border:none; cursor:pointer; color:currentColor; opacity:0.5;">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            @endif

            @if($errors->any())
            <div class="flash-message error mx-4 mt-4">
                <div class="flex items-center mb-2">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    <span class="font-bold">Terjadi kesalahan:</span>
                </div>
                <ul style="list-style: disc; margin-left: 1.5rem; font-size: 0.875rem;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- Page Content -->
            @yield('content')
        </main>
    </div>

    <!-- Mobile Bottom Navigation -->
    <nav class="bottom-nav lg:hidden">
        <div class="bottom-nav-content w-full flex">
            <a href="{{ route('dashboard') }}" class="bottom-nav-item {{ request()->routeIs('dashboard', 'user.dashboard') ? 'active' : '' }}">
                <span class="bottom-nav-icon fas fa-th-large"></span>
                <span class="bottom-nav-label">Dashboard</span>
            </a>
            <a href="{{ route('attendance.absensi') }}" class="bottom-nav-item {{ request()->routeIs('attendance.absensi', 'attendance.clock-in', 'attendance.clock-out') ? 'active' : '' }}">
                <span class="bottom-nav-icon fas fa-clock"></span>
                <span class="bottom-nav-label">Presensi</span>
            </a>
            <a href="{{ route('attendance.riwayat') }}" class="bottom-nav-item {{ request()->routeIs('attendance.riwayat') ? 'active' : '' }}">
                <span class="bottom-nav-icon fas fa-history"></span>
                <span class="bottom-nav-label">Riwayat</span>
            </a>
            <a href="{{ route('leave.index') }}" class="bottom-nav-item {{ request()->routeIs('leave.*') ? 'active' : '' }}">
                <span class="bottom-nav-icon fas fa-file-signature"></span>
                <span class="bottom-nav-label">Izin</span>
            </a>
            <a href="{{ route('profile.show') }}" class="bottom-nav-item {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                <span class="bottom-nav-icon fas fa-user-circle"></span>
                <span class="bottom-nav-label">Profil</span>
            </a>
        </div>
    </nav>

    <!-- Scripts -->
    <script>
        // Mobile sidebar toggle
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const closeSidebarBtn = document.getElementById('close-sidebar-btn');
        const mobileSidebar = document.getElementById('mobile-sidebar');
        const mobileSidebarOverlay = document.getElementById('mobile-sidebar-overlay');

        function openSidebar() {
            mobileSidebar.classList.add('open');
            mobileSidebarOverlay.classList.add('open');
            document.body.style.overflow = 'hidden';
        }

        function closeSidebar() {
            mobileSidebar.classList.remove('open');
            mobileSidebarOverlay.classList.remove('open');
            document.body.style.overflow = '';
        }

        if (mobileMenuBtn) mobileMenuBtn.addEventListener('click', openSidebar);
        if (closeSidebarBtn) closeSidebarBtn.addEventListener('click', closeSidebar);
        if (mobileSidebarOverlay) mobileSidebarOverlay.addEventListener('click', closeSidebar);

        // Auto-hide flash messages
        setTimeout(() => {
            document.getElementById('flash-success')?.remove();
            document.getElementById('flash-error')?.remove();
        }, 5000);
    </script>
    
    @stack('scripts')
</body>
</html>