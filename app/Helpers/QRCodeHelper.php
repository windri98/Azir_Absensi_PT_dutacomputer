<?php

namespace App\Helpers;

use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

class QRCodeHelper
{
    /**
     * Generate QR code as SVG string
     *
     * @param string $data
     * @param int $size
     * @return string
     */
    public static function generate(string $data, int $size = 200): string
    {
        $renderer = new ImageRenderer(
            new RendererStyle($size),
            new SvgImageBackEnd()
        );
        
        $writer = new Writer($renderer);
        
        return $writer->writeString($data);
    }

    /**
     * Generate QR code for user attendance
     *
     * @param \App\Models\User $user
     * @return string
     */
    public static function generateForUser($user): string
    {
        // Generate unique QR data with employee_id and timestamp
        $data = json_encode([
            'employee_id' => $user->employee_id,
            'user_id' => $user->id,
            'type' => 'attendance',
            'generated_at' => now()->toDateTimeString()
        ]);
        
        return self::generate($data);
    }
}
