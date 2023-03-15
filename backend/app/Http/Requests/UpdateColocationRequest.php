<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateColocationRequest extends FormRequest
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
            'data.type' => 'required|string|in:colocations',
            'data.id' => 'required|integer|exists:colocations,id',
            'data.attributes.name' => 'string',
            'data.attributes.access_key' => 'string',
            'data.attributes.monthly_rent' => 'integer|min:0',
            'data.attributes.max_roommates' => 'integer|min:1',
            'data.relationships.roommates' => 'array',
            'data.relationships.roommates.data' => 'array',
            'data.relationships.roommates.data.id' => 'integer|exists:users,id',
            'data.relationships.roommates.data.type' => 'string|in:users',
        ];
    }
}
