<?php

namespace App\Repositories;

use App\Models\Client;
use Illuminate\Pagination\LengthAwarePaginator;

class EloquentClientRepository implements Contracts\ClientRepositoryInterface
{

    public function find(int $id): ?Client
    {
        return Client::findOrFail($id);
    }
    public function onlyTrashed(int $id): ?Client
    {
        return Client::onlyTrashed()->findOrFail($id);
    }

    public function listOnlyTrashed(int $perPage = 20): LengthAwarePaginator
    {
        return Client::onlyTrashed()->latest('created_at')->paginate($perPage);
    }

    public function countAll(): int
    {
        return Client::count();
    }
}