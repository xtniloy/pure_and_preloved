<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserStoreRequest;
use App\Http\Requests\Admin\UserUpdateRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService){
        $this->userService = $userService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $users = User::orderBy('id','desc');

        if (isset($request->q)) {
            $q = strtolower($request->q);
            $users->where(function ($query) use ($q) {
                $query->whereRaw('LOWER(title) LIKE ?', '%' . $q . '%');
            });
        }

        if (isset($request->status)) {
            $status = strtolower($request->status);
            $users->where('status', $status);
        }

        $users = $users->paginate(10)
            ->appends([
                'q' => strtolower($request->q??""),
                'status' => $request->status,
            ]);


        return view('admin.sections.users.index',compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.sections.users.form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserStoreRequest $request)
    {
        try {

            $user = $this->userService->storeUser($request);

            return redirect()->route('admin.users.edit', ['user' => $user])->with('success', 'User created successfully');

        }catch (\Exception $e){
            Log::error($e->getMessage());
            return redirect()->route('admin.users.create')->with('error',$e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return view('admin.sections.users.form',compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserUpdateRequest $request, User $user)
    {
        try {
            $user = $this->userService->updateUser($request,$user);

            return redirect()->route('admin.users.edit', ['user' => $user])->with('success', 'User updated successfully');
        }catch (\Exception $e){
            Log::error($e->getMessage());

            return redirect()->route('admin.users.edit', ['user' => $user])->with('error',$e->getMessage());
        }
    }

    public function resend_email(User $user)
    {
        if ($user->email_verified_at == null){

            try {

                $this->userService->sendSetPasswordEmail($user);;

                return  redirect()->route('admin.users.edit',['user'=>$user])->with('success','Email resend successful');

            }catch (\Exception $e){
                Log::error($e->getMessage());
                return redirect()->route('admin.users.edit')->with('error',$e->getMessage());
            }
        }

        return redirect()->route('admin.users.edit',['user'=>$user])->with('error','This user already verified there email');
    }

    public function verification_toggle(User $user){

        $message = $this->userService->verification_toggle($user);

        return redirect()->route('admin.users.edit',['user'=>$user])
            ->with('info',$message);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
}
