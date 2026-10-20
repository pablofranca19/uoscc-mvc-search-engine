<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ActivityRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return ['osc_id' => ['required', 'exists:oscs,id'], 'tipo' => ['required', 'in:evento,projeto'], 'titulo' => ['required', 'string', 'max:255'], 'data' => ['required', 'date'], 'local' => ['required', 'string', 'max:255'], 'regiao' => ['nullable', 'string', 'max:255'], 'vagas' => ['required', 'integer', 'min:0'], 'descricao' => ['required', 'string', 'max:2000']];
    }
}
