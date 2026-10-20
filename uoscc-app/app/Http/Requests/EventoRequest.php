<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class EventoRequest extends FormRequest
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
        return ['titulo'=>['required','string','max:255'],'organizacao'=>['required','string','max:255'],'data'=>['required','date'],'hora'=>['required','date_format:H:i'],'local'=>['required','string','max:255'],'regiao'=>['required','string','max:255'],'tipo'=>['required','string','max:255'],'vagas'=>['required','integer','min:1'],'descricao'=>['required','string','max:2000']];
    }
}
