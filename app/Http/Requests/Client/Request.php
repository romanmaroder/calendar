<?php

namespace App\Http\Requests\Client;

use App\Models\Client;
use Illuminate\Foundation\Http\FormRequest;

class Request extends FormRequest
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
            'avatar'=>'string',
            'name' => 'required|string|max:255',
            'surname' => 'nullable|string|max:255',
            'middleName' => 'nullable|string|max:255',
            'phone' => 'required|string|max:255',
            'comment' => 'nullable|string|max:255',
            'blacklist' => 'nullable|boolean',
            'prepayment' => 'nullable|boolean',
            'discount' => 'nullable|numeric|min:0|max:100',
            'records'=>'nullable|numeric',
            'total'=>'nullable|numeric',
            'source'=>'nullable|string',
            'email' => 'nullable|string|lowercase|email|max:255|unique:'.Client::class,
        ];
    }
}
