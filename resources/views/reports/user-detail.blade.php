<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Laporan {{ $user->name }} - Sistem Absensi</title>
    <link rel="stylesheet" href="{{ asset('css/popup.css') }}">
    <link rel="stylesheet" href="{{ asset('css/report-user-detail.css') }}">
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
