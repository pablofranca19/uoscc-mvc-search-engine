<?php

namespace App\Support;

use App\Models\User;
use Illuminate\Http\RedirectResponse;

class GuestAuthenticationRedirect
{
    /**
     * Send a guest to sign in when the e-mail already has an account, or to
     * sign up otherwise, returning to the given URL afterwards.
     */
    public static function to(string $returnUrl, string $name, string $email, string $purpose): RedirectResponse
    {
        redirect()->setIntendedUrl($returnUrl);

        if (User::query()->where('email', $email)->exists()) {
            return redirect()
                ->route('login')
                ->withInput(['email' => $email])
                ->with('success', 'Entre na sua conta para concluir '.$purpose.'.');
        }

        return redirect()
            ->route('apoiadores.cadastro')
            ->withInput(['name' => $name, 'email' => $email])
            ->with('success', 'Crie sua conta de apoiador para concluir '.$purpose.'.');
    }
}
