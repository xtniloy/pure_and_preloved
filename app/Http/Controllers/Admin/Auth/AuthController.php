<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminAuthRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function index(): View
    {
        return view('admin.auth.login');
    }

    public function login(AdminAuthRequest $request): RedirectResponse
    {
        $rememberMe = isset($request->remember_me);

        if (auth()->guard('super_admin')->attempt(['email' => $request->input('email'), 'password' => $request->input('password')], $rememberMe)) {
            return redirect()->route('super_admin.dashboard')->with('success', "Login Successful");
        } else {
            return back()->with('error', 'Email or Password not matching');
        }
    }

    public function logout(): RedirectResponse
    {
        Auth::guard('super_admin')->logout();

        return redirect()->route('super_admin.login')->with('success', 'Logout Successful');
    }
}
