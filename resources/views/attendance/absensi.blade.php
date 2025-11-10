<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Absensi - Sistem Absensi</title>
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
            padding: 50px 20px 80px 20px;
            position: relative;
            border-radius: 0 0 30px 30px;
        }
        .back-btn {
            position: absolute;
            top: 20px;
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
        }
        .back-btn:hover {
            background: rgba(255, 255, 255, 0.3);
        }
        .profile-section {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-top: 50px;
        }
        .profile-image {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background-image: url('assets/image/439605617_454358160308404_313339237371064683_n.png');
            background-size: cover;
            background-position: center;
            border: 3px solid rgba(255, 255, 255, 0.3);
        }
        .profile-info {
            flex: 1;
        }
        .employee-status {
            background: rgba(255, 255, 255, 0.2);
            padding: 5px 12px;
            border-radius: 15px;
            font-size: 12px;
            display: inline-block;
            margin-bottom: 8px;
        }
        .employee-name {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .main-content {
            padding: 20px;
        }
        .attendance-card {
            background: white;
            border-radius: 20px;
            padding: 25px 20px;
            margin-bottom: 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .live-attendance h3 {
            color: #333;
            font-size: 18px;
            margin-bottom: 15px;
        }
        .current-time {
            color: #1ec7e6;
            font-size: 32px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .current-date {
            color: #999;
            font-size: 14px;
            margin-bottom: 25px;
        }
        .divider {
            height: 1px;
            background-color: #e0e0e0;
            margin: 25px 0;
        }
        .office-hours h4 {
            color: #666;
            font-size: 16px;
            margin-bottom: 10px;
        }
        .office-time {
            color: #333;
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .clock-buttons {
            display: flex;
            gap: 12px;
            justify-content: center;
        }
        .clock-btn {
            flex: 1;
            padding: 12px 20px;
            border: none;
            border-radius: 15px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .clock-in {
            background: linear-gradient(135deg, #1ec7e6, #0ea5e9);
            color: white;
        }
        .clock-out {
            background: linear-gradient(135deg, #22d3ee, #06b6d4);
            color: white;
        }
        .clock-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(30, 199, 230, 0.3);
        }
        .history-section {
            background: white;
            border-radius: 20px;
            padding: 25px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        .history-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
        }
        .history-header h3 {
            color: #333;
            font-size: 18px;
        }
        .history-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid #f0f0f0;
        }
        .history-item:last-child {
            border-bottom: none;
        }
        .history-date {
            color: #666;
            font-size: 14px;
        }
        .history-time {
            color: #333;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .history-time.late {
            color: #ef4444;
        }
        .history-time.ontime {
            color: #10b981;
        }
        .time-icon {
            font-size: 16px;
        }
    </style>
</head>
<body>
    <div class="header">
        <button class="back-btn" onclick="goBack()">←</button>
        
        <div class="profile-section">
            <div class="profile-image" style="background-image: url('{{ $user->photo ? asset('storage/' . $user->photo) : asset('assets/image/439605617_454358160308404_313339237371064683_n.png') }}');"></div>
            <div class="profile-info">
                <div class="employee-status">{{ $user->roles->first()->description ?? 'Karyawan' }}</div>
                <div class="employee-name">{{ $user->name }}</div>
            </div>
        </div>
    </div>

    <div class="main-content">
        <div class="attendance-card live-attendance">
            <h3>Live Attendance</h3>
            <div class="current-time" id="currentTime">08:34 AM</div>
            <div class="current-date" id="currentDate">Fri, 14 April 2024</div>
            
            <div class="divider"></div>
            
            <h4>Office Hours</h4>
            @if($userShift)
                <div class="office-time">
                    {{ \Carbon\Carbon::parse($userShift->start_time)->format('h:i A') }} - 
                    {{ \Carbon\Carbon::parse($userShift->end_time)->format('h:i A') }}
                </div>
                <small style="color: #888; font-size: 12px;">Shift: {{ $userShift->name }}</small>
            @else
                <div class="office-time" style="color: #999;">Belum ada shift</div>
                <small style="color: #ff6b6b; font-size: 12px;">⚠️ Hubungi admin untuk assign shift</small>
            @endif
            
            <div class="clock-buttons">
                <button class="clock-btn clock-in" onclick="scanQR()">📱 Scan QR</button>
                <button class="clock-btn clock-in" onclick="goToClockIn()">Clock In</button>
                <button class="clock-btn clock-out" onclick="goToClockOut()">Clock Out</button>
            </div>
        </div>

        <div class="history-section">
            <div class="history-header">
                <span style="font-size: 20px;">👥</span>
                <h3>Attendance History</h3>
            </div>
            <div style="margin-bottom: 15px; font-weight: bold; color: #1ec7e6;">Total Hadir: {{ $attendances->where('status', '!=', 'absent')->count() }}</div>
            @if($attendances->count() > 0)
                @foreach($attendances as $attendance)
                <div class="history-item">
                    <div class="history-date">
                        {{ \Carbon\Carbon::parse($attendance->date)->translatedFormat('D, d F Y') }}
                    </div>
                    <div class="history-time {{ $attendance->status === 'late' ? 'late' : 'ontime' }}">
                        <span class="time-icon">🕐</span>
                        {{ $attendance->check_in ? \Carbon\Carbon::parse($attendance->check_in)->format('H:i') : '--:--' }} - {{ $attendance->check_out ? \Carbon\Carbon::parse($attendance->check_out)->format('H:i') : '--:--' }}
                    </div>
                </div>
                @endforeach
            @else
                <div style="text-align:center; color:#888; padding:20px 0;">Belum ada data absensi</div>
            @endif
        </div>
    </div>

    <script>
        function goBack() {
            window.location.href = '/dashboard';
        }

        function goToClockIn() {
            window.location.href = 'clock-in';
        }

        function goToClockOut() {
            window.location.href = 'clock-out';
        }
        
        function scanQR() {
            window.location.href = 'qr-scan';
        }

        function clockOut() {
            const currentTime = new Date().toLocaleTimeString('en-US', {
                hour: '2-digit',
                minute: '2-digit',
                hour12: false
            });
            
            // Store clock out data
            const clockOutData = {
                time: currentTime,
                date: new Date().toISOString(),
                type: 'clock-out'
            };
            
            // Get existing attendance history
            let attendanceHistory = JSON.parse(localStorage.getItem('attendanceHistory') || '[]');
            attendanceHistory.push(clockOutData);
            localStorage.setItem('attendanceHistory', JSON.stringify(attendanceHistory));
            
            // Show success popup
            showSuccessPopup({
                title: 'Clock Out Successful!',
                message: 'Anda berhasil melakukan clock out',
                time: currentTime,
                buttonText: 'Continue',
                onClose: () => {
                    location.reload();
                }
            });
        }

        function updateCurrentTime() {
            const now = new Date();
            
            const timeString = now.toLocaleTimeString('en-US', {
                hour: '2-digit',
                minute: '2-digit',
                hour12: true
            });
            
            const dateString = now.toLocaleDateString('en-US', {
                weekday: 'short',
                day: '2-digit',
                month: 'long',
                year: 'numeric'
            });
            
            document.getElementById('currentTime').textContent = timeString;
            document.getElementById('currentDate').textContent = dateString;
        }

        // Update time every second
        setInterval(updateCurrentTime, 1000);
        
        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateCurrentTime();
        });
    </script>
    
    <!-- Popup Component JavaScript -->
    <script src="components/popup.js"></script>
</body>
</html>