<?php

namespace App\Repositories\Interfaces;

use App\Http\Requests\Admin\UserStoreRequest;
use App\Http\Requests\Admin\UserUpdateRequest;
use App\Models\User;

interface UserRepositoryInterface
{
    public function storeUser(UserStoreRequest $request);
    public function updateUser(UserUpdateRequest $request, User $user);
    public function updateUserPassword(string $password, User $user);
    public function lastLogin(User $user):bool;
    public function verification_toggle(User $user):bool;
    public function deleteUser(User $user);
}
