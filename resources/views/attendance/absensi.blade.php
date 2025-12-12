<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Absensi - Sistem Absensi</title>
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
            background-image: url('assets/image/account-circle.svg');
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
        .overtime-btn {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: white;
        }
        .leave-btn {
            background: linear-gradient(135deg, #8b5cf6, #7c3aed);
            color: white;
            margin-top: 15px;
            width: 100%;
        }
        .clock-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(30, 199, 230, 0.3);
        }
        .leave-btn:hover {
            box-shadow: 0 4px 15px rgba(139, 92, 246, 0.3);
        }
        .attendance-completed {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            border-radius: 15px;
            padding: 15px;
            text-align: center;
            font-weight: 600;
            margin: 10px 0;
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
            <div class="profile-image" style="background-image: url('{{ $user->photo ? asset('storage/' . $user->photo) : asset('assets/image/account-circle.svg') }}');"></div>
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
            
            <!-- Status Attendance Hari Ini -->
            @if($todayAttendance)
                <div style="margin: 15px 0; padding: 10px; background: #f8fafc; border-radius: 10px; font-size: 12px; border-left: 4px solid #1ec7e6;">
                    @if($todayAttendance->check_in && $todayAttendance->check_out)
                        <span style="color: #10b981; font-weight: 600;">✅ Completed: {{ \Carbon\Carbon::parse($todayAttendance->check_in)->format('H:i') }} - {{ \Carbon\Carbon::parse($todayAttendance->check_out)->format('H:i') }}</span>
                    @elseif($todayAttendance->check_in)
                        <span style="color: #3b82f6; font-weight: 600;">🔵 Checked In: {{ \Carbon\Carbon::parse($todayAttendance->check_in)->format('H:i') }}</span>
                        <br><small style="color: #6b7280;">Jangan lupa check-out setelah kerja selesai</small>
                    @endif
                </div>
            @else
                <div style="margin: 15px 0; padding: 10px; background: #fef3c7; border-radius: 10px; font-size: 12px; border-left: 4px solid #f59e0b;">
                    <span style="color: #92400e; font-weight: 600;">⚪ Ready to Check-in</span>
                    <br><small style="color: #78716c;">Tap Clock In to start your work day</small>
                </div>
            @endif
            
            <div class="clock-buttons">
                @if(!$todayAttendance || !$todayAttendance->check_in)
                    <!-- Belum check-in hari ini -->
                    <button class="clock-btn clock-in" onclick="scanQR()">📱 Scan QR</button>
                    <button class="clock-btn clock-in" onclick="goToClockIn()">🕐 Clock In</button>
                @elseif($todayAttendance && $todayAttendance->check_in && !$todayAttendance->check_out)
                    <!-- Sudah check-in tapi belum check-out -->
                    <button class="clock-btn clock-out" onclick="goToClockOut()">🕐 Clock Out</button>
                    <button class="clock-btn overtime-btn" onclick="goToOvertime()">⏰ Overtime</button>
                @else
                    <!-- Sudah check-in dan check-out -->
                    <div class="attendance-completed">
                        ✅ Attendance completed for today
                    </div>
                @endif
                
                <!-- Tombol Ajukan Izin - selalu tersedia -->
                <button class="clock-btn leave-btn" onclick="showLeaveModal()">📋 Ajukan Izin</button>
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

    <!-- Modal Pengajuan Izin -->
    <div id="leaveModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>📋 Pengajuan Izin</h3>
                <button class="modal-close" onclick="closeLeaveModal()">&times;</button>
            </div>
            <form id="leaveForm" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label>Tanggal</label>
                    <input type="date" name="date" required class="form-control" min="{{ date('Y-m-d') }}">
                </div>
                
                <div class="form-group">
                    <label>Jenis Izin</label>
                    <select name="type" required class="form-control" onchange="toggleUpload(this.value)">
                        <option value="">Pilih Jenis Izin</option>
                        <option value="work_leave">Izin Kerja</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Keterangan</label>
                    <textarea name="notes" required class="form-control" rows="3" placeholder="Alasan/keterangan izin..."></textarea>
                </div>
                
                <!-- Upload untuk surat izin kerja -->
                <div id="workLeaveUpload" class="upload-section" style="display: none;">
                    <label>Surat Keterangan Izin Kerja</label>
                    <div class="upload-area" onclick="document.getElementById('work_leave_document').click()">
                        <div class="upload-icon">📄</div>
                        <div class="upload-text">
                            <span>Klik untuk upload surat izin kerja</span>
                            <small>JPG, PNG, PDF - Max 5MB</small>
                        </div>
                        <input type="file" id="work_leave_document" name="work_leave_document" accept=".jpg,.jpeg,.png,.pdf" style="display: none;" onchange="showFileName('work_leave_document', 'workLeaveFileName')">
                    </div>
                    <div id="workLeaveFileName" class="file-name"></div>
                </div>
                
                <div class="form-actions">
                    <button type="button" onclick="closeLeaveModal()" class="btn-cancel">Batal</button>
                    <button type="submit" class="btn-submit">Ajukan Izin</button>
                </div>
            </form>
        </div>
    </div>

    <style>
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            backdrop-filter: blur(5px);
        }
        
        .modal-content {
            background: white;
            margin: 5% auto;
            padding: 0;
            border-radius: 20px;
            width: 90%;
            max-width: 500px;
            max-height: 90vh;
            overflow-y: auto;
            animation: modalSlideIn 0.3s ease;
        }
        
        @keyframes modalSlideIn {
            from { transform: translateY(-50px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        
        .modal-header {
            background: linear-gradient(135deg, #8b5cf6, #7c3aed);
            color: white;
            padding: 20px;
            border-radius: 20px 20px 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .modal-header h3 {
            margin: 0;
            font-size: 18px;
        }
        
        .modal-close {
            background: none;
            border: none;
            color: white;
            font-size: 24px;
            cursor: pointer;
            line-height: 1;
        }
        
        .form-group {
            margin: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }
        
        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            font-size: 14px;
            transition: border-color 0.3s ease;
            box-sizing: border-box;
        }
        
        .form-control:focus {
            outline: none;
            border-color: #8b5cf6;
        }
        
        .upload-section {
            margin: 20px;
        }
        
        .upload-area {
            border: 2px dashed #d1d5db;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            background: #f9fafb;
        }
        
        .upload-area:hover {
            border-color: #8b5cf6;
            background: #f3f4f6;
        }
        
        .upload-icon {
            font-size: 24px;
            margin-bottom: 10px;
        }
        
        .upload-text span {
            display: block;
            font-weight: 600;
            color: #374151;
            margin-bottom: 5px;
        }
        
        .upload-text small {
            color: #6b7280;
            font-size: 12px;
        }
        
        .file-name {
            margin-top: 10px;
            padding: 8px 12px;
            background: #ecfdf5;
            border: 1px solid #d1fae5;
            border-radius: 8px;
            color: #065f46;
            font-size: 13px;
            display: none;
        }
        
        .form-actions {
            display: flex;
            gap: 15px;
            padding: 20px;
            border-top: 1px solid #e5e7eb;
        }
        
        .btn-cancel {
            flex: 1;
            padding: 12px 20px;
            border: 2px solid #e5e7eb;
            background: white;
            color: #374151;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
        }
        
        .btn-submit {
            flex: 1;
            padding: 12px 20px;
            background: linear-gradient(135deg, #8b5cf6, #7c3aed);
            color: white;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
        }
        
        .btn-submit:hover {
            opacity: 0.9;
        }
    </style>

    <script>
        // Fallback function if popup.js fails to load
        if (typeof smartGoBack === 'undefined') {
            function smartGoBack(fallbackUrl) {
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
            }
        }

        function goBack() {
            smartGoBack('{{ route("dashboard") }}');
        }

        function goToClockIn() {
            window.location.href = "{{ route('attendance.clock-in') }}";
        }

        function goToClockOut() {
            window.location.href = "{{ route('attendance.clock-out') }}";
        }
        
        function scanQR() {
            window.location.href = "{{ route('attendance.qr-scan') }}";
        }

        function goToOvertime() {
            // Untuk lembur, harus scan QR code terlebih dahulu
            showInfoPopup({
                title: 'Scan QR Code',
                message: 'Untuk clock in lembur, silakan scan QR code terlebih dahulu',
                buttonText: 'Scan QR Code',
                onClose: () => {
                    window.location.href = "{{ route('attendance.qr-scan') }}";
                }
            });
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

        // Modal functions
        function showLeaveModal() {
            document.getElementById('leaveModal').style.display = 'block';
            document.body.style.overflow = 'hidden';
        }

        function closeLeaveModal() {
            document.getElementById('leaveModal').style.display = 'none';
            document.body.style.overflow = 'auto';
            document.getElementById('leaveForm').reset();
            document.querySelectorAll('.upload-section').forEach(section => {
                section.style.display = 'none';
            });
            document.querySelectorAll('.file-name').forEach(element => {
                element.style.display = 'none';
            });
        }

        function toggleUpload(type) {
            document.getElementById('workLeaveUpload').style.display = 'none';
            
            if (type === 'work_leave') {
                document.getElementById('workLeaveUpload').style.display = 'block';
            }
        }

        function showFileName(inputId, displayId) {
            const input = document.getElementById(inputId);
            const display = document.getElementById(displayId);
            
            if (input.files && input.files[0]) {
                const file = input.files[0];
                const fileSize = (file.size / 1024 / 1024).toFixed(2);
                display.textContent = `📎 ${file.name} (${fileSize} MB)`;
                display.style.display = 'block';
            }
        }

        // Form submission
        document.getElementById('leaveForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const submitBtn = this.querySelector('.btn-submit');
            const originalText = submitBtn.textContent;
            submitBtn.textContent = 'Mengirim...';
            submitBtn.disabled = true;
            
            const formData = new FormData(this);
            
            fetch('{{ route("attendance.submit-leave") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showSuccessPopup({
                        title: 'Izin Berhasil Diajukan!',
                        message: data.message,
                        buttonText: 'OK',
                        onClose: () => {
                            closeLeaveModal();
                            location.reload();
                        }
                    });
                } else {
                    alert('Error: ' + (data.message || 'Terjadi kesalahan'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat mengirim data');
            })
            .finally(() => {
                submitBtn.textContent = originalText;
                submitBtn.disabled = false;
            });
        });

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('leaveModal');
            if (event.target === modal) {
                closeLeaveModal();
            }
        };
    </script>
    
    <!-- Popup Component JavaScript -->
    <script src="{{ asset('components/popup.js') }}"></script>
</body>
</html>