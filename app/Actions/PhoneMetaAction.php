<?php

namespace App\Actions;

use App\Services\PhoneContextService;

class PhoneMetaAction
{
    public static function getByCountryId(?int $countryId): array
    {
        if (!$countryId) {
            return [
                'phone_mask' => null,
                'phone_regex' => null,
                'country_code' => null,
            ];
        }

        $country = PhoneContextService::getCountryForCompany($countryId);
        return PhoneContextService::metaForCountry($country);
    }

    public static function getByCompanyId(?int $companyId): array
    {
        if (!$companyId) {
            return [
                'phone_mask' => null,
                'phone_regex' => null,
                'country_code' => null,
            ];
        }

        $country = PhoneContextService::getCountryByCompanyId($companyId);
        return PhoneContextService::metaForCountry($country);
    }

    public static function getByBranchId(?int $branchId): array
    {
        if (!$branchId) {
            return [
                'phone_mask' => null,
                'phone_regex' => null,
                'country_code' => null,
            ];
        }

        $country = PhoneContextService::getCountryByBranchId($branchId);
        return PhoneContextService::metaForCountry($country);
    }

}

