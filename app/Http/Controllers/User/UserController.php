<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Requests\Client\StoreRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Inertia\Inertia;

class UserController extends Controller
{
    public function index()
    {
        $count = User::count();
        $columns = User::columns(['surname','middleName']);
        $filters = User::columns(['avatar'],['surname','middleName']);

        return Inertia::render(
            'User/Index',
            ['users' => User::paginate($count), 'count' => $count, 'columns' =>$columns, 'filters'=>$filters]
        );
    }

    public function create()
    {
        return Inertia::render('User/Create');
    }

    public function store(StoreRequest $request)
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
