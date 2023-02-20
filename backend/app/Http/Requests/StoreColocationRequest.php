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
            "data.attributes.name" => ["required", "string", "max:255"],
            "data.attributes.access_key" => ["required", "string", "max:255", "unique:colocations,access_key"],
            "data.attributes.monthly_rent" => ["required", "numeric", "min:0"],
            "data.attributes.max_roomates" => ["required", "numeric", "min:0"],
        ];
    }
}
