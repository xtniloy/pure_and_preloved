<?php

namespace App\Services;

use App\Models\Admin;
use App\Models\AdminAccessToken;
use Carbon\Carbon;
use Ramsey\Uuid\Uuid;

class AdminAccessTokenService
{
    public static function generate(string $email, ?int $use_for = null): AdminAccessToken
    {
        $token = Uuid::uuid1();

        $admin = Admin::where('email', $email)->firstOrFail();

        $data = [
            'admin_id'   => $admin->id,
            'email'      => $email,
            'token'      => $token,
            'expires_at' => Carbon::now()->addDay(),
            'use_for'    => $use_for,
        ];

        $existing = AdminAccessToken::where('email', $email)->first();

        if ($existing) {
            AdminAccessToken::where('token', $existing->token)->update($data);
            return AdminAccessToken::where('token', $token)->first();
        }

        return AdminAccessToken::create($data);
    }

    public static function canRequest(string $email): bool
    {
        $existing = AdminAccessToken::where('email', $email)->first();
        if (!$existing) {
            return true;
        }
        return Carbon::now()->greaterThan($existing->updated_at->addMinutes(3));
    }

    public static function remainingSeconds(string $email): int
    {
        $existing = AdminAccessToken::where('email', $email)->first();
        if (!$existing) {
            return 0;
        }
        $expiryTime = $existing->updated_at->addMinutes(3);
        if (Carbon::now()->greaterThan($expiryTime)) {
            return 0;
        }
        return (int) Carbon::now()->diffInSeconds($expiryTime);
    }
}
