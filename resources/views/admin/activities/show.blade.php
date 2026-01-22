@extends('admin.layout')

@section('title', 'Detail Aktivitas')

@section('content')
<div class="page-header">
    <h2>Detail Aktivitas</h2>
    <div class="actions">
        <a href="{{ route('admin.activities.index') }}" class="btn btn-secondary">Kembali</a>
    </div>
</div>

<div class="card" style="margin-bottom:16px">
    <h3 style="margin-bottom:8px">{{ $activity->title }}</h3>
    <p style="color:#6b7280;margin-bottom:12px">{{ $activity->description ?? '-' }}</p>

    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:12px">
        <div>
            <div class="text-sm text-gray-500">Teknisi</div>
            <div class="font-semibold">{{ $activity->user->name ?? '-' }}</div>
        </div>
        <div>
            <div class="text-sm text-gray-500">Mitra</div>
            <div class="font-semibold">{{ $activity->partner->name ?? '-' }}</div>
        </div>
        <div>
            <div class="text-sm text-gray-500">Waktu Mulai</div>
            <div class="font-semibold">{{ $activity->start_time?->format('d/m/Y H:i') }}</div>
        </div>
        <div>
            <div class="text-sm text-gray-500">Waktu Selesai</div>
            <div class="font-semibold">{{ $activity->end_time?->format('d/m/Y H:i') ?? '-' }}</div>
        </div>
        <div>
            <div class="text-sm text-gray-500">Status</div>
            <div class="font-semibold">{{ strtoupper($activity->status) }}</div>
        </div>
        <div>
            <div class="text-sm text-gray-500">PIC Mitra</div>
            <div class="font-semibold">{{ $activity->signature_name ?? '-' }}</div>
        </div>
    </div>
</div>

<div class="card" style="margin-bottom:16px">
    <h4 style="margin-bottom:12px">Bukti Pekerjaan</h4>
    @if($activity->evidence_path)
        <img src="{{ asset('storage/' . $activity->evidence_path) }}" alt="Bukti Aktivitas" style="max-width:100%;border-radius:10px;border:1px solid #e5e7eb">
    @else
        <p class="text-gray-500">Tidak ada bukti.</p>
    @endif
</div>

<div class="card" style="margin-bottom:16px">
    <h4 style="margin-bottom:12px">Tanda Tangan PIC Mitra</h4>
    @if($activity->signature_path)
        <img src="{{ asset('storage/' . $activity->signature_path) }}" alt="Tanda Tangan" style="max-width:100%;border-radius:10px;border:1px solid #e5e7eb">
    @else
        <p class="text-gray-500">Belum ada tanda tangan.</p>
    @endif
</div>

<div class="card" style="margin-bottom:16px">
    <h4 style="margin-bottom:12px">Approval</h4>
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:12px">
        <div>
            <div class="text-sm text-gray-500">Disetujui Oleh</div>
            <div class="font-semibold">{{ $activity->approvedBy->name ?? '-' }}</div>
        </div>
        <div>
            <div class="text-sm text-gray-500">Waktu Approval</div>
            <div class="font-semibold">{{ $activity->approved_at?->format('d/m/Y H:i') ?? '-' }}</div>
        </div>
        <div>
            <div class="text-sm text-gray-500">Alasan Penolakan</div>
            <div class="font-semibold">{{ $activity->rejected_reason ?? '-' }}</div>
        </div>
    </div>
</div>

@if($activity->status === 'signed')
<div class="card">
    <h4 style="margin-bottom:12px">Tindakan</h4>
    <div style="display:flex;gap:12px;flex-wrap:wrap">
        <form method="post" action="{{ route('admin.activities.approve', $activity) }}">
            @csrf
            <button class="btn btn-primary" onclick="return confirm('Setujui aktivitas ini?')">Setujui</button>
        </form>
        <form method="post" action="{{ route('admin.activities.reject', $activity) }}" style="flex:1;min-width:260px">
            @csrf
            <div style="display:flex;gap:10px;align-items:center">
                <input type="text" name="rejected_reason" class="input" placeholder="Alasan penolakan (min 10 karakter)" style="flex:1">
                <button class="btn btn-danger">Tolak</button>
            </div>
        </form>
    </div>
</div>
@endif
@endsection
