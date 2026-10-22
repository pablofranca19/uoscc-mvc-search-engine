<?php

namespace App\Http\Requests;

use Closure;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class RegisterSupporterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() === null;
    }

    protected function prepareForValidation(): void
    {
        if (is_string($this->input('email'))) {
            $this->merge(['email' => Str::lower(trim($this->input('email')))]);
        }
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:3', 'max:120'],
            'email' => ['required', 'string', 'email', 'max:254', 'unique:users,email'],
            'password' => ['required', 'string', 'min:12', 'max:72', 'confirmed', function (string $attribute, mixed $value, Closure $fail): void {
                if (is_string($value) && (strlen($value) > 72 || str_contains($value, "\0"))) {
                    $fail('A senha não é compatível com este ambiente. Use uma senha mais curta e sem caracteres nulos.');
                }
            }],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Informe seu nome.',
            'name.string' => 'Informe um nome válido.',
            'name.min' => 'O nome deve ter pelo menos 3 caracteres.',
            'name.max' => 'Use no máximo 120 caracteres no nome.',
            'email.required' => 'Informe seu e-mail.',
            'email.string' => 'Informe um e-mail válido.',
            'email.email' => 'Informe um e-mail válido.',
            'email.max' => 'Use no máximo 254 caracteres no e-mail.',
            'email.unique' => 'Este e-mail já possui cadastro. Use a opção Entrar.',
            'password.required' => 'Crie uma senha.',
            'password.string' => 'Informe uma senha válida.',
            'password.min' => 'A senha deve ter pelo menos 12 caracteres.',
            'password.max' => 'A senha deve ter no máximo 72 caracteres.',
            'password.confirmed' => 'A confirmação deve ser igual à senha.',
        ];
    }
}
