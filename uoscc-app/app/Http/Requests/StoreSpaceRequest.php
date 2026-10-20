<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSpaceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user()?->isSupporter();
    }

    /**
     * @return array<string, list<mixed>>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'description' => ['required', 'string', 'min:10'],
            'capacity' => ['required', 'integer', 'min:1'],
            'facilities' => ['nullable', 'array'],
            'facilities.*' => ['required', 'string', 'max:255'],
            'location_id' => ['required', 'integer', Rule::exists('locations', 'id')],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Informe o nome do espaço.',
            'name.min' => 'O nome deve ter pelo menos 3 caracteres.',
            'description.required' => 'Descreva o espaço.',
            'description.min' => 'A descrição deve ter pelo menos 10 caracteres.',
            'capacity.required' => 'Informe a capacidade do espaço.',
            'capacity.integer' => 'Informe um número de vagas válido.',
            'capacity.min' => 'A capacidade deve ser de pelo menos 1 pessoa.',
            'facilities.array' => 'Informe a estrutura como uma lista.',
            'facilities.*.string' => 'Informe itens de estrutura válidos.',
            'location_id.required' => 'Selecione a localização do espaço.',
            'location_id.exists' => 'Selecione uma localização válida.',
        ];
    }
}
