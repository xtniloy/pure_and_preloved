<?php

namespace App\Repositories\Interfaces;

use App\Http\Requests\Admin\UserStoreRequest;
use App\Http\Requests\Admin\UserUpdateRequest;
use App\Models\User;

interface UserRepositoryInterface
{
    public function storeUser(array $data);
    public function updateUser(array $data, User $user);
    public function updateUserPassword(string $hashedPassword, User $user);
    public function email_verified_at(User $user);
    public function lastLogin(User $user):bool;
//    public function verification_toggle(User $user):bool;
    public function deleteUser(User $user);
}
