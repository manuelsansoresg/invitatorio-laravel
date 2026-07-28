{{--
    Form parcial compartido por create.blade.php y edit.blade.php.
    Espera:
      - $cupon                    (modelo, old() manejado por el controlador)
      - $paquetes                 (todos los paquetes del catálogo)
      - $paqueteIdsSeleccionados  (ids actualmente asociados al cupón, array)
      - $action                   (URL POST/PUT)
      - $method                   ('POST' o 'PUT')
--}}
@php
    $tipoActual = old('tipo', $cupon->tipo);
    $esPrecio   = $tipoActual === \App\Models\Cupon::TIPO_PRECIO;
@endphp

<form method="POST" action="{{ $action }}" class="grid gap-8 lg:grid-cols-[1.4fr_1fr]">
    @csrf
    @if (strtoupper($method) !== 'POST')
        @method($method)
    @endif

    <div class="space-y-6">
        <div class="rounded-lg border border-border-soft bg-white p-5 shadow-sm">
            <h2 class="mb-4 font-display text-lg font-bold text-text-dark">Identidad del cupón</h2>

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label for="codigo" class="block text-sm font-semibold text-text-dark">Código <span class="text-orange-brand">*</span></label>
                    <input id="codigo" name="codigo" maxlength="40" required
                           value="{{ old('codigo', $cupon->codigo) }}"
                           placeholder="VERANO20"
                           class="mt-2 w-full rounded-md border border-border-soft px-3 py-2.5 font-mono text-sm uppercase outline-none focus:border-orange-brand focus:ring-2 focus:ring-orange-soft">
                    <p class="mt-1.5 text-xs text-text-gray">Se guarda en mayúsculas. Sin espacios.</p>
                    @error('codigo')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="valor" class="block text-sm font-semibold text-text-dark">
                        Valor del descuento <span class="text-orange-brand">*</span>
                    </label>
                    <div class="mt-2 flex items-stretch overflow-hidden rounded-md border border-border-soft focus-within:border-orange-brand focus-within:ring-2 focus-within:ring-orange-soft">
                        <span class="grid place-items-center bg-cream-bg px-3 text-sm font-semibold text-text-gray" data-valor-prefix>$ MXN</span>
                        <input id="valor" name="valor" type="number" min="1" step="1" required
                               value="{{ old('valor', $cupon->valor) }}"
                               class="w-full bg-white px-3 py-2.5 text-sm outline-none">
                        <span class="grid place-items-center bg-cream-bg px-3 text-sm font-semibold text-text-gray" data-valor-suffix></span>
                    </div>
                    <p class="mt-1.5 text-xs text-text-gray" data-valor-help>
                        @if ($esPrecio)
                            Cantidad en <strong>centavos</strong> a descontar. Ej. 20000 = $200 MXN.
                        @else
                            Porcentaje de descuento. Entre 1 y 100.
                        @endif
                    </p>
                    @error('valor')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="sm:col-span-2">
                    <span class="block text-sm font-semibold text-text-dark">Tipo de descuento <span class="text-orange-brand">*</span></span>
                    <div class="mt-2 grid gap-3 sm:grid-cols-2">
                        <label class="flex cursor-pointer items-start gap-3 rounded-md border border-border-soft p-3 transition hover:border-orange-brand has-[:checked]:border-orange-brand has-[:checked]:bg-orange-soft/40">
                            <input type="radio" name="tipo" value="{{ \App\Models\Cupon::TIPO_PORCENTAJE }}" @checked($tipoActual === \App\Models\Cupon::TIPO_PORCENTAJE)
                                   class="mt-0.5 h-4 w-4 border-border-soft text-orange-brand focus:ring-orange-soft">
                            <span>
                                <span class="block text-sm font-semibold text-text-dark">Porcentaje</span>
                                <span class="block text-xs text-text-gray">Descuenta un % del subtotal del paquete.</span>
                            </span>
                        </label>
                        <label class="flex cursor-pointer items-start gap-3 rounded-md border border-border-soft p-3 transition hover:border-orange-brand has-[:checked]:border-orange-brand has-[:checked]:bg-orange-soft/40">
                            <input type="radio" name="tipo" value="{{ \App\Models\Cupon::TIPO_PRECIO }}" @checked($tipoActual === \App\Models\Cupon::TIPO_PRECIO)
                                   class="mt-0.5 h-4 w-4 border-border-soft text-orange-brand focus:ring-orange-soft">
                            <span>
                                <span class="block text-sm font-semibold text-text-dark">Precio fijo</span>
                                <span class="block text-xs text-text-gray">Descuenta una cantidad fija en centavos.</span>
                            </span>
                        </label>
                    </div>
                    @error('tipo')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="sm:col-span-2">
                    <label for="descripcion" class="block text-sm font-semibold text-text-dark">Descripción interna</label>
                    <textarea id="descripcion" name="descripcion" rows="2" maxlength="255"
                              placeholder="Ej. Campaña de lanzamiento, sólo web premium"
                              class="mt-2 w-full rounded-md border border-border-soft px-3 py-2.5 text-sm outline-none focus:border-orange-brand focus:ring-2 focus:ring-orange-soft">{{ old('descripcion', $cupon->descripcion) }}</textarea>
                    <p class="mt-1.5 text-xs text-text-gray">No se muestra al cliente. Solo la ve el equipo.</p>
                    @error('descripcion')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        <div class="rounded-lg border border-border-soft bg-white p-5 shadow-sm">
            <h2 class="mb-4 font-display text-lg font-bold text-text-dark">A qué paquetes aplica</h2>

            @if ($paquetes->isEmpty())
                <p class="text-sm text-text-gray">Todavía no hay paquetes en el catálogo. <a href="{{ route('admin.paquetes.create') }}" class="font-semibold text-purple-brand hover:text-orange-brand">Crear uno →</a></p>
            @else
                <label class="flex cursor-pointer items-start gap-3 rounded-md p-2 transition hover:bg-cream-bg">
                    <input type="checkbox" id="aplica_todos" @checked(empty($paqueteIdsSeleccionados))
                           onclick="document.querySelectorAll('input[name=&quot;paquete_ids[]&quot;]').forEach(c => c.checked = false);"
                           class="mt-0.5 h-4 w-4 rounded border-border-soft text-orange-brand focus:ring-orange-soft">
                    <span>
                        <span class="block text-sm font-semibold text-text-dark">Aplica a todos los paquetes</span>
                        <span class="block text-xs text-text-gray">Si lo marcas, no necesitas seleccionar paquetes específicos. Recomendado para promos globales.</span>
                    </span>
                </label>

                <div class="mt-3 grid gap-4 sm:grid-cols-3">
                    @php
                        $porFormato = $paquetes->groupBy('formato');
                    @endphp
                    @foreach ($porFormato as $formato => $grupo)
                        <div class="rounded-md border border-border-soft p-3">
                            <p class="mb-2 text-xs font-extrabold uppercase tracking-wider text-orange-intense">{{ $formato }}</p>
                            <div class="space-y-1.5">
                                @foreach ($grupo as $paquete)
                                    <label class="flex cursor-pointer items-start gap-2 text-sm">
                                        <input type="checkbox" name="paquete_ids[]" value="{{ $paquete->id }}"
                                               @checked(in_array($paquete->id, old('paquete_ids', $paqueteIdsSeleccionados), true))
                                               onclick="document.getElementById('aplica_todos').checked = false;"
                                               class="mt-0.5 h-4 w-4 rounded border-border-soft text-orange-brand focus:ring-orange-soft">
                                        <span class="text-text-dark">{{ $paquete->nombre }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <aside class="space-y-6">
        <div class="rounded-lg border border-border-soft bg-white p-5 shadow-sm">
            <h2 class="mb-4 font-display text-lg font-bold text-text-dark">Vigencia y usos</h2>

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label for="fecha_inicio" class="block text-sm font-semibold text-text-dark">Vigente desde</label>
                    <input id="fecha_inicio" name="fecha_inicio" type="date"
                           value="{{ old('fecha_inicio', optional($cupon->fecha_inicio)->format('Y-m-d')) }}"
                           class="mt-2 w-full rounded-md border border-border-soft px-3 py-2.5 text-sm outline-none focus:border-orange-brand focus:ring-2 focus:ring-orange-soft">
                    @error('fecha_inicio')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="fecha_fin" class="block text-sm font-semibold text-text-dark">Vigente hasta</label>
                    <input id="fecha_fin" name="fecha_fin" type="date"
                           value="{{ old('fecha_fin', optional($cupon->fecha_fin)->format('Y-m-d')) }}"
                           class="mt-2 w-full rounded-md border border-border-soft px-3 py-2.5 text-sm outline-none focus:border-orange-brand focus:ring-2 focus:ring-orange-soft">
                    @error('fecha_fin')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="max_usos" class="block text-sm font-semibold text-text-dark">Máx. usos totales</label>
                    <input id="max_usos" name="max_usos" type="number" min="1" step="1"
                           value="{{ old('max_usos', $cupon->max_usos) }}"
                           placeholder="Ilimitado"
                           class="mt-2 w-full rounded-md border border-border-soft px-3 py-2.5 text-sm outline-none focus:border-orange-brand focus:ring-2 focus:ring-orange-soft">
                    <p class="mt-1.5 text-xs text-text-gray">Déjalo vacío para que sea ilimitado.</p>
                    @error('max_usos')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="minimo_compra_centavos" class="block text-sm font-semibold text-text-dark">Compra mínima</label>
                    <div class="mt-2 flex items-stretch overflow-hidden rounded-md border border-border-soft focus-within:border-orange-brand focus-within:ring-2 focus-within:ring-orange-soft">
                        <span class="grid place-items-center bg-cream-bg px-3 text-sm font-semibold text-text-gray">$ MXN × 100</span>
                        <input id="minimo_compra_centavos" name="minimo_compra_centavos" type="number" min="0" step="1"
                               value="{{ old('minimo_compra_centavos', $cupon->minimo_compra_centavos ?? 0) }}"
                               class="w-full bg-white px-3 py-2.5 text-sm outline-none">
                    </div>
                    <p class="mt-1.5 text-xs text-text-gray">Si el paquete cuesta menos, el cupón no aplica.</p>
                    @error('minimo_compra_centavos')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        <div class="rounded-lg border border-border-soft bg-white p-5 shadow-sm">
            <h2 class="mb-4 font-display text-lg font-bold text-text-dark">Visibilidad</h2>
            <label class="flex cursor-pointer items-start gap-3 rounded-md p-2 transition hover:bg-cream-bg">
                <input type="hidden" name="activo" value="0">
                <input type="checkbox" name="activo" value="1" @checked(old('activo', $cupon->activo))
                       class="mt-0.5 h-4 w-4 rounded border-border-soft text-orange-brand focus:ring-orange-soft">
                <span>
                    <span class="block text-sm font-semibold text-text-dark">Activo</span>
                    <span class="block text-xs text-text-gray">Si está apagado, el cupón no se puede aplicar aunque el cliente tenga el código.</span>
                </span>
            </label>
        </div>

        <div class="flex flex-col gap-2">
            <button type="submit"
                    class="w-full rounded-md bg-orange-brand px-4 py-3 text-sm font-bold text-white transition hover:bg-orange-intense">
                Guardar
            </button>
            <a href="{{ route('admin.cupones.index') }}"
               class="w-full rounded-md border border-border-soft bg-white px-4 py-3 text-center text-sm font-bold text-purple-brand transition hover:border-purple-brand">
                Volver al listado
            </a>
        </div>
    </aside>
</form>

@once
    @push('head')
        <script>
            (function () {
                const radios = document.querySelectorAll('input[name="tipo"]');
                const prefixEl = document.querySelector('[data-valor-prefix]');
                const helpEl   = document.querySelector('[data-valor-help]');
                const PRECIO   = @json(\App\Models\Cupon::TIPO_PRECIO);
                const PORC     = @json(\App\Models\Cupon::TIPO_PORCENTAJE);

                function update() {
                    const checked = document.querySelector('input[name="tipo"]:checked');
                    if (!checked) return;
                    if (checked.value === PRECIO) {
                        prefixEl.textContent = '$ MXN';
                        helpEl.innerHTML = 'Cantidad en <strong>centavos</strong> a descontar. Ej. 20000 = $200 MXN.';
                    } else {
                        prefixEl.textContent = '';
                        helpEl.innerHTML = 'Porcentaje de descuento. Entre 1 y 100.';
                    }
                }
                radios.forEach(r => r.addEventListener('change', update));
                update();
            })();
        </script>
    @endpush
@endonce
