@extends('layouts.app')

@section('title', 'Riwayat Aktivitas - PT DUTA COMPUTER')

@section('content')
<div class="px-4 py-8 lg:px-8 max-w-6xl mx-auto">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div class="flex items-center gap-4">
            <button class="btn btn-secondary !p-2 shadow-sm" onclick="history.back()">
                <i class="fas fa-chevron-left"></i>
            </button>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Riwayat Aktivitas</h1>
                <p class="text-sm text-gray-600">Pantau status aktivitas teknisi Anda</p>
            </div>
        </div>
        <a href="{{ route('activities.create') }}" class="btn btn-primary">
            <i class="fas fa-plus mr-2"></i>Aktivitas Baru
        </a>
    </div>

    @if($activities->count() > 0)
        <div class="space-y-4">
            @foreach($activities as $activity)
                <div class="bg-white rounded-2xl shadow-soft border border-gray-100 p-6 hover:shadow-card-hover transition-all duration-300">
                    <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <h3 class="text-lg font-bold text-gray-900">{{ $activity->title }}</h3>
                                <span class="inline-block px-3 py-1 rounded-full text-xs font-bold
                                    @if($activity->status === 'approved') bg-green-100 text-green-700
                                    @elseif($activity->status === 'rejected') bg-red-100 text-red-700
                                    @elseif($activity->status === 'signed') bg-blue-100 text-blue-700
                                    @else bg-yellow-100 text-yellow-700
                                    @endif">
                                    {{ strtoupper($activity->status) }}
                                </span>
                            </div>
                            <p class="text-gray-600 text-sm mb-3">{{ \Illuminate\Support\Str::limit($activity->description, 120) }}</p>
                            <div class="flex flex-wrap gap-4 text-xs text-gray-500">
                                <span><i class="fas fa-building mr-1"></i>{{ $activity->partner->name ?? '-' }}</span>
                                <span><i class="fas fa-calendar mr-1"></i>{{ $activity->created_at->format('d M Y H:i') }}</span>
                                <span><i class="fas fa-clock mr-1"></i>{{ $activity->start_time?->format('H:i') }}</span>
                            </div>
                        </div>
                        <div class="flex flex-col gap-2">
                            <a href="{{ route('activities.show', $activity) }}" class="btn btn-primary text-sm">
                                <i class="fas fa-eye mr-1"></i>Lihat Detail
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-8 flex justify-center">
            {{ $activities->links() }}
        </div>
    @else
        <div class="bg-white rounded-2xl shadow-soft border border-gray-100 p-12 text-center">
            <div class="mb-4">
                <i class="fas fa-clipboard-list text-6xl text-gray-300"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-900 mb-2">Belum Ada Aktivitas</h3>
            <p class="text-gray-600 mb-6">Anda belum membuat aktivitas apapun.</p>
            <a href="{{ route('activities.create') }}" class="btn btn-primary">
                <i class="fas fa-plus mr-2"></i>Buat Aktivitas
            </a>
        </div>
    @endif
</div>
@endsection
