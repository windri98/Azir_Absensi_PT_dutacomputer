
// QR Scanner Functions
let qrStream = null;
let qrVideoElement = null;
let qrCanvasElement = null;
let qrScanning = false;

function startQRScanner() {
    const container = document.getElementById('qrScannerContainer');
    qrVideoElement = document.getElementById('qrVideo');
    qrCanvasElement = document.getElementById('qrCanvas');
    const resultDiv = document.getElementById('qrResult');
    
    container.style.display = 'block';
    resultDiv.style.display = 'none';
    qrScanning = true;

    // Request camera access
    navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } })
        .then(function(stream) {
            qrStream = stream;
            qrVideoElement.srcObject = stream;
            qrVideoElement.setAttribute('playsinline', true);
            qrVideoElement.play();
            requestAnimationFrame(scanQRCode);
        })
        .catch(function(err) {
            console.error('Error accessing camera:', err);
            alert('Tidak dapat mengakses kamera. Pastikan izin kamera sudah diberikan.');
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
