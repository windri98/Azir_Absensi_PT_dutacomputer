<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="theme-color" content="#1ec7e6">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <title>Pengaturan - Sistem Absensi</title>
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
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 16px;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .back-btn {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            font-size: 20px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .header-title {
            flex: 1;
        }

        .header-title h1 {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 4px;
        }

        .header-title p {
            font-size: 13px;
            opacity: 0.9;
        }

        .content {
            padding: 20px;
            padding-bottom: 100px;
        }

        .section {
            margin-bottom: 24px;
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
        }

        .menu-icon.profile {
            background: linear-gradient(135deg, #8b5cf6, #7c3aed);
            color: white;
        }

        .menu-icon.password {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: white;
        }

        .menu-icon.notification {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            color: white;
        }

        .menu-icon.language {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
        }

        .menu-icon.theme {
            background: linear-gradient(135deg, #ec4899, #db2777);
            color: white;
        }

        .menu-icon.privacy {
            background: linear-gradient(135deg, #6366f1, #4f46e5);
            color: white;
        }

        .menu-icon.help {
            background: linear-gradient(135deg, #06b6d4, #0891b2);
            color: white;
        }

        .menu-icon.about {
            background: linear-gradient(135deg, #84cc16, #65a30d);
            color: white;
        }

        .menu-icon.logout {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
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

        .toggle-switch {
            position: relative;
            width: 48px;
            height: 28px;
            margin-left: 8px;
        }

        .toggle-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #e5e7eb;
            transition: 0.3s;
            border-radius: 28px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 20px;
            width: 20px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: 0.3s;
            border-radius: 50%;
        }

        input:checked + .slider {
            background-color: #1ec7e6;
        }

        input:checked + .slider:before {
            transform: translateX(20px);
        }

        .version-info {
            text-align: center;
            padding: 24px 20px;
            color: #9ca3af;
            font-size: 13px;
        }

        .version-info .app-name {
            font-weight: 600;
            color: #6b7280;
            margin-bottom: 4px;
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
    <div class="header">
        <button class="back-btn" onclick="window.location.href='{{ route('profile.show') }}'">←</button>
        <div class="header-title">
            <h1>Pengaturan</h1>
            <p>Kelola preferensi aplikasi</p>
        </div>
    </div>

    <div class="content">
        <!-- Akun Section -->
        <div class="section">
            <div class="section-title">Akun</div>
            <div class="menu-list">
                <a href="edit-profile" class="menu-item">
                    <div class="menu-icon profile">👤</div>
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
            </div>
        </div>

        <!-- Preferensi Section -->
        <div class="section">
            <div class="section-title">Preferensi</div>
            <div class="menu-list">
                <a href="notification-settings" class="menu-item">
                    <div class="menu-icon notification">🔔</div>
                    <div class="menu-content">
                        <div class="menu-label">Notifikasi</div>
                        <div class="menu-desc">Pengaturan pemberitahuan</div>
                    </div>
                    <div class="menu-arrow">›</div>
                </a>
                
                <div class="menu-item" onclick="toggleDarkMode()">
                    <div class="menu-icon theme">🌙</div>
                    <div class="menu-content">
                        <div class="menu-label">Mode Gelap</div>
                        <div class="menu-desc">Tampilan tema</div>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" id="darkModeToggle">
                        <span class="slider"></span>
                    </label>
                </div>
                
                <div class="menu-item">
                    <div class="menu-icon language">🌐</div>
                    <div class="menu-content">
                        <div class="menu-label">Bahasa</div>
                        <div class="menu-desc">Indonesia</div>
                    </div>
                    <div class="menu-arrow">›</div>
                </div>
            </div>
        </div>

        <!-- Privasi & Keamanan Section -->
        <div class="section">
            <div class="section-title">Privasi & Keamanan</div>
            <div class="menu-list">
                <div class="menu-item">
                    <div class="menu-icon privacy">🛡️</div>
                    <div class="menu-content">
                        <div class="menu-label">Privasi</div>
                        <div class="menu-desc">Pengaturan privasi data</div>
                    </div>
                    <div class="menu-arrow">›</div>
                </div>
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
                    <div class="menu-icon help">📞</div>
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

        <!-- Logout Section -->
        <div class="section">
            <div class="menu-list">
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

        <div class="version-info">
            <div class="app-name">Sistem Absensi Mobile</div>
            <div>Version 1.0.0 (Build 2025)</div>
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
        <a href="{{ route('profile.show') }}" class="nav-item active">
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
        // Dark Mode Toggle
        function toggleDarkMode() {
            const toggle = document.getElementById('darkModeToggle');
            const isDarkMode = toggle.checked;
            
            // Save preference
            localStorage.setItem('darkMode', isDarkMode ? 'enabled' : 'disabled');
            
            // Apply dark mode (to be implemented)
            if (isDarkMode) {
                alert('Mode gelap akan segera hadir! 🌙');
            }
        }

        // Load dark mode preference
        window.addEventListener('DOMContentLoaded', function() {
            const darkMode = localStorage.getItem('darkMode');
            if (darkMode === 'enabled') {
                document.getElementById('darkModeToggle').checked = true;
            }
        });

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

        // Prevent toggle from triggering parent click
        document.getElementById('darkModeToggle').addEventListener('click', function(e) {
            e.stopPropagation();
        });
    </script>
</body>
</html>
