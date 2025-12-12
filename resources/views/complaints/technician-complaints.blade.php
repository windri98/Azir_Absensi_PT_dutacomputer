<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="theme-color" content="#1ec7e6">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <title>Keluhan Teknisi - Sistem Absensi</title>
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
            background: linear-gradient(135deg, #8b5cf6, #7c3aed);
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
            flex: 1;
        }

        .header-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 4px;
        }

        .header-subtitle {
            font-size: 12px;
            opacity: 0.9;
        }

        .refresh-btn {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            font-size: 18px;
            cursor: pointer;
        }

        .content {
            padding: 20px;
            padding-bottom: 100px;
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
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
        }

        .stat-card.pending::before { background: #f59e0b; }
        .stat-card.process::before { background: #3b82f6; }
        .stat-card.done::before { background: #10b981; }

        .stat-number {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 4px;
        }

        .stat-number.pending { color: #f59e0b; }
        .stat-number.process { color: #3b82f6; }
        .stat-number.done { color: #10b981; }

        .stat-label {
            font-size: 12px;
            color: #6b7280;
            font-weight: 600;
        }

        .filter-section {
            background: white;
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 16px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        }

        .filter-title {
            font-size: 14px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 12px;
        }

        .filter-tabs {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .filter-tab {
            background: #f9fafb;
            border: 2px solid transparent;
            color: #6b7280;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .filter-tab.active {
            background: #8b5cf6;
            color: white;
            border-color: #8b5cf6;
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
            border-left: 4px solid #e5e7eb;
        }

        .complaint-card.pending {
            border-left-color: #f59e0b;
        }

        .complaint-card.process {
            border-left-color: #3b82f6;
        }

        .complaint-card.done {
            border-left-color: #10b981;
        }

        .complaint-card:hover {
            transform: translateX(4px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .complaint-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 12px;
        }

        .ticket-info {
            flex: 1;
        }

        .ticket-number {
            font-size: 13px;
            color: #8b5cf6;
            font-family: monospace;
            font-weight: bold;
            margin-bottom: 4px;
        }

        .reporter-name {
            font-size: 12px;
            color: #6b7280;
        }

        .status-badge {
            padding: 6px 12px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
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

        .priority-badge {
            padding: 4px 8px;
            border-radius: 8px;
            font-size: 11px;
            font-weight: 600;
        }

        .priority-badge.low {
            background: #d1fae5;
            color: #059669;
        }

        .priority-badge.medium {
            background: #fef3c7;
            color: #d97706;
        }

        .priority-badge.high {
            background: #fee2e2;
            color: #dc2626;
        }

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
        }

        .empty-text {
            font-size: 16px;
            color: #6b7280;
        }

        /* Detail Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.6);
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
            max-height: 85vh;
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
            text-transform: uppercase;
            letter-spacing: 0.5px;
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
            background: #f9fafb;
            padding: 12px;
            border-radius: 8px;
        }

        .action-section {
            background: #f9fafb;
            padding: 16px;
            border-radius: 12px;
            margin-top: 20px;
        }

        .action-title {
            font-size: 14px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 12px;
        }

        .action-buttons {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 8px;
            margin-bottom: 16px;
        }

        .action-btn {
            padding: 10px;
            border: 2px solid;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            text-align: center;
        }

        .action-btn.pending {
            background: white;
            border-color: #f59e0b;
            color: #d97706;
        }

        .action-btn.pending:hover {
            background: #fef3c7;
        }

        .action-btn.process {
            background: white;
            border-color: #3b82f6;
            color: #2563eb;
        }

        .action-btn.process:hover {
            background: #dbeafe;
        }

        .action-btn.done {
            background: white;
            border-color: #10b981;
            color: #059669;
        }

        .action-btn.done:hover {
            background: #d1fae5;
        }

        .action-btn.active {
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .action-btn.pending.active {
            background: #f59e0b;
            color: white;
        }

        .action-btn.process.active {
            background: #3b82f6;
            color: white;
        }

        .action-btn.done.active {
            background: #10b981;
            color: white;
        }

        .notes-input {
            width: 100%;
            padding: 12px;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            font-size: 14px;
            font-family: Arial, sans-serif;
            min-height: 80px;
            resize: vertical;
            margin-bottom: 12px;
        }

        .notes-input:focus {
            outline: none;
            border-color: #8b5cf6;
        }

        .assign-input {
            width: 100%;
            padding: 12px;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            font-size: 14px;
            margin-bottom: 12px;
        }

        .assign-input:focus {
            outline: none;
            border-color: #8b5cf6;
        }

        .save-btn {
            background: #8b5cf6;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
        }

        .save-btn:hover {
            background: #7c3aed;
        }

        .timeline {
            margin-top: 24px;
            padding-top: 24px;
            border-top: 2px solid #e5e7eb;
        }

        .timeline-title {
            font-size: 14px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 16px;
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
            top: 28px;
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
            font-weight: bold;
        }

        .timeline-dot.pending { background: #fef3c7; color: #d97706; }
        .timeline-dot.process { background: #dbeafe; color: #2563eb; }
        .timeline-dot.done { background: #d1fae5; color: #059669; }

        .timeline-content {
            flex: 1;
        }

        .timeline-status {
            font-size: 14px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 4px;
        }

        .timeline-time {
            font-size: 12px;
            color: #6b7280;
        }

        .timeline-note {
            font-size: 13px;
            color: #6b7280;
            margin-top: 4px;
            font-style: italic;
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

        /* @media (min-width: 394px) {
            .bottom-nav {
                left: 50%;
                transform: translateX(-50%);
            }
        } */

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
            color: #8b5cf6;
        }

        .nav-item:hover {
            color: #8b5cf6;
        }

        .nav-icon {
            font-size: 24px;
            margin-bottom: 4px;
            line-height: 1;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-left">
            <div class="header-title">Panel Teknisi</div>
            <div class="header-subtitle">Kelola Keluhan Karyawan</div>
        </div>
        <button class="refresh-btn" onclick="loadComplaints()">🔄</button>
    </div>

    <div class="content">
        <div class="stats-grid">
            <div class="stat-card pending">
                <div class="stat-number pending" id="statPending">0</div>
                <div class="stat-label">Pending</div>
            </div>
            <div class="stat-card process">
                <div class="stat-number process" id="statProcess">0</div>
                <div class="stat-label">Diproses</div>
            </div>
            <div class="stat-card done">
                <div class="stat-number done" id="statDone">0</div>
                <div class="stat-label">Selesai</div>
            </div>
        </div>

        <div class="filter-section">
            <div class="filter-title">Filter Status</div>
            <div class="filter-tabs">
                <button class="filter-tab active" onclick="filterComplaints('all', this)">Semua</button>
                <button class="filter-tab" onclick="filterComplaints('pending', this)">Pending</button>
                <button class="filter-tab" onclick="filterComplaints('process', this)">Diproses</button>
                <button class="filter-tab" onclick="filterComplaints('done', this)">Selesai</button>
            </div>
        </div>

        <div class="complaint-list" id="complaintList"></div>

        <div class="empty-state" id="emptyState" style="display: none;">
            <div class="empty-icon">✅</div>
            <div class="empty-text">Tidak ada keluhan yang perlu ditangani</div>
        </div>
    </div>

    <div class="bottom-nav">
        <a href="{{ route('dashboard') }}" class="nav-item">
            <div class="nav-icon">🏠</div>
            <div>Beranda</div>
        </a>
        <a href="{{ route('complaints.history') }}" class="nav-item">
            <div class="nav-icon">📝</div>
            <div>Riwayat</div>
        </a>
        <a href="{{ route('complaints.technician') }}" class="nav-item active">
            <div class="nav-icon">🔧</div>
            <div>Keluhan</div>
        </a>
        <a href="{{ route('profile.show') }}" class="nav-item">
            <div class="nav-icon">👤</div>
            <div>Profil</div>
        </a>
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
        let currentComplaint = null;

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
                complaintList.innerHTML = filteredComplaints.map((complaint, index) => `
                    <div class="complaint-card ${complaint.status}" onclick='showDetail(${index})'>
                        <div class="complaint-header">
                            <div class="ticket-info">
                                <div class="ticket-number">${complaint.ticketNumber}</div>
                                <div class="reporter-name">👤 ${complaint.reportedBy}</div>
                            </div>
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
                            <div class="priority-badge ${complaint.priority}">
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

        function showDetail(index) {
            const complaints = JSON.parse(localStorage.getItem('complaints') || '[]');
            const filteredComplaints = currentFilter === 'all' 
                ? complaints 
                : complaints.filter(c => c.status === currentFilter);
            
            currentComplaint = filteredComplaints[index];
            const fullIndex = complaints.findIndex(c => c.ticketNumber === currentComplaint.ticketNumber);

            const modalBody = document.getElementById('modalBody');
            modalBody.innerHTML = `
                <div class="detail-section">
                    <div class="detail-label">Nomor Tiket</div>
                    <div class="detail-value" style="font-family: monospace; font-weight: 600; color: #8b5cf6;">${currentComplaint.ticketNumber}</div>
                </div>
                
                <div class="detail-section">
                    <div class="detail-label">Dilaporkan Oleh</div>
                    <div class="detail-value">👤 ${currentComplaint.reportedBy} (${currentComplaint.reportedById})</div>
                </div>
                
                <div class="detail-section">
                    <div class="detail-label">Kategori</div>
                    <div class="detail-value">${getCategoryText(currentComplaint.category)}</div>
                </div>
                
                <div class="detail-section">
                    <div class="detail-label">Judul</div>
                    <div class="detail-value" style="font-weight: 600; font-size: 16px;">${currentComplaint.title}</div>
                </div>
                
                <div class="detail-section">
                    <div class="detail-label">Deskripsi Masalah</div>
                    <div class="detail-description">${currentComplaint.description}</div>
                </div>
                
                <div class="detail-section">
                    <div class="detail-label">Tingkat Urgensi</div>
                    <div class="priority-badge ${currentComplaint.priority}">${getPriorityText(currentComplaint.priority)}</div>
                </div>
                
                ${currentComplaint.location ? `
                <div class="detail-section">
                    <div class="detail-label">Lokasi</div>
                    <div class="detail-value">📍 ${currentComplaint.location}</div>
                </div>
                ` : ''}
                
                ${currentComplaint.file ? `
                <div class="detail-section">
                    <div class="detail-label">Lampiran</div>
                    <div class="detail-value">📷 ${currentComplaint.file}</div>
                </div>
                ` : ''}
                
                <div class="detail-section">
                    <div class="detail-label">Tanggal Laporan</div>
                    <div class="detail-value">${formatDateTime(currentComplaint.createdAt)}</div>
                </div>
                
                <div class="action-section">
                    <div class="action-title">⚡ Tindakan</div>
                    
                    <div class="action-buttons">
                        <button class="action-btn pending ${currentComplaint.status === 'pending' ? 'active' : ''}" onclick="setStatus('pending')">
                            Pending
                        </button>
                        <button class="action-btn process ${currentComplaint.status === 'process' ? 'active' : ''}" onclick="setStatus('process')">
                            Proses
                        </button>
                        <button class="action-btn done ${currentComplaint.status === 'done' ? 'active' : ''}" onclick="setStatus('done')">
                            Selesai
                        </button>
                    </div>
                    
                    <input 
                        type="text" 
                        class="assign-input" 
                        id="technicianName" 
                        placeholder="Nama Teknisi yang menangani"
                        value="${currentComplaint.technician || ''}"
                    >
                    
                    <textarea 
                        class="notes-input" 
                        id="technicianNotes" 
                        placeholder="Catatan teknisi (opsional)..."
                    >${currentComplaint.notes || ''}</textarea>
                    
                    <button class="save-btn" onclick="saveChanges(${fullIndex})">💾 Simpan Perubahan</button>
                </div>
                
                ${currentComplaint.history && currentComplaint.history.length > 0 ? `
                <div class="timeline">
                    <div class="timeline-title">📋 Riwayat</div>
                    ${currentComplaint.history.map(h => `
                        <div class="timeline-item">
                            <div class="timeline-dot ${h.status}">${getStatusIcon(h.status)}</div>
                            <div class="timeline-content">
                                <div class="timeline-status">${getStatusText(h.status)}</div>
                                <div class="timeline-time">${formatDateTime(h.timestamp)}</div>
                                ${h.note ? `<div class="timeline-note">${h.note}</div>` : ''}
                            </div>
                        </div>
                    `).join('')}
                </div>
                ` : ''}
            `;
            
            document.getElementById('detailModal').classList.add('show');
        }

        function setStatus(status) {
            document.querySelectorAll('.action-btn').forEach(btn => btn.classList.remove('active'));
            event.target.classList.add('active');
            currentComplaint.status = status;
        }

        function saveChanges(index) {
            const technician = document.getElementById('technicianName').value;
            const notes = document.getElementById('technicianNotes').value;
            
            const complaints = JSON.parse(localStorage.getItem('complaints') || '[]');
            
            // Initialize history if not exists
            if (!complaints[index].history) {
                complaints[index].history = [];
            }
            
            // Add to history if status changed
            if (complaints[index].status !== currentComplaint.status) {
                complaints[index].history.push({
                    status: currentComplaint.status,
                    timestamp: new Date().toISOString(),
                    note: notes,
                    technician: technician
                });
            }
            
            complaints[index].status = currentComplaint.status;
            complaints[index].technician = technician;
            complaints[index].notes = notes;
            complaints[index].updatedAt = new Date().toISOString();
            
            localStorage.setItem('complaints', JSON.stringify(complaints));
            
            alert('✅ Perubahan berhasil disimpan!');
            closeModal();
            loadComplaints();
        }

        function closeModal() {
            document.getElementById('detailModal').classList.remove('show');
            currentComplaint = null;
        }

        function getStatusText(status) {
            const map = { 'pending': 'Pending', 'process': 'Diproses', 'done': 'Selesai' };
            return map[status] || status;
        }

        function getStatusIcon(status) {
            const map = { 'pending': '⏳', 'process': '⚙️', 'done': '✓' };
            return map[status] || '●';
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

        document.getElementById('detailModal').addEventListener('click', function(e) {
            if (e.target === this) closeModal();
        });

        window.addEventListener('DOMContentLoaded', loadComplaints);
    </script>
</body>
</html>
