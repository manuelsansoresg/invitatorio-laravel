<?php

namespace App\Http\Controllers;

use App\Models\Invitado;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Controller público (sin auth) para que el invitado confirme su
 * asistencia desde su link único: /c/{token}
 *
 * La vista muestra el nombre del invitado, cuántos lugares tiene
 * asignados, y le permite elegir cuántos van (entre 0 = no asistirá,
 * y N = los lugares máximos).
 *
 * Re-confirmación permitida — el invitado puede cambiar su respuesta
 * las veces que necesite con el mismo link.
 */
class AsistenciaController extends Controller
{
    public function show(string $token): View
    {
        $invitado = $this->buscarInvitado($token);

        return view('confirmar.show', [
            'invitado'   => $invitado,
            'invitacion' => $invitado->invitacion,
        ]);
    }

    public function confirmar(Request $request, string $token): RedirectResponse
    {
        $invitado = $this->buscarInvitado($token);

        $data = $request->validate([
            'lugares'  => ['required', 'integer', 'min:0', 'max:' . $invitado->lugares_asignados],
            'comentario' => ['nullable', 'string', 'max:500'],
        ], [
            'lugares.required' => 'Selecciona cuántos van.',
            'lugares.max'      => "Solo tienes {$invitado->lugares_asignados} lugar(es) asignados.",
            'lugares.min'      => 'Si no vas a asistir, indícalo explícitamente abajo.',
        ]);

        $lugares = (int) $data['lugares'];

        if ($lugares === 0) {
            $invitado->update([
                'estado'              => 'no_asistira',
                'lugares_confirmados' => 0,
                'confirmado_at'       => now(),
                'ip'                  => $request->ip(),
                'user_agent'          => substr((string) $request->userAgent(), 0, 255),
            ]);
            return redirect()
                ->route('confirmar.gracias', $invitado->token)
                ->with('status', 'Gracias por avisar. Te esperamos en otra ocasión.');
        }

        $invitado->update([
            'estado'              => 'confirmado',
            'lugares_confirmados' => $lugares,
            'confirmado_at'       => now(),
            'ip'                  => $request->ip(),
            'user_agent'          => substr((string) $request->userAgent(), 0, 255),
        ]);

        return redirect()
            ->route('confirmar.gracias', $invitado->token)
            ->with('status', "¡Listo! {$lugares} lugar(es) confirmado(s).");
    }

    public function gracias(string $token): View
    {
        $invitado = $this->buscarInvitado($token);
        return view('confirmar.gracias', [
            'invitado'   => $invitado,
            'invitacion' => $invitado->invitacion,
        ]);
    }

    private function buscarInvitado(string $token): Invitado
    {
        $invitado = Invitado::where('token', $token)->first();

        if (! $invitado) {
            abort(404, 'Link inválido o expirado.');
        }

        return $invitado->loadMissing('invitacion');
    }
}
