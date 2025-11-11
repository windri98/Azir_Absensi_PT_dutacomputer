<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Jadwal Shift</title>
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
            padding-bottom: 20px;
        }
        
        @media (min-width: 394px) {
            body {
                box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            }
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
            font-size: 18px;
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
            <a href="{{ route('dashboard') }}" class="back-btn" style="text-decoration: none;">←</a>
            <div class="header-title">
                <h1>Jadwal Kerja Karyawan</h1>
                <div class="header-subtitle">Shift Management - Read Only</div>
            </div>
        </div>
    </div>

    <!-- Statistics -->
    <div class="shift-config">
        <div class="config-header">
            <div class="config-title">📊 Statistik Karyawan</div>
            <div class="config-subtitle">Ringkasan data shift karyawan</div>
        </div>
        
        <div class="shift-types" style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px; padding: 20px;">
            <div style="background: #f8fafc; border-radius: 12px; padding: 16px; text-align: center;">
                <div style="font-size: 12px; color: #666; margin-bottom: 8px;">Total Karyawan</div>
                <div style="font-size: 28px; font-weight: 700; color: #667eea;">{{ $users->total() }}</div>
            </div>
            <div style="background: #f8fafc; border-radius: 12px; padding: 16px; text-align: center;">
                <div style="font-size: 12px; color: #666; margin-bottom: 8px;">Belum Ada Shift</div>
                <div style="font-size: 28px; font-weight: 700; color: #f59e0b;">{{ $usersWithoutShifts }}</div>
            </div>
            @foreach($shiftStats as $shift)
            <div style="background: #f8fafc; border-radius: 12px; padding: 16px; text-align: center;">
                <div style="font-size: 12px; color: #666; margin-bottom: 8px;">{{ $shift->name }}</div>
                <div style="font-size: 28px; font-weight: 700; color: #333;">{{ $shift->users_count }}</div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Search & Filter -->
    <div style="padding: 0 20px 20px;">
        <form method="GET" action="{{ route('management.shift') }}" id="filterForm">
            <div style="background: white; border-radius: 12px; padding: 12px 16px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08); display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
                <span style="font-size: 18px;">🔍</span>
                <input type="text" name="search" placeholder="Cari nama atau ID karyawan..." value="{{ $search }}" 
                       style="border: none; outline: none; flex: 1; font-size: 14px;"
                       onchange="document.getElementById('filterForm').submit()">
            </div>
            <select name="shift_filter" style="width: 100%; padding: 12px 16px; border-radius: 12px; border: 1px solid #e5e7eb; background: white; font-size: 14px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);"
                    onchange="document.getElementById('filterForm').submit()">
                <option value="">Semua Shift</option>
                @foreach($shifts as $shift)
                <option value="{{ $shift->id }}" {{ $shiftFilter == $shift->id ? 'selected' : '' }}>{{ $shift->name }}</option>
                @endforeach
            </select>
        </form>
    </div>

    <!-- Employee List -->
    <div class="employee-assignment">
        <div class="config-header">
            <div class="config-title">👥 Daftar Karyawan & Shift</div>
            <div class="config-subtitle">{{ $users->total() }} karyawan terdaftar</div>
        </div>
        
        <div class="assignment-list">
            @forelse($users as $user)
            <div class="employee-item">
                <div class="employee-avatar">{{ strtoupper(substr($user->name, 0, 2)) }}</div>
                <div class="employee-info">
                    <div class="employee-name">{{ $user->name }}</div>
                    <div class="employee-id">{{ $user->employee_id }}</div>
                    @if($user->roles->isNotEmpty())
                    <div style="font-size: 11px; color: #999; margin-top: 2px;">{{ $user->roles->first()->name }}</div>
                    @endif
                </div>
                <div style="text-align: right; font-size: 12px;">
                    @if($user->shifts->isNotEmpty())
                        @foreach($user->shifts as $shift)
                        <div style="background: #dbeafe; color: #1e40af; padding: 6px 12px; border-radius: 6px; margin-bottom: 4px; font-weight: 500;">
                            {{ $shift->name }}<br>
                            <span style="font-size: 10px; opacity: 0.8;">{{ substr($shift->start_time, 0, 5) }}-{{ substr($shift->end_time, 0, 5) }}</span>
                        </div>
                        @endforeach
                    @else
                        <div style="background: #f3f4f6; color: #6b7280; padding: 6px 12px; border-radius: 6px; font-size: 11px;">
                            ⚠️ Belum Ada Shift
                        </div>
                    @endif
                </div>
            </div>
            @empty
            <div style="text-align: center; padding: 40px 20px; color: #999;">
                <div style="font-size: 48px; margin-bottom: 16px;">📋</div>
                <div style="font-size: 16px; font-weight: 600; margin-bottom: 8px;">Tidak Ada Data</div>
                <div style="font-size: 14px;">
                    @if($search || $shiftFilter)
                    Tidak ada karyawan yang sesuai dengan filter
                    @else
                    Belum ada data karyawan
                    @endif
                </div>
            </div>
            @endforelse
        </div>
        
        <!-- Pagination -->
        @if($users->hasPages())
        <div style="display: flex; justify-content: center; align-items: center; gap: 8px; padding: 20px;">
            @if($users->onFirstPage())
                <span style="padding: 8px 16px; border-radius: 8px; border: 1px solid #e5e7eb; background: #f3f4f6; color: #999; font-size: 14px; opacity: 0.5;">← Prev</span>
            @else
                <a href="{{ $users->previousPageUrl() }}" style="padding: 8px 16px; border-radius: 8px; border: 1px solid #e5e7eb; background: white; color: #666; font-size: 14px; text-decoration: none;">← Prev</a>
            @endif
            
            <span style="padding: 8px 16px; border-radius: 8px; background: #667eea; color: white; font-size: 14px; border: 1px solid #667eea;">{{ $users->currentPage() }}</span>
            
            @if($users->hasMorePages())
                <a href="{{ $users->nextPageUrl() }}" style="padding: 8px 16px; border-radius: 8px; border: 1px solid #e5e7eb; background: white; color: #666; font-size: 14px; text-decoration: none;">Next →</a>
            @else
                <span style="padding: 8px 16px; border-radius: 8px; border: 1px solid #e5e7eb; background: #f3f4f6; color: #999; font-size: 14px; opacity: 0.5;">Next →</span>
            @endif
        </div>
        @endif
    </div>

    <script src="{{ asset('components/popup.js') }}"></script>
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
        
        // Auto-submit form on input change with debounce
        let searchTimeout;
        const searchInput = document.querySelector('input[name="search"]');
        if (searchInput) {
            searchInput.addEventListener('input', function(e) {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    document.getElementById('filterForm').submit();
                }, 500);
            });
        }
    </script>
</body>
</html>
