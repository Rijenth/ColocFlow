<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateChargeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->charge->colocation->owner->is($this->user()) || $this->charge->colocation->roommates->contains($this->user());
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'data' => 'array',
            'data.type' => 'required|string|in:charges',
            'data.id' => 'required|integer|exists:charges,id',
            'data.attributes' => 'array',
            'data.attributes.amount' => 'numeric|min:0|required_with:data.attributes',
            'data.attributes.name' => 'string',
            'data.relationships' => 'array',
            'data.relationships.users' => 'array',
            'data.relationships.users.data' => 'array',
            'data.relationships.users.data.id' => 'integer|exists:users,id',
            'data.relationships.users.data.type' => 'string|in:users',
            'data.relationships.users.data.attributes' => 'array|required_with:data.relationships.users.data',
            'data.relationships.users.data.attributes.amount' => 'numeric|min:0|required_with:data.relationships.users.data.attributes',
        ];
    }
}
