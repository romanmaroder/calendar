<?php

namespace App\Http\Requests\Client;

use App\Models\Client;
use App\Models\Country\Country;
use App\Services\PhoneContextService;
use Exception;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class StoreClientRequest extends FormRequest
{

    protected ?Country $country = null;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }


    /**
     * @throws Exception
     */
    protected function prepareForValidation(): void
    {
        $this->country = PhoneContextService::getCountryForClient();
        if (!$this->country) {
            throw ValidationException::withMessages([
                                                        'phone' => 'Страна у указанного филиала не найдена.',
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
            'blacklist' => 'nullable|boolean',
            'prepayment' => 'nullable|boolean',
            'discount' => 'nullable|numeric|min:0|max:100',
            'records' => 'nullable|numeric',
            'total' => 'nullable|numeric',
            'source' => 'nullable|string',
            'birthday' => 'nullable|string',
            'email' => 'required|string|lowercase|email|max:255|unique:' . Client::class,
            'password' => 'required|string|min:8',
            'phone' =>array_merge(PhoneContextService::rulesForCountry($this->country),[Rule::unique(Client::class)
                ->ignore($this->client)]),
        ];
    }
}
