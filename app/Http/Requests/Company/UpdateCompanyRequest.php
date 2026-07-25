<?php

namespace App\Http\Requests\Company;

use App\Models\Company\Company;
use App\Models\Country\Country;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class UpdateCompanyRequest extends FormRequest
{

    protected ?Country $country = null;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $countryId = $this->input('country_id');

        if (!$countryId) {
            throw ValidationException::withMessages([
                                                        'country_id' => 'Необходимо указать страну.',
                                                    ]);
        }

        $this->country = Country::find($countryId);

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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
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
            'phone' => ['required', 'string', 'max:50', 'regex:' . $this->country->phone_regex],
        ];

    }
}
