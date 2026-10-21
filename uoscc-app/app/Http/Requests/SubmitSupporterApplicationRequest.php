<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubmitSupporterApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'applicant_type' => ['required', 'in:person,organization'],
            'organization_name' => ['exclude_unless:applicant_type,organization', 'required', 'string', 'max:160'],
            'interests' => ['required', 'string', 'min:3', 'max:500'],
            'contribution' => ['required', 'string', 'min:20', 'max:2000'],
        ];
    }

    public function messages(): array
    {
        return [
            'applicant_type.required' => 'Escolha como pretende participar.',
            'applicant_type.in' => 'Escolha pessoa ou organização.',
            'organization_name.required' => 'Informe o nome da organização que representa.',
            'organization_name.string' => 'Informe um nome de organização válido.',
            'organization_name.max' => 'Use no máximo 160 caracteres no nome da organização.',
            'interests.required' => 'Informe suas áreas de interesse.',
            'interests.string' => 'Informe interesses válidos.',
            'interests.min' => 'Descreva seus interesses com pelo menos 3 caracteres.',
            'interests.max' => 'Use no máximo 500 caracteres nos interesses.',
            'contribution.required' => 'Conte como pretende contribuir.',
            'contribution.string' => 'Informe uma descrição válida.',
            'contribution.min' => 'Descreva sua contribuição com pelo menos 20 caracteres.',
            'contribution.max' => 'Use no máximo 2000 caracteres na descrição.',
        ];
    }
}
