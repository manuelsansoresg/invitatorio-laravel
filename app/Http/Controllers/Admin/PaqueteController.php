<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Paquete;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

/**
 * Admin · CRUD de Paquetes.
 *
 * Mismo idioma y estilo que el resto del admin: minimal, directo,
 * con confirmaciones por dialog (no por alerts JS) para mantener
 * consistencia con dashboard.blade.php.
 */
class PaqueteController extends Controller
{
    public function index(): View
    {
        $paquetes = Paquete::query()
            ->orderBy('formato')
            ->orderBy('orden')
            ->orderBy('nombre')
            ->get();

        return view('admin.paquetes.index', [
            'paquetes' => $paquetes,
        ]);
    }

    public function create(): View
    {
        return view('admin.paquetes.create', [
            'paquete'     => new Paquete(['activo' => true, 'formato' => 'web', 'items' => []]),
            'formatos'    => $this->formatosPermitidos(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validar($request);

        $paquete = Paquete::create($data);

        return redirect()
            ->route('admin.paquetes.edit', $paquete)
            ->with('status', 'Paquete creado correctamente.');
    }

    public function edit(Paquete $paquete): View
    {
        return view('admin.paquetes.edit', [
            'paquete'  => $paquete,
            'formatos' => $this->formatosPermitidos(),
        ]);
    }

    public function update(Request $request, Paquete $paquete): RedirectResponse
    {
        $data = $this->validar($request, $paquete);

        $paquete->update($data);

        return redirect()
            ->route('admin.paquetes.edit', $paquete)
            ->with('status', 'Paquete actualizado correctamente.');
    }

    public function destroy(Request $request, Paquete $paquete): RedirectResponse
    {
        $request->validate([
            'confirm_slug' => ['required', Rule::in([$paquete->slug])],
        ], [
            'confirm_slug.required' => 'Escribe el slug del paquete para confirmar.',
            'confirm_slug.in'       => 'El slug escrito no coincide.',
        ]);

        $nombre = $paquete->nombre;
        $paquete->delete();

        return redirect()
            ->route('admin.paquetes.index')
            ->with('status', "El paquete \"{$nombre}\" fue eliminado.");
    }

    // ─────────────── helpers ───────────────

    private function validar(Request $request, ?Paquete $paquete = null): array
    {
        $slugUnique = Rule::unique('paquetes', 'slug')
            ->ignore($paquete?->id);

        // Usamos array_merge (no +) para que los campos normalizados
        // abajo SIEMPRE sobrescriban lo que diga la validación. Si el
        // form no envía 'items' (textarea vacío), la validación con
        // 'nullable' devolvería null, pero nosotros siempre queremos
        // guardar un array. Mismo para los booleanos.
        return array_merge(
            $request->validate([
                'slug'           => ['required', 'string', 'max:80', 'regex:/^[a-z0-9-]+$/', $slugUnique],
                'formato'        => ['required', Rule::in(array_keys($this->formatosPermitidos()))],
                'nombre'         => ['required', 'string', 'max:80'],
                'descripcion'    => ['required', 'string', 'max:255'],
                'precio_centavos' => ['required', 'integer', 'min:0', 'max:99999999'],
                'badge'          => ['nullable', 'string', 'max:40'],
                'destacado'      => ['nullable', 'boolean'],
                'items'          => ['nullable', 'string', 'max:2000'],
                'orden'          => ['nullable', 'integer', 'min:0', 'max:9999'],
                'activo'         => ['nullable', 'boolean'],
                'permite_gestionar_invitados' => ['nullable', 'boolean'],
            ], [
                'slug.regex'             => 'El slug solo puede tener minúsculas, números y guiones.',
                'slug.unique'            => 'Ya existe un paquete con ese slug.',
                'precio_centavos.integer' => 'El precio debe ser un número entero (en centavos).',
            ]),
            [
                // Normalización: SIEMPRE array, SIEMPRE boolean, SIEMPRE int.
                // Esto garantiza que el modelo no reciba null donde la
                // columna es NOT NULL (caso típico: textarea de items vacío).
                'items'     => $this->normalizarItems($request->input('items')),
                'destacado' => $request->boolean('destacado'),
                'activo'    => $request->boolean('activo'),
                'permite_gestionar_invitados' => $request->boolean('permite_gestionar_invitados'),
                'orden'     => (int) ($request->input('orden') ?? 0),
            ]
        );
    }

    private function normalizarItems(mixed $raw): array
    {
        if (! filled($raw)) {
            return [];
        }
        // Aceptamos líneas separadas por \n o por comas.
        $raw = (string) $raw;
        $items = preg_split('/\r?\n|,/', $raw) ?: [];
        $items = array_map('trim', $items);
        $items = array_values(array_filter($items, fn ($s) => $s !== ''));
        return $items;
    }

    private function formatosPermitidos(): array
    {
        return [
            'web'    => 'Invitación web',
            'imagen' => 'Invitación en imagen',
            'video'  => 'Invitación en video',
        ];
    }
}
