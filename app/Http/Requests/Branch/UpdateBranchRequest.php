<?php

namespace App\Http\Requests\Branch;

use App\Models\Country\Country;
use App\Services\PhoneContextService;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class UpdateBranchRequest extends FormRequest
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
        $companyId = $this->input('company_id');

        if (!$companyId) {
            throw ValidationException::withMessages([
                                                        'company_id' => 'Необходимо указать компанию.',
                                                    ]);
        }

        $this->country = PhoneContextService::getCountryByCompanyId($companyId);

        if (!$this->country) {
            throw ValidationException::withMessages([
                                                        'company_id' => 'Страна у указанной компании не найдена.',
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
            'name' => 'required|string|min:3|max:250',
            'description' => 'nullable|string|min:3|max:550',
            'contact' => 'required|string|min:3|max:250',
            'avatar' => 'nullable|string',
            'status' => 'boolean',
            'company_id' => 'required|exists:companies,id',
            'phone'=>PhoneContextService::rulesForCountry($this->country),
        ];
    }

    public function messages(): array
    {
        return [
            'company_id.required' => '«Компания» обязательно для заполнения.',
            'name.required' => '«Имя» обязательно для заполнения.',
            'contact.required' => '«Контакты» обязательно для заполнения.',
            'phone.required' => '«Телефон» обязательно для заполнения.',
        ];
    }
}
