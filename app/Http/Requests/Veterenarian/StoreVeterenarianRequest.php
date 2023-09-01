<?php

namespace App\Http\Requests\Veterenarian;

use Illuminate\Foundation\Http\FormRequest;

class StoreVeterenarianRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can("store veterenarian");
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            "first_name" => ["required"],"last_name" => ["required"],"email" => ["required"],"phone_number" => ["required"],"specialization" => ["required"],
        ];
    }
}
