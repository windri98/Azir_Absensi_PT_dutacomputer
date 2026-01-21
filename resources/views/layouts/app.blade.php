<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'PT DUTA COMPUTER - Sistem Manajemen Absensi')</title>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            200: '#bae6fd',
                            300: '#7dd3fc',
                            400: '#38bdf8',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1',
                            800: '#075985',
                            900: '#0c3d66',
                        },
                        accent: {
                            50: '#fdf2f8',
                            100: '#fce7f3',
                            200: '#fbcfe8',
                            300: '#f8b4d8',
                            400: '#f472b6',
                            500: '#ec4899',
                            600: '#db2777',
                            700: '#be185d',
                            800: '#9d174d',
                            900: '#831843',
                        },
                        success: {
                            50: '#f0fdf4',
                            100: '#dcfce7',
                            200: '#bbf7d0',
                            300: '#86efac',
                            400: '#4ade80',
                            500: '#22c55e',
                            600: '#16a34a',
                            700: '#15803d',
                            800: '#166534',
                            900: '#145231',
                        },
                        warning: {
                            50: '#fffbeb',
                            100: '#fef3c7',
                            200: '#fde68a',
                            300: '#fcd34d',
                            400: '#fbbf24',
                            500: '#f59e0b',
                            600: '#d97706',
                            700: '#b45309',
                            800: '#92400e',
                            900: '#78350f',
                        },
                        danger: {
                            50: '#fef2f2',
                            100: '#fee2e2',
                            200: '#fecaca',
                            300: '#fca5a5',
                            400: '#f87171',
                            500: '#ef4444',
                            600: '#dc2626',
                            700: '#b91c1c',
                            800: '#991b1b',
                            900: '#7f1d1d',
                        },
                    },
                    fontFamily: {
                        sans: ['Inter', 'system-ui', 'sans-serif'],
                        display: ['Poppins', 'system-ui', 'sans-serif'],
                    },
                    boxShadow: {
                        'soft': '0 2px 8px rgba(0, 0, 0, 0.08)',
                        'medium': '0 4px 16px rgba(0, 0, 0, 0.12)',
                        'lg': '0 8px 24px rgba(0, 0, 0, 0.15)',
                        'xl': '0 12px 32px rgba(0, 0, 0, 0.18)',
                        'glow': '0 0 20px rgba(14, 165, 233, 0.3)',
                        'glow-lg': '0 0 30px rgba(14, 165, 233, 0.5)',
                        'glow-accent': '0 0 20px rgba(236, 72, 153, 0.3)',
                        'glow-success': '0 0 20px rgba(34, 197, 94, 0.3)',
                        'card': '0 4px 12px rgba(0, 0, 0, 0.1)',
                        'card-hover': '0 12px 24px rgba(0, 0, 0, 0.15)',
                    },
                    backgroundImage: {
                        'gradient-primary': 'linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%)',
                        'gradient-primary-light': 'linear-gradient(135deg, #38bdf8 0%, #0ea5e9 100%)',
                        'gradient-accent': 'linear-gradient(135deg, #ec4899 0%, #db2777 100%)',
                        'gradient-accent-light': 'linear-gradient(135deg, #f472b6 0%, #ec4899 100%)',
                        'gradient-success': 'linear-gradient(135deg, #22c55e 0%, #16a34a 100%)',
                        'gradient-success-light': 'linear-gradient(135deg, #4ade80 0%, #22c55e 100%)',
                        'gradient-warning': 'linear-gradient(135deg, #f59e0b 0%, #d97706 100%)',
                        'gradient-warning-light': 'linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%)',
                        'gradient-danger': 'linear-gradient(135deg, #ef4444 0%, #dc2626 100%)',
                        'gradient-danger-light': 'linear-gradient(135deg, #f87171 0%, #ef4444 100%)',
                        'gradient-vibrant': 'linear-gradient(135deg, #0ea5e9 0%, #ec4899 50%, #f59e0b 100%)',
                        'gradient-cool': 'linear-gradient(135deg, #0284c7 0%, #0369a1 100%)',
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.3s ease-in',
                        'fade-in-slow': 'fadeIn 0.6s ease-in',
                        'slide-up': 'slideUp 0.3s ease-out',
                        'slide-down': 'slideDown 0.3s ease-out',
                        'slide-left': 'slideLeft 0.3s ease-out',
                        'slide-right': 'slideRight 0.3s ease-out',
                        'pulse-soft': 'pulseSoft 2s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                        'bounce-soft': 'bounceSoft 0.6s ease-in-out',
                        'glow-pulse': 'glowPulse 2s ease-in-out infinite',
                        'shimmer': 'shimmer 2s infinite',
                        'float': 'float 3s ease-in-out infinite',
                        'scale-in': 'scaleIn 0.3s ease-out',
                        'rotate-in': 'rotateIn 0.4s ease-out',
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0' },
                            '100%': { opacity: '1' },
                        },
                        slideUp: {
                            '0%': { transform: 'translateY(10px)', opacity: '0' },
                            '100%': { transform: 'translateY(0)', opacity: '1' },
                        },
                        slideDown: {
                            '0%': { transform: 'translateY(-10px)', opacity: '0' },
                            '100%': { transform: 'translateY(0)', opacity: '1' },
                        },
                        slideLeft: {
                            '0%': { transform: 'translateX(10px)', opacity: '0' },
                            '100%': { transform: 'translateX(0)', opacity: '1' },
                        },
                        slideRight: {
                            '0%': { transform: 'translateX(-10px)', opacity: '0' },
                            '100%': { transform: 'translateX(0)', opacity: '1' },
                        },
                        pulseSoft: {
                            '0%, 100%': { opacity: '1' },
                            '50%': { opacity: '0.8' },
                        },
                        bounceSoft: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-5px)' },
                        },
                        glowPulse: {
                            '0%, 100%': { boxShadow: '0 0 20px rgba(14, 165, 233, 0.3)' },
                            '50%': { boxShadow: '0 0 30px rgba(14, 165, 233, 0.6)' },
                        },
                        shimmer: {
                            '0%': { backgroundPosition: '-1000px 0' },
                            '100%': { backgroundPosition: '1000px 0' },
                        },
                        float: {
                            '0%, 100%': { transform: 'translateY(0px)' },
                            '50%': { transform: 'translateY(-10px)' },
                        },
                        scaleIn: {
                            '0%': { transform: 'scale(0.95)', opacity: '0' },
                            '100%': { transform: 'scale(1)', opacity: '1' },
                        },
                        rotateIn: {
                            '0%': { transform: 'rotate(-5deg)', opacity: '0' },
                            '100%': { transform: 'rotate(0)', opacity: '1' },
                        },
                    },
                }
            }
        }
    </script>

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
                    @if(auth()->check() && (auth()->user()->hasPermission('dashboard.view') || auth()->user()->hasPermission('dashboard.admin')))
                    <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard', 'user.dashboard') ? 'active' : '' }}">
                        <span class="sidebar-link-icon"><i class="fas fa-th-large"></i></span>
                        <span>Dashboard</span>
                    </a>
                    @endif
                    @if(auth()->check() && (auth()->user()->hasPermission('attendance.own') || auth()->user()->hasPermission('attendance.view_all')))
                    <a href="{{ route('attendance.absensi') }}" class="sidebar-link {{ request()->routeIs('attendance.absensi', 'attendance.clock-in', 'attendance.clock-out') ? 'active' : '' }}">
                        <span class="sidebar-link-icon"><i class="fas fa-clock"></i></span>
                        <span>Absensi</span>
                    </a>
                    <a href="{{ route('attendance.riwayat') }}" class="sidebar-link {{ request()->routeIs('attendance.riwayat') ? 'active' : '' }}">
                        <span class="sidebar-link-icon"><i class="fas fa-history"></i></span>
                        <span>Riwayat</span>
                    </a>
                    @endif
                </div>

                <div class="sidebar-nav-group">
                    <p class="sidebar-nav-label">Layanan</p>
                    @if(auth()->check() && (auth()->user()->hasPermission('complaints.create') || auth()->user()->hasPermission('complaints.view_own') || auth()->user()->hasPermission('complaints.view_all')))
                    <a href="{{ route('leave.index') }}" class="sidebar-link {{ request()->routeIs('leave.*', 'complaints.*') ? 'active' : '' }}">
                        <span class="sidebar-link-icon"><i class="fas fa-file-signature"></i></span>
                        <span>Izin & Cuti</span>
                    </a>
                    @endif
                    @if(auth()->check() && auth()->user()->hasPermission('reports.view_all'))
                        <a href="{{ route('reports.index') }}" class="sidebar-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                            <span class="sidebar-link-icon"><i class="fas fa-chart-pie"></i></span>
                            <span>Laporan</span>
                        </a>
                    @elseif(auth()->check() && auth()->user()->hasPermission('reports.view_own'))
                        <a href="{{ route('reports.history') }}" class="sidebar-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                            <span class="sidebar-link-icon"><i class="fas fa-chart-pie"></i></span>
                            <span>Laporan</span>
                        </a>
                    @endif
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
                @if(auth()->check() && (auth()->user()->hasPermission('dashboard.view') || auth()->user()->hasPermission('dashboard.admin')))
                    <a href="{{ route('dashboard') }}" class="sidebar-nav-item {{ request()->routeIs('dashboard', 'user.dashboard') ? 'active' : '' }}">
                        <i class="sidebar-nav-icon fas fa-home"></i>
                        <span>Dashboard</span>
                    </a>
                @endif

                @if(auth()->check() && (auth()->user()->hasPermission('attendance.own') || auth()->user()->hasPermission('attendance.view_all')))
                    <a href="{{ route('attendance.absensi') }}" class="sidebar-nav-item {{ request()->routeIs('attendance.absensi') ? 'active' : '' }}">
                        <i class="sidebar-nav-icon fas fa-clock"></i>
                        <span>Absensi</span>
                    </a>

                    <a href="{{ route('attendance.riwayat') }}" class="sidebar-nav-item {{ request()->routeIs('attendance.riwayat') ? 'active' : '' }}">
                        <i class="sidebar-nav-icon fas fa-history"></i>
                        <span>Riwayat</span>
                    </a>
                @endif

                @if(auth()->check() && (auth()->user()->hasPermission('complaints.create') || auth()->user()->hasPermission('complaints.view_own') || auth()->user()->hasPermission('complaints.view_all')))
                    <a href="{{ route('leave.index') }}" class="sidebar-nav-item {{ request()->routeIs('leave.*', 'complaints.*') ? 'active' : '' }}">
                        <i class="sidebar-nav-icon fas fa-file-alt"></i>
                        <span>Izin & Cuti</span>
                    </a>
                @endif

                @if(auth()->check() && auth()->user()->hasPermission('reports.view_all'))
                    <a href="{{ route('reports.index') }}" class="sidebar-nav-item {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                        <i class="sidebar-nav-icon fas fa-chart-bar"></i>
                        <span>Laporan</span>
                    </a>
                @elseif(auth()->check() && auth()->user()->hasPermission('reports.view_own'))
                    <a href="{{ route('reports.history') }}" class="sidebar-nav-item {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                        <i class="sidebar-nav-icon fas fa-chart-bar"></i>
                        <span>Laporan</span>
                    </a>
                @endif

                @if(auth()->check() && (auth()->user()->hasPermission('profile.edit_own') || auth()->user()->hasPermission('profile.view_others')))
                    <a href="{{ route('profile.show') }}" class="sidebar-nav-item {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                        <i class="sidebar-nav-icon fas fa-user"></i>
                        <span>Profil</span>
                    </a>
                @endif

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
            @if(auth()->check() && (auth()->user()->hasPermission('dashboard.view') || auth()->user()->hasPermission('dashboard.admin')))
                <a href="{{ route('dashboard') }}" class="bottom-nav-item {{ request()->routeIs('dashboard', 'user.dashboard') ? 'active' : '' }}">
                    <span class="bottom-nav-icon fas fa-th-large"></span>
                    <span class="bottom-nav-label">Dashboard</span>
                </a>
            @endif
            @if(auth()->check() && (auth()->user()->hasPermission('attendance.own') || auth()->user()->hasPermission('attendance.view_all')))
                <a href="{{ route('attendance.absensi') }}" class="bottom-nav-item {{ request()->routeIs('attendance.absensi', 'attendance.clock-in', 'attendance.clock-out') ? 'active' : '' }}">
                    <span class="bottom-nav-icon fas fa-clock"></span>
                    <span class="bottom-nav-label">Presensi</span>
                </a>
                <a href="{{ route('attendance.riwayat') }}" class="bottom-nav-item {{ request()->routeIs('attendance.riwayat') ? 'active' : '' }}">
                    <span class="bottom-nav-icon fas fa-history"></span>
                    <span class="bottom-nav-label">Riwayat</span>
                </a>
            @endif
            @if(auth()->check() && (auth()->user()->hasPermission('complaints.create') || auth()->user()->hasPermission('complaints.view_own') || auth()->user()->hasPermission('complaints.view_all')))
                <a href="{{ route('leave.index') }}" class="bottom-nav-item {{ request()->routeIs('leave.*') ? 'active' : '' }}">
                    <span class="bottom-nav-icon fas fa-file-signature"></span>
                    <span class="bottom-nav-label">Izin</span>
                </a>
            @endif
            @if(auth()->check() && (auth()->user()->hasPermission('profile.edit_own') || auth()->user()->hasPermission('profile.view_others')))
                <a href="{{ route('profile.show') }}" class="bottom-nav-item {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                    <span class="bottom-nav-icon fas fa-user-circle"></span>
                    <span class="bottom-nav-label">Profil</span>
                </a>
            @endif
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