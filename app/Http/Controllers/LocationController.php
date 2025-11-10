<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class LocationController extends Controller
{
    public function reverseGeocode(Request $request)
    {
        $lat = $request->query('lat');
        $lng = $request->query('lng');
        if (! $lat || ! $lng) {
            return response()->json(['error' => 'Latitude and longitude required'], 400);
        }
        $url = "https://nominatim.openstreetmap.org/reverse?format=json&lat={$lat}&lon={$lng}";
        try {
            $response = Http::withHeaders([
                'User-Agent' => 'website-absensi/1.0 (admin@yourdomain.com)',
            ])->get($url);

            return response()->json($response->json(), $response->status());
        } catch (\Exception $e) {
            return response()->json(['error' => 'Reverse geocoding failed', 'message' => $e->getMessage()], 500);
        }
    }
}
