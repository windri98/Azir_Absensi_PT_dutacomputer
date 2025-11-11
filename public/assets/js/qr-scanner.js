
// QR Scanner Functions
let qrStream = null;
let qrVideoElement = null;
let qrCanvasElement = null;
let qrScanning = false;

// Check if browser supports camera access
function isCameraSupported() {
    return !!(navigator.mediaDevices && navigator.mediaDevices.getUserMedia);
}

function startQRScanner() {
    // Check browser support
    if (!isCameraSupported()) {
        alert('Browser Anda tidak mendukung akses kamera.\n\nSolusi:\n1. Gunakan Chrome/Firefox/Edge terbaru\n2. Pastikan menggunakan HTTPS (bukan HTTP)\n3. Berikan izin kamera di pengaturan browser');
        return;
    }

    const container = document.getElementById('qrScannerContainer');
    qrVideoElement = document.getElementById('qrVideo');
    qrCanvasElement = document.getElementById('qrCanvas');
    const resultDiv = document.getElementById('qrResult');
    
    if (!container || !qrVideoElement || !qrCanvasElement) {
        alert('QR Scanner belum siap. Refresh halaman dan coba lagi.');
        return;
    }
    
    container.style.display = 'block';
    resultDiv.style.display = 'none';
    qrScanning = true;

    // Camera constraints with fallbacks
    const constraints = {
        video: {
            facingMode: { ideal: 'environment' }, // Try back camera first
            width: { ideal: 1280 },
            height: { ideal: 720 }
        }
    };

    // Request camera access with better error handling
    navigator.mediaDevices.getUserMedia(constraints)
        .then(function(stream) {
            qrStream = stream;
            qrVideoElement.srcObject = stream;
            qrVideoElement.setAttribute('playsinline', true);
            qrVideoElement.setAttribute('autoplay', true);
            qrVideoElement.setAttribute('muted', true);
            
            qrVideoElement.onloadedmetadata = function() {
                qrVideoElement.play()
                    .then(() => {
                        console.log('Camera started successfully');
                        requestAnimationFrame(scanQRCode);
                    })
                    .catch(err => {
                        console.error('Error playing video:', err);
                        alert('Gagal memulai kamera. Coba refresh halaman.');
                        stopQRScanner();
                    });
            };
        })
        .catch(function(err) {
            console.error('Camera access error:', err);
            
            let errorMessage = 'Tidak dapat mengakses kamera.\n\n';
            
            if (err.name === 'NotAllowedError' || err.name === 'PermissionDeniedError') {
                errorMessage += 'Izin kamera ditolak. Silakan:\n';
                errorMessage += '1. Klik ikon kamera di address bar\n';
                errorMessage += '2. Izinkan akses kamera\n';
                errorMessage += '3. Refresh halaman ini';
            } else if (err.name === 'NotFoundError' || err.name === 'DevicesNotFoundError') {
                errorMessage += 'Kamera tidak ditemukan.\n';
                errorMessage += 'Pastikan device memiliki kamera.';
            } else if (err.name === 'NotReadableError' || err.name === 'TrackStartError') {
                errorMessage += 'Kamera sedang digunakan aplikasi lain.\n';
                errorMessage += 'Tutup aplikasi lain yang menggunakan kamera.';
            } else if (err.name === 'OverconstrainedError') {
                errorMessage += 'Kamera tidak mendukung resolusi yang diminta.\n';
                errorMessage += 'Coba dengan device lain.';
            } else if (err.name === 'NotSupportedError') {
                errorMessage += 'Browser tidak mendukung akses kamera.\n';
                errorMessage += 'Gunakan Chrome, Firefox, atau Edge terbaru.';
            } else if (err.name === 'TypeError') {
                errorMessage += 'Koneksi tidak aman (HTTP).\n';
                errorMessage += 'Kamera hanya berfungsi di HTTPS atau localhost.';
            } else {
                errorMessage += 'Error: ' + err.message;
            }
            
            alert(errorMessage);
            stopQRScanner();
        });
}

function scanQRCode() {
    if (!qrScanning || !qrVideoElement) return;

    const canvas = qrCanvasElement;
    const video = qrVideoElement;

    if (video.readyState === video.HAVE_ENOUGH_DATA) {
        canvas.height = video.videoHeight;
        canvas.width = video.videoWidth;
        const context = canvas.getContext('2d');
        context.drawImage(video, 0, 0, canvas.width, canvas.height);
        
        const imageData = context.getImageData(0, 0, canvas.width, canvas.height);
        if (typeof jsQR !== 'undefined') {
            const code = jsQR(imageData.data, imageData.width, imageData.height);

            if (code) {
                handleQRCodeScanned(code.data);
                return;
            }
        }
    }

    requestAnimationFrame(scanQRCode);
}

