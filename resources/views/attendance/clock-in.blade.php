<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Clock In - Sistem Absensi</title>
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" 
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" 
        crossorigin=""/>
    <!-- Popup Component CSS -->
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
            width: 393px;
            height: 852px;
            margin: 0 auto;
            overflow: hidden;
            position: relative;
        }
        .map-container {
            position: relative;
            height: 60%;
            background-color: #e5e5e5;
        }
        #map {
            height: 100%;
            width: 100%;
            z-index: 1;
        }
        .map-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 5;
            pointer-events: none;
        }
        .map-overlay > * {
            pointer-events: auto;
        }
        .back-btn {
            position: absolute;
            top: 30px;
            left: 20px;
            background: rgba(30, 199, 230, 0.9);
            color: white;
            border: none;
            padding: 12px;
            border-radius: 50%;
            font-size: 20px;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }
        .back-btn:hover {
            background: rgba(30, 199, 230, 1);
            transform: scale(1.05);
        }
        .refresh-btn {
            position: absolute;
            top: 30px;
            right: 20px;
            background: rgba(30, 199, 230, 0.9);
            color: white;
            border: none;
            padding: 12px;
            border-radius: 50%;
            font-size: 20px;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }
        .refresh-btn:hover {
            background: rgba(30, 199, 230, 1);
            transform: rotate(180deg);
        }
        .time-display {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: rgba(255, 255, 255, 0.95);
            padding: 15px 25px;
            border-radius: 20px;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            z-index: 10;
        }
        .time-display .time {
            font-size: 28px;
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
        }
        .location-marker {
            position: absolute;
            top: 45%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 40px;
            color: #ef4444;
            z-index: 5;
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.3));
            pointer-events: none;
            display: none; /* Hidden when using real map */
        }
        .clock-in-panel {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: white;
            border-radius: 25px 25px 0 0;
            padding: 15px 20px 20px 20px;
            box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.1);
            height: 38%;
            display: flex;
            flex-direction: column;
        }
        .panel-header {
            text-align: center;
            margin-bottom: 8px;
        }
        .panel-title {
            font-size: 20px;
            font-weight: bold;
            color: #1ec7e6;
            margin-bottom: 0;
        }
        .location-section {
            margin-bottom: 8px;
        }
        .location-header {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 6px;
        }
        .location-icon {
            font-size: 18px;
            color: #666;
        }
        .location-title {
            font-size: 15px;
            font-weight: bold;
            color: #333;
        }
        .location-address {
            color: #666;
            font-size: 13px;
            line-height: 1.3;
            padding-left: 26px;
        }
        .loading-location {
            color: #1ec7e6;
            font-style: italic;
        }
        .location-error {
            color: #ef4444;
            font-size: 13px;
            line-height: 1.4;
            padding: 10px;
            background: rgba(239, 68, 68, 0.1);
            border-radius: 5px;
            margin-top: 5px;
        }
        .location-error strong {
            color: #dc2626;
        }
        .location-accuracy {
            font-size: 11px;
            color: #999;
            margin-top: 4px;
            padding-left: 26px;
        }
        .note-section {
            margin-bottom: 10px;
        }
        .note-input {
            width: 100%;
            padding: 10px;
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            font-size: 13px;
            color: #666;
            background-color: #f8f9fa;
            resize: none;
            height: 45px;
        }
        .note-input:focus {
            outline: none;
            border-color: #1ec7e6;
            background-color: white;
        }
        .clock-in-btn {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #1ec7e6, #0ea5e9);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: auto;
            box-shadow: 0 4px 15px rgba(30, 199, 230, 0.3);
        }
        .clock-in-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(30, 199, 230, 0.4);
        }
        .clock-in-btn:active {
            transform: translateY(0);
        }
        .clock-in-btn:disabled {
            background: linear-gradient(135deg, #ccc, #999);
            cursor: not-allowed;
            opacity: 0.6;
        }
        
        /* Map specific styles */
        .leaflet-control-container {
            pointer-events: auto;
        }
        .leaflet-control-container .leaflet-top.leaflet-left {
            top: 120px;
        }
        .leaflet-control-zoom {  
            margin-top: 0 !important;
        }
        .custom-marker {
            background-color: #ef4444;
            border: 3px solid white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }
        .map-loading {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: rgba(255, 255, 255, 0.9);
            padding: 20px;
            border-radius: 10px;
            z-index: 10;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="map-container">
        <div id="map"></div>
        <div class="map-loading" id="mapLoading">
            <div>📍 Memuat peta...</div>
        </div>
        
        <div class="map-overlay">
            <button class="back-btn" onclick="goBack()">←</button>
            <button class="refresh-btn" onclick="refreshLocation()">↻</button>
            
            <div class="time-display">
                <div class="time" id="currentTime">08:34 AM</div>
            </div>
        </div>
        
        <div class="location-marker">📍</div>
    </div>

    <div class="clock-in-panel">
        <div class="panel-header">
            <div class="panel-title">Clock In</div>
        </div>
        
        <div class="location-section">
            <div class="location-header">
                <span class="location-icon">📍</span>
                <span class="location-title">Your Location</span>
            </div>
            <div class="location-address" id="locationAddress">
                <span class="loading-location">📍 Mendapatkan lokasi Anda...</span>
            </div>
            <div class="location-accuracy" id="locationAccuracy"></div>
            <button onclick="getCurrentLocation(true)" style="margin-top: 10px; padding: 8px 16px; background: #10b981; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 12px; display: none;" id="refreshLocationBtn">🔄 Refresh Location</button>
        </div>
        
        <div class="note-section">
            <textarea class="note-input" placeholder="Note (optional)" id="noteInput"></textarea>
        </div>
        
        <button class="clock-in-btn" onclick="performClockIn()" id="clockInBtn" disabled>Waiting for location...</button>
    </div>

    <!-- Leaflet JavaScript -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" 
            integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" 
            crossorigin=""></script>
    <!-- Popup Component JavaScript -->
    <script src="{{ asset('components/popup.js') }}"></script>
    <script>
        let currentLocation = null;
        let watchId = null;
        let map = null;
        let currentMarker = null;

        function initMap() {
            // Initialize map with default location (Jakarta)
            map = L.map('map', {
                center: [-6.2088, 106.8456], // Jakarta coordinates
                zoom: 15,
                zoomControl: true,
                scrollWheelZoom: true,
                doubleClickZoom: true,
                boxZoom: false
            });

            // Add OpenStreetMap tiles
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors',
                maxZoom: 19
            }).addTo(map);

            // Hide loading indicator
            document.getElementById('mapLoading').style.display = 'none';

            return map;
        }

        function addLocationMarker(lat, lng) {
            // Remove existing marker
            if (currentMarker) {
                map.removeLayer(currentMarker);
            }

            // Create custom marker
            const customIcon = L.divIcon({
                className: 'custom-marker',
                iconSize: [20, 20],
                iconAnchor: [10, 10]
            });

            // Add new marker
            currentMarker = L.marker([lat, lng], { icon: customIcon }).addTo(map);

            // Center map on current location
            map.setView([lat, lng], 17);

            // Add accuracy circle
            if (currentLocation && currentLocation.accuracy) {
                L.circle([lat, lng], {
                    radius: currentLocation.accuracy,
                    color: '#1ec7e6',
                    fillColor: '#1ec7e6',
                    fillOpacity: 0.1,
                    weight: 2
                }).addTo(map);
            }
        }

        function goBack() {
            // Stop watching location when leaving page
            if (watchId) {
                navigator.geolocation.clearWatch(watchId);
            }
            
            // Use smartGoBack function if available
            if (typeof smartGoBack === 'function') {
                smartGoBack('{{ route("attendance.absensi") }}');
            } else {
                // Fallback navigation
                if (window.history.length > 1 && document.referrer && 
                    document.referrer !== window.location.href &&
                    !document.referrer.includes('login')) {
                    try {
                        window.history.back();
                    } catch (error) {
                        window.location.href = '{{ route("attendance.absensi") }}';
                    }
                } else {
                    window.location.href = '{{ route("attendance.absensi") }}';
                }
            }
        }

        function refreshLocation() {
            const refreshBtn = document.querySelector('.refresh-btn');
            const locationAddress = document.getElementById('locationAddress');
            const locationAccuracy = document.getElementById('locationAccuracy');
            const clockInBtn = document.getElementById('clockInBtn');
            
            refreshBtn.style.transform = 'rotate(360deg)';
            locationAddress.innerHTML = '<span class="loading-location">📍 Memperbarui lokasi...</span>';
            locationAccuracy.textContent = '';
            clockInBtn.disabled = true;
            clockInBtn.textContent = 'Waiting for location...';
            
            setTimeout(() => {
                refreshBtn.style.transform = 'rotate(0deg)';
                getCurrentLocation(true);
            }, 1000);
        }

        function performClockIn() {
            const note = document.getElementById('noteInput').value;
            const currentTime = new Date().toLocaleTimeString('en-US', {
                hour: '2-digit',
                minute: '2-digit',
                hour12: false
            });
            
            if (!currentLocation) {
                showErrorPopup({
                    title: 'Location Error',
                    message: 'Mohon tunggu hingga lokasi terdeteksi',
                    buttonText: 'OK'
                });
                return;
            }
            
            // Simulate clock in process
            const clockBtn = document.querySelector('.clock-in-btn');
            clockBtn.textContent = 'Processing...';
            clockBtn.disabled = true;
            
            // Prepare data to send to server
            const clockInData = {
                location: JSON.stringify({
                    latitude: currentLocation.lat,
                    longitude: currentLocation.lng,
                    accuracy: currentLocation.accuracy
                }),
                note: note,
                _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            };
            
            // Send AJAX request to Laravel backend
            fetch("{{ route('attendance.check-in') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': clockInData._token
                },
                body: JSON.stringify(clockInData)
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => Promise.reject(err));
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Show success popup with time
                    showSuccessPopup({
                        title: 'Clock In Successful!',
                        message: 'Anda berhasil melakukan clock in',
                        time: currentTime,
                        buttonText: 'Continue',
                        onClose: () => {
                            window.location.href = "{{ route('attendance.absensi') }}";
                        }
                    });
                } else {
                    // Show error popup
                    showErrorPopup({
                        title: 'Clock In Failed',
                        message: data.message || 'Terjadi kesalahan saat clock in',
                        buttonText: 'OK'
                    });
                    clockBtn.textContent = 'Clock In';
                    clockBtn.disabled = false;
                }
            })
            .catch(error => {
                console.error('Clock in error:', error);
                let errorMessage = 'Terjadi kesalahan koneksi. Silakan coba lagi.';
                
                // Handle validation errors
                if (error.errors) {
                    errorMessage = Object.values(error.errors).flat().join('\n');
                } else if (error.message) {
                    errorMessage = error.message;
                }
                
                showErrorPopup({
                    title: 'Clock In Failed',
                    message: errorMessage,
                    buttonText: 'OK'
                });
                clockBtn.textContent = 'Clock In';
                clockBtn.disabled = false;
            });
        }

        function updateCurrentTime() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('en-US', {
                hour: '2-digit',
                minute: '2-digit',
                hour12: true
            });
            
            document.getElementById('currentTime').textContent = timeString;
        }

        function reverseGeocode(lat, lng) {
            // Using OpenStreetMap Nominatim API for reverse geocoding
            const url = `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=18&addressdetails=1`;
            
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    const locationAddress = document.getElementById('locationAddress');
                    
                    if (data && data.display_name) {
                        // Parse address components
                        const address = data.address || {};
                        const formattedAddress = formatAddress(address, data.display_name);
                        locationAddress.innerHTML = formattedAddress;
                    } else {
                        locationAddress.innerHTML = `Koordinat: ${lat.toFixed(6)}, ${lng.toFixed(6)}`;
                    }
                })
                .catch(error => {
                    console.error('Reverse geocoding error:', error);
                    const locationAddress = document.getElementById('locationAddress');
                    locationAddress.innerHTML = `Koordinat: ${lat.toFixed(6)}, ${lng.toFixed(6)}`;
                });
        }

        function formatAddress(address, fullAddress) {
            // Try to format address in Indonesian style
            let formattedParts = [];
            
            if (address.road || address.pedestrian) {
                formattedParts.push(address.road || address.pedestrian);
            }
            
            if (address.house_number) {
                formattedParts[0] = (formattedParts[0] || '') + ' No.' + address.house_number;
            }
            
            if (address.suburb || address.neighbourhood) {
                formattedParts.push(address.suburb || address.neighbourhood);
            }
            
            if (address.city_district || address.municipality) {
                formattedParts.push(address.city_district || address.municipality);
            }
            
            if (address.city || address.town) {
                formattedParts.push(address.city || address.town);
            }
            
            if (address.state) {
                formattedParts.push(address.state);
            }
            
            if (address.postcode) {
                formattedParts.push(address.postcode);
            }
            
            // If we have enough parts, use them, otherwise use full address
            if (formattedParts.length >= 3) {
                return formattedParts.join(', ');
            } else {
                // Truncate very long addresses
                return fullAddress.length > 150 ? fullAddress.substring(0, 150) + '...' : fullAddress;
            }
        }

        // Force geolocation for development (bypass HTTPS check)
        function forceGetLocation() {
            const locationAddress = document.getElementById('locationAddress');
            const locationAccuracy = document.getElementById('locationAccuracy');
            
            if (!navigator.geolocation) {
                locationAddress.innerHTML = '<span class="location-error">❌ Geolocation tidak didukung browser</span>';
                return;
            }
            
            locationAddress.innerHTML = '<span class="loading-location">⚠️ Memaksa akses lokasi (Development Mode)...</span>';
            locationAccuracy.textContent = 'Menunggu respons GPS...';

            const options = {
                enableHighAccuracy: true,
                timeout: 15000,
                maximumAge: 0
            };

            function success(position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                const accuracy = position.coords.accuracy;
                
                currentLocation = { lat, lng, accuracy };
                
                // Enable clock in button
                const clockInBtn = document.getElementById('clockInBtn');
                clockInBtn.disabled = false;
                clockInBtn.textContent = 'Clock In';
                
                // Show refresh location button
                const refreshBtn = document.getElementById('refreshLocationBtn');
                refreshBtn.style.display = 'inline-block';
                
                // Update accuracy info
                locationAccuracy.textContent = `Akurasi: ±${Math.round(accuracy)} meter (Forced)`;
                
                // Update map with current location
                if (map) {
                    addLocationMarker(lat, lng);
                }
                
                // Get address from coordinates
                reverseGeocode(lat, lng);
                
                console.log(`Force location updated: ${lat}, ${lng} (±${accuracy}m)`);
            }

            function error(err) {
                console.error('Force Geolocation error:', err);
                locationAddress.innerHTML = `
                    <div class="location-error">
                        ❌ Gagal memaksa akses lokasi<br>
                        💡 Browser benar-benar memblokir geolocation di HTTP<br>
                        <strong>Gunakan ngrok untuk HTTPS</strong>
                        <br><button onclick="forceGetLocation()" style="margin-top: 10px; padding: 5px 10px; background: #dc2626; color: white; border: none; border-radius: 5px; cursor: pointer;">🔄 Coba Lagi Force</button>
                    </div>
                `;
                locationAccuracy.textContent = '';
            }

            // Try to get position anyway
            navigator.geolocation.getCurrentPosition(success, error, options);
        }

        function getCurrentLocation(forceRefresh = false) {
            const locationAddress = document.getElementById('locationAddress');
            const locationAccuracy = document.getElementById('locationAccuracy');
            
            // Check if geolocation is supported
            if (!navigator.geolocation) {
                locationAddress.innerHTML = '<span class="location-error">❌ Geolocation tidak didukung browser</span>';
                return;
            }
            
            // Check if using HTTP (not secure context) - allow local network for development
            const isLocalNetwork = location.hostname.startsWith('192.168.') || location.hostname.startsWith('10.') || location.hostname.startsWith('172.');
            const isSecureContext = location.protocol === 'https:' || location.hostname === 'localhost' || location.hostname === '127.0.0.1' || isLocalNetwork;
            
            if (!isSecureContext) {
                locationAddress.innerHTML = `
                    <div class="location-error">
                        ❌ Geolocation memerlukan HTTPS<br>
                        💡 <strong>Solusi:</strong> Akses via HTTPS atau gunakan ngrok
                        <br><button onclick="forceGetLocation()" style="margin-top: 10px; padding: 5px 10px; background: #dc2626; color: white; border: none; border-radius: 5px; cursor: pointer;">⚠️ Force Try (Development)</button>
                        <br><button onclick="getCurrentLocation(true)" style="margin-top: 5px; padding: 5px 10px; background: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer;">🔄 Coba Lagi</button>
                    </div>
                `;
                return;
            }
            
            // Show loading state
            locationAddress.innerHTML = '<span class="loading-location">📍 Mendapatkan lokasi Anda...</span>';
            locationAccuracy.textContent = 'Menunggu respons GPS...';

            const options = {
                enableHighAccuracy: true,
                timeout: 10000,
                maximumAge: forceRefresh ? 0 : 60000 // Cache for 1 minute unless forced refresh
            };

            function success(position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                const accuracy = position.coords.accuracy;
                
                currentLocation = { lat, lng, accuracy };
                
                // Enable clock in button
                const clockInBtn = document.getElementById('clockInBtn');
                clockInBtn.disabled = false;
                clockInBtn.textContent = 'Clock In';
                
                // Show refresh location button
                const refreshBtn = document.getElementById('refreshLocationBtn');
                refreshBtn.style.display = 'inline-block';
                
                // Update accuracy info
                locationAccuracy.textContent = `Akurasi: ±${Math.round(accuracy)} meter`;
                
                // Update map with current location
                if (map) {
                    addLocationMarker(lat, lng);
                }
                
                // Get address from coordinates
                reverseGeocode(lat, lng);
                
                console.log(`Location updated: ${lat}, ${lng} (±${accuracy}m)`);
            }

            function error(err) {
                console.error('Geolocation error:', err);
                let errorMessage = '';
                let showRetryButton = true;
                
                switch(err.code) {
                    case err.PERMISSION_DENIED:
                        errorMessage = '❌ Akses lokasi ditolak.<br>' +
                                     '💡 <strong>Cara mengaktifkan:</strong><br>' +
                                     '• Klik ikon 🔒 di address bar<br>' +
                                     '• Pilih "Izinkan" untuk Location<br>' +
                                     '• Refresh halaman ini';
                        break;
                    case err.POSITION_UNAVAILABLE:
                        errorMessage = '❌ Informasi lokasi tidak tersedia.<br>' +
                                     '💡 Pastikan GPS/Location Services aktif';
                        break;
                    case err.TIMEOUT:
                        errorMessage = '❌ Timeout mendapatkan lokasi.<br>' +
                                     '💡 Coba lagi dengan koneksi yang lebih stabil';
                        break;
                    default:
                        errorMessage = '❌ Error tidak diketahui saat mendapatkan lokasi.';
                        break;
                }
                
                locationAddress.innerHTML = `
                    <div class="location-error">
                        ${errorMessage}
                        ${showRetryButton ? '<br><button onclick="getCurrentLocation(true)" style="margin-top: 10px; padding: 5px 10px; background: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer;">🔄 Coba Lagi</button>' : ''}
                    </div>
                `;
                locationAccuracy.textContent = '';
                
                // Disable clock in button
                const clockInBtn = document.getElementById('clockInBtn');
                clockInBtn.disabled = true;
                clockInBtn.textContent = 'Lokasi Diperlukan';
            }

            // Get current position
            navigator.geolocation.getCurrentPosition(success, error, options);
        }

        function startLocationWatching() {
            if (!navigator.geolocation) return;
            
            const options = {
                enableHighAccuracy: true,
                timeout: 15000,
                maximumAge: 30000
            };
            
            // Watch position changes (lokasi seacara real-time)
            watchId = navigator.geolocation.watchPosition(
                function(position) {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;
                    const accuracy = position.coords.accuracy;
                    
                    // Only update if location changed significantly (more than 10 meters)
                    if (!currentLocation || 
                        Math.abs(currentLocation.lat - lat) > 0.0001 || 
                        Math.abs(currentLocation.lng - lng) > 0.0001) {
                        
                        currentLocation = { lat, lng, accuracy };
                        
                        // Enable clock in button
                        const clockInBtn = document.getElementById('clockInBtn');
                        if (clockInBtn.disabled) {
                            clockInBtn.disabled = false;
                            clockInBtn.textContent = 'Clock In';
                        }
                        
                        // Update accuracy info
                        document.getElementById('locationAccuracy').textContent = `Akurasi: ±${Math.round(accuracy)} meter`;
                        
                        // Update map with current location
                        if (map) {
                            addLocationMarker(lat, lng);
                        }
                        
                        // Get updated address
                        reverseGeocode(lat, lng);
                        
                        console.log(`Location watched: ${lat}, ${lng} (±${accuracy}m)`);
                    }
                },
                function(err) {
                    console.error('Watch position error:', err);
                },
                options
            );
        }

        // Update time every second
        setInterval(updateCurrentTime, 1000);
        
        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            @guest
                // Redirect to login if not authenticated
                window.location.href = "{{ route('login') }}";
                return;
            @endguest
            
            updateCurrentTime();
            
            // Initialize map first
            initMap();
            
            // Then get location
            setTimeout(() => {
                getCurrentLocation();
                // Start watching location changes
                setTimeout(() => {
                    startLocationWatching();
                }, 2000); // Start watching after initial location is loaded
            }, 500);
        });

        // Clean up when page is unloaded
        window.addEventListener('beforeunload', function() {
            if (watchId) {
                navigator.geolocation.clearWatch(watchId);
            }
        });
    </script>
</body>
</html>