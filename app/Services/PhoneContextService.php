<?php

namespace App\Services;

use App\Models\Branch\Branch;
use App\Models\Company\Company;
use App\Models\Country\Country;
use Exception;
use Illuminate\Support\Facades\Auth;

class PhoneContextService
{
    /**
     * Получить страну по ID филиала (для филиалов, сотрудников, любых сущностей с branch_id)
     */
    public static function getCountryByBranchId(int $branchId): Country
    {
        return Branch::with('company.country')
            ->findOrFail($branchId)
            ->company->country;
    }

    /**
     * Получить «основную» страну для клиента: через филиал пользователя и is_primary
     * @throws Exception
     */
    public static function getCountryForClient(): Country
    {
        $user = Auth::user();
        if (!$user || !$user->branch_id) {
            throw new Exception('У пользователя не указан филиал.');
        }

        $primaryCompany = Company::query()
            ->join('branches', 'companies.id', '=', 'branches.company_id')
            ->where('branches.id', $user->branch_id)
            ->where('is_primary', true)
            ->with('country')
            ->first();

        if (!$primaryCompany) {
            throw new Exception('Нет основной компании. Назначьте её.');
        }

        return $primaryCompany->country;
    }

    /**
     * Правила валидации по стране
     */
    public static function rulesForCountry(Country $country): array
    {
        return [
            'phone' => [ 'string', 'max:50', 'regex:' . $country->phone_regex],
            //'phone_secondary' => ['nullable', 'string', 'max:50', 'regex:' . $country->phone_regex],
        ];
    }

    /**
     * Метаданные для фронтенда (маска, код страны)
     */
    public static function metaForCountry(Country $country): array
    {
        return [
            'country_code' => $country->code,
            'phone_mask' => $country->phone_mask,
        ];
    }
}
