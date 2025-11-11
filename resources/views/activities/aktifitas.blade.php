<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="theme-color" content="#1ec7e6">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <title>Aktivitas - Sistem Absensi</title>
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
            padding-bottom: env(safe-area-inset-bottom, 0px);
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
            pointer-events: auto;
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
            position: relative;
            z-index: 1001;
            transition: all 0.3s ease;
            user-select: none;
            pointer-events: auto;
            -webkit-tap-highlight-color: transparent;
        }
        .back-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: scale(1.05);
        }
        .back-btn:active {
            transform: scale(0.95);
        }
        .back-btn:focus {
            outline: 2px solid rgba(255, 255, 255, 0.5);
            outline-offset: 2px;
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

        .menu-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
            margin-bottom: 24px;
        }

        .menu-card {
            background: white;
            border-radius: 16px;
            padding: 20px;
            text-align: center;
            text-decoration: none;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            transition: all 0.2s ease;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 12px;
            min-height: 140px;
            justify-content: center;
        }

        .menu-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12);
        }

        .menu-icon {
            width: 60px;
            height: 60px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            margin-bottom: 4px;
        }

        .menu-card:nth-child(1) .menu-icon {
            background: linear-gradient(135deg, #ec4899, #db2777);
            color: white;
        }

        .menu-card:nth-child(2) .menu-icon {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            color: white;
        }

        .menu-card:nth-child(3) .menu-icon {
            background: linear-gradient(135deg, #8b5cf6, #7c3aed);
            color: white;
        }

        .menu-card:nth-child(4) .menu-icon {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
        }

        .menu-card:nth-child(5) .menu-icon {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: white;
        }

        .menu-card:nth-child(6) .menu-icon {
            background: linear-gradient(135deg, #06b6d4, #0891b2);
            color: white;
        }

        .menu-title {
            font-size: 15px;
            font-weight: 600;
            color: #374151;
            margin: 0;
        }

        .menu-subtitle {
            font-size: 12px;
            color: #6b7280;
            margin: 0;
        }

        .section-title {
            font-size: 16px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .recent-activity {
            background: white;
            border-radius: 16px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        }

        .activity-item {
            display: flex;
            gap: 12px;
            padding: 12px 0;
            border-bottom: 1px solid #f3f4f6;
        }

        .activity-item:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .activity-item:first-child {
            padding-top: 0;
        }

        .activity-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            flex-shrink: 0;
        }

        .activity-icon.absen {
            background: linear-gradient(135deg, #1ec7e6, #0ea5e9);
            color: white;
        }

        .activity-icon.izin {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: white;
        }

        .activity-icon.laporan {
            background: linear-gradient(135deg, #8b5cf6, #7c3aed);
            color: white;
        }

        .activity-icon.keluhan {
            background: linear-gradient(135deg, #ec4899, #db2777);
            color: white;
        }

        .activity-content {
            flex: 1;
        }

        .activity-title {
            font-size: 14px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 4px;
        }

        .activity-desc {
            font-size: 12px;
            color: #6b7280;
            margin-bottom: 4px;
        }

        .activity-time {
            font-size: 11px;
            color: #9ca3af;
        }

        .empty-state {
            text-align: center;
            padding: 40px 20px;
        }

        .empty-icon {
            font-size: 64px;
            margin-bottom: 12px;
            opacity: 0.5;
        }

        .empty-text {
            font-size: 14px;
            color: #6b7280;
        }

        /* Bottom Navigation */
        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            width: 100%;
            background: white;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-around;
            align-items: center;
            padding: 8px 0;
            padding-bottom: calc(8px + env(safe-area-inset-bottom, 0px));
            z-index: 9999;
            border-top: 1px solid #e5e7eb;
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
            justify-content: center;
            text-decoration: none;
            color: #9ca3af;
            font-size: 10px;
            padding: 6px 8px;
            transition: all 0.2s ease;
            cursor: pointer;
            font-weight: 500;
            min-width: 60px;
        }

        .nav-item.active {
            color: #1ec7e6;
        }

        .nav-item:hover {
            color: #1ec7e6;
        }

        .nav-icon {
            font-size: 22px;
            margin-bottom: 2px;
            line-height: 1;
        }
    </style>
</head>
<body>
    <div class="header">
        <button class="back-btn" onclick="goBack()">←</button>
        <div class="header-title">
            <h1>Aktivitas</h1>
            <p>Menu aktivitas karyawan</p>
        </div>
    </div>

    <div class="content">
        <div class="menu-grid">
            <a href="{{ route('attendance.riwayat') }}" class="menu-card">
                <div class="menu-icon">📊</div>
                <div>
                    <div class="menu-title">Riwayat Absensi</div>
                    <div class="menu-subtitle">Lihat rekam jejak</div>
                </div>
            </a>
            
            <a href="{{ route('activities.izin') }}" class="menu-card">
                <div class="menu-icon">📝</div>
                <div>
                    <div class="menu-title">Pengajuan Izin</div>
                    <div class="menu-subtitle">Cuti dan izin</div>
                </div>
            </a>
            
            @if(auth()->check() && (auth()->user()->hasAnyRole(['admin', 'manager']) || auth()->user()->can('reports.view')))
            <a href="{{ route('reports.index') }}" class="menu-card">
                <div class="menu-icon">📈</div>
                <div>
                    <div class="menu-title">Laporan</div>
                    <div class="menu-subtitle">Analisa kehadiran</div>
                </div>
            </a>
            @else
            <a href="{{ route('reports.history') }}" class="menu-card">
                <div class="menu-icon">📈</div>
                <div>
                    <div class="menu-title">Riwayat Laporan</div>
                    <div class="menu-subtitle">Laporan pribadi</div>
                </div>
            </a>
            @endif
            
            <a href="{{ route('complaints.form') }}" class="menu-card">
                <div class="menu-icon">🔧</div>
                <div>
                    <div class="menu-title">Keluhan</div>
                    <div class="menu-subtitle">Laporkan masalah</div>
                </div>
            </a>
            
            @if(auth()->check() && (auth()->user()->hasAnyRole(['admin', 'manager']) || auth()->user()->can('reports.view')))
            <a href="{{ route('reports.customer') }}" class="menu-card">
                <div class="menu-icon">💬</div>
                <div>
                    <div class="menu-title">Laporan Customer</div>
                    <div class="menu-subtitle">Form khusus</div>
                </div>
            </a>
            @else
            <a href="{{ route('absensi') }}" class="menu-card">
                <div class="menu-icon">📱</div>
                <div>
                    <div class="menu-title">Absensi</div>
                    <div class="menu-subtitle">Clock in/out</div>
                </div>
            </a>
            @endif
            
            @if(auth()->check() && auth()->user()->hasAnyRole(['admin', 'manager']))
            <a href="{{ route('management.shift') }}" class="menu-card">
                <div class="menu-icon">🕐</div>
                <div>
                    <div class="menu-title">Shift</div>
                    <div class="menu-subtitle">Kelola jadwal</div>
                </div>
            </a>
            @else
            <a href="{{ route('profile.show') }}" class="menu-card">
                <div class="menu-icon">👤</div>
                <div>
                    <div class="menu-title">Profile</div>
                    <div class="menu-subtitle">Kelola profil</div>
                </div>
            </a>
            @endif
        </div>

        <div class="section-title">
            ⚡ Aktivitas Terakhir
        </div>

        <div class="recent-activity" id="activityList">
            <!-- Will be populated by JavaScript -->
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
        <a href="{{ route('activities.aktifitas') }}" class="nav-item active">
            <div class="nav-icon">📈</div>
            <div>Aktivitas</div>
        </a>
        <a href="{{ route('profile.show') }}" class="nav-item">
            <div class="nav-icon">👤</div>
            <div>Profile</div>
        </a>
    </div>

    <script>
        function loadRecentActivity() {
            const activityList = document.getElementById('activityList');
            
            // Combine activities from different sources
            const activities = [];
            
            // Add some sample activities if no data exists
            const today = new Date();
            const yesterday = new Date(today);
            yesterday.setDate(yesterday.getDate() - 1);
            
            // Sample recent activities
            activities.push({
                type: 'absen',
                icon: '✓',
                title: 'Clock In',
                desc: 'Absen masuk hari ini',
                time: today.toISOString(),
                priority: 1
            });
            
            activities.push({
                type: 'izin',
                icon: '📝',
                title: 'Pengajuan Izin',
                desc: 'Izin sakit - Menunggu persetujuan',
                time: yesterday.toISOString(),
                priority: 2
            });
            
            activities.push({
                type: 'laporan',
                icon: '📊',
                title: 'Lihat Laporan',
                desc: 'Mengecek laporan kehadiran bulan ini',
                time: yesterday.toISOString(),
                priority: 3
            });
            
            // Get attendance history from storage
            const attendanceHistory = JSON.parse(localStorage.getItem('attendanceHistory') || '[]');
            attendanceHistory.slice(0, 3).forEach(record => {
                activities.push({
                    type: 'absen',
                    icon: '✓',
                    title: record.type === 'in' ? 'Clock In' : 'Clock Out',
                    desc: `${record.location || 'Lokasi kantor'}`,
                    time: record.timestamp || new Date().toISOString(),
                    priority: 1
                });
            });
            
            // Get izin/cuti
            const izinData = JSON.parse(localStorage.getItem('izinData') || '[]');
            izinData.slice(0, 2).forEach(izin => {
                activities.push({
                    type: 'izin',
                    icon: '📋',
                    title: `${izin.type === 'izin' ? 'Izin' : 'Cuti'} - ${izin.status}`,
                    desc: izin.alasan,
                    time: izin.tanggalMulai,
                    priority: 2
                });
            });
            
            // Get complaints
            const complaints = JSON.parse(localStorage.getItem('complaints') || '[]');
            complaints.slice(0, 2).forEach(complaint => {
                activities.push({
                    type: 'keluhan',
                    icon: '🔧',
                    title: `Keluhan ${complaint.status}`,
                    desc: complaint.title,
                    time: complaint.createdAt,
                    priority: 3
                });
            });
            
            // Get customer reports
            const reports = JSON.parse(localStorage.getItem('customerReports') || '[]');
            reports.slice(0, 2).forEach(report => {
                activities.push({
                    type: 'laporan',
                    icon: '📝',
                    title: `Laporan Customer - ${report.status}`,
                    desc: report.subject,
                    time: report.createdAt,
                    priority: 4
                });
            });
            
            // Sort by time
            activities.sort((a, b) => new Date(b.time) - new Date(a.time));
            
            if (activities.length === 0) {
                activityList.innerHTML = `
                    <div class="empty-state">
                        <div class="empty-icon">📭</div>
                        <div class="empty-text">Belum ada aktivitas terbaru</div>
                    </div>
                `;
            } else {
                activityList.innerHTML = activities.slice(0, 10).map(activity => `
                    <div class="activity-item">
                        <div class="activity-icon ${activity.type}">
                            ${activity.icon}
                        </div>
                        <div class="activity-content">
                            <div class="activity-title">${activity.title}</div>
                            <div class="activity-desc">${activity.desc}</div>
                            <div class="activity-time">${formatDateTime(activity.time)}</div>
                        </div>
                    </div>
                `).join('');
            }
        }
        
        function formatDateTime(dateString) {
            if (!dateString) return 'Invalid Date';
            
            const date = new Date(dateString);
            
            // Check if date is valid
            if (isNaN(date.getTime())) {
                return 'Invalid Date';
            }
            
            const now = new Date();
            const diff = now - date;
            const days = Math.floor(diff / (1000 * 60 * 60 * 24));
            const hours = Math.floor(diff / (1000 * 60 * 60));
            const minutes = Math.floor(diff / (1000 * 60));
            
            if (minutes < 1) return 'Baru saja';
            if (minutes < 60) return `${minutes} menit yang lalu`;
            if (hours < 24) return `${hours} jam yang lalu`;
            if (days < 7) return `${days} hari yang lalu`;
            
            return date.toLocaleDateString('id-ID', { 
                day: 'numeric', 
                month: 'short', 
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        }
        
        // Fallback function if popup.js fails to load
        if (typeof smartGoBack === 'undefined') {
            function smartGoBack(fallbackUrl) {
                if (window.history.length > 1 && document.referrer && 
                    document.referrer !== window.location.href &&
                    !document.referrer.includes('login')) {
                    try {
                        window.history.back();
                    } catch (error) {
                        window.location.href = fallbackUrl;
                    }
                } else {
                    window.location.href = fallbackUrl;
                }
            }
        }

        function goBack() {
            smartGoBack('{{ route("dashboard") }}');
        }
        
        window.addEventListener('DOMContentLoaded', loadRecentActivity);
    </script>

    <script src="{{ asset('components/popup.js') }}"></script>
</body>
</html>
