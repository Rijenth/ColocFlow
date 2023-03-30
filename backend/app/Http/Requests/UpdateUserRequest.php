<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'data' => 'required|array',
            'data.type' => 'required|string|in:users',
            'data.id' => 'required|integer|exists:users,id',
            'data.attributes' => 'array',
            'data.attributes.email' => 'unique:users,email|email',
            'data.attributes.password' => 'string|min:5',
            'data.attributes.old_password' => 'string|min:4',
            'data.attributes.firstname' => 'string',
            'data.attributes.lastname' => 'string',
        ];
    }
}
