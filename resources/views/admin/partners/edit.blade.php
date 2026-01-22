@extends('admin.layout')

@section('title', 'Edit Mitra')

@section('content')
<div class="space-y-5">
    <div class="bg-white rounded-2xl p-5 border border-gray-200 shadow-soft">
        <div>
            <h2 class="text-lg font-bold text-gray-900">Edit Mitra</h2>
            <p class="text-sm text-gray-500">Perbarui data perusahaan rekanan.</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-soft max-w-3xl">
        <form method="post" action="{{ route('admin.partners.update', $partner) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <label class="block text-sm text-gray-700 md:col-span-2">
                    Nama Mitra *
                    <input class="mt-1 w-full form-input" type="text" name="name" value="{{ old('name', $partner->name) }}" required>
                </label>
                <label class="block text-sm text-gray-700">
                    PIC (Contact Person)
                    <input class="mt-1 w-full form-input" type="text" name="contact_person" value="{{ old('contact_person', $partner->contact_person) }}">
                </label>
                <label class="block text-sm text-gray-700">
                    Telepon
                    <input class="mt-1 w-full form-input" type="text" name="phone" value="{{ old('phone', $partner->phone) }}">
                </label>
                <label class="block text-sm text-gray-700 md:col-span-2">
                    Alamat
                    <textarea class="mt-1 w-full form-textarea" name="address" rows="3">{{ old('address', $partner->address) }}</textarea>
                </label>
                <label class="block text-sm text-gray-700">
                    Latitude
                    <input class="mt-1 w-full form-input" type="text" name="latitude" value="{{ old('latitude', $partner->latitude) }}" placeholder="-6.200000">
                </label>
                <label class="block text-sm text-gray-700">
                    Longitude
                    <input class="mt-1 w-full form-input" type="text" name="longitude" value="{{ old('longitude', $partner->longitude) }}" placeholder="106.816666">
                </label>
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                <a href="{{ route('admin.partners.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

<style>
    .form-input,
    .form-textarea {
        border: 1px solid #e5e7eb;
        border-radius: 0.75rem;
        padding: 0.6rem 0.75rem;
        background: #ffffff;
        color: #111827;
        transition: box-shadow 0.2s ease, border-color 0.2s ease;
    }

    .form-input:focus,
    .form-textarea:focus {
        outline: none;
        border-color: #93c5fd;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
    }
</style>
@endsection
