@extends('layouts.app')

@section('title', 'Pengajuan Izin - Sistem Absensi')

@php
    $hideHeader = true;
@endphp

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/izin.css') }}">
@endpush

@section('content')
    <div class="header">
        <div class="header-content">
            <a href="{{ route('dashboard') }}" class="back-btn">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div class="header-title">
                <h1>Pengajuan Izin</h1>
                <p>Ajukan cuti dan izin Anda dengan mudah</p>
            </div>
        </div>
    </div>

    <div class="px-4 py-8 lg:px-8 max-w-5xl mx-auto">
        <!-- Leave Balance -->
        <div class="balance-card">
            <div class="balance-header">
                <h3 class="balance-title">Sisa Jatah Cuti Tahun {{ now()->year }}</h3>
            </div>
            <div class="balance-grid">
                <div class="balance-item">
                    <div class="balance-number">{{ $leaveBalance['annual'] }}</div>
                    <div class="balance-label">Tahunan</div>
                </div>
                <div class="balance-item">
                    <div class="balance-number">{{ $leaveBalance['sick'] }}</div>
                    <div class="balance-label">Sakit</div>
                </div>
                <div class="balance-item">
                    <div class="balance-number">{{ $leaveBalance['special'] }}</div>
                    <div class="balance-label">Khusus</div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Form Section -->
            <div class="lg:col-span-2">
                <div class="form-section">
                    <h3 class="text-lg font-bold text-main mb-6">Ajukan Izin Baru</h3>
                    
                    <form id="leaveForm" action="{{ route('complaints.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label class="form-label">Pilih Jenis Izin</label>
                            <div class="leave-types">
                                <div class="leave-type" data-type="cuti">
                                    <div class="leave-type-icon">🏖️</div>
                                    <div class="leave-type-name">Cuti Tahunan</div>
                                </div>
                                <div class="leave-type" data-type="sakit">
                                    <div class="leave-type-icon">🏥</div>
                                    <div class="leave-type-name">Izin Sakit</div>
                                </div>
                                <div class="leave-type emergency" data-type="mendadak">
                                    <div class="leave-type-icon">⚡</div>
                                    <div class="leave-type-name">Mendadak</div>
                                </div>
                                <div class="leave-type" data-type="keluarga">
                                    <div class="leave-type-icon">👨‍👩‍👧‍👦</div>
                                    <div class="leave-type-name">Keluarga</div>
                                </div>
                            </div>
                            <input type="hidden" name="category" id="categoryInput" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Periode Izin</label>
                            <div class="date-range">
                                <div class="flex flex-col gap-1">
                                    <span class="text-xs text-light">Mulai</span>
                                    <input type="date" class="form-input" name="start_date" id="startDate" required>
                                </div>
                                <div class="flex flex-col gap-1">
                                    <span class="text-xs text-light">Selesai</span>
                                    <input type="date" class="form-input" name="end_date" id="endDate" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Judul Pengajuan</label>
                            <input type="text" class="form-input" name="title" id="title" placeholder="Contoh: Cuti Liburan Keluarga" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Alasan & Deskripsi</label>
                            <textarea class="form-textarea" name="description" id="reason" placeholder="Jelaskan detail pengajuan Anda..." required></textarea>
                        </div>

                        <!-- Upload MC & Bukti -->
                        <div class="form-group" id="uploadSection" style="display: none;">
                            <label class="form-label" id="uploadLabel">Lampiran Pendukung</label>
                            <div id="uploadInfo" class="mb-3"></div>
                            <div class="upload-area" onclick="document.getElementById('attachment').click();">
                                <input type="file" name="attachment" id="attachment" accept="image/*,.pdf,.doc,.docx" style="display: none;">
                                <div id="uploadPlaceholder">
                                    <div class="upload-icon" id="uploadIcon">📎</div>
                                    <div class="text-sm text-muted">
                                        <strong class="text-primary">Klik untuk upload</strong><br>
                                        <span class="text-xs">File PDF, JPG, PNG (Max 5MB)</span>
                                    </div>
                                </div>
                                <div id="filePreview" class="hidden"></div>
                            </div>
                        </div>

                        <div class="form-group" id="adminNotesSection" style="display: none;">
                            <label class="form-label">Catatan Tambahan</label>
                            <textarea class="form-textarea" name="admin_notes" id="adminNotes" placeholder="Catatan tambahan jika ada..." style="min-height: 80px;"></textarea>
                        </div>

                        <input type="hidden" name="priority" value="medium" id="priorityInput">

                        <button type="submit" class="btn btn-primary w-full py-4 text-lg shadow-lg mt-6" id="submitBtn">
                            Kirim Pengajuan
                        </button>
                    </form>
                </div>
            </div>

            <!-- History Section -->
            <div class="lg:col-span-1">
                <div class="recent-section">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-bold text-main">Riwayat</h3>
                        <button onclick="location.reload()" class="btn btn-secondary !p-2 !text-xs">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                    </div>
                    
                    @if(isset($complaints) && $complaints->count() > 0)
                        <div class="flex flex-col gap-4">
                            @foreach($complaints as $complaint)
                                <div class="card p-4 hover:border-primary-color transition-colors">
                                    <div class="flex justify-between items-start mb-2">
                                        <span class="badge badge-info">{{ ucfirst($complaint->category) }}</span>
                                        @php
                                            $statusClass = 'status-pending';
                                            $statusText = 'Pending';
                                            if($complaint->status == 'approved') { $statusClass = 'status-approved'; $statusText = 'Disetujui'; }
                                            elseif($complaint->status == 'rejected') { $statusClass = 'status-rejected'; $statusText = 'Ditolak'; }
                                        @endphp
                                        <span class="request-status {{ $statusClass }}">{{ $statusText }}</span>
                                    </div>
                                    <h4 class="font-bold text-sm mb-1">{{ $complaint->title }}</h4>
                                    <p class="text-xs text-muted mb-2 line-clamp-2">{{ $complaint->description }}</p>
                                    <div class="flex items-center gap-2 text-[10px] text-light">
                                        <i class="far fa-calendar-alt"></i>
                                        <span>{{ $complaint->start_date }} - {{ $complaint->end_date }}</span>
                                    </div>
                                    
                                    @if($complaint->response)
                                        <div class="mt-3 p-3 rounded-lg text-xs {{ $complaint->status == 'rejected' ? 'bg-danger-light text-danger' : 'bg-success-light text-success' }} border border-current opacity-80">
                                            <strong>Respon Admin:</strong>
                                            <p class="mt-1">{{ $complaint->response }}</p>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="empty-state">
                            <i class="fas fa-file-invoice empty-state-icon"></i>
                            <p class="empty-state-text">Belum ada riwayat pengajuan</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const leaveTypes = document.querySelectorAll('.leave-type');
            const categoryInput = document.getElementById('categoryInput');
            const uploadSection = document.getElementById('uploadSection');
            const adminNotesSection = document.getElementById('adminNotesSection');
            const priorityInput = document.getElementById('priorityInput');
            const submitBtn = document.getElementById('submitBtn');
            
            leaveTypes.forEach(type => {
                type.addEventListener('click', function() {
                    leaveTypes.forEach(t => t.classList.remove('selected'));
                    this.classList.add('selected');
                    const leaveType = this.dataset.type;
                    categoryInput.value = leaveType;
                    
                    // Reset UI
                    uploadSection.style.display = 'none';
                    adminNotesSection.style.display = 'none';
                    priorityInput.value = 'medium';
                    submitBtn.textContent = 'Kirim Pengajuan';
                    
                    if (leaveType === 'sakit') {
                        uploadSection.style.display = 'block';
                        adminNotesSection.style.display = 'block';
                        document.getElementById('uploadLabel').textContent = '📄 Surat MC / Dokter (Wajib)';
                    } else if (leaveType === 'mendadak') {
                        uploadSection.style.display = 'block';
                        priorityInput.value = 'high';
                        submitBtn.textContent = '⚡ Kirim Pengajuan Darurat';
                    }
                });
            });

            // File handling
            const attachment = document.getElementById('attachment');
            const placeholder = document.getElementById('uploadPlaceholder');
            const preview = document.getElementById('filePreview');

            attachment.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    placeholder.classList.add('hidden');
                    preview.classList.remove('hidden');
                    preview.innerHTML = `
                        <div class="flex items-center justify-between p-2 bg-primary-light rounded border border-primary-color">
                            <span class="text-xs font-medium truncate max-w-[150px]">${file.name}</span>
                            <button type="button" class="text-danger p-1" onclick="resetFile()">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    `;
                }
            });

            window.resetFile = function() {
                attachment.value = '';
                preview.classList.add('hidden');
                placeholder.classList.remove('hidden');
            };
        });
    </script>
@endpush
