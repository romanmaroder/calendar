<?php

namespace App\Repositories\Contracts;

use App\Models\Branch\Branch;
use Illuminate\Pagination\LengthAwarePaginator;

interface BranchRepositoryInterface
{
    public function findWithCountryInfo(int $id): ?Branch;
    public function listWithCountryInfo(int $perPage = 20): LengthAwarePaginator;
}