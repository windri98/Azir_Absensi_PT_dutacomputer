@extends('layouts.app')

@section('title', 'Clock In - Sistem Absensi')

@push('styles')
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" 
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" 
        crossorigin=""/>
    <link rel="stylesheet" href="{{ asset('css/popup.css') }}">
    <style>
        .map-container {
            height: 400px;
            border-radius: 1.5rem;
            overflow: hidden;
            position: relative;
            border: 1px solid #e2e8f0;
            margin-bottom: 2rem;
        }
        #map { height: 100%; width: 100%; z-index: 1; }
        .map-overlay {
            position: absolute;
            top: 1rem;
            left: 1rem;
            right: 1rem;
            z-index: 10;
            display: flex;
            justify-content: space-between;
            pointer-events: none;
        }
        .map-overlay > * { pointer-events: auto; }
        .map-btn {
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 0.75rem;
            background: white;
            border: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #475569;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            cursor: pointer;
        }
    </style>
@endpush

@section('content')
    <div class="px-4 py-8 lg:px-8 max-w-4xl mx-auto">
        <div class="flex items-center gap-4 mb-8">
            <button class="w-10 h-10 rounded-xl bg-white border border-gray-200 flex items-center justify-center text-gray-600 hover:bg-gray-50 transition-colors" onclick="history.back()">
                <i class="fas fa-chevron-left"></i>
            </button>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Clock In</h1>
                <p class="text-sm text-gray-500">Konfirmasi lokasi dan mulai jam kerja Anda.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="md:col-span-1">
                <div class="map-container shadow-sm">
                    <div id="map"></div>
                    <div class="map-overlay">
                        <button class="map-btn" onclick="refreshLocation()" title="Refresh Lokasi">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                        <div class="bg-white/90 backdrop-blur px-3 py-1.5 rounded-xl border border-gray-200 shadow-sm text-xs font-bold text-gray-800 flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                            <span id="currentTime">--:--</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="md:col-span-1">
                <div class="modern-card bg-white shadow-sm border-gray-100 p-6">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 rounded-xl bg-primary-50 text-primary-600 flex items-center justify-center">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Lokasi Saat Ini</h3>
                            <p class="text-[10px] text-gray-400 font-bold uppercase" id="locationAccuracy">Mencari GPS...</p>
                        </div>
                    </div>

                    <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100 mb-6">
                        <p class="text-sm text-gray-600 leading-relaxed" id="locationAddress">
                            <i class="fas fa-circle-notch fa-spin mr-2"></i> Mendapatkan koordinat...
                        </p>
                    </div>

                    <div class="mb-6">
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 block ml-1">Catatan (Opsional)</label>
                        <textarea id="noteInput" class="w-full p-4 bg-gray-50 border border-gray-100 rounded-2xl text-sm focus:outline-none focus:border-primary-500 transition-colors" rows="3" placeholder="Tulis catatan aktivitas Anda..."></textarea>
                    </div>

                    <button class="w-full btn btn-primary !rounded-2xl !py-4 shadow-lg shadow-primary/20 font-bold" onclick="performClockIn()" id="clockInBtn" disabled>
                        Konfirmasi Clock In
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- Leaflet JavaScript -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" 
            integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" 
            crossorigin=""></script>
    <script src="{{ asset('components/popup.js') }}"></script>
    <script>
        let currentLocation = null;
        let map = null;
        let currentMarker = null;

        function initMap() {
            map = L.map('map').setView([-6.2088, 106.8456], 15);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);
        }

        function refreshLocation() {
            getCurrentLocation();
        }

        function fetchWithTimeout(url, options = {}, timeout = 8000) {
            const controller = new AbortController();
            const id = setTimeout(() => controller.abort(), timeout);
            return fetch(url, { ...options, signal: controller.signal })
                .finally(() => clearTimeout(id));
        }

        function getCurrentLocation() {
            if (!navigator.geolocation) {
                document.getElementById('locationAddress').textContent = 'Geolocation tidak didukung oleh browser Anda.';
                showErrorPopup({ title: 'Lokasi', message: 'Geolocation tidak didukung oleh browser Anda.' });
                return;
            }
            
            navigator.geolocation.getCurrentPosition(position => {
                const { latitude, longitude, accuracy } = position.coords;
                currentLocation = { lat: latitude, lng: longitude, accuracy };
                
                if (!map) initMap();
                
                map.setView([latitude, longitude], 17);
                if (currentMarker) map.removeLayer(currentMarker);
                
                currentMarker = L.marker([latitude, longitude]).addTo(map);
                L.circle([latitude, longitude], { radius: accuracy, color: '#2563eb', fillOpacity: 0.1 }).addTo(map);
                
                document.getElementById('locationAccuracy').textContent = `Akurasi: ±${Math.round(accuracy)}m`;
                document.getElementById('clockInBtn').disabled = false;

                const coordsText = `${latitude.toFixed(6)}, ${longitude.toFixed(6)}`;
                document.getElementById('locationAddress').textContent = `Koordinat: ${coordsText}`;
                
                // Simple Reverse Geocode (non-blocking, with timeout)
                fetchWithTimeout(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${latitude}&lon=${longitude}`)
                    .then(r => r.json())
                    .then(data => {
                        if (data && data.display_name) {
                            document.getElementById('locationAddress').textContent = data.display_name;
                        }
                    })
                    .catch(() => {
                        document.getElementById('locationAddress').textContent = `Koordinat: ${coordsText}`;
                    });
            }, error => {
                const message = error.code === 1
                    ? 'Izin lokasi ditolak. Aktifkan GPS atau gunakan HTTPS di server.'
                    : 'Gagal mendapatkan lokasi. Coba refresh lokasi.';
                document.getElementById('locationAccuracy').textContent = 'Lokasi tidak tersedia';
                document.getElementById('locationAddress').textContent = message;
                showErrorPopup({ title: 'Lokasi', message });
            }, { enableHighAccuracy: true, timeout: 10000, maximumAge: 60000 });
        }

        function performClockIn() {
            const btn = document.getElementById('clockInBtn');
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-circle-notch fa-spin mr-2"></i> Memproses...';

            if (!currentLocation) {
                btn.disabled = false;
                btn.innerHTML = 'Konfirmasi Clock In';
                showErrorPopup({ title: 'Lokasi', message: 'Lokasi belum tersedia. Silakan refresh lokasi.' });
                return;
            }

            fetchWithTimeout("{{ route('attendance.check-in', [], false) }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    location: JSON.stringify(currentLocation),
                    note: document.getElementById('noteInput').value
                })
            }, 10000)
            .then(async r => {
                const contentType = r.headers.get('content-type') || '';
                const payload = contentType.includes('application/json')
                    ? await r.json()
                    : { message: `Request gagal (${r.status}).` };

                if (!r.ok) {
                    throw new Error(payload.message || `Request gagal (${r.status}).`);
                }

                return payload;
            })
            .then(data => {
                if (data.success) {
                    showSuccessPopup({
                        title: 'Berhasil!',
                        message: 'Absensi masuk Anda telah tercatat.',
                        onClose: () => window.location.href = "{{ route('attendance.absensi', [], false) }}"
                    });
                } else {
                    showErrorPopup({ title: 'Gagal', message: data.message || 'Proses check-in gagal.' });
                    btn.disabled = false;
                    btn.innerHTML = 'Konfirmasi Clock In';
                }
            })
            .catch((error) => {
                const message = error?.message || 'Koneksi lambat atau server tidak merespons.';
                showErrorPopup({ title: 'Gagal', message });
                btn.disabled = false;
                btn.innerHTML = 'Konfirmasi Clock In';
            });
        }

        setInterval(() => {
            const now = new Date();
            document.getElementById('currentTime').textContent = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
        }, 1000);

        document.addEventListener('DOMContentLoaded', getCurrentLocation);
    </script>
@endpush
