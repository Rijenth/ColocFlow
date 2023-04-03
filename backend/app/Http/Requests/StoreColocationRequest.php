<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreColocationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->owner()->exists() === false && $this->user()->colocation_id === null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'data.type' => 'required|string|in:colocations',
            'data.attributes' => 'array',
            'data.attributes.name' => ['required', 'string', 'max:255', 'unique:colocations,name'],
            'data.attributes.access_key' => ['required', 'string', 'min:4'],
            'data.attributes.monthly_rent' => ['required', 'numeric', 'min:0'],
            'data.attributes.max_roommates' => ['required', 'numeric', 'min:1'],
            'data.attributes.charges' => ['array'],
            'data.attributes.charges.*.name' => [
                'required_with:data.attributes.charges',
                'string',
            ],
            'data.attributes.charges.*.amount' => [
                'required_with:data.attributes.charges',
                'numeric',
                'min:0',
            ],
        ];
    }
}
