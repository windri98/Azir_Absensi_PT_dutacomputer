<?php

namespace App\Services;

use Illuminate\Support\Facades\Crypt;

class EncryptionService
{
    /**
     * Encrypt sensitive data
     */
    public function encrypt(string $data): string
    {
        return Crypt::encryptString($data);
    }

    /**
     * Decrypt sensitive data
     */
    public function decrypt(string $encrypted): string
    {
        return Crypt::decryptString($encrypted);
    }

    /**
     * Encrypt user phone number
     */
    public function encryptPhone(string $phone): string
    {
        return $this->encrypt($phone);
    }

    /**
     * Decrypt user phone number
     */
    public function decryptPhone(string $encrypted): string
    {
        return $this->decrypt($encrypted);
    }

    /**
     * Encrypt user address
     */
    public function encryptAddress(string $address): string
    {
        return $this->encrypt($address);
    }

    /**
     * Decrypt user address
     */
    public function decryptAddress(string $encrypted): string
    {
        return $this->decrypt($encrypted);
    }

    /**
     * Hash sensitive data (one-way)
     */
    public function hash(string $data): string
    {
        return hash('sha256', $data);
    }

    /**
     * Verify hashed data
     */
    public function verify(string $data, string $hash): bool
    {
        return hash('sha256', $data) === $hash;
    }

    /**
     * Generate secure token
     */
    public function generateToken(int $length = 32): string
    {
        return bin2hex(random_bytes($length / 2));
    }

    /**
     * Generate OTP
     */
    public function generateOtp(int $length = 6): string
    {
        $otp = '';
        for ($i = 0; $i < $length; $i++) {
            $otp .= random_int(0, 9);
        }
        return $otp;
    }
}
