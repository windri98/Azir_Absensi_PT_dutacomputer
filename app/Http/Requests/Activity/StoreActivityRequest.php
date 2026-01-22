<?php

namespace App\Http\Requests\Activity;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Partner;
use App\Services\GeoService;

class StoreActivityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasPermission('activities.create');
    }

    public function rules(): array
    {
        return [
            'partner_id' => 'required|exists:partners,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'required|date',
            'end_time' => 'nullable|date|after_or_equal:start_time',
            'evidence' => 'required|file|mimes:jpg,jpeg,png|max:5120',
            'signature_data' => 'required|string',
            'signature_name' => 'required|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'location_address' => 'nullable|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'partner_id.required' => 'Mitra wajib dipilih',
            'title.required' => 'Judul aktivitas wajib diisi',
            'start_time.required' => 'Waktu mulai wajib diisi',
            'evidence.required' => 'Foto bukti wajib diunggah',
            'signature_data.required' => 'Tanda tangan PIC wajib diisi',
            'signature_name.required' => 'Nama PIC wajib diisi',
            'latitude.required' => 'Lokasi Anda wajib diisi',
            'longitude.required' => 'Lokasi Anda wajib diisi',
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $partnerId = $this->input('partner_id');
            if (! $partnerId) {
                return;
            }

            $partner = Partner::find($partnerId);
            if (! $partner || $partner->latitude === null || $partner->longitude === null) {
                $validator->errors()->add('partner_id', 'Lokasi mitra belum diatur, silakan hubungi admin.');
                return;
            }

            $lat = $this->input('latitude');
            $lng = $this->input('longitude');
            if ($lat === null || $lng === null) {
                $validator->errors()->add('latitude', 'Lokasi Anda wajib diisi.');
                return;
            }

            $radiusMeters = (float) config('services.activity.radius_meters', 200);
            $geoService = app(GeoService::class);
            $distance = $geoService->distanceMeters((float) $lat, (float) $lng, (float) $partner->latitude, (float) $partner->longitude);

            if ($distance > $radiusMeters) {
                $validator->errors()->add('latitude', 'Lokasi Anda berada di luar radius ' . $radiusMeters . ' meter dari mitra.');
            }
        });
    }
}
