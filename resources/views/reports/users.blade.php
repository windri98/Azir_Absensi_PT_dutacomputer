<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan User - Sistem Absensi</title>
    <link rel="stylesheet" href="{{ asset('css/popup.css') }}">
    <link rel="stylesheet" href="{{ asset('css/report-users.css') }}">
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
