<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem Absensi')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { font-family: 'Inter', 'Segoe UI', sans-serif; }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center">
                        <i class="fas fa-clock text-blue-600 text-2xl mr-3"></i>
                        <span class="font-bold text-xl text-gray-800">Sistem Absensi</span>
                    </a>
                </div>
                
                <div class="flex items-center space-x-4">
                    <!-- Navigation Links -->
                    <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-home mr-1"></i> Dashboard
                    </a>
                    
                    <a href="{{ route('attendance.absensi') }}" class="text-gray-600 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-clock mr-1"></i> Absensi
                    </a>
                    
                    <a href="{{ route('complaints.history') }}" class="text-gray-600 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-exclamation-triangle mr-1"></i> Keluhan
                    </a>
                    
                    <a href="{{ route('management.shift') }}" class="text-gray-600 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-users-cog mr-1"></i> Manajemen Shift
                    </a>
                    
                    <a href="{{ route('management.permissions') }}" class="text-gray-600 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-shield-alt mr-1"></i> Permissions
                    </a>
                    
                    <!-- User Menu -->
                    <div class="relative">
                        <div class="flex items-center space-x-2">
                            @auth
                                <span class="text-gray-700">{{ Auth::user()->name }}</span>
                                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" 
                                    class="text-red-600 hover:text-red-800 px-3 py-2 rounded-md text-sm font-medium">
                                    <i class="fas fa-sign-out-alt mr-1"></i> Logout
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                                    @csrf
                                </form>
                            @else
                                <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-800 px-3 py-2 rounded-md text-sm font-medium">
                                    Login
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="min-h-screen">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-6 mt-12">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p>&copy; 2025 Sistem Absensi. All rights reserved.</p>
        </div>
    </footer>

    <!-- Scripts -->
    <script>
        // Auto hide alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.bg-green-100, .bg-red-100, .bg-yellow-100');
            alerts.forEach(function(alert) {
                alert.style.transition = 'opacity 0.5s';
                alert.style.opacity = '0';
                setTimeout(function() {
                    alert.remove();
                }, 500);
            });
        }, 5000);
    </script>
    
    @yield('scripts')
</body>
</html>