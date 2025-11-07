<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Absensi - Sistem Absensi</title>
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
        
        /* Header */
        .header {
            background: linear-gradient(135deg, #1ec7e6, #0ea5e9);
            color: white;
            padding: 50px 20px 30px 20px;
            position: sticky;
            top: 0;
            z-index: 10;
        }
        .header-content {
            display: flex;
            align-items: center;
            gap: 15px;
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
        }
        .header-title {
            flex: 1;
        }
        .header-title h1 {
            font-size: 20px;
            font-weight: 600;
        }
        .header-title p {
            font-size: 14px;
            opacity: 0.8;
            margin-top: 4px;
        }
        
        /* Filter Section */
        .filter-section {
            background: white;
            padding: 20px;
            margin: 0 20px 20px 20px;
            border-radius: 16px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
            margin-top: -10px;
            position: relative;
            z-index: 5;
        }
        .filter-tabs {
            display: flex;
            gap: 8px;
            margin-bottom: 16px;
        }
        .filter-tab {
            padding: 8px 16px;
            border: 1px solid #e5e7eb;
            border-radius: 20px;
            background: white;
            color: #6b7280;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .filter-tab.active {
            background: #1ec7e6;
            color: white;
            border-color: #1ec7e6;
        }
        .date-range {
            display: flex;
            gap: 12px;
        }
        .date-input {
            flex: 1;
            padding: 10px 12px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            font-size: 14px;
        }
        
        /* Summary Cards */
        .summary-section {
            padding: 0 20px 20px 20px;
        }
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
            margin-bottom: 20px;
        }
        .summary-card {
            background: white;
            padding: 16px;
            border-radius: 12px;
            text-align: center;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        }
        .summary-number {
            font-size: 24px;
            font-weight: bold;
            color: #1ec7e6;
            margin-bottom: 4px;
        }
        .summary-label {
            font-size: 12px;
            color: #6b7280;
        }
        
        /* History List */
        .history-section {
            padding: 0 20px;
        }
        .section-title {
            font-size: 16px;
            font-weight: 600;
            color: #333;
            margin-bottom: 12px;
        }
        .history-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        .history-card {
            background: white;
            border-radius: 12px;
            padding: 16px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
            transition: transform 0.2s ease;
        }
        .history-card:hover {
            transform: translateY(-2px);
        }
        .history-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
        }
        .history-date {
            font-size: 14px;
            font-weight: 600;
            color: #333;
        }
        .history-status {
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 500;
        }
        .status-present {
            background: #dcfce7;
            color: #16a34a;
        }
        .status-late {
            background: #fef3c7;
            color: #d97706;
        }
        .status-absent {
            background: #fee2e2;
            color: #dc2626;
        }
        .history-details {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
        }
        .detail-item {
            text-align: center;
        }
        .detail-label {
            font-size: 11px;
            color: #6b7280;
            margin-bottom: 4px;
        }
        .detail-value {
            font-size: 14px;
            font-weight: 600;
            color: #333;
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
        
        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #6b7280;
        }
        .empty-icon {
            font-size: 48px;
            margin-bottom: 16px;
            opacity: 0.5;
        }
        .empty-text {
            font-size: 16px;
            margin-bottom: 8px;
        }
        .empty-subtext {
            font-size: 14px;
            opacity: 0.7;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-content">
            <button class="back-btn" onclick="goBack()">←</button>
            <div class="header-title">
                <h1>Riwayat Absensi</h1>
                <p>Lihat rekam jejak kehadiran Anda</p>
            </div>
        </div>
    </div>

    <div class="filter-section">
        <div class="filter-tabs">
            <button class="filter-tab active" onclick="filterBy('week')">Minggu Ini</button>
            <button class="filter-tab" onclick="filterBy('month')">Bulan Ini</button>
            <button class="filter-tab" onclick="filterBy('custom')">Custom</button>
        </div>
        <div class="date-range" id="dateRange" style="display: none;">
            <input type="date" class="date-input" id="startDate">
            <input type="date" class="date-input" id="endDate">
        </div>
    </div>

    <div class="summary-section">
        <div class="summary-grid">
            <div class="summary-card">
                <div class="summary-number" id="totalDays">0</div>
                <div class="summary-label">Total Hari</div>
            </div>
            <div class="summary-card">
                <div class="summary-number" id="presentDays">0</div>
                <div class="summary-label">Hadir</div>
            </div>
            <div class="summary-card">
                <div class="summary-number" id="lateDays">0</div>
                <div class="summary-label">Terlambat</div>
            </div>
            <div class="summary-card">
                <div class="summary-number" id="totalHours">0h</div>
                <div class="summary-label">Total Jam</div>
            </div>
        </div>
    </div>

    <div class="history-section">
        <h3 class="section-title">Detail Kehadiran</h3>
        <div class="history-list" id="historyList">
            <!-- History items will be populated here -->
        </div>
        
        <div class="empty-state" id="emptyState" style="display: none;">
            <div class="empty-icon">📅</div>
            <div class="empty-text">Belum ada data absensi</div>
            <div class="empty-subtext">Mulai catat kehadiran Anda hari ini</div>
        </div>
    </div>

    <!-- Bottom Navigation -->
    <nav class="bottom-nav">
        <a href="dashboard" class="nav-item">
            <span class="nav-icon">🏠</span>
            <span class="nav-label">Home</span>
        </a>
        <a href="riwayat" class="nav-item active">
            <span class="nav-icon">📊</span>
            <span class="nav-label">History</span>
        </a>
        <a href="laporan" class="nav-item">
            <span class="nav-icon">📈</span>
            <span class="nav-label">Reports</span>
        </a>
        <a href="profile" class="nav-item">
            <span class="nav-icon">👤</span>
            <span class="nav-label">Profile</span>
        </a>
    </nav>

    <script src="components/popup.js"></script>
    <script>
        let currentFilter = 'week';

        function goBack() {
            window.location.href = 'dashboard';
        }

        function filterBy(period) {
            currentFilter = period;
            
            // Update active tab
            document.querySelectorAll('.filter-tab').forEach(tab => {
                tab.classList.remove('active');
            });
            event.target.classList.add('active');
            
            // Show/hide date range
            const dateRange = document.getElementById('dateRange');
            if (period === 'custom') {
                dateRange.style.display = 'flex';
            } else {
                dateRange.style.display = 'none';
            }
            
            loadHistoryData();
        }

        function loadHistoryData() {
            const attendanceHistory = JSON.parse(localStorage.getItem('attendanceHistory') || '[]');
            const historyList = document.getElementById('historyList');
            const emptyState = document.getElementById('emptyState');
            
            // Filter data based on selected period
            let filteredData = filterDataByPeriod(attendanceHistory, currentFilter);
            
            if (filteredData.length === 0) {
                historyList.style.display = 'none';
                emptyState.style.display = 'block';
                return;
            }
            
            historyList.style.display = 'block';
            emptyState.style.display = 'none';
            
            // Group data by date
            const groupedData = groupByDate(filteredData);
            
            // Update summary
            updateSummary(groupedData);
            
            // Render history items
            renderHistoryItems(groupedData);
        }

        function filterDataByPeriod(data, period) {
            const now = new Date();
            let startDate, endDate;
            
            switch (period) {
                case 'week':
                    startDate = new Date(now.setDate(now.getDate() - now.getDay()));
                    endDate = new Date();
                    break;
                case 'month':
                    startDate = new Date(now.getFullYear(), now.getMonth(), 1);
                    endDate = new Date();
                    break;
                case 'custom':
                    const startInput = document.getElementById('startDate').value;
                    const endInput = document.getElementById('endDate').value;
                    if (!startInput || !endInput) return data;
                    startDate = new Date(startInput);
                    endDate = new Date(endInput);
                    break;
                default:
                    return data;
            }
            
            return data.filter(record => {
                const recordDate = new Date(record.date);
                return recordDate >= startDate && recordDate <= endDate;
            });
        }

        function groupByDate(data) {
            const grouped = {};
            
            data.forEach(record => {
                const dateStr = new Date(record.date).toDateString();
                if (!grouped[dateStr]) {
                    grouped[dateStr] = [];
                }
                grouped[dateStr].push(record);
            });
            
            return grouped;
        }

        function updateSummary(groupedData) {
            const totalDays = Object.keys(groupedData).length;
            let presentDays = 0;
            let lateDays = 0;
            let totalMinutes = 0;
            
            Object.values(groupedData).forEach(dayRecords => {
                const clockIn = dayRecords.find(r => r.type === 'clock-in');
                const clockOut = dayRecords.find(r => r.type === 'clock-out');
                
                if (clockIn) {
                    presentDays++;
                    
                    // Check if late (after 08:00)
                    const clockInTime = new Date(`1970-01-01 ${clockIn.time}`);
                    const standardTime = new Date(`1970-01-01 08:00`);
                    if (clockInTime > standardTime) {
                        lateDays++;
                    }
                    
                    // Calculate working hours
                    if (clockOut) {
                        const start = new Date(`1970-01-01 ${clockIn.time}`);
                        const end = new Date(`1970-01-01 ${clockOut.time}`);
                        totalMinutes += (end - start) / (1000 * 60);
                    }
                }
            });
            
            const totalHours = Math.floor(totalMinutes / 60);
            
            document.getElementById('totalDays').textContent = totalDays;
            document.getElementById('presentDays').textContent = presentDays;
            document.getElementById('lateDays').textContent = lateDays;
            document.getElementById('totalHours').textContent = `${totalHours}h`;
        }

        function renderHistoryItems(groupedData) {
            const historyList = document.getElementById('historyList');
            historyList.innerHTML = '';
            
            Object.entries(groupedData)
                .sort(([a], [b]) => new Date(b) - new Date(a))
                .forEach(([dateStr, records]) => {
                    const clockIn = records.find(r => r.type === 'clock-in');
                    const clockOut = records.find(r => r.type === 'clock-out');
                    
                    const date = new Date(dateStr);
                    const formattedDate = date.toLocaleDateString('id-ID', {
                        weekday: 'long',
                        day: '2-digit',
                        month: 'long',
                        year: 'numeric'
                    });
                    
                    // Determine status
                    let status = 'absent';
                    let statusText = 'Tidak Hadir';
                    if (clockIn) {
                        const clockInTime = new Date(`1970-01-01 ${clockIn.time}`);
                        const standardTime = new Date(`1970-01-01 08:00`);
                        status = clockInTime > standardTime ? 'late' : 'present';
                        statusText = clockInTime > standardTime ? 'Terlambat' : 'Hadir';
                    }
                    
                    // Calculate working duration
                    let workingTime = '--:--';
                    if (clockIn && clockOut) {
                        const start = new Date(`1970-01-01 ${clockIn.time}`);
                        const end = new Date(`1970-01-01 ${clockOut.time}`);
                        const diff = end - start;
                        const hours = Math.floor(diff / (1000 * 60 * 60));
                        const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                        workingTime = `${hours}h ${minutes}m`;
                    }
                    
                    const historyCard = `
                        <div class="history-card">
                            <div class="history-header">
                                <div class="history-date">${formattedDate}</div>
                                <div class="history-status status-${status}">${statusText}</div>
                            </div>
                            <div class="history-details">
                                <div class="detail-item">
                                    <div class="detail-label">Masuk</div>
                                    <div class="detail-value">${clockIn ? clockIn.time : '--:--'}</div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label">Keluar</div>
                                    <div class="detail-value">${clockOut ? clockOut.time : '--:--'}</div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label">Durasi</div>
                                    <div class="detail-value">${workingTime}</div>
                                </div>
                            </div>
                        </div>
                    `;
                    
                    historyList.insertAdjacentHTML('beforeend', historyCard);
                });
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Check if user is logged in
            const userSession = localStorage.getItem('userSession');
            if (!userSession) {
                window.location.href = 'welcome';
                return;
            }
            
            loadHistoryData();
        });
    </script>
</body>
</html>