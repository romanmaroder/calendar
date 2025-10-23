<?php

namespace App\Http\Requests\User;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
            'avatar'=>'nullable|string',
            'name' => 'required|string|max:255',
            'surname' => 'nullable|string|max:255',
            'middleName' => 'nullable|string|max:255',
            'phone' => 'required|string|max:255|unique:'.User::class,
            'comment' => 'nullable|string|max:255',
            'birthday'=>'nullable|string',
            'email' => 'nullable|string|lowercase|email|max:255|unique:'.User::class,
            'branch_id' => 'nullable|integer',
        ];
    }
}
