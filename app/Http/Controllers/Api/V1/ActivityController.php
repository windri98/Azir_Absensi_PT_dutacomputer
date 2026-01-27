<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Partner;
use App\Services\GeoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ActivityController extends Controller
{
    public function index(Request $request)
    {
        $query = Activity::with('partner')
            ->where('user_id', Auth::id());

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $activities = $query->orderBy('created_at', 'desc')->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $activities->items(),
            'pagination' => [
                'total' => $activities->total(),
                'per_page' => $activities->perPage(),
                'current_page' => $activities->currentPage(),
                'last_page' => $activities->lastPage(),
            ],
        ]);
    }

    public function show($id)
    {
        $activity = Activity::with(['partner', 'approvedBy'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $activity,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'partner_id' => 'required|exists:partners,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'required|date',
            'end_time' => 'nullable|date|after_or_equal:start_time',
            'evidence' => 'required|file|mimes:jpg,jpeg,png|max:5120',
            'signature_data' => 'required|string',
            'signature_name' => 'required|string|max:255',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'location_address' => 'nullable|string|max:255',
        ], [
            'partner_id.required' => 'Mitra wajib dipilih',
            'title.required' => 'Judul aktivitas wajib diisi',
            'start_time.required' => 'Waktu mulai wajib diisi',
            'evidence.required' => 'Foto bukti wajib diunggah',
            'signature_data.required' => 'Tanda tangan PIC wajib diisi',
            'signature_name.required' => 'Nama PIC wajib diisi',
            'latitude.required' => 'Lokasi Anda wajib diisi',
            'longitude.required' => 'Lokasi Anda wajib diisi',
        ]);

        $validator->after(function ($validator) use ($request) {
            $partner = Partner::find($request->partner_id);
            if (! $partner || $partner->latitude === null || $partner->longitude === null) {
                $validator->errors()->add('partner_id', 'Lokasi mitra belum diatur, silakan hubungi admin.');
                return;
            }

            $radiusMeters = (float) config('services.activity.radius_meters', 200);
            $geoService = app(GeoService::class);
            $distance = $geoService->distanceMeters(
                (float) $request->latitude,
                (float) $request->longitude,
                (float) $partner->latitude,
                (float) $partner->longitude
            );

            if ($distance > $radiusMeters) {
                $validator->errors()->add('latitude', 'Lokasi Anda berada di luar radius ' . $radiusMeters . ' meter dari mitra.');
            }
        });

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $data = $validator->validated();

        try {
            $activityData = DB::transaction(function () use ($request, $data) {
                $activityData = [
                    'user_id' => Auth::id(),
                    'partner_id' => $data['partner_id'],
                    'title' => $data['title'],
                    'description' => $data['description'] ?? null,
                    'start_time' => $data['start_time'],
                    'end_time' => $data['end_time'] ?? null,
                    'status' => 'signed',
                    'signature_name' => $data['signature_name'],
                    'signed_at' => now(),
                    'latitude' => $data['latitude'],
                    'longitude' => $data['longitude'],
                    'location_address' => $data['location_address'] ?? null,
                ];

                // Store evidence file
                try {
                    $activityData['evidence_path'] = $request->file('evidence')
                        ->store('activities/evidence', 'public');
                } catch (\Exception $e) {
                    throw new \RuntimeException('Gagal mengupload foto bukti: ' . $e->getMessage());
                }

                // Store signature
                try {
                    $activityData['signature_path'] = $this->storeSignatureImage($data['signature_data']);
                } catch (\Exception $e) {
                    // Clean up uploaded evidence file if signature fails
                    if (isset($activityData['evidence_path'])) {
                        Storage::disk('public')->delete($activityData['evidence_path']);
                    }
                    throw new \RuntimeException('Gagal menyimpan tanda tangan: ' . $e->getMessage());
                }

                return Activity::create($activityData);
            });

            return response()->json([
                'success' => true,
                'message' => 'Aktivitas berhasil dikirim',
                'data' => $activityData->load('partner'),
            ], 201);
        } catch (\RuntimeException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan aktivitas',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    private function storeSignatureImage(string $signatureData): string
    {
        $extension = 'png';
        $payload = $signatureData;

        if (str_starts_with($signatureData, 'data:')) {
            if (preg_match('/^data:image\\/(png|jpg|jpeg);base64,/', $signatureData, $matches)) {
                $extension = $matches[1] === 'jpeg' ? 'jpg' : $matches[1];
                $payload = substr($signatureData, strpos($signatureData, ',') + 1);
            }
        }

        $binary = base64_decode($payload, true);

        if ($binary === false) {
            throw new \RuntimeException('Tanda tangan tidak valid');
        }

        $filename = 'activities/signatures/' . now()->format('Ymd') . '-' . Str::random(20) . '.' . $extension;
        Storage::disk('public')->put($filename, $binary);

        return $filename;
    }
}
