<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Clock In Lembur - Sistem Absensi</title>
    <link rel="stylesheet" href="/components/popup.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
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
        }
        
        /* Header */
        .header {
            background: linear-gradient(135deg, #1ec7e6, #0ea5e9);
            padding: 50px 20px 30px;
            color: white;
            position: relative;
        }
        .header-content {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 20px;
        }
        .back-btn {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: none;
            padding: 10px;
            border-radius: 50%;
            font-size: 16px;
            cursor: pointer;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .header-title h1 {
            font-size: 24px;
            font-weight: 600;
        }
        .overtime-info {
            background: rgba(255, 255, 255, 0.1);
            padding: 15px;
            border-radius: 12px;
            margin-top: 15px;
        }
        .overtime-badge {
            display: inline-block;
            background: #ff6b35;
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 8px;
        }
        .overtime-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            font-size: 14px;
        }
        
        /* Current Time */
        .current-time {
            text-align: center;
            padding: 30px 20px;
            background: white;
            margin: 20px;
            border-radius: 16px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .time-display {
            font-size: 48px;
            font-weight: 700;
            color: #333;
            margin-bottom: 5px;
            letter-spacing: 2px;
        }
        .date-display {
            font-size: 16px;
            color: #666;
            margin-bottom: 15px;
        }
        .timezone {
            font-size: 14px;
            color: #999;
        }
        
        /* QR Info */
        .qr-info {
            margin: 0 20px 20px;
            background: white;
            border-radius: 16px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .qr-info h3 {
            color: #333;
            margin-bottom: 15px;
            font-size: 18px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .info-grid {
            display: grid;
            gap: 12px;
        }
        .info-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px;
            background: #f8fafc;
            border-radius: 8px;
        }
        .info-label {
            font-size: 14px;
            color: #666;
            font-weight: 500;
        }
        .info-value {
            font-size: 14px;
            color: #333;
            font-weight: 600;
        }
        .info-value.shift {
            background: #ff6b35;
            color: white;
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 12px;
        }
        
        /* Location Section */
        .location-section {
            margin: 0 20px 20px;
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .location-header {
            padding: 20px;
            border-bottom: 1px solid #e5e7eb;
        }
        .location-header h3 {
            color: #333;
            margin-bottom: 8px;
            font-size: 18px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .location-status {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
        }
        .status-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #10b981;
        }
        .status-dot.searching {
            background: #f59e0b;
            animation: pulse 1.5s infinite;
        }
        .status-dot.error {
            background: #ef4444;
        }
        
        #map {
            height: 200px;
            width: 100%;
        }
        
        .location-info {
            padding: 15px 20px;
            background: #f8fafc;
        }
        .location-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            font-size: 13px;
        }
        .location-detail {
            color: #666;
        }
        .location-value {
            color: #333;
            font-weight: 600;
        }
        
        /* Clock In Button */
        .clock-in-section {
            padding: 20px;
        }
        .clock-in-btn {
            width: 100%;
            background: linear-gradient(135deg, #ff6b35, #f59e0b);
            color: white;
            border: none;
            padding: 20px;
            border-radius: 16px;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(255, 107, 53, 0.3);
            margin-bottom: 15px;
        }
        .clock-in-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 107, 53, 0.4);
        }
        .clock-in-btn:disabled {
            background: #d1d5db;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }
        
        .btn-content {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
        }
        .btn-icon {
            font-size: 24px;
        }
        .btn-text {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .btn-title {
            font-size: 18px;
            font-weight: 600;
        }
        .btn-subtitle {
            font-size: 14px;
            opacity: 0.9;
        }
        
        /* Notes Section */
        .notes-section {
            margin: 0 20px 30px;
            background: white;
            border-radius: 16px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .notes-section h3 {
            color: #333;
            margin-bottom: 15px;
            font-size: 16px;
        }
        .notes-input {
            width: 100%;
            min-height: 80px;
            padding: 12px;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-size: 14px;
            font-family: Arial, sans-serif;
            resize: vertical;
        }
        .notes-input:focus {
            outline: none;
            border-color: #ff6b35;
        }
        .char-count {
            text-align: right;
            margin-top: 5px;
            font-size: 12px;
            color: #999;
        }
        
        /* Loading State */
        .loading-spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 2px solid #ffffff;
            border-radius: 50%;
            border-top-color: transparent;
            animation: spin 1s ease-in-out infinite;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
        
        /* Error/Success States */
        .error-message {
            background: #fee2e2;
            color: #dc2626;
            padding: 12px;
            border-radius: 8px;
            margin: 10px 20px;
            font-size: 14px;
            text-align: center;
        }
        .success-message {
            background: #dcfce7;
            color: #16a34a;
            padding: 12px;
            border-radius: 8px;
            margin: 10px 20px;
            font-size: 14px;
            text-align: center;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="header-content">
            <button class="back-btn" onclick="goBack()">←</button>
            <div class="header-title">
                <h1>Clock In Lembur</h1>
            </div>
        </div>
        <div class="overtime-info">
            <div class="overtime-badge">OVERTIME SHIFT</div>
            <div class="overtime-details">
                <div>
                    <strong>Jenis:</strong> Lembur
                </div>
                <div>
                    <strong>Rate:</strong> 1.5x
                </div>
                <div>
                    <strong>Min Duration:</strong> 2 jam
                </div>
                <div>
                    <strong>Max Duration:</strong> 8 jam
                </div>
            </div>
        </div>
    </div>

    <!-- Current Time -->
    <div class="current-time">
        <div class="time-display" id="currentTime">--:--:--</div>
        <div class="date-display" id="currentDate">Loading...</div>
        <div class="timezone">WIB (UTC+7)</div>
    </div>

    <!-- QR Code Info -->
    <div class="qr-info" id="qrInfoSection">
        <h3>📱 QR Code Information</h3>
        <div class="info-grid">
            <div class="info-item">
                <span class="info-label">Office</span>
                <span class="info-value" id="qrOffice">-</span>
            </div>
            <div class="info-item">
                <span class="info-label">Shift Type</span>
                <span class="info-value shift" id="qrShift">OVERTIME</span>
            </div>
            <div class="info-item">
                <span class="info-label">Location</span>
                <span class="info-value" id="qrLocation">-</span>
            </div>
            <div class="info-item">
                <span class="info-label">Scan Time</span>
                <span class="info-value" id="qrTime">-</span>
            </div>
        </div>
    </div>

    <!-- Location Section -->
    <div class="location-section">
        <div class="location-header">
            <h3>📍 Your Location</h3>
            <div class="location-status">
                <div class="status-dot searching" id="locationDot"></div>
                <span id="locationStatus">Getting location...</span>
            </div>
        </div>
        <div id="map"></div>
        <div class="location-info">
            <div class="location-details">
                <div class="location-detail">Latitude:</div>
                <div class="location-value" id="latitude">-</div>
                <div class="location-detail">Longitude:</div>
                <div class="location-value" id="longitude">-</div>
                <div class="location-detail">Accuracy:</div>
                <div class="location-value" id="accuracy">-</div>
                <div class="location-detail">Distance:</div>
                <div class="location-value" id="distance">Calculating...</div>
            </div>
        </div>
    </div>

    <!-- Notes Section -->
    <div class="notes-section">
        <h3>📝 Catatan Lembur (Opsional)</h3>
        <textarea 
            class="notes-input" 
            id="overtimeNotes" 
            placeholder="Jelaskan alasan atau detail pekerjaan lembur..."
            maxlength="200"
            oninput="updateCharCount()"
        ></textarea>
        <div class="char-count">
            <span id="charCount">0</span>/200 karakter
        </div>
    </div>

    <!-- Clock In Button -->
    <div class="clock-in-section">
        <button class="clock-in-btn" id="clockInBtn" onclick="clockInOvertime()" disabled>
            <div class="btn-content">
                <span class="btn-icon">⏰</span>
                <div class="btn-text">
                    <span class="btn-title">Clock In Lembur</span>
                    <span class="btn-subtitle">Tap untuk mulai lembur</span>
                </div>
            </div>
        </button>
    </div>

    <script src="components/popup.js"></script>
    <script>
        let map;
        let userMarker;
        let officeMarker;
        let currentPosition = null;
        let qrData = null;
        
        // Office coordinates (example)
        const officeLocations = {
            'MAIN_OFFICE': { lat: -6.2088, lng: 106.8456, name: 'Main Office' },
            'BRANCH_A': { lat: -6.1751, lng: 106.8650, name: 'Branch A' },
            'BRANCH_B': { lat: -6.2297, lng: 106.8175, name: 'Branch B' },
            'WAREHOUSE': { lat: -6.2615, lng: 106.7815, name: 'Warehouse' }
        };
        
        function goBack() {
            window.location.href = '{{ route("attendance.absensi") }}';
        }
        
        function updateTime() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('id-ID', { 
                hour12: false,
                timeZone: 'Asia/Jakarta'
            });
            const dateString = now.toLocaleDateString('id-ID', { 
                weekday: 'long', 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric',
                timeZone: 'Asia/Jakarta'
            });
            
            document.getElementById('currentTime').textContent = timeString;
            document.getElementById('currentDate').textContent = dateString;
        }
        
        function initMap() {
            // Default center (Jakarta)
            map = L.map('map').setView([-6.2088, 106.8456], 15);
            
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);
            
            getCurrentLocation();
        }
        
        function getCurrentLocation() {
            const locationStatus = document.getElementById('locationStatus');
            const locationDot = document.getElementById('locationDot');
            
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        currentPosition = {
                            lat: position.coords.latitude,
                            lng: position.coords.longitude,
                            accuracy: position.coords.accuracy
                        };
                        
                        updateLocationDisplay();
                        checkLocationValid();
                        
                        locationStatus.textContent = 'Location found';
                        locationDot.className = 'status-dot';
                    },
                    (error) => {
                        locationStatus.textContent = 'Location access denied';
                        locationDot.className = 'status-dot error';
                        console.error('Geolocation error:', error);
                    },
                    {
                        enableHighAccuracy: true,
                        timeout: 10000,
                        maximumAge: 60000
                    }
                );
            } else {
                locationStatus.textContent = 'Geolocation not supported';
                locationDot.className = 'status-dot error';
            }
        }
        
        function updateLocationDisplay() {
            if (!currentPosition) return;
            
            // Update coordinates display
            document.getElementById('latitude').textContent = currentPosition.lat.toFixed(6);
            document.getElementById('longitude').textContent = currentPosition.lng.toFixed(6);
            document.getElementById('accuracy').textContent = Math.round(currentPosition.accuracy) + 'm';
            
            // Update map
            map.setView([currentPosition.lat, currentPosition.lng], 17);
            
            // Add user marker
            if (userMarker) {
                map.removeLayer(userMarker);
            }
            userMarker = L.marker([currentPosition.lat, currentPosition.lng])
                .addTo(map)
                .bindPopup('Your Location')
                .openPopup();
            
            // Add office marker if QR data available
            if (qrData && officeLocations[qrData.office]) {
                const office = officeLocations[qrData.office];
                if (officeMarker) {
                    map.removeLayer(officeMarker);
                }
                officeMarker = L.marker([office.lat, office.lng], {
                    icon: L.icon({
                        iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-red.png',
                        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                        iconSize: [25, 41],
                        iconAnchor: [12, 41],
                        popupAnchor: [1, -34],
                        shadowSize: [41, 41]
                    })
                })
                .addTo(map)
                .bindPopup(office.name);
                
                // Calculate distance
                const distance = calculateDistance(
                    currentPosition.lat, currentPosition.lng,
                    office.lat, office.lng
                );
                document.getElementById('distance').textContent = distance < 1000 ? 
                    Math.round(distance) + 'm' : 
                    (distance / 1000).toFixed(1) + 'km';
            }
        }
        
        function calculateDistance(lat1, lng1, lat2, lng2) {
            const R = 6371000; // Earth's radius in meters
            const dLat = (lat2 - lat1) * Math.PI / 180;
            const dLng = (lng2 - lng1) * Math.PI / 180;
            const a = Math.sin(dLat/2) * Math.sin(dLat/2) +
                    Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                    Math.sin(dLng/2) * Math.sin(dLng/2);
            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
            return R * c;
        }
        
        function checkLocationValid() {
            const clockInBtn = document.getElementById('clockInBtn');
            
            if (!currentPosition || !qrData) {
                clockInBtn.disabled = true;
                return;
            }
            
            const office = officeLocations[qrData.office];
            if (!office) {
                clockInBtn.disabled = true;
                return;
            }
            
            const distance = calculateDistance(
                currentPosition.lat, currentPosition.lng,
                office.lat, office.lng
            );
            
            // Allow overtime check-in within 500m radius
            if (distance <= 500) {
                clockInBtn.disabled = false;
            } else {
                clockInBtn.disabled = true;
                showErrorPopup({
                    title: 'Lokasi Terlalu Jauh',
                    message: `Anda berada ${distance > 1000 ? (distance/1000).toFixed(1) + 'km' : Math.round(distance) + 'm'} dari kantor. Maksimal jarak untuk clock in lembur adalah 500m.`,
                    buttonText: 'OK'
                });
            }
        }
        
        function updateCharCount() {
            const notes = document.getElementById('overtimeNotes').value;
            document.getElementById('charCount').textContent = notes.length;
        }
        
        function clockInOvertime() {
            const clockInBtn = document.getElementById('clockInBtn');
            const notes = document.getElementById('overtimeNotes').value.trim();
            
            if (!currentPosition) {
                showErrorPopup({
                    title: 'Lokasi Tidak Ditemukan',
                    message: 'Silakan aktifkan GPS dan refresh halaman',
                    buttonText: 'OK'
                });
                return;
            }
            
            // Disable button and show loading
            clockInBtn.disabled = true;
            clockInBtn.innerHTML = `
                <div class="btn-content">
                    <div class="loading-spinner"></div>
                    <div class="btn-text">
                        <span class="btn-title">Processing...</span>
                        <span class="btn-subtitle">Sedang menyimpan data</span>
                    </div>
                </div>
            `;
            
            // Simulate API call delay
            setTimeout(() => {
                const attendanceData = {
                    type: 'clock-in-overtime',
                    timestamp: new Date().toISOString(),
                    location: currentPosition,
                    qrData: qrData,
                    notes: notes,
                    employee: JSON.parse(localStorage.getItem('userSession')),
                    date: new Date().toISOString().split('T')[0]
                };
                
                // Save to localStorage (replace with API call)
                saveOvertimeAttendance(attendanceData);
                
                showSuccessPopup({
                    title: 'Clock In Lembur Berhasil!',
                    message: 'Selamat bekerja lembur. Jangan lupa clock out setelah selesai.',
                    buttonText: 'OK',
                    onClose: () => {
                        window.location.href = 'dashboard';
                    }
                });
            }, 2000);
        }
        
        function saveOvertimeAttendance(data) {
            // Get existing attendance data
            let attendanceHistory = JSON.parse(localStorage.getItem('attendanceHistory') || '[]');
            
            // Add new overtime entry
            attendanceHistory.push(data);
            
            // Save back to localStorage
            localStorage.setItem('attendanceHistory', JSON.stringify(attendanceHistory));
            
            // Update user session with current status
            let userSession = JSON.parse(localStorage.getItem('userSession'));
            userSession.currentStatus = 'overtime';
            userSession.lastClockIn = data.timestamp;
            userSession.currentShift = 'OVERTIME';
            localStorage.setItem('userSession', JSON.stringify(userSession));
        }
        
        function loadQRData() {
            const qrResult = localStorage.getItem('qrScanResult');
            if (qrResult) {
                qrData = JSON.parse(qrResult);
                
                // Update QR info display
                document.getElementById('qrOffice').textContent = qrData.office.replace('_', ' ');
                document.getElementById('qrShift').textContent = qrData.shift;
                document.getElementById('qrLocation').textContent = qrData.location;
                document.getElementById('qrTime').textContent = new Date(qrData.timestamp).toLocaleTimeString('id-ID');
                
                // Clear QR result from storage
                localStorage.removeItem('qrScanResult');
            } else {
                // Redirect back if no QR data
                showErrorPopup({
                    title: 'Data QR Tidak Ditemukan',
                    message: 'Silakan scan QR code terlebih dahulu',
                    buttonText: 'OK',
                    onClose: () => goBack()
                });
            }
        }
        
        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Authentication is enforced server-side; avoid client-side redirect.
            loadQRData();
            updateTime();
            setInterval(updateTime, 1000);
            initMap();
        });
    </script>
</body>
</html>