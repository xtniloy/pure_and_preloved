<?php

namespace App\Http\Controllers\User\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForgetPasswordRequest;
use App\Http\Requests\Auth\UpdatePasswordRequest;
use App\Http\Requests\Auth\UserLoginRequest;
use App\Http\Requests\Auth\UserRegistrationRequest;

use App\Models\User;
use App\Models\UserAccessToken;

use App\Services\UserAuthService;
use App\Services\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    protected UserService $userService;
    protected UserAuthService $userAuthService;

    public function __construct(UserService $userService, UserAuthService $userAuthService){
        $this->userService = $userService;
        $this->userAuthService = $userAuthService;
    }
    public function login_page(): View
    {
        return view('user.auth.login');
    }

    public function registration_page(string $code = null): View
    {
        return view('user.auth.register', compact('code'));
    }

    public function login(UserLoginRequest $request): RedirectResponse
    {
        $credentials = $request->only('email', 'password');
        $rememberMe = $request->filled('remember_me');

        if (auth()->attempt($credentials, $rememberMe)) {
            $user = auth()->user();

            if (!$user->hasVerifiedEmail()) {
                Auth::logout();
                return redirect()->route('login')->with('warning', 'You must verify your email address before logging in.');
            }

            if (intval($user->status) !== 1) {
                Auth::logout();
                return redirect()->route('login')->with('warning', 'This account is not active.');
            }

            $user->update(['last_login' => now()]);

            return redirect()->route('user.dashboard')->with('success', 'Login Successful');
        }

        return redirect()->route('login')->with('error', 'Email or Password not matching');
    }


    public function registration(UserRegistrationRequest $request)
    {

        $this->userService->register($request);

        return redirect()->route('login')->with('success', 'Your registration is successful. Please check your email and verify your email !');
    }

    public function email_verify(string $token = null)
    {
        $userAccessToken = UserAccessToken::where('token',$token)->where('use_for',1)->first();

        if ($userAccessToken){
            $user = $userAccessToken->user;

            $userAuthService =  $this->userAuthService;
            $user = $userAuthService->verifyEmail($user, $userAccessToken);

            if ($user->status == 0){
                return redirect()->route('login')->with('error', 'This account is not active');
            }

            $userAuthService->login($user);

            return redirect()->route('user.dashboard')->with('success', 'Email verified successful');
        }

        return redirect()->route('login')->with('error', 'Token not found');
    }

    public function set_password(string $token = null)
    {

        $access_token = UserAccessToken::where('token', $token)->first();

        if ($access_token) {
            return view('user.auth.set_password');
        }

        return redirect()->route('login')->with('error', 'Token not found');
    }

    public function save_password(UpdatePasswordRequest $request, string $token = null)
    {
        $access_token = UserAccessToken::where('token', $token)->where('use_for',2)->first();

        if ($access_token) {
            $user =  $access_token->user;

            $userAuthService =  $this->userAuthService;
            $userAuthService->setPassword($user, $request->password, $access_token);

            if ($user->status == 0){
                return redirect()->route('login')->with('error', 'This account is not active');
            }

            $userAuthService->login($user);

            return redirect()->route('user.home')->with('success', 'Password update successful');

        }

        return redirect()->route('login')->with('error', 'Token not found');
    }

    public function email_not_verify()
    {

        return redirect()->route('login')->with('error', 'Your email is not verified');
    }

    public function forget_password(){

        return view('user.auth.forget_password');
    }

    public function request_forget_password(ForgetPasswordRequest $request){

        $user = User::where('email',$request->email)->first();
        $userAuth = $this->userAuthService;

        $userAuth->sendConfirmRegistrationEmail($user, 'forget_password');

        return redirect()->route('forget_password')
            ->with('success','Forget password email has been send. Check your email.');
    }

    public function logout(): RedirectResponse
    {
        Auth::guard('web')->logout();

        return redirect()->route('login')->with('success', 'Logout Successful');
    }
}
