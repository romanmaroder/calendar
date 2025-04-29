<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Requests\User\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class UserController extends Controller
{
    public function index()
    {
        $count = User::count();
        return Inertia::render('User/Index', ['users' => User::all(),'count' => $count]);
    }

    public function create()
    {
        return Inertia::render('User/Create');
    }

    public function store(Request $request)
    {

        $data = $request->validated();
        //$password = Hash::make($data['password']);
        $password = Hash::make(12345678);
        $data['password'] = $password;
        //dd(new AvatarResource($path));

        $user = User::create($data);

        //event(new Registered($user));

        //Auth::login($user);

        return to_route('users');
    }

}
