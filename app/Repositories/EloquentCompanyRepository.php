<?php

namespace App\Repositories;

use App\Models\Company\Company;
use Illuminate\Pagination\LengthAwarePaginator;

class EloquentCompanyRepository implements Contracts\CompanyRepositoryInterface
{

    public function findCompany($id): ?Company
    {
        return Company::find($id);
    }

    public function findWithCountryInfo(int $id): ?Company
    {
        return Company::with([
                                 'country' => function ($query) {
                                     $query->select('id', 'name', 'phone_mask', 'phone_regex');
                                 }
                             ])
            ->withCount('branches')
            ->findOrFail($id);
    }

    public function findWithTrashedAndBranchesInfo(int $id): ?Company
    {
        return Company::withTrashed()->with([
                                                'branches' => function ($query) {
                                                    $query->select(
                                                        'id',
                                                        'name',
                                                        'phone',
                                                        'contact',
                                                        'created_at',
                                                        'company_id'
                                                    );
                                                }
                                            ])->select(
            'id',
            'name',
            'phone',
            'description',
            'contact',
            'info',
            'avatar',
            'created_at'
        )
            ->findOrFail($id);
    }

    public function listWithCountryAndBranchesInfo(int $perPage = 20): LengthAwarePaginator
    {
        return Company::with(['country', 'branches'])
            ->withCount('branches')
            ->paginate($perPage);
    }

    public function listWithCountryInfo(int $perPage = 20): LengthAwarePaginator
    {
        return Company::with(['country'])
            ->paginate($perPage);
    }

    public function listOnlyTrashed(int $perPage = 20): LengthAwarePaginator
    {
        return Company::onlyTrashed()->latest('created_at')->paginate($perPage);
    }

    public function findOrFail(int $id): ?Company
    {
        return Company::findOrFail($id);
    }

    public function withTrashed(int $id): ?Company
    {
        return Company::withTrashed()->findOrFail($id);
    }

    public function onlyTrashed(int $id): ?Company
    {
        return Company::onlyTrashed()->findOrFail($id);
    }


}