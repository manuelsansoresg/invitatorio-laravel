<?php

namespace App\Http\Controllers;

use App\Models\Invitacion;
use App\Models\Invitado;
use App\Models\User;
use Illuminate\View\View;

class InvitacionController extends Controller
{
    public function show(Invitacion $invitacion): View
    {
        $blocksRelation = request()->has('editor_preview') ? 'blocks' : 'activeBlocks';

        $invitacion->load([
            $blocksRelation,
            'gallery' => fn ($query) => $query->where('activo', true)->orderBy('orden'),
        ]);

        // Si la invitación está en borrador, no debe verse públicamente.
        if ($invitacion->estado === 'borrador') {
            abort(404);
        }

        // Si está vencida, mostramos una página especial en vez del
        // render normal del template. La página dice que la invitación
        // caducó y cómo renovarla.
        if ($invitacion->estaVencida()) {
            return view('invitaciones.vencida', [
                'invitacion' => $invitacion,
            ]);
        }

        // Decidir si el paquete del cliente permite gestionar invitados.
        // Si la invitación no tiene cliente asignado, miramos el del
        // admin/último paquete activo (caso de invitaciones demo o
        // creadas sin cliente). El admin siempre pasa (override).
        $cliente = $invitacion->cliente;
        $paquete = $cliente?->paqueteActivo();
        $gestionHabilitada = $paquete?->permite_gestionar_invitados === true;
        $esVistaAdmin = $cliente?->isAdmin() ?? false;

        // Si viene ?invitado_token=X, buscamos al invitado SOLO si el
        // paquete del cliente tiene la gestión de invitados habilitada.
        // Si el paquete NO la tiene, ignoramos el token y dejamos el
        // modal de confirmar como estaba (sin nombre pre-llenado).
        $invitado = null;
        $invitadoToken = request()->query('invitado_token');
        $prefillHabilitado = $gestionHabilitada || $esVistaAdmin;
        if ($prefillHabilitado && is_string($invitadoToken) && $invitadoToken !== '') {
            $invitado = Invitado::where('token', $invitadoToken)
                ->where('invitacion_id', $invitacion->id)
                ->first();
        }

        $template = match ($invitacion->template_key) {
            'instagram' => 'invitaciones.bodas.instagram',
            'xv-mariana' => 'invitaciones.xv.mariana',
            default => 'invitaciones.xv.valeria',
        };

        return view($template, [
            'invitacion'         => $invitacion,
            'invitado'           => $invitado,
            'invitadoToken'      => $invitado?->token,
            'prefillHabilitado'  => $prefillHabilitado,
        ]);
    }
}

