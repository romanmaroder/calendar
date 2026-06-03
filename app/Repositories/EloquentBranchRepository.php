<?php

namespace App\Repositories;

use App\Models\Branch\Branch;
use App\Models\Country\Country;
use Illuminate\Pagination\LengthAwarePaginator;

class EloquentBranchRepository implements Contracts\BranchRepositoryInterface
{

    public function findWithCountryInfo(int $id): ?Branch
    {
        return Branch::addSelect([
                                     'country_id' => Country::select('countries.id')
                                         ->join('companies', 'countries.id', '=', 'companies.country_id')
                                         ->whereColumn('companies.id', 'branches.company_id')
                                         ->limit(1),
                                     'country_name' => Country::select('countries.name')
                                         ->join('companies', 'countries.id', '=', 'companies.country_id')
                                         ->whereColumn('companies.id', 'branches.company_id')
                                         ->limit(1),
                                     'phone_regex' => Country::select('countries.phone_regex')
                                         ->join('companies', 'countries.id', '=', 'companies.country_id')
                                         ->whereColumn('companies.id', 'branches.company_id')
                                         ->limit(1),
                                     'phone_mask' => Country::select('countries.phone_mask')
                                         ->join('companies', 'countries.id', '=', 'companies.country_id')
                                         ->whereColumn('companies.id', 'branches.company_id')
                                         ->limit(1)
                                 ])
            ->with('users')
            ->withCount('users')
            ->findOrFail($id);
    }

    public function listWithCountryInfo(int $perPage = 20): LengthAwarePaginator
    {
        return Branch::addSelect([
                                     'country_id' => Country::select('countries.id')
                                         ->join('companies', 'countries.id', '=', 'companies.country_id')
                                         ->whereColumn('companies.id', 'branches.company_id')
                                         ->limit(1),
                                     'phone_regex' => Country::select('countries.phone_regex')
                                         ->join('companies', 'countries.id', '=', 'companies.country_id')
                                         ->whereColumn('companies.id', 'branches.company_id')
                                         ->limit(1),
                                     'country_name' => Country::select('countries.name')
                                         ->join('companies', 'countries.id', '=', 'companies.country_id')
                                         ->whereColumn('companies.id', 'branches.company_id')
                                         ->limit(1),
                                     'phone_mask' => Country::select('countries.phone_mask')
                                         ->join('companies', 'countries.id', '=', 'companies.country_id')
                                         ->whereColumn('companies.id', 'branches.company_id')
                                         ->limit(1)
                                 ])
            ->with('users')
            ->withCount('users')
            ->paginate($perPage);
    }
}