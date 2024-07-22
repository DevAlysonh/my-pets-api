<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterUserRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users|max:255',
            'password' => 'required|string|min:4|confirmed'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'O campo nome é obrigatório.',
            'email.required' => 'O campo email é obrigatório.',
            'email.email' => 'O email deve ser um email válido.',
            'password.required' => 'O campo password é obrigatório.',
            'email.unique' => 'Este email já está sendo utilizado por outro usuário.',
            'name.max' => 'O nome deve ter no máximo 255 caracteres.',
            'email.max' => 'O email deve ter no máximo 255 caracteres.',
            'password.min' => 'O password deve ter pelo menos 4 caracteres.',
        ];
    }
}
