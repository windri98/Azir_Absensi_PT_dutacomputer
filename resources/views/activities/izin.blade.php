<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengajuan Izin - Sistem Absensi</title>
    <link rel="stylesheet" href="{{ asset('components/popup.css') }}">
    <style>
                .leave-type {
                    position: relative;
                }
                .leave-type:hover {
                    outline: 2px solid #e11d48 !important; /* merah, debug */
                    z-index: 10;
                }
                .leave-type.selected {
                    outline: 2px solid #1ec7e6 !important; /* biru, aktif */
                    z-index: 10;
                }
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
            position: relative;
            z-index: 11;
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
            transition: all 0.3s ease;
            z-index: 1000;
            position: relative;
            user-select: none;
            -webkit-tap-highlight-color: transparent;
            flex-shrink: 0;
            text-decoration: none;
        }
        .back-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: scale(1.1);
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
            user-select: none;
            position: relative;
            min-height: 80px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            pointer-events: auto !important;
            outline: 0px solid transparent;
        }
        .leave-type:hover {
            border-color: #94a3b8;
            background: #f8fafc;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        .leave-type.selected {
            border-color: #1ec7e6;
            background: #f0faff;
            transform: translateY(-2px);
            box-shadow: 0 4px 16px rgba(30, 199, 230, 0.2);
        }
        .leave-type.emergency {
            /* Tidak ada warna khusus, tampil netral seperti leave-type lain */
            border: 2px solid #e5e7eb;
            background: white;
            position: relative;
            animation: none;
        }
        .leave-type.emergency.selected {
            border-color: #1ec7e6;
            background: #f0faff;
        }
        .leave-type.emergency::after {
            content: "URGENT";
            position: absolute;
            top: -8px;
            right: -8px;
            background: #dc2626;
            color: white;
            font-size: 8px;
            padding: 2px 6px;
            border-radius: 10px;
            font-weight: bold;
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
            position: relative;
            z-index: 1;
            min-height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(30, 199, 230, 0.4);
            background: linear-gradient(135deg, #0ea5e9, #1ec7e6);
        }
        .submit-btn:active {
            transform: translateY(0);
            box-shadow: 0 2px 8px rgba(30, 199, 230, 0.3);
        }
        .submit-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }
        
        /* Make sure buttons are clickable */
        .submit-btn, .leave-type {
            pointer-events: auto;
            user-select: none;
        }
        
        /* Upload Area */
        .upload-area {
            border: 2px dashed #d1d5db;
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            background: #f9fafb;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        .upload-area:hover {
            border-color: #1ec7e6;
            background: #f0faff;
        }
        .upload-icon {
            font-size: 32px;
            margin-bottom: 8px;
        }
        .upload-text {
            color: #6b7280;
            font-size: 14px;
        }
        .upload-text strong {
            color: #1ec7e6;
        }
        .file-preview {
            margin-top: 12px;
            padding: 12px;
            background: #f0faff;
            border-radius: 8px;
            border: 1px solid #1ec7e6;
            font-size: 14px;
            color: #0369a1;
        }
        
        /* Admin Notes Section */
        .admin-note-info {
            background: #f0f9ff;
            border-radius: 8px;
            padding: 8px 12px;
            margin-bottom: 8px;
            border-left: 4px solid #1ec7e6;
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
            <a href="{{ route('dashboard') }}" class="back-btn">←</a>
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
            
            <form id="leaveForm" action="{{ route('complaints.store') }}" method="POST" enctype="multipart/form-data">
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
                            <div class="leave-type-name">Izin Sakit</div>
                        </div>
                        <div class="leave-type emergency" data-type="mendadak">
                            <div class="leave-type-icon">⚡</div>
                            <div class="leave-type-name">Izin Mendadak</div>
                        </div>
                            <div class="leave-type" data-type="keluarga">
                                <div class="leave-type-icon">👨‍👩‍👧‍👦</div>
                                <div class="leave-type-check"></div>
                                <div class="leave-type-name">Urusan Keluarga</div>
                            </div>
                            <div class="leave-type" data-type="izin">
                                <div class="leave-type-icon">👤</div>
                                <div class="leave-type-check"></div>
                                <div class="leave-type-name">Izin Pribadi</div>
                            </div>
                            <div class="leave-type" data-type="lainnya">
                                <div class="leave-type-icon">�</div>
                                <div class="leave-type-check"></div>
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

                <!-- Upload Surat MC & Bukti Pendukung -->
                <div class="form-group" id="uploadSection" style="display: none;">
                    <label class="form-label" id="uploadLabel">Upload Bukti Pendukung</label>
                    <div class="upload-info" id="uploadInfo" style="display: none;"></div>
                    <div class="upload-area">
                        <input type="file" class="form-input" name="attachment" id="attachment" accept="image/*,.pdf,.doc,.docx" style="display: none;">
                        <div class="upload-placeholder" onclick="document.getElementById('attachment').click();">
                            <div class="upload-icon" id="uploadIcon">📎</div>
                            <div class="upload-text" id="uploadText">
                                <strong>Klik untuk upload</strong><br>
                                <small>Foto, PDF, atau dokumen (max 5MB)</small>
                            </div>
                        </div>
                        <div class="file-preview" id="filePreview" style="display: none;"></div>
                    </div>
                </div>

                <!-- Catatan untuk Admin (khusus sakit) -->
                <div class="form-group" id="adminNotesSection" style="display: none;">
                    <label class="form-label">Catatan untuk Admin</label>
                    <div class="admin-note-info">
                        <small style="color: #6b7280; display: block; margin-bottom: 8px;">
                            💡 Informasi ini akan membantu admin memproses pengajuan Anda dengan lebih baik
                        </small>
                    </div>
                    <textarea class="form-textarea" name="admin_notes" id="adminNotes" placeholder="Contoh: Sakit demam tinggi, sudah ke dokter, perlu istirahat 2 hari..." style="min-height: 80px;"></textarea>
                </div>

                <input type="hidden" name="priority" value="medium" id="priorityInput">

                <button type="submit" class="submit-btn" id="submitBtn">Ajukan Izin</button>
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
                                <div style="display:flex;flex-direction:column;gap:6px;">
                                    <div style="display:flex;justify-content:space-between;align-items:center;">
                                        <span style="font-size:13px;color:#6b7280;font-weight:500;">Kategori: <b>{{ ucfirst($complaint->category) }}</b></span>
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
                                    <div style="font-size:15px;font-weight:600;color:#333;">Judul: {{ $complaint->title }}</div>
                                    <div style="font-size:13px;color:#374151;">Alasan: {{ $complaint->description }}</div>
                                    <div style="font-size:13px;color:#6b7280;">Periode: {{ $complaint->start_date }} s/d {{ $complaint->end_date }}</div>
                                    <div style="font-size:12px;color:#6b7280;">Diajukan: {{ $complaint->created_at->format('d M Y H:i') }}</div>
                                    @if($complaint->response)
                                        <div style="margin-top:8px;padding:10px;background:{{ $complaint->status == 'rejected' ? '#fee2e2' : '#d1fae5' }};border-left:3px solid {{ $complaint->status == 'rejected' ? '#ef4444' : '#10b981' }};border-radius:6px">
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

    <script src="{{ asset('components/popup.js') }}" onerror="console.error('❌ Failed to load popup.js')"></script>
    <script>
// ========================================
// FIXED LEAVE TYPE SELECTION - No More Bugs
// ========================================

let selectedLeaveType = null;

// Ensure DOM is fully loaded
document.addEventListener('DOMContentLoaded', function() {
    console.log('✅ Initializing leave type selection...');
    
    // Get all leave type elements
    const leaveTypes = document.querySelectorAll('.leave-type');
    console.log(`Found ${leaveTypes.length} leave types`);
    
    // Force enable pointer events
    leaveTypes.forEach(type => {
        type.style.pointerEvents = 'auto';
        type.style.cursor = 'pointer';
    });
    
    // Setup click handlers with proper event handling
    leaveTypes.forEach((leaveType) => {
        // Remove any existing listeners first
        const newLeaveType = leaveType.cloneNode(true);
        leaveType.parentNode.replaceChild(newLeaveType, leaveType);
        
        // Add fresh click listener
        newLeaveType.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            console.log('🎯 Leave type clicked:', this.dataset.type);
            
            // Remove selection from all
            document.querySelectorAll('.leave-type').forEach(t => {
                t.classList.remove('selected');
                t.style.outline = 'none';
            });
            
            // Add selection to clicked item
            this.classList.add('selected');
            this.style.outline = '2px solid #1ec7e6';
            
            // Update selected type
            selectedLeaveType = this.dataset.type;
            console.log('✅ Selected leave type:', selectedLeaveType);
            
            // Update hidden input
            const categoryInput = document.getElementById('categoryInput');
            if (categoryInput) {
                categoryInput.value = selectedLeaveType;
                console.log('✅ Category input updated:', categoryInput.value);
            }
            
            // Handle UI changes based on type
            handleLeaveTypeChange(selectedLeaveType);
            
            // Visual feedback
            this.style.transform = 'scale(0.95)';
            setTimeout(() => {
                this.style.transform = 'scale(1)';
            }, 100);
        });
        
        // Add hover effects
        newLeaveType.addEventListener('mouseenter', function() {
            if (!this.classList.contains('selected')) {
                this.style.outline = '2px solid #94a3b8';
            }
        });
        
        newLeaveType.addEventListener('mouseleave', function() {
            if (!this.classList.contains('selected')) {
                this.style.outline = 'none';
            }
        });
    });
    
    // Setup form submission
    setupFormSubmission();
    
    // Auto-select from URL parameter
    autoSelectFromURL();
    
    console.log('✅ Leave type selection initialized');
});

