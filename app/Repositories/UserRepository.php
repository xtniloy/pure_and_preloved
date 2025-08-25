<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;

class UserRepository implements UserRepositoryInterface
{

    public function storeUser(Request $request): User
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        return $user;
    }

    public function updateUser(Request $request, User $user): User
    {
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'status' => $request->status ?? $user->status,
        ]);

        if ($user->verified_by != null){
            $user->update(['verified_by' => auth()->guard('admin')->user()->id]);
        }

        return $user;
    }

    public function updateUserPassword(string $password, User $user): User
    {
        if ($user->email_verified_at == null){
            $user->email_verified_at = Carbon::now();
        }
        $user->password = $password;

        $user->save();

        return $user;
    }

    public function lastLogin(User $user) : bool
    {
        $user->last_login = Carbon::now();
        $user->save();

        return true;
    }

    public function verification_toggle(User $user):bool
    {
        if ($user->verified_by == null){
            $user->verified_by = auth()->guard('admin')->user()->id;
            $result = true;
        }
        else{
            $user->verified_by = null;
            $result = false;
        }
        $user->save();

        return $result;
    }

    public function deleteUser(User $user): void
    {
        $user->delete();
    }
}
