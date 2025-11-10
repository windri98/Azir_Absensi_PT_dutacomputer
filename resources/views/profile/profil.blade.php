<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="theme-color" content="#1ec7e6">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <title>Profil - Sistem Absensi</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            -webkit-tap-highlight-color: transparent;
        }
        
        html {
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            width: 100%;
            max-width: 393px;
            min-height: 100vh;
            margin: 0 auto;
            overflow-y: auto;
            padding-bottom: 0;
        }
        
        @media (min-width: 394px) {
            body {
                box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            }
        }

        .header {
            background: linear-gradient(135deg, #1ec7e6, #0ea5e9);
            color: white;
            padding: 40px 20px 60px 20px;
            position: relative;
        }

        .profile-card {
            background: white;
            border-radius: 20px;
            padding: 24px;
            margin: -40px 20px 20px 20px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        .profile-header {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 20px;
            padding-bottom: 20px;
            border-bottom: 1px solid #f3f4f6;
        }

        .profile-photo {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background: linear-gradient(135deg, #1ec7e6, #0ea5e9);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            color: white;
            flex-shrink: 0;
            box-shadow: 0 4px 12px rgba(30, 199, 230, 0.3);
        }

        .profile-info {
            flex: 1;
        }

        .profile-name {
            font-size: 18px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 4px;
        }

        .profile-email {
            font-size: 13px;
            color: #6b7280;
            margin-bottom: 8px;
        }

        .profile-badge {
            display: inline-block;
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
        }

        .profile-stats {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
            margin-bottom: 20px;
        }

        .stat-item {
            text-align: center;
            padding: 12px;
            background: #f9fafb;
            border-radius: 12px;
        }

        .stat-value {
            font-size: 20px;
            font-weight: 700;
            color: #1ec7e6;
            margin-bottom: 4px;
        }

        .stat-label {
            font-size: 11px;
            color: #6b7280;
            font-weight: 500;
        }

        .content {
            padding: 0 20px 100px 20px;
        }

        .section {
            margin-bottom: 20px;
        }

        .section-title {
            font-size: 13px;
            font-weight: 600;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 12px;
            padding-left: 4px;
        }

        .menu-list {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        }

        .menu-item {
            display: flex;
            align-items: center;
            padding: 16px;
            text-decoration: none;
            color: #374151;
            border-bottom: 1px solid #f3f4f6;
            transition: background-color 0.2s ease;
            cursor: pointer;
        }

        .menu-item:last-child {
            border-bottom: none;
        }

        .menu-item:hover {
            background-color: #f9fafb;
        }

        .menu-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            margin-right: 16px;
            flex-shrink: 0;
            color: white;
        }

        .menu-icon.edit {
            background: linear-gradient(135deg, #8b5cf6, #7c3aed);
        }

        .menu-icon.password {
            background: linear-gradient(135deg, #f59e0b, #d97706);
        }

        .menu-icon.notification {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
        }

        .menu-icon.help {
            background: linear-gradient(135deg, #06b6d4, #0891b2);
        }

        .menu-icon.contact {
            background: linear-gradient(135deg, #10b981, #059669);
        }

        .menu-icon.about {
            background: linear-gradient(135deg, #84cc16, #65a30d);
        }

        .menu-icon.settings {
            background: linear-gradient(135deg, #6b7280, #4b5563);
        }

        .menu-icon.logout {
            background: linear-gradient(135deg, #ef4444, #dc2626);
        }

        .menu-content {
            flex: 1;
        }

        .menu-label {
            font-size: 15px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 4px;
        }

        .menu-desc {
            font-size: 12px;
            color: #9ca3af;
        }

        .menu-arrow {
            color: #d1d5db;
            font-size: 18px;
            margin-left: 8px;
        }

        /* Bottom Navigation */
        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            width: 100%;
            max-width: 393px;
            margin: 0 auto;
            background: white;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.15);
            display: flex;
            justify-content: space-around;
            align-items: center;
            padding: 12px 0 16px 0;
            z-index: 9999;
            border-top: 1px solid #e5e7eb;
        }

        @media (min-width: 394px) {
            .bottom-nav {
                left: 50%;
                transform: translateX(-50%);
            }
        }

        .nav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            color: #9ca3af;
            font-size: 11px;
            padding: 4px 12px;
            transition: all 0.2s ease;
            cursor: pointer;
            font-weight: 500;
        }

        .nav-item.active {
            color: #1ec7e6;
        }

        .nav-item:hover {
            color: #1ec7e6;
        }

        .nav-icon {
            font-size: 24px;
            margin-bottom: 4px;
            line-height: 1;
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 10000;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .modal.show {
            display: flex;
        }

        .modal-content {
            background: white;
            border-radius: 16px;
            padding: 24px;
            width: 100%;
            max-width: 320px;
            text-align: center;
        }

        .modal-title {
            font-size: 18px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 12px;
        }

        .modal-message {
            font-size: 14px;
            color: #6b7280;
            margin-bottom: 24px;
            line-height: 1.5;
        }

        .modal-buttons {
            display: flex;
            gap: 12px;
        }

        .modal-btn {
            flex: 1;
            padding: 12px;
            border: none;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .modal-btn.cancel {
            background: #f3f4f6;
            color: #6b7280;
        }

        .modal-btn.cancel:hover {
            background: #e5e7eb;
        }

        .modal-btn.confirm {
            background: #ef4444;
            color: white;
        }

        .modal-btn.confirm:hover {
            background: #dc2626;
        }
    </style>
</head>
<body>
    <div class="header"></div>

    <div class="profile-card">
        <div class="profile-header">
            <div class="profile-photo" id="profilePhoto">👤</div>
            <div class="profile-info">
                <div class="profile-name" id="profileName">Nama Karyawan</div>
                <div class="profile-email" id="profileEmail">email@perusahaan.com</div>
                <span class="profile-badge" id="profileRole">Karyawan</span>
            </div>
        </div>

        <div class="profile-stats">
            <div class="stat-item">
                <div class="stat-value" id="statHadir">{{ $stats['hadir'] ?? 0 }}</div>
                <div class="stat-label">Hadir</div>
            </div>
            <div class="stat-item">
                <div class="stat-value" id="statTotalHari">{{ $stats['total_days'] ?? 0 }}</div>
                <div class="stat-label">Total Hari</div>
            </div>
            <div class="stat-item">
                <div class="stat-value" id="statTerlambat">{{ $stats['terlambat'] ?? 0 }}</div>
                <div class="stat-label">Terlambat</div>
            </div>
            <div class="stat-item">
                <div class="stat-value" id="statTotalJam">{{ number_format($stats['total_hours'] ?? 0, 2) }}h</div>
                <div class="stat-label">Total Jam</div>
            </div>
        </div>
    </div>

    <div class="content">
        <!-- Akun Section -->
        <div class="section">
            <div class="section-title">Akun</div>
            <div class="menu-list">
                <a href="edit-profile" class="menu-item">
                    <div class="menu-icon edit">✏️</div>
                    <div class="menu-content">
                        <div class="menu-label">Edit Profil</div>
                        <div class="menu-desc">Update informasi personal</div>
                    </div>
                    <div class="menu-arrow">›</div>
                </a>
                
                <a href="change-password" class="menu-item">
                    <div class="menu-icon password">🔒</div>
                    <div class="menu-content">
                        <div class="menu-label">Ubah Password</div>
                        <div class="menu-desc">Keamanan akun</div>
                    </div>
                    <div class="menu-arrow">›</div>
                </a>

                <a href="notification-settings" class="menu-item">
                    <div class="menu-icon notification">🔔</div>
                    <div class="menu-content">
                        <div class="menu-label">Notifikasi</div>
                        <div class="menu-desc">Pengaturan pemberitahuan</div>
                    </div>
                    <div class="menu-arrow">›</div>
                </a>
            </div>
        </div>

        <!-- Bantuan Section -->
        <div class="section">
            <div class="section-title">Bantuan</div>
            <div class="menu-list">
                <a href="help" class="menu-item">
                    <div class="menu-icon help">❓</div>
                    <div class="menu-content">
                        <div class="menu-label">Pusat Bantuan</div>
                        <div class="menu-desc">FAQ dan panduan</div>
                    </div>
                    <div class="menu-arrow">›</div>
                </a>
                
                <a href="contact" class="menu-item">
                    <div class="menu-icon contact">📞</div>
                    <div class="menu-content">
                        <div class="menu-label">Hubungi Support</div>
                        <div class="menu-desc">Dukungan teknis</div>
                    </div>
                    <div class="menu-arrow">›</div>
                </a>
                
                <a href="about" class="menu-item">
                    <div class="menu-icon about">ℹ️</div>
                    <div class="menu-content">
                        <div class="menu-label">Tentang Aplikasi</div>
                        <div class="menu-desc">Versi 1.0.0</div>
                    </div>
                    <div class="menu-arrow">›</div>
                </a>
            </div>
        </div>

        <!-- Lainnya Section -->
        <div class="section">
            <div class="section-title">Lainnya</div>
            <div class="menu-list">
                <a href="pengaturan" class="menu-item">
                    <div class="menu-icon settings">⚙️</div>
                    <div class="menu-content">
                        <div class="menu-label">Pengaturan</div>
                        <div class="menu-desc">Preferensi aplikasi</div>
                    </div>
                    <div class="menu-arrow">›</div>
                </a>

                <div class="menu-item" onclick="showLogoutModal()">
                    <div class="menu-icon logout">🚪</div>
                    <div class="menu-content">
                        <div class="menu-label" style="color: #ef4444;">Keluar</div>
                        <div class="menu-desc">Logout dari akun</div>
                    </div>
                    <div class="menu-arrow">›</div>
                </div>
            </div>
        </div>
    </div>

    <div class="bottom-nav">
        <a href="{{ route('dashboard') }}" class="nav-item">
            <div class="nav-icon">🏠</div>
            <div>Beranda</div>
        </a>
        <a href="{{ route('attendance.riwayat') }}" class="nav-item">
            <div class="nav-icon">📊</div>
            <div>History</div>
        </a>
        <a href="{{ route('activities.aktifitas') }}" class="nav-item">
            <div class="nav-icon">📈</div>
            <div>Aktivitas</div>
        </a>
        <a href="{{ route('profil') }}" class="nav-item active">
            <div class="nav-icon">👤</div>
            <div>Profile</div>
        </a>
    </div>

    <!-- Logout Modal -->
    <div class="modal" id="logoutModal">
        <div class="modal-content">
            <div class="modal-title">Keluar dari Akun?</div>
            <div class="modal-message">Apakah Anda yakin ingin keluar dari akun ini?</div>
            <div class="modal-buttons">
                <button class="modal-btn cancel" onclick="closeLogoutModal()">Batal</button>
                <button class="modal-btn confirm" onclick="confirmLogout()">Keluar</button>
            </div>
        </div>
    </div>

    <script>
        // Load profile data
        function loadProfileData() {
            const userProfile = JSON.parse(localStorage.getItem('userProfile') || '{}');
            
            // Set profile info
            if (userProfile.nama) {
                document.getElementById('profileName').textContent = userProfile.nama;
                // Get initials for photo
                const initials = userProfile.nama.split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase();
                document.getElementById('profilePhoto').textContent = initials;
            }
            
            if (userProfile.email) {
                document.getElementById('profileEmail').textContent = userProfile.email;
            }
            
            if (userProfile.jabatan) {
                document.getElementById('profileRole').textContent = userProfile.jabatan;
            }
            
            // Data kehadiran di-render server-side, tidak perlu load dari localStorage
        }

        // Fungsi loadAttendanceStats dihapus karena perhitungan dilakukan server-side

        // Logout Modal
        function showLogoutModal() {
            document.getElementById('logoutModal').classList.add('show');
        }

        function closeLogoutModal() {
            document.getElementById('logoutModal').classList.remove('show');
        }

        function confirmLogout() {
            // Clear user session
            localStorage.removeItem('userProfile');
            localStorage.removeItem('isLoggedIn');
            
            // Redirect to login
            window.location.href = 'login';
        }

        // Close modal when clicking outside
        document.getElementById('logoutModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeLogoutModal();
            }
        });

        // Load data on page load
        window.addEventListener('DOMContentLoaded', loadProfileData);
    </script>
</body>
</html>
