<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="theme-color" content="#1ec7e6">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <title>Pengaturan Notifikasi - Sistem Absensi</title>
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

        /* Section */
        .section {
            background: white;
            border-radius: 12px;
            margin-bottom: 16px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        }

        .section-header {
            padding: 16px 20px;
            background: #f9fafb;
            border-bottom: 1px solid #e5e7eb;
        }

        .section-title {
            font-size: 14px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 4px;
        }

        .section-description {
            font-size: 12px;
            color: #6b7280;
        }

        /* Setting Item */
        .setting-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 20px;
            border-bottom: 1px solid #f3f4f6;
        }

        .setting-item:last-child {
            border-bottom: none;
        }

        .setting-info {
            flex: 1;
        }

        .setting-title {
            font-size: 14px;
            font-weight: 500;
            color: #374151;
            margin-bottom: 4px;
        }

        .setting-subtitle {
            font-size: 12px;
            color: #6b7280;
        }

        /* Toggle Switch */
        .toggle-switch {
            position: relative;
            width: 48px;
            height: 28px;
            margin-left: 16px;
        }

        .toggle-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .toggle-slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #d1d5db;
            transition: 0.3s;
            border-radius: 28px;
        }

        .toggle-slider:before {
            position: absolute;
            content: "";
            height: 20px;
            width: 20px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: 0.3s;
            border-radius: 50%;
        }

        input:checked + .toggle-slider {
            background-color: #1ec7e6;
        }

        input:checked + .toggle-slider:before {
            transform: translateX(20px);
        }

        /* Time Selector */
        .time-selector {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-left: 16px;
        }

        .time-input {
            padding: 6px 12px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 14px;
            color: #374151;
            background-color: white;
        }

        .time-input:disabled {
            background-color: #f3f4f6;
            color: #9ca3af;
        }

        /* Success Message */
        .success-message {
            background: #10b981;
            color: white;
            padding: 12px 20px;
            text-align: center;
            font-size: 14px;
            display: none;
            position: fixed;
            top: 70px;
            left: 50%;
            transform: translateX(-50%);
            width: calc(100% - 40px);
            max-width: 353px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            z-index: 1000;
        }

        .success-message.show {
            display: block;
            animation: slideDown 0.3s ease-out;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translate(-50%, -20px);
            }
            to {
                opacity: 1;
                transform: translate(-50%, 0);
            }
        }

        /* Info Box */
        .info-box {
            background: #dbeafe;
            border-left: 4px solid #1ec7e6;
            padding: 12px 16px;
            margin: 16px 20px;
            border-radius: 6px;
        }

        .info-box-text {
            font-size: 12px;
            color: #1e40af;
            line-height: 1.5;
        }

        .info-icon {
            display: inline-block;
            margin-right: 8px;
        }
    </style>
