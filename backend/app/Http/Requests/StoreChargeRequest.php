<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreChargeRequest extends FormRequest
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
            'data.type' => 'required|string|in:charges',
            'data.attributes' => 'array|required_with:data',
            'data.attributes.amount' => 'numeric|min:0|required_with:data.attributes',
            'data.attributes.name' => 'string|required_with:data.attributes|unique:charges,name',
        ];
    }
}
