<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="theme-color" content="#1ec7e6">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <title>Buat Laporan - Sistem Absensi</title>
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

        /* Info Card */
        .info-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }

        .info-card-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .info-card-text {
            font-size: 13px;
            opacity: 0.9;
            line-height: 1.5;
        }

        /* Form Section */
        .form-section {
            background: white;
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        }

        .section-title {
            font-size: 16px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group:last-child {
            margin-bottom: 0;
        }

        .form-label {
            display: block;
            font-size: 14px;
            color: #374151;
            font-weight: 500;
            margin-bottom: 8px;
        }

        .form-label .required {
            color: #ef4444;
        }

        .form-input,
        .form-select,
        .form-textarea {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #d1d5db;
            border-radius: 10px;
            font-size: 14px;
            transition: all 0.2s ease;
            background-color: white;
            font-family: Arial, sans-serif;
        }

        .form-input:focus,
        .form-select:focus,
        .form-textarea:focus {
            outline: none;
            border-color: #1ec7e6;
            box-shadow: 0 0 0 3px rgba(30, 199, 230, 0.1);
        }

        .form-select {
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%236b7280' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            padding-right: 40px;
        }

        .form-textarea {
            resize: vertical;
            min-height: 120px;
        }

        .form-hint {
            font-size: 12px;
            color: #6b7280;
            margin-top: 6px;
        }

        /* File Upload */
        .file-upload-wrapper {
            position: relative;
            border: 2px dashed #d1d5db;
            border-radius: 10px;
            padding: 24px;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .file-upload-wrapper:hover {
            border-color: #1ec7e6;
            background-color: #f0f9ff;
        }

        .file-upload-wrapper.has-file {
            border-color: #10b981;
            background-color: #f0fdf4;
        }

        .file-input {
            display: none;
        }

        .upload-icon {
            font-size: 40px;
            margin-bottom: 12px;
        }

        .upload-text {
            font-size: 14px;
            color: #6b7280;
            margin-bottom: 4px;
        }

        .upload-hint {
            font-size: 12px;
            color: #9ca3af;
        }

        .file-preview {
            display: none;
            margin-top: 12px;
            padding: 12px;
            background: #f9fafb;
            border-radius: 8px;
            text-align: left;
        }

        .file-preview.show {
            display: block;
        }

        .file-name {
            font-size: 13px;
            color: #374151;
            margin-bottom: 4px;
            word-break: break-all;
        }

        .file-size {
            font-size: 12px;
            color: #6b7280;
        }

        .remove-file-btn {
            background: #ef4444;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 12px;
            cursor: pointer;
            margin-top: 8px;
        }

        /* Priority Tags */
        .priority-options {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
        }

        .priority-option {
            padding: 12px;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s ease;
            background: white;
        }

        .priority-option input[type="radio"] {
            display: none;
        }

        .priority-option:hover {
            border-color: #d1d5db;
        }

        .priority-option input[type="radio"]:checked + label {
            background: white;
        }

        .priority-option.low input[type="radio"]:checked ~ label {
            border-color: #10b981;
            background: #f0fdf4;
            color: #10b981;
        }

        .priority-option.medium input[type="radio"]:checked ~ label {
            border-color: #f59e0b;
            background: #fffbeb;
            color: #f59e0b;
        }

        .priority-option.high input[type="radio"]:checked ~ label {
            border-color: #ef4444;
            background: #fef2f2;
            color: #ef4444;
        }

        .priority-label {
            cursor: pointer;
            font-size: 13px;
            font-weight: 500;
            color: #6b7280;
        }

        .priority-icon {
            font-size: 20px;
            margin-bottom: 4px;
        }

        /* Buttons */
        .button-group {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-top: 24px;
        }

        .btn {
            padding: 14px 24px;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-cancel {
            background: #f3f4f6;
            color: #6b7280;
        }

        .btn-cancel:hover {
            background: #e5e7eb;
        }

        .btn-submit {
            background: #1ec7e6;
            color: white;
        }

        .btn-submit:hover {
            background: #0ea5e9;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(30, 199, 230, 0.3);
        }

        .btn-submit:disabled {
            background: #d1d5db;
            cursor: not-allowed;
            transform: none;
        }

        /* Success Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }

        .modal.show {
            display: flex;
        }

        .modal-content {
            background: white;
            border-radius: 20px;
            padding: 32px 24px;
            max-width: 320px;
            width: 90%;
            text-align: center;
            animation: modalSlideIn 0.3s ease;
        }

        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: scale(0.9);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .success-icon {
            width: 64px;
            height: 64px;
            background: #10b981;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            margin: 0 auto 16px;
        }

        .modal-title {
            font-size: 20px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px;
        }

        .modal-text {
            font-size: 14px;
            color: #6b7280;
            margin-bottom: 24px;
        }

        .modal-btn {
            background: #1ec7e6;
            color: white;
            border: none;
            padding: 12px 32px;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
        }

        /* Character Counter */
        .char-counter {
            text-align: right;
            font-size: 12px;
            color: #9ca3af;
            margin-top: 6px;
        }

        .char-counter.warning {
            color: #f59e0b;
        }

        .char-counter.danger {
            color: #ef4444;
        }
    </style>
</head>
<body>
    <div class="header">
        <button class="back-btn" onclick="goBack()">←</button>
        <div class="header-title">Buat Laporan</div>
    </div>

    <div class="content">
        <!-- Info Card -->
        <div class="info-card">
            <div class="info-card-title">📝 Laporkan Masalah Anda</div>
            <div class="info-card-text">
                Gunakan form ini untuk melaporkan masalah terkait absensi, sistem, atau memberikan feedback. Tim kami akan menindaklanjuti laporan Anda.
            </div>
        </div>

        <!-- Form Section -->
        <form id="reportForm" class="form-section">
            <div class="section-title">
                <span>📋</span> Informasi Laporan
            </div>

            <div class="form-group">
                <label class="form-label">
                    Kategori Laporan <span class="required">*</span>
                </label>
                <select class="form-select" id="category" required>
                    <option value="">Pilih Kategori</option>
                    <option value="absensi">Masalah Absensi</option>
                    <option value="sistem">Error Sistem</option>
                    <option value="izin">Pengajuan Izin/Cuti</option>
                    <option value="gaji">Pertanyaan Gaji</option>
                    <option value="jadwal">Masalah Jadwal</option>
                    <option value="lembur">Lembur</option>
                    <option value="feedback">Feedback & Saran</option>
                    <option value="lainnya">Lainnya</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">
                    Judul Laporan <span class="required">*</span>
                </label>
                <input 
                    type="text" 
                    class="form-input" 
                    id="title"
                    placeholder="Ringkasan singkat masalah Anda"
                    maxlength="100"
                    required
                >
                <div class="char-counter" id="titleCounter">0/100</div>
            </div>

            <div class="form-group">
                <label class="form-label">
                    Deskripsi Detail <span class="required">*</span>
                </label>
                <textarea 
                    class="form-textarea" 
                    id="description"
                    placeholder="Jelaskan masalah Anda secara detail. Sertakan informasi seperti tanggal, waktu, dan langkah yang sudah Anda coba..."
                    maxlength="1000"
                    required
                ></textarea>
                <div class="char-counter" id="descCounter">0/1000</div>
                <div class="form-hint">
                    Minimal 20 karakter. Semakin detail, semakin cepat kami dapat membantu.
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">
                    Prioritas <span class="required">*</span>
                </label>
                <div class="priority-options">
                    <div class="priority-option low">
                        <input type="radio" name="priority" id="lowPriority" value="low" required>
                        <label for="lowPriority" class="priority-label">
                            <div class="priority-icon">🟢</div>
                            <div>Rendah</div>
                        </label>
                    </div>
                    <div class="priority-option medium">
                        <input type="radio" name="priority" id="mediumPriority" value="medium">
                        <label for="mediumPriority" class="priority-label">
                            <div class="priority-icon">🟡</div>
                            <div>Sedang</div>
                        </label>
                    </div>
                    <div class="priority-option high">
                        <input type="radio" name="priority" id="highPriority" value="high">
                        <label for="highPriority" class="priority-label">
                            <div class="priority-icon">🔴</div>
                            <div>Tinggi</div>
                        </label>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">
                    Tanggal Kejadian
                </label>
                <input 
                    type="date" 
                    class="form-input" 
                    id="incidentDate"
                    max="2025-11-01"
                >
            </div>

            <div class="form-group">
                <label class="form-label">
                    Lampiran (Opsional)
                </label>
                <div class="file-upload-wrapper" id="fileUploadWrapper" onclick="document.getElementById('fileInput').click()">
                    <input 
                        type="file" 
                        id="fileInput" 
                        class="file-input"
                        accept="image/*,.pdf,.doc,.docx"
                        onchange="handleFileSelect(event)"
                    >
                    <div class="upload-icon">📎</div>
                    <div class="upload-text">Klik untuk upload file</div>
                    <div class="upload-hint">PNG, JPG, PDF (Max 5MB)</div>
                    
                    <div class="file-preview" id="filePreview">
                        <div class="file-name" id="fileName"></div>
                        <div class="file-size" id="fileSize"></div>
                        <button type="button" class="remove-file-btn" onclick="removeFile(event)">Hapus File</button>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">
                    Email untuk Update (Opsional)
                </label>
                <input 
                    type="email" 
                    class="form-input" 
                    id="email"
                    placeholder="email@example.com"
                    value=""
                >
                <div class="form-hint">
                    Kami akan mengirim update status laporan ke email ini
                </div>
            </div>

            <div class="button-group">
                <button type="button" class="btn btn-cancel" onclick="cancelReport()">Batal</button>
                <button type="submit" class="btn btn-submit">Kirim Laporan</button>
            </div>
        </form>
    </div>

    <!-- Success Modal -->
    <div class="modal" id="successModal">
        <div class="modal-content">
            <div class="success-icon">✓</div>
            <div class="modal-title">Laporan Berhasil Dikirim!</div>
            <div class="modal-text">
                Laporan Anda telah diterima. Nomor tiket: <strong id="ticketNumber"></strong>
                <br><br>
                Kami akan menindaklanjuti dalam 1-2 hari kerja.
            </div>
            <button class="modal-btn" onclick="closeModal()">OK, Mengerti</button>
        </div>
    </div>

    <script>
        // Character Counter
        const titleInput = document.getElementById('title');
        const titleCounter = document.getElementById('titleCounter');
        const descInput = document.getElementById('description');
        const descCounter = document.getElementById('descCounter');

        titleInput.addEventListener('input', function() {
            const count = this.value.length;
            titleCounter.textContent = `${count}/100`;
            if (count > 90) {
                titleCounter.classList.add('danger');
            } else if (count > 80) {
                titleCounter.classList.add('warning');
                titleCounter.classList.remove('danger');
            } else {
                titleCounter.classList.remove('warning', 'danger');
            }
        });

        descInput.addEventListener('input', function() {
            const count = this.value.length;
            descCounter.textContent = `${count}/1000`;
            if (count > 950) {
                descCounter.classList.add('danger');
            } else if (count > 900) {
                descCounter.classList.add('warning');
                descCounter.classList.remove('danger');
            } else {
                descCounter.classList.remove('warning', 'danger');
            }
        });

        // Priority Selection
        document.querySelectorAll('.priority-option').forEach(option => {
            option.addEventListener('click', function() {
                const radio = this.querySelector('input[type="radio"]');
                radio.checked = true;
            });
        });

        // File Upload
        function handleFileSelect(event) {
            const file = event.target.files[0];
            if (file) {
                // Check file size (5MB max)
                if (file.size > 5 * 1024 * 1024) {
                    alert('Ukuran file terlalu besar. Maksimal 5MB');
                    event.target.value = '';
                    return;
                }

                // Show file preview
                document.getElementById('fileName').textContent = file.name;
                document.getElementById('fileSize').textContent = formatFileSize(file.size);
                document.getElementById('filePreview').classList.add('show');
                document.getElementById('fileUploadWrapper').classList.add('has-file');
            }
        }

        function removeFile(event) {
            event.stopPropagation();
            document.getElementById('fileInput').value = '';
            document.getElementById('filePreview').classList.remove('show');
            document.getElementById('fileUploadWrapper').classList.remove('has-file');
        }

        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
        }

        // Form Submit
        document.getElementById('reportForm').addEventListener('submit', function(e) {
            e.preventDefault();

            // Validate description length
            const description = document.getElementById('description').value;
            if (description.length < 20) {
                alert('Deskripsi minimal 20 karakter');
                return;
            }

            // Collect form data
            const formData = {
                category: document.getElementById('category').value,
                title: document.getElementById('title').value,
                description: description,
                priority: document.querySelector('input[name="priority"]:checked').value,
                incidentDate: document.getElementById('incidentDate').value,
                email: document.getElementById('email').value,
                file: document.getElementById('fileInput').files[0]?.name || null,
                timestamp: new Date().toISOString()
            };

            // Generate ticket number
            const ticketNumber = 'TKT-' + Date.now().toString().slice(-8);
            
            // Save to localStorage
            const reports = JSON.parse(localStorage.getItem('customerReports') || '[]');
            reports.push({
                ...formData,
                ticketNumber: ticketNumber,
                status: 'pending'
            });
            localStorage.setItem('customerReports', JSON.stringify(reports));

            // Show success modal
            document.getElementById('ticketNumber').textContent = ticketNumber;
            document.getElementById('successModal').classList.add('show');
        });

        function closeModal() {
            document.getElementById('successModal').classList.remove('show');
            // Reset form
            document.getElementById('reportForm').reset();
            titleCounter.textContent = '0/100';
            descCounter.textContent = '0/1000';
            removeFile(new Event('click'));
            // Redirect back
            setTimeout(() => {
                goBack();
            }, 500);
        }

        function cancelReport() {
            if (confirm('Batalkan pembuatan laporan? Data yang sudah diisi akan hilang.')) {
                goBack();
            }
        }

        function goBack() {
            if (typeof smartGoBack === 'function') {
                smartGoBack('{{ route("dashboard") }}');
            } else {
                // Fallback navigation
                if (window.history.length > 1 && document.referrer && 
                    document.referrer !== window.location.href &&
                    !document.referrer.includes('login')) {
                    try {
                        window.history.back();
                    } catch (error) {
                        window.location.href = '{{ route("dashboard") }}';
                    }
                } else {
                    window.location.href = '{{ route("dashboard") }}';
                }
            }
        }

        // Set max date to today
        document.getElementById('incidentDate').max = new Date().toISOString().split('T')[0];
    </script>
</body>
</html>
