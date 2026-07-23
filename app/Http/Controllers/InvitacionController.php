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

        $template = match ($invitacion->template_key) {
            'xv-mariana' => 'invitaciones.xv.mariana',
            default => 'invitaciones.xv.valeria',
        };

        return view($template, [
            'invitacion' => $invitacion,
        ]);
    }
}
