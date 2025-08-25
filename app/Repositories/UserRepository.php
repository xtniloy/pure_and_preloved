<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Carbon\Carbon;

class UserRepository implements UserRepositoryInterface
{
    public function storeUser(array $data): User
    {
        return User::create([
            'name'  => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
        ]);
    }

    public function updateUser(array $data, User $user): User
    {
        $user->update([
            'name'   => $data['name'] ?? $user->name,
            'email'  => $data['email'] ?? $user->email,
            'phone'  => $data['phone'] ?? $user->phone,
            'status' => $data['status'] ?? $user->status,
            'verified_by' => $data['verified_by'] ?? $user->verified_by,
        ]);

        return $user;
    }

    public function updateUserPassword(string $hashedPassword, User $user): User
    {
        if (!$user->email_verified_at) {
            $user->email_verified_at = Carbon::now();
        }

        $user->password = $hashedPassword;
        $user->save();

        return $user;
    }

    public function lastLogin(User $user): bool
    {
        $user->last_login = Carbon::now();
        return $user->save();
    }

    public function deleteUser(User $user): void
    {
        $user->delete();
    }
}
