<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminStoreRequest;
use App\Http\Requests\Admin\AdminUpdateRequest;
use App\Models\Admin;
use App\Services\AdminService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function __construct(protected AdminService $adminService)
    {
    }

    public function index(Request $request): View
    {
        $admins = Admin::orderBy('id', 'desc');

        if ($request->filled('q')) {
            $q = strtolower($request->q);
            $admins->where(function ($query) use ($q) {
                $query->whereRaw('LOWER(name) LIKE ?', ['%' . $q . '%'])
                      ->orWhereRaw('LOWER(email) LIKE ?', ['%' . $q . '%']);
            });
        }

        if ($request->filled('status')) {
            $admins->where('status', $request->status);
        }

        $admins = $admins->paginate(10)->appends([
            'q'      => $request->q ?? '',
            'status' => $request->status,
        ]);

        return view('admin.sections.admins.index', compact('admins'));
    }

    public function create(): View
    {
        return view('admin.sections.admins.form');
    }

    public function store(AdminStoreRequest $request): RedirectResponse
    {
        try {
            $admin = $this->adminService->storeAdmin($request->validated());

            return redirect()
                ->route('admin.admins.edit', $admin)
                ->with('success', 'Admin created successfully. An activation email has been sent.');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()
                ->route('admin.admins.create')
                ->with('error', $e->getMessage());
        }
    }

    public function show(Admin $admin)
    {
        //
    }

    public function edit(Admin $admin): View
    {
        return view('admin.sections.admins.form', compact('admin'));
    }

    public function update(AdminUpdateRequest $request, Admin $admin): RedirectResponse
    {
        try {
            // Prevent deactivating yourself
            if ($admin->id === Auth::guard('admin')->id() && (int) $request->status === 0) {
                return redirect()
                    ->route('admin.admins.edit', $admin)
                    ->with('error', 'You cannot deactivate your own account.');
            }

            $admin = $this->adminService->updateAdmin($request->validated(), $admin);

            return redirect()
                ->route('admin.admins.edit', $admin)
                ->with('success', 'Admin updated successfully.');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()
                ->route('admin.admins.edit', $admin)
                ->with('error', $e->getMessage());
        }
    }

    public function destroy(Admin $admin): RedirectResponse
    {
        if ($admin->id === Auth::guard('admin')->id()) {
            return redirect()
                ->route('admin.admins.index')
                ->with('error', 'You cannot delete your own account.');
        }

        try {
            $this->adminService->deleteAdmin($admin);

            return redirect()
                ->route('admin.admins.index')
                ->with('success', 'Admin deleted successfully.');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()
                ->route('admin.admins.index')
                ->with('error', $e->getMessage());
        }
    }

    public function resend_email(Admin $admin): RedirectResponse
    {
        if ($admin->email_verified_at !== null) {
            return redirect()
                ->route('admin.admins.edit', $admin)
                ->with('error', 'This admin has already activated their account.');
        }

        try {
            $this->adminService->sendSetPasswordEmail($admin, 'new_registration');

            return redirect()
                ->route('admin.admins.edit', $admin)
                ->with('success', 'Activation email resent successfully.');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()
                ->route('admin.admins.edit', $admin)
                ->with('error', $e->getMessage());
        }
    }
}
