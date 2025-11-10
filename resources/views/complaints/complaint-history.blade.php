<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="theme-color" content="#1ec7e6">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <title>Riwayat Keluhan - Sistem Absensi</title>
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
        }

        .content {
            padding: 20px;
        }

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
        .stat-number.done { color: #10b981; }

        .stat-label {
            font-size: 12px;
            color: #6b7280;
        }

        .filter-tabs {
            display: flex;
            gap: 8px;
            margin-bottom: 20px;
            overflow-x: auto;
            padding-bottom: 5px;
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

        .complaint-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .complaint-card {
            background: white;
            border-radius: 12px;
            padding: 16px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .complaint-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .complaint-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 12px;
        }

        .ticket-number {
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

        .status-badge.done {
            background: #d1fae5;
            color: #059669;
        }

        .complaint-title {
            font-size: 15px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px;
        }

        .complaint-meta {
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

        .priority-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
        }

        .priority-dot.low { background: #10b981; }
        .priority-dot.medium { background: #f59e0b; }
        .priority-dot.high { background: #ef4444; }

        .complaint-description {
            font-size: 13px;
            color: #6b7280;
            line-height: 1.5;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

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
            from { transform: translateY(100%); }
            to { transform: translateY(0); }
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

        .timeline {
            margin-top: 20px;
        }

        .timeline-item {
            display: flex;
            gap: 12px;
            margin-bottom: 16px;
            position: relative;
        }

        .timeline-item:not(:last-child)::before {
            content: '';
            position: absolute;
            left: 11px;
            top: 30px;
            bottom: -16px;
            width: 2px;
            background: #e5e7eb;
        }

        .timeline-dot {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 12px;
        }

        .timeline-dot.pending { background: #fef3c7; color: #d97706; }
        .timeline-dot.process { background: #dbeafe; color: #2563eb; }
        .timeline-dot.done { background: #d1fae5; color: #059669; }

        .timeline-content {
            flex: 1;
        }

        .timeline-title {
            font-size: 14px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 4px;
        }

        .timeline-time {
            font-size: 12px;
            color: #6b7280;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-left">
            <button class="back-btn" onclick="goBack()">←</button>
            <div class="header-title">Riwayat Keluhan</div>
        </div>
        <button class="add-btn" onclick="createComplaint()">+ Buat</button>
    </div>

    <div class="content">
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
                <div class="stat-number done" id="statDone">0</div>
                <div class="stat-label">Selesai</div>
            </div>
        </div>

        <div class="filter-tabs">
            <button class="filter-tab active" onclick="filterComplaints('all', this)">Semua</button>
            <button class="filter-tab" onclick="filterComplaints('pending', this)">Pending</button>
            <button class="filter-tab" onclick="filterComplaints('process', this)">Diproses</button>
            <button class="filter-tab" onclick="filterComplaints('done', this)">Selesai</button>
        </div>

        <div class="complaint-list" id="complaintList"></div>

        <div class="empty-state" id="emptyState" style="display: none;">
            <div class="empty-icon">📋</div>
            <div class="empty-text">Belum Ada Keluhan</div>
            <div class="empty-subtext">Buat keluhan pertama Anda untuk melaporkan masalah teknis</div>
            <button class="empty-btn" onclick="createComplaint()">Buat Keluhan</button>
        </div>
    </div>

    <div class="modal" id="detailModal">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">Detail Keluhan</div>
                <button class="close-btn" onclick="closeModal()">×</button>
            </div>
            <div class="modal-body" id="modalBody"></div>
        </div>
    </div>

    <script>
        let currentFilter = 'all';

        function loadComplaints() {
            const complaints = JSON.parse(localStorage.getItem('complaints') || '[]');
            
            // Update stats
            document.getElementById('statPending').textContent = complaints.filter(c => c.status === 'pending').length;
            document.getElementById('statProcess').textContent = complaints.filter(c => c.status === 'process').length;
            document.getElementById('statDone').textContent = complaints.filter(c => c.status === 'done').length;

            // Filter
            const filteredComplaints = currentFilter === 'all' 
                ? complaints 
                : complaints.filter(c => c.status === currentFilter);

            const complaintList = document.getElementById('complaintList');
            const emptyState = document.getElementById('emptyState');

            if (filteredComplaints.length === 0) {
                complaintList.innerHTML = '';
                emptyState.style.display = 'block';
            } else {
                emptyState.style.display = 'none';
                complaintList.innerHTML = filteredComplaints.map(complaint => `
                    <div class="complaint-card" onclick='showDetail(${JSON.stringify(complaint).replace(/'/g, "&#39;")})'>
                        <div class="complaint-header">
                            <div class="ticket-number">${complaint.ticketNumber}</div>
                            <div class="status-badge ${complaint.status}">
                                ${getStatusText(complaint.status)}
                            </div>
                        </div>
                        <div class="complaint-title">${complaint.title}</div>
                        <div class="complaint-meta">
                            <div class="meta-item">
                                <span>📁</span>
                                ${getCategoryText(complaint.category)}
                            </div>
                            <div class="meta-item">
                                <div class="priority-dot ${complaint.priority}"></div>
                                ${getPriorityText(complaint.priority)}
                            </div>
                            <div class="meta-item">
                                <span>📅</span>
                                ${formatDate(complaint.createdAt)}
                            </div>
                        </div>
                        <div class="complaint-description">${complaint.description}</div>
                    </div>
                `).join('');
            }
        }

        function filterComplaints(status, element) {
            currentFilter = status;
            document.querySelectorAll('.filter-tab').forEach(tab => tab.classList.remove('active'));
            element.classList.add('active');
            loadComplaints();
        }

        function showDetail(complaint) {
            const modalBody = document.getElementById('modalBody');
            modalBody.innerHTML = `
                <div class="detail-section">
                    <div class="detail-label">Nomor Tiket</div>
                    <div class="detail-value" style="font-family: monospace; font-weight: 600;">${complaint.ticketNumber}</div>
                </div>
                
                <div class="detail-section">
                    <div class="detail-label">Status</div>
                    <div class="status-badge ${complaint.status}">${getStatusText(complaint.status)}</div>
                </div>
                
                <div class="detail-section">
                    <div class="detail-label">Kategori</div>
                    <div class="detail-value">${getCategoryText(complaint.category)}</div>
                </div>
                
                <div class="detail-section">
                    <div class="detail-label">Judul</div>
                    <div class="detail-value" style="font-weight: 600;">${complaint.title}</div>
                </div>
                
                <div class="detail-section">
                    <div class="detail-label">Deskripsi</div>
                    <div class="detail-description">${complaint.description}</div>
                </div>
                
                <div class="detail-section">
                    <div class="detail-label">Urgensi</div>
                    <div class="detail-value">
                        <span class="priority-dot ${complaint.priority}" style="display: inline-block; vertical-align: middle;"></span>
                        ${getPriorityText(complaint.priority)}
                    </div>
                </div>
                
                ${complaint.location ? `
                <div class="detail-section">
                    <div class="detail-label">Lokasi</div>
                    <div class="detail-value">${complaint.location}</div>
                </div>
                ` : ''}
                
                ${complaint.file ? `
                <div class="detail-section">
                    <div class="detail-label">Lampiran</div>
                    <div class="detail-value">📷 ${complaint.file}</div>
                </div>
                ` : ''}
                
                <div class="detail-section">
                    <div class="detail-label">Tanggal Laporan</div>
                    <div class="detail-value">${formatDateTime(complaint.createdAt)}</div>
                </div>
                
                ${complaint.technician ? `
                <div class="detail-section">
                    <div class="detail-label">Teknisi</div>
                    <div class="detail-value">👨‍🔧 ${complaint.technician}</div>
                </div>
                ` : ''}
                
                ${complaint.notes ? `
                <div class="detail-section">
                    <div class="detail-label">Catatan Teknisi</div>
                    <div class="detail-description">${complaint.notes}</div>
                </div>
                ` : ''}
            `;
            
            document.getElementById('detailModal').classList.add('show');
        }

        function closeModal() {
            document.getElementById('detailModal').classList.remove('show');
        }

        function getStatusText(status) {
            const map = { 'pending': 'Menunggu', 'process': 'Diproses', 'done': 'Selesai' };
            return map[status] || status;
        }

        function getCategoryText(category) {
            const map = {
                'hardware': 'Hardware/Perangkat',
                'software': 'Software/Aplikasi',
                'network': 'Jaringan/Internet',
                'system': 'Sistem Absensi',
                'access': 'Akses/Login',
                'other': 'Lainnya'
            };
            return map[category] || category;
        }

        function getPriorityText(priority) {
            const map = { 'low': 'Rendah', 'medium': 'Sedang', 'high': 'Tinggi' };
            return map[priority] || priority;
        }

        function formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
        }

        function formatDateTime(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' }) + 
                   ', ' + date.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
        }

        function createComplaint() {
            window.location.href = '{{ route('complaints.form') }}';
        }

        function goBack() {
            window.history.back();
        }

        document.getElementById('detailModal').addEventListener('click', function(e) {
            if (e.target === this) closeModal();
        });

        window.addEventListener('DOMContentLoaded', loadComplaints);
    </script>
</body>
</html>
