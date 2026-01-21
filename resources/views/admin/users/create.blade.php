@extends('admin.layout')

@section('title', 'Tambah Pengguna')

@section('content')
<div class="space-y-5">
    <div class="bg-white rounded-2xl p-5 border border-gray-200 shadow-soft">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-lg font-bold text-gray-900">Tambah Pengguna</h2>
                <p class="text-sm text-gray-500">Lengkapi data pengguna baru secara lengkap.</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-soft max-w-3xl">
        <form method="post" action="{{ route('admin.users.store') }}" class="space-y-6">
            @csrf

            <div>
                <h3 class="text-sm font-semibold text-gray-700 mb-3">Informasi Dasar</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <label class="block text-sm text-gray-700">
                        ID Card / Employee ID *
                        <input class="mt-1 w-full form-input" type="text" name="employee_id" value="{{ old('employee_id') }}" placeholder="EMP001" required>
                    </label>
                    <label class="block text-sm text-gray-700">
                        Nama *
                        <input class="mt-1 w-full form-input" type="text" name="name" value="{{ old('name') }}" required>
                    </label>
                    <label class="block text-sm text-gray-700">
                        Email *
                        <input class="mt-1 w-full form-input" type="email" name="email" value="{{ old('email') }}" required>
                    </label>
                    <label class="block text-sm text-gray-700">
                        Password *
                        <input class="mt-1 w-full form-input" type="password" name="password" required>
                    </label>
                </div>
            </div>

            <div class="border-t border-gray-200 pt-5">
                <h3 class="text-sm font-semibold text-gray-700 mb-3">Informasi Pribadi</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <label class="block text-sm text-gray-700">
                        Telepon
                        <input class="mt-1 w-full form-input" type="text" name="phone" value="{{ old('phone') }}" placeholder="08123456789">
                    </label>
                    <label class="block text-sm text-gray-700">
                        Tanggal Lahir
                        <input class="mt-1 w-full form-input" type="date" name="birth_date" value="{{ old('birth_date') }}">
                    </label>
                    <label class="block text-sm text-gray-700 md:col-span-2">
                        Alamat
                        <textarea class="mt-1 w-full form-textarea" name="address" rows="3" placeholder="Alamat lengkap">{{ old('address') }}</textarea>
                    </label>
                    <label class="block text-sm text-gray-700">
                        Jenis Kelamin *
                        <select class="mt-1 w-full form-select" name="gender" required>
                            <option value="">Pilih Jenis Kelamin</option>
                            <option value="Laki-laki" {{ old('gender') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="Perempuan" {{ old('gender') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </label>
                </div>
            </div>

            <div class="border-t border-gray-200 pt-5">
                <h3 class="text-sm font-semibold text-gray-700 mb-3">Role & Shift</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <label class="block text-sm text-gray-700">
                        Role
                        <select class="mt-1 w-full form-select" name="roles[]">
                            <option value="">Pilih Role</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->name }} - {{ $role->description }}</option>
                            @endforeach
                        </select>
                        <span class="form-help">Gunakan role sesuai tanggung jawab pengguna.</span>
                    </label>

                    <label class="block text-sm text-gray-700">
                        Shift
                        <select class="mt-1 w-full form-select" name="shifts[]">
                            <option value="">Pilih Shift</option>
                            @foreach($shifts as $shift)
                                <option value="{{ $shift->id }}">{{ $shift->name }} ({{ $shift->start_time }} - {{ $shift->end_time }})</option>
                            @endforeach
                        </select>
                        <span class="form-help">Kosongkan jika belum ada jadwal tetap.</span>
                    </label>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Batal</a>
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

    .form-help {
        display: block;
        margin-top: 0.35rem;
        font-size: 0.75rem;
        color: #6b7280;
    }
</style>
@endsection
