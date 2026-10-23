<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Account page (users.show). A user may read their own account; an
     * organization manager may also read the accounts of their own OSC.
     */
    public function show(Request $request, int $id): View
    {
        $user = User::query()->with('organization')->findOrFail($id);
        $viewer = $request->user();

        abort_unless(
            $viewer->is($user) || $this->managesSameOrganization($viewer, $user),
            403,
        );

        return view('users.show', ['usuario' => $user]);
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        $user = User::query()->create([
            ...$request->validated(),
            'organization_id' => $request->user()->organization_id,
        ]);

        return redirect()
            ->route('users.show', $user->id)
            ->with('success', 'Usuário cadastrado com sucesso.');
    }

    /**
     * Whether the viewer manages the organization the account belongs to.
     */
    private function managesSameOrganization(User $viewer, User $user): bool
    {
        return $viewer->isOrganizationManager()
            && $viewer->organization_id !== null
            && $viewer->organization_id === $user->organization_id;
    }
}
