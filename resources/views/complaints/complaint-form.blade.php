<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="theme-color" content="#1ec7e6">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <title>Buat Keluhan - Sistem Absensi</title>
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

        .history-btn {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
        }

        .content {
            padding: 20px;
        }

        .info-banner {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: white;
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 20px;
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }

        .info-icon {
            font-size: 24px;
            flex-shrink: 0;
        }

        .info-text {
            flex: 1;
            font-size: 13px;
            line-height: 1.5;
        }

        .form-section {
            background: white;
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-size: 14px;
            color: #374151;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .required {
            color: #ef4444;
        }

        .form-input,
        .form-select,
        .form-textarea {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            font-size: 14px;
            transition: all 0.2s ease;
            font-family: Arial, sans-serif;
        }

        .form-input:focus,
        .form-select:focus,
        .form-textarea:focus {
            outline: none;
            border-color: #1ec7e6;
            background: #f0f9ff;
        }

        .form-select {
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%236b7280' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
        }

        .form-textarea {
            min-height: 120px;
            resize: vertical;
        }

        .form-hint {
            font-size: 12px;
            color: #6b7280;
            margin-top: 6px;
        }

        .priority-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
        }

        .priority-card {
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            padding: 16px 12px;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s ease;
            position: relative;
        }

        .priority-card input {
            display: none;
        }

        .priority-card.selected {
            border-width: 3px;
        }

        .priority-card.low.selected {
            border-color: #10b981;
            background: #ecfdf5;
        }

        .priority-card.medium.selected {
            border-color: #f59e0b;
            background: #fffbeb;
        }

        .priority-card.high.selected {
            border-color: #ef4444;
            background: #fef2f2;
        }

        .priority-icon {
            font-size: 28px;
            margin-bottom: 8px;
        }

        .priority-label {
            font-size: 13px;
            font-weight: 600;
            color: #374151;
        }

        .file-upload {
            border: 2px dashed #d1d5db;
            border-radius: 10px;
            padding: 24px;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .file-upload:hover {
            border-color: #1ec7e6;
            background: #f0f9ff;
        }

        .file-upload.has-file {
            border-color: #10b981;
            background: #ecfdf5;
        }

        .upload-icon {
            font-size: 48px;
            margin-bottom: 12px;
        }

        .upload-text {
            font-size: 14px;
            color: #374151;
            font-weight: 600;
            margin-bottom: 4px;
        }

        .upload-hint {
            font-size: 12px;
            color: #6b7280;
        }

        .file-preview {
            display: none;
            margin-top: 16px;
            padding: 12px;
            background: white;
            border-radius: 8px;
            text-align: left;
        }

        .file-preview.show {
            display: block;
        }

        .file-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .file-icon {
            font-size: 32px;
        }

        .file-details {
            flex: 1;
        }

        .file-name {
            font-size: 14px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 4px;
        }

        .file-size {
            font-size: 12px;
            color: #6b7280;
        }

        .remove-file {
            background: #ef4444;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 12px;
            cursor: pointer;
        }

        .button-group {
            display: grid;
            grid-template-columns: 1fr 2fr;
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

        .btn-submit {
            background: #1ec7e6;
            color: white;
        }

        .btn-submit:hover {
            background: #0ea5e9;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(30, 199, 230, 0.3);
        }

        .success-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.6);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }

        .success-modal.show {
            display: flex;
        }

        .modal-content {
            background: white;
            border-radius: 20px;
            padding: 32px 24px;
            max-width: 320px;
            width: 90%;
            text-align: center;
            animation: modalPop 0.3s ease;
        }

        @keyframes modalPop {
            0% { transform: scale(0.8); opacity: 0; }
            100% { transform: scale(1); opacity: 1; }
        }

        .success-icon-wrapper {
            width: 80px;
            height: 80px;
            background: #10b981;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }

        .success-icon {
            font-size: 48px;
        }

        .modal-title {
            font-size: 20px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 12px;
        }

        .modal-ticket {
            font-size: 16px;
            color: #6b7280;
            margin-bottom: 8px;
        }

        .ticket-number {
            font-family: monospace;
            font-weight: bold;
            color: #1ec7e6;
            font-size: 18px;
        }

        .modal-text {
            font-size: 14px;
            color: #6b7280;
            line-height: 1.6;
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
    </style>
</head>
<body>
    <div class="header">
        <div class="header-left">
            <button class="back-btn" onclick="goBack()">←</button>
            <div class="header-title">Buat Keluhan</div>
        </div>
        <button class="history-btn" onclick="viewHistory()">Riwayat</button>
    </div>

    <div class="content">
        <div class="info-banner">
            <div class="info-icon">⚠️</div>
            <div class="info-text">
                Sampaikan keluhan Anda terkait masalah teknis atau sistem. Tim teknisi kami akan segera menindaklanjuti.
            </div>
        </div>

        <form id="complaintForm" class="form-section">
            <div class="form-group">
                <label class="form-label">
                    Kategori Masalah <span class="required">*</span>
                </label>
                <select class="form-select" id="category" required>
                    <option value="">Pilih Kategori</option>
                    <option value="hardware">Hardware/Perangkat</option>
                    <option value="software">Software/Aplikasi</option>
                    <option value="network">Jaringan/Internet</option>
                    <option value="system">Sistem Absensi</option>
                    <option value="access">Akses/Login</option>
                    <option value="other">Lainnya</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">
                    Judul Keluhan <span class="required">*</span>
                </label>
                <input 
                    type="text" 
                    class="form-input" 
                    id="title"
                    placeholder="Ringkasan masalah yang dialami"
                    maxlength="100"
                    required
                >
                <div class="form-hint">Maksimal 100 karakter</div>
            </div>

            <div class="form-group">
                <label class="form-label">
                    Deskripsi Masalah <span class="required">*</span>
                </label>
                <textarea 
                    class="form-textarea" 
                    id="description"
                    placeholder="Jelaskan masalah secara detail: kapan terjadi, apa yang terjadi, dan apa yang sudah dicoba..."
                    required
                ></textarea>
                <div class="form-hint">Semakin detail, semakin cepat teknisi dapat membantu</div>
            </div>

            <div class="form-group">
                <label class="form-label">
                    Lokasi/Ruangan
                </label>
                <input 
                    type="text" 
                    class="form-input" 
                    id="location"
                    placeholder="Contoh: Lantai 2, Ruang IT"
                >
            </div>

            <div class="form-group">
                <label class="form-label">
                    Tingkat Urgensi <span class="required">*</span>
                </label>
                <div class="priority-grid">
                    <label class="priority-card low">
                        <input type="radio" name="priority" value="low" required>
                        <div class="priority-icon">🟢</div>
                        <div class="priority-label">Rendah</div>
                    </label>
                    <label class="priority-card medium">
                        <input type="radio" name="priority" value="medium">
                        <div class="priority-icon">🟡</div>
                        <div class="priority-label">Sedang</div>
                    </label>
                    <label class="priority-card high">
                        <input type="radio" name="priority" value="high">
                        <div class="priority-icon">🔴</div>
                        <div class="priority-label">Tinggi</div>
                    </label>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">
                    Lampiran Foto (Opsional)
                </label>
                <div class="file-upload" id="fileUpload" onclick="document.getElementById('fileInput').click()">
                    <input 
                        type="file" 
                        id="fileInput" 
                        accept="image/*"
                        style="display: none;"
                        onchange="handleFileSelect(event)"
                    >
                    <div class="upload-icon">📷</div>
                    <div class="upload-text">Upload Foto Masalah</div>
                    <div class="upload-hint">PNG, JPG (Max 5MB)</div>
                    
                    <div class="file-preview" id="filePreview">
                        <div class="file-info">
                            <div class="file-icon">🖼️</div>
                            <div class="file-details">
                                <div class="file-name" id="fileName"></div>
                                <div class="file-size" id="fileSize"></div>
                            </div>
                            <button type="button" class="remove-file" onclick="removeFile(event)">✕</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="button-group">
                <button type="button" class="btn btn-cancel" onclick="cancelForm()">Batal</button>
                <button type="submit" class="btn btn-submit">Kirim Keluhan</button>
            </div>
        </form>
    </div>

    <div class="success-modal" id="successModal">
        <div class="modal-content">
            <div class="success-icon-wrapper">
                <div class="success-icon">✓</div>
            </div>
            <div class="modal-title">Keluhan Terkirim!</div>
            <div class="modal-ticket">
                Nomor Tiket:<br>
                <span class="ticket-number" id="ticketNumber"></span>
            </div>
            <div class="modal-text">
                Keluhan Anda telah diterima oleh tim teknisi. Kami akan segera menindaklanjuti masalah Anda.
            </div>
            <button class="modal-btn" onclick="closeModal()">OK, Mengerti</button>
        </div>
    </div>

    <script>
        // Priority card selection
        document.querySelectorAll('.priority-card').forEach(card => {
            card.addEventListener('click', function() {
                document.querySelectorAll('.priority-card').forEach(c => c.classList.remove('selected'));
                this.classList.add('selected');
                this.querySelector('input').checked = true;
            });
        });

        // File upload
        function handleFileSelect(event) {
            const file = event.target.files[0];
            if (file) {
                if (file.size > 5 * 1024 * 1024) {
                    alert('Ukuran file terlalu besar. Maksimal 5MB');
                    event.target.value = '';
                    return;
                }

                document.getElementById('fileName').textContent = file.name;
                document.getElementById('fileSize').textContent = formatFileSize(file.size);
                document.getElementById('filePreview').classList.add('show');
                document.getElementById('fileUpload').classList.add('has-file');
            }
        }

        function removeFile(event) {
            event.stopPropagation();
            document.getElementById('fileInput').value = '';
            document.getElementById('filePreview').classList.remove('show');
            document.getElementById('fileUpload').classList.remove('has-file');
        }

        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
        }

        // Form submit
        document.getElementById('complaintForm').addEventListener('submit', function(e) {
            e.preventDefault();

            // Generate ticket number
            const ticketNumber = 'KLH-' + Date.now().toString().slice(-8);

            // Get form data
            const complaintData = {
                ticketNumber: ticketNumber,
                category: document.getElementById('category').value,
                title: document.getElementById('title').value,
                description: document.getElementById('description').value,
                location: document.getElementById('location').value,
                priority: document.querySelector('input[name="priority"]:checked').value,
                file: document.getElementById('fileInput').files[0]?.name || null,
                status: 'pending',
                reportedBy: 'Widya Mayasari Fauziah',
                reportedById: 'EMP001',
                createdAt: new Date().toISOString(),
                updatedAt: new Date().toISOString()
            };

            // Save to localStorage
            const complaints = JSON.parse(localStorage.getItem('complaints') || '[]');
            complaints.unshift(complaintData);
            localStorage.setItem('complaints', JSON.stringify(complaints));

            // Show success modal
            document.getElementById('ticketNumber').textContent = ticketNumber;
            document.getElementById('successModal').classList.add('show');
        });

        function closeModal() {
            document.getElementById('successModal').classList.remove('show');
            document.getElementById('complaintForm').reset();
            document.querySelectorAll('.priority-card').forEach(c => c.classList.remove('selected'));
            removeFile(new Event('click'));
            
            setTimeout(() => {
                window.location.href = '{{ route('complaints.history') }}';
            }, 300);
        }

        function cancelForm() {
            if (confirm('Batalkan pembuatan keluhan? Data yang sudah diisi akan hilang.')) {
                goBack();
            }
        }

        function viewHistory() {
            window.location.href = '{{ route('complaints.history') }}';
        }

        function goBack() {
            window.history.back();
        }
    </script>
</body>
</html>
