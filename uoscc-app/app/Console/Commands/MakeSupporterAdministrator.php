<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

#[Signature('apoiadores:administrador {email : E-mail de uma conta existente} {--revogar : Remove a permissão administrativa}')]
#[Description('Define ou remove um administrador da análise de apoiadores neste ambiente local')]
class MakeSupporterAdministrator extends Command
{
    public function handle(): int
    {
        if (! app()->environment('local', 'testing')) {
            $this->error('Este comando está disponível somente no ambiente local.');

            return self::FAILURE;
        }
        $user = User::where('email', Str::lower(trim($this->argument('email'))))->first();
        if (! $user) {
            $this->error('Conta não encontrada. Crie a conta pela página de cadastro antes de executar este comando.');

            return self::FAILURE;
        }
        $user->is_supporter_admin = ! $this->option('revogar');
        $user->save();
        $this->info($user->is_supporter_admin ? 'Permissão de análise concedida. Entre novamente para abrir a área da equipe.' : 'Permissão de análise removida.');

        return self::SUCCESS;
    }
}
