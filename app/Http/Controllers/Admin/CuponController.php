<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cupon;
use App\Models\Paquete;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

/**
 * Admin · CRUD de Cupones.
 *
 * Reglas de validación clave:
 *   - tipo=precio: valor = centavos (>= 1).
 *   - tipo=porcentaje: valor = 1..100.
 *   - fecha_fin >= fecha_inicio.
 *   - si max_usos viene, > 0.
 *   - paquete_ids restringe a paquetes existentes.
 *
 * Nota sobre route model binding: usamos parámetro {cupon} y la firma
 * recibe $cupon. Laravel lo resolverá por id y listo.
 */
class CuponController extends Controller
{
    public function index(): View
    {
        $cupones = Cupon::query()
            ->withCount('ordenes')
            ->latest('id')
            ->get();

        return view('admin.cupones.index', [
            'cupones' => $cupones,
        ]);
    }

    public function create(): View
    {
        return view('admin.cupones.create', $this->formData(new Cupon([
            'activo'                  => true,
            'tipo'                    => Cupon::TIPO_PORCENTAJE,
            'valor'                   => 10,
            'minimo_compra_centavos'  => 0,
            'max_usos'                => null,
        ])));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validar($request);

        $cupon = DB::transaction(function () use ($data, $request) {
            $cupon = Cupon::create($data);
            $cupon->paquetes()->sync($request->input('paquete_ids', []));
            return $cupon;
        });

        return redirect()
            ->route('admin.cupones.edit', $cupon)
            ->with('status', 'Cupón creado correctamente.');
    }

    public function edit(Cupon $cupon): View
    {
        $cupon->load('paquetes');

        return view('admin.cupones.edit', $this->formData($cupon, true));
    }

    public function update(Request $request, Cupon $cupon): RedirectResponse
    {
        $data = $this->validar($request, $cupon);

        DB::transaction(function () use ($data, $cupon, $request) {
            $cupon->update($data);
            $cupon->paquetes()->sync($request->input('paquete_ids', []));
        });

        return redirect()
            ->route('admin.cupones.edit', $cupon)
            ->with('status', 'Cupón actualizado correctamente.');
    }

    public function destroy(Request $request, Cupon $cupon): RedirectResponse
    {
        $request->validate([
            'confirm_codigo' => ['required', Rule::in([$cupon->codigo])],
        ], [
            'confirm_codigo.required' => 'Escribe el código del cupón para confirmar.',
            'confirm_codigo.in'       => 'El código escrito no coincide.',
        ]);

        try {
            $cupon->delete();
        } catch (\Illuminate\Database\QueryException $e) {
            return back()->withErrors([
                'confirm_codigo' => 'No se puede eliminar: el cupón ya fue usado en órdenes. Mejor desactívalo.',
            ]);
        }

        return redirect()
            ->route('admin.cupones.index')
            ->with('status', "Cupón {$cupon->codigo} eliminado.");
    }

    // ─────────────── helpers ───────────────

    private function formData(Cupon $cupon, bool $edit = false): array
    {
        return [
            'cupon'    => $cupon,
            'paquetes' => Paquete::query()
                ->orderBy('formato')
                ->orderBy('orden')
                ->orderBy('nombre')
                ->get(),
            'paqueteIdsSeleccionados' => $edit
                ? $cupon->paquetes->pluck('id')->all()
                : [],
        ];
    }

    private function validar(Request $request, ?Cupon $cupon = null): array
    {
        $codigoUnique = Rule::unique('cupones', 'codigo')
            ->ignore($cupon?->id);

        $data = $request->validate([
            'codigo'                 => ['required', 'string', 'max:40', 'regex:/^[A-Za-z0-9_-]+$/', $codigoUnique],
            'descripcion'            => ['nullable', 'string', 'max:255'],
            'tipo'                   => ['required', Rule::in([Cupon::TIPO_PRECIO, Cupon::TIPO_PORCENTAJE])],
            'valor'                  => ['required', 'integer', 'min:1'],
            'minimo_compra_centavos' => ['nullable', 'integer', 'min:0'],
            'fecha_inicio'           => ['nullable', 'date'],
            'fecha_fin'              => ['nullable', 'date', 'after_or_equal:fecha_inicio'],
            'max_usos'               => ['nullable', 'integer', 'min:1'],
            'activo'                 => ['nullable', 'boolean'],
            'paquete_ids'            => ['nullable', 'array'],
            'paquete_ids.*'          => ['integer', Rule::exists('paquetes', 'id')],
        ], [
            'codigo.regex'   => 'El código solo puede tener letras, números, guion y guion bajo.',
            'codigo.unique'  => 'Ya existe un cupón con ese código.',
            'valor.min'      => 'El valor del descuento debe ser mayor a 0.',
        ]);

        if ($data['tipo'] === Cupon::TIPO_PORCENTAJE && (int) $data['valor'] > 100) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'valor' => 'Si el tipo es porcentaje, el valor no puede ser mayor a 100.',
            ]);
        }

        return [
            'codigo'                 => strtoupper($data['codigo']),
            'descripcion'            => $data['descripcion'] ?? null,
            'tipo'                   => $data['tipo'],
            'valor'                  => (int) $data['valor'],
            'minimo_compra_centavos' => (int) ($data['minimo_compra_centavos'] ?? 0),
            'fecha_inicio'           => $this->parseFecha($data['fecha_inicio'] ?? null),
            'fecha_fin'              => $this->parseFecha($data['fecha_fin'] ?? null, endOfDay: true),
            'max_usos'               => $data['max_usos'] !== null ? (int) $data['max_usos'] : null,
            'activo'                 => $request->boolean('activo'),
        ];
    }

    private function parseFecha(mixed $raw, bool $endOfDay = false): ?string
    {
        if (! filled($raw)) {
            return null;
        }
        try {
            $carbon = Carbon::parse((string) $raw);
        } catch (\Throwable) {
            return null;
        }
        return $endOfDay ? $carbon->endOfDay()->toDateTimeString() : $carbon->startOfDay()->toDateTimeString();
    }
}
