<?php

namespace App;

use Illuminate\Support\Carbon;

class ActivityRegistration
{
    public static function status(array $activity): string
    {
        $deadline = Carbon::parse($activity['prazo_inscricao'], 'America/Bahia')->endOfDay();

        if (Carbon::now('America/Bahia')->greaterThan($deadline)) {
            return 'encerrada';
        }

        return $activity['inscritos'] >= $activity['vagas'] ? 'esgotada' : 'aberta';
    }
}
