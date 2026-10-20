<?php

namespace App\Models;

use Database\Factories\ActivityFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Activity extends Model
{
    /** @use HasFactory<ActivityFactory> */
    use HasFactory;

    protected $fillable = ['osc_id', 'tipo', 'titulo', 'data', 'local', 'regiao', 'vagas', 'descricao'];

    public function osc(): BelongsTo
    {
        return $this->belongsTo(Osc::class);
    }
}
