<?php

namespace App\Models;

use Database\Factories\OscFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        'localizacao',
        'outra_localizacao',
        'email',
        'telefone',
        'redes_sociais',
        'descricao',
    ];

    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class);
    }
}
