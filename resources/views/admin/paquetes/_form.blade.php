{{--
    Form parcial compartido por create.blade.php y edit.blade.php.
    Espera:
      - $paquete   (modelo, old() manejado por el controlador)
      - $formatos  (array formato => label)
      - $action     (URL del POST/PUT)
      - $method     ('POST' o 'PUT')
--}}
<form method="POST" action="{{ $action }}" class="grid gap-8 lg:grid-cols-[1.4fr_1fr]">
    @csrf
    @if (strtoupper($method) !== 'POST')
        @method($method)
    @endif

    <div class="space-y-6">
        <div class="rounded-lg border border-border-soft bg-white p-5 shadow-sm">
            <h2 class="mb-4 font-display text-lg font-bold text-text-dark">Información del paquete</h2>

            <div class="grid gap-4 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <label for="nombre" class="block text-sm font-semibold text-text-dark">Nombre <span class="text-orange-brand">*</span></label>
                    <input id="nombre" name="nombre" value="{{ old('nombre', $paquete->nombre) }}" required maxlength="80"
                           class="mt-2 w-full rounded-md border border-border-soft px-3 py-2.5 text-sm outline-none focus:border-orange-brand focus:ring-2 focus:ring-orange-soft">
                    @error('nombre')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="slug" class="block text-sm font-semibold text-text-dark">Slug <span class="text-orange-brand">*</span></label>
                    <input id="slug" name="slug" value="{{ old('slug', $paquete->slug) }}" required maxlength="80"
                           pattern="[a-z0-9-]+" placeholder="web-esencial"
                           class="mt-2 w-full rounded-md border border-border-soft px-3 py-2.5 text-sm font-mono outline-none focus:border-orange-brand focus:ring-2 focus:ring-orange-soft">
                    <p class="mt-1.5 text-xs text-text-gray">URL: <span class="font-mono">/comprar/{{ old('slug', $paquete->slug) }}</span></p>
                    @error('slug')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="formato" class="block text-sm font-semibold text-text-dark">Formato <span class="text-orange-brand">*</span></label>
                    <select id="formato" name="formato" required
                            class="mt-2 w-full cursor-pointer rounded-md border border-border-soft bg-white px-3 py-2.5 text-sm outline-none focus:border-orange-brand focus:ring-2 focus:ring-orange-soft">
                        @foreach ($formatos as $value => $label)
                            <option value="{{ $value }}" @selected(old('formato', $paquete->formato) === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('formato')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="sm:col-span-2">
                    <label for="descripcion" class="block text-sm font-semibold text-text-dark">Descripción <span class="text-orange-brand">*</span></label>
                    <textarea id="descripcion" name="descripcion" rows="2" required maxlength="255"
                              class="mt-2 w-full rounded-md border border-border-soft px-3 py-2.5 text-sm outline-none focus:border-orange-brand focus:ring-2 focus:ring-orange-soft">{{ old('descripcion', $paquete->descripcion) }}</textarea>
                    @error('descripcion')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        <div class="rounded-lg border border-border-soft bg-white p-5 shadow-sm">
            <h2 class="mb-4 font-display text-lg font-bold text-text-dark">Lo que incluye</h2>
            <label for="items" class="block text-sm font-semibold text-text-dark">Items</label>
            <textarea id="items" name="items" rows="7"
                      placeholder="Un beneficio por línea. Ej:&#10;Diseño estático personalizado&#10;Formato vertical para WhatsApp&#10;Nombres, fecha, hora y lugar"
                      class="mt-2 w-full rounded-md border border-border-soft px-3 py-2.5 text-sm outline-none focus:border-orange-brand focus:ring-2 focus:ring-orange-soft">{{ old('items', is_array($paquete->items) ? implode("\n", $paquete->items) : '') }}</textarea>
            <p class="mt-1.5 text-xs text-text-gray">Una línea por beneficio. Se muestran como check-list en la landing y en el checkout.</p>
            @error('items')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>
    </div>

    <aside class="space-y-6">
        <div class="rounded-lg border border-border-soft bg-white p-5 shadow-sm">
            <h2 class="mb-4 font-display text-lg font-bold text-text-dark">Precio y posición</h2>

            <div>
                <label for="precio_centavos" class="block text-sm font-semibold text-text-dark">Precio (centavos) <span class="text-orange-brand">*</span></label>
                <div class="mt-2 flex items-stretch overflow-hidden rounded-md border border-border-soft focus-within:border-orange-brand focus-within:ring-2 focus-within:ring-orange-soft">
                    <span class="grid place-items-center bg-cream-bg px-3 text-sm font-semibold text-text-gray">$ MXN × 100</span>
                    <input id="precio_centavos" name="precio_centavos" type="number" min="0" step="1"
                           value="{{ old('precio_centavos', $paquete->precio_centavos) }}" required
                           class="w-full bg-white px-3 py-2.5 text-sm outline-none">
                </div>
                @php
                    $centavosParaPreview = (int) old('precio_centavos', $paquete->precio_centavos);
                @endphp
                <p class="mt-1.5 text-xs text-text-gray" data-precio-preview="{{ $centavosParaPreview }}">
                    Se mostrará como: <span class="font-mono font-semibold text-purple-brand">${{ number_format($centavosParaPreview / 100, 0, '.', ',') }} MXN</span>
                </p>
                @error('precio_centavos')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div class="mt-4 grid grid-cols-2 gap-4">
                <div>
                    <label for="orden" class="block text-sm font-semibold text-text-dark">Orden</label>
                    <input id="orden" name="orden" type="number" min="0" step="1"
                           value="{{ old('orden', $paquete->orden ?? 0) }}"
                           class="mt-2 w-full rounded-md border border-border-soft px-3 py-2.5 text-sm outline-none focus:border-orange-brand focus:ring-2 focus:ring-orange-soft">
                </div>
                <div>
                    <label for="badge" class="block text-sm font-semibold text-text-dark">Badge</label>
                    <input id="badge" name="badge" maxlength="40" value="{{ old('badge', $paquete->badge) }}"
                           placeholder="Más elegida"
                           class="mt-2 w-full rounded-md border border-border-soft px-3 py-2.5 text-sm outline-none focus:border-orange-brand focus:ring-2 focus:ring-orange-soft">
                </div>
            </div>
        </div>

        <div class="rounded-lg border border-border-soft bg-white p-5 shadow-sm">
            <h2 class="mb-4 font-display text-lg font-bold text-text-dark">Visibilidad</h2>

            <label class="flex cursor-pointer items-start gap-3 rounded-md p-2 transition hover:bg-cream-bg">
                <input type="hidden" name="activo" value="0">
                <input type="checkbox" name="activo" value="1" @checked(old('activo', $paquete->activo))
                       class="mt-0.5 h-4 w-4 rounded border-border-soft text-orange-brand focus:ring-orange-soft">
                <span>
                    <span class="block text-sm font-semibold text-text-dark">Activo</span>
                    <span class="block text-xs text-text-gray">Si está apagado, el paquete no aparece en la landing ni se puede comprar.</span>
                </span>
            </label>

            <label class="mt-1 flex cursor-pointer items-start gap-3 rounded-md p-2 transition hover:bg-cream-bg">
                <input type="hidden" name="destacado" value="0">
                <input type="checkbox" name="destacado" value="1" @checked(old('destacado', $paquete->destacado))
                       class="mt-0.5 h-4 w-4 rounded border-border-soft text-orange-brand focus:ring-orange-soft">
                <span>
                    <span class="block text-sm font-semibold text-text-dark">Destacado</span>
                    <span class="block text-xs text-text-gray">Se resalta con badge y borde en la card de pricing.</span>
                </span>
            </label>
        </div>

        <div class="flex flex-col gap-2">
            <button type="submit"
                    class="w-full rounded-md bg-orange-brand px-4 py-3 text-sm font-bold text-white transition hover:bg-orange-intense">
                Guardar
            </button>
            <a href="{{ route('admin.paquetes.index') }}"
               class="w-full rounded-md border border-border-soft bg-white px-4 py-3 text-center text-sm font-bold text-purple-brand transition hover:border-purple-brand">
                Volver al listado
            </a>
        </div>
    </aside>
</form>
