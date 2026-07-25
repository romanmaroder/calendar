<?php

namespace App\Http\Requests\Company;

use App\Models\Company\Company;
use App\Models\Country\Country;
use App\Services\PhoneContextService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class StoreCompanyRequest extends FormRequest
{
    protected ?Country $country = null;

    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $countryId = $this->input('country_id');

        if (!$countryId) {
            throw ValidationException::withMessages([
                                                        'country_id' => 'Поле Страна обязательно для заполнения.',
                                                    ]);
        }

        $this->country = PhoneContextService::getCountryForCompany($countryId);

        if (!$this->country) {
            throw ValidationException::withMessages([
                                                        'country_id' => 'Указанная страна не найдена.',
                                                    ]);
        }

        // Проверка на наличие другой основной компании — именно здесь
        if ($this->boolean('is_primary') && Company::where('is_primary', true)->exists()) {
            throw ValidationException::withMessages([
                                                        'is_primary' => 'Сначала удалите или снимите флаг "Основная" с существующей компании.',
                                                    ]);
        }
    }

    public function rules(): array
    {
        return [
            'country_id' => 'required|exists:countries,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'contact' => 'required|string|max:255',
            'info' => 'nullable|string|max:500',
            'avatar' => 'nullable|string',
            'is_primary' => 'nullable|boolean',
            'phone'=>PhoneContextService::rulesForCountry($this->country),
        ];
    }
}
