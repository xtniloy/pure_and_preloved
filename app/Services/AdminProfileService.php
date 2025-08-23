<?php

namespace App\Services;

use App\Http\Requests\Admin\AdminProfileUpdateRequest;
use App\Models\Admin;
use App\Repositories\AdminProfileRepository;
use Illuminate\Support\Facades\Hash;

class AdminProfileService
{
    protected AdminProfileRepository $adminProfileRepository;

    public function __construct(AdminProfileRepository $adminProfileRepository)
    {
        $this->adminProfileRepository = $adminProfileRepository;
    }

    public function update(AdminProfileUpdateRequest $request): Admin
    {
        $admin = auth()->guard('admin')->user();

        $data = $request->safe()->except('confirm_password');

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        return $this->adminProfileRepository->update_profile($data, $admin);
    }

}
