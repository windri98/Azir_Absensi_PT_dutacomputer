<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan User - Sistem Absensi</title>
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

        .filter-section {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }

        .filter-form {
            display: flex;
            gap: 15px;
            align-items: center;
            flex-wrap: wrap;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 5px;
            min-width: 200px;
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
            text-decoration: none;
            display: inline-block;
        }

        .btn:hover {
            background: #0284c7;
            transform: translateY(-1px);
        }

        .btn-secondary {
            background: #6b7280;
        }

        .btn-secondary:hover {
            background: #4b5563;
        }

        .users-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .user-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 20px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .user-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        }

        .user-header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 15px;
        }

        .user-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, #1ec7e6, #0ea5e9);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
            font-weight: bold;
        }

        .user-info h3 {
            color: #1f2937;
            margin-bottom: 5px;
            font-size: 18px;
        }

        .user-info p {
            color: #6b7280;
            font-size: 14px;
            margin-bottom: 3px;
        }

        .user-stats {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
            margin-bottom: 15px;
        }

        .stat-item {
            text-align: center;
            padding: 10px;
            background: #f8fafc;
            border-radius: 8px;
        }

        .stat-value {
            font-size: 20px;
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 3px;
        }

        .stat-label {
            font-size: 12px;
            color: #6b7280;
            font-weight: 600;
        }

        .user-actions {
            display: flex;
            gap: 10px;
        }

        .btn-detail {
            background: #0ea5e9;
            color: white;
            text-decoration: none;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
            transition: all 0.3s ease;
            flex: 1;
            text-align: center;
        }

        .btn-detail:hover {
            background: #0284c7;
            color: white;
        }

        .no-data {
            text-align: center;
            padding: 60px 20px;
            color: #9ca3af;
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .no-data h3 {
            margin-bottom: 10px;
            font-size: 18px;
        }

        @media (max-width: 768px) {
            .container {
                padding: 15px;
            }

            .header-content {
                padding: 0 15px;
            }

            .filter-form {
                flex-direction: column;
                align-items: stretch;
            }

            .users-grid {
                grid-template-columns: 1fr;
                gap: 15px;
            }

            .user-card {
                padding: 15px;
            }

            .user-header {
                flex-direction: column;
                text-align: center;
            }

            .user-stats {
                grid-template-columns: repeat(4, 1fr);
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-content">
            <button class="back-btn" onclick="goBack()">←</button>
            <div class="header-title">
                <h1>Laporan Per User</h1>
                <p>Pilih karyawan untuk melihat detail laporan</p>
            </div>
        </div>
    </div>

    <div class="container">
        <!-- Filter Section -->
        <div class="filter-section">
            <form method="GET" class="filter-form">
                <div class="form-group">
                    <label for="search">Cari Karyawan</label>
                    <input type="text" name="search" id="search" placeholder="Nama atau email..." value="{{ request('search') }}">
                </div>
                <div class="form-group">
                    <label for="role">Role</label>
                    <select name="role" id="role">
                        <option value="">Semua Role</option>
                        <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="manager" {{ request('role') == 'manager' ? 'selected' : '' }}>Manager</option>
                        <option value="employee" {{ request('role') == 'employee' ? 'selected' : '' }}>Employee</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>&nbsp;</label>
                    <button type="submit" class="btn">Filter</button>
                </div>
                @if(request()->hasAny(['search', 'role']))
                <div class="form-group">
                    <label>&nbsp;</label>
                    <a href="{{ route('reports.users') }}" class="btn btn-secondary">Reset</a>
                </div>
                @endif
            </form>
        </div>

        <!-- Users Grid -->
        @if($users->count() > 0)
        <div class="users-grid">
            @foreach($users as $user)
            @php
                $currentMonth = now()->month;
                $currentYear = now()->year;
                $monthlyAttendances = $user->attendances->filter(function($attendance) use ($currentMonth, $currentYear) {
                    return \Carbon\Carbon::parse($attendance->date)->month == $currentMonth && 
                           \Carbon\Carbon::parse($attendance->date)->year == $currentYear;
                });
                
                $stats = [
                    'total' => $monthlyAttendances->count(),
                    'present' => $monthlyAttendances->where('status', 'present')->count(),
                    'late' => $monthlyAttendances->where('status', 'late')->count(),
                    'absent' => $monthlyAttendances->where('status', 'absent')->count(),
                ];
            @endphp
            <div class="user-card">
                <div class="user-header">
                    <div class="user-avatar">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div class="user-info">
                        <h3>{{ $user->name }}</h3>
                        <p>{{ $user->email }}</p>
                        @if($user->roles->isNotEmpty())
                        <p><strong>Role:</strong> {{ $user->roles->pluck('name')->join(', ') }}</p>
                        @endif
                    </div>
                </div>

                <div class="user-stats">
                    <div class="stat-item">
                        <div class="stat-value">{{ $stats['total'] }}</div>
                        <div class="stat-label">Total</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value" style="color: #10b981;">{{ $stats['present'] }}</div>
                        <div class="stat-label">Hadir</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value" style="color: #f59e0b;">{{ $stats['late'] }}</div>
                        <div class="stat-label">Terlambat</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value" style="color: #ef4444;">{{ $stats['absent'] }}</div>
                        <div class="stat-label">Tidak Hadir</div>
                    </div>
                </div>

                <div class="user-actions">
                    <a href="{{ route('reports.user-detail', $user->id) }}" class="btn-detail">
                        📊 Lihat Detail Laporan
                    </a>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($users->hasPages())
        <div style="margin-top: 20px;">
            {{ $users->links() }}
        </div>
        @endif
        @else
        <div class="no-data">
            <h3>Tidak Ada Data</h3>
            <p>Tidak ada karyawan ditemukan sesuai kriteria pencarian.</p>
        </div>
        @endif
    </div>

    <script>
        function goBack() {
            if (typeof smartGoBack === 'function') {
                smartGoBack('{{ route("dashboard") }}');
            } else {
                // Fallback navigation
                if (window.history.length > 1 && document.referrer && 
                    document.referrer !== window.location.href &&
                    !document.referrer.includes('login')) {
                    try {
                        window.history.back();
                    } catch (error) {
                        window.location.href = '{{ route("dashboard") }}';
                    }
                } else {
                    window.location.href = '{{ route("dashboard") }}';
                }
            }
        }

        // Auto submit search after typing
        let searchTimeout;
        document.getElementById('search').addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                this.form.submit();
            }, 500);
        });

        // Auto submit when role changes
        document.getElementById('role').addEventListener('change', function() {
            this.form.submit();
        });
    </script>
</body>
</html>