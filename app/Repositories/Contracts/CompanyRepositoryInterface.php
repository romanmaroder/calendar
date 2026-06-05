<?php

namespace App\Repositories\Contracts;

use App\Models\Company\Company;
use Illuminate\Pagination\LengthAwarePaginator;

interface CompanyRepositoryInterface
{
    public function findWithCountryInfo(int $id): ?Company;
    public function listWithCountryInfo(int $perPage = 20): LengthAwarePaginator;
    public function listWithCountryAndBranchesInfo(int $perPage = 20): LengthAwarePaginator;
}