<?php

namespace App\Http\Requests;

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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'data.attributes.username' => 'required|string|unique:users,username',
            'data.attributes.password' => 'required|string',
            'data.attributes.firstname' => 'required|string',
            'data.attributes.lastname' => 'required|string',
        ];
    }
}
