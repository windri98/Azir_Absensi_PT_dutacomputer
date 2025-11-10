<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>QR Scan Absensi - Sistem Absensi</title>
    <link rel="stylesheet" href="/components/popup.css">
    <!-- QR Code Scanner Library -->
    <script src="https://cdn.jsdelivr.net/npm/qr-scanner@1.4.2/qr-scanner.umd.min.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            background-color: #000;
            width: 100%;
            max-width: 393px;
            height: 100vh;
            min-height: 100vh;
            margin: 0 auto;
            overflow: hidden;
            position: relative;
        }
        
        @media (min-width: 394px) {
            body {
                box-shadow: 0 0 20px rgba(0, 0, 0, 0.3);
            }
        }
        
        /* Camera View */
        .camera-container {
            position: relative;
            width: 100%;
            height: 100vh;
            background: #000;
        }
        #qr-video {
            width: 100%;
            height: 100vh;
            object-fit: cover;
        }
        
        /* Overlay */
        .camera-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 10;
            pointer-events: none;
        }
        .camera-overlay > * {
            pointer-events: auto;
        }
        
        /* Header */
        .scan-header {
            position: absolute;
            top: 30px;
            left: 20px;
            right: 20px;
            z-index: 20;
            padding-top: 20px;
        }
        .header-content {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .back-btn {
            background: rgba(0, 0, 0, 0.6);
            color: white;
            border: none;
            padding: 12px;
            border-radius: 50%;
            font-size: 18px;
            cursor: pointer;
            width: 45px;
            height: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(10px);
        }
        .header-title {
            flex: 1;
            color: white;
            text-align: center;
        }
        .header-title h1 {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 4px;
        }
        .header-title p {
            font-size: 14px;
            opacity: 0.8;
        }
        
        /* Scan Frame */
        .scan-frame {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            margin-top: -20px;
            width: 250px;
            height: 250px;
            border: 2px solid #1ec7e6;
            border-radius: 20px;
            z-index: 15;
        }
        .scan-frame::before,
        .scan-frame::after {
            content: '';
            position: absolute;
            width: 40px;
            height: 40px;
            border: 3px solid #1ec7e6;
        }
        .scan-frame::before {
            top: -3px;
            left: -3px;
            border-right: none;
            border-bottom: none;
            border-radius: 20px 0 0 0;
        }
        .scan-frame::after {
            bottom: -3px;
            right: -3px;
            border-left: none;
            border-top: none;
            border-radius: 0 0 20px 0;
        }
        
        /* Additional corners */
        .scan-corner-tl,
        .scan-corner-tr,
        .scan-corner-bl,
        .scan-corner-br {
            position: absolute;
            width: 40px;
            height: 40px;
            border: 3px solid #1ec7e6;
        }
        .scan-corner-tr {
            top: -3px;
            right: -3px;
            border-left: none;
            border-bottom: none;
            border-radius: 0 20px 0 0;
        }
        .scan-corner-bl {
            bottom: -3px;
            left: -3px;
            border-right: none;
            border-top: none;
            border-radius: 0 0 0 20px;
        }
        
        /* Scan Line Animation */
        .scan-line {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, transparent, #1ec7e6, transparent);
            animation: scan 2s linear infinite;
        }
        @keyframes scan {
            0% { transform: translateY(0); }
            100% { transform: translateY(246px); }
        }
        
        /* Instructions */
        .scan-instructions {
            position: absolute;
            bottom: 120px;
            left: 20px;
            right: 20px;
            text-align: center;
            color: white;
            z-index: 20;
        }
        .instruction-text {
            font-size: 16px;
            font-weight: 500;
            margin-bottom: 8px;
        }
        .instruction-subtext {
            font-size: 14px;
            opacity: 0.8;
        }
        
        /* Controls */
        .scan-controls {
            position: absolute;
            bottom: 40px;
            left: 20px;
            right: 20px;
            display: flex;
            justify-content: center;
            gap: 20px;
            z-index: 20;
        }
        .control-btn {
            background: rgba(0, 0, 0, 0.6);
            color: white;
            border: none;
            padding: 16px;
            border-radius: 50%;
            font-size: 20px;
            cursor: pointer;
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
        }
        .control-btn:hover {
            background: rgba(30, 199, 230, 0.8);
        }
        
        /* Manual Input Panel */
        .manual-panel {
            position: absolute;
            bottom: -300px;
            left: 0;
            right: 0;
            background: white;
            border-radius: 20px 20px 0 0;
            padding: 30px 20px;
            transition: bottom 0.3s ease;
            z-index: 25;
        }
        .manual-panel.show {
            bottom: 0;
        }
        .panel-header {
            text-align: center;
            margin-bottom: 20px;
        }
        .panel-title {
            font-size: 18px;
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
        }
        .panel-subtitle {
            font-size: 14px;
            color: #666;
        }
        .manual-input {
            width: 100%;
            padding: 16px;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 16px;
            text-align: center;
            margin-bottom: 20px;
            letter-spacing: 2px;
        }
        .manual-input:focus {
            outline: none;
            border-color: #1ec7e6;
        }
        .manual-buttons {
            display: flex;
            gap: 12px;
        }
        .manual-btn {
            flex: 1;
            padding: 14px;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .manual-btn.primary {
            background: linear-gradient(135deg, #1ec7e6, #0ea5e9);
            color: white;
        }
        .manual-btn.secondary {
            background: #f3f4f6;
            color: #374151;
        }
        
        /* Status Indicator */
        .scan-status {
            position: absolute;
            top: 120px;
            left: 20px;
            right: 20px;
            text-align: center;
            z-index: 20;
        }
        .status-indicator {
            display: inline-block;
            padding: 8px 16px;
            background: rgba(0, 0, 0, 0.6);
            color: white;
            border-radius: 20px;
            font-size: 14px;
            backdrop-filter: blur(10px);
        }
        .status-indicator.success {
            background: rgba(16, 185, 129, 0.8);
        }
        .status-indicator.error {
            background: rgba(239, 68, 68, 0.8);
        }
    </style>
</head>
<body>
    <div class="camera-container">
        <video id="qr-video" playsinline></video>
        
        <div class="camera-overlay">
            <!-- Header -->
            <div class="scan-header">
                <div class="header-content">
                    <button class="back-btn" onclick="goBack()">←</button>
                    <div class="header-title">
                        <h1>Scan QR Code</h1>
                        <p>Arahkan kamera ke QR code</p>
                    </div>
                </div>
            </div>
            
            <!-- Scan Status -->
            <div class="scan-status">
                <div class="status-indicator" id="scanStatus">Mencari QR Code...</div>
            </div>
            
            <!-- Scan Frame -->
            <div class="scan-frame">
                <div class="scan-corner-tr"></div>
                <div class="scan-corner-bl"></div>
                <div class="scan-line"></div>
            </div>
            
            <!-- Instructions -->
            <div class="scan-instructions">
                <div class="instruction-text">Posisikan QR code di dalam frame</div>
                <div class="instruction-subtext">QR code akan otomatis terdeteksi</div>
            </div>
            
            <!-- Controls -->
            <div class="scan-controls">
                <button class="control-btn" onclick="toggleFlash()" id="flashBtn">🔦</button>
                <button class="control-btn" onclick="toggleManualInput()">⌨️</button>
                <button class="control-btn" onclick="switchCamera()" id="cameraBtn">🔄</button>
            </div>
        </div>
    </div>
    
    <!-- Manual Input Panel -->
    <div class="manual-panel" id="manualPanel">
        <div class="panel-header">
            <div class="panel-title">Input Manual</div>
            <div class="panel-subtitle">Masukkan kode QR secara manual</div>
        </div>
        <input type="text" class="manual-input" id="manualInput" placeholder="Masukkan kode QR" maxlength="20">
        <div class="manual-buttons">
            <button class="manual-btn secondary" onclick="toggleManualInput()">Batal</button>
            <button class="manual-btn primary" onclick="processManualInput()">Submit</button>
        </div>
    </div>

    <script src="components/popup.js"></script>
    <script>
        let qrScanner = null;
        let flashEnabled = false;
        let currentCamera = 'environment'; // 'user' for front, 'environment' for back
        
        function goBack() {
            if (qrScanner) {
                qrScanner.stop();
            }
            window.location.href = '{{ route("attendance.absensi") }}';
        }
        
        function initQRScanner() {
            const video = document.getElementById('qr-video');
            const statusIndicator = document.getElementById('scanStatus');
            
            qrScanner = new QrScanner(
                video,
                result => handleScanResult(result.data),
                {
                    onDecodeError: error => {
                        statusIndicator.textContent = 'Mencari QR Code...';
                        statusIndicator.className = 'status-indicator';
                    },
                    highlightScanRegion: false,
                    highlightCodeOutline: false,
                    preferredCamera: currentCamera
                }
            );
            
            qrScanner.start().then(() => {
                statusIndicator.textContent = 'Kamera siap, arahkan ke QR code';
                statusIndicator.className = 'status-indicator';
            }).catch(err => {
                statusIndicator.textContent = 'Gagal mengakses kamera';
                statusIndicator.className = 'status-indicator error';
                console.error('Camera error:', err);
            });
        }
        
        function handleScanResult(qrData) {
            const statusIndicator = document.getElementById('scanStatus');
            statusIndicator.textContent = 'QR Code terdeteksi!';
            statusIndicator.className = 'status-indicator success';
            
            // Stop scanner
            if (qrScanner) {
                qrScanner.stop();
            }
            
            // Process QR code data
            processQRCode(qrData);
        }
        
        function processQRCode(qrData) {
            try {
                // Expected QR format: "OFFICE:SHIFT:LOCATION"
                // Example: "MAIN_OFFICE:MORNING:LOBBY" or "BRANCH_A:NIGHT:ENTRANCE"
                const qrParts = qrData.split(':');
                
                if (qrParts.length !== 3) {
                    throw new Error('Invalid QR format');
                }
                
                const [office, shift, location] = qrParts;
                
                // Validate office code
                const validOffices = ['MAIN_OFFICE', 'BRANCH_A', 'BRANCH_B', 'WAREHOUSE'];
                if (!validOffices.includes(office)) {
                    throw new Error('Invalid office code');
                }
                
                // Validate shift
                const validShifts = ['MORNING', 'AFTERNOON', 'NIGHT', 'OVERTIME'];
                if (!validShifts.includes(shift)) {
                    throw new Error('Invalid shift code');
                }
                
                // Check if current time matches shift
                const currentHour = new Date().getHours();
                const shiftValidation = validateShiftTime(shift, currentHour);
                
                if (!shiftValidation.valid) {
                    showErrorPopup({
                        title: 'Shift Tidak Sesuai',
                        message: shiftValidation.message,
                        buttonText: 'OK',
                        onClose: () => {
                            initQRScanner(); // Restart scanner
                        }
                    });
                    return;
                }
                
                // Success - redirect to attendance with QR data
                const qrInfo = {
                    office: office,
                    shift: shift,
                    location: location,
                    timestamp: new Date().toISOString(),
                    qrCode: qrData
                };
                
                localStorage.setItem('qrScanResult', JSON.stringify(qrInfo));
                
                showSuccessPopup({
                    title: 'QR Code Valid!',
                    message: `Office: ${office.replace('_', ' ')}\nShift: ${shift}\nLocation: ${location}`,
                    buttonText: 'Lanjutkan',
                    onClose: () => {
                        window.location.href = shift === 'OVERTIME' ? 'clock-overtime' : 'clock-in';
                    }
                });
                
            } catch (error) {
                showErrorPopup({
                    title: 'QR Code Invalid',
                    message: 'QR code tidak valid atau tidak dikenali sistem',
                    buttonText: 'Scan Ulang',
                    onClose: () => {
                        initQRScanner(); // Restart scanner
                    }
                });
            }
        }
        
        function validateShiftTime(shift, currentHour) {
            const shiftTimes = {
                'MORNING': { start: 6, end: 14, name: 'Shift Pagi (06:00-14:00)' },
                'AFTERNOON': { start: 14, end: 22, name: 'Shift Siang (14:00-22:00)' },
                'NIGHT': { start: 22, end: 6, name: 'Shift Malam (22:00-06:00)' },
                'OVERTIME': { start: 0, end: 24, name: 'Lembur' }
            };
            
            const shiftInfo = shiftTimes[shift];
            
            if (shift === 'OVERTIME') {
                return { valid: true, message: '' };
            }
            
            if (shift === 'NIGHT') {
                // Night shift spans midnight
                const valid = currentHour >= 22 || currentHour < 6;
                return {
                    valid: valid,
                    message: valid ? '' : `Waktu saat ini tidak sesuai dengan ${shiftInfo.name}`
                };
            } else {
                const valid = currentHour >= shiftInfo.start && currentHour < shiftInfo.end;
                return {
                    valid: valid,
                    message: valid ? '' : `Waktu saat ini tidak sesuai dengan ${shiftInfo.name}`
                };
            }
        }
        
        function toggleFlash() {
            if (qrScanner && qrScanner.hasFlash()) {
                flashEnabled = !flashEnabled;
                qrScanner.toggleFlash();
                
                const flashBtn = document.getElementById('flashBtn');
                flashBtn.textContent = flashEnabled ? '🔦' : '🔦';
                flashBtn.style.background = flashEnabled ? 'rgba(30, 199, 230, 0.8)' : 'rgba(0, 0, 0, 0.6)';
            }
        }
        
        function switchCamera() {
            if (qrScanner) {
                currentCamera = currentCamera === 'environment' ? 'user' : 'environment';
                qrScanner.setCamera(currentCamera);
            }
        }
        
        function toggleManualInput() {
            const panel = document.getElementById('manualPanel');
            panel.classList.toggle('show');
            
            if (panel.classList.contains('show')) {
                document.getElementById('manualInput').focus();
                if (qrScanner) qrScanner.stop();
            } else {
                document.getElementById('manualInput').value = '';
                initQRScanner();
            }
        }
        
        function processManualInput() {
            const manualInput = document.getElementById('manualInput').value.trim();
            
            if (!manualInput) {
                showErrorPopup({
                    title: 'Input Kosong',
                    message: 'Silakan masukkan kode QR',
                    buttonText: 'OK'
                });
                return;
            }
            
            toggleManualInput();
            processQRCode(manualInput);
        }
        
        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Authentication is enforced server-side; avoid client-side redirect.

            // Check if browser supports camera
            if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                showErrorPopup({
                    title: 'Kamera Tidak Didukung',
                    message: 'Browser Anda tidak mendukung akses kamera',
                    buttonText: 'Kembali',
                    onClose: () => goBack()
                });
                return;
            }

            initQRScanner();
        });
        
        // Clean up when page is unloaded
        window.addEventListener('beforeunload', function() {
            if (qrScanner) {
                qrScanner.stop();
            }
        });
    </script>
</body>
</html>