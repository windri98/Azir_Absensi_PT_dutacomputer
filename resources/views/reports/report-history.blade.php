<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="theme-color" content="#1ec7e6">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <title>Riwayat Laporan - Sistem Absensi</title>
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
            padding-bottom: 80px;
        }
        
        @media (min-width: 394px) {
            body {
                box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            }
        }

        /* Header */
        .header {
            background: linear-gradient(135deg, #1ec7e6, #0ea5e9);
            color: white;
            padding: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .header-left {
            display: flex;
            align-items: center;
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

        .add-btn {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        /* Content */
        .content {
            padding: 20px;
        }

        /* Stats */
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
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        }

        .stat-number {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 4px;
        }

        .stat-number.pending { color: #f59e0b; }
        .stat-number.process { color: #3b82f6; }
        .stat-number.resolved { color: #10b981; }

        .stat-label {
            font-size: 12px;
            color: #6b7280;
        }

        /* Filter Tabs */
        .filter-tabs {
            display: flex;
            gap: 8px;
            margin-bottom: 20px;
            overflow-x: auto;
            padding-bottom: 5px;
        }

        .filter-tabs::-webkit-scrollbar {
            height: 4px;
        }

        .filter-tabs::-webkit-scrollbar-thumb {
            background: #d1d5db;
            border-radius: 4px;
        }

        .filter-tab {
            background: white;
            border: 2px solid #e5e7eb;
            color: #6b7280;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            white-space: nowrap;
            transition: all 0.2s ease;
        }

        .filter-tab.active {
            background: #1ec7e6;
            border-color: #1ec7e6;
            color: white;
        }

        /* Report List */
        .report-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .report-card {
            background: white;
            border-radius: 12px;
            padding: 16px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .report-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .report-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 12px;
        }

        .report-ticket {
            font-size: 12px;
            color: #6b7280;
            font-family: monospace;
        }

        .status-badge {
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
        }

        .status-badge.pending {
            background: #fef3c7;
            color: #d97706;
        }

        .status-badge.process {
            background: #dbeafe;
            color: #2563eb;
        }

        .status-badge.resolved {
            background: #d1fae5;
            color: #059669;
        }

        .status-badge.rejected {
            background: #fee2e2;
            color: #dc2626;
        }

        .report-title {
            font-size: 15px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px;
        }

        .report-meta {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
            margin-bottom: 8px;
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 4px;
            font-size: 12px;
            color: #6b7280;
        }

        .priority-indicator {
            width: 8px;
            height: 8px;
            border-radius: 50%;
        }

        .priority-indicator.low { background: #10b981; }
        .priority-indicator.medium { background: #f59e0b; }
        .priority-indicator.high { background: #ef4444; }

        .report-description {
            font-size: 13px;
            color: #6b7280;
            line-height: 1.5;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
        }

        .empty-icon {
            font-size: 64px;
            margin-bottom: 16px;
            opacity: 0.3;
        }

        .empty-text {
            font-size: 16px;
            color: #6b7280;
            margin-bottom: 8px;
        }

        .empty-subtext {
            font-size: 14px;
            color: #9ca3af;
            margin-bottom: 24px;
        }

        .empty-btn {
            background: #1ec7e6;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
        }

        /* Detail Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            align-items: flex-end;
            justify-content: center;
        }

        .modal.show {
            display: flex;
        }

        .modal-content {
            background: white;
            border-radius: 20px 20px 0 0;
            width: 100%;
            max-width: 393px;
            max-height: 80vh;
            overflow-y: auto;
            animation: slideUp 0.3s ease;
        }

        @keyframes slideUp {
            from {
                transform: translateY(100%);
            }
            to {
                transform: translateY(0);
            }
        }

        .modal-header {
            padding: 20px;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            background: white;
            z-index: 10;
        }

        .modal-title {
            font-size: 18px;
            font-weight: 600;
            color: #374151;
        }

        .close-btn {
            background: #f3f4f6;
            border: none;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            font-size: 20px;
            cursor: pointer;
            color: #6b7280;
        }

        .modal-body {
            padding: 20px;
        }

        .detail-section {
            margin-bottom: 20px;
        }

        .detail-label {
            font-size: 12px;
            color: #6b7280;
            margin-bottom: 6px;
            font-weight: 600;
        }

        .detail-value {
            font-size: 14px;
            color: #374151;
        }

        .detail-description {
            font-size: 14px;
            color: #374151;
            line-height: 1.6;
            white-space: pre-wrap;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-left">
            <button class="back-btn" onclick="goBack()">←</button>
            <div class="header-title">Riwayat Laporan</div>
        </div>
        <button class="add-btn" onclick="createNewReport()">
            <span>+</span> Buat
        </button>
    </div>

    <div class="content">
        <!-- Stats -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number pending" id="statPending">0</div>
                <div class="stat-label">Pending</div>
            </div>
            <div class="stat-card">
                <div class="stat-number process" id="statProcess">0</div>
                <div class="stat-label">Diproses</div>
            </div>
            <div class="stat-card">
                <div class="stat-number resolved" id="statResolved">0</div>
                <div class="stat-label">Selesai</div>
            </div>
        </div>

        <!-- Filter Tabs -->
        <div class="filter-tabs">
            <button class="filter-tab active" onclick="filterReports('all')">Semua</button>
            <button class="filter-tab" onclick="filterReports('pending')">Pending</button>
            <button class="filter-tab" onclick="filterReports('process')">Diproses</button>
            <button class="filter-tab" onclick="filterReports('resolved')">Selesai</button>
            <button class="filter-tab" onclick="filterReports('rejected')">Ditolak</button>
        </div>

        <!-- Report List -->
        <div class="report-list" id="reportList">
            <!-- Reports will be loaded here -->
        </div>

        <!-- Empty State -->
        <div class="empty-state" id="emptyState" style="display: none;">
            <div class="empty-icon">📋</div>
            <div class="empty-text">Belum Ada Laporan</div>
            <div class="empty-subtext">Buat laporan pertama Anda untuk melaporkan masalah atau memberikan feedback</div>
            <button class="empty-btn" onclick="createNewReport()">Buat Laporan</button>
        </div>
    </div>

    <!-- Detail Modal -->
    <div class="modal" id="detailModal">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">Detail Laporan</div>
                <button class="close-btn" onclick="closeModal()">×</button>
            </div>
            <div class="modal-body" id="modalBody">
                <!-- Detail will be loaded here -->
            </div>
        </div>
    </div>

    <script>
        let currentFilter = 'all';

        function loadReports() {
            const reports = JSON.parse(localStorage.getItem('customerReports') || '[]');
            
            // Update stats
            document.getElementById('statPending').textContent = reports.filter(r => r.status === 'pending').length;
            document.getElementById('statProcess').textContent = reports.filter(r => r.status === 'process').length;
            document.getElementById('statResolved').textContent = reports.filter(r => r.status === 'resolved').length;

            // Filter reports
            const filteredReports = currentFilter === 'all' 
                ? reports 
                : reports.filter(r => r.status === currentFilter);

            const reportList = document.getElementById('reportList');
            const emptyState = document.getElementById('emptyState');

            if (filteredReports.length === 0) {
                reportList.innerHTML = '';
                emptyState.style.display = 'block';
            } else {
                emptyState.style.display = 'none';
                reportList.innerHTML = filteredReports.map(report => `
                    <div class="report-card" onclick='showDetail(${JSON.stringify(report).replace(/'/g, "&#39;")})'>
                        <div class="report-header">
                            <div class="report-ticket">${report.ticketNumber}</div>
                            <div class="status-badge ${report.status}">
                                ${getStatusText(report.status)}
                            </div>
                        </div>
                        <div class="report-title">${report.title}</div>
                        <div class="report-meta">
                            <div class="meta-item">
                                <span>📁</span>
                                ${getCategoryText(report.category)}
                            </div>
                            <div class="meta-item">
                                <div class="priority-indicator ${report.priority}"></div>
                                ${getPriorityText(report.priority)}
                            </div>
                            <div class="meta-item">
                                <span>📅</span>
                                ${formatDate(report.timestamp)}
                            </div>
                        </div>
                        <div class="report-description">${report.description}</div>
                    </div>
                `).join('');
            }
        }

        function filterReports(status) {
            currentFilter = status;
            
            // Update active tab
            document.querySelectorAll('.filter-tab').forEach(tab => {
                tab.classList.remove('active');
            });
            event.target.classList.add('active');
            
            loadReports();
        }

        function showDetail(report) {
            const modalBody = document.getElementById('modalBody');
            modalBody.innerHTML = `
                <div class="detail-section">
                    <div class="detail-label">Nomor Tiket</div>
                    <div class="detail-value" style="font-family: monospace; font-weight: 600;">${report.ticketNumber}</div>
                </div>
                
                <div class="detail-section">
                    <div class="detail-label">Status</div>
                    <div class="status-badge ${report.status}">${getStatusText(report.status)}</div>
                </div>
                
                <div class="detail-section">
                    <div class="detail-label">Kategori</div>
                    <div class="detail-value">${getCategoryText(report.category)}</div>
                </div>
                
                <div class="detail-section">
                    <div class="detail-label">Judul</div>
                    <div class="detail-value" style="font-weight: 600;">${report.title}</div>
                </div>
                
                <div class="detail-section">
                    <div class="detail-label">Deskripsi</div>
                    <div class="detail-description">${report.description}</div>
                </div>
                
                <div class="detail-section">
                    <div class="detail-label">Prioritas</div>
                    <div class="detail-value">
                        <span class="priority-indicator ${report.priority}" style="display: inline-block; vertical-align: middle;"></span>
                        ${getPriorityText(report.priority)}
                    </div>
                </div>
                
                ${report.incidentDate ? `
                <div class="detail-section">
                    <div class="detail-label">Tanggal Kejadian</div>
                    <div class="detail-value">${formatDate(report.incidentDate)}</div>
                </div>
                ` : ''}
                
                ${report.file ? `
                <div class="detail-section">
                    <div class="detail-label">Lampiran</div>
                    <div class="detail-value">📎 ${report.file}</div>
                </div>
                ` : ''}
                
                <div class="detail-section">
                    <div class="detail-label">Tanggal Laporan</div>
                    <div class="detail-value">${formatDateTime(report.timestamp)}</div>
                </div>
                
                ${report.email ? `
                <div class="detail-section">
                    <div class="detail-label">Email</div>
                    <div class="detail-value">${report.email}</div>
                </div>
                ` : ''}
            `;
            
            document.getElementById('detailModal').classList.add('show');
        }

        function closeModal() {
            document.getElementById('detailModal').classList.remove('show');
        }

        function getStatusText(status) {
            const statusMap = {
                'pending': 'Menunggu',
                'process': 'Diproses',
                'resolved': 'Selesai',
                'rejected': 'Ditolak'
            };
            return statusMap[status] || status;
        }

        function getCategoryText(category) {
            const categoryMap = {
                'absensi': 'Masalah Absensi',
                'sistem': 'Error Sistem',
                'izin': 'Pengajuan Izin/Cuti',
                'gaji': 'Pertanyaan Gaji',
                'jadwal': 'Masalah Jadwal',
                'lembur': 'Lembur',
                'feedback': 'Feedback & Saran',
                'lainnya': 'Lainnya'
            };
            return categoryMap[category] || category;
        }

        function getPriorityText(priority) {
            const priorityMap = {
                'low': 'Rendah',
                'medium': 'Sedang',
                'high': 'Tinggi'
            };
            return priorityMap[priority] || priority;
        }

        function formatDate(dateString) {
            const date = new Date(dateString);
            const options = { day: 'numeric', month: 'short', year: 'numeric' };
            return date.toLocaleDateString('id-ID', options);
        }

        function formatDateTime(dateString) {
            const date = new Date(dateString);
            const dateOptions = { day: 'numeric', month: 'short', year: 'numeric' };
            const timeOptions = { hour: '2-digit', minute: '2-digit' };
            return date.toLocaleDateString('id-ID', dateOptions) + ', ' + 
                   date.toLocaleTimeString('id-ID', timeOptions);
        }

        function createNewReport() {
            window.location.href = 'customer-report';
        }

        function goBack() {
            window.history.back();
        }

        // Close modal when clicking outside
        document.getElementById('detailModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });

        // Load reports on page load
        window.addEventListener('DOMContentLoaded', loadReports);
    </script>
</body>
</html>
