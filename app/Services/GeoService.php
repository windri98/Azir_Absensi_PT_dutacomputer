<?php

namespace App\Services;

class GeoService
{
    /**
     * Calculate distance between two coordinates in meters.
     */
    public function distanceMeters(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $earthRadius = 6371000;

        $latDelta = deg2rad($lat2 - $lat1);
        $lngDelta = deg2rad($lng2 - $lng1);

        $a = sin($latDelta / 2) ** 2
            + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($lngDelta / 2) ** 2;
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }

    public function withinRadius(
        float $lat1,
        float $lng1,
        float $lat2,
        float $lng2,
        float $radiusMeters
    ): bool {
        return $this->distanceMeters($lat1, $lng1, $lat2, $lng2) <= $radiusMeters;
    }
}
