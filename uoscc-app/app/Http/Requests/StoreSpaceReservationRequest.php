<?php

namespace App\Http\Requests;

use App\Models\Space;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;

/**
 * Validates the public (demo) space reservation form.
 *
 * Holds the validation + authorization responsibility for
 * SpaceReservationController@store, keeping the controller focused on the
 * response.
 */
class StoreSpaceReservationRequest extends FormRequest
{
    /**
     * The reservation form is public (consulta pública), so anyone may submit it.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Ensure a request for an unknown space results in a 404 instead of
     * validation errors, matching the public route's not-found behavior.
     */
    protected function prepareForValidation(): void
    {
        abort_unless(
            Space::query()->whereKey($this->route('id'))->exists(),
            404,
        );
    }

    /**
     * @return array<string, list<string>>
     */
    public function rules(): array
    {
        return [
            'nome' => ['required', 'string', 'min:3', 'max:120'],
            'email' => ['required', 'email', 'max:254'],
            'data' => ['required', 'date_format:Y-m-d', 'after_or_equal:'.Carbon::now('America/Bahia')->toDateString()],
            'finalidade' => ['required', 'string', 'min:10', 'max:1000'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'nome.required' => 'Informe seu nome.',
            'nome.string' => 'Informe um nome válido.',
            'nome.min' => 'O nome deve ter pelo menos 3 caracteres.',
            'nome.max' => 'O nome deve ter no máximo 120 caracteres.',
            'email.required' => 'Informe seu e-mail.',
            'email.email' => 'Informe um e-mail válido.',
            'email.max' => 'O e-mail deve ter no máximo 254 caracteres.',
            'data.required' => 'Escolha a data desejada.',
            'data.date_format' => 'Informe uma data válida.',
            'data.after_or_equal' => 'Escolha hoje ou uma data futura.',
            'finalidade.required' => 'Descreva a finalidade da atividade.',
            'finalidade.string' => 'Informe uma descrição válida.',
            'finalidade.min' => 'Descreva a finalidade com pelo menos 10 caracteres.',
            'finalidade.max' => 'Use no máximo 1000 caracteres para a finalidade.',
        ];
    }
}
