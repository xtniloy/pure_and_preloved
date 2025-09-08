<?php

namespace App\Services;

use App\Http\Requests\Admin\UserStoreRequest;
use App\Http\Requests\Admin\UserUpdateRequest;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;

class UserService
{
    protected UserRepository $userRepository;
    protected UserAuthService $userAuthService;

    public function __construct(UserRepository $userRepository, UserAuthService $userAuthService)
    {
        $this->userRepository = $userRepository;
        $this->userAuthService = $userAuthService;
    }

    public function storeUser(UserStoreRequest $request): User
    {
        $data = $request->validated();

        $user = $this->userRepository->storeUser($data);

        $this->sendSetPasswordEmail($user);

        return $user;
    }

    public function register(Request $request): User
    {
        $data = $request->validated();

        $user = $this->userRepository->storeUser($data);

        $userAuth = $this->userAuthService;
        $userAuth->setPassword($user, $data['password']);
        $userAuth->sendEmailVerification($user);

        return $user;
    }

    public function sendSetPasswordEmail(User $user): void
    {
        $userAuth = $this->userAuthService;
        $userAuth->sendConfirmRegistrationEmail($user);
    }

    public function updateUser(UserUpdateRequest $request, User $user): User
    {
        $data = $request->validated();
        return $this->userRepository->updateUser($data, $user);
    }

    public function updatePassword(User $user, string $newPassword): void
    {
        $userAuth = $this->userAuthService;
        $userAuth->setPassword($user, $newPassword);
    }

    public function deleteUser(User $user): void
    {
        $this->userRepository->deleteUser($user);
    }

    public function verification_toggle(User $user): string
    {
        $result = $this->userRepository->verification_toggle($user);

        return $result ? 'User verification success':'User verification revoke';
    }
}
