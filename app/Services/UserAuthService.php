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
//    private User $user;
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
//        $this->user = $user;
        $this->userRepository = $userRepository;
    }

    public function sendConfirmRegistrationEmail(User $user, string $use_for): void
    {
        $token = $this->getAccessToken($user, 'password_update');
        $link = route('set_password', ['token' => $token->token]);

        ConfirmRegistrationEmailJob::dispatch(
            email: $user->email,
            link: $link,
            subject: $use_for === 'forget_password'
                ? 'You are requested to reset your password【'.config('app.name').'】'
                : null,
            use_for: $use_for
        );
    }

    public function sendEmailVerification(User $user): void
    {
        $token = $this->getAccessToken($user,'email_verification');
        $link = route('email_verify', ['token' => $token->token]);

        EmailVerificationMailJob::dispatch(email: $user->email, link: $link);
    }

    private function getAccessToken(User $user, string $type): UserAccessToken
    {
        $use_for = General::$access_token_types[$type];

        return UserAccessTokenService::generate(
            email: $user->email,
            use_for: $use_for
        );
    }

    public function setPassword(User $user, string $password, ?UserAccessToken $userAccessToken = null): void
    {
        $hashedPassword = Hash::make($password);

        $this->userRepository->updateUserPassword($hashedPassword, $user);

        if ($userAccessToken) {
            $this->userRepository->email_verified_at($user);
            UserAccessToken::where('token', $userAccessToken->token)->delete();
        }
    }

    public function login(User $user, bool $remember = false): bool
    {
        if ($user->status != 0) {
            \Auth::login($user, $remember);
            $this->userRepository->lastLogin($user);
            return true;
        }

        return false;
    }

    public function verifyEmail(User $user, UserAccessToken $token): User
    {
        $this->userRepository->email_verified_at($user);

        UserAccessToken::where('token', $token->token)->delete();

        return $user;
    }
}
