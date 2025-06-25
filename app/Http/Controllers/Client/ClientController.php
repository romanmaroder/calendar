<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Filters\Pipeline\Client\Email;
use App\Http\Filters\Pipeline\Client\Id;
use App\Http\Filters\Pipeline\Client\MiddleName;
use App\Http\Filters\Pipeline\Client\Name;
use App\Http\Filters\Pipeline\Client\Phone;
use App\Http\Filters\Pipeline\Client\Surname;
use App\Http\Requests\Client\AvatarClientRequest;
use App\Http\Requests\Client\FilterClientRequest;
use App\Http\Requests\Client\StoreClientRequest;
use App\Http\Requests\Client\UpdateClientRequest;
use App\Models\Client;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(FilterClientRequest $request)
    {
        $data = $request->validated();

        $clients = app()->make(Pipeline::class)->send(Client::query())->through([
                                                                                    Id::class,
                                                                                    Name::class,
                                                                                    Surname::class,
                                                                                    MiddleName::class,
                                                                                    Phone::class,
                                                                                    Email::class,
                                                                                ])->thenReturn();
        $count = $clients->count();
        $clients = $clients->paginate($count);

        return Inertia::render('Client/Index', [
            'clients' => $clients,
            'count' => $count,
            'columns' => Client::columns(['deleted_at','surname','middleName']),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Client/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreClientRequest $request)
    {
        $data = $request->validated();
        $password = Hash::make(12345678);
        $data['password'] = $password;
        Client::create($data);
        return to_route('clients.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Client $client)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Client $client)
    {
        return Inertia::render('Client/Edit', [
            'client' => $client,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateClientRequest $request, Client $client)
    {
        $data = $request->validated();
        $client->update($data);

        return to_route('clients.index');
    }

    public function avatar(AvatarClientRequest $request, Client $client)
    {
        $request->validated();

        if (isset($client->avatar)) {
            $extension = explode('/', $client->avatar);
            $avatar = end($extension);

            $client->update(['avatar' => null]);
            return [
                'code' => 200,
                'message' => 'Avatar updated successfully',
                'url' => '',
                'name' => $avatar,
            ];
        }
        return [
            'code' => 404,
            'message' => 'Avatar not found',
        ];
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client)
    {
        $client = Client::findOrFail($client->id);
        if ($client->delete()) {
            return [
                'code' => 200,
                'message' => 'ID:' . $client->id . ' ' . $client->surname . ' deleted'
            ];
        }

        return [
            'code' => 404,
            'message' => 'Client not found'
        ];
    }

    public function multiDestroy($ids)
    {
        $ids = explode(',', $ids);

        if (Client::destroy($ids)) {
            return [
                'code' => 200,
                'message' => 'Moven to the basket.'
            ];
        }

        return [
            'code' => 404,
            'message' => 'Client not found'
        ];
    }

    public function archive()
    {
        $count = Client::onlyTrashed()->count();
        return Inertia::render('Client/Archive', [
            'clients' => Client::onlyTrashed()->latest('created_at')->paginate($count),
            'count' => $count,
            'columns' => Client::columns(['surname','middleName','comment','records','total','source','discount','blacklist','prepayment']),
        ]);
    }


    public function restore($id)
    {
        $client = Client::where('id',$id)->onlyTrashed()->firstOrFail();
        if($client->restore()){
            return [
                'code' => 200,
                'message' => 'ID:' . $client->id . ' ' . $client->surname . ' restored.'
            ];
        }
        return [
            'code' => 404,
            'message' => 'Client not found'
        ];
    }

    public function multiRestore($ids)
    {
        $ids = explode(',', $ids);
        $clients = Client::whereIn('id', $ids)->onlyTrashed()->get();
        $response =[];
        foreach ($clients as $client) {
            if ($client->restore()) {
                $response=[
                    'code' => 200,
                    'message' => 'Client restored'
                ];
            }else{
                $response=[
                    'code' => 404,
                    'message' => 'Client already restored'
                ];
            }
        }
        return $response;
    }

    public function trash($ids)
    {
        $ids = explode(',', $ids);
        $clients = Client::whereIn('id', $ids)->withTrashed()->get();

        $response = [];

        foreach ($clients as $client) {
            if (!$client) {
                $response = [
                    'code' => 404,
                    'message' => 'The client is already deleted'
                ];
            }
            if ($client->trashed()) {
                $client->forceDelete();

                if ($client->id) {
                    $extension = explode('/', $client->avatar);
                    $avatar = end($extension);
                    Storage::disk('public')->delete('/avatars/' . $avatar);
                }


                $response = [
                    'code' => 200,
                    'message' => 'Clients has been deleted'
                ];
            };
        }
        return $response;
    }
}
