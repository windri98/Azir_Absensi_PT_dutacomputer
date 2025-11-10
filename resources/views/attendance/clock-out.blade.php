<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Clock Out - Sistem Absensi</title>
    <!-- Popup Component CSS -->
    <link rel="stylesheet" href="/components/popup.css">
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
        .status-bar {
            position: absolute;
            top: 20px;
            left: 20px;
            right: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 16px;
            font-weight: bold;
            color: white;
            z-index: 10;
        }
        .status-right {
            display: flex;
            gap: 5px;
            align-items: center;
        }
        .back-btn {
            position: absolute;
            top: 70px;
            left: 20px;
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: none;
            padding: 12px;
            border-radius: 50%;
            font-size: 18px;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 45px;
            height: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10;
        }
        .back-btn:hover {
            background: rgba(255, 255, 255, 0.3);
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
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: white;
            border-radius: 25px 25px 0 0;
            padding: 30px 25px;
            box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.1);
            height: 40%;
            display: flex;
            flex-direction: column;
        }
        .panel-header {
            text-align: center;
            margin-bottom: 25px;
        }
        .panel-title {
            font-size: 24px;
            font-weight: bold;
            color: #1ec7e6;
            margin-bottom: 10px;
        }
        .panel-subtitle {
            font-size: 14px;
            color: #666;
        }
        .note-section {
            margin-bottom: 25px;
        }
        .note-input {
            width: 100%;
            padding: 15px;
            border: 1px solid #e0e0e0;
            border-radius: 12px;
            font-size: 14px;
            color: #666;
            background-color: #f8f9fa;
            resize: none;
            height: 60px;
        }
        .note-input:focus {
            outline: none;
            border-color: #1ec7e6;
            background-color: white;
        }
        .clock-out-btn {
            width: 100%;
            padding: 18px;
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
        <div class="status-bar">
            <span id="statusTime">08:34</span>
            <div class="status-right">
                <span>●●●●</span>
                <span>4G</span>
                <span>📶</span>
                <span>🔋</span>
            </div>
        </div>
        
        <button class="back-btn" onclick="goBack()">←</button>
        
        <div class="clock-icon">🕐</div>
        <div class="current-time" id="currentTime">17:00</div>
        <div class="current-date" id="currentDate">Friday, Oct 11, 2025</div>
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
    <script src="/components/popup.js"></script>
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

        function goBack() {
            window.location.href = '{{ route("attendance.absensi") }}';
        }

        async function performClockOut() {
            const note = document.getElementById('noteInput').value;
            const clockBtn = document.querySelector('.clock-out-btn');
            
            clockBtn.textContent = 'Processing...';
            clockBtn.disabled = true;
            
            try {
                const payload = {
                    location: 'Clock Out Location', // You can add geolocation here if needed
                    notes: note
                };

                console.log('Sending check-out request with payload:', payload);

                const response = await fetch('/attendance/check-out', {
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
                    const currentTime = new Date().toLocaleTimeString('en-US', {
                        hour: '2-digit',
                        minute: '2-digit',
                        hour12: true
                    });

                    showSuccessPopup({
                        title: 'Clock Out Successful!',
                        message: data.message || 'Anda berhasil melakukan clock out',
                        time: currentTime,
                        buttonText: 'Continue',
                        onClose: () => {
                            window.location.href = '{{ route("attendance.absensi") }}';
                        }
                    });
                } else {
                    showErrorPopup({
                        title: 'Error',
                        message: data.message || 'Gagal melakukan clock out',
                        buttonText: 'OK'
                    });
                    clockBtn.textContent = 'Clock Out';
                    clockBtn.disabled = false;
                }
            } catch (err) {
                console.error('Check-out error:', err);
                showErrorPopup({
                    title: 'Error',
                    message: 'Terjadi kesalahan: ' + err.message,
                    buttonText: 'OK'
                });
                clockBtn.textContent = 'Clock Out';
                clockBtn.disabled = false;
            }
        }

        function updateCurrentTime() {
            const now = new Date();
            
            const timeString = now.toLocaleTimeString('en-US', {
                hour: '2-digit',
                minute: '2-digit',
                hour12: false
            });
            
            const dateString = now.toLocaleDateString('en-US', {
                weekday: 'long',
                month: 'short',
                day: '2-digit',
                year: 'numeric'
            });
            
            const statusTimeString = now.toLocaleTimeString('en-US', {
                hour: '2-digit',
                minute: '2-digit',
                hour12: true
            });
            
            document.getElementById('currentTime').textContent = timeString;
            document.getElementById('currentDate').textContent = dateString;
            document.getElementById('statusTime').textContent = statusTimeString;
        }

        // Update time every second
        setInterval(updateCurrentTime, 1000);
        
        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Authentication is enforced server-side; avoid client-side redirect.
            updateCurrentTime();
        });
    </script>
</body>
</html>