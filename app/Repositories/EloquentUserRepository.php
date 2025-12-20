<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;

class EloquentUserRepository implements Contracts\UserRepositoryInterface
{

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
}