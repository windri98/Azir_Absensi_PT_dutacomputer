<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shift Management - Admin Panel</title>
    <link rel="stylesheet" href="components/popup.css">
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
        .header-subtitle {
            font-size: 14px;
            opacity: 0.9;
            margin-top: 5px;
        }
        
        /* Shift Configuration */
        .shift-config {
            margin: 20px;
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .config-header {
            padding: 20px;
            border-bottom: 1px solid #e5e7eb;
            background: #f8fafc;
        }
        .config-title {
            font-size: 18px;
            font-weight: 600;
            color: #333;
            margin-bottom: 5px;
        }
        .config-subtitle {
            font-size: 14px;
            color: #666;
        }
        
        /* Shift Types */
        .shift-types {
            padding: 20px;
        }
        .shift-item {
            background: #f8fafc;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 15px;
            position: relative;
        }
        .shift-item.active {
            border-color: #1ec7e6;
            background: linear-gradient(135deg, #f0f9ff, #e0f2fe);
        }
        .shift-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 15px;
        }
        .shift-name {
            font-size: 16px;
            font-weight: 600;
            color: #333;
            margin-bottom: 5px;
        }
        .shift-time {
            font-size: 14px;
            color: #666;
        }
        .shift-badge {
            padding: 4px 8px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            color: white;
        }
        .shift-badge.morning {
            background: #10b981;
        }
        .shift-badge.afternoon {
            background: #f59e0b;
        }
        .shift-badge.night {
            background: #6366f1;
        }
        .shift-badge.overtime {
            background: #ef4444;
        }
        
        .shift-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 15px;
        }
        .detail-item {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }
        .detail-label {
            font-size: 12px;
            color: #666;
            font-weight: 500;
        }
        .detail-value {
            font-size: 14px;
            color: #333;
            font-weight: 600;
        }
        
        .shift-qr {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
        }
        .qr-code {
            width: 80px;
            height: 80px;
            background: #f3f4f6;
            border: 2px dashed #d1d5db;
            border-radius: 8px;
            margin: 0 auto 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }
        .qr-text {
            font-size: 12px;
            color: #666;
            margin-bottom: 5px;
        }
        .qr-value {
            font-size: 11px;
            color: #999;
            font-family: monospace;
            word-break: break-all;
        }
        
        /* Employee Assignment */
        .employee-assignment {
            margin: 20px;
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .assignment-list {
            padding: 20px;
        }
        .employee-item {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 15px;
            background: #f8fafc;
            border-radius: 12px;
            margin-bottom: 10px;
        }
        .employee-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: linear-gradient(135deg, #1ec7e6, #0ea5e9);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 18px;
        }
        .employee-info {
            flex: 1;
        }
        .employee-name {
            font-size: 14px;
            font-weight: 600;
            color: #333;
            margin-bottom: 3px;
        }
        .employee-id {
            font-size: 12px;
            color: #666;
        }
        .shift-selector {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 8px 12px;
            font-size: 12px;
            min-width: 80px;
        }
        
        /* Overtime Rules */
        .overtime-rules {
            margin: 20px;
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .rules-content {
            padding: 20px;
        }
        .rule-item {
            background: #f8fafc;
            border-left: 4px solid #1ec7e6;
            padding: 15px;
            border-radius: 0 8px 8px 0;
            margin-bottom: 15px;
        }
        .rule-title {
            font-size: 14px;
            font-weight: 600;
            color: #333;
            margin-bottom: 5px;
        }
        .rule-description {
            font-size: 13px;
            color: #666;
            line-height: 1.5;
        }
        
        /* Action Buttons */
        .action-buttons {
            padding: 20px;
            display: flex;
            gap: 12px;
        }
        .action-btn {
            flex: 1;
            padding: 14px;
            border: none;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .action-btn.primary {
            background: linear-gradient(135deg, #1ec7e6, #0ea5e9);
            color: white;
        }
        .action-btn.secondary {
            background: #f3f4f6;
            color: #374151;
            border: 1px solid #e5e7eb;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="header-content">
            <button class="back-btn" onclick="goBack()">←</button>
            <div class="header-title">
                <h1>Shift Management</h1>
                <div class="header-subtitle">Konfigurasi shift dan lembur</div>
            </div>
        </div>
    </div>

    <!-- Shift Configuration -->
    <div class="shift-config">
        <div class="config-header">
            <div class="config-title">⏰ Konfigurasi Shift</div>
            <div class="config-subtitle">Atur jam kerja dan QR code untuk setiap shift</div>
        </div>
        
        <div class="shift-types">
            <!-- Morning Shift -->
            <div class="shift-item active">
                <div class="shift-header">
                    <div>
                        <div class="shift-name">Shift Pagi</div>
                        <div class="shift-time">06:00 - 14:00 WIB</div>
                    </div>
                    <div class="shift-badge morning">MORNING</div>
                </div>
                
                <div class="shift-details">
                    <div class="detail-item">
                        <div class="detail-label">Jam Masuk</div>
                        <div class="detail-value">06:00</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Jam Keluar</div>
                        <div class="detail-value">14:00</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Toleransi Telat</div>
                        <div class="detail-value">15 menit</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Aktif Hari</div>
                        <div class="detail-value">Senin - Jumat</div>
                    </div>
                </div>
                
                <div class="shift-qr">
                    <div class="qr-code">📱</div>
                    <div class="qr-text">QR Code Format:</div>
                    <div class="qr-value">MAIN_OFFICE:MORNING:LOBBY</div>
                </div>
            </div>
            
            <!-- Afternoon Shift -->
            <div class="shift-item">
                <div class="shift-header">
                    <div>
                        <div class="shift-name">Shift Siang</div>
                        <div class="shift-time">14:00 - 22:00 WIB</div>
                    </div>
                    <div class="shift-badge afternoon">AFTERNOON</div>
                </div>
                
                <div class="shift-details">
                    <div class="detail-item">
                        <div class="detail-label">Jam Masuk</div>
                        <div class="detail-value">14:00</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Jam Keluar</div>
                        <div class="detail-value">22:00</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Toleransi Telat</div>
                        <div class="detail-value">15 menit</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Aktif Hari</div>
                        <div class="detail-value">Senin - Jumat</div>
                    </div>
                </div>
                
                <div class="shift-qr">
                    <div class="qr-code">📱</div>
                    <div class="qr-text">QR Code Format:</div>
                    <div class="qr-value">MAIN_OFFICE:AFTERNOON:LOBBY</div>
                </div>
            </div>
            
            <!-- Night Shift -->
            <div class="shift-item">
                <div class="shift-header">
                    <div>
                        <div class="shift-name">Shift Malam</div>
                        <div class="shift-time">22:00 - 06:00 WIB</div>
                    </div>
                    <div class="shift-badge night">NIGHT</div>
                </div>
                
                <div class="shift-details">
                    <div class="detail-item">
                        <div class="detail-label">Jam Masuk</div>
                        <div class="detail-value">22:00</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Jam Keluar</div>
                        <div class="detail-value">06:00</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Toleransi Telat</div>
                        <div class="detail-value">15 menit</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Aktif Hari</div>
                        <div class="detail-value">Senin - Jumat</div>
                    </div>
                </div>
                
                <div class="shift-qr">
                    <div class="qr-code">📱</div>
                    <div class="qr-text">QR Code Format:</div>
                    <div class="qr-value">MAIN_OFFICE:NIGHT:LOBBY</div>
                </div>
            </div>
            
            <!-- Overtime -->
            <div class="shift-item">
                <div class="shift-header">
                    <div>
                        <div class="shift-name">Lembur</div>
                        <div class="shift-time">Fleksibel (Min 2 jam)</div>
                    </div>
                    <div class="shift-badge overtime">OVERTIME</div>
                </div>
                
                <div class="shift-details">
                    <div class="detail-item">
                        <div class="detail-label">Durasi Minimum</div>
                        <div class="detail-value">2 jam</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Durasi Maksimum</div>
                        <div class="detail-value">8 jam</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Rate Multiplier</div>
                        <div class="detail-value">1.5x</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Approval</div>
                        <div class="detail-value">Required</div>
                    </div>
                </div>
                
                <div class="shift-qr">
                    <div class="qr-code">📱</div>
                    <div class="qr-text">QR Code Format:</div>
                    <div class="qr-value">MAIN_OFFICE:OVERTIME:LOBBY</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Employee Assignment -->
    <div class="employee-assignment">
        <div class="config-header">
            <div class="config-title">👥 Assignment Karyawan</div>
            <div class="config-subtitle">Tentukan shift untuk setiap karyawan</div>
        </div>
        
        <div class="assignment-list">
            <div class="employee-item">
                <div class="employee-avatar">WM</div>
                <div class="employee-info">
                    <div class="employee-name">Widya Mayasari</div>
                    <div class="employee-id">EMP001</div>
                </div>
                <select class="shift-selector">
                    <option value="MORNING" selected>Pagi</option>
                    <option value="AFTERNOON">Siang</option>
                    <option value="NIGHT">Malam</option>
                </select>
            </div>
            
            <div class="employee-item">
                <div class="employee-avatar">JD</div>
                <div class="employee-info">
                    <div class="employee-name">John Doe</div>
                    <div class="employee-id">EMP002</div>
                </div>
                <select class="shift-selector">
                    <option value="MORNING">Pagi</option>
                    <option value="AFTERNOON" selected>Siang</option>
                    <option value="NIGHT">Malam</option>
                </select>
            </div>
            
            <div class="employee-item">
                <div class="employee-avatar">JS</div>
                <div class="employee-info">
                    <div class="employee-name">Jane Smith</div>
                    <div class="employee-id">EMP003</div>
                </div>
                <select class="shift-selector">
                    <option value="MORNING">Pagi</option>
                    <option value="AFTERNOON">Siang</option>
                    <option value="NIGHT" selected>Malam</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Overtime Rules -->
    <div class="overtime-rules">
        <div class="config-header">
            <div class="config-title">📋 Aturan Lembur</div>
            <div class="config-subtitle">Kebijakan dan aturan untuk lembur</div>
        </div>
        
        <div class="rules-content">
            <div class="rule-item">
                <div class="rule-title">🕐 Waktu Lembur</div>
                <div class="rule-description">
                    Lembur dapat dilakukan setelah jam kerja normal atau di hari libur. 
                    Durasi minimum 2 jam, maksimum 8 jam per hari.
                </div>
            </div>
            
            <div class="rule-item">
                <div class="rule-title">💰 Rate Lembur</div>
                <div class="rule-description">
                    Lembur weekdays: 1.5x gaji per jam<br>
                    Lembur weekend: 2.0x gaji per jam<br>
                    Lembur hari libur: 3.0x gaji per jam
                </div>
            </div>
            
            <div class="rule-item">
                <div class="rule-title">✅ Approval Required</div>
                <div class="rule-description">
                    Semua lembur harus mendapat persetujuan supervisor sebelum 
                    dilakukan. Request lembur maksimal H-1.
                </div>
            </div>
            
            <div class="rule-item">
                <div class="rule-title">📍 Lokasi Tracking</div>
                <div class="rule-description">
                    Lembur harus dilakukan di lokasi kantor dengan QR code yang valid. 
                    GPS tracking aktif selama periode lembur.
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="action-buttons">
        <button class="action-btn secondary" onclick="exportConfig()">Export Config</button>
        <button class="action-btn primary" onclick="saveChanges()">Save Changes</button>
    </div>

    <script src="components/popup.js"></script>
    <script>
        function goBack() {
            window.location.href = 'dashboard';
        }
        
        function exportConfig() {
            const config = {
                shifts: {
                    MORNING: {
                        name: 'Shift Pagi',
                        start: '06:00',
                        end: '14:00',
                        tolerance: 15,
                        days: ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'],
                        qrFormat: 'MAIN_OFFICE:MORNING:LOBBY'
                    },
                    AFTERNOON: {
                        name: 'Shift Siang',
                        start: '14:00',
                        end: '22:00',
                        tolerance: 15,
                        days: ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'],
                        qrFormat: 'MAIN_OFFICE:AFTERNOON:LOBBY'
                    },
                    NIGHT: {
                        name: 'Shift Malam',
                        start: '22:00',
                        end: '06:00',
                        tolerance: 15,
                        days: ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'],
                        qrFormat: 'MAIN_OFFICE:NIGHT:LOBBY'
                    },
                    OVERTIME: {
                        name: 'Lembur',
                        minDuration: 2,
                        maxDuration: 8,
                        rateMultiplier: 1.5,
                        approvalRequired: true,
                        qrFormat: 'MAIN_OFFICE:OVERTIME:LOBBY'
                    }
                },
                employees: [
                    { id: 'EMP001', name: 'Widya Mayasari', assignedShift: 'MORNING' },
                    { id: 'EMP002', name: 'John Doe', assignedShift: 'AFTERNOON' },
                    { id: 'EMP003', name: 'Jane Smith', assignedShift: 'NIGHT' }
                ],
                overtimeRules: {
                    weekdayRate: 1.5,
                    weekendRate: 2.0,
                    holidayRate: 3.0,
                    maxRadius: 500,
                    approvalDeadline: 1
                }
            };
            
            const blob = new Blob([JSON.stringify(config, null, 2)], { type: 'application/json' });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'shift-config.json';
            a.click();
            URL.revokeObjectURL(url);
            
            showSuccessPopup({
                title: 'Export Berhasil',
                message: 'Konfigurasi shift telah diexport ke file JSON',
                buttonText: 'OK'
            });
        }
        
        function saveChanges() {
            const employees = document.querySelectorAll('.employee-item');
            const assignments = [];
            
            employees.forEach(emp => {
                const name = emp.querySelector('.employee-name').textContent;
                const id = emp.querySelector('.employee-id').textContent;
                const shift = emp.querySelector('.shift-selector').value;
                
                assignments.push({
                    id: id,
                    name: name,
                    assignedShift: shift
                });
            });
            
            localStorage.setItem('shiftAssignments', JSON.stringify(assignments));
            
            showSuccessPopup({
                title: 'Perubahan Disimpan',
                message: 'Konfigurasi shift dan assignment karyawan telah disimpan',
                buttonText: 'OK'
            });
        }
        
        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Load existing assignments if any
            const savedAssignments = localStorage.getItem('shiftAssignments');
            if (savedAssignments) {
                const assignments = JSON.parse(savedAssignments);
                
                assignments.forEach(assignment => {
                    const empElement = Array.from(document.querySelectorAll('.employee-item')).find(emp => 
                        emp.querySelector('.employee-id').textContent === assignment.id
                    );
                    
                    if (empElement) {
                        empElement.querySelector('.shift-selector').value = assignment.assignedShift;
                    }
                });
            }
        }); 
    </script>
</body>
</html>