<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sistem Absensi</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            min-height: 100vh;
            width: 100%;
            max-width: 393px;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            position: relative;
            overflow-y: auto;
        }
        
        @media (min-width: 394px) {
            body {
                box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            }
        }
        
        .header {
            background: linear-gradient(135deg, #1ec7e6, #0ea5e9);
            color: white;
            padding: 50px 20px 30px 20px;
            position: relative;
            border-radius: 0 0 50px 50px;
            overflow: hidden;
            height: auto;
            min-height: 200px;
        }
        .logout-btn {
            position: absolute;
            top: 20px;
            left: 20px;
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 15px;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .logout-btn:hover {
            background: rgba(255, 255, 255, 0.3);
        }
        .dropdown-container {
            position: absolute;
            top: 70px;
            right: 20px;
            display: inline-block;
        }
        .dropdown-btn {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 15px;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .dropdown-btn:hover {
            background: rgba(255, 255, 255, 0.3);
        }
        .dropdown-content {
            display: none;
            position: absolute;
            right: 0;
            top: 100%;
            background-color: white;
            min-width: 200px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.2);
            border-radius: 10px;
            z-index: 1000;
            margin-top: 5px;
        }
        .dropdown-content.show {
            display: block;
            animation: slideDown 0.3s ease;
        }
        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .dropdown-item {
            color: #333;
            padding: 12px 16px;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: background-color 0.2s ease;
            font-size: 14px;
        }
        .dropdown-item:first-child {
            border-radius: 10px 10px 0 0;
        }
        .dropdown-item:last-child {
            border-radius: 0 0 10px 10px;
        }
        .dropdown-item:hover {
            background-color: #f0f0f0;
        }
        .dropdown-item .icon {
            font-size: 16px;
            width: 20px;
            text-align: center;
        }
        .dropdown-divider {
            height: 1px;
            background-color: #e0e0e0;
            margin: 5px 0;
        }
        .profile-section {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-top: 40px;
            padding: 0 20px;
        }
        .profile-image {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background-image: url('assets/image/account-circle.svg');
            background-size: cover;
            background-position: center;
            border: 3px solid rgba(255, 255, 255, 0.3);
        }
        .profile-info {
            flex: 1;
        }
        .employee-status {
            background: rgba(255, 255, 255, 0.2);
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 12px;
            display: inline-block;
            margin-bottom: 8px;
        }
        .employee-name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .welcome-card {
            position: relative;
            margin: -20px 20px 20px 20px;
            background: white;
            padding: 25px 20px;
            border-radius: 20px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            text-align: center;
            z-index: 10;
        }
        .welcome-text {
            color: #666;
            font-size: 14px;
            margin-bottom: 5px;
        }
        .user-name {
            color: #333;
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .menu-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin-top: 15px;
        }
        .menu-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-decoration: none;
            color: #333;
            transition: transform 0.2s ease;
        }
        .menu-item:hover {
            transform: translateY(-2px);
        }
        .menu-icon {
            width: 50px;
            height: 50px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            margin-bottom: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        .menu-icon.profil {
            background-color: #6366f1;
            color: white;
        }
        .menu-icon.absen {
            background-color: #06b6d4;
            color: white;
        }
        .menu-icon.aktifitas {
            background-color: #f59e0b;
            color: white;
        }
        .menu-icon.riwayat {
            background-color: #ef4444;
            color: white;
        }
        .menu-label {
            font-size: 13px;
            color: #666;
            text-align: center;
            font-weight: 500;
        }
        .main-content {
            flex: 1;
            padding: 0 0 80px 0;
            background-color: #f5f5f5;
            min-height: calc(100vh - 300px);
        }
        
        /* Enhanced Dashboard Components */
        .status-card {
            background: white;
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        }
        .status-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
        }
        .status-header h3 {
            font-size: 16px;
            font-weight: 600;
            color: #333;
        }
        .status-badge {
            background: #fee2e2;
            color: #dc2626;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 500;
        }
        .status-badge.active {
            background: #dcfce7;
            color: #16a34a;
        }
        .status-content {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        .status-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .status-label {
            font-size: 14px;
            color: #6b7280;
        }
        .status-time {
            font-size: 14px;
            font-weight: 600;
            color: #333;
        }
        
        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            margin-bottom: 20px;
        }
        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 16px;
            text-align: center;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        }
        .stat-number {
            font-size: 24px;
            font-weight: bold;
            color: #1ec7e6;
            margin-bottom: 4px;
        }
        .stat-label {
            font-size: 12px;
            color: #6b7280;
        }
        
        /* Quick Actions */
        .quick-actions {
            margin-bottom: 20px;
        }
        .quick-actions h3 {
            font-size: 16px;
            font-weight: 600;
            color: #333;
            margin-bottom: 12px;
            padding: 0 4px;
        }
        .action-buttons {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }
        .action-btn {
            background: white;
            border: none;
            border-radius: 12px;
            padding: 16px 12px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
            font-size: 14px;
            font-weight: 500;
        }
        .action-btn.primary {
            background: linear-gradient(135deg, #1ec7e6, #0ea5e9);
            color: white;
        }
        .action-btn.secondary {
            background: white;
            color: #333;
            border: 1px solid #e5e7eb;
        }

        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12);
        }
        .action-icon {
            font-size: 20px;
        }
        
        /* Bottom Navigation */
        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            width: 100%;
            background: white;
            border-top: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-around;
            padding: 8px 0;
            padding-bottom: calc(8px + env(safe-area-inset-bottom, 0px));
            z-index: 100;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
        }
        
        @media (max-width: 393px) {
            .bottom-nav {
                max-width: 100%;
            }
        }
        
        @media (min-width: 394px) {
            .bottom-nav {
                max-width: 393px;
                left: 50%;
                transform: translateX(-50%);
                border-radius: 12px 12px 0 0;
                box-shadow: 0 -2px 15px rgba(0, 0, 0, 0.15);
            }
        }
        .nav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-decoration: none;
            color: #9ca3af;
            transition: color 0.2s ease;
            padding: 6px 8px;
            min-width: 60px;
        }
        .nav-item.active {
            color: #1ec7e6;
        }
        .nav-icon {
            font-size: 22px;
            margin-bottom: 2px;
        }
        .nav-label {
            font-size: 10px;
            font-weight: 500;
        }
    </style>
