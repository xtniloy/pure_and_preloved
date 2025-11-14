<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminProfileUpdateRequest;
use App\Services\AdminProfileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminProfileController extends Controller
{
    protected AdminProfileService $adminProfileService;

    public function __construct(AdminProfileService $adminProfileService){
        $this->adminProfileService = $adminProfileService;
    }
    public function edit()
    {
        $user = Auth::guard('admin')->user();

        return view('admin.sections.profile.index',compact('user'));
    }

    public function update(AdminProfileUpdateRequest $request)
    {

        $this->adminProfileService->update($request);

        return redirect()->route('admin.profile.view')->with('success','Profile updated');
    }
}
