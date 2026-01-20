@extends('layouts.app')

@section('title', 'Riwayat Pengajuan - PT DUTA COMPUTER')

@section('content')
<div class="px-4 py-8 lg:px-8 max-w-6xl mx-auto">
    <!-- Page Header -->
    <div class="flex items-center gap-4 mb-8">
        <button class="btn btn-secondary !p-2 shadow-sm" onclick="goBack()">
            <i class="fas fa-chevron-left"></i>
        </button>
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Riwayat Pengajuan</h1>
            <p class="text-sm text-gray-600">Kelola pengajuan cuti, sakit, izin, dan keluhan Anda</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-2xl shadow-soft border border-gray-100 p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Filter Status</label>
                <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500" onchange="filterByStatus(this.value)">
                    <option value="">Semua Status</option>
                    <option value="pending" @selected(request('status') === 'pending')>Menunggu Persetujuan</option>
                    <option value="approved" @selected(request('status') === 'approved')>Disetujui</option>
                    <option value="rejected" @selected(request('status') === 'rejected')>Ditolak</option>
                    <option value="resolved" @selected(request('status') === 'resolved')>Selesai</option>
                    <option value="closed" @selected(request('status') === 'closed')>Ditutup</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Filter Kategori</label>
                <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500" onchange="filterByCategory(this.value)">
                    <option value="">Semua Kategori</option>
                    <option value="cuti" @selected(request('category') === 'cuti')>Cuti</option>
                    <option value="sakit" @selected(request('category') === 'sakit')>Sakit</option>
                    <option value="izin" @selected(request('category') === 'izin')>Izin</option>
                    <option value="technical" @selected(request('category') === 'technical')>Keluhan Teknis</option>
                    <option value="administrative" @selected(request('category') === 'administrative')>Keluhan Administratif</option>
                    <option value="lainnya" @selected(request('category') === 'lainnya')>Lainnya</option>
                </select>
            </div>
            <div class="flex items-end">
                <button class="w-full btn btn-secondary" onclick="resetFilters()">
                    <i class="fas fa-redo mr-2"></i>Reset Filter
                </button>
            </div>
        </div>
    </div>

    <!-- List of Complaints -->
    @if($complaints->count() > 0)
        <div class="space-y-4">
            @foreach($complaints as $complaint)
                <div class="bg-white rounded-2xl shadow-soft border border-gray-100 p-6 hover:shadow-card-hover transition-all duration-300">
                    <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                        <!-- Left Side: Main Info -->
                        <div class="flex-1">
                            <div class="flex items-start gap-4">
                                <!-- Icon by Category -->
                                <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0
                                    @if($complaint->category === 'cuti') bg-blue-100 text-blue-600
                                    @elseif($complaint->category === 'sakit') bg-red-100 text-red-600
                                    @elseif($complaint->category === 'izin') bg-yellow-100 text-yellow-600
                                    @else bg-gray-100 text-gray-600
                                    @endif">
                                    @if($complaint->category === 'cuti')
                                        <i class="fas fa-calendar text-lg"></i>
                                    @elseif($complaint->category === 'sakit')
                                        <i class="fas fa-heartbeat text-lg"></i>
                                    @elseif($complaint->category === 'izin')
                                        <i class="fas fa-check-circle text-lg"></i>
                                    @else
                                        <i class="fas fa-comment text-lg"></i>
                                    @endif
                                </div>

                                <!-- Content -->
                                <div>
                                    <div class="flex items-center gap-3 mb-2">
                                        <h3 class="text-lg font-bold text-gray-900">{{ $complaint->title }}</h3>
                                        <!-- Status Badge -->
                                        <span class="inline-block px-3 py-1 rounded-full text-xs font-bold
                                            @if($complaint->status === 'pending') bg-yellow-100 text-yellow-700
                                            @elseif($complaint->status === 'approved' || $complaint->status === 'resolved') bg-green-100 text-green-700
                                            @elseif($complaint->status === 'rejected') bg-red-100 text-red-700
                                            @elseif($complaint->status === 'closed') bg-gray-100 text-gray-700
                                            @else bg-blue-100 text-blue-700
                                            @endif">
                                            @if($complaint->status === 'pending') Menunggu
                                            @elseif($complaint->status === 'approved') Disetujui
                                            @elseif($complaint->status === 'rejected') Ditolak
                                            @elseif($complaint->status === 'resolved') Selesai
                                            @elseif($complaint->status === 'closed') Ditutup
                                            @else {{ ucfirst($complaint->status) }}
                                            @endif
                                        </span>
                                    </div>

                                    <p class="text-gray-600 text-sm mb-3">{{ Str::limit($complaint->description, 100) }}</p>

                                    <div class="flex flex-wrap gap-4 text-xs text-gray-500">
                                        <span><i class="fas fa-tag mr-1"></i>{{ ucfirst($complaint->category) }}</span>
                                        <span><i class="fas fa-calendar mr-1"></i>{{ $complaint->created_at->format('d M Y H:i') }}</span>
                                        @if($complaint->responded_at)
                                            <span><i class="fas fa-check mr-1"></i>Dibalas: {{ $complaint->responded_at->format('d M Y') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Side: Action Button -->
                        <div class="flex flex-col gap-2">
                            <button class="btn btn-primary text-sm" onclick="viewComplaintDetail({{ $complaint->id }})">
                                <i class="fas fa-eye mr-1"></i>Lihat Detail
                            </button>
                            @if($complaint->status === 'pending')
                                <button class="btn btn-secondary text-sm" onclick="deleteComplaint({{ $complaint->id }})">
                                    <i class="fas fa-trash mr-1"></i>Hapus
                                </button>
                            @endif
                        </div>
                    </div>

                    <!-- Response Section (if exists) -->
                    @if($complaint->response)
                        <div class="mt-4 pt-4 border-t border-gray-100 bg-gray-50 rounded-lg p-4">
                            <p class="text-sm font-semibold text-gray-700 mb-2">Balasan Admin:</p>
                            <p class="text-sm text-gray-600">{{ $complaint->response }}</p>
                            @if($complaint->responder)
                                <p class="text-xs text-gray-500 mt-2">Oleh: {{ $complaint->responder->name }}</p>
                            @endif
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8 flex justify-center">
            {{ $complaints->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div class="bg-white rounded-2xl shadow-soft border border-gray-100 p-12 text-center">
            <div class="mb-4">
                <i class="fas fa-inbox text-6xl text-gray-300"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-900 mb-2">Belum Ada Pengajuan</h3>
            <p class="text-gray-600 mb-6">Anda belum membuat pengajuan apapun. Silakan buat pengajuan baru.</p>
            <a href="{{ route('complaints.form') }}" class="btn btn-primary">
                <i class="fas fa-plus mr-2"></i>Buat Pengajuan Baru
            </a>
        </div>
    @endif
</div>

<script>
function goBack() {
    window.history.back();
}

function filterByStatus(status) {
    const url = new URL(window.location);
    if (status) {
        url.searchParams.set('status', status);
    } else {
        url.searchParams.delete('status');
    }
    window.location.href = url.toString();
}

function filterByCategory(category) {
    const url = new URL(window.location);
    if (category) {
        url.searchParams.set('category', category);
    } else {
        url.searchParams.delete('category');
    }
    window.location.href = url.toString();
}

function resetFilters() {
    window.location.href = "{{ route('complaints.history') }}";
}

function viewComplaintDetail(complaintId) {
    // TODO: Implement detail modal or page
    alert('Fitur detail pengajuan akan segera tersedia');
}

function deleteComplaint(complaintId) {
    if (!confirm('Apakah Anda yakin ingin menghapus pengajuan ini?')) {
        return;
    }

    fetch(`/complaints/${complaintId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json',
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Pengajuan berhasil dihapus');
            location.reload();
        } else {
            alert('Gagal menghapus pengajuan: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat menghapus pengajuan');
    });
}
</script>
@endsection
