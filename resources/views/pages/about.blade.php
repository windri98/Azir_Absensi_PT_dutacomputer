<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="theme-color" content="#1ec7e6">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <title>Tentang Aplikasi - Sistem Absensi</title>
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
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .back-btn {
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
            margin-right: 16px;
        }

        .header-title {
            font-size: 18px;
            font-weight: 600;
        }

        .content {
            padding: 20px;
        }

        .app-logo {
            background: white;
            border-radius: 16px;
            padding: 32px;
            text-align: center;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        }

        .logo-icon {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, #1ec7e6, #0ea5e9);
            border-radius: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 48px;
            margin: 0 auto 16px;
            box-shadow: 0 8px 24px rgba(30, 199, 230, 0.3);
        }

        .app-name {
            font-size: 24px;
            font-weight: bold;
            color: #374151;
            margin-bottom: 8px;
        }

        .app-version {
            font-size: 14px;
            color: #6b7280;
            margin-bottom: 4px;
        }

        .app-tagline {
            font-size: 13px;
            color: #9ca3af;
            font-style: italic;
        }

        .info-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 16px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        }

        .info-title {
            font-size: 16px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .info-text {
            font-size: 14px;
            color: #6b7280;
            line-height: 1.7;
            text-align: justify;
        }

        .feature-list {
            list-style: none;
        }

        .feature-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            margin-bottom: 12px;
            padding: 12px;
            background: #f9fafb;
            border-radius: 8px;
        }

        .feature-icon {
            font-size: 24px;
            flex-shrink: 0;
        }

        .feature-text {
            flex: 1;
        }

        .feature-name {
            font-size: 14px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 4px;
        }

        .feature-desc {
            font-size: 13px;
            color: #6b7280;
        }

        .team-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
        }

        .team-card {
            background: #f9fafb;
            border-radius: 12px;
            padding: 16px;
            text-align: center;
        }

        .team-avatar {
            width: 64px;
            height: 64px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            margin: 0 auto 12px;
        }

        .team-name {
            font-size: 14px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 4px;
        }

        .team-role {
            font-size: 12px;
            color: #6b7280;
        }

        .stat-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            margin-top: 16px;
        }

        .stat-item {
            text-align: center;
            padding: 16px;
            background: #f9fafb;
            border-radius: 12px;
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

        .footer-links {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-top: 16px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        }

        .link-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 14px 0;
            border-bottom: 1px solid #f3f4f6;
            text-decoration: none;
            color: #374151;
        }

        .link-item:last-child {
            border-bottom: none;
        }

        .link-text {
            font-size: 14px;
            font-weight: 500;
        }

        .link-arrow {
            color: #9ca3af;
        }

        .copyright {
            text-align: center;
            font-size: 12px;
            color: #9ca3af;
            margin-top: 24px;
            padding: 16px;
        }
    </style>
