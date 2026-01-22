@extends('layouts.app')

@section('title', 'Aktivitas Baru - PT DUTA COMPUTER')

@section('content')
<div class="px-4 py-8 lg:px-8 max-w-5xl mx-auto">
    <div class="flex items-center gap-4 mb-6">
        <button class="btn btn-secondary !p-2 shadow-sm" onclick="history.back()">
            <i class="fas fa-chevron-left"></i>
        </button>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Aktivitas Baru</h1>
            <p class="text-sm text-gray-600">Catat aktivitas teknisi di lokasi mitra</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-soft border border-gray-100 p-6">
        <form method="post" action="{{ route('activities.store') }}" enctype="multipart/form-data" id="activityForm" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <label class="block text-sm text-gray-700 md:col-span-2">
                    Mitra *
                    <select class="mt-1 w-full form-select" name="partner_id" id="partnerSelect" required>
                        <option value="">Pilih Mitra</option>
                        @foreach($partners as $partner)
                            <option
                                value="{{ $partner->id }}"
                                data-lat="{{ $partner->latitude }}"
                                data-lng="{{ $partner->longitude }}"
                                @selected(old('partner_id') == $partner->id)
                            >
                                {{ $partner->name }}
                            </option>
                        @endforeach
                    </select>
                </label>
                <label class="block text-sm text-gray-700 md:col-span-2">
                    Judul Aktivitas *
                    <input class="mt-1 w-full form-input" type="text" name="title" value="{{ old('title') }}" required>
                </label>
                <label class="block text-sm text-gray-700 md:col-span-2">
                    Deskripsi
                    <textarea class="mt-1 w-full form-textarea" name="description" rows="3">{{ old('description') }}</textarea>
                </label>
                <label class="block text-sm text-gray-700">
                    Waktu Mulai *
                    <input class="mt-1 w-full form-input" type="datetime-local" name="start_time" value="{{ old('start_time') }}" required>
                </label>
                <label class="block text-sm text-gray-700">
                    Waktu Selesai
                    <input class="mt-1 w-full form-input" type="datetime-local" name="end_time" value="{{ old('end_time') }}">
                </label>
                <label class="block text-sm text-gray-700 md:col-span-2">
                    Foto Bukti *
                    <input class="mt-2 w-full" type="file" name="evidence" accept="image/*" required>
                </label>
                <label class="block text-sm text-gray-700 md:col-span-2">
                    Nama PIC Mitra *
                    <input class="mt-1 w-full form-input" type="text" name="signature_name" value="{{ old('signature_name') }}" required>
                </label>
            </div>

            <div class="bg-gray-50 border border-gray-200 rounded-2xl p-4">
                <div class="flex items-center justify-between gap-3 mb-3">
                    <div>
                        <h3 class="font-semibold text-gray-900">Lokasi Aktivitas *</h3>
                        <p class="text-xs text-gray-500">Aktivitas harus berada di dalam radius mitra.</p>
                    </div>
                    <button type="button" class="btn btn-secondary" id="refreshLocationBtn">Ambil Lokasi</button>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
                    <div class="bg-white border border-gray-200 rounded-xl p-3">
                        <div class="text-xs text-gray-500">Status Lokasi</div>
                        <div id="locationStatus" class="font-semibold text-gray-800">Menunggu lokasi...</div>
                    </div>
                    <div class="bg-white border border-gray-200 rounded-xl p-3">
                        <div class="text-xs text-gray-500">Alamat</div>
                        <div id="locationAddress" class="font-semibold text-gray-800">-</div>
                    </div>
                    <div class="bg-white border border-gray-200 rounded-xl p-3">
                        <div class="text-xs text-gray-500">Koordinat</div>
                        <div id="locationCoords" class="font-mono text-gray-800">-</div>
                    </div>
                    <div class="bg-white border border-gray-200 rounded-xl p-3">
                        <div class="text-xs text-gray-500">Jarak ke Mitra</div>
                        <div id="locationDistance" class="font-semibold text-gray-800">-</div>
                    </div>
                </div>
                <p id="locationError" class="text-sm text-red-600 mt-3 hidden"></p>
                <input type="hidden" name="latitude" id="activityLatitude" value="{{ old('latitude') }}">
                <input type="hidden" name="longitude" id="activityLongitude" value="{{ old('longitude') }}">
                <input type="hidden" name="location_address" id="activityAddress" value="{{ old('location_address') }}">
            </div>

            <div>
                <label class="block text-sm text-gray-700 mb-2">Tanda Tangan PIC *</label>
                <div class="border border-gray-200 rounded-xl p-3 bg-gray-50">
                    <canvas id="signaturePad" width="600" height="200" style="width:100%;height:200px;border-radius:12px;background:#fff;border:1px solid #e5e7eb"></canvas>
                    <div class="flex flex-wrap items-center gap-2 mt-3">
                        <button type="button" class="btn btn-secondary" onclick="clearSignature()">Hapus</button>
                        <button type="button" class="btn btn-primary" onclick="saveSignature()">Simpan Tanda Tangan</button>
                        <span id="signatureStatus" class="text-xs text-gray-500">Gunakan mouse atau sentuhan untuk tanda tangan</span>
                    </div>
                </div>
                <input type="hidden" name="signature_data" id="signatureData" value="{{ old('signature_data') }}">
                <p id="signatureError" class="text-sm text-red-600 mt-2 hidden"></p>
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" class="btn btn-primary">Kirim Aktivitas</button>
                <a href="{{ route('activities.history') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

