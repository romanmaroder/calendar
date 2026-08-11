<?php

namespace App\Http\Requests\User;

use App\Models\Country\Country;
use App\Models\User;
use App\Rules\PhoneByCountryRegex;
use App\Services\PhoneContextService;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class StoreUserRequest extends FormRequest
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
        $branchId = $this->input('branch_id');

        if (!$branchId) {
            throw ValidationException::withMessages([
                                                        'branch_id' => 'Поле Филиал обязательно для заполнения.',
                                                    ]);
        }

        $this->country = PhoneContextService::getCountryByBranchId($branchId);

        if (!$this->country) {
            throw ValidationException::withMessages([
                                                        'branch_id' => 'Страна у указанного филиала не найдена.',
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
            'avatar' => 'nullable|string',
            'name' => 'required|string|max:255',
            'surname' => 'nullable|string|max:255',
            'middleName' => 'nullable|string|max:255',
            'comment' => 'nullable|string|max:255',
            'birthday' => 'nullable|string',
            'email' => ['nullable','string','lowercase','email','max:255',
                Rule::unique(User::class)->ignore($this->user)
            ],
            'branch_id' => 'required|integer',
            'password' => 'nullable|string|min:8',
            'phone' =>array_merge(PhoneContextService::rulesForCountry($this->country),[Rule::unique(User::class)
                ->ignore($this->user)]),
            'role_ids' => ['sometimes', 'array'],
            'role_ids.*' => ['integer','exists:roles,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'branch_id.required' => '«Филиал» обязательно для заполнения.',
            'name.required' => '«Имя» обязательно для заполнения.',
            'phone.required' => '«Телефон» обязательно для заполнения.',
        ];
    }
}
