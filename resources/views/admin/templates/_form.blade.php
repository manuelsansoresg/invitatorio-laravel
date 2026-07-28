{{--
    Form parcial compartido por create.blade.php y edit.blade.php.
    Espera:
      - $template  (modelo, old() manejado por el controlador)
      - $formatos  (array formato => label)
      - $action    (URL POST/PUT)
      - $method    ('POST' o 'PUT')
--}}
<div class="mx-auto max-w-4xl">
<form method="POST" action="{{ $action }}" class="grid gap-8 lg:grid-cols-[1.4fr_1fr]">
    @csrf
    @if (strtoupper($method) !== 'POST')
        @method($method)
    @endif

    <div class="space-y-6">
        <div class="rounded-lg border border-border-soft bg-white p-5 shadow-sm">
            <h2 class="mb-4 font-display text-lg font-bold text-text-dark">Identidad</h2>
            <div class="grid gap-4 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <label for="nombre" class="block text-sm font-semibold text-text-dark">Nombre <span class="text-orange-brand">*</span></label>
                    <input id="nombre" name="nombre" value="{{ old('nombre', $template->nombre) }}" required maxlength="80"
                           placeholder="Valeria elegante"
                           class="mt-2 w-full rounded-md border border-border-soft px-3 py-2.5 text-sm outline-none focus:border-orange-brand focus:ring-2 focus:ring-orange-soft">
                    @error('nombre')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="slug" class="block text-sm font-semibold text-text-dark">Slug <span class="text-orange-brand">*</span></label>
                    <input id="slug" name="slug" value="{{ old('slug', $template->slug) }}" required maxlength="80"
                           pattern="[a-z0-9-]+" placeholder="valeria-elegante"
                           class="mt-2 w-full rounded-md border border-border-soft px-3 py-2.5 font-mono text-sm outline-none focus:border-orange-brand focus:ring-2 focus:ring-orange-soft">
                    @error('slug')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="formato" class="block text-sm font-semibold text-text-dark">Formato <span class="text-orange-brand">*</span></label>
                    <select id="formato" name="formato" required
                            class="mt-2 w-full cursor-pointer rounded-md border border-border-soft bg-white px-3 py-2.5 text-sm outline-none focus:border-orange-brand focus:ring-2 focus:ring-orange-soft">
                        @foreach ($formatos as $value => $label)
                            <option value="{{ $value }}" @selected(old('formato', $template->formato) === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('formato')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="sm:col-span-2">
                    <label for="descripcion" class="block text-sm font-semibold text-text-dark">Descripción</label>
                    <input id="descripcion" name="descripcion" value="{{ old('descripcion', $template->descripcion) }}" maxlength="255"
                           placeholder="Línea corta que ve el cliente en la galería."
                           class="mt-2 w-full rounded-md border border-border-soft px-3 py-2.5 text-sm outline-none focus:border-orange-brand focus:ring-2 focus:ring-orange-soft">
                    @error('descripcion')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="sm:col-span-2">
                    <label for="imagen_preview_path" class="block text-sm font-semibold text-text-dark">URL de imagen preview</label>
                    <input id="imagen_preview_path" name="imagen_preview_path" value="{{ old('imagen_preview_path', $template->imagen_preview_path) }}" maxlength="255"
                           placeholder="images/templates/valeria.png"
                           class="mt-2 w-full rounded-md border border-border-soft px-3 py-2.5 font-mono text-sm outline-none focus:border-orange-brand focus:ring-2 focus:ring-orange-soft">
                    <p class="mt-1.5 text-xs text-text-gray">Ruta relativa en /public. Si lo dejas vacío, mostraremos un placeholder.</p>
                    @error('imagen_preview_path')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        <div class="rounded-lg border border-border-soft bg-white p-5 shadow-sm">
        <h2 class="mb-2 font-display text-lg font-bold text-text-dark">¿Para qué sirve editar un template?</h2>
        <p class="text-sm text-text-gray">
            Cada template es un <strong>diseño base</strong> (Valeria, Pergamino, XV moderno, etc.) que el admin ofrece a los clientes. Cuando un cliente elige uno, se le abre el editor para que personalice su propia invitación.
        </p>
        <p class="mt-3 text-sm text-text-gray">
            <strong>Editar este template solo cambia la identidad y la categoría</strong> del diseño. No modifica invitaciones que ya estén publicadas.
        </p>
    </div>
    </div>

    <aside class="space-y-6">
        <div class="rounded-lg border border-border-soft bg-white p-5 shadow-sm">
            <h2 class="mb-4 font-display text-lg font-bold text-text-dark">Posición y visibilidad</h2>
            <div>
                <label for="orden" class="block text-sm font-semibold text-text-dark">Orden</label>
                <input id="orden" name="orden" type="number" min="0" step="1"
                       value="{{ old('orden', $template->orden ?? 0) }}"
                       class="mt-2 w-full rounded-md border border-border-soft px-3 py-2.5 text-sm outline-none focus:border-orange-brand focus:ring-2 focus:ring-orange-soft">
                <p class="mt-1.5 text-xs text-text-gray">Menor = aparece primero dentro del mismo formato.</p>
            </div>

            <label class="mt-4 flex cursor-pointer items-start gap-3 rounded-md p-2 transition hover:bg-cream-bg">
                <input type="hidden" name="activo" value="0">
                <input type="checkbox" name="activo" value="1" @checked(old('activo', $template->activo))
                       class="mt-0.5 h-4 w-4 rounded border-border-soft text-orange-brand focus:ring-orange-soft">
                <span>
                    <span class="block text-sm font-semibold text-text-dark">Activo</span>
                    <span class="block text-xs text-text-gray">Si está apagado, este template no aparece en el catálogo (las invitaciones viejas que lo usen siguen funcionando).</span>
                </span>
            </label>
        </div>

        <div class="flex flex-col gap-2">
            <button type="submit"
                    class="w-full rounded-md bg-orange-brand px-4 py-3 text-sm font-bold text-white transition hover:bg-orange-intense">
                Guardar
            </button>
            <a href="{{ route('admin.templates.index') }}"
               class="w-full rounded-md border border-border-soft bg-white px-4 py-3 text-center text-sm font-bold text-purple-brand transition hover:border-purple-brand">
                Volver al listado
            </a>
        </div>
    </aside>
</form>
</div>
