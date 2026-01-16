@extends('layouts.app')

@section('title', 'Scan QR - Sistem Absensi')

@push('styles')
    <script src="https://cdn.jsdelivr.net/npm/qr-scanner@1.4.2/qr-scanner.umd.min.js"></script>
    <style>
        .scanner-container {
            position: relative;
            width: 100%;
            max-width: 500px;
            aspect-ratio: 1;
            background: #000;
            border-radius: 2rem;
            overflow: hidden;
            margin: 0 auto;
            border: 4px solid white;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }
        #qr-video { width: 100%; height: 100%; object-fit: cover; }
        .scan-overlay {
            position: absolute;
            inset: 0;
            border: 2px solid rgba(255,255,255,0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            pointer-events: none;
        }
        .scan-frame {
            width: 250px;
            height: 250px;
            border: 2px solid #2563eb;
            border-radius: 2rem;
            position: relative;
            box-shadow: 0 0 0 1000px rgba(0,0,0,0.5);
        }
        .scan-line {
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 2px;
            background: #2563eb;
            box-shadow: 0 0 15px #2563eb;
            animation: scan 2s linear infinite;
        }
        @keyframes scan {
            0% { top: 0; }
            100% { top: 100%; }
        }
    </style>
@endpush

@section('content')
    <div class="px-4 py-8 lg:px-8 max-w-4xl mx-auto text-center">
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900">Scan QR Code</h1>
            <p class="text-sm text-gray-500">Arahkan kamera Anda ke QR Code yang tersedia di lokasi.</p>
        </div>

        <div class="scanner-container mb-8">
            <video id="qr-video" playsinline></video>
            <div class="scan-overlay">
                <div class="scan-frame">
                    <div class="scan-line"></div>
                </div>
            </div>
        </div>

        <div class="flex flex-col items-center gap-4">
            <div class="px-4 py-2 bg-gray-100 rounded-full text-xs font-bold text-gray-600 uppercase tracking-widest" id="scanStatus">
                Mencari QR Code...
            </div>
            
            <div class="flex gap-3">
                <button class="w-12 h-12 rounded-full bg-white border border-gray-200 shadow-sm flex items-center justify-center text-gray-600 hover:bg-gray-50" onclick="history.back()">
                    <i class="fas fa-times"></i>
                </button>
                <button class="w-12 h-12 rounded-full bg-primary-600 shadow-lg shadow-primary/20 flex items-center justify-center text-white" onclick="toggleFlash()" id="flashBtn">
                    <i class="fas fa-bolt"></i>
                </button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('components/popup.js') }}"></script>
    <script>
        let qrScanner = null;
        
        function initScanner() {
            const video = document.getElementById('qr-video');
            qrScanner = new QrScanner(video, result => {
                qrScanner.stop();
                document.getElementById('scanStatus').textContent = 'Terdeteksi!';
                processQR(result.data);
            }, { highlightScanRegion: false });
            qrScanner.start();
        }

        function processQR(data) {
            // Your existing logic for processing QR
            showSuccessPopup({
                title: 'QR Terdeteksi',
                message: 'Memproses data lokasi...',
                onClose: () => window.location.href = "{{ route('attendance.clock-in') }}?qr=" + btoa(data)
            });
        }

        function toggleFlash() {
            qrScanner.toggleFlash();
        }

        document.addEventListener('DOMContentLoaded', initScanner);
    </script>
@endpush
