<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminAuthRequest;
use App\Http\Requests\Admin\AdminForgetPasswordRequest;
use App\Http\Requests\Admin\AdminSetPasswordRequest;
use App\Models\Admin;
use App\Models\AdminAccessToken;
use App\Services\AdminAccessTokenService;
use App\Services\AdminService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function __construct(protected AdminService $adminService)
    {
    }

    public function index(): View
    {
        return view('admin.auth.login');
    }

    public function login(AdminAuthRequest $request): RedirectResponse
    {
        $rememberMe = isset($request->remember_me);

        if (auth()->guard('admin')->attempt(
            ['email' => $request->email, 'password' => $request->password],
            $rememberMe
        )) {
            $admin = auth()->guard('admin')->user();

            if ((int) $admin->status !== 1) {
                auth()->guard('admin')->logout();
                return back()->with('error', 'Your account has been deactivated. Contact a super-admin.');
            }

            return redirect()->route('admin.dashboard')->with('success', 'Login Successful');
        }

        // Give a helpful message if account is not activated
        $admin = Admin::where('email', $request->email)->first();
        if ($admin && !$admin->email_verified_at) {
            return back()->with('error', 'Account not yet activated. Please check your email to set your password.');
        }

        return back()->with('error', 'Email or Password not matching');
    }

    public function logout(): RedirectResponse
    {
        Auth::guard('admin')->logout();

        return redirect()->route('admin.login')->with('success', 'Logout Successful');
    }

    // ─── Forgot Password ────────────────────────────────────────────────────────

    public function forget_password(): View
    {
        return view('admin.auth.forget_password');
    }

    public function request_forget_password(AdminForgetPasswordRequest $request): RedirectResponse
    {
        $admin = Admin::where('email', $request->email)->first();

        // 3-minute throttle
        if (!AdminAccessTokenService::canRequest($admin->email)) {
            $remaining = AdminAccessTokenService::remainingSeconds($admin->email);
            $minutes   = ceil($remaining / 60);
            return back()->with('error', "Please wait {$minutes} minute(s) before requesting another reset email.");
        }

        $this->adminService->sendSetPasswordEmail($admin, 'forget_password');

        return redirect()
            ->route('admin.email_success', $admin)
            ->with('success', 'Password reset email sent. Please check your inbox.');
    }

    public function email_success(Admin $admin): View
    {
        $token = $admin->adminAccessToken;

        if (!$token) {
            abort(404);
        }

        $now         = Carbon::now();
        $expiryTime  = $token->updated_at->addMinutes(3);
        $remaining   = $now->lessThan($expiryTime) ? (int) $now->diffInSeconds($expiryTime) : 0;

        return view('admin.auth.email_success', compact('admin', 'remaining'));
    }

    public function email_resend(Admin $admin): RedirectResponse
    {
        $token = $admin->adminAccessToken;

        if (!$token || now()->lessThan($token->updated_at->addMinutes(3))) {
            abort(404);
        }

        try {
            $this->adminService->sendSetPasswordEmail($admin, 'forget_password');

            return redirect()
                ->route('admin.email_success', $admin)
                ->with('success', 'Reset email resent successfully.');
        } catch (\Exception $e) {
            return redirect()
                ->route('admin.email_success', $admin)
                ->with('error', $e->getMessage());
        }
    }

    // ─── Set Password (used for both account activation & password reset) ───────

    public function set_password(string $token = null): View|RedirectResponse
    {
        $accessToken = AdminAccessToken::where('token', $token)->where('use_for', 2)->first();

        if (!$accessToken) {
            return redirect()->route('admin.login')->with('error', 'Invalid or expired token.');
        }

        return view('admin.auth.set_password', compact('token'));
    }

    public function save_password(AdminSetPasswordRequest $request, string $token = null): RedirectResponse
    {
        $accessToken = AdminAccessToken::where('token', $token)->where('use_for', 2)->first();

        if (!$accessToken) {
            return redirect()->route('admin.login')->with('error', 'Invalid or expired token.');
        }

        $admin = $accessToken->admin;

        if ((int) $admin->status !== 1) {
            return redirect()->route('admin.login')->with('error', 'Your account is inactive.');
        }

        $this->adminService->setPassword($admin, $request->password, $accessToken);

        return redirect()->route('admin.login')->with('success', 'Password set successfully. You can now log in.');
    }
}
