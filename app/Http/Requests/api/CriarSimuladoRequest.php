<?php

namespace App\Http\Requests\api;

use Illuminate\Foundation\Http\FormRequest;

class CriarSimuladoRequest extends FormRequest
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
            'user' => 'required|integer|exists:users,id',
            'dificuldade' => 'required|string|in:facil,medio,dificil'
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'user.required' => 'O ID do usuário é obrigatório.',
            'user.integer' => 'O ID do usuário deve ser um número.',
            'user.exists' => 'O usuário informado não existe.',
            'dificuldade.required' => 'A dificuldade é obrigatória.',
            'dificuldade.in' => 'A dificuldade deve ser: facil, medio ou dificil.'
        ];
    }
}
