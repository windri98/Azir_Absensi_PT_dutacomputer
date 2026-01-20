@extends('layouts.app')

@section('title', 'Pengaturan Notifikasi - PT DUTA COMPUTER')

@section('content')
<div class="px-4 py-8 lg:px-8 max-w-4xl mx-auto">
    <!-- Page Header -->
    <div class="flex items-center gap-4 mb-8">
        <button class="btn btn-secondary !p-2 shadow-sm" onclick="goBack()">
            <i class="fas fa-chevron-left"></i>
        </button>
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Pengaturan Notifikasi</h1>
            <p class="text-sm text-gray-600">Kelola preferensi notifikasi Anda</p>
        </div>
    </div>

    <!-- Notification Settings Card -->
    <div class="bg-white rounded-2xl shadow-soft border border-gray-100 p-8">
        <div class="space-y-6">
            <!-- Email Notifications -->
            <div class="border-b border-gray-100 pb-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="fas fa-envelope text-primary-600"></i>
                    Notifikasi Email
                </h3>
                <div class="space-y-4">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" class="w-5 h-5 rounded border-gray-300 text-primary-600" checked>
                        <div>
                            <p class="font-semibold text-gray-900">Check-in/Check-out</p>
                            <p class="text-sm text-gray-600">Terima notifikasi saat berhasil check-in atau check-out</p>
                        </div>
                    </label>
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" class="w-5 h-5 rounded border-gray-300 text-primary-600" checked>
                        <div>
                            <p class="font-semibold text-gray-900">Izin/Cuti Disetujui</p>
                            <p class="text-sm text-gray-600">Terima notifikasi saat pengajuan izin disetujui atau ditolak</p>
                        </div>
                    </label>
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" class="w-5 h-5 rounded border-gray-300 text-primary-600" checked>
                        <div>
                            <p class="font-semibold text-gray-900">Laporan Bulanan</p>
                            <p class="text-sm text-gray-600">Terima laporan kehadiran bulanan di email</p>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Push Notifications -->
            <div class="border-b border-gray-100 pb-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="fas fa-bell text-primary-600"></i>
                    Notifikasi Push
                </h3>
                <div class="space-y-4">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" class="w-5 h-5 rounded border-gray-300 text-primary-600" checked>
                        <div>
                            <p class="font-semibold text-gray-900">Pengingat Check-in</p>
                            <p class="text-sm text-gray-600">Pengingat untuk check-in pada jam kerja</p>
                        </div>
                    </label>
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" class="w-5 h-5 rounded border-gray-300 text-primary-600" checked>
                        <div>
                            <p class="font-semibold text-gray-900">Update Persetujuan</p>
                            <p class="text-sm text-gray-600">Notifikasi real-time untuk update persetujuan</p>
                        </div>
                    </label>
                </div>
            </div>

            <!-- SMS Notifications -->
            <div>
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="fas fa-sms text-primary-600"></i>
                    Notifikasi SMS
                </h3>
                <div class="space-y-4">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" class="w-5 h-5 rounded border-gray-300 text-primary-600">
                        <div>
                            <p class="font-semibold text-gray-900">Persetujuan Penting</p>
                            <p class="text-sm text-gray-600">Hanya untuk notifikasi persetujuan yang penting (memerlukan biaya SMS)</p>
                        </div>
                    </label>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex gap-3 mt-8 pt-8 border-t border-gray-100">
            <button class="btn btn-primary flex-1" onclick="saveNotificationSettings()">
                <i class="fas fa-save mr-2"></i>Simpan Pengaturan
            </button>
            <button class="btn btn-secondary flex-1" onclick="resetNotificationSettings()">
                <i class="fas fa-redo mr-2"></i>Reset
            </button>
        </div>
    </div>
</div>

<script>
function goBack() {
    window.history.back();
}

function saveNotificationSettings() {
    alert('Pengaturan notifikasi berhasil disimpan');
}

function resetNotificationSettings() {
    if (confirm('Apakah Anda yakin ingin mereset semua pengaturan ke default?')) {
        location.reload();
    }
}
</script>
@endsection
