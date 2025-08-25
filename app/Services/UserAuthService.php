<?php

namespace App\Services;

use App\Enum\General;
use App\Jobs\ConfirmRegistrationEmailJob;
use App\Jobs\EmailVerificationMailJob;
use App\Models\User;
use App\Models\UserAccessToken;
use App\Repositories\UserRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class UserAuthService
{
    private User $user;
    private UserRepository $userRepository;

    public function __construct(User $user, UserRepository $userRepository)
    {
        $this->user = $user;
        $this->userRepository = $userRepository;
    }

    public function sendConfirmRegistrationEmail(string $use_for = "new_registration"): void
    {
        $token = $this->getAccessToken('password_update');
        $link = route('set_password', ['token' => $token->token]);

        ConfirmRegistrationEmailJob::dispatch(
            email: $this->user->email,
            link: $link,
            subject: $use_for === 'forget_password'
                ? 'You are requested to reset your password【'.config('app.name').'】'
                : null,
            use_for: $use_for
        );
    }

    public function sendEmailVerification(): void
    {
        $token = $this->getAccessToken('email_verification');
        $link = route('email_verify', ['token' => $token->token]);

        EmailVerificationMailJob::dispatch(email: $this->user->email, link: $link);
    }

    private function getAccessToken(string $type): UserAccessToken
    {
        $use_for = General::$access_token_types[$type];

        return UserAccessTokenService::generate(
            email: $this->user->email,
            use_for: $use_for
        );
    }

    public function setPassword(string $password, ?UserAccessToken $userAccessToken = null): void
    {
        $hashedPassword = Hash::make($password);

        $this->userRepository->updateUserPassword($hashedPassword, $this->user);

        if ($userAccessToken) {
            UserAccessToken::where('token', $userAccessToken->token)->delete();
        }
    }

    public function login(bool $remember = false): bool
    {
        if ($this->user->status != 0) {
            \Auth::login($this->user, $remember);
            $this->userRepository->lastLogin($this->user);
            return true;
        }

        return false;
    }

    public function verifyEmail(UserAccessToken $token): User
    {
        $this->user->email_verified_at = Carbon::now();
        $this->user->save();

        UserAccessToken::where('token', $token->token)->delete();

        return $this->user;
    }
}
