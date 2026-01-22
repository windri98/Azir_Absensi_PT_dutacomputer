<?php

namespace App\Http\Controllers;

use App\Http\Requests\Activity\StoreActivityRequest;
use App\Models\Activity;
use App\Models\Partner;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ActivityController extends Controller
{
    public function create()
    {
        $partners = Partner::orderBy('name')->get();

        return view('activities.create', compact('partners'));
    }

    public function store(StoreActivityRequest $request)
    {
        $data = $request->validated();

        try {
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
                'latitude' => $data['latitude'] ?? null,
                'longitude' => $data['longitude'] ?? null,
                'location_address' => $data['location_address'] ?? null,
            ];

            if ($request->hasFile('evidence')) {
                $activityData['evidence_path'] = $request->file('evidence')
                    ->store('activities/evidence', 'public');
            }

            $activityData['signature_path'] = $this->storeSignatureImage($data['signature_data']);

            Activity::create($activityData);

            return redirect()->route('activities.history')
                ->with('success', 'Aktivitas berhasil dikirim dan menunggu persetujuan');
        } catch (\Exception $e) {
            Log::error('Error storing activity: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
            ]);

            return back()
                ->withInput()
                ->with('error', 'Gagal menyimpan aktivitas: '.$e->getMessage());
        }
    }

    public function history()
    {
        $activities = Activity::with('partner')
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('activities.history', compact('activities'));
    }

    public function show(Activity $activity)
    {
        if ($activity->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $activity->load('partner', 'approvedBy');

        return view('activities.show', compact('activity'));
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
