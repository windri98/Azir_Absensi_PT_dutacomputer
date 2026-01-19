<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel') - PT DUTA COMPUTER</title>

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

    <style>
        /* Admin specific sidebar adjustments */
        .admin-layout {
            display: flex;
            min-height: 100vh;
        }

        .admin-sidebar {
            width: var(--sidebar-width);
            background: var(--bg-card);
            border-right: 1px solid var(--border-color);
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            z-index: 50;
            display: flex;
            flex-direction: column;
            transition: var(--transition);
        }

        .admin-main {
            flex: 1;
            margin-left: var(--sidebar-width);
            background: var(--bg-body);
            min-height: 100vh;
            padding: 2rem;
            transition: var(--transition);
        }

        .admin-topbar {
            background: var(--bg-card);
            border-bottom: 1px solid var(--border-color);
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: -2rem -2rem 2rem -2rem;
            position: sticky;
            top: 0;
            z-index: 40;
        }

        @media (max-width: 1024px) {
            .admin-sidebar {
                left: -100%;
            }
            .admin-sidebar.open {
                left: 0;
            }
            .admin-main {
                margin-left: 0;
                padding: 1rem;
            }
            .admin-topbar {
                margin: -1rem -1rem 1.5rem -1rem;
                padding: 1rem;
            }
        }

        .admin-nav-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.875rem 1.25rem;
            color: var(--text-muted);
            font-weight: 500;
            border-radius: var(--radius-md);
            margin: 0.25rem 1rem;
            transition: var(--transition);
        }

        .admin-nav-item:hover {
            background: var(--bg-body);
            color: var(--primary-color);
        }

        .admin-nav-item.active {
            background: var(--primary-light);
            color: var(--primary-color);
        }

        .admin-nav-icon {
            width: 20px;
            text-align: center;
            font-size: 1.1rem;
        }

        /* Overlay for mobile */
        .admin-overlay {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.5);
            backdrop-filter: blur(4px);
            z-index: 45;
            display: none;
        }
        .admin-overlay.show {
            display: block;
        }
    </style>
    @stack('styles')
</head>
<body class="bg-body">
    <div class="admin-layout">
        <!-- Sidebar -->
        <aside class="admin-sidebar" id="adminSidebar">
            <div class="p-6 flex items-center gap-3">
                <div class="bg-primary-color w-10 h-10 rounded-xl flex items-center justify-center text-white shadow-lg shadow-primary-color/20">
                    <i class="fas fa-tools"></i>
                </div>
                <span class="sidebar-brand-text">Admin Panel</span>
            </div>

            <nav class="flex-1 overflow-y-auto pt-2">
                <a href="{{ route('admin.dashboard') }}" class="admin-nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="admin-nav-icon fas fa-th-large"></i>
                    <span>Dashboard</span>
                </a>

                <div class="px-6 mt-6 mb-2 text-[10px] font-bold text-light uppercase tracking-widest">Manajemen</div>
                
                @if(auth()->user()->hasPermission('users.view'))
                <a href="{{ route('admin.users.index') }}" class="admin-nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <i class="admin-nav-icon fas fa-users"></i>
                    <span>Pengguna</span>
                </a>
                @endif

                @if(auth()->user()->hasPermission('roles.view'))
                <a href="{{ route('admin.roles.index') }}" class="admin-nav-item {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}">
                    <i class="admin-nav-icon fas fa-user-shield"></i>
                    <span>Role & Akses</span>
                </a>
                @endif

                @if(auth()->user()->hasPermission('shifts.view'))
                <a href="{{ route('admin.shifts.index') }}" class="admin-nav-item {{ request()->routeIs('admin.shifts.*') ? 'active' : '' }}">
                    <i class="admin-nav-icon fas fa-clock"></i>
                    <span>Jadwal Shift</span>
                </a>
                @endif

                <div class="px-6 mt-6 mb-2 text-[10px] font-bold text-light uppercase tracking-widest">Layanan</div>

                @if(auth()->user()->hasPermission('complaints.view_all'))
                <a href="{{ route('admin.complaints.index') }}" class="admin-nav-item {{ request()->routeIs('admin.complaints.*') ? 'active' : '' }}">
                    <i class="admin-nav-icon fas fa-file-signature"></i>
                    <span>Pengajuan Izin</span>
                </a>
                @endif

                @if(auth()->user()->hasPermission('reports.export'))
                <a href="{{ route('admin.reports.export') }}" class="admin-nav-item {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                    <i class="admin-nav-icon fas fa-file-download"></i>
                    <span>Ekspor Laporan</span>
                </a>
                @endif
            </nav>

            <div class="p-4 border-t border-color">
                <a href="{{ route('dashboard') }}" class="admin-nav-item">
                    <i class="admin-nav-icon fas fa-arrow-left"></i>
                    <span>Kembali ke App</span>
                </a>
            </div>
        </aside>

        <!-- Overlay -->
        <div class="admin-overlay" id="adminOverlay" onclick="toggleAdminSidebar()"></div>

        <!-- Main Content -->
        <main class="admin-main">
            <header class="admin-topbar">
                <div class="flex items-center gap-4">
                    <button class="btn btn-secondary !p-2 lg:hidden" onclick="toggleAdminSidebar()">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h2 class="text-lg font-bold text-main">@yield('title')</h2>
                </div>
                
                <div class="flex items-center gap-4">
                    <div class="hidden sm:flex flex-col items-end mr-2">
                        <span class="text-sm font-bold text-main">{{ auth()->user()->name }}</span>
                        <span class="text-[10px] text-muted">{{ auth()->user()->roles->first()->display_name ?? 'Admin' }}</span>
                    </div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-secondary !text-danger hover:!bg-danger-light">
                            <i class="fas fa-sign-out-alt"></i>
                            <span class="hidden sm:inline ml-1">Keluar</span>
                        </button>
                    </form>
                </div>
            </header>

            @if(session('success'))
                <div class="flash-message success mb-6 shadow-sm">
                    <i class="fas fa-check-circle"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="flash-message error mb-6 shadow-sm">
                    <i class="fas fa-exclamation-circle"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <script>
        function toggleAdminSidebar() {
            const sidebar = document.getElementById('adminSidebar');
            const overlay = document.getElementById('adminOverlay');
            sidebar.classList.toggle('open');
            overlay.classList.toggle('show');
        }
    </script>
    @stack('scripts')
</body>
</html>
