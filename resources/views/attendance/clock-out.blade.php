@extends('layouts.app')

@section('title', 'Clock Out - Sistem Absensi')

@section('content')
    <div class="px-4 py-8 lg:px-8 max-w-2xl mx-auto text-center">
        <div class="mb-12">
            <h1 class="text-2xl font-bold text-gray-900">Clock Out</h1>
            <p class="text-sm text-gray-500">Selesaikan hari kerja Anda dan pastikan catatan tersimpan.</p>
        </div>

        <div class="modern-card bg-white shadow-sm border-gray-100 p-8">
            <div class="w-20 h-20 bg-red-50 text-red-500 rounded-full flex items-center justify-center mx-auto mb-6 text-3xl shadow-sm">
                <i class="fas fa-sign-out-alt"></i>
            </div>

            <div class="mb-8">
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Waktu Sekarang</p>
                <p class="text-4xl font-black text-gray-900" id="currentTime">--:--</p>
                <p class="text-sm text-gray-500 mt-1" id="currentDate">--</p>
            </div>

            <div class="mb-8 text-left">
                <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 block ml-1">Catatan Akhir Hari (Opsional)</label>
                <textarea id="noteInput" class="w-full p-4 bg-gray-50 border border-gray-100 rounded-2xl text-sm focus:outline-none focus:border-red-500 transition-colors" rows="3" placeholder="Tuliskan ringkasan pekerjaan Anda hari ini..."></textarea>
            </div>

            <div class="flex gap-4">
                <button class="flex-1 btn btn-secondary !rounded-2xl !py-4 font-bold" onclick="history.back()">
                    Batal
                </button>
                <button class="flex-[2] btn btn-danger !rounded-2xl !py-4 shadow-lg shadow-red-500/20 font-bold" onclick="performClockOut()" id="clockOutBtn">
                    Konfirmasi Pulang
                </button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('components/popup.js') }}"></script>
    <script>
        function performClockOut() {
            const btn = document.getElementById('clockOutBtn');
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-circle-notch fa-spin mr-2"></i> Memproses...';

            fetch("{{ route('attendance.check-out', [], false) }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    location: 'Clock Out Device',
                    notes: document.getElementById('noteInput').value
                })
            })
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
                        message: 'Terima kasih atas dedikasi Anda hari ini.',
                        onClose: () => window.location.href = "{{ route('attendance.absensi', [], false) }}"
                    });
                } else {
                    showErrorPopup({ title: 'Gagal', message: data.message || 'Proses check-out gagal.' });
                    btn.disabled = false;
                    btn.innerHTML = 'Konfirmasi Pulang';
                }
            })
            .catch((error) => {
                const message = error?.message || 'Koneksi lambat atau server tidak merespons.';
                showErrorPopup({ title: 'Gagal', message });
                btn.disabled = false;
                btn.innerHTML = 'Konfirmasi Pulang';
            });
        }

        setInterval(() => {
            const now = new Date();
            document.getElementById('currentTime').textContent = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
            document.getElementById('currentDate').textContent = now.toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });
        }, 1000);

        document.addEventListener('DOMContentLoaded', () => {
            const now = new Date();
            document.getElementById('currentTime').textContent = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
            document.getElementById('currentDate').textContent = now.toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });
        });
    </script>
@endpush
