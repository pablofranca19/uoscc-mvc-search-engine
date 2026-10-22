<?php

namespace App\Http\Requests;

use App\Enums\FocusArea;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreOrganizationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user()?->hasApprovedSupporterAccess();
    }

    /**
     * @return array<string, list<mixed>>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'description' => ['required', 'string', 'min:10'],
            'contact_email' => ['nullable', 'email', 'max:255'],
            'contact_phone' => ['nullable', 'string', 'max:255'],
            'social_media_links' => ['nullable', 'string', 'max:255'],
            'focus_area' => ['required', Rule::enum(FocusArea::class)],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Informe o nome da organização.',
            'name.min' => 'O nome deve ter pelo menos 3 caracteres.',
            'name.max' => 'O nome deve ter no máximo 255 caracteres.',
            'description.required' => 'Descreva a organização.',
            'description.min' => 'A descrição deve ter pelo menos 10 caracteres.',
            'contact_email.email' => 'Informe um e-mail válido.',
            'focus_area.required' => 'Selecione a área de atuação.',
            'focus_area.Illuminate\\Validation\\Rules\\Enum' => 'Selecione uma área de atuação válida.',
        ];
    }
}
