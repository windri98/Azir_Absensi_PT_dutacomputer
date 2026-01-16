{{-- File Upload Component --}}
<div class="file-upload-container mb-4">
    <div class="card">
        <div class="card-header">
            <h6 class="mb-0">
                <i class="fas fa-paperclip text-primary me-2"></i>
                {{ $title ?? 'Upload Dokumen' }}
            </h6>
        </div>
        <div class="card-body">
            <div class="upload-area" id="uploadArea_{{ $fieldName }}">
                <div class="upload-zone border-2 border-dashed border-primary rounded-3 p-4 text-center">
                    <div class="upload-icon mb-3">
                        <i class="fas fa-cloud-upload-alt text-primary" style="font-size: 2rem;"></i>
                    </div>
                    <div class="upload-text">
                        <p class="mb-1"><strong>Klik untuk upload atau drag & drop</strong></p>
                        <p class="text-muted small mb-0">Maksimal 5MB - Format: JPG, PNG, PDF</p>
                    </div>
                    <input type="file" 
                           class="form-control d-none" 
                           id="{{ $fieldName }}" 
                           name="{{ $fieldName }}" 
                           accept=".jpg,.jpeg,.png,.pdf"
                           {{ $attributes ?? '' }}>
                </div>
            </div>

            <!-- Preview Area -->
            <div class="preview-area mt-3 d-none" id="previewArea_{{ $fieldName }}">
                <div class="selected-file p-3 bg-light rounded">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="file-info d-flex align-items-center">
                            <i class="fas fa-file-alt text-secondary me-2"></i>
                            <div>
                                <span class="file-name fw-bold"></span>
                                <div class="file-size text-muted small"></div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-danger remove-file">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </div>
                    <!-- Image Preview for images -->
                    <div class="image-preview mt-2 d-none">
                        <img src="" class="img-thumbnail" style="max-width: 200px; max-height: 150px;" alt="Preview">
                    </div>
                </div>
            </div>

            @error($fieldName)
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const fieldName = '{{ $fieldName }}';
    const uploadArea = document.getElementById(`uploadArea_${fieldName}`);
    const uploadZone = uploadArea.querySelector('.upload-zone');
    const fileInput = document.getElementById(fieldName);
    const previewArea = document.getElementById(`previewArea_${fieldName}`);

    // Click to upload
    uploadZone.addEventListener('click', () => fileInput.click());

    // Drag and drop functionality
    uploadZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        uploadZone.classList.add('border-success', 'bg-light');
    });

    uploadZone.addEventListener('dragleave', () => {
        uploadZone.classList.remove('border-success', 'bg-light');
    });

    uploadZone.addEventListener('drop', (e) => {
        e.preventDefault();
        uploadZone.classList.remove('border-success', 'bg-light');
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            fileInput.files = files;
            handleFileSelect(files[0]);
        }
    });

    // File input change
    fileInput.addEventListener('change', (e) => {
        if (e.target.files.length > 0) {
            handleFileSelect(e.target.files[0]);
        }
    });

    // Remove file
    previewArea.addEventListener('click', (e) => {
        if (e.target.closest('.remove-file')) {
            fileInput.value = '';
            previewArea.classList.add('d-none');
            uploadArea.classList.remove('d-none');
        }
    });

    function handleFileSelect(file) {
        // Validate file size (5MB)
        if (file.size > 5 * 1024 * 1024) {
            alert('File terlalu besar! Maksimal 5MB');
            return;
        }

        // Validate file type
        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'application/pdf'];
        if (!allowedTypes.includes(file.type)) {
            alert('Format file tidak didukung! Gunakan JPG, PNG, atau PDF');
            return;
        }

        // Show preview
        showPreview(file);
    }

    function showPreview(file) {
        const fileName = previewArea.querySelector('.file-name');
        const fileSize = previewArea.querySelector('.file-size');
        const imagePreview = previewArea.querySelector('.image-preview');
        const imgElement = imagePreview.querySelector('img');

        fileName.textContent = file.name;
        fileSize.textContent = `${(file.size / 1024 / 1024).toFixed(2)} MB`;

        // Show image preview if it's an image
        if (file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = (e) => {
                imgElement.src = e.target.result;
                imagePreview.classList.remove('d-none');
            };
            reader.readAsDataURL(file);
        } else {
            imagePreview.classList.add('d-none');
        }

        uploadArea.classList.add('d-none');
        previewArea.classList.remove('d-none');
    }
});
</script>

<style>
.upload-zone {
    cursor: pointer;
    transition: all 0.3s ease;
}

.upload-zone:hover {
    border-color: #0d6efd !important;
    background-color: #f8f9fa;
}

.file-upload-container .card {
    border: 1px solid #e3e6f0;
}

.file-upload-container .card-header {
    background-color: #f8f9fc;
    border-bottom: 1px solid #e3e6f0;
}
</style>
