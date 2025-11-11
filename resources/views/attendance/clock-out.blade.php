<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Clock Out - Sistem Absensi</title>
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
            width: 100%;
            max-width: 393px;
            height: 100vh;
            margin: 0 auto;
            overflow: hidden;
            position: relative;
        }
        
        @media (min-width: 394px) {
            body {
                box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            }
        }
        
        .header {
            background: linear-gradient(135deg, #1ec7e6, #0ea5e9);
            color: white;
            padding: 50px 20px 30px 20px;
            position: relative;
            height: 60%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        .back-btn {
            position: absolute;
            top: 50px;
            left: 20px;
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: none;
            padding: 10px;
            border-radius: 50%;
            font-size: 18px;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10;
        }
        .back-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: scale(1.1);
        }
        .back-btn:active {
            transform: scale(0.95);
        }
        .clock-icon {
            width: 120px;
            height: 120px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 60px;
            margin-bottom: 20px;
            border: 3px solid rgba(255, 255, 255, 0.3);
        }
        .current-time {
            font-size: 48px;
            font-weight: bold;
            color: white;
            margin-bottom: 10px;
        }
        .current-date {
            font-size: 16px;
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 30px;
        }
        .clock-out-panel {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: white;
            border-radius: 25px 25px 0 0;
            padding: 20px 25px 25px 25px;
            box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.1);
            max-height: 35%;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
            z-index: 1000;
        }
        .panel-header {
            text-align: center;
            margin-bottom: 15px;
            flex-shrink: 0;
        }
        .panel-title {
            font-size: 22px;
            font-weight: bold;
            color: #1ec7e6;
            margin-bottom: 5px;
        }
        .panel-subtitle {
            font-size: 14px;
            color: #666;
        }
        .note-section {
            margin-bottom: 15px;
            flex-shrink: 0;
        }
        .note-input {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #e0e0e0;
            border-radius: 12px;
            font-size: 14px;
            color: #666;
            background-color: #f8f9fa;
            resize: none;
            height: 50px;
        }
        .note-input:focus {
            outline: none;
            border-color: #1ec7e6;
            background-color: white;
        }
        .clock-out-btn {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
            border: none;
            border-radius: 15px;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: auto;
            box-shadow: 0 4px 15px rgba(239, 68, 68, 0.3);
            flex-shrink: 0;
        }
        .clock-out-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(239, 68, 68, 0.4);
        }
        .clock-out-btn:active {
            transform: translateY(0);
        }
    </style>
</head>
<body>
    <div class="header">
        <button class="back-btn" onclick="goBack()">←</button>
        
        <div class="clock-icon">🕐</div>
        <div class="current-time" id="currentTime">--:--</div>
        <div class="current-date" id="currentDate">Loading...</div>
    </div>

    <div class="clock-out-panel">
        <div class="panel-header">
            <div class="panel-title">Clock Out</div>
            <div class="panel-subtitle">End your work day</div>
        </div>
        
        <div class="note-section">
            <textarea class="note-input" placeholder="Note (optional)" id="noteInput"></textarea>
        </div>
        
        <button class="clock-out-btn" onclick="performClockOut()">Clock Out</button>
    </div>

    <!-- Popup Component JavaScript -->
    <script src="{{ asset('components/popup.js') }}"></script>
    <script>
        // Fallback functions if popup.js fails to load
        if (typeof showSuccessPopup === 'undefined') {
            window.showSuccessPopup = function(options) {
                alert(options.title + '\n' + options.message);
                if (options.onClose) options.onClose();
            };
        }
        if (typeof showErrorPopup === 'undefined') {
            window.showErrorPopup = function(options) {
                alert('ERROR: ' + options.title + '\n' + options.message);
            };
        }
        if (typeof smartGoBack === 'undefined') {
            window.smartGoBack = function(fallbackUrl) {
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
            };
        }

        function goBack() {
            // Use the global smartGoBack function from popup.js
            if (typeof smartGoBack === 'function') {
                smartGoBack('{{ route("attendance.absensi") }}');
            } else {
                // Fallback if popup.js fails to load
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

        async function performClockOut() {
            const note = document.getElementById('noteInput').value;
            const clockBtn = document.querySelector('.clock-out-btn');
            
            // Disable button and show loading state
            const originalText = clockBtn.textContent;
            clockBtn.textContent = '⏳ Processing...';
            clockBtn.disabled = true;
            clockBtn.style.opacity = '0.7';
            clockBtn.style.cursor = 'not-allowed';
            
            try {
                const payload = {
                    location: 'Clock Out Location', // You can add geolocation here if needed
                    notes: note
                };

                console.log('Sending check-out request with payload:', payload);

                const response = await fetch("{{ route('attendance.check-out') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(payload)
                });

                console.log('Response status:', response.status);
                const data = await response.json();
                console.log('Response data:', data);

                if (response.ok && data.success) {
                    const currentTime = new Date().toLocaleTimeString('id-ID', {
                        hour: '2-digit',
                        minute: '2-digit',
                        hour12: false
                    });

                    showSuccessPopup({
                        title: 'Clock Out Berhasil!',
                        message: data.message || 'Anda berhasil melakukan clock out',
                        time: currentTime,
                        buttonText: 'Lanjutkan',
                        onClose: () => {
                            window.location.href = '{{ route("attendance.absensi") }}';
                        }
                    });
                } else {
                    showErrorPopup({
                        title: 'Gagal Clock Out',
                        message: data.message || 'Gagal melakukan clock out. Silakan coba lagi.',
                        buttonText: 'OK'
                    });
                    // Reset button state
                    clockBtn.textContent = originalText;
                    clockBtn.disabled = false;
                    clockBtn.style.opacity = '1';
                    clockBtn.style.cursor = 'pointer';
                }
            } catch (err) {
                console.error('Check-out error:', err);
                showErrorPopup({
                    title: 'Koneksi Error',
                    message: 'Terjadi kesalahan koneksi: ' + err.message,
                    buttonText: 'OK'
                });
                // Reset button state
                clockBtn.textContent = originalText;
                clockBtn.disabled = false;
                clockBtn.style.opacity = '1';
                clockBtn.style.cursor = 'pointer';
            }
        }

        function updateCurrentTime() {
            const now = new Date();
            
            // Format waktu 24 jam untuk display utama
            const timeString = now.toLocaleTimeString('id-ID', {
                hour: '2-digit',
                minute: '2-digit',
                hour12: false
            });
            
            // Format tanggal dalam bahasa Indonesia
            const options = { 
                weekday: 'long', 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric' 
            };
            const dateString = now.toLocaleDateString('id-ID', options);
            
            // Update elements
            const timeElement = document.getElementById('currentTime');
            const dateElement = document.getElementById('currentDate');
            
            if (timeElement) {
                timeElement.textContent = timeString;
            }
            
            if (dateElement) {
                dateElement.textContent = dateString;
            }
        }

        // Update time every second
        setInterval(updateCurrentTime, 1000);
        
        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Clock-out page loaded');
            updateCurrentTime();
            
            // Focus on note input for better UX
            const noteInput = document.getElementById('noteInput');
            if (noteInput) {
                noteInput.focus();
            }
        });
    </script>
</body>
</html>