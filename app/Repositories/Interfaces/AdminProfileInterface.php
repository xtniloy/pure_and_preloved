<?php

namespace App\Repositories\Interfaces;

use App\Http\Requests\Admin\AdminProfileUpdateRequest;
use App\Models\Admin;

interface AdminProfileInterface
{
    public function update_profile(array $data, Admin $user): Admin;
}
