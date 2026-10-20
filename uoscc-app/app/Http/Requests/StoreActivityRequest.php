<?php

namespace App\Http\Requests;

use App\Enums\ActivityType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;

class StoreActivityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user()?->isOrganizationManager();
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'activity_date' => $this->normalizeBrazilianDate($this->input('activity_date')),
            'registration_deadline' => $this->normalizeBrazilianDate($this->input('registration_deadline')),
        ]);
    }

    /**
     * @return array<string, list<mixed>>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'min:3', 'max:255'],
            'description' => ['required', 'string', 'min:10'],
            'activity_date' => ['required', 'date_format:Y-m-d'],
            'space_id' => ['nullable', 'integer', Rule::exists('spaces', 'id')],
            'activity_type' => ['required', Rule::enum(ActivityType::class)],
            'capacity' => ['nullable', 'integer', 'min:1'],
            'registration_deadline' => ['nullable', 'date_format:Y-m-d', 'before_or_equal:activity_date'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Informe o título da atividade.',
            'title.min' => 'O título deve ter pelo menos 3 caracteres.',
            'description.required' => 'Descreva a atividade.',
            'description.min' => 'A descrição deve ter pelo menos 10 caracteres.',
            'activity_date.required' => 'Informe a data da atividade.',
            'activity_date.date_format' => 'Informe uma data válida.',
            'space_id.exists' => 'Selecione um espaço válido.',
            'activity_type.required' => 'Selecione o tipo da atividade.',
            'activity_type.Illuminate\\Validation\\Rules\\Enum' => 'Selecione um tipo de atividade válido.',
            'capacity.integer' => 'Informe um número de vagas válido.',
            'capacity.min' => 'A capacidade deve ser de pelo menos 1 vaga.',
            'registration_deadline.date_format' => 'Informe um prazo de inscrição válido.',
            'registration_deadline.before_or_equal' => 'O prazo de inscrição deve ser até a data da atividade.',
        ];
    }

    private function normalizeBrazilianDate(mixed $date): mixed
    {
        if (! is_string($date) || ! Carbon::canBeCreatedFromFormat($date, 'd/m/Y')) {
            return $date;
        }

        return Carbon::createFromFormat('!d/m/Y', $date)->toDateString();
    }
}
