@extends('layouts.app')

@section('title', 'Manajemen Shift - Sistem Absensi')

@section('content')
    <div class="px-4 py-8 lg:px-8 max-w-6xl mx-auto">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div class="flex items-center gap-4">
                <button class="w-10 h-10 rounded-xl bg-white border border-gray-200 flex items-center justify-center text-gray-600 hover:bg-gray-50 transition-colors" onclick="history.back()">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Manajemen Shift</h1>
                    <p class="text-sm text-gray-500">Kelola jadwal kerja dan penugasan shift karyawan.</p>
                </div>
            </div>
            
            <form method="GET" action="{{ route('management.shift') }}" id="filterForm" class="flex flex-wrap gap-2">
                <div class="relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                        <i class="fas fa-search text-xs"></i>
                    </span>
                    <input type="text" name="search" placeholder="Cari karyawan..." value="{{ $search }}" 
                           class="pl-9 pr-4 py-2 bg-white border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-primary-500 w-64"
                           onchange="this.form.submit()">
                </div>
                <select name="shift_filter" class="px-4 py-2 bg-white border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-primary-500"
                        onchange="this.form.submit()">
                    <option value="">Semua Shift</option>
                    @foreach($shifts as $shift)
                    <option value="{{ $shift->id }}" {{ $shiftFilter == $shift->id ? 'selected' : '' }}>{{ $shift->name }}</option>
                    @endforeach
                </select>
            </form>
        </div>

        <!-- Stats Overview -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
            <div class="modern-card bg-white p-4 text-center border-l-4 border-indigo-500">
                <p class="text-2xl font-black text-indigo-600">{{ $users->total() }}</p>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Total Karyawan</p>
            </div>
            <div class="modern-card bg-white p-4 text-center border-l-4 border-amber-500">
                <p class="text-2xl font-black text-amber-600">{{ $usersWithoutShifts }}</p>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Tanpa Shift</p>
            </div>
            @foreach($shiftStats->take(2) as $shift)
            <div class="modern-card bg-white p-4 text-center border-l-4 border-emerald-500">
                <p class="text-2xl font-black text-emerald-600">{{ $shift->users_count }}</p>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ $shift->name }}</p>
            </div>
            @endforeach
        </div>

        <!-- Employee List Table -->
        <div class="modern-card bg-white !p-0 overflow-hidden shadow-sm border-gray-100">
            <div class="p-6 border-b border-gray-50">
                <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Penugasan Shift</h3>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100">
                            <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase">Karyawan</th>
                            <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase">Peran</th>
                            <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase">Shift Aktif</th>
                            <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($users as $user)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-primary-500 to-primary-600 flex items-center justify-center text-white font-bold text-xs">
                                        {{ strtoupper(substr($user->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-gray-900">{{ $user->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $user->employee_id }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 bg-gray-100 text-gray-600 rounded-lg text-[10px] font-bold uppercase tracking-wider">
                                    {{ $user->roles->first()->name ?? 'No Role' }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @forelse($user->shifts as $shift)
                                <div class="inline-flex flex-col mb-1 last:mb-0">
                                    <span class="text-sm font-bold text-primary-600">{{ $shift->name }}</span>
                                    <span class="text-[10px] text-gray-400">{{ substr($shift->start_time, 0, 5) }} - {{ substr($shift->end_time, 0, 5) }}</span>
                                </div>
                                @empty
                                <span class="text-xs text-amber-500 font-medium">⚠️ Belum Diatur</span>
                                @endforelse
                            </td>
                            <td class="px-6 py-4 text-right">
                                <button class="btn !bg-primary-50 !text-primary-600 !px-4 !py-2 !text-xs font-bold hover:!bg-primary-100 rounded-xl">
                                    Ubah Shift
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-gray-400">
                                <i class="fas fa-user-slash text-3xl mb-3 block"></i>
                                Tidak ada karyawan yang ditemukan.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($users->hasPages())
            <div class="p-6 border-t border-gray-50">
                {{ $users->links() }}
            </div>
            @endif
        </div>
    </div>
@endsection
