<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Invitacion;
use App\Models\Invitado;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class InvitadoController extends Controller
{
    /**
     * Lista de invitados de una invitación. Es la pantalla principal
     * del organizador: ve su lista, estado de cada uno, puede agregar,
     * editar, borrar, copiar link y compartir por WhatsApp/FB.
     */
    public function index(Request $request, Invitacion $invitacion): View
    {
        $this->autorizar($request->user(), $invitacion);

        $invitados = $invitacion->invitados()
            ->orderByRaw("FIELD(estado, 'pendiente', 'confirmado', 'no_asistira')")
            ->orderBy('nombre')
            ->get();

        // Métricas para la cabecera
        $totales = [
            'total'           => $invitados->count(),
            'pendientes'      => $invitados->where('estado', 'pendiente')->count(),
            'confirmados'     => $invitados->where('estado', 'confirmado')->count(),
            'rechazados'      => $invitados->where('estado', 'no_asistira')->count(),
            'lugares_asignados'   => $invitados->sum('lugares_asignados'),
            'lugares_confirmados' => $invitados->where('estado', 'confirmado')->sum('lugares_confirmados'),
        ];

        return view('panel.invitados.index', [
            'invitacion' => $invitacion,
            'invitados'  => $invitados,
            'totales'    => $totales,
        ]);
    }

    public function create(Request $request, Invitacion $invitacion): View
    {
        $this->autorizar($request->user(), $invitacion);
        return view('panel.invitados.create', ['invitacion' => $invitacion]);
    }

    public function store(Request $request, Invitacion $invitacion): RedirectResponse
    {
        $this->autorizar($request->user(), $invitacion);

        $data = $this->validarInvitado($request);

        $invitacion->invitados()->create($data);

        return redirect()
            ->route('panel.invitados.index', $invitacion)
            ->with('status', 'Invitado agregado. Comparte su link para que confirme.');
    }

    public function edit(Request $request, Invitacion $invitacion, Invitado $invitado): View
    {
        $this->autorizar($request->user(), $invitacion);
        $this->asegurarMismaInvitacion($invitado, $invitacion);

        return view('panel.invitados.edit', [
            'invitacion' => $invitacion,
            'invitado'   => $invitado,
        ]);
    }

    public function update(Request $request, Invitacion $invitacion, Invitado $invitado): RedirectResponse
    {
        $this->autorizar($request->user(), $invitacion);
        $this->asegurarMismaInvitacion($invitado, $invitacion);

        $data = $this->validarInvitado($request);

        // Si baja lugares_asignados por debajo de lugares_confirmados, no
        // permitimos — el cliente debe re-confirmar primero.
        if (array_key_exists('lugares_asignados', $data)
            && $invitado->lugares_confirmados !== null
            && $data['lugares_asignados'] < $invitado->lugares_confirmados) {
            return back()
                ->withInput()
                ->withErrors([
                    'lugares_asignados' => "No puedes bajar a {$data['lugares_asignados']} lugares: este invitado ya confirmó {$invitado->lugares_confirmados}. Pídele que re-confirme o elimina la confirmación primero.",
                ]);
        }

        $invitado->update($data);

        return redirect()
            ->route('panel.invitados.index', $invitacion)
            ->with('status', 'Invitado actualizado.');
    }

    public function destroy(Request $request, Invitacion $invitacion, Invitado $invitado): RedirectResponse
    {
        $this->autorizar($request->user(), $invitacion);
        $this->asegurarMismaInvitacion($invitado, $invitacion);

        $invitado->delete();

        return redirect()
            ->route('panel.invitados.index', $invitacion)
            ->with('status', 'Invitado eliminado de la lista.');
    }

    /**
     * Alta masiva: pega una lista tipo:
     *   Manuel Sansores, 3
     *   Pablo Manzanero, 2
     *   Familia Arceo, 5
     *   María López
     * (línea vacía o con solo nombre → 1 lugar por defecto)
     */
    public function storeBulk(Request $request, Invitacion $invitacion): RedirectResponse
    {
        $this->autorizar($request->user(), $invitacion);

        $validated = $request->validate([
            'texto' => ['required', 'string', 'min:2', 'max:10000'],
        ], [
            'texto.required' => 'Pega al menos un invitado.',
        ]);

        $lineas = $this->parsearLista(($validated['texto']));

        if (empty($lineas)) {
            return back()
                ->withInput()
                ->withErrors(['texto' => 'No pude leer ningún invitado. Usa el formato: Nombre, lugares (uno por línea).']);
        }

        $creados = 0;
        $errores = [];

        DB::transaction(function () use ($lineas, $invitacion, &$creados, &$errores) {
            foreach ($lineas as $idx => $linea) {
                if ($linea['nombre'] === '') {
                    $errores[] = "Línea " . ($idx + 1) . ": nombre vacío.";
                    continue;
                }
                if ($linea['lugares'] < 1 || $linea['lugares'] > 50) {
                    $errores[] = "Línea " . ($idx + 1) . ": lugares debe ser entre 1 y 50 (recibido: {$linea['lugares']}).";
                    continue;
                }
                $invitacion->invitados()->create([
                    'nombre'            => $linea['nombre'],
                    'lugares_asignados' => $linea['lugares'],
                ]);
                $creados++;
            }
        });

        $msg = "Se agregaron {$creados} invitado(s).";
        if (! empty($errores)) {
            $msg .= ' Omitidos: ' . implode(' | ', array_slice($errores, 0, 5));
            if (count($errores) > 5) {
                $msg .= ' (+' . (count($errores) - 5) . ' más)';
            }
        }

        return redirect()
            ->route('panel.invitados.index', $invitacion)
            ->with('status', $msg);
    }

    /**
     * Regenear el token de un invitado (por si se filtró el link).
     * El anterior deja de funcionar — útil pero drástico.
     */
    public function regenerarToken(Request $request, Invitacion $invitacion, Invitado $invitado): RedirectResponse
    {
        $this->autorizar($request->user(), $invitacion);
        $this->asegurarMismaInvitacion($invitado, $invitacion);

        $invitado->token = Invitado::generarTokenUnico();
        $invitado->save();

        return back()->with('status', 'Link regenerado. El link anterior ya no funciona.');
    }

    // ─── helpers ────────────────────────────────────────────────────────

    private function validarInvitado(Request $request): array
    {
        return $request->validate([
            'nombre'            => ['required', 'string', 'min:2', 'max:120'],
            'telefono'          => ['nullable', 'string', 'max:30'],
            'lugares_asignados' => ['required', 'integer', 'min:1', 'max:50'],
        ], [
            'nombre.required'            => 'Escribe el nombre del invitado.',
            'lugares_asignados.required' => '¿Cuántos lugares le asignas?',
            'lugares_asignados.max'      => 'Máximo 50 lugares por invitado (es un evento, no un estadio).',
        ]);
    }

    /**
     * Lee un textarea con líneas tipo "Nombre, lugares" y devuelve
     * [['nombre' => 'X', 'lugares' => N], ...].
     */
    private function parsearLista(string $texto): array
    {
        $resultado = [];
        foreach (preg_split('/\r\n|\r|\n/', $texto) as $linea) {
            $linea = trim($linea);
            if ($linea === '' || str_starts_with($linea, '#')) {
                continue; // línea vacía o comentario
            }
            $partes = array_map('trim', explode(',', $linea, 2));
            $nombre = $partes[0] ?? '';
            $lugares = isset($partes[1]) ? (int) $partes[1] : 1;
            $resultado[] = [
                'nombre'  => $nombre,
                'lugares' => $lugares > 0 ? $lugares : 1,
            ];
        }
        return $resultado;
    }

    private function autorizar(User $user, Invitacion $invitacion): void
    {
        if ($user->isAdmin()) {
            return;
        }
        if ((int) $invitacion->user_id !== (int) $user->id) {
            abort(Response::HTTP_FORBIDDEN);
        }
    }

    private function asegurarMismaInvitacion(Invitado $invitado, Invitacion $invitacion): void
    {
        if ((int) $invitado->invitacion_id !== (int) $invitacion->id) {
            abort(Response::HTTP_NOT_FOUND);
        }
    }
}
