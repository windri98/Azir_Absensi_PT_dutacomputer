<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengajuan Izin - Sistem Absensi</title>
    <link rel="stylesheet" href="{{ asset('components/popup.css') }}">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
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
                <h3 class="balance-title">Sisa Cuti Anda Tahun {{ now()->year }}</h3>
            </div>
            <div class="balance-grid">
                <div class="balance-item">
                    <div class="balance-number">{{ $leaveBalance['annual'] }}</div>
                    <div class="balance-label">Cuti Tahunan</div>
                </div>
                <div class="balance-item">
                    <div class="balance-number">{{ $leaveBalance['sick'] }}</div>
                    <div class="balance-label">Cuti Sakit</div>
                </div>
                <div class="balance-item">
                    <div class="balance-number">{{ $leaveBalance['special'] }}</div>
                    <div class="balance-label">Cuti Khusus</div>
                </div>
            </div>
        </div>

        <!-- Leave Request Form -->
        <div class="form-section">
            <h3 class="section-title">Ajukan Izin Baru</h3>
            
            <form id="leaveForm" action="{{ route('complaints.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label">Jenis Izin</label>
                    <div class="leave-types">
                        <div class="leave-type" data-type="cuti">
                            <div class="leave-type-icon">🏖️</div>
                            <div class="leave-type-name">Cuti Tahunan</div>
                        </div>
                        <div class="leave-type" data-type="sakit">
                            <div class="leave-type-icon">🏥</div>
                            <div class="leave-type-name">Cuti Sakit</div>
                        </div>
                        <div class="leave-type" data-type="izin">
                            <div class="leave-type-icon">👤</div>
                            <div class="leave-type-name">Izin Pribadi</div>
                        </div>
                        <div class="leave-type" data-type="lainnya">
                            <div class="leave-type-icon">🚨</div>
                            <div class="leave-type-name">Lainnya</div>
                        </div>
                    </div>
                    <input type="hidden" name="category" id="categoryInput" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Periode Izin</label>
                    <div class="date-range">
                        <input type="date" class="form-input" name="start_date" id="startDate" required>
                        <input type="date" class="form-input" name="end_date" id="endDate" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Judul Pengajuan</label>
                    <input type="text" class="form-input" name="title" id="title" placeholder="Contoh: Cuti Liburan Akhir Tahun" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Alasan Izin</label>
                    <textarea class="form-textarea" name="description" id="reason" placeholder="Jelaskan alasan pengajuan izin Anda..." required></textarea>
                </div>

                <input type="hidden" name="priority" value="medium">

                <button type="submit" class="submit-btn">Ajukan Izin</button>
            </form>
        </div>

        <!-- Recent Requests -->
        <div class="recent-section">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
                <h3 class="section-title" style="margin:0;">Riwayat Pengajuan</h3>
                <button onclick="location.reload()" style="background:#e5e7eb;border:none;padding:6px 12px;border-radius:6px;font-size:12px;cursor:pointer;">
                    🔄 Refresh
                </button>
            </div>
            
            @if(isset($complaints))
                @if($complaints->count() > 0)
                    <div id="recentRequests">
                        @foreach($complaints as $complaint)
                            <div class="request-item" style="display:block;padding:16px;border-bottom:1px solid #f3f4f6">
                                <div style="display:flex;justify-content:space-between;align-items:start;margin-bottom:8px">
                                    <div class="request-info" style="flex:1">
                                        <h4 style="font-size:15px;font-weight:600;color:#333;margin-bottom:4px">{{ $complaint->title }}</h4>
                                        <p style="font-size:13px;color:#6b7280;margin:0">
                                            {{ ucfirst($complaint->category) }} • {{ $complaint->created_at->format('d M Y H:i') }}
                                        </p>
                                    </div>
                                    @php
                                        $statusClass = 'status-pending';
                                        $statusText = 'Pending';
                                        if($complaint->status == 'approved') {
                                            $statusClass = 'status-approved';
                                            $statusText = 'Disetujui';
                                        } elseif($complaint->status == 'rejected') {
                                            $statusClass = 'status-rejected';
                                            $statusText = 'Ditolak';
                                        }
                                    @endphp
                                    <span class="request-status {{ $statusClass }}" style="margin-left:12px">{{ $statusText }}</span>
                                </div>
                                
                                @if($complaint->response)
                                    <div style="margin-top:12px;padding:12px;background:{{ $complaint->status == 'rejected' ? '#fee2e2' : '#d1fae5' }};border-left:3px solid {{ $complaint->status == 'rejected' ? '#ef4444' : '#10b981' }};border-radius:6px">
                                        <div style="font-size:12px;font-weight:600;color:{{ $complaint->status == 'rejected' ? '#991b1b' : '#065f46' }};margin-bottom:4px">
                                            {{ $complaint->status == 'rejected' ? '❌ Alasan Penolakan:' : '✅ Catatan Admin:' }}
                                        </div>
                                        <div style="font-size:13px;color:#374151">{{ $complaint->response }}</div>
                                        @if($complaint->responded_at)
                                            <div style="font-size:11px;color:#6b7280;margin-top:4px">
                                                {{ $complaint->responded_at->format('d M Y, H:i') }}
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div style="text-align: center; padding: 20px; color: #6b7280;">
                        <div style="font-size: 48px; margin-bottom: 16px; opacity: 0.5;">📝</div>
                        <div>Belum ada pengajuan izin</div>
                        <small style="font-size: 12px; margin-top: 8px; display: block;">Ajukan izin pertama Anda di formulir di atas</small>
                    </div>
                @endif
            @else
                <div style="text-align: center; padding: 20px; color: #ff6b6b;">
                    <div style="font-size: 48px; margin-bottom: 16px;">⚠️</div>
                    <div>Data tidak tersedia - Variable $complaints tidak ada</div>
                </div>
            @endif
        </div>
    </div>

    <script src="{{ asset('components/popup.js') }}"></script>
    <script>
        let selectedLeaveType = null;

        function goBack() {
            window.location.href = '{{ route('dashboard') }}';
        }

        // Handle leave type selection
        document.querySelectorAll('.leave-type').forEach(type => {
            type.addEventListener('click', function() {
                document.querySelectorAll('.leave-type').forEach(t => t.classList.remove('selected'));
                this.classList.add('selected');
                selectedLeaveType = this.dataset.type;
                document.getElementById('categoryInput').value = selectedLeaveType;
            });
        });

        // Handle form submission
        document.getElementById('leaveForm').addEventListener('submit', function(e) {
            if (!selectedLeaveType) {
                e.preventDefault();
                showErrorPopup({
                    title: 'Error',
                    message: 'Silakan pilih jenis izin terlebih dahulu',
                    buttonText: 'OK'
                });
                return;
            }

            const startDate = document.getElementById('startDate').value;
            const endDate = document.getElementById('endDate').value;

            // Validate date range
            if (new Date(startDate) > new Date(endDate)) {
                e.preventDefault();
                showErrorPopup({
                    title: 'Error',
                    message: 'Tanggal mulai tidak boleh lebih besar dari tanggal selesai',
                    buttonText: 'OK'
                });
                return;
            }

            // Debug log
            console.log('Form submitting with data:', {
                category: selectedLeaveType,
                title: document.getElementById('title').value,
                description: document.getElementById('reason').value,
                start_date: startDate,
                end_date: endDate
            });
        });

        // Set minimum date to today
        document.addEventListener('DOMContentLoaded', function() {
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('startDate').min = today;
            document.getElementById('endDate').min = today;
            
            // Update end date minimum when start date changes
            document.getElementById('startDate').addEventListener('change', function() {
                document.getElementById('endDate').min = this.value;
            });
        });

        // Show success message if redirected after successful submission
        @if(session('success'))
            showSuccessPopup({
                title: 'Pengajuan Berhasil!',
                message: '{{ session('success') }}',
                buttonText: 'OK'
            });
        @endif

        @if(session('error'))
            showErrorPopup({
                title: 'Error',
                message: '{{ session('error') }}',
                buttonText: 'OK'
            });
        @endif
    </script>
</body>
</html>