<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\AvatarClientRequest;
use App\Http\Requests\Client\StoreClientRequest;
use App\Http\Requests\Client\UpdateClientRequest;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ClientController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clients = Client::paginate(20);
        return Inertia::render('client/Index', [
            'clients' => $clients,
            'count' => $clients->total(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('client/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreClientRequest $request)
    {
        $data = $request->validated();
        // Гарантируем наличие ключа 'password' (даже если он пустой)
        // Если поле не установлено в форме, мутатор не сработает
        $data['password'] = $data['password'] ?? '';
        Client::create($data);
        return to_route('clients.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Client $client)
    {
        if ($client->trashed()) {
            return Inertia::render('client/Show', [
                'client' => $client,
                'isDeleted' => true,
            ]);
        }
        return Inertia::render('client/Show', ['client' => $client, 'isDeleted' => false]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Client $client)
    {
        return Inertia::render('client/Edit', [
            'client' => $client,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateClientRequest $request, $id)
    {
        $client = Client::find($id);
        $data = $request->validated();

        $client->update($data);

        return to_route('clients.index');
    }

    public function avatar(AvatarClientRequest $request, Client $client)
    {
        $request->validated();
        if (isset($client->avatar)) {
            $client->update(['avatar' => null]);
        }
    }

    public function archive()
    {
        $Clients = Client::onlyTrashed()->latest('created_at')->paginate(20);
        return Inertia::render('client/Archive', [
            'clients' => $Clients->collect(),
            'count' => $Clients->total(),
        ]);
    }

    public function softDelete(string $id)
    {
        $resource = Client::findOrFail($id);
        $resource->delete();
        return response()->json(['success' => true, 'message' => 'Client has been deleted', 'code' => 200]);
    }

    public function bulkSoftDelete(Request $request)
    {
        $ids = $request->input('ids', []);
        Client::whereIn('id', $ids)->delete();
        return response()->json(
            ['success' => true, 'count' => count($ids), 'message' => 'Move to the basket.', 'code' => 200]
        );
    }

    public function forceDelete(string $id)
    {
        $client = Client::withTrashed()->findOrFail($id);
        $extension = explode('/', $client->avatar);
        $avatar = end($extension);
        Storage::disk('public')->delete('/avatars/' . $avatar);
        $client->forceDelete();
        return response()->json([
                                    'success' => true,
                                    'message' => 'ID:' . $client->id . ' ' . $client->surname . ' deleted',
                                    'code' => 200
                                ]);
    }

    public function bulkForceDelete(Request $request)
    {
        $ids = $request->input('ids', []);

        $clients = Client::withTrashed()->whereIn('id', $ids)->get();

        foreach ($clients as $client) {
            $extension = explode('/', $client->avatar);
            $avatar = end($extension);
            Storage::disk('public')->delete('/avatars/' . $avatar);
        }


        Client::withTrashed()->whereIn('id', $ids)->forceDelete();
        return response()->json([
                                    'success' => true,
                                    'message' => 'Clients have been deleted',
                                    'count' => count($ids)
                                ]);
    }

    public function restore($id)
    {
        $client = Client::onlyTrashed()->findOrFail($id);
        $client->restore();
        return response()->json([
                                    'success' => true,
                                    'message' => 'ID:' . $client->id . ' ' . $client->surname . ' restored.',
                                    'code' => 200
                                ]);
    }

    public function bulkRestore(Request $request)
    {
        $ids = $request->input('ids', []);
        Client::onlyTrashed()->whereIn('id', $ids)->restore();
        return response()->json([
                                    'success' => true,
                                    'code' => 200,
                                    'message' => 'Clients restored'
                                ]);
    }

}
