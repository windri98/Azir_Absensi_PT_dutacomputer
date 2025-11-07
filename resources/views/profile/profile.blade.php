<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - Sistem Absensi</title>
    <link rel="stylesheet" href="components/popup.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            width: 393px;
            height: 852px;
            margin: 0 auto;
            overflow-y: auto;
            padding-bottom: 80px;
        }
        
        /* Profile Header */
        .profile-header {
            background: linear-gradient(135deg, #1ec7e6, #0ea5e9);
            color: white;
            padding: 50px 20px 40px 20px;
            text-align: center;
            position: relative;
        }
        .back-btn {
            position: absolute;
            top: 60px;
            left: 20px;
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: none;
            padding: 10px;
            border-radius: 50%;
            font-size: 18px;
            cursor: pointer;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .profile-avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background-image: url('assets/image/439605617_454358160308404_313339237371064683_n.png');
            background-size: cover;
            background-position: center;
            border: 4px solid rgba(255, 255, 255, 0.3);
            margin: 0 auto 16px auto;
            position: relative;
        }
        .edit-avatar {
            position: absolute;
            bottom: 0;
            right: 0;
            background: #1ec7e6;
            color: white;
            border: none;
            border-radius: 50%;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            cursor: pointer;
            border: 2px solid white;
        }
        .profile-name {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 8px;
        }
        .profile-role {
            font-size: 14px;
            opacity: 0.8;
            margin-bottom: 4px;
        }
        .profile-id {
            font-size: 12px;
            opacity: 0.6;
        }
        
        /* Profile Content */
        .profile-content {
            padding: 20px;
        }
        
        /* Stats Cards */
        .stats-section {
            margin-bottom: 24px;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
        }
        .stat-card {
            background: white;
            padding: 16px;
            border-radius: 12px;
            text-align: center;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        }
        .stat-number {
            font-size: 20px;
            font-weight: bold;
            color: #1ec7e6;
            margin-bottom: 4px;
        }
        .stat-label {
            font-size: 11px;
            color: #6b7280;
        }
        
        /* Menu Sections */
        .menu-section {
            background: white;
            border-radius: 16px;
            margin-bottom: 16px;
            overflow: hidden;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        }
        .menu-item {
            display: flex;
            align-items: center;
            padding: 16px 20px;
            text-decoration: none;
            color: #333;
            border-bottom: 1px solid #f3f4f6;
            transition: background-color 0.2s ease;
        }
        .menu-item:last-child {
            border-bottom: none;
        }
        .menu-item:hover {
            background-color: #f9fafb;
        }
        .menu-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
            font-size: 18px;
        }
        .menu-icon.blue { background: #dbeafe; color: #1d4ed8; }
        .menu-icon.green { background: #dcfce7; color: #16a34a; }
        .menu-icon.orange { background: #fed7aa; color: #ea580c; }
        .menu-icon.purple { background: #e9d5ff; color: #9333ea; }
        .menu-icon.red { background: #fee2e2; color: #dc2626; }
        .menu-icon.gray { background: #f3f4f6; color: #6b7280; }
        
        .menu-text {
            flex: 1;
        }
        .menu-title {
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 2px;
        }
        .menu-subtitle {
            font-size: 12px;
            color: #6b7280;
        }
        .menu-arrow {
            font-size: 16px;
            color: #9ca3af;
        }
        
        /* Section Headers */
        .section-header {
            font-size: 16px;
            font-weight: 600;
            color: #333;
            margin-bottom: 12px;
            padding: 0 4px;
        }
        
        /* Bottom Navigation */
        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 393px;
            background: white;
            border-top: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-around;
            padding: 8px 0 20px 0;
            z-index: 100;
        }
        .nav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-decoration: none;
            color: #9ca3af;
            transition: color 0.2s ease;
            padding: 8px 12px;
        }
        .nav-item.active {
            color: #1ec7e6;
        }
        .nav-icon {
            font-size: 20px;
            margin-bottom: 4px;
        }
        .nav-label {
            font-size: 11px;
            font-weight: 500;
        }
    </style>
</head>
<body>
    <div class="profile-header">
        <button class="back-btn" onclick="goBack()">←</button>
        <div class="profile-avatar">
            <button class="edit-avatar">📷</button>
        </div>
        <div class="profile-name" id="profileName">Widya Mayasari Fauziah</div>
        <div class="profile-role">Karyawan</div>
        <div class="profile-id" id="profileId">ID: EMP001</div>
    </div>

    <div class="profile-content">
        <!-- Stats Section -->
        <div class="stats-section">
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-number" id="monthlyAttendance">22</div>
                    <div class="stat-label">Bulan Ini</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number" id="lateCount">2</div>
                    <div class="stat-label">Terlambat</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number" id="overtimeHours">8h</div>
                    <div class="stat-label">Overtime</div>
                </div>
            </div>
        </div>

        <!-- Account Section -->
        <h3 class="section-header">Akun</h3>
        <div class="menu-section">
            <a href="edit-profile" class="menu-item">
                <div class="menu-icon blue">👤</div>
                <div class="menu-text">
                    <div class="menu-title">Edit Profile</div>
                    <div class="menu-subtitle">Update informasi personal</div>
                </div>
                <div class="menu-arrow">→</div>
            </a>
            <a href="change-password" class="menu-item">
                <div class="menu-icon green">🔒</div>
                <div class="menu-text">
                    <div class="menu-title">Ubah Password</div>
                    <div class="menu-subtitle">Keamanan akun</div>
                </div>
                <div class="menu-arrow">→</div>
            </a>
            <a href="notification-settings" class="menu-item">
                <div class="menu-icon orange">🔔</div>
                <div class="menu-text">
                    <div class="menu-title">Notifikasi</div>
                    <div class="menu-subtitle">Pengaturan pemberitahuan</div>
                </div>
                <div class="menu-arrow">→</div>
            </a>
        </div>

        <!-- Activity Section -->
        <h3 class="section-header">Aktivitas</h3>
        <div class="menu-section">
            <a href="riwayat" class="menu-item">
                <div class="menu-icon purple">📊</div>
                <div class="menu-text">
                    <div class="menu-title">Riwayat Absensi</div>
                    <div class="menu-subtitle">Lihat rekam jejak kehadiran</div>
                </div>
                <div class="menu-arrow">→</div>
            </a>
            <a href="izin" class="menu-item">
                <div class="menu-icon blue">📝</div>
                <div class="menu-text">
                    <div class="menu-title">Pengajuan Izin</div>
                    <div class="menu-subtitle">Cuti dan izin</div>
                </div>
                <div class="menu-arrow">→</div>
            </a>
            <a href="laporan" class="menu-item">
                <div class="menu-icon green">📈</div>
                <div class="menu-text">
                    <div class="menu-title">Laporan</div>
                    <div class="menu-subtitle">Analisa kehadiran</div>
                </div>
                <div class="menu-arrow">→</div>
            </a>
        </div>

        <!-- Support Section -->
        <h3 class="section-header">Bantuan</h3>
        <div class="menu-section">
            <a href="help" class="menu-item">
                <div class="menu-icon gray">❓</div>
                <div class="menu-text">
                    <div class="menu-title">Pusat Bantuan</div>
                    <div class="menu-subtitle">FAQ dan panduan</div>
                </div>
                <div class="menu-arrow">→</div>
            </a>
            <a href="contact" class="menu-item">
                <div class="menu-icon blue">📞</div>
                <div class="menu-text">
                    <div class="menu-title">Hubungi Support</div>
                    <div class="menu-subtitle">Dukungan teknis</div>
                </div>
                <div class="menu-arrow">→</div>
            </a>
            <a href="about" class="menu-item">
                <div class="menu-icon gray">ℹ️</div>
                <div class="menu-text">
                    <div class="menu-title">Tentang Aplikasi</div>
                    <div class="menu-subtitle">Versi 1.0.0</div>
                </div>
                <div class="menu-arrow">→</div>
            </a>
        </div>

        <!-- Logout Section -->
        <div class="menu-section">
            <a href="#" class="menu-item" onclick="logout()">
                <div class="menu-icon red">🚪</div>
                <div class="menu-text">
                    <div class="menu-title">Logout</div>
                    <div class="menu-subtitle">Keluar dari aplikasi</div>
                </div>
                <div class="menu-arrow">→</div>
            </a>
        </div>
    </div>

    <!-- Bottom Navigation -->
    <nav class="bottom-nav">
        <a href="dashboard" class="nav-item">
            <span class="nav-icon">🏠</span>
            <span class="nav-label">Home</span>
        </a>
        <a href="riwayat" class="nav-item">
            <span class="nav-icon">📊</span>
            <span class="nav-label">History</span>
        </a>
        <a href="laporan" class="nav-item">
            <span class="nav-icon">📈</span>
            <span class="nav-label">Reports</span>
        </a>
        <a href="profile" class="nav-item active">
            <span class="nav-icon">👤</span>
            <span class="nav-label">Profile</span>
        </a>
    </nav>

    <script src="components/popup.js"></script>
    <script>
        function goBack() {
            window.location.href = 'dashboard';
        }

        function logout() {
            showWarningPopup({
                title: 'Konfirmasi Logout',
                message: 'Apakah Anda yakin ingin keluar dari aplikasi?',
                buttonText: 'Ya, Logout',
                onClose: () => {
                    // Clear user session
                    localStorage.removeItem('userSession');
                    localStorage.removeItem('attendanceHistory');
                    // Redirect to welcome page
                    window.location.href = 'welcome';
                }
            });
        }

        function updateProfileInfo() {
            const registeredUser = localStorage.getItem('registeredUser');
            if (registeredUser) {
                const userData = JSON.parse(registeredUser);
                document.getElementById('profileName').textContent = userData.name || 'User';
                document.getElementById('profileId').textContent = `ID: ${userData.idcard || 'N/A'}`;
            }

            // Update stats from attendance history
            const attendanceHistory = JSON.parse(localStorage.getItem('attendanceHistory') || '[]');
            
            // Calculate monthly attendance
            const thisMonth = new Date().getMonth();
            const thisYear = new Date().getFullYear();
            const monthlyRecords = attendanceHistory.filter(record => {
                const recordDate = new Date(record.date);
                return recordDate.getMonth() === thisMonth && 
                       recordDate.getFullYear() === thisYear &&
                       record.type === 'clock-in';
            });
            
            // Calculate late count
            const lateRecords = monthlyRecords.filter(record => {
                const clockInTime = new Date(`1970-01-01 ${record.time}`);
                const standardTime = new Date(`1970-01-01 08:00`);
                return clockInTime > standardTime;
            });
            
            document.getElementById('monthlyAttendance').textContent = monthlyRecords.length;
            document.getElementById('lateCount').textContent = lateRecords.length;
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Check if user is logged in
            const userSession = localStorage.getItem('userSession');
            if (!userSession) {
                window.location.href = 'welcome';
                return;
            }
            
            updateProfileInfo();
        });
    </script>
</body>
</html>