function handleQRCodeScanned(data) {
    qrScanning = false;
    
    try {
        const qrDataParsed = JSON.parse(data);
        window.qrData = qrDataParsed;

        const resultDiv = document.getElementById('qrResult');
        const scannedInfo = document.getElementById('scannedInfo');
        
        scannedInfo.innerHTML = `
            <strong>Employee ID:</strong> ${qrDataParsed.employee_id}<br>
            <strong>Name:</strong> ${qrDataParsed.name || 'N/A'}<br>
            <strong>Type:</strong> ${qrDataParsed.type}
        `;
        
        resultDiv.style.display = 'block';
        
        if (typeof showSuccessPopup === 'function') {
            showSuccessPopup({
                title: 'QR Code Berhasil Discan!',
                message: `Employee: ${qrDataParsed.name || qrDataParsed.employee_id}`,
                buttonText: 'OK'
            });
        } else {
            alert(`QR Code Berhasil Discan!\nEmployee: ${qrDataParsed.name || qrDataParsed.employee_id}`);
        }

        stopQRScanner();
    } catch (e) {
        console.error('Invalid QR code data:', e);
        if (typeof showErrorPopup === 'function') {
            showErrorPopup({
                title: 'QR Code Tidak Valid',
                message: 'Format QR code tidak sesuai. Silakan scan ulang.',
                buttonText: 'OK'
            });
        } else {
            alert('QR Code Tidak Valid\nFormat QR code tidak sesuai. Silakan scan ulang.');
        }
    }
}

function stopQRScanner() {
    qrScanning = false;
    
    if (qrStream) {
        qrStream.getTracks().forEach(track => track.stop());
        qrStream = null;
    }
    
    if (qrVideoElement) {
        qrVideoElement.srcObject = null;
    }
    
    const container = document.getElementById('qrScannerContainer');
    if (container) {
        container.style.display = 'none';
    }
}

// Handle QR code from uploaded file
function handleQRFile(event) {
    const file = event.target.files[0];
    if (!file) return;

    // Check if it's an image
    if (!file.type.match('image.*')) {
        alert('Harap pilih file gambar (JPG, PNG, dll)');
        return;
    }

    const reader = new FileReader();
    
    reader.onload = function(e) {
        const img = new Image();
        img.onload = function() {
            const uploadCanvas = document.getElementById('uploadCanvas');
            const uploadPreview = document.getElementById('uploadPreview');
            const uploadResult = document.getElementById('uploadResult');
            
            if (!uploadCanvas || !uploadPreview) {
                alert('Upload preview tidak ditemukan');
                return;
            }
            
            uploadPreview.style.display = 'block';
            
            // Draw image to canvas
            uploadCanvas.width = img.width;
            uploadCanvas.height = img.height;
            const ctx = uploadCanvas.getContext('2d');
            ctx.drawImage(img, 0, 0);
            
            // Try to decode QR code
            const imageData = ctx.getImageData(0, 0, uploadCanvas.width, uploadCanvas.height);
            
            if (typeof jsQR !== 'undefined') {
                const code = jsQR(imageData.data, imageData.width, imageData.height);
                
                if (code) {
                    // QR found!
                    try {
                        const qrDataParsed = JSON.parse(code.data);
                        window.qrData = qrDataParsed;

                        const uploadScannedInfo = document.getElementById('uploadScannedInfo');
                        if (uploadScannedInfo) {
                            uploadScannedInfo.innerHTML = `
                                <strong>Employee ID:</strong> ${qrDataParsed.employee_id}<br>
                                <strong>Name:</strong> ${qrDataParsed.name || 'N/A'}<br>
                                <strong>Type:</strong> ${qrDataParsed.type}
                            `;
                        }
                        
                        if (uploadResult) uploadResult.style.display = 'block';
                        
                        if (typeof showSuccessPopup === 'function') {
                            showSuccessPopup({
                                title: 'QR Code Berhasil Dibaca!',
                                message: `Employee: ${qrDataParsed.name || qrDataParsed.employee_id}`,
                                buttonText: 'OK'
                            });
                        } else {
                            alert(`QR Code Berhasil!\nEmployee: ${qrDataParsed.name || qrDataParsed.employee_id}`);
                        }
                    } catch (e) {
                        console.error('Invalid QR code data:', e);
                        alert('QR Code tidak valid. Format data tidak sesuai.');
                    }
                } else {
                    // QR not found
                    if (uploadResult) uploadResult.style.display = 'none';
                    alert('QR Code tidak ditemukan di gambar.\n\nPastikan:\n- Gambar jelas dan tidak blur\n- QR code terlihat lengkap\n- Cahaya cukup saat foto');
                }
            } else {
                alert('QR Scanner library belum ter-load. Refresh halaman dan coba lagi.');
            }
        };
        
        img.onerror = function() {
            alert('Gagal memuat gambar. Coba file lain.');
        };
        
        img.src = e.target.result;
    };
    
    reader.onerror = function() {
        alert('Gagal membaca file. Coba lagi.');
    };
    
    reader.readAsDataURL(file);
}
