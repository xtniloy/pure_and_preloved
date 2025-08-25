<?php

namespace App\Services;

use App\Enum\General;
use App\Jobs\ConfirmRegistrationEmailJob;
use App\Jobs\EmailVerificationMailJob;
use App\Jobs\SetPasswordEmailJob;
use App\Models\User;
use App\Models\UserAccessToken;
use App\Repositories\UserRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class UserAuthService
{
    protected User $user;

    protected static ?string $password;
    protected UserRepository $userRepository;

    public function __construct(User $user){
        $this->user = $user;
        $this->userRepository = new UserRepository;
    }

    public function sendConfirmRegistrationEmail(string $use_for = "new_registration"): int
    {
        $user = $this->user;

        $token = $this->getAccessToken('password_update');

        $link = route('set_password', ['token' => $token->token]);

        if ($use_for == 'forget_password'){
            ConfirmRegistrationEmailJob::dispatch(
                email: $user->email,
                link: $link,
                subject: 'You are requested to reset your password【'.config('app.name').'】',
                use_for: $use_for
            );
        }
        else{
            ConfirmRegistrationEmailJob::dispatch(email: $user->email, link: $link);

        }


        return 0;
    }

    public function sendEmailVerification(): int
    {
        $user = $this->user;

        $token = $this->getAccessToken('email_verification');

        $link = route('email_verify', ['token' => $token->token]);

        EmailVerificationMailJob::dispatch(email: $user->email, link: $link);

        return 0;
    }

    private function getAccessToken(string $type): UserAccessToken
    {
        $use_for = General::$access_token_types[$type];

        $user = $this->user;

        $token = UserAccessTokenService::generate(
            email: $user->email,
            use_for: $use_for
        );

        return $token;
    }

    public function set_password(string $password, UserAccessToken $userAccessToken = null): void
    {
        $user = $this->user;

        $hashed_password = static::$password ??= Hash::make($password);

        $this->userRepository->updateUserPassword($hashed_password, $user);

        if ($userAccessToken) {
            UserAccessToken::where('token', $userAccessToken->token)->delete();
        }
    }

    public function user_login(bool $remember = false): bool
    {
        $user = $this->user;

        if ($user->status != 0) {
            \Auth::login($user, $remember);

            $this->userRepository->lastLogin($user);

            return true;
        }

        return false;
    }

    public function verifyUserEmail(UserAccessToken $token): User
    {
        $user = $this->user;

        $user->email_verified_at = Carbon::now();
        $user->save();

        UserAccessToken::where('token', $token->token)->delete();

        return $user;
    }

}
