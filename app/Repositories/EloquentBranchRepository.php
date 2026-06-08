<?php

namespace App\Repositories;

use App\Models\Branch\Branch;
use App\Models\Country\Country;
use Illuminate\Pagination\LengthAwarePaginator;

class EloquentBranchRepository implements Contracts\BranchRepositoryInterface
{

    public function find(int $id): ?Branch
    {
        return Branch::findOrFail($id);
    }

    public function findWithUsers(int $id): ?Branch
    {
        return Branch::with('users')->withCount('users')->findOrFail($id);
    }

    public function findWithTrashedAndUsersInfo(int $id): ?Branch
    {
        return Branch::withTrashed()->with('users')->withCount('users')->findOrFail($id);
    }


    public function listWithCompanyAndUserInfo(int $perPage = 20): LengthAwarePaginator
    {
        return Branch::with(['users', 'company'])->withCount('users')->paginate($perPage);
    }

    public function listOnlyTrashed(int $perPage = 20): LengthAwarePaginator
    {
        return Branch::onlyTrashed()->latest('created_at')->paginate($perPage);
    }
}