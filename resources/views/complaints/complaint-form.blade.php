@extends('layouts.app')

@section('title', 'Buat Pengajuan - PT DUTA COMPUTER')

@section('content')
<div class="px-4 py-8 lg:px-8 max-w-2xl mx-auto">
    <!-- Page Header -->
    <div class="flex items-center gap-4 mb-8">
        <button class="btn btn-secondary !p-2 shadow-sm" onclick="goBack()">
            <i class="fas fa-chevron-left"></i>
        </button>
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Buat Pengajuan Baru</h1>
            <p class="text-sm text-gray-600">Ajukan cuti, sakit, izin, atau keluhan</p>
        </div>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-2xl shadow-soft border border-gray-100 p-8">
        <form action="{{ route('complaints.store') }}" method="POST" enctype="multipart/form-data" id="complaintForm">
            @csrf

            <!-- Category Selection -->
            <div class="mb-6">
                <label class="block text-sm font-bold text-gray-900 mb-3">Jenis Pengajuan *</label>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                    @foreach(['cuti' => 'Cuti', 'sakit' => 'Sakit', 'izin' => 'Izin', 'technical' => 'Keluhan Teknis', 'administrative' => 'Keluhan Admin', 'lainnya' => 'Lainnya'] as $value => $label)
                        <label class="relative flex items-center p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-primary-400 transition-colors has-[:checked]:border-primary-500 has-[:checked]:bg-primary-50">
                            <input type="radio" name="category" value="{{ $value }}" class="hidden" onchange="updateCategoryLabel()">
                            <div class="flex items-center gap-3 w-full">
                                <div class="w-5 h-5 rounded-full border-2 border-gray-300 flex items-center justify-center">
                                    <div class="w-2.5 h-2.5 rounded-full bg-primary-500 opacity-0 transition-opacity"></div>
                                </div>
                                <span class="text-sm font-semibold text-gray-700">{{ $label }}</span>
                            </div>
                        </label>
                    @endforeach
                </div>
                @error('category')
                    <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <!-- Date Range (for leave requests) -->
            <div id="dateRangeSection" class="mb-6 hidden">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-900 mb-2">Tanggal Mulai</label>
                        <input type="date" name="start_date" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500" value="{{ old('start_date') }}">
                        @error('start_date')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-900 mb-2">Tanggal Selesai</label>
                        <input type="date" name="end_date" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500" value="{{ old('end_date') }}">
                        @error('end_date')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Title -->
            <div class="mb-6">
                <label class="block text-sm font-bold text-gray-900 mb-2">Judul Pengajuan *</label>
                <input type="text" name="title" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500" placeholder="Contoh: Cuti Tahunan, Sakit Demam, dll" value="{{ old('title') }}" required>
                @error('title')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div class="mb-6">
                <label class="block text-sm font-bold text-gray-900 mb-2">Deskripsi *</label>
                <textarea name="description" rows="5" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500" placeholder="Jelaskan detail pengajuan Anda..." required>{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Priority -->
            <div class="mb-6">
                <label class="block text-sm font-bold text-gray-900 mb-2">Prioritas</label>
                <select name="priority" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500">
                    <option value="low" @selected(old('priority') === 'low')>Rendah</option>
                    <option value="medium" @selected(old('priority') === 'medium' || old('priority') === null)>Sedang</option>
                    <option value="high" @selected(old('priority') === 'high')>Tinggi</option>
                    <option value="urgent" @selected(old('priority') === 'urgent')>Mendesak</option>
                </select>
                @error('priority')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Attachment -->
            <div class="mb-6">
                <label class="block text-sm font-bold text-gray-900 mb-2">Lampiran (Opsional)</label>
                <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center cursor-pointer hover:border-primary-400 transition-colors" id="dropZone">
                    <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-3"></i>
                    <p class="text-gray-600 font-semibold mb-1">Drag dan drop file atau klik untuk memilih</p>
                    <p class="text-gray-500 text-sm">Format: PDF, DOC, DOCX, JPG, PNG (Max: 5MB)</p>
                    <input type="file" name="attachment" class="hidden" id="attachmentInput" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                </div>
                <div id="filePreview" class="mt-4 hidden">
                    <p class="text-sm font-semibold text-gray-700 mb-2">File Terpilih:</p>
                    <div class="flex items-center justify-between bg-gray-50 p-3 rounded-lg">
                        <span id="fileName" class="text-sm text-gray-600"></span>
                        <button type="button" class="text-red-600 hover:text-red-700" onclick="clearFile()">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
                @error('attachment')
                    <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <!-- Admin Notes (if applicable) -->
            <div class="mb-8">
                <label class="block text-sm font-bold text-gray-900 mb-2">Catatan Tambahan (Opsional)</label>
                <textarea name="admin_notes" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500" placeholder="Informasi tambahan untuk admin...">{{ old('admin_notes') }}</textarea>
                @error('admin_notes')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-3">
                <button type="submit" class="flex-1 btn btn-primary">
                    <i class="fas fa-paper-plane mr-2"></i>Kirim Pengajuan
                </button>
                <button type="reset" class="flex-1 btn btn-secondary">
                    <i class="fas fa-redo mr-2"></i>Reset Form
                </button>
            </div>

            <!-- Info Box -->
            <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                <p class="text-sm text-blue-800">
                    <i class="fas fa-info-circle mr-2"></i>
                    <strong>Catatan:</strong> Pengajuan Anda akan dikirimkan ke admin untuk disetujui. Anda dapat melacak status pengajuan di halaman riwayat pengajuan.
                </p>
            </div>
        </form>
    </div>
</div>

<script>
function goBack() {
    window.history.back();
}

function updateCategoryLabel() {
    const selected = document.querySelector('input[name="category"]:checked');
    const dateSection = document.getElementById('dateRangeSection');
    
    if (selected && ['cuti', 'sakit', 'izin'].includes(selected.value)) {
        dateSection.classList.remove('hidden');
    } else {
        dateSection.classList.add('hidden');
    }
}

// Drag and drop file handling
const dropZone = document.getElementById('dropZone');
const attachmentInput = document.getElementById('attachmentInput');
const filePreview = document.getElementById('filePreview');

if (dropZone && attachmentInput) {
    dropZone.addEventListener('click', () => attachmentInput.click());
    
    dropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropZone.classList.add('border-primary-400', 'bg-primary-50');
    });
    
    dropZone.addEventListener('dragleave', () => {
        dropZone.classList.remove('border-primary-400', 'bg-primary-50');
    });
    
    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropZone.classList.remove('border-primary-400', 'bg-primary-50');
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            attachmentInput.files = files;
            displayFile(files[0]);
        }
    });
    
    attachmentInput.addEventListener('change', (e) => {
        if (e.target.files.length > 0) {
            displayFile(e.target.files[0]);
        }
    });
}

function displayFile(file) {
    document.getElementById('fileName').textContent = file.name + ' (' + (file.size / 1024).toFixed(2) + ' KB)';
    filePreview.classList.remove('hidden');
}

function clearFile() {
    attachmentInput.value = '';
    filePreview.classList.add('hidden');
}

// Initialize date range visibility on page load
document.addEventListener('DOMContentLoaded', updateCategoryLabel);
</script>
@endsection