</head>
<body>
    <div class="header">
        <button class="logout-btn" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Log Out</button>
        
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
        
        <div class="dropdown-container">
            <button class="dropdown-btn" onclick="toggleDropdown()">
                ⋮ Profile
            </button>
            <div class="dropdown-content" id="dropdownMenu">
                <a href="{{ route('profile.show') }}" class="dropdown-item">
                    <span class="icon">👤</span>
                    Lihat Profile
                </a>
                @if(auth()->user()->hasPermission('dashboard.admin'))
                    <a href="{{ route('dashboard') }}?type=admin" class="dropdown-item">
                        <span class="icon">🛠️</span>
                        Dashboard Admin
                    </a>
                @endif
                <a href="{{ route('management.pengaturan') }}" class="dropdown-item">
                    <span class="icon">⚙️</span>
                    Pengaturan
                </a>
                <div class="dropdown-divider"></div>
                @can('reports.view')
                <a href="{{ route('reports.users') }}" class="dropdown-item">
                    <span class="icon">👥</span>
                    Laporan Per User
                </a>
                @endcan
                <a href="#" class="dropdown-item" onclick="downloadReport('laporan')">
                    <span class="icon">📄</span>
                    Download Laporan
                </a>
                <a href="#" class="dropdown-item" onclick="downloadReport('absensi')">
                    <span class="icon">📊</span>
                    Download Data Absensi
                </a>
                <a href="#" class="dropdown-item" onclick="downloadReport('riwayat')">
                    <span class="icon">📋</span>
                    Download Riwayat
                </a>
                <div class="dropdown-divider"></div>
                <a href="{{ route('help') }}" class="dropdown-item">
                    <span class="icon">❓</span>
                    Bantuan
                </a>
                <a href="{{ route('about') }}" class="dropdown-item">
                    <span class="icon">ℹ️</span>
                    Tentang Aplikasi
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" style="color: #ef4444;">
                    <span class="icon">🚪</span>
                    Keluar
                </a>
            </div>
        </div>
        
        <div class="profile-section">
            <div class="profile-image" style="background-image: url('{{ $user->photo ? asset('storage/' . $user->photo) : asset('assets/image/account-circle.svg') }}');"></div>
            <div class="profile-info">
                <div class="employee-status">
                    @if($user->roles->isNotEmpty())
                        {{ ucfirst($user->roles->first()->name) }}
                    @else
                        Karyawan
                    @endif
                </div>
                <div class="employee-name">{{ $user->name }}</div>
            </div>
        </div>
    </div>
    
    <div class="main-content">
        <div class="welcome-card">
            <div class="welcome-text">
                @php
                    $hour = date('H');
                    if ($hour >= 5 && $hour < 11) {
                        echo 'Selamat pagi,';
                    } elseif ($hour >= 11 && $hour < 15) {
                        echo 'Selamat siang,';
                    } elseif ($hour >= 15 && $hour < 18) {
                        echo 'Selamat sore,';
                    } else {
                        echo 'Selamat malam,';
                    }
                @endphp
            </div>
            <div class="user-name">{{ $user->name }}</div>
            
            <div class="menu-grid">
                <a href="{{ route('attendance.absensi') }}" class="menu-item">
                    <div class="menu-icon absen">📷</div>
                    <div class="menu-label">Absen</div>
                </a>
                
                <a href="{{ route('activities.aktifitas') }}" class="menu-item">
                    <div class="menu-icon aktifitas">📋</div>
                    <div class="menu-label">Aktivitas</div>
                </a>
                
                <a href="{{ route('attendance.riwayat') }}" class="menu-item">
                    <div class="menu-icon riwayat">🕒</div>
                    <div class="menu-label">Riwayat</div>
                </a>
            </div>
        </div>
        
        <!-- Additional content space -->
        <div style="padding: 0 20px; margin-top: 20px;">
            <!-- Today's Status Card -->
            <div class="status-card">
                <div class="status-header">
                    <h3>Today's Status</h3>
                    @if($todayAttendance)
                        @if($todayAttendance->check_out)
                            <span class="status-badge">Completed</span>
                        @else
                            <span class="status-badge active">Working</span>
                        @endif
                    @else
                        <span class="status-badge" id="statusBadge">Not Clocked In</span>
                    @endif
                </div>
                <div class="status-content">
                    <div class="status-item">
                        <span class="status-label">Clock In</span>
                        <span class="status-time" id="clockInTime">
                            {{ $todayAttendance ? \Carbon\Carbon::parse($todayAttendance->check_in)->format('H:i') : '--:--' }}
                        </span>
                    </div>
                    <div class="status-item">
                        <span class="status-label">Clock Out</span>
                        <span class="status-time" id="clockOutTime">
                            {{ $todayAttendance && $todayAttendance->check_out ? \Carbon\Carbon::parse($todayAttendance->check_out)->format('H:i') : '--:--' }}
                        </span>
                    </div>
                    <div class="status-item">
                        <span class="status-label">Working Hours</span>
                        <span class="status-time" id="workingHours">
                            {{ $todayAttendance && $todayAttendance->work_hours ? number_format($todayAttendance->work_hours, 1) . 'h' : '0h' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- QR Code Section -->
            <div class="status-card">
                <div class="status-header">
                    <h3>🎫 QR Code Anda</h3>
                    <span class="status-badge active">Active</span>
                </div>
                <div style="text-align: center; padding: 20px 0;">
                    <div style="background: white; padding: 20px; border-radius: 16px; display: inline-block; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                        {!! $user->getQRCode() !!}
                    </div>
                    <p style="margin-top: 16px; color: #6b7280; font-size: 14px;">
                        Scan QR code ini untuk absensi overtime
                    </p>
                    <div style="margin-top: 12px; padding: 12px; background: #f0f9ff; border-radius: 12px;">
                        <p style="font-size: 12px; color: #0284c7; margin: 0;">
                            <strong>ID Karyawan:</strong> {{ $user->employee_id }}
                        </p>
                        <p style="font-size: 12px; color: #0284c7; margin: 4px 0 0 0;">
                            <strong>Nama:</strong> {{ $user->name }}
                        </p>
                    </div>
                    <button onclick="downloadQRCode()" style="margin-top: 16px; background: linear-gradient(135deg, #1ec7e6, #0ea5e9); color: white; border: none; padding: 12px 24px; border-radius: 12px; cursor: pointer; font-size: 14px; font-weight: 600;">
                        📥 Download QR Code
                    </button>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-number">{{ $monthlyStats['present'] + $monthlyStats['late'] }}</div>
                    <div class="stat-label">This Month</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">{{ $monthlyStats['late'] }}</div>
                    <div class="stat-label">Late Days</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">{{ $monthlyStats['work_leave'] ?? 0 }}</div>
                    <div class="stat-label">Work Leave</div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="quick-actions">
                <h3>Quick Actions</h3>
                <div class="action-buttons">
                    <button class="action-btn primary" onclick="quickClockIn()">
                        <span class="action-icon">📍</span>
                        Quick Clock In
                    </button>
                    <button class="action-btn secondary" onclick="requestWorkLeave()">
                        <span class="action-icon">📝</span>
                        Ajukan Izin
                    </button>
                </div>
            </div>
        </div>

        <!-- Bottom Navigation -->
        <nav class="bottom-nav">
            <a href="{{ route('dashboard') }}" class="nav-item active">
                <span class="nav-icon">🏠</span>
                <span class="nav-label">Home</span>
            </a>
            <a href="{{ route('attendance.riwayat') }}" class="nav-item">
                <span class="nav-icon">📊</span>
                <span class="nav-label">History</span>
            </a>
            <a href="{{ route('reports.index') }}" class="nav-item">
                <span class="nav-icon">📈</span>
                <span class="nav-label">Reports</span>
            </a>
            <a href="{{ route('profile.show') }}" class="nav-item">
                <span class="nav-icon">👤</span>
                <span class="nav-label">Profile</span>
            </a>
        </nav>
    </div>

    <script>
        // Logout function is now handled by form submission (see logout button above)
        
        function downloadReport(type) {
            // Simulate download functionality
            const reportTypes = {
                'laporan': 'Laporan Kehadiran',
                'absensi': 'Data Absensi',
                'riwayat': 'Riwayat Aktivitas'
            };
            
            alert(`Download ${reportTypes[type]} akan segera dimulai...`);
            // In real app, this would trigger actual download
        }

        function toggleDropdown() {
            const dropdown = document.getElementById('dropdownMenu');
            dropdown.classList.toggle('show');
        }

        // Close dropdown when clicking outside
        window.onclick = function(event) {
            if (!event.target.matches('.dropdown-btn')) {
                const dropdowns = document.getElementsByClassName('dropdown-content');
                for (let i = 0; i < dropdowns.length; i++) {
                    const openDropdown = dropdowns[i];
                    if (openDropdown.classList.contains('show')) {
                        openDropdown.classList.remove('show');
                    }
                }
            }
        }

        // Function to get greeting based on time
        function getGreeting() {
            const hour = new Date().getHours();
            if (hour < 12) {
                return 'selamat pagi,';
            } else if (hour < 15) {
                return 'selamat siang,';
            } else if (hour < 18) {
                return 'selamat sore,';
            } else {
                return 'selamat malam,';
            }
        }

        // Update user info from session
        // NOTE: User info is now rendered server-side via Laravel Blade ({{ $user->name }})
        // No need for client-side localStorage updates

        // Update today's status
        function updateTodayStatus() {
            const attendanceHistory = JSON.parse(localStorage.getItem('attendanceHistory') || '[]');
            const today = new Date().toDateString();
            
            const todayRecords = attendanceHistory.filter(record => 
                new Date(record.date).toDateString() === today
            );
            
            const clockInRecord = todayRecords.find(record => record.type === 'clock-in');
            const clockOutRecord = todayRecords.find(record => record.type === 'clock-out');
            
            const statusBadge = document.getElementById('statusBadge');
            const clockInTime = document.getElementById('clockInTime');
            const clockOutTime = document.getElementById('clockOutTime');
            const workingHours = document.getElementById('workingHours');
            
            if (clockInRecord) {
                clockInTime.textContent = clockInRecord.time;
                statusBadge.textContent = clockOutRecord ? 'Completed' : 'Working';
                statusBadge.className = clockOutRecord ? 'status-badge' : 'status-badge active';
            }
            
            if (clockOutRecord) {
                clockOutTime.textContent = clockOutRecord.time;
            }
            
            // Calculate working hours
            if (clockInRecord && clockOutRecord) {
                const start = new Date(`${today} ${clockInRecord.time}`);
                const end = new Date(`${today} ${clockOutRecord.time}`);
                const diff = end - start;
                const hours = Math.floor(diff / (1000 * 60 * 60));
                const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                workingHours.textContent = `${hours}h ${minutes}m`;
            }
        }

        // Quick actions
        function quickClockIn() {
            window.location.href = '{{ route("attendance.clock-in") }}';
        }

        function requestWorkLeave() {
            window.location.href = '{{ route("absensi") }}';
        }

                // Update greeting based on current time
        document.addEventListener('DOMContentLoaded', function() {
            const welcomeText = document.querySelector('.welcome-text');
            welcomeText && (welcomeText.textContent = getGreeting());

            // Update today's status
            updateTodayStatus();
        });

        // Download QR Code function
        function downloadQRCode() {
            const svgElement = document.querySelector('.status-card svg');
            if (!svgElement) {
                alert('QR Code tidak ditemukan');
                return;
            }

            // Create canvas
            const canvas = document.createElement('canvas');
            const ctx = canvas.getContext('2d');
            const svgData = new XMLSerializer().serializeToString(svgElement);
            const img = new Image();
            
            img.onload = function() {
                canvas.width = img.width;
                canvas.height = img.height;
                ctx.fillStyle = 'white';
                ctx.fillRect(0, 0, canvas.width, canvas.height);
                ctx.drawImage(img, 0, 0);
                
                // Download
                canvas.toBlob(function(blob) {
                    const url = URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = 'qrcode_{{ $user->employee_id }}.png';
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);
                    URL.revokeObjectURL(url);
                });
            };
            
            img.src = 'data:image/svg+xml;base64,' + btoa(unescape(encodeURIComponent(svgData)));
        }
    </script>
</body>
</html>
