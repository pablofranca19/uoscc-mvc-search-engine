<?php

namespace App\Http\Requests;

use App\Enums\UserRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
{
    /**
     * Only an organization manager may create accounts, and the new account is
     * always bound to their own organization, so no one can grant themselves
     * access to another OSC.
     */
    public function authorize(): bool
    {
        return (bool) $this->user()?->isOrganizationManager();
    }

    /**
     * @return array<string, list<mixed>>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'max:100', 'string'],
            'email' => ['required', 'max:100', 'email', Rule::unique('users', 'email')],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', Rule::enum(UserRole::class)->only([UserRole::Supporter, UserRole::OrganizationManager])],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Informe o nome da pessoa.',
            'email.required' => 'Informe o e-mail da pessoa.',
            'email.email' => 'Informe um e-mail válido.',
            'email.unique' => 'Este e-mail já possui uma conta.',
            'password.required' => 'Defina uma senha inicial.',
            'password.min' => 'A senha deve ter pelo menos 8 caracteres.',
            'password.confirmed' => 'A confirmação da senha não corresponde.',
            'role.required' => 'Selecione o tipo de acesso.',
        ];
    }
}
