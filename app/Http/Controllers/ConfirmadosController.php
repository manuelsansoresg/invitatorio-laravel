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

    /**
     * Confirmados de UNA invitación + gestión de invitados.
     *
     * El botón "Confirmados" del admin dashboard apunta aquí, no a la
     * lista global, porque los confirmados van ligados a una invitación.
     * Además esta pantalla integra la gestión de invitados
     * (lista con link único de WhatsApp/FB) si el paquete del cliente
     * tiene permite_gestionar_invitados=true. El admin siempre pasa.
     */
    public function indexForInvitacion(Request $request, Invitacion $invitacion): View
    {
        $this->autorizarAcceso($request->user(), $invitacion);

        $user = $request->user();
        $paquete = $user->paqueteActivo();
        $permiteInvitados = $user->isAdmin() || ($paquete?->permite_gestionar_invitados === true);

        $confirmaciones = $invitacion->confirmaciones()->latest()->get();

        $invitados = $permiteInvitados
            ? $invitacion->invitados()
                ->orderByRaw("FIELD(estado, 'pendiente', 'confirmado', 'no_asistira')")
                ->orderBy('nombre')
                ->get()
            : collect();

        $totalesInvitados = [
            'total'           => $invitados->count(),
            'pendientes'      => $invitados->where('estado', 'pendiente')->count(),
            'confirmados'     => $invitados->where('estado', 'confirmado')->count(),
            'rechazados'      => $invitados->where('estado', 'no_asistira')->count(),
            'lugares_asignados'   => $invitados->sum('lugares_asignados'),
            'lugares_confirmados' => $invitados->where('estado', 'confirmado')->sum('lugares_confirmados'),
        ];

        return view('panel.confirmados.por-invitacion', [
            'invitacion'        => $invitacion,
            'confirmaciones'    => $confirmaciones,
            'invitados'         => $invitados,
            'permiteInvitados'  => $permiteInvitados,
            'paquete'           => $paquete,
            'totalesInvitados'  => $totalesInvitados,
            'isAdmin'           => $user->isAdmin(),
        ]);
    }

    private function autorizarAcceso(User $user, Invitacion $invitacion): void
    {
        if ($user->isAdmin()) {
            return;
        }
        if ((int) $invitacion->user_id !== (int) $user->id) {
            abort(Response::HTTP_FORBIDDEN);
        }
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
