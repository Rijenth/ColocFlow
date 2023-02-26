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
            "data.attributes.name" => "string",
            "data.attributes.access_key" => "string",
            "data.attributes.monthly_rent" => "integer|min:0",
            "data.attributes.max_roomates" => "integer|min:0",
            "data.relationships.roomates" => "array",
            "data.relationships.roomates.data" => "array",
            "data.relationships.roomates.data.id" => "integer|exists:users,id",
            "data.relationships.roomates.data.type" => "string|in:Users",
        ];
    }
}
