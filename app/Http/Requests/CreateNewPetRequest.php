<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateNewPetRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:80',
            'age' => 'required|string',
            'breed' => 'required|string|max:80',
            'specie' => 'required|string|max:80',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'O nome é obrigatório.',
            'age.required' => 'A idade é obrigatória.',
            'breed.required' => 'A raça é obrigatória.',
            'specie.required' => 'A espécie é obrigatória.',
        ];
    }
}