// Handle different leave types
function handleLeaveTypeChange(leaveType) {
    const uploadSection = document.getElementById('uploadSection');
    const adminNotesSection = document.getElementById('adminNotesSection');
    const priorityInput = document.getElementById('priorityInput');
    const submitBtn = document.querySelector('.submit-btn');
    const uploadLabel = document.getElementById('uploadLabel');
    const uploadInfo = document.getElementById('uploadInfo');
    const uploadIcon = document.getElementById('uploadIcon');
    const uploadText = document.getElementById('uploadText');
    
    // Reset all sections
    uploadSection.style.display = 'none';
    adminNotesSection.style.display = 'none';
    priorityInput.value = 'medium';
    submitBtn.textContent = 'Ajukan Izin';
    submitBtn.style.background = 'linear-gradient(135deg, #1ec7e6, #0ea5e9)';
    
    switch(leaveType) {
        case 'sakit':
            uploadSection.style.display = 'block';
            adminNotesSection.style.display = 'block';
            uploadLabel.textContent = '📄 Upload Surat MC/Surat Dokter';
            uploadIcon.textContent = '🏥';
            uploadText.innerHTML = '<strong>Upload Surat MC/Surat Dokter</strong><br><small>PDF, JPG, PNG (max 5MB) - Wajib untuk verifikasi</small>';
            uploadInfo.style.display = 'block';
            uploadInfo.innerHTML = `
                <div style="background: #fef3c7; border: 1px solid #f59e0b; border-radius: 8px; padding: 12px; margin-bottom: 12px;">
                    <strong style="color: #92400e;">📋 Wajib dilampirkan:</strong>
                    <ul style="margin: 8px 0 0 20px; color: #78716c; font-size: 13px;">
                        <li>Surat Keterangan Dokter (MC)</li>
                        <li>Resep obat (jika ada)</li>
                        <li>Surat rujukan (jika ada)</li>
                    </ul>
                </div>
            `;
            showInfoPopup({
                title: '🏥 Izin Sakit - Perlu Surat MC',
                message: 'Untuk izin sakit wajib melampirkan:\n\n📄 Surat Keterangan Dokter (MC)\n💊 Resep obat (jika ada)\n🏥 Surat rujukan (jika perlu)\n\nAdmin akan memverifikasi dokumen untuk pencatatan yang akurat.',
                buttonText: 'Mengerti'
            });
            break;
            
        case 'mendadak':
            uploadSection.style.display = 'block';
            priorityInput.value = 'high';
            submitBtn.textContent = '⚡ Ajukan Segera';
            submitBtn.style.background = 'linear-gradient(135deg, #f59e0b, #d97706)';
            uploadLabel.textContent = '📎 Upload Bukti Pendukung';
            uploadIcon.textContent = '⚡';
            uploadText.innerHTML = '<strong>Upload Bukti Pendukung</strong><br><small>Foto, dokumen, atau bukti lainnya (max 5MB)</small>';
            uploadInfo.style.display = 'block';
            uploadInfo.innerHTML = `
                <div style="background: #fee2e2; border: 1px solid #f87171; border-radius: 8px; padding: 12px; margin-bottom: 12px;">
                    <strong style="color: #dc2626;">⚡ Izin Mendadak:</strong>
                    <ul style="margin: 8px 0 0 20px; color: #7f1d1d; font-size: 13px;">
                        <li>Jelaskan kondisi darurat dengan detail</li>
                        <li>Upload bukti jika memungkinkan (foto, surat, dll)</li>
                        <li>Pengajuan akan diproses prioritas tinggi</li>
                    </ul>
                </div>
            `;
            showInfoPopup({
                title: '⚡ Izin Mendadak - Prioritas Tinggi',
                message: 'Untuk izin mendadak:\n\n📝 Berikan alasan yang sangat jelas\n📎 Upload bukti pendukung jika ada\n⚡ Pengajuan akan diprioritaskan\n📬 Notifikasi langsung ke admin/atasan\n\nAdmin akan segera memproses pengajuan ini.',
                buttonText: 'Mengerti'
            });
            break;
            
        case 'keluarga':
            showInfoPopup({
                title: '👨‍👩‍👧‍👦 Urusan Keluarga',
                message: 'Untuk urusan keluarga:\n• Jelaskan keperluan keluarga\n• Tentukan durasi yang diperlukan\n• Berikan pemberitahuan sejauh mungkin',
                buttonText: 'Mengerti'
            });
            break;
        case 'cuti':
            showInfoPopup({
                title: '🏖️ Cuti Tahunan',
                message: 'Untuk cuti tahunan:\n• Ajukan minimal 3 hari sebelumnya\n• Pastikan tidak bentrok dengan jadwal penting\n• Sisa cuti Anda akan dipotong sesuai durasi',
                buttonText: 'Mengerti'
            });
            break;
    }
}

