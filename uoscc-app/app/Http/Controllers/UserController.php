<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class UserController extends Controller
{
    public function show(string $id): View
    {
        return view('users.show', ['id' => $id]);
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        $user = User::query()->create($request->validated());

        return redirect()
            ->route('users.show', $user->id)
            ->with('success', 'Usuário cadastrado com sucesso.');
    }
}
