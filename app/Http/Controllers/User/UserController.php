<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class UserController extends Controller
{
    public function index()
    {
        $count = User::count();

        return Inertia::render(
            'user/Index',
            ['users' => User::paginate($count), 'count' => $count]
        );
    }

    public function create()
    {
        return Inertia::render('user/Create');
    }

    public function store(Request $request)
    {
        $data = $request->validated();
        $password = Hash::make(12345678);
        $data['password'] = $password;

        User::create($data);

        return to_route('users');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return Inertia::render('user/Show', ['user' => $user]);
    }
    public function edit(User $user)
    {
        return Inertia::render('user/Edit', [
            'user' => $user,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {

        //dd($user,$request);
        //$user = user::findOrFail($id);
        $data = $request->validated();
        $user->update($data);

        return to_route('users');
    }
    public function destroy(User $user)
    {
        $user = User::findOrFail($user->id);
        if ($user->delete()) {
            return [
                'code' => 200,
                'message' => 'ID:' . $user->id . ' ' . $user->surname . ' deleted'
            ];
        }

        return [
            'code' => 404,
            'message' => 'user not found'
        ];
    }

    public function multiDestroy($ids)
    {
        $ids = explode(',', $ids);

        if (User::destroy($ids)) {
            return [
                'code' => 200,
                'message' => 'Move to the basket.'
            ];
        }

        return [
            'code' => 404,
            'message' => 'user not found'
        ];
    }

    public function archive()
    {
        $count = User::onlyTrashed()->count();
        return Inertia::render('user/Archive', [
            'users' => User::onlyTrashed()->latest('created_at')->paginate($count),
            'count' => $count,
        ]);
    }

    public function restore($id)
    {
        $user = User::where('id', $id)->onlyTrashed()->firstOrFail();
        if ($user->restore()) {
            return [
                'code' => 200,
                'message' => 'ID:' . $user->id . ' ' . $user->surname . ' restored.'
            ];
        }
        return [
            'code' => 404,
            'message' => 'user not found'
        ];
    }

    public function multiRestore($ids)
    {
        $ids = explode(',', $ids);
        $users = User::whereIn('id', $ids)->onlyTrashed()->get();
        $response = [];
        foreach ($users as $user) {
            if ($user->restore()) {
                $response = [
                    'code' => 200,
                    'message' => 'User restored'
                ];
            } else {
                $response = [
                    'code' => 404,
                    'message' => 'User already restored'
                ];
            }
        }
        return $response;
    }

    public function trash($ids)
    {
        $ids = explode(',', $ids);
        $users = User::whereIn('id', $ids)->withTrashed()->get();

        $response = [];

        foreach ($users as $user) {
            if (!$user) {
                $response = [
                    'code' => 404,
                    'message' => 'The user is already deleted'
                ];
            }
            if ($user->trashed()) {
                $user->forceDelete();

                if ($user->id) {
                    $extension = explode('/', $user->avatar);
                    $avatar = end($extension);
                    Storage::disk('public')->delete('/avatars/' . $avatar);
                }


                $response = [
                    'code' => 200,
                    'message' => 'User has been deleted'
                ];
            }
        }
        return $response;
    }
}
