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
    
    <style>
        * {
            font-family: 'Inter', system-ui, sans-serif;
        }
        
        .font-display {
            font-family: 'Poppins', system-ui, sans-serif;
        }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-slate-900 via-primary-900 to-slate-900 relative overflow-hidden">
    <!-- Animated Background Elements -->
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-primary-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-pulse"></div>
        <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-accent-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-pulse" style="animation-delay: 2s;"></div>
    </div>

    <!-- Content -->
    <div class="relative z-10">
        @yield('content')
    </div>

    @stack('scripts')
</body>
</html>