<style>
    .form-input,
    .form-select,
    .form-textarea {
        border: 1px solid #e5e7eb;
        border-radius: 0.75rem;
        padding: 0.6rem 0.75rem;
        background: #ffffff;
        color: #111827;
        transition: box-shadow 0.2s ease, border-color 0.2s ease;
    }

    .form-input:focus,
    .form-select:focus,
    .form-textarea:focus {
        outline: none;
        border-color: #93c5fd;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
    }
</style>

<script>
    const canvas = document.getElementById('signaturePad');
    const ctx = canvas.getContext('2d');
    let drawing = false;
    let signatureSaved = false;

    const radiusMeters = @json(config('services.activity.radius_meters', 200));
    const partnerSelect = document.getElementById('partnerSelect');
    const locationStatus = document.getElementById('locationStatus');
    const locationAddress = document.getElementById('locationAddress');
    const locationCoords = document.getElementById('locationCoords');
    const locationDistance = document.getElementById('locationDistance');
    const locationError = document.getElementById('locationError');
    const refreshLocationBtn = document.getElementById('refreshLocationBtn');

    const latitudeInput = document.getElementById('activityLatitude');
    const longitudeInput = document.getElementById('activityLongitude');
    const addressInput = document.getElementById('activityAddress');
    const signatureStatus = document.getElementById('signatureStatus');
    const signatureError = document.getElementById('signatureError');

    function getPosition(event) {
        const rect = canvas.getBoundingClientRect();
        const clientX = event.touches ? event.touches[0].clientX : event.clientX;
        const clientY = event.touches ? event.touches[0].clientY : event.clientY;
        return {
            x: clientX - rect.left,
            y: clientY - rect.top
        };
    }

    function startDrawing(event) {
        event.preventDefault();
        drawing = true;
        const { x, y } = getPosition(event);
        ctx.beginPath();
        ctx.moveTo(x, y);
    }

    function draw(event) {
        if (!drawing) return;
        event.preventDefault();
        const { x, y } = getPosition(event);
        ctx.lineTo(x, y);
        ctx.strokeStyle = '#111827';
        ctx.lineWidth = 2;
        ctx.lineCap = 'round';
        ctx.stroke();
    }

    function stopDrawing() {
        drawing = false;
    }

    function clearSignature() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        signatureSaved = false;
        signatureStatus.textContent = 'Gunakan mouse atau sentuhan untuk tanda tangan';
        signatureStatus.classList.remove('text-green-600');
        signatureStatus.classList.add('text-gray-500');
        signatureError.classList.add('hidden');
    }

    function saveSignature() {
        const dataUrl = canvas.toDataURL('image/png');
        const blankCanvas = document.createElement('canvas');
        blankCanvas.width = canvas.width;
        blankCanvas.height = canvas.height;
        const blankData = blankCanvas.toDataURL('image/png');

        if (dataUrl === blankData) {
            signatureError.textContent = 'Tanda tangan PIC wajib diisi.';
            signatureError.classList.remove('hidden');
            signatureSaved = false;
            return;
        }

        document.getElementById('signatureData').value = dataUrl;
        signatureSaved = true;
        signatureError.classList.add('hidden');
        signatureStatus.textContent = 'Tanda tangan tersimpan';
        signatureStatus.classList.remove('text-gray-500');
        signatureStatus.classList.add('text-green-600');
    }

    function setLocationError(message) {
        locationError.textContent = message;
        locationError.classList.remove('hidden');
        locationStatus.textContent = 'Lokasi belum siap';
        locationStatus.classList.add('text-red-600');
    }

    function clearLocationError() {
        locationError.classList.add('hidden');
        locationStatus.classList.remove('text-red-600');
    }

    function getSelectedPartnerCoords() {
        const option = partnerSelect.options[partnerSelect.selectedIndex];
        const lat = option?.dataset?.lat ? Number(option.dataset.lat) : null;
        const lng = option?.dataset?.lng ? Number(option.dataset.lng) : null;
        return { lat, lng };
    }

    function haversineDistance(lat1, lon1, lat2, lon2) {
        const R = 6371000;
        const dLat = (lat2 - lat1) * Math.PI / 180;
        const dLon = (lon2 - lon1) * Math.PI / 180;
        const a = Math.sin(dLat / 2) ** 2
            + Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180)
            * Math.sin(dLon / 2) ** 2;
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
        return R * c;
    }

    async function fetchAddress(lat, lng) {
        try {
            const response = await fetch(`{{ route('reverse-geocode') }}?lat=${lat}&lng=${lng}`);
            if (!response.ok) return;
            const data = await response.json();
            if (data?.display_name) {
                locationAddress.textContent = data.display_name;
                addressInput.value = data.display_name;
            }
        } catch (error) {
            console.warn('Reverse geocode failed', error);
        }
    }

    function updateDistanceDisplay() {
        const partner = getSelectedPartnerCoords();
        const lat = Number(latitudeInput.value);
        const lng = Number(longitudeInput.value);

        if (!partnerSelect.value) {
            locationDistance.textContent = '-';
            return;
        }

        if (partner.lat === null || partner.lng === null) {
            setLocationError('Lokasi mitra belum diatur, silakan hubungi admin.');
            locationDistance.textContent = '-';
            return;
        }

        if (!lat || !lng) {
            locationDistance.textContent = '-';
            return;
        }

        const distance = haversineDistance(lat, lng, partner.lat, partner.lng);
        const rounded = Math.round(distance);
        locationDistance.textContent = `${rounded} m (radius ${radiusMeters} m)`;

        if (distance > radiusMeters) {
            setLocationError(`Lokasi Anda berada di luar radius ${radiusMeters} meter dari mitra.`);
        } else {
            clearLocationError();
            locationStatus.textContent = 'Lokasi siap';
            locationStatus.classList.remove('text-red-600');
        }
    }

    function updateLocation(lat, lng) {
        latitudeInput.value = lat;
        longitudeInput.value = lng;
        locationCoords.textContent = `${Number(lat).toFixed(6)}, ${Number(lng).toFixed(6)}`;
        locationStatus.textContent = 'Lokasi ditemukan';
        clearLocationError();
        updateDistanceDisplay();
        fetchAddress(lat, lng);
    }

    function requestLocation() {
        if (!navigator.geolocation) {
            setLocationError('Browser tidak mendukung geolocation.');
            return;
        }

        locationStatus.textContent = 'Mengambil lokasi...';
        navigator.geolocation.getCurrentPosition(
            (position) => {
                updateLocation(position.coords.latitude, position.coords.longitude);
            },
            () => {
                setLocationError('Izin lokasi ditolak. Aktifkan lokasi untuk melanjutkan.');
            },
            { enableHighAccuracy: true, timeout: 10000 }
        );
    }

    canvas.addEventListener('mousedown', startDrawing);
    canvas.addEventListener('mousemove', draw);
    canvas.addEventListener('mouseup', stopDrawing);
    canvas.addEventListener('mouseleave', stopDrawing);
    canvas.addEventListener('touchstart', startDrawing, { passive: false });
    canvas.addEventListener('touchmove', draw, { passive: false });
    canvas.addEventListener('touchend', stopDrawing);

    document.getElementById('activityForm').addEventListener('submit', (event) => {
        updateDistanceDisplay();
        if (!latitudeInput.value || !longitudeInput.value) {
            event.preventDefault();
            setLocationError('Lokasi Anda wajib diisi.');
            return;
        }

        if (!signatureSaved) {
            event.preventDefault();
            signatureError.textContent = 'Klik "Simpan Tanda Tangan" sebelum mengirim.';
            signatureError.classList.remove('hidden');
            return;
        }
    });

    partnerSelect.addEventListener('change', () => {
        updateDistanceDisplay();
    });

    refreshLocationBtn.addEventListener('click', requestLocation);

    if (latitudeInput.value && longitudeInput.value) {
        updateLocation(latitudeInput.value, longitudeInput.value);
    } else {
        requestLocation();
    }
</script>
@endsection