// Setup form submission with validation
function setupFormSubmission() {
    const form = document.getElementById('leaveForm');
    
    form.addEventListener('submit', function(e) {
        console.log('📝 Form submission triggered');
        console.log('Selected type:', selectedLeaveType);
        
        // Validate leave type selection
        if (!selectedLeaveType) {
            e.preventDefault();
            console.log('❌ No leave type selected');
            showErrorPopup({
                title: 'Pilih Jenis Izin',
                message: 'Silakan pilih jenis izin terlebih dahulu',
                buttonText: 'OK'
            });
            return false;
        }
        
        // Validate MC for sick leave
        if (selectedLeaveType === 'sakit') {
            const attachment = document.getElementById('attachment').files[0];
            if (!attachment) {
                e.preventDefault();
                showErrorPopup({
                    title: '🏥 Surat MC Wajib',
                    message: 'Untuk izin sakit, Anda wajib melampirkan Surat Keterangan Dokter (MC) atau dokumen medis lainnya untuk verifikasi admin.',
                    buttonText: 'OK'
                });
                return false;
            }
        }
        
        // Validate date range
        const startDate = document.getElementById('startDate').value;
        const endDate = document.getElementById('endDate').value;
        
        if (new Date(startDate) > new Date(endDate)) {
            e.preventDefault();
            showErrorPopup({
                title: 'Tanggal Tidak Valid',
                message: 'Tanggal mulai tidak boleh lebih besar dari tanggal selesai',
                buttonText: 'OK'
            });
            return false;
        }
        
        console.log('✅ Form validation passed, submitting...');
        return true;
    });
}

