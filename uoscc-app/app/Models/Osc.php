<?php

namespace App\Models;

use Database\Factories\OscFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Osc extends Model
{
    /** @use HasFactory<OscFactory> */
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'nome',
        'area_atuacao',
        'email',
        'telefone',
        'redes_sociais',
        'descricao',
    ];
}
