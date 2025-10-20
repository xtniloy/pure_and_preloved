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
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
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

    public function email_success(User $user): View
    {
        $userAccessToken = $user->userAccessToken;
        if (!$userAccessToken) {
            abort(404);
        }

        $now = Carbon::now();
        $expiryTime = $userAccessToken->updated_at->addMinutes(3);
        $remainingTime =  0;

        // Check if current time is less than expiry time
        if ($now->lessThan($expiryTime)) {
            $remainingTime = $now->diffInSeconds($expiryTime);
        }

        return view('user.auth.success', compact('remainingTime','user'));
    }

    public function email_resend(User $user)
    {
        $userAccessToken = $user->userAccessToken;

        if (!$userAccessToken || now()->lessThan($userAccessToken->updated_at->addMinutes(3))) {
            abort(404);
        }

        try {
            if ($userAccessToken->use_for == 1 && !$user->email_verified_at) {
                $this->userService->sendEmailVerificationEmail($user);
                return redirect()
                    ->route('email.success', $user)
                    ->with('success', 'Email resend successful');
            }

            if ($userAccessToken->use_for == 2) {
                $this->userService->sendSetPasswordEmail($user, 'forget_password');
                return redirect()
                    ->route('email.success', $user)
                    ->with('success', 'Confirmation email resend successful');
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()
                ->route('admin.users.edit')
                ->with('error', $e->getMessage());
        }

        abort(404);
    }

    public function login(UserLoginRequest $request): RedirectResponse
    {
        $credentials = $request->only('email', 'password');
        $rememberMe = $request->filled('remember_me');

        if (auth()->attempt($credentials, $rememberMe)) {
            $user = auth()->user();

            if (!$user->hasVerifiedEmail()) {
                Auth::logout();
                return redirect()->route('email.success',$user)->with('warning', 'You must verify your email address before logging in.');
            }

            if (intval($user->status) !== 1) {
                Auth::logout();
                return redirect()->route('login')->with('warning', 'This account is not active.');
            }

            $user->update(['last_login' => now()]);

            return redirect()->route('user.home')->with('success', 'Login Successful');
        }

        return redirect()->route('login')->with('error', 'Email or Password not matching');
    }


    public function registration(UserRegistrationRequest $request)
    {
        $unverified_user = User::where('email', $request->email)
            ->whereNull('email_verified_at')
            ->first();
        if ($unverified_user) {
            return redirect()->route('email.success',$unverified_user)->with('warning', 'User already exist, You can not change any information until you verify the email');
        }

        $user = $this->userService->register($request);

        return redirect()->route('email.success',$user);
//        return redirect()->route('login')->with('success', 'Your registration is successful. Please check your email and verify your email !');
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

            return redirect()->route('user.home')->with('success', 'Email verified successful');
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

        $unverified_user = User::where('email', $request->email)
            ->whereNull('email_verified_at')
            ->first();
        if ($unverified_user) {
            return redirect()->route('email.success',$unverified_user)->with('warning', 'You can not request forget-password until you verify the email');
        }

        $user = User::where('email',$request->email)->first();

        $this->userService->sendSetPasswordEmail($user, 'forget_password');

        return redirect()->route('email.success',$user)
            ->with('success','Forget password email has been send. Check your email.');
    }

    public function logout(): RedirectResponse
    {
        Auth::guard('web')->logout();

        return redirect()->route('login')->with('success', 'Logout Successful');
    }
}
