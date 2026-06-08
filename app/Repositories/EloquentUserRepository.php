<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class EloquentUserRepository implements Contracts\UserRepositoryInterface
{

    public function find(int $id): ?User
    {
        return User::findOrFail($id);
    }
    /**
     * @inheritDoc
     */
    public function getUserIdsInBranch(array $userIds, int $branchId): array
    {
        return User::query()
            ->whereIn('id', $userIds)
            ->where('branch_id', $branchId)
            ->pluck('id')
            ->toArray();
    }

    /**
     * @inheritDoc
     */
    public function unsubscribeFromBranch(array $userIds): int
    {
        return User::query()
            ->whereIn('id', $userIds)
            ->update(['branch_id' => null]);
    }

    public function findWithBranchInfo(int $id): ?User
    {
       return User::with(['branch'])->findOrFail($id);
    }
    public function findWithTrashedAndBranchInfo(int $id): ?User
    {
        return User::withTrashed()->with(['branch'])->findOrFail($id);
    }

    public function listWithBranchInfo(int $perPage = 20): LengthAwarePaginator
    {
        return User::with(['branch'])->paginate($perPage);
    }

    public function listOnlyTrashed(int $perPage = 20): LengthAwarePaginator
    {
        return User::onlyTrashed()->latest('created_at')->paginate($perPage);
    }

    public function countAll(): int
    {
        return User::count();
    }
}