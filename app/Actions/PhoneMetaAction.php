<?php

namespace App\Actions;

use App\Services\PhoneContextService;
use Exception;

class PhoneMetaAction
{
    public static function getByCountryId(?int $countryId): array
    {
        if (!$countryId) {
            return self::defaultMeta();
        }

        $country = PhoneContextService::getCountryForCompany($countryId);
        return PhoneContextService::metaForCountry($country);
    }

    public static function getByCompanyId(?int $companyId): array
    {
        if (!$companyId) {
            return self::defaultMeta();
        }

        $country = PhoneContextService::getCountryByCompanyId($companyId);
        return PhoneContextService::metaForCountry($country);
    }

    /**
     * @throws Exception
     */
    public static function getByBranchId(?int $branchId): array
    {
        if (!$branchId) {
            return self::getForClient();
        }

        $country = PhoneContextService::getCountryByBranchId($branchId);
        return PhoneContextService::metaForCountry($country);
    }


    /**
     * @throws Exception
     */
    public static function getForClient(): array
    {
        $country = PhoneContextService::getCountryForClient();
        return PhoneContextService::metaForCountry($country);
    }

    private static function defaultMeta(): array
    {
        return [
            'phone_mask' => null,
            'phone_regex' => null,
            'country_code' => null,
        ];
    }


}

