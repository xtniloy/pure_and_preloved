<?php

namespace App\Services;

use App\Http\Requests\Admin\UserStoreRequest;
use App\Http\Requests\Admin\UserUpdateRequest;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;

class UserService
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository){
        $this->userRepository = $userRepository;
    }

    public function storeUser(Request $request):User
    {
        $user = $this->userRepository->storeUser($request);

        $this->sendSetPasswordEmail($user);

        return $user;
    }

    public function register(Request $request): User
    {
        $user = $this->userRepository->storeUser($request);

        $userAuth = new UserAuthService($user);

        $userAuth->set_password($request->password);

        $userAuth->sendEmailVerification();

        return $user;
    }

    public function sendSetPasswordEmail(User $user):User
    {
        $userAuth = new UserAuthService($user);

        $userAuth->sendConfirmRegistrationEmail();

        return $user;
    }

    public function verification_toggle(User $user): string
    {
        $result = $this->userRepository->verification_toggle($user);

        return $result ? 'User verification success':'User verification revoke';
    }

    public function updateUser(Request $request, User $user)
    {
        return $this->userRepository->updateUser($request, $user);
    }

    public function updatePassword(Request $request, User $user): void
    {
        $userAuth = new UserAuthService($user);

        $userAuth->set_password($request->password);
    }

    public function deleteUser(User $user)
    {
        $this->userRepository->deleteUser($user);
    }
}
