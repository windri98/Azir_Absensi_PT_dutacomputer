@extends('layouts.app')

@section('title', 'Detail Aktivitas - PT DUTA COMPUTER')

@section('content')
<div class="px-4 py-8 lg:px-8 max-w-5xl mx-auto">
    <div class="flex items-center gap-4 mb-6">
        <button class="btn btn-secondary !p-2 shadow-sm" onclick="history.back()">
            <i class="fas fa-chevron-left"></i>
        </button>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Detail Aktivitas</h1>
            <p class="text-sm text-gray-600">Informasi lengkap aktivitas teknisi</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-soft border border-gray-100 p-6 mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h3 class="text-xl font-bold text-gray-900">{{ $activity->title }}</h3>
                <p class="text-sm text-gray-600 mt-1">{{ $activity->description ?? '-' }}</p>
            </div>
            <span class="inline-block px-3 py-1 rounded-full text-xs font-bold
                @if($activity->status === 'approved') bg-green-100 text-green-700
                @elseif($activity->status === 'rejected') bg-red-100 text-red-700
                @elseif($activity->status === 'signed') bg-blue-100 text-blue-700
                @else bg-yellow-100 text-yellow-700
                @endif">
                {{ strtoupper($activity->status) }}
            </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6 text-sm text-gray-700">
            <div>
                <span class="text-gray-500">Mitra</span>
                <div class="font-semibold">{{ $activity->partner->name ?? '-' }}</div>
            </div>
            <div>
                <span class="text-gray-500">PIC Mitra</span>
                <div class="font-semibold">{{ $activity->signature_name ?? '-' }}</div>
            </div>
            <div>
                <span class="text-gray-500">Waktu Mulai</span>
                <div class="font-semibold">{{ $activity->start_time?->format('d M Y H:i') }}</div>
            </div>
            <div>
                <span class="text-gray-500">Waktu Selesai</span>
                <div class="font-semibold">{{ $activity->end_time?->format('d M Y H:i') ?? '-' }}</div>
            </div>
            <div>
                <span class="text-gray-500">Disetujui Oleh</span>
                <div class="font-semibold">{{ $activity->approvedBy->name ?? '-' }}</div>
            </div>
            <div>
                <span class="text-gray-500">Waktu Approval</span>
                <div class="font-semibold">{{ $activity->approved_at?->format('d M Y H:i') ?? '-' }}</div>
            </div>
        </div>

        @if($activity->rejected_reason)
        <div class="mt-4 p-4 rounded-xl bg-red-50 text-red-700 text-sm">
            <strong>Alasan Penolakan:</strong> {{ $activity->rejected_reason }}
        </div>
        @endif
    </div>

    <div class="bg-white rounded-2xl shadow-soft border border-gray-100 p-6 mb-6">
        <h4 class="text-lg font-bold text-gray-900 mb-3">Bukti Pekerjaan</h4>
        @if($activity->evidence_path)
            <img src="{{ asset('storage/' . $activity->evidence_path) }}" alt="Bukti Aktivitas" class="rounded-xl border border-gray-200">
        @else
            <p class="text-sm text-gray-500">Tidak ada bukti.</p>
        @endif
    </div>

    <div class="bg-white rounded-2xl shadow-soft border border-gray-100 p-6">
        <h4 class="text-lg font-bold text-gray-900 mb-3">Tanda Tangan PIC</h4>
        @if($activity->signature_path)
            <img src="{{ asset('storage/' . $activity->signature_path) }}" alt="Tanda Tangan" class="rounded-xl border border-gray-200">
        @else
            <p class="text-sm text-gray-500">Belum ada tanda tangan.</p>
        @endif
    </div>
</div>
@endsection
