<?php

namespace App\Services;

use App\Http\Requests\Admin\UserStoreRequest;
use App\Http\Requests\Admin\UserUpdateRequest;
use App\Models\User;
use App\Repositories\UserRepository;

class UserService
{
    protected UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function storeUser(UserStoreRequest $request): User
    {
        $data = $request->validated();

        $user = $this->userRepository->storeUser($data);

        $this->sendSetPasswordEmail($user);

        return $user;
    }

    public function register(UserStoreRequest $request): User
    {
        $data = $request->validated();

        $user = $this->userRepository->storeUser($data);

        $userAuth = new UserAuthService($user, $this->userRepository);
        $userAuth->setPassword($data['password']);
        $userAuth->sendEmailVerification();

        return $user;
    }

    public function sendSetPasswordEmail(User $user): void
    {
        $userAuth = new UserAuthService($user, $this->userRepository);
        $userAuth->sendConfirmRegistrationEmail();
    }

    public function updateUser(UserUpdateRequest $request, User $user): User
    {
        $data = $request->validated();
        return $this->userRepository->updateUser($data, $user);
    }

    public function updatePassword(User $user, string $newPassword): void
    {
        $userAuth = new UserAuthService($user, $this->userRepository);
        $userAuth->setPassword($newPassword);
    }

    public function deleteUser(User $user): void
    {
        $this->userRepository->deleteUser($user);
    }
}
