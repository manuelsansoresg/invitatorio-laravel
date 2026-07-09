<?php

namespace App\Http\Controllers;

use App\Models\Confirmacion;
use App\Models\Invitacion;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ConfirmacionController extends Controller
{
    /**
     * Registra la confirmación de asistencia de un invitado.
     *
     * Espera:
     *  - ruta_invitacion : string  (slug de la invitación, p. ej. "xv-valentina")
     *  - nombre          : string  (nombre de quien confirma)
     *
     * Responde:
     *  - JSON { ok: true, mensaje, confirmacion } cuando viene por fetch/AJAX
     *  - redirect()->back() con flash cuando es un envío tradicional
     */
    public function store(Request $request)
    {
        // Validación
        try {
            $data = $request->validate([
                'ruta_invitacion' => ['required', 'string', 'max:120'],
                'nombre'          => ['required', 'string', 'min:3', 'max:120'],
            ]);
        } catch (ValidationException $e) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'ok'     => false,
                    'errors' => $e->errors(),
                ], 422);
            }
            throw $e;
        }

        // Buscar la invitación por ruta
        $invitacion = Invitacion::where('ruta', $data['ruta_invitacion'])->first();

        if (! $invitacion) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'ok'    => false,
                    'error' => 'La invitación no existe o ya no está disponible.',
                ], 404);
            }
            return back()->withErrors([
                'ruta_invitacion' => 'La invitación no existe o ya no está disponible.',
            ])->withInput();
        }

        // Crear la confirmación
        $confirmacion = Confirmacion::create([
            'invitacion_id' => $invitacion->id,
            'nombre'        => trim($data['nombre']),
            'ip'            => $request->ip(),
            'user_agent'    => substr((string) $request->userAgent(), 0, 1000),
        ]);

        // Respuesta
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'ok'           => true,
                'mensaje'      => '¡Gracias por confirmar! Te esperamos.',
                'confirmacion' => [
                    'id'      => $confirmacion->id,
                    'nombre'  => $confirmacion->nombre,
                    'created' => $confirmacion->created_at?->toIso8601String(),
                ],
            ], 201);
        }

        return back()->with('confirmacion_ok', true);
    }
}