// Auto-select leave type from URL parameter
function autoSelectFromURL() {
    const urlParams = new URLSearchParams(window.location.search);
    const preSelectedType = urlParams.get('type');
    
    if (preSelectedType) {
        setTimeout(() => {
            const targetLeaveType = document.querySelector(`[data-type="${preSelectedType}"]`);
            if (targetLeaveType) {
                console.log('🎯 Auto-selecting type from URL:', preSelectedType);
                targetLeaveType.click();
                targetLeaveType.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }, 500);
    }
}

// Handle file upload
document.getElementById('attachment')?.addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('filePreview');
    const placeholder = document.querySelector('.upload-placeholder');
    
    if (file) {
        // Check file size (5MB limit)
        if (file.size > 5 * 1024 * 1024) {
            showErrorPopup({
                title: 'File Terlalu Besar',
                message: 'Ukuran file maksimal 5MB. Silakan pilih file yang lebih kecil.',
                buttonText: 'OK'
            });
            e.target.value = '';
            return;
        }
        
        placeholder.style.display = 'none';
        preview.style.display = 'block';
        preview.innerHTML = `
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <div>
                    <strong>📎 ${file.name}</strong><br>
                    <small>${(file.size / 1024).toFixed(1)} KB</small>
                </div>
                <button type="button" onclick="removeFile()" style="background: #ef4444; color: white; border: none; padding: 4px 8px; border-radius: 4px; font-size: 12px;">✕</button>
            </div>
        `;
    }
});

function removeFile() {
    document.getElementById('attachment').value = '';
    document.getElementById('filePreview').style.display = 'none';
    document.querySelector('.upload-placeholder').style.display = 'block';
}

// Set minimum date to today
const today = new Date().toISOString().split('T')[0];
document.getElementById('startDate').min = today;
document.getElementById('endDate').min = today;

// Update end date minimum when start date changes
document.getElementById('startDate').addEventListener('change', function() {
    document.getElementById('endDate').min = this.value;
});
    </script>
</body>
</html>