<?php

namespace App\Repositories\Contracts;

use App\Models\Branch\Branch;
use Illuminate\Pagination\LengthAwarePaginator;

interface BranchRepositoryInterface
{
    public function findWithUsers(int $id): ?Branch;
    public function listWithCompanyAndUserInfo(int $perPage = 20): LengthAwarePaginator;
}