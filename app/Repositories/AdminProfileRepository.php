<?php

namespace App\Repositories;

use App\Http\Requests\Admin\AdminProfileUpdateRequest;
use App\Models\Admin;
use App\Repositories\Interfaces\AdminProfileInterface;

class AdminProfileRepository implements AdminProfileInterface
{
    public function update_profile(array $data, Admin $user): Admin
    {
        $user->update($data);

        return $user;
    }
}
