@extends('layouts.app')

@section('title', 'Riwayat Laporan - Sistem Absensi')

@php
    $hideHeader = true;
@endphp

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/report-history.css') }}">
@endpush

@section('content')
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
@endsection

@push('scripts')
    <script src="{{ asset('components/popup.js') }}"></script>
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
            window.location.href = '{{ route("reports.customer") }}';
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

        // Close modal when clicking outside
        document.getElementById('detailModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });

        // Load reports on page load
        window.addEventListener('DOMContentLoaded', loadReports);
    </script>
@endpush