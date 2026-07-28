<?php

namespace App\Http\Controllers;

use App\Models\Invitacion;
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

        $template = match ($invitacion->template_key) {
            'instagram' => 'invitaciones.bodas.instagram',
            'xv-mariana' => 'invitaciones.xv.mariana',
            default => 'invitaciones.xv.valeria',
        };

        return view($template, [
            'invitacion' => $invitacion,
        ]);
    }
}
