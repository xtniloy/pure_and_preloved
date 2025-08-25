<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserAccessToken;
use Carbon\Carbon;
use Ramsey\Uuid\Uuid;

class UserAccessTokenService
{
    public static function generate(string $email, ?int $use_for = null): UserAccessToken
    {
        $token = Uuid::uuid1(); //Time based

        $user = User::where('email', $email)->firstOrFail();

        if (!$user) {
            throw new \Exception("User with email {$email} not found.");
        }

        $data = [
            'user_id'    => $user->id,
            'email'      => $email,
            'token'      => $token,
            'expires_at' => Carbon::now()->addDay(),
            'use_for'    => $use_for,
        ];

        $existing_token = UserAccessToken::where('email', $email);

//        Confusing let's fixed later
//        if ($use_for != null) {
//            $data = array_merge($data, ['use_for' => $use_for]);
//            $existing_token->where('use_for', $use_for);
//        }

        $existing_token = $existing_token->first();

        if ($existing_token) {
            UserAccessToken::where('token', $existing_token->token)->update($data);

            return UserAccessToken::where('token', $token)->first();
        }

        return UserAccessToken::create($data);
    }
}
