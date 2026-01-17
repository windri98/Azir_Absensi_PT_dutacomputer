<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Cache;

class TwoFactorAuthService
{
    protected $encryptionService;

    public function __construct(EncryptionService $encryptionService)
    {
        $this->encryptionService = $encryptionService;
    }

    /**
     * Enable 2FA for user
     */
    public function enable(User $user): array
    {
        $secret = $this->generateSecret();
        $qrCode = $this->generateQrCode($user->email, $secret);

        // Store secret temporarily (not yet confirmed)
        Cache::put("2fa_pending:{$user->id}", $secret, 600); // 10 minutes

        return [
            'secret' => $secret,
            'qr_code' => $qrCode,
        ];
    }

    /**
     * Confirm 2FA setup
     */
    public function confirm(User $user, string $code): bool
    {
        $secret = Cache::get("2fa_pending:{$user->id}");

        if (!$secret) {
            return false;
        }

        if (!$this->verifyCode($secret, $code)) {
            return false;
        }

        // Save secret to user
        $user->update([
            'two_factor_secret' => $this->encryptionService->encrypt($secret),
            'two_factor_enabled' => true,
        ]);

        // Clear pending secret
        Cache::forget("2fa_pending:{$user->id}");

        return true;
    }

    /**
     * Disable 2FA for user
     */
    public function disable(User $user): bool
    {
        $user->update([
            'two_factor_secret' => null,
            'two_factor_enabled' => false,
        ]);

        return true;
    }

    /**
     * Verify 2FA code
     */
    public function verify(User $user, string $code): bool
    {
        if (!$user->two_factor_enabled || !$user->two_factor_secret) {
            return false;
        }

        $secret = $this->encryptionService->decrypt($user->two_factor_secret);

        return $this->verifyCode($secret, $code);
    }

    /**
     * Generate backup codes
     */
    public function generateBackupCodes(User $user): array
    {
        $codes = [];
        for ($i = 0; $i < 10; $i++) {
            $codes[] = $this->encryptionService->generateToken(8);
        }

        $user->update([
            'two_factor_backup_codes' => json_encode($codes),
        ]);

        return $codes;
    }

    /**
     * Verify backup code
     */
    public function verifyBackupCode(User $user, string $code): bool
    {
        if (!$user->two_factor_backup_codes) {
            return false;
        }

        $codes = json_decode($user->two_factor_backup_codes, true);

        if (!in_array($code, $codes)) {
            return false;
        }

        // Remove used code
        $codes = array_filter($codes, function ($c) use ($code) {
            return $c !== $code;
        });

        $user->update([
            'two_factor_backup_codes' => json_encode($codes),
        ]);

        return true;
    }

    /**
     * Generate TOTP secret
     */
    private function generateSecret(): string
    {
        return base32_encode(random_bytes(32));
    }

    /**
     * Verify TOTP code
     */
    private function verifyCode(string $secret, string $code): bool
    {
        $time = floor(time() / 30);

        // Check current and previous time windows
        for ($i = -1; $i <= 1; $i++) {
            $hash = hash_hmac('sha1', pack('N*', $time + $i), base32_decode($secret), true);
            $offset = ord($hash[19]) & 0xf;
            $otp = (((ord($hash[$offset]) & 0x7f) << 24) |
                    ((ord($hash[$offset + 1]) & 0xff) << 16) |
                    ((ord($hash[$offset + 2]) & 0xff) << 8) |
                    (ord($hash[$offset + 3]) & 0xff)) % 1000000;

            if ($otp == $code) {
                return true;
            }
        }

        return false;
    }

    /**
     * Generate QR code for 2FA setup
     */
    private function generateQrCode(string $email, string $secret): string
    {
        $appName = config('app.name');
        $data = "otpauth://totp/{$appName}:{$email}?secret={$secret}&issuer={$appName}";

        // Return QR code data URL (you can use a QR code library)
        return $data;
    }
}

/**
 * Base32 encode function
 */
function base32_encode(string $input): string
{
    $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
    $output = '';
    $v = 0;
    $vbits = 0;

    for ($i = 0; $i < strlen($input); $i++) {
        $v = ($v << 8) | ord($input[$i]);
        $vbits += 8;

        while ($vbits >= 5) {
            $vbits -= 5;
            $output .= $alphabet[($v >> $vbits) & 31];
        }
    }

    if ($vbits > 0) {
        $output .= $alphabet[($v << (5 - $vbits)) & 31];
    }

    return $output;
}

/**
 * Base32 decode function
 */
function base32_decode(string $input): string
{
    $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
    $output = '';
    $v = 0;
    $vbits = 0;

    for ($i = 0; $i < strlen($input); $i++) {
        $v = ($v << 5) | strpos($alphabet, $input[$i]);
        $vbits += 5;

        if ($vbits >= 8) {
            $vbits -= 8;
            $output .= chr(($v >> $vbits) & 255);
        }
    }

    return $output;
}
