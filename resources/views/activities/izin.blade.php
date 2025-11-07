<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengajuan Izin - Sistem Absensi</title>
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
        .header-title h1 {
            font-size: 20px;
            font-weight: 600;
        }
        .header-title p {
            font-size: 14px;
            opacity: 0.8;
            margin-top: 4px;
        }
        
        /* Content */
        .content {
            padding: 20px;
        }
        
        /* Leave Balance Card */
        .balance-card {
            background: white;
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        }
        .balance-header {
            text-align: center;
            margin-bottom: 16px;
        }
        .balance-title {
            font-size: 16px;
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
        }
        .balance-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
        }
        .balance-item {
            text-align: center;
            padding: 12px;
            border-radius: 12px;
            background: #f8fafc;
        }
        .balance-number {
            font-size: 20px;
            font-weight: bold;
            color: #1ec7e6;
            margin-bottom: 4px;
        }
        .balance-label {
            font-size: 12px;
            color: #6b7280;
        }
        
        /* Form Section */
        .form-section {
            background: white;
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        }
        .section-title {
            font-size: 16px;
            font-weight: 600;
            color: #333;
            margin-bottom: 16px;
        }
        .form-group {
            margin-bottom: 16px;
        }
        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 500;
            color: #374151;
            margin-bottom: 6px;
        }
        .form-input {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            font-size: 14px;
            background: #f9fafb;
            transition: all 0.2s ease;
        }
        .form-input:focus {
            outline: none;
            border-color: #1ec7e6;
            background: white;
            box-shadow: 0 0 0 3px rgba(30, 199, 230, 0.1);
        }
        .form-select {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            font-size: 14px;
            background: white;
            cursor: pointer;
        }
        .form-textarea {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            font-size: 14px;
            background: #f9fafb;
            resize: none;
            height: 80px;
        }
        .date-range {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }
        
        /* Leave Type Cards */
        .leave-types {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
            margin-bottom: 16px;
        }
        .leave-type {
            padding: 16px;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s ease;
            background: white;
        }
        .leave-type.selected {
            border-color: #1ec7e6;
            background: #f0faff;
        }
        .leave-type-icon {
            font-size: 24px;
            margin-bottom: 8px;
        }
        .leave-type-name {
            font-size: 14px;
            font-weight: 500;
            color: #333;
        }
        
        /* Submit Button */
        .submit-btn {
            width: 100%;
            background: linear-gradient(135deg, #1ec7e6, #0ea5e9);
            color: white;
            border: none;
            padding: 16px;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 20px;
        }
        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(30, 199, 230, 0.4);
        }
        .submit-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }
        
        /* Recent Requests */
        .recent-section {
            background: white;
            border-radius: 16px;
            padding: 20px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        }
        .request-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #f3f4f6;
        }
        .request-item:last-child {
            border-bottom: none;
        }
        .request-info h4 {
            font-size: 14px;
            font-weight: 500;
            color: #333;
            margin-bottom: 4px;
        }
        .request-info p {
            font-size: 12px;
            color: #6b7280;
        }
        .request-status {
            padding: 4px 8px;
            border-radius: 8px;
            font-size: 11px;
            font-weight: 500;
        }
        .status-pending {
            background: #fef3c7;
            color: #d97706;
        }
        .status-approved {
            background: #dcfce7;
            color: #16a34a;
        }
        .status-rejected {
            background: #fee2e2;
            color: #dc2626;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-content">
            <button class="back-btn" onclick="goBack()">←</button>
            <div class="header-title">
                <h1>Pengajuan Izin</h1>
                <p>Ajukan cuti dan izin Anda</p>
            </div>
        </div>
    </div>

    <div class="content">
        <!-- Leave Balance -->
        <div class="balance-card">
            <div class="balance-header">
                <h3 class="balance-title">Sisa Cuti Anda</h3>
            </div>
            <div class="balance-grid">
                <div class="balance-item">
                    <div class="balance-number">12</div>
                    <div class="balance-label">Cuti Tahunan</div>
                </div>
                <div class="balance-item">
                    <div class="balance-number">5</div>
                    <div class="balance-label">Cuti Sakit</div>
                </div>
                <div class="balance-item">
                    <div class="balance-number">3</div>
                    <div class="balance-label">Cuti Khusus</div>
                </div>
            </div>
        </div>

        <!-- Leave Request Form -->
        <div class="form-section">
            <h3 class="section-title">Ajukan Izin Baru</h3>
            
            <form id="leaveForm">
                <div class="form-group">
                    <label class="form-label">Jenis Izin</label>
                    <div class="leave-types">
                        <div class="leave-type" data-type="annual">
                            <div class="leave-type-icon">🏖️</div>
                            <div class="leave-type-name">Cuti Tahunan</div>
                        </div>
                        <div class="leave-type" data-type="sick">
                            <div class="leave-type-icon">🏥</div>
                            <div class="leave-type-name">Cuti Sakit</div>
                        </div>
                        <div class="leave-type" data-type="personal">
                            <div class="leave-type-icon">👤</div>
                            <div class="leave-type-name">Cuti Pribadi</div>
                        </div>
                        <div class="leave-type" data-type="emergency">
                            <div class="leave-type-icon">🚨</div>
                            <div class="leave-type-name">Darurat</div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Periode Izin</label>
                    <div class="date-range">
                        <input type="date" class="form-input" id="startDate" required>
                        <input type="date" class="form-input" id="endDate" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Alasan Izin</label>
                    <textarea class="form-textarea" id="reason" placeholder="Jelaskan alasan pengajuan izin Anda..." required></textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Pengganti Selama Izin (Opsional)</label>
                    <input type="text" class="form-input" id="replacement" placeholder="Nama pengganti">
                </div>

                <button type="submit" class="submit-btn">Ajukan Izin</button>
            </form>
        </div>

        <!-- Recent Requests -->
        <div class="recent-section">
            <h3 class="section-title">Riwayat Pengajuan</h3>
            <div id="recentRequests">
                <!-- Recent requests will be populated here -->
                <div class="request-item">
                    <div class="request-info">
                        <h4>Cuti Tahunan</h4>
                        <p>15-17 Oktober 2025 • 3 hari</p>
                    </div>
                    <span class="request-status status-pending">Pending</span>
                </div>
                <div class="request-item">
                    <div class="request-info">
                        <h4>Cuti Sakit</h4>
                        <p>10 Oktober 2025 • 1 hari</p>
                    </div>
                    <span class="request-status status-approved">Approved</span>
                </div>
            </div>
        </div>
    </div>

    <script src="components/popup.js"></script>
    <script>
        let selectedLeaveType = null;

        function goBack() {
            window.location.href = 'dashboard';
        }

        // Handle leave type selection
        document.querySelectorAll('.leave-type').forEach(type => {
            type.addEventListener('click', function() {
                document.querySelectorAll('.leave-type').forEach(t => t.classList.remove('selected'));
                this.classList.add('selected');
                selectedLeaveType = this.dataset.type;
            });
        });

        // Handle form submission
        document.getElementById('leaveForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (!selectedLeaveType) {
                showErrorPopup({
                    title: 'Error',
                    message: 'Silakan pilih jenis izin terlebih dahulu',
                    buttonText: 'OK'
                });
                return;
            }

            const startDate = document.getElementById('startDate').value;
            const endDate = document.getElementById('endDate').value;
            const reason = document.getElementById('reason').value;
            const replacement = document.getElementById('replacement').value;

            if (!startDate || !endDate || !reason) {
                showErrorPopup({
                    title: 'Error',
                    message: 'Mohon lengkapi semua field yang wajib diisi',
                    buttonText: 'OK'
                });
                return;
            }

            // Validate date range
            if (new Date(startDate) > new Date(endDate)) {
                showErrorPopup({
                    title: 'Error',
                    message: 'Tanggal mulai tidak boleh lebih besar dari tanggal selesai',
                    buttonText: 'OK'
                });
                return;
            }

            // Calculate days
            const start = new Date(startDate);
            const end = new Date(endDate);
            const diffTime = Math.abs(end - start);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;

            // Create leave request
            const leaveRequest = {
                id: Date.now(),
                type: selectedLeaveType,
                startDate: startDate,
                endDate: endDate,
                days: diffDays,
                reason: reason,
                replacement: replacement,
                status: 'pending',
                createdAt: new Date().toISOString()
            };

            // Save to localStorage
            const leaveRequests = JSON.parse(localStorage.getItem('leaveRequests') || '[]');
            leaveRequests.push(leaveRequest);
            localStorage.setItem('leaveRequests', JSON.stringify(leaveRequests));

            // Show success popup
            showSuccessPopup({
                title: 'Pengajuan Berhasil!',
                message: `Pengajuan izin ${diffDays} hari telah dikirim untuk diproses`,
                buttonText: 'OK',
                onClose: () => {
                    // Reset form
                    document.getElementById('leaveForm').reset();
                    document.querySelectorAll('.leave-type').forEach(t => t.classList.remove('selected'));
                    selectedLeaveType = null;
                    
                    // Refresh recent requests
                    loadRecentRequests();
                }
            });
        });

        function loadRecentRequests() {
            const leaveRequests = JSON.parse(localStorage.getItem('leaveRequests') || '[]');
            const recentContainer = document.getElementById('recentRequests');
            
            if (leaveRequests.length === 0) {
                recentContainer.innerHTML = `
                    <div style="text-align: center; padding: 20px; color: #6b7280;">
                        <div style="font-size: 48px; margin-bottom: 16px; opacity: 0.5;">📝</div>
                        <div>Belum ada pengajuan izin</div>
                    </div>
                `;
                return;
            }

            recentContainer.innerHTML = '';
            
            // Show latest 5 requests
            leaveRequests.slice(-5).reverse().forEach(request => {
                const leaveTypeNames = {
                    'annual': 'Cuti Tahunan',
                    'sick': 'Cuti Sakit',
                    'personal': 'Cuti Pribadi',
                    'emergency': 'Darurat'
                };

                const formatDate = (dateStr) => {
                    return new Date(dateStr).toLocaleDateString('id-ID', {
                        day: '2-digit',
                        month: 'long',
                        year: 'numeric'
                    });
                };

                const dateRange = request.startDate === request.endDate 
                    ? formatDate(request.startDate)
                    : `${formatDate(request.startDate)} - ${formatDate(request.endDate)}`;

                const requestItem = `
                    <div class="request-item">
                        <div class="request-info">
                            <h4>${leaveTypeNames[request.type]}</h4>
                            <p>${dateRange} • ${request.days} hari</p>
                        </div>
                        <span class="request-status status-${request.status}">
                            ${request.status.charAt(0).toUpperCase() + request.status.slice(1)}
                        </span>
                    </div>
                `;
                
                recentContainer.insertAdjacentHTML('beforeend', requestItem);
            });
        }

        // Set minimum date to today
        document.addEventListener('DOMContentLoaded', function() {
            // Check if user is logged in
            const userSession = localStorage.getItem('userSession');
            if (!userSession) {
                window.location.href = 'welcome';
                return;
            }

            const today = new Date().toISOString().split('T')[0];
            document.getElementById('startDate').min = today;
            document.getElementById('endDate').min = today;
            
            // Update end date minimum when start date changes
            document.getElementById('startDate').addEventListener('change', function() {
                document.getElementById('endDate').min = this.value;
            });

            loadRecentRequests();
        });
    </script>
</body>
</html>