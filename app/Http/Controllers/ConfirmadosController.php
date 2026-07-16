<?php

namespace App\Http\Controllers;

use App\Models\Confirmacion;
use App\Models\Invitacion;
use App\Models\User;
use App\Support\ConfirmadosPdf;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class ConfirmadosController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $confirmaciones = $this->confirmacionesVisibles($user)
            ->latest()
            ->get();

        $invitaciones = $this->invitacionesVisibles($user)
            ->withCount('confirmaciones')
            ->orderBy('nombre')
            ->get();

        return view('panel.confirmados.index', [
            'confirmaciones' => $confirmaciones,
            'invitaciones' => $invitaciones,
            'isAdmin' => $user->isAdmin(),
        ]);
    }

    public function destroy(Request $request, Confirmacion $confirmacion): RedirectResponse
    {
        $deleted = $this->confirmacionesVisibles($request->user())
            ->whereKey($confirmacion->id)
            ->delete();

        if ($deleted === 0) {
            abort(Response::HTTP_FORBIDDEN);
        }

        return redirect()
            ->route('panel.confirmados.index')
            ->with('status', 'Confirmación eliminada correctamente.');
    }

    public function destroySelected(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'confirmaciones' => ['required', 'array', 'min:1'],
            'confirmaciones.*' => ['integer'],
        ], [
            'confirmaciones.required' => 'Selecciona al menos una confirmación para borrar.',
        ]);

        $deleted = $this->confirmacionesVisibles($request->user())
            ->whereKey($validated['confirmaciones'])
            ->delete();

        return redirect()
            ->route('panel.confirmados.index')
            ->with('status', $deleted.' confirmación(es) eliminada(s).');
    }

    public function exportPdf(Request $request): Response
    {
        $user = $request->user();

        $confirmaciones = $this->confirmacionesVisibles($user)
            ->oldest()
            ->get();

        $pdf = ConfirmadosPdf::make(
            confirmaciones: $confirmaciones,
            titulo: $user->isAdmin() ? 'Lista general de confirmados' : 'Lista de confirmados',
            subtitulo: $user->isAdmin() ? 'Todas las invitaciones' : 'Invitaciones asignadas a tu usuario',
        );

        return response($pdf, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="confirmados.pdf"',
        ]);
    }

    private function confirmacionesVisibles(User $user): Builder
    {
        return Confirmacion::query()
            ->with('invitacion')
            ->whereHas('invitacion', fn (Builder $query) => $this->aplicarFiltroInvitaciones($query, $user));
    }

    private function invitacionesVisibles(User $user): Builder
    {
        return Invitacion::query()
            ->when(! $user->isAdmin(), fn (Builder $query) => $this->aplicarFiltroInvitaciones($query, $user));
    }

    private function aplicarFiltroInvitaciones(Builder $query, User $user): Builder
    {
        if ($user->isAdmin()) {
            return $query;
        }

        return $query->where('user_id', $user->id);
    }
}
