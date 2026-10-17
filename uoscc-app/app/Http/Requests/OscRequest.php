<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OscRequest extends FormRequest
{
    /**
     * Determine whether the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'nome' => ['required', 'string', 'max:255'],
            'area_atuacao' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'telefone' => ['nullable', 'string', 'max:255'],
            'redes_sociais' => ['nullable', 'string', 'max:255'],
            'descricao' => ['nullable', 'string'],
        ];
    }
}
