<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class ClientController extends Controller
{
    public function index()
    {
        $count = Client::count();
        return Inertia::render('Client/Index', ['users' => Client::all(),'count' => $count]);
    }

    public function create()
    {
        return Inertia::render('Client/Create');
    }

    public function store(\App\Http\Requests\Client\Request $request)
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
