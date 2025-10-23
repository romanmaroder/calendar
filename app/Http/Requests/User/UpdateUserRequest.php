<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
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
            'id'=>'required|exists:users,id',
            'avatar'=>'nullable|string',
            'name' => 'required|string|max:255',
            'surname' => 'nullable|string|max:255',
            'middleName' => 'nullable|string|max:255',
            'phone' => 'required|string|max:255',
            'comment' => 'nullable|string|max:255',
            'birthday' => 'nullable|string|max:255',
            'branch_id' => 'nullable|integer',
            'email' => ['string','lowercase','email','max:255',
                Rule::unique('clients')->ignore($this->user)
            ],
            'password' => 'nullable|string|min:6|confirmed',
        ];
    }
}
