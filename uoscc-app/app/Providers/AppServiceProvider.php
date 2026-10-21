<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('review-supporters', fn (User $user): bool => (bool) $user->is_supporter_admin);
        RateLimiter::for('supporter-login', function (Request $request): Limit {
            $email = is_string($request->input('email')) ? Str::lower(trim($request->input('email'))) : '';

            return Limit::perMinute(5)->by(hash('sha256', $email.'|'.$request->ip()))
                ->response(fn () => back()->withErrors(['email' => 'Muitas tentativas. Aguarde um minuto antes de tentar novamente.'])->withInput($request->only('email')));
        });
    }
}
