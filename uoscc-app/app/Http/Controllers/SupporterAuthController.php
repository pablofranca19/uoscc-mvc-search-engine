<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterSupporterRequest;
use App\Models\User;
use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class SupporterAuthController extends Controller
{
    public function create(): View
    {
        return view('apoiadores-login');
    }

    public function registerForm(): View
    {
        return view('apoiadores-cadastro');
    }

    public function register(RegisterSupporterRequest $request): RedirectResponse
    {
        $user = User::create($request->safe()->only(['name', 'email', 'password']));
        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('apoiadores.solicitacao');
    }

    public function store(Request $request): RedirectResponse
    {
        if (is_string($request->input('email'))) {
            $request->merge(['email' => Str::lower(trim($request->input('email')))]);
        }
        $credentials = $request->validate([
            'email' => ['required', 'string', 'email', 'max:254'],
            'password' => ['required', 'string', 'max:72', function (string $attribute, mixed $value, Closure $fail): void {
                if (is_string($value) && (strlen($value) > 72 || str_contains($value, "\0"))) {
                    $fail('Informe uma senha válida de no máximo 72 bytes.');
                }
            }],
        ], [
            'email.required' => 'Informe seu e-mail.',
            'email.email' => 'Informe um e-mail válido.',
            'email.string' => 'Informe um e-mail válido.',
            'email.max' => 'Use no máximo 254 caracteres no e-mail.',
            'password.required' => 'Informe sua senha.',
            'password.string' => 'Informe uma senha válida.',
            'password.max' => 'Informe uma senha de no máximo 72 caracteres.',
        ]);

        if (! Auth::attempt($credentials)) {
            throw ValidationException::withMessages(['email' => 'E-mail ou senha incorretos.']);
        }
        $request->session()->regenerate();

        return redirect()->route('apoiadores.acesso');
    }

    public function access(Request $request): RedirectResponse
    {
        if ($request->user()->is_supporter_admin) {
            return redirect()->route('apoiadores.admin');
        }
        if ($request->user()->hasApprovedSupporterAccess()) {
            return redirect()->route('apoiadores.painel');
        }

        return redirect()->route('apoiadores.solicitacao');
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('apoiadores.login');
    }
}
