<?php

namespace App\Services;

use App\Jobs\ConfirmRegistrationEmailJob;
use App\Models\Admin;
use App\Models\AdminAccessToken;

class AdminService
{
    public function storeAdmin(array $data): Admin
    {
        $admin = Admin::create([
            'name'  => $data['name'],
            'email' => $data['email'],
        ]);

        $this->sendSetPasswordEmail($admin, 'new_registration');

        return $admin;
    }

    public function updateAdmin(array $data, Admin $admin): Admin
    {
        $admin->update([
            'name'   => $data['name'],
            'email'  => $data['email'],
            'status' => $data['status'] ?? $admin->status,
        ]);

        return $admin->fresh();
    }

    public function deleteAdmin(Admin $admin): void
    {
        AdminAccessToken::where('email', $admin->email)->delete();
        $admin->delete();
    }

    public function sendSetPasswordEmail(Admin $admin, string $use_for = 'new_registration'): void
    {
        $token = AdminAccessTokenService::generate($admin->email, 2);
        $link  = route('admin.set_password', ['token' => $token->token]);

        ConfirmRegistrationEmailJob::dispatch(
            email: $admin->email,
            link: $link,
            subject: $use_for === 'forget_password'
                ? 'Reset your admin password【' . config('app.name') . '】'
                : 'Activate your admin account【' . config('app.name') . '】',
            use_for: $use_for,
        );
    }

    public function setPassword(Admin $admin, string $password, AdminAccessToken $token): void
    {
        // Set directly to bypass $fillable (email_verified_at) and avoid double-hashing
        // (password cast is 'hashed', so we pass the plain password)
        $admin->password = $password;
        if (!$admin->email_verified_at) {
            $admin->email_verified_at = now();
        }
        $admin->save();

        AdminAccessToken::where('token', $token->token)->delete();
    }
}
