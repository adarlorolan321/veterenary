<?php

namespace App\Http\Requests\Medicalrecord;

use Illuminate\Foundation\Http\FormRequest;

class StoreMedicalRecordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can("store medical_record");
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            "animal_id" => ["required"],"vet_id" => ["required"],"record_date" => ["required"],"diagnosis" => ["required"],"treatment" => ["required"],"notes" => ["required"],
        ];
    }
}
