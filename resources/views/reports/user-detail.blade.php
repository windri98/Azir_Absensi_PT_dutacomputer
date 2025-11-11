<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Laporan {{ $user->name }} - Sistem Absensi</title>
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
            min-height: 100vh;
        }

        .header {
            background: linear-gradient(135deg, #1ec7e6, #0ea5e9);
            color: white;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .header-content {
            display: flex;
            align-items: center;
            gap: 15px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .back-btn {
            background: rgba(255,255,255,0.2);
            border: none;
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 18px;
            transition: all 0.3s ease;
        }

        .back-btn:hover {
            background: rgba(255,255,255,0.3);
            transform: translateX(-2px);
        }

        .header-title {
            flex: 1;
        }

        .header-title h1 {
            font-size: 24px;
            margin-bottom: 5px;
        }

        .header-title p {
            opacity: 0.9;
            font-size: 14px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .user-info {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .user-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: linear-gradient(135deg, #1ec7e6, #0ea5e9);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 32px;
            font-weight: bold;
        }

        .user-details h2 {
            color: #1f2937;
            margin-bottom: 5px;
        }

        .user-details p {
            color: #6b7280;
            margin-bottom: 3px;
        }

        .period-selector {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }

        .period-form {
            display: flex;
            gap: 15px;
            align-items: center;
            flex-wrap: wrap;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .form-group label {
            font-weight: 600;
            color: #374151;
            font-size: 14px;
        }

        .form-group input,
        .form-group select {
            padding: 10px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 14px;
            min-width: 120px;
        }

        .btn {
            padding: 10px 20px;
            background: #0ea5e9;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn:hover {
            background: #0284c7;
            transform: translateY(-1px);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            text-align: center;
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);
        }

        .stat-icon {
            font-size: 32px;
            margin-bottom: 10px;
            display: block;
        }

        .stat-value {
            font-size: 28px;
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 5px;
        }

        .stat-label {
            color: #6b7280;
            font-size: 14px;
            font-weight: 600;
        }

        .present { color: #10b981; }
        .late { color: #f59e0b; }
        .absent { color: #ef4444; }
        .sick { color: #8b5cf6; }
        .leave { color: #06b6d4; }
        .overtime { color: #f97316; }

        .attendance-table {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .table-header {
            background: #f8fafc;
            padding: 20px;
            border-bottom: 1px solid #e5e7eb;
        }

        .table-header h3 {
            color: #1f2937;
            margin-bottom: 5px;
        }

        .table-header p {
            color: #6b7280;
            font-size: 14px;
        }

        .table-container {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #f3f4f6;
        }

        th {
            background: #f8fafc;
            font-weight: 600;
            color: #374151;
            font-size: 14px;
        }

        td {
            color: #6b7280;
            font-size: 14px;
        }

        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .badge-present {
            background: #d1fae5;
            color: #065f46;
        }

        .badge-late {
            background: #fef3c7;
            color: #92400e;
        }

        .badge-absent {
            background: #fee2e2;
            color: #991b1b;
        }

        .badge-sick {
            background: #ede9fe;
            color: #5b21b6;
        }

        .badge-leave {
            background: #cffafe;
            color: #0e7490;
        }

        .badge-overtime {
            background: #fed7aa;
            color: #9a3412;
        }

        .weekend {
            background: #f9fafb;
        }

        .no-data {
            text-align: center;
            padding: 40px;
            color: #9ca3af;
        }

        @media (max-width: 768px) {
            .container {
                padding: 15px;
            }

            .header-content {
                padding: 0 15px;
            }

            .user-info {
                flex-direction: column;
                text-align: center;
            }

            .period-form {
                flex-direction: column;
                align-items: stretch;
            }

            .stats-grid {
                grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
                gap: 15px;
            }

            .stat-card {
                padding: 15px;
            }

            .stat-icon {
                font-size: 24px;
            }

            .stat-value {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-content">
            <button class="back-btn" onclick="goBack()">←</button>
            <div class="header-title">
                <h1>Detail Laporan User</h1>
                <p>Laporan absensi dan aktivitas karyawan</p>
            </div>
        </div>
    </div>

    <div class="container">
        <!-- User Information -->
        <div class="user-info">
            <div class="user-avatar">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <div class="user-details">
                <h2>{{ $user->name }}</h2>
                <p><strong>Email:</strong> {{ $user->email }}</p>
                <p><strong>ID Karyawan:</strong> {{ $user->id }}</p>
                @if($user->roles->isNotEmpty())
                <p><strong>Role:</strong> {{ $user->roles->pluck('name')->join(', ') }}</p>
                @endif
            </div>
        </div>

        <!-- Period Selector -->
        <div class="period-selector">
            <form method="GET" action="{{ route('reports.user-detail', $user->id) }}" class="period-form">
                <div class="form-group">
                    <label for="month">Bulan</label>
                    <select name="month" id="month">
                        @for($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}" {{ $month == $i ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($i)->format('F') }}
                        </option>
                        @endfor
                    </select>
                </div>
                <div class="form-group">
                    <label for="year">Tahun</label>
                    <select name="year" id="year">
                        @for($i = date('Y'); $i >= date('Y') - 3; $i--)
                        <option value="{{ $i }}" {{ $year == $i ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                </div>
                <div class="form-group">
                    <label>&nbsp;</label>
                    <button type="submit" class="btn">Filter</button>
                </div>
            </form>
        </div>

        <!-- Statistics Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <span class="stat-icon">📅</span>
                <div class="stat-value">{{ $stats['total_days'] }}</div>
                <div class="stat-label">Total Hari</div>
            </div>
            <div class="stat-card">
                <span class="stat-icon present">✅</span>
                <div class="stat-value present">{{ $stats['present'] }}</div>
                <div class="stat-label">Hadir</div>
            </div>
            <div class="stat-card">
                <span class="stat-icon late">⏰</span>
                <div class="stat-value late">{{ $stats['late'] }}</div>
                <div class="stat-label">Terlambat</div>
            </div>
            <div class="stat-card">
                <span class="stat-icon absent">❌</span>
                <div class="stat-value absent">{{ $stats['absent'] }}</div>
                <div class="stat-label">Tidak Hadir</div>
            </div>
            <div class="stat-card">
                <span class="stat-icon sick">🤒</span>
                <div class="stat-value sick">{{ $stats['sick'] }}</div>
                <div class="stat-label">Sakit</div>
            </div>
            <div class="stat-card">
                <span class="stat-icon leave">🏖️</span>
                <div class="stat-value leave">{{ $stats['leave'] }}</div>
                <div class="stat-label">Izin</div>
            </div>
            <div class="stat-card">
                <span class="stat-icon overtime">🕐</span>
                <div class="stat-value overtime">{{ number_format($stats['total_work_hours'], 1) }}</div>
                <div class="stat-label">Total Jam Kerja</div>
            </div>
            <div class="stat-card">
                <span class="stat-icon overtime">⏱️</span>
                <div class="stat-value overtime">{{ number_format($stats['average_work_hours'], 1) }}</div>
                <div class="stat-label">Rata-rata Jam/Hari</div>
            </div>
        </div>

        <!-- Attendance Table -->
        <div class="attendance-table">
            <div class="table-header">
                <h3>Detail Absensi - {{ \Carbon\Carbon::create($year, $month)->format('F Y') }}</h3>
                <p>Rincian absensi harian karyawan</p>
            </div>
            <div class="table-container">
                @if($attendances->count() > 0)
                <table>
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Hari</th>
                            <th>Status</th>
                            <th>Jam Masuk</th>
                            <th>Jam Keluar</th>
                            <th>Jam Kerja</th>
                            <th>Lembur</th>
                            <th>Lokasi</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($monthlyData as $dayData)
                        @php
                            $attendance = $dayData['attendance'];
                            $isWeekend = $dayData['is_weekend'];
                        @endphp
                        <tr class="{{ $isWeekend ? 'weekend' : '' }}">
                            <td>{{ $dayData['date']->format('d M Y') }}</td>
                            <td>{{ $dayData['date']->format('l') }}</td>
                            <td>
                                @if($attendance)
                                    <span class="status-badge badge-{{ $attendance->status }}">
                                        {{ ucfirst($attendance->status) }}
                                    </span>
                                @elseif($isWeekend)
                                    <span class="status-badge" style="background: #f3f4f6; color: #6b7280;">Weekend</span>
                                @else
                                    <span class="status-badge badge-absent">Absent</span>
                                @endif
                            </td>
                            <td>{{ $attendance ? ($attendance->clock_in ? \Carbon\Carbon::parse($attendance->clock_in)->format('H:i') : '-') : '-' }}</td>
                            <td>{{ $attendance ? ($attendance->clock_out ? \Carbon\Carbon::parse($attendance->clock_out)->format('H:i') : '-') : '-' }}</td>
                            <td>{{ $attendance ? ($attendance->work_hours ? number_format($attendance->work_hours, 1) . ' jam' : '-') : '-' }}</td>
                            <td>{{ $attendance ? ($attendance->overtime_hours ? number_format($attendance->overtime_hours, 1) . ' jam' : '-') : '-' }}</td>
                            <td>{{ $attendance ? ($attendance->location ?? '-') : '-' }}</td>
                            <td>{{ $attendance ? ($attendance->notes ?? '-') : ($isWeekend ? 'Hari libur' : 'Tidak ada data') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <div class="no-data">
                    <p>Tidak ada data absensi untuk periode yang dipilih</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        function goBack() {
            if (typeof smartGoBack === 'function') {
                smartGoBack('{{ route("reports.users") }}');
            } else {
                // Fallback navigation
                if (window.history.length > 1 && document.referrer && 
                    document.referrer !== window.location.href &&
                    !document.referrer.includes('login')) {
                    try {
                        window.history.back();
                    } catch (error) {
                        window.location.href = '{{ route("reports.users") }}';
                    }
                } else {
                    window.location.href = '{{ route("reports.users") }}';
                }
            }
        }

        // Auto submit form when period changes
        document.getElementById('month').addEventListener('change', function() {
            this.form.submit();
        });

        document.getElementById('year').addEventListener('change', function() {
            this.form.submit();
        });

        // Print functionality
        function printReport() {
            window.print();
        }
    </script>
</body>
</html>