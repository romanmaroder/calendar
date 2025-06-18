<?php

namespace App\Http\Requests\Client;

use App\Models\Client;
use Illuminate\Foundation\Http\FormRequest;

class FilterClientRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'id'=>'nullable|integer',
            'name' => 'nullable|string',
            'surname' => 'nullable|string',
            'middleName' => 'nullable|string',
            'phone' => 'nullable|string',
            'email' => 'nullable|string|lowercase',
        ];
    }
}