</head>
<body>
    <div class="header">
        <button class="back-btn" onclick="goBack()">←</button>
        <div class="header-title">Tentang Aplikasi</div>
    </div>

    <div class="content">
        <div class="app-logo">
            <div class="logo-icon">📱</div>
            <div class="app-name">Sistem Absensi</div>
            <div class="app-version">Versi 1.0.0</div>
            <div class="app-tagline">"Kelola Kehadiran dengan Mudah"</div>
        </div>

        <div class="info-card">
            <div class="info-title">
                <span>📖</span> Tentang Aplikasi
            </div>
            <div class="info-text">
                Sistem Absensi adalah aplikasi berbasis web yang dirancang untuk memudahkan pengelolaan kehadiran karyawan secara digital. Dengan fitur-fitur modern dan user-friendly, aplikasi ini membantu perusahaan dalam memantau, mencatat, dan menganalisis data kehadiran karyawan secara real-time dan akurat.
            </div>
        </div>

        <div class="info-card">
            <div class="info-title">
                <span>✨</span> Fitur Utama
            </div>
            <ul class="feature-list">
                <li class="feature-item">
                    <div class="feature-icon">📸</div>
                    <div class="feature-text">
                        <div class="feature-name">Absensi Selfie</div>
                        <div class="feature-desc">Clock in/out dengan verifikasi foto selfie</div>
                    </div>
                </li>
                <li class="feature-item">
                    <div class="feature-icon">📍</div>
                    <div class="feature-text">
                        <div class="feature-name">GPS Location</div>
                        <div class="feature-desc">Verifikasi lokasi saat absensi</div>
                    </div>
                </li>
                <li class="feature-item">
                    <div class="feature-icon">📊</div>
                    <div class="feature-text">
                        <div class="feature-name">Laporan Real-time</div>
                        <div class="feature-desc">Monitor kehadiran secara langsung</div>
                    </div>
                </li>
                <li class="feature-item">
                    <div class="feature-icon">📝</div>
                    <div class="feature-text">
                        <div class="feature-name">Manajemen Izin</div>
                        <div class="feature-desc">Pengajuan dan persetujuan izin/cuti</div>
                    </div>
                </li>
                <li class="feature-item">
                    <div class="feature-icon">⏰</div>
                    <div class="feature-text">
                        <div class="feature-name">Lembur</div>
                        <div class="feature-desc">Pencatatan jam kerja lembur</div>
                    </div>
                </li>
                <li class="feature-item">
                    <div class="feature-icon">📈</div>
                    <div class="feature-text">
                        <div class="feature-name">Analytics</div>
                        <div class="feature-desc">Dashboard dan statistik lengkap</div>
                    </div>
                </li>
            </ul>
        </div>

        <div class="info-card">
            <div class="info-title">
                <span>🎯</span> Visi & Misi
            </div>
            <div class="info-text">
                <strong>Visi:</strong> Menjadi solusi terdepan dalam digitalisasi sistem kehadiran karyawan yang efisien dan terpercaya.
                <br><br>
                <strong>Misi:</strong> Menyediakan platform yang mudah digunakan, aman, dan dapat diandalkan untuk membantu perusahaan mengelola kehadiran karyawan dengan lebih baik.
            </div>
        </div>

        <div class="info-card">
            <div class="info-title">
                <span>📊</span> Statistik
            </div>
            <div class="stat-grid">
                <div class="stat-item">
                    <div class="stat-number">1K+</div>
                    <div class="stat-label">Pengguna</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">50+</div>
                    <div class="stat-label">Perusahaan</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">99.9%</div>
                    <div class="stat-label">Uptime</div>
                </div>
            </div>
        </div>

        <div class="info-card">
            <div class="info-title">
                <span>👥</span> Tim Pengembang
            </div>
            <div class="team-grid">
                <div class="team-card">
                    <div class="team-avatar">👨‍💻</div>
                    <div class="team-name">Ahmad Rizki</div>
                    <div class="team-role">Lead Developer</div>
                </div>
                <div class="team-card">
                    <div class="team-avatar" style="background: linear-gradient(135deg, #f093fb, #f5576c);">👩‍💻</div>
                    <div class="team-name">Siti Nurhaliza</div>
                    <div class="team-role">UI/UX Designer</div>
                </div>
                <div class="team-card">
                    <div class="team-avatar" style="background: linear-gradient(135deg, #4ade80, #22c55e);">👨‍💼</div>
                    <div class="team-name">Budi Santoso</div>
                    <div class="team-role">Product Manager</div>
                </div>
                <div class="team-card">
                    <div class="team-avatar" style="background: linear-gradient(135deg, #fbbf24, #f59e0b);">👩‍💼</div>
                    <div class="team-name">Dewi Lestari</div>
                    <div class="team-role">QA Tester</div>
                </div>
            </div>
        </div>

        <div class="footer-links">
            <a href="help" class="link-item">
                <span class="link-text">❓ Bantuan & FAQ</span>
                <span class="link-arrow">→</span>
            </a>
            <a href="contact" class="link-item">
                <span class="link-text">📞 Hubungi Kami</span>
                <span class="link-arrow">→</span>
            </a>
            <a href="#" class="link-item" onclick="alert('Syarat & Ketentuan akan segera tersedia')">
                <span class="link-text">📄 Syarat & Ketentuan</span>
                <span class="link-arrow">→</span>
            </a>
            <a href="#" class="link-item" onclick="alert('Kebijakan Privasi akan segera tersedia')">
                <span class="link-text">🔒 Kebijakan Privasi</span>
                <span class="link-arrow">→</span>
            </a>
            <a href="#" class="link-item" onclick="alert('Lisensi Open Source akan segera tersedia')">
                <span class="link-text">⚖️ Lisensi</span>
                <span class="link-arrow">→</span>
            </a>
        </div>

        <div class="copyright">
            © 2025 Sistem Absensi. All rights reserved.<br>
            Made with ❤️ in Indonesia
        </div>
    </div>

    <script>
        function goBack() {
            if (typeof smartGoBack === 'function') {
                smartGoBack('{{ route("dashboard") }}');
            } else {
                // Fallback navigation
                if (window.history.length > 1 && document.referrer && 
                    document.referrer !== window.location.href &&
                    !document.referrer.includes('login')) {
                    try {
                        window.history.back();
                    } catch (error) {
                        window.location.href = '{{ route("dashboard") }}';
                    }
                } else {
                    window.location.href = '{{ route("dashboard") }}';
                }
            }
        }
    </script>
</body>
</html>