</head>
<body>
    <div class="header">
        <button class="back-btn" onclick="goBack()">←</button>
        <div class="header-title">Pengaturan Notifikasi</div>
    </div>

    <div class="success-message" id="successMessage">
        ✓ Pengaturan notifikasi berhasil disimpan
    </div>

    <div class="content">
        <!-- General Notifications -->
        <div class="section">
            <div class="section-header">
                <div class="section-title">Notifikasi Umum</div>
                <div class="section-description">Kelola notifikasi aplikasi</div>
            </div>
            <div class="setting-item">
                <div class="setting-info">
                    <div class="setting-title">Push Notification</div>
                    <div class="setting-subtitle">Aktifkan notifikasi push</div>
                </div>
                <label class="toggle-switch">
                    <input type="checkbox" id="pushNotification" checked onchange="saveSettings()">
                    <span class="toggle-slider"></span>
                </label>
            </div>
            <div class="setting-item">
                <div class="setting-info">
                    <div class="setting-title">Suara Notifikasi</div>
                    <div class="setting-subtitle">Aktifkan suara untuk notifikasi</div>
                </div>
                <label class="toggle-switch">
                    <input type="checkbox" id="soundNotification" checked onchange="saveSettings()">
                    <span class="toggle-slider"></span>
                </label>
            </div>
            <div class="setting-item">
                <div class="setting-info">
                    <div class="setting-title">Getar</div>
                    <div class="setting-subtitle">Aktifkan getar untuk notifikasi</div>
                </div>
                <label class="toggle-switch">
                    <input type="checkbox" id="vibrationNotification" checked onchange="saveSettings()">
                    <span class="toggle-slider"></span>
                </label>
            </div>
        </div>

        <!-- Attendance Notifications -->
        <div class="section">
            <div class="section-header">
                <div class="section-title">Notifikasi Absensi</div>
                <div class="section-description">Pengingat waktu absensi</div>
            </div>
            <div class="setting-item">
                <div class="setting-info">
                    <div class="setting-title">Pengingat Clock In</div>
                    <div class="setting-subtitle">Notifikasi saat waktu masuk</div>
                </div>
                <label class="toggle-switch">
                    <input type="checkbox" id="clockInReminder" checked onchange="toggleClockInTime()">
                    <span class="toggle-slider"></span>
                </label>
            </div>
            <div class="setting-item">
                <div class="setting-info">
                    <div class="setting-title">Waktu Pengingat</div>
                    <div class="setting-subtitle">Atur waktu pengingat clock in</div>
                </div>
                <div class="time-selector">
                    <input type="time" class="time-input" id="clockInTime" value="08:00" onchange="saveSettings()">
                </div>
            </div>
            <div class="setting-item">
                <div class="setting-info">
                    <div class="setting-title">Pengingat Clock Out</div>
                    <div class="setting-subtitle">Notifikasi saat waktu pulang</div>
                </div>
                <label class="toggle-switch">
                    <input type="checkbox" id="clockOutReminder" checked onchange="toggleClockOutTime()">
                    <span class="toggle-slider"></span>
                </label>
            </div>
            <div class="setting-item">
                <div class="setting-info">
                    <div class="setting-title">Waktu Pengingat</div>
                    <div class="setting-subtitle">Atur waktu pengingat clock out</div>
                </div>
                <div class="time-selector">
                    <input type="time" class="time-input" id="clockOutTime" value="17:00" onchange="saveSettings()">
                </div>
            </div>
        </div>

        <!-- Leave & Overtime Notifications -->
        <div class="section">
            <div class="section-header">
                <div class="section-title">Notifikasi Izin & Lembur</div>
                <div class="section-description">Update status pengajuan</div>
            </div>
            <div class="setting-item">
                <div class="setting-info">
                    <div class="setting-title">Status Izin</div>
                    <div class="setting-subtitle">Notifikasi persetujuan/penolakan izin</div>
                </div>
                <label class="toggle-switch">
                    <input type="checkbox" id="leaveStatus" checked onchange="saveSettings()">
                    <span class="toggle-slider"></span>
                </label>
            </div>
            <div class="setting-item">
                <div class="setting-info">
                    <div class="setting-title">Status Lembur</div>
                    <div class="setting-subtitle">Notifikasi persetujuan/penolakan lembur</div>
                </div>
                <label class="toggle-switch">
                    <input type="checkbox" id="overtimeStatus" checked onchange="saveSettings()">
                    <span class="toggle-slider"></span>
                </label>
            </div>
        </div>

        <!-- Schedule Notifications -->
        <div class="section">
            <div class="section-header">
                <div class="section-title">Notifikasi Jadwal</div>
                <div class="section-description">Informasi jadwal kerja</div>
            </div>
            <div class="setting-item">
                <div class="setting-info">
                    <div class="setting-title">Perubahan Shift</div>
                    <div class="setting-subtitle">Notifikasi saat ada perubahan shift</div>
                </div>
                <label class="toggle-switch">
                    <input type="checkbox" id="shiftChange" checked onchange="saveSettings()">
                    <span class="toggle-slider"></span>
                </label>
            </div>
            <div class="setting-item">
                <div class="setting-info">
                    <div class="setting-title">Jadwal Minggu Depan</div>
                    <div class="setting-subtitle">Pengingat jadwal di awal minggu</div>
                </div>
                <label class="toggle-switch">
                    <input type="checkbox" id="weeklySchedule" checked onchange="saveSettings()">
                    <span class="toggle-slider"></span>
                </label>
            </div>
        </div>

        <!-- Info Box -->
        <div class="info-box">
            <div class="info-box-text">
                <span class="info-icon">💡</span>
                <strong>Tips:</strong> Aktifkan notifikasi untuk tidak melewatkan pengingat penting tentang absensi dan jadwal kerja Anda.
            </div>
        </div>
    </div>

    <script>
        // Load settings from localStorage
        function loadSettings() {
            const settings = localStorage.getItem('notificationSettings');
            if (settings) {
                const parsedSettings = JSON.parse(settings);
                
                // Apply saved settings
                document.getElementById('pushNotification').checked = parsedSettings.pushNotification ?? true;
                document.getElementById('soundNotification').checked = parsedSettings.soundNotification ?? true;
                document.getElementById('vibrationNotification').checked = parsedSettings.vibrationNotification ?? true;
                document.getElementById('clockInReminder').checked = parsedSettings.clockInReminder ?? true;
                document.getElementById('clockInTime').value = parsedSettings.clockInTime ?? '08:00';
                document.getElementById('clockOutReminder').checked = parsedSettings.clockOutReminder ?? true;
                document.getElementById('clockOutTime').value = parsedSettings.clockOutTime ?? '17:00';
                document.getElementById('leaveStatus').checked = parsedSettings.leaveStatus ?? true;
                document.getElementById('overtimeStatus').checked = parsedSettings.overtimeStatus ?? true;
                document.getElementById('shiftChange').checked = parsedSettings.shiftChange ?? true;
                document.getElementById('weeklySchedule').checked = parsedSettings.weeklySchedule ?? true;
                
                // Update time input states
                toggleClockInTime();
                toggleClockOutTime();
            }
        }

        // Save settings to localStorage
        function saveSettings() {
            const settings = {
                pushNotification: document.getElementById('pushNotification').checked,
                soundNotification: document.getElementById('soundNotification').checked,
                vibrationNotification: document.getElementById('vibrationNotification').checked,
                clockInReminder: document.getElementById('clockInReminder').checked,
                clockInTime: document.getElementById('clockInTime').value,
                clockOutReminder: document.getElementById('clockOutReminder').checked,
                clockOutTime: document.getElementById('clockOutTime').value,
                leaveStatus: document.getElementById('leaveStatus').checked,
                overtimeStatus: document.getElementById('overtimeStatus').checked,
                shiftChange: document.getElementById('shiftChange').checked,
                weeklySchedule: document.getElementById('weeklySchedule').checked
            };
            
            localStorage.setItem('notificationSettings', JSON.stringify(settings));
            
            // Show success message
            const successMessage = document.getElementById('successMessage');
            successMessage.classList.add('show');
            setTimeout(() => {
                successMessage.classList.remove('show');
            }, 2000);
        }

        // Toggle clock in time input
        function toggleClockInTime() {
            const reminderEnabled = document.getElementById('clockInReminder').checked;
            document.getElementById('clockInTime').disabled = !reminderEnabled;
            saveSettings();
        }

        // Toggle clock out time input
        function toggleClockOutTime() {
            const reminderEnabled = document.getElementById('clockOutReminder').checked;
            document.getElementById('clockOutTime').disabled = !reminderEnabled;
            saveSettings();
        }

        function goBack() {
            if (typeof smartGoBack === 'function') {
                smartGoBack('{{ route("profile.show") }}');
            } else {
                // Fallback navigation
                if (window.history.length > 1 && document.referrer && 
                    document.referrer !== window.location.href &&
                    !document.referrer.includes('login')) {
                    try {
                        window.history.back();
                    } catch (error) {
                        window.location.href = '{{ route("profile.show") }}';
                    }
                } else {
                    window.location.href = '{{ route("profile.show") }}';
                }
            }
        }

        // Load settings on page load
        window.addEventListener('DOMContentLoaded', loadSettings);
    </script>
</body>
</html>
