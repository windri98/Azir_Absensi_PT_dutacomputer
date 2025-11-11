<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="theme-color" content="#1ec7e6">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <title>Hubungi Kami - Sistem Absensi</title>
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

        .hero-card {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border-radius: 16px;
            padding: 32px 24px;
            text-align: center;
            margin-bottom: 24px;
            box-shadow: 0 8px 24px rgba(102, 126, 234, 0.3);
        }

        .hero-icon {
            font-size: 64px;
            margin-bottom: 16px;
        }

        .hero-title {
            font-size: 22px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .hero-text {
            font-size: 14px;
            opacity: 0.9;
            line-height: 1.6;
        }

        .section-title {
            font-size: 16px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 16px;
        }

        .contact-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            display: flex;
            align-items: flex-start;
            gap: 16px;
        }

        .contact-icon-wrapper {
            width: 56px;
            height: 56px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            flex-shrink: 0;
        }

        .contact-icon-wrapper.email {
            background: linear-gradient(135deg, #667eea, #764ba2);
        }

        .contact-icon-wrapper.phone {
            background: linear-gradient(135deg, #f093fb, #f5576c);
        }

        .contact-icon-wrapper.whatsapp {
            background: linear-gradient(135deg, #4ade80, #22c55e);
        }

        .contact-icon-wrapper.location {
            background: linear-gradient(135deg, #fbbf24, #f59e0b);
        }

        .contact-info {
            flex: 1;
        }

        .contact-label {
            font-size: 13px;
            color: #6b7280;
            margin-bottom: 4px;
        }

        .contact-value {
            font-size: 15px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px;
        }

        .contact-action {
            background: #1ec7e6;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }

        .contact-action:hover {
            background: #0ea5e9;
        }

        .office-hours {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-top: 24px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        }

        .hours-title {
            font-size: 16px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .hours-item {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #f3f4f6;
        }

        .hours-item:last-child {
            border-bottom: none;
        }

        .hours-day {
            font-size: 14px;
            color: #6b7280;
        }

        .hours-time {
            font-size: 14px;
            font-weight: 600;
            color: #374151;
        }

        .hours-time.closed {
            color: #ef4444;
        }

        .social-media {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-top: 16px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        }

        .social-title {
            font-size: 16px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 16px;
        }

        .social-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
        }

        .social-btn {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 16px 8px;
            border-radius: 12px;
            text-decoration: none;
            color: white;
            font-size: 12px;
            font-weight: 600;
            transition: transform 0.2s ease;
        }

        .social-btn:hover {
            transform: translateY(-4px);
        }

        .social-btn.instagram {
            background: linear-gradient(135deg, #f093fb, #f5576c);
        }

        .social-btn.facebook {
            background: linear-gradient(135deg, #4267B2, #3b5998);
        }

        .social-btn.twitter {
            background: linear-gradient(135deg, #1DA1F2, #0c85d0);
        }

        .social-btn.linkedin {
            background: linear-gradient(135deg, #0077B5, #006399);
        }

        .social-icon {
            font-size: 32px;
            margin-bottom: 8px;
        }
    </style>
</head>
<body>
    <div class="header">
        <button class="back-btn" onclick="goBack()">←</button>
        <div class="header-title">Hubungi Kami</div>
    </div>

    <div class="content">
        <div class="hero-card">
            <div class="hero-icon">💬</div>
            <div class="hero-title">Kami Siap Membantu!</div>
            <div class="hero-text">
                Tim support kami tersedia untuk menjawab pertanyaan dan membantu menyelesaikan masalah Anda.
            </div>
        </div>

        <div class="section-title">Kontak Kami</div>

        <div class="contact-card">
            <div class="contact-icon-wrapper email">📧</div>
            <div class="contact-info">
                <div class="contact-label">Email Support</div>
                <div class="contact-value">lorem@absensi.com</div>
                <a href="mailto:support@absensi.com" class="contact-action">Kirim Email</a>
            </div>
        </div>

        <div class="contact-card">
            <div class="contact-icon-wrapper phone">📱</div>
            <div class="contact-info">
                <div class="contact-label">Telepon / SMS</div>
                <div class="contact-value">+62 812-****-****</div>
                <a href="tel:+6281234567890" class="contact-action">Hubungi</a>
            </div>
        </div>

        <div class="contact-card">
            <div class="contact-icon-wrapper whatsapp">💬</div>
            <div class="contact-info">
                <div class="contact-label">WhatsApp</div>
                <div class="contact-value">+62 812-****-****</div>
                <a href="https://wa.me/6281234567890?text=Halo,%20saya%20butuh%20bantuan%20dengan%20aplikasi%20absensi" target="_blank" class="contact-action">Chat WhatsApp</a>
            </div>
        </div>

        <div class="contact-card">
            <div class="contact-icon-wrapper location">📍</div>
            <div class="contact-info">
                <div class="contact-label">Alamat Kantor</div>
                <div class="contact-value">Jl. Sudirman No. 123, Jakarta Pusat 10220</div>
                <a href="https://maps.google.com/?q=-6.208763,106.845599" target="_blank" class="contact-action">Buka Maps</a>
            </div>
        </div>

        <div class="office-hours">
            <div class="hours-title">
                <span>🕐</span> Jam Operasional
            </div>
            <div class="hours-item">
                <div class="hours-day">Senin - Jumat</div>
                <div class="hours-time">08:00 - 17:00 WIB</div>
            </div>
            <div class="hours-item">
                <div class="hours-day">Sabtu</div>
                <div class="hours-time">09:00 - 14:00 WIB</div>
            </div>
            <div class="hours-item">
                <div class="hours-day">Minggu & Libur</div>
                <div class="hours-time closed">Tutup</div>
            </div>
        </div>

        <div class="social-media">
            <div class="social-title">Ikuti Kami</div>
            <div class="social-grid">
                <a href="https://instagram.com" target="_blank" class="social-btn instagram">
                    <div class="social-icon">📷</div>
                    <div>Instagram</div>
                </a>
                <a href="https://facebook.com" target="_blank" class="social-btn facebook">
                    <div class="social-icon">📘</div>
                    <div>Facebook</div>
                </a>
                <a href="https://twitter.com" target="_blank" class="social-btn twitter">
                    <div class="social-icon">🐦</div>
                    <div>Twitter</div>
                </a>
                <a href="https://linkedin.com" target="_blank" class="social-btn linkedin">
                    <div class="social-icon">💼</div>
                    <div>LinkedIn</div>
                </a>
            </div>
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
