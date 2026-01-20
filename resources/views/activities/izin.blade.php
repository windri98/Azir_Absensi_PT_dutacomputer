@extends('layouts.app')

@section('title', 'Ajukan Izin / Cuti - PT DUTA COMPUTER')

@section('content')
<div class="px-4 py-8 lg:px-8 max-w-4xl mx-auto">
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('dashboard') }}" class="btn btn-secondary !p-2 shadow-sm">
            <i class="fas fa-chevron-left"></i>
        </a>
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Pengajuan Izin & Cuti</h1>
            <p class="text-sm text-gray-600">Ajukan izin, cuti, atau sakit dengan mudah</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        @if (session('success') || session('error'))
            <div class="lg:col-span-3">
                @if (session('success'))
                    <div class="mb-4 rounded-xl border border-success-200 bg-success-50 px-4 py-3 text-sm text-success-800">
                        <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="mb-4 rounded-xl border border-danger-200 bg-danger-50 px-4 py-3 text-sm text-danger-800">
                        <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
                    </div>
                @endif
            </div>
        @endif
        <!-- MAIN FORM -->
        <div class="lg:col-span-2">
            <!-- Tab Navigation -->
            <div class="bg-white rounded-t-2xl border border-b-0 border-gray-100 p-4 shadow-soft">
                <div class="flex gap-2 flex-wrap">
                    <button class="leave-tab-btn px-6 py-2 rounded-lg font-semibold text-sm transition-all active" data-type="izin">
                        <i class="fas fa-file-signature mr-2"></i> Izin Kerja
                    </button>
                    <button class="leave-tab-btn px-6 py-2 rounded-lg font-semibold text-sm transition-all" data-type="sakit">
                        <i class="fas fa-heartbeat mr-2"></i> Sakit
                    </button>
                    <button class="leave-tab-btn px-6 py-2 rounded-lg font-semibold text-sm transition-all" data-type="cuti">
                        <i class="fas fa-calendar-alt mr-2"></i> Cuti
                    </button>
                </div>
            </div>

            <!-- Form Content -->
            <form id="leaveForm" action="{{ route('complaints.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-b-2xl shadow-soft border border-gray-100 overflow-hidden">
                @csrf
                <div class="p-8 space-y-6">
                    <!-- Type Selection (hidden, controlled by tabs) -->
                    <input type="hidden" id="leaveType" name="category" value="izin">
                    <input type="hidden" id="leaveTitle" name="title" value="">

                    <!-- Type-Specific Instructions -->
                    <div id="typeGuide" class="p-4 bg-blue-50 rounded-xl border border-blue-200">
                        <div class="flex gap-3">
                            <div class="text-2xl text-blue-600"><i class="fas fa-info-circle"></i></div>
                            <div>
                                <p class="font-bold text-blue-900 mb-1">Panduan Pengajuan Izin Kerja</p>
                                <p class="text-sm text-blue-800">Izin kerja digunakan ketika Anda perlu tidak masuk dan tetap memberi alasan jelas. Lampirkan bukti/izin tertulis jika ada.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Date Selection -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            <i class="fas fa-calendar text-primary-600 mr-2"></i>Tanggal Pengajuan
                        </label>
                        <input type="date" id="leaveDate" name="start_date" required 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-primary-600 focus:ring-2 focus:ring-primary-100 transition-all"
                            min="{{ now()->format('Y-m-d') }}">
                        <p class="text-xs text-gray-600 mt-2"><i class="fas fa-lightbulb mr-1"></i>Anda dapat mengajukan untuk tanggal hari ini atau masa depan</p>
                    </div>

                    <!-- Date Range (for multiple days) -->
                    <div id="dateRangeContainer" class="hidden">
                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            <i class="fas fa-calendar-range text-primary-600 mr-2"></i>Tanggal Berakhir (Opsional)
                        </label>
                        <input type="date" id="leaveDateEnd" name="end_date"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-primary-600 focus:ring-2 focus:ring-primary-100 transition-all">
                        <p class="text-xs text-gray-600 mt-2">Kosongkan jika hanya 1 hari</p>
                    </div>

                    <!-- Reason/Description -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            <i class="fas fa-edit text-primary-600 mr-2"></i>Alasan / Keterangan (wajib jelas)
                        </label>
                        <textarea id="leaveReason" name="description" rows="4" required
                            placeholder="Contoh: Izin sakit demam tinggi, istirahat 2 hari. Sertakan keterangan singkat dan jelas."
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-primary-600 focus:ring-2 focus:ring-primary-100 transition-all resize-none"></textarea>
                        <p class="text-xs text-gray-600 mt-2">Minimum 10 karakter untuk alasan yang jelas</p>
                    </div>

                    <!-- Document Upload (required for izin/sakit) -->
                    <div id="documentUploadContainer" class="hidden">
                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            <i class="fas fa-paperclip text-primary-600 mr-2"></i>Bukti Pendukung (Wajib untuk Izin/Sakit)
                        </label>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 hover:border-primary-600 hover:bg-primary-50 transition-all cursor-pointer" onclick="document.getElementById('leaveDocument').click()">
                            <div class="text-center">
                                <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-2"></i>
                                <p class="font-semibold text-gray-700 mb-1">Klik untuk upload dokumen</p>
                                <p class="text-xs text-gray-600">atau drag and drop</p>
                                <p class="text-xs text-gray-500 mt-2">PDF, JPG, PNG (Max 5MB)</p>
                            </div>
                        </div>
                        <input type="file" id="leaveDocument" name="attachment" accept=".pdf,.jpg,.jpeg,.png" class="hidden">
                        <div id="documentPreview" class="mt-3"></div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="w-full bg-gradient-primary text-white px-6 py-4 rounded-xl font-bold text-lg hover:shadow-lg hover:scale-105 transition-all duration-300 active:scale-95 flex items-center justify-center gap-2">
                        <i class="fas fa-paper-plane"></i>
                        Kirim Pengajuan
                    </button>
                </div>
            </form>
        </div>

        <!-- SIDEBAR: LEAVE BALANCE & HISTORY -->
        <div class="space-y-6">
            <!-- Leave Balance -->
            <div class="bg-white rounded-2xl shadow-soft border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-primary-50 to-white">
                    <h3 class="font-bold text-gray-900 flex items-center gap-2">
                        <i class="fas fa-chart-pie text-primary-600"></i>
                        Saldo Cuti Anda
                    </h3>
                </div>
                <div class="p-6 space-y-4">
                    <!-- Annual Leave -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <p class="text-sm font-bold text-gray-700">Cuti Tahunan</p>
                            <p class="text-lg font-bold text-primary-900">{{ $leaveBalance['annual'] ?? 12 }} hari</p>
                        </div>
                        <div class="w-full h-2 bg-gray-200 rounded-full overflow-hidden">
                            <div class="h-full bg-gradient-primary rounded-full" style="width: {{ (($leaveBalance['annual'] ?? 12) / 12) * 100 }}%"></div>
                        </div>
                    </div>

                    <!-- Sick Leave -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <p class="text-sm font-bold text-gray-700">Cuti Sakit</p>
                            <p class="text-lg font-bold text-warning-900">{{ $leaveBalance['sick'] ?? 12 }} hari</p>
                        </div>
                        <div class="w-full h-2 bg-gray-200 rounded-full overflow-hidden">
                            <div class="h-full bg-gradient-warning rounded-full" style="width: {{ (($leaveBalance['sick'] ?? 12) / 12) * 100 }}%"></div>
                        </div>
                    </div>

                    <!-- Special Leave -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <p class="text-sm font-bold text-gray-700">Cuti Khusus</p>
                            <p class="text-lg font-bold text-success-900">{{ $leaveBalance['special'] ?? 3 }} hari</p>
                        </div>
                        <div class="w-full h-2 bg-gray-200 rounded-full overflow-hidden">
                            <div class="h-full bg-gradient-success rounded-full" style="width: {{ (($leaveBalance['special'] ?? 3) / 3) * 100 }}%"></div>
                        </div>
                    </div>

                    <div class="p-3 bg-blue-50 rounded-lg border border-blue-200 text-xs text-blue-800">
                        <i class="fas fa-info-circle mr-2"></i>
                        Saldo akan diperbarui setelah persetujuan admin
                    </div>
                </div>
            </div>

            <!-- Recent Requests -->
            <div class="bg-white rounded-2xl shadow-soft border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                    <h3 class="font-bold text-gray-900 flex items-center gap-2">
                        <i class="fas fa-history text-primary-600"></i>
                        Riwayat Pengajuan
                    </h3>
                </div>
                <div class="p-6 space-y-3 max-h-96 overflow-y-auto">
                    @forelse($complaints ?? [] as $complaint)
                        <div class="p-3 bg-gray-50 rounded-lg border border-gray-200 hover:bg-primary-50 hover:border-primary-200 transition-colors">
                            <div class="flex items-start justify-between mb-2">
                                <div>
                                    <p class="font-semibold text-sm text-gray-900">{{ ucfirst(str_replace('_', ' ', $complaint->category)) }}</p>
                                    <p class="text-xs text-gray-600 mt-1">{{ $complaint->created_at->format('d M Y') }}</p>
                                </div>
                                <span class="text-xs font-bold px-2 py-1 rounded
                                    @if($complaint->status === 'pending') bg-warning-100 text-warning-700
                                    @elseif($complaint->status === 'approved') bg-success-100 text-success-700
                                    @elseif($complaint->status === 'rejected') bg-danger-100 text-danger-700
                                    @else bg-gray-100 text-gray-700
                                    @endif
                                ">
                                    @if($complaint->status === 'pending') Pending
                                    @elseif($complaint->status === 'approved') Approved
                                    @elseif($complaint->status === 'rejected') Rejected
                                    @else {{ ucfirst($complaint->status) }}
                                    @endif
                                </span>
                            </div>
                            <p class="text-xs text-gray-600 line-clamp-2">{{ $complaint->title }}</p>
                        </div>
                    @empty
                        <div class="text-center py-6">
                            <i class="fas fa-inbox text-3xl text-gray-300 mb-2"></i>
                            <p class="text-sm text-gray-600">Belum ada riwayat pengajuan</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .leave-tab-btn {
        background: transparent;
        color: #6B7280;
        border: 2px solid transparent;
    }

    .leave-tab-btn.active {
        background: linear-gradient(135deg, #3B82F6, #1E40AF);
        color: white;
    }

    .leave-tab-btn:not(.active):hover {
        background: #F3F4F6;
    }
</style>
@endpush

@push('scripts')
<script>
    // Tab switching
    document.querySelectorAll('.leave-tab-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const type = this.dataset.type;
            
            // Update active tab
            document.querySelectorAll('.leave-tab-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            
            // Update form
            document.getElementById('leaveType').value = type;
            
            // Update guide text
            const guides = {
                'izin': 'Izin kerja digunakan ketika Anda perlu tidak masuk dan tetap memberi alasan jelas. Lampirkan bukti/izin tertulis jika ada.',
                'sakit': 'Cuti sakit digunakan untuk istirahat karena sakit. Lampirkan bukti medis/surat dokter.',
                'cuti': 'Cuti tahunan adalah hak Anda untuk istirahat. Ajukan dengan pemberitahuan cukup untuk persiapan tim.'
            };
            
            document.querySelector('#typeGuide p:last-child').textContent = guides[type];
            
            // Show/hide document upload container
            if (type === 'izin' || type === 'sakit') {
                document.getElementById('documentUploadContainer').classList.remove('hidden');
                document.getElementById('dateRangeContainer').classList.add('hidden');
            } else {
                document.getElementById('documentUploadContainer').classList.add('hidden');
                document.getElementById('dateRangeContainer').classList.remove('hidden');
            }
        });
    });

    // Set minimum date to today
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('leaveDate').min = today;
    document.getElementById('leaveDateEnd').min = today;

    // Document upload preview
    document.getElementById('leaveDocument').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const preview = document.getElementById('documentPreview');
            preview.innerHTML = `
                <div class="p-3 bg-success-50 rounded-lg border border-success-200 flex items-center gap-3">
                    <i class="fas fa-check-circle text-success-600"></i>
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-success-900">${file.name}</p>
                        <p class="text-xs text-success-700">${(file.size / 1024 / 1024).toFixed(2)} MB</p>
                    </div>
                </div>
            `;
        }
    });

    // Form submission
    document.getElementById('leaveForm').addEventListener('submit', function(e) {
        const reason = document.getElementById('leaveReason').value.trim();
        if (reason.length < 10) {
            e.preventDefault();
            alert('Alasan minimal harus 10 karakter');
            return;
        }

        const type = document.getElementById('leaveType').value;
        const startDate = document.getElementById('leaveDate').value;
        const endDate = document.getElementById('leaveDateEnd').value;
        const titleTarget = document.getElementById('leaveTitle');

        const typeLabel = type.charAt(0).toUpperCase() + type.slice(1);
        const dateLabel = endDate ? `${startDate} s/d ${endDate}` : startDate;
        titleTarget.value = `${typeLabel} - ${dateLabel}`;
    });
</script>
@endpush
