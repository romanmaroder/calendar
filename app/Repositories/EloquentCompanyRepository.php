<?php

namespace App\Repositories;

use App\Models\Company\Company;
use Illuminate\Pagination\LengthAwarePaginator;

class EloquentCompanyRepository implements Contracts\CompanyRepositoryInterface
{

    public function findWithCountryInfo(int $id): ?Company
    {
        return Company::with([
                                 'country' => function ($query) {
                                     $query->select('id', 'name', 'phone_mask', 'phone_regex');
                                 }
                             ])
            ->with(['branches','branches.users'])
            ->withCount('branches')
            ->findOrFail($id);
    }

    public function listWithCountryInfo(int $perPage = 20): LengthAwarePaginator
    {
        return Company::with(['country', 'branches'])
            ->withCount('branches')
            ->paginate($perPage);
    }
}