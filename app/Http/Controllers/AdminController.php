<?php

namespace App\Http\Controllers;

use App\Models\Invitacion;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function dashboard(): View
    {
        $invitaciones = Invitacion::query()
            ->with('cliente')
            ->withCount('confirmaciones')
            ->latest()
            ->get();

        $users = User::query()
            ->latest()
            ->get();

        $clientes = User::query()
            ->where('role', User::ROLE_CLIENT)
            ->orderBy('name')
            ->get();

        return view('admin.dashboard', [
            'clientes' => $clientes,
            'invitaciones' => $invitaciones,
            'roles' => User::roles(),
            'users' => $users,
        ]);
    }

    public function storeUser(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', Rule::in(User::roles())],
        ]);

        User::create($validated);

        return redirect()
            ->route('admin.dashboard')
            ->with('status', 'Usuario creado correctamente.');
    }

    public function updateInvitationClient(Request $request, Invitacion $invitacion): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => [
                'nullable',
                Rule::exists('users', 'id')->where('role', User::ROLE_CLIENT),
            ],
        ]);

        $invitacion->update([
            'user_id' => $validated['user_id'] ?? null,
        ]);

        return redirect()
            ->route('admin.dashboard')
            ->with('status', 'Cliente asignado correctamente.');
    }
}
