<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="theme-color" content="#1ec7e6">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <title>Laporan - Sistem Absensi</title>
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

        /* Content */
        .content {
            padding: 20px;
        }

        /* Filter Section */
        .filter-section {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        }

        .filter-title {
            font-size: 16px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 16px;
        }

        .filter-group {
            margin-bottom: 16px;
        }

        .filter-group:last-child {
            margin-bottom: 0;
        }

        .filter-label {
            display: block;
            font-size: 13px;
            color: #6b7280;
            margin-bottom: 8px;
            font-weight: 500;
        }

        .filter-select,
        .filter-input {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 14px;
            background-color: white;
            cursor: pointer;
        }

        .filter-select:focus,
        .filter-input:focus {
            outline: none;
            border-color: #1ec7e6;
            box-shadow: 0 0 0 3px rgba(30, 199, 230, 0.1);
        }

        .date-range {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        .filter-buttons {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-top: 20px;
        }

        .btn {
            padding: 12px 20px;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-reset {
            background: #f3f4f6;
            color: #6b7280;
        }

        .btn-reset:hover {
            background: #e5e7eb;
        }

        .btn-apply {
            background: #1ec7e6;
            color: white;
        }

        .btn-apply:hover {
            background: #0ea5e9;
        }

        /* Summary Cards */
        .summary-section {
            margin-bottom: 20px;
        }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
        }

        .summary-card {
            background: white;
            border-radius: 12px;
            padding: 16px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        }

        .summary-card-header {
            display: flex;
            align-items: center;
            margin-bottom: 12px;
        }

        .summary-icon {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            margin-right: 10px;
        }

        .summary-icon.blue { background: #dbeafe; }
        .summary-icon.green { background: #dcfce7; }
        .summary-icon.orange { background: #fed7aa; }
        .summary-icon.red { background: #fee2e2; }

        .summary-value {
            font-size: 24px;
            font-weight: bold;
            color: #374151;
            margin-bottom: 4px;
        }

        .summary-label {
            font-size: 12px;
            color: #6b7280;
        }

        /* Report Section */
        .report-section {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        }

        .report-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
        }

        .report-title {
            font-size: 16px;
            font-weight: 600;
            color: #374151;
        }

        .export-btn {
            background: #1ec7e6;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .export-btn:hover {
            background: #0ea5e9;
        }

        /* Report Table */
        .report-table {
            width: 100%;
            border-collapse: collapse;
        }

        .report-table th {
            background: #f9fafb;
            padding: 10px 8px;
            text-align: left;
            font-size: 12px;
            font-weight: 600;
            color: #6b7280;
            border-bottom: 1px solid #e5e7eb;
        }

        .report-table td {
            padding: 12px 8px;
            font-size: 13px;
            color: #374151;
            border-bottom: 1px solid #f3f4f6;
        }

        .report-table tr:last-child td {
            border-bottom: none;
        }

        .status-badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 600;
            display: inline-block;
        }

        .status-badge.hadir {
            background: #dcfce7;
            color: #16a34a;
        }

        .status-badge.terlambat {
            background: #fed7aa;
            color: #ea580c;
        }

        .status-badge.izin {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .status-badge.alpha {
            background: #fee2e2;
            color: #dc2626;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 40px 20px;
        }

        .empty-icon {
            font-size: 48px;
            margin-bottom: 16px;
            opacity: 0.3;
        }

        .empty-text {
            font-size: 14px;
            color: #6b7280;
        }

        /* Loading */
        .loading {
            text-align: center;
            padding: 40px 20px;
            display: none;
        }

        .loading.show {
            display: block;
        }

        .loading-spinner {
            width: 40px;
            height: 40px;
            border: 4px solid #f3f4f6;
            border-top: 4px solid #1ec7e6;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto 16px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .loading-text {
            font-size: 14px;
            color: #6b7280;
        }

        /* Bottom Navigation */
        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100%;
            max-width: 393px;
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
    <div class="header">
        <button class="back-btn" onclick="goBack()">←</button>
        <div class="header-title">Laporan Absensi</div>
    </div>

    <div class="content">
        <!-- Filter Section -->
        <div class="filter-section">
            <div class="filter-title">Filter Laporan</div>
            
            <div class="filter-group">
                <label class="filter-label">Periode</label>
                <select class="filter-select" id="periodFilter">
                    <option value="today">Hari Ini</option>
                    <option value="week">Minggu Ini</option>
                    <option value="month" selected>Bulan Ini</option>
                    <option value="custom">Custom</option>
                </select>
            </div>

            <div class="filter-group" id="dateRangeGroup" style="display: none;">
                <label class="filter-label">Rentang Tanggal</label>
                <div class="date-range">
                    <input type="date" class="filter-input" id="startDate">
                    <input type="date" class="filter-input" id="endDate">
                </div>
            </div>

            <div class="filter-group">
                <label class="filter-label">Status Kehadiran</label>
                <select class="filter-select" id="statusFilter">
                    <option value="all">Semua Status</option>
                    <option value="hadir">Hadir</option>
                    <option value="terlambat">Terlambat</option>
                    <option value="izin">Izin</option>
                    <option value="alpha">Alpha</option>
                </select>
            </div>

            <div class="filter-buttons">
                <button class="btn btn-reset" onclick="resetFilters()">Reset</button>
                <button class="btn btn-apply" onclick="applyFilters()">Terapkan</button>
            </div>
        </div>

        <!-- Summary Section -->
        <div class="summary-section">
            <div class="summary-grid">
                <div class="summary-card">
                    <div class="summary-card-header">
                        <div class="summary-icon blue">✓</div>
                    </div>
                    <div class="summary-value" id="totalHadir">22</div>
                    <div class="summary-label">Hadir</div>
                </div>
                <div class="summary-card">
                    <div class="summary-card-header">
                        <div class="summary-icon orange">⏰</div>
                    </div>
                    <div class="summary-value" id="totalTerlambat">2</div>
                    <div class="summary-label">Terlambat</div>
                </div>
                <div class="summary-card">
                    <div class="summary-card-header">
                        <div class="summary-icon green">📝</div>
                    </div>
                    <div class="summary-value" id="totalIzin">1</div>
                    <div class="summary-label">Izin</div>
                </div>
                <div class="summary-card">
                    <div class="summary-card-header">
                        <div class="summary-icon red">✗</div>
                    </div>
                    <div class="summary-value" id="totalAlpha">0</div>
                    <div class="summary-label">Alpha</div>
                </div>
            </div>
        </div>

        <!-- Loading State -->
        <div class="loading" id="loadingState">
            <div class="loading-spinner"></div>
            <div class="loading-text">Memuat laporan...</div>
        </div>

        <!-- Report Section -->
        <div class="report-section" id="reportSection">
            <div class="report-header">
                <div class="report-title">Detail Laporan</div>
                <button class="export-btn" onclick="exportReport()">
                    <span>📥</span> Export
                </button>
            </div>

            <div style="overflow-x: auto;">
                <table class="report-table">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Masuk</th>
                            <th>Keluar</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody id="reportTableBody">
                        <tr>
                            <td>01 Nov 2025</td>
                            <td>08:00</td>
                            <td>17:00</td>
                            <td><span class="status-badge hadir">Hadir</span></td>
                        </tr>
                        <tr>
                            <td>31 Okt 2025</td>
                            <td>08:15</td>
                            <td>17:05</td>
                            <td><span class="status-badge terlambat">Terlambat</span></td>
                        </tr>
                        <tr>
                            <td>30 Okt 2025</td>
                            <td>08:00</td>
                            <td>17:00</td>
                            <td><span class="status-badge hadir">Hadir</span></td>
                        </tr>
                        <tr>
                            <td>29 Okt 2025</td>
                            <td>-</td>
                            <td>-</td>
                            <td><span class="status-badge izin">Izin</span></td>
                        </tr>
                        <tr>
                            <td>28 Okt 2025</td>
                            <td>08:05</td>
                            <td>17:00</td>
                            <td><span class="status-badge hadir">Hadir</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Empty State (hidden by default) -->
        <div class="report-section" id="emptyState" style="display: none;">
            <div class="empty-state">
                <div class="empty-icon">📊</div>
                <div class="empty-text">Tidak ada data untuk ditampilkan</div>
            </div>
        </div>
    </div>

    <!-- Bottom Navigation -->
    <div class="bottom-nav">
        <a href="dashboard" class="nav-item">
            <div class="nav-icon">🏠</div>
            <div class="nav-label">Home</div>
        </a>
        <a href="riwayat" class="nav-item">
            <div class="nav-icon">📊</div>
            <div class="nav-label">Riwayat</div>
        </a>
        <a href="laporan" class="nav-item active">
            <div class="nav-icon">📈</div>
            <div class="nav-label">Laporan</div>
        </a>
        <a href="profile" class="nav-item">
            <div class="nav-icon">👤</div>
            <div class="nav-label">Profile</div>
        </a>
    </div>

    <script>
        // Period filter change handler
        document.getElementById('periodFilter').addEventListener('change', function() {
            const dateRangeGroup = document.getElementById('dateRangeGroup');
            if (this.value === 'custom') {
                dateRangeGroup.style.display = 'block';
                setDefaultDateRange();
            } else {
                dateRangeGroup.style.display = 'none';
            }
        });

        function setDefaultDateRange() {
            const today = new Date();
            const lastMonth = new Date(today.getFullYear(), today.getMonth() - 1, today.getDate());
            
            document.getElementById('endDate').value = today.toISOString().split('T')[0];
            document.getElementById('startDate').value = lastMonth.toISOString().split('T')[0];
        }

        function resetFilters() {
            document.getElementById('periodFilter').value = 'month';
            document.getElementById('statusFilter').value = 'all';
            document.getElementById('dateRangeGroup').style.display = 'none';
            applyFilters();
        }

        function applyFilters() {
            // Show loading
            document.getElementById('loadingState').classList.add('show');
            document.getElementById('reportSection').style.display = 'none';
            document.getElementById('emptyState').style.display = 'none';

            // Simulate API call
            setTimeout(() => {
                // Hide loading
                document.getElementById('loadingState').classList.remove('show');
                
                // Show results
                const hasData = true; // Change this based on actual data
                if (hasData) {
                    document.getElementById('reportSection').style.display = 'block';
                    generateReportData();
                } else {
                    document.getElementById('emptyState').style.display = 'block';
                }
            }, 1000);
        }

        function generateReportData() {
            // Sample data generation
            const period = document.getElementById('periodFilter').value;
            const status = document.getElementById('statusFilter').value;
            
            // Update summary based on filters
            let hadir = 22, terlambat = 2, izin = 1, alpha = 0;
            
            if (status === 'hadir') {
                terlambat = 0; izin = 0; alpha = 0;
            } else if (status === 'terlambat') {
                hadir = 0; izin = 0; alpha = 0;
            } else if (status === 'izin') {
                hadir = 0; terlambat = 0; alpha = 0;
            } else if (status === 'alpha') {
                hadir = 0; terlambat = 0; izin = 0;
            }
            
            document.getElementById('totalHadir').textContent = hadir;
            document.getElementById('totalTerlambat').textContent = terlambat;
            document.getElementById('totalIzin').textContent = izin;
            document.getElementById('totalAlpha').textContent = alpha;
        }

        function exportReport() {
            // Simulate export
            const period = document.getElementById('periodFilter').value;
            const periodText = document.getElementById('periodFilter').options[document.getElementById('periodFilter').selectedIndex].text;
            
            alert(`Laporan "${periodText}" sedang disiapkan untuk di-export.\n\nFormat: Excel (XLSX)\n\nFile akan diunduh dalam beberapa detik.`);
            
            // In real implementation, this would trigger actual file download
            // For example: window.location.href = '/api/export-report?period=' + period;
        }

        function goBack() {
            window.history.back();
        }

        // Initialize
        window.addEventListener('DOMContentLoaded', function() {
            // Load any saved filters from localStorage
            const savedFilters = localStorage.getItem('reportFilters');
            if (savedFilters) {
                const filters = JSON.parse(savedFilters);
                document.getElementById('periodFilter').value = filters.period || 'month';
                document.getElementById('statusFilter').value = filters.status || 'all';
            }
        });
    </script>
</body>
</html>
