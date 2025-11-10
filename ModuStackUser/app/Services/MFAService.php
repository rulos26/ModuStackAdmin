<?php

namespace App\Services;

use App\Models\User;
use PragmaRX\Google2FA\Google2FA;

class MFAService
{
    protected Google2FA $google2fa;

    public function __construct()
    {
        $this->google2fa = new Google2FA();
    }

    /**
     * Generate a new MFA secret for a user.
     */
    public function generateSecret(): string
    {
        return $this->google2fa->generateSecretKey();
    }

    /**
     * Get QR Code URL for MFA setup.
     */
    public function getQrCodeUrl(User $user, string $secret): string
    {
        return $this->google2fa->getQRCodeUrl(
            config('app.name'),
            $user->email,
            $secret
        );
    }

    /**
     * Verify MFA code.
     */
    public function verifyCode(User $user, string $code): bool
    {
        if (!$user->hasMfaEnabled()) {
            return false;
        }

        return $this->google2fa->verifyKey($user->mfa_secret, $code);
    }

    /**
     * Enable MFA for a user.
     */
    public function enableMfa(User $user, string $secret): bool
    {
        $user->update([
            'mfa_enabled' => true,
            'mfa_secret' => $secret,
            'mfa_enabled_at' => now(),
        ]);

        return true;
    }

    /**
     * Disable MFA for a user.
     */
    public function disableMfa(User $user): bool
    {
        $user->update([
            'mfa_enabled' => false,
            'mfa_secret' => null,
            'mfa_enabled_at' => null,
        ]);

        return true;
    }
}



