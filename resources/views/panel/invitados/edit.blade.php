@extends('layouts.admin', ['title' => 'Editar invitado'])

@section('content')
    <div class="mb-6">
        <p class="text-sm font-semibold uppercase tracking-[0.18em] text-orange-brand">Editar invitado</p>
        <h1 class="mt-2 font-display text-3xl font-extrabold text-purple-dark">{{ $invitado->nombre }}</h1>
        <p class="mt-2 text-sm text-text-gray">
            Invitación: <strong>{{ $invitacion->nombre_completo }}</strong>
        </p>
    </div>

    @if ($errors->any())
        <div class="mb-6 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm font-semibold text-red-700">
            {{ $errors->first() }}
        </div>
    @endif

    <section class="rounded-lg border border-border-soft bg-white p-6 shadow-sm">
        <form method="POST" action="{{ route('panel.invitados.update', [$invitacion, $invitado]) }}" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="mb-1 block text-sm font-semibold text-text-dark">Nombre</label>
                <input type="text" name="nombre" required minlength="2" maxlength="120"
                       value="{{ old('nombre', $invitado->nombre) }}"
                       class="w-full rounded-md border border-border-soft bg-white px-3 py-2 text-sm focus:border-orange-brand focus:outline-none">
            </div>

            <div>
                <label class="mb-1 block text-sm font-semibold text-text-dark">Teléfono</label>
                <input type="text" name="telefono" maxlength="30"
                       value="{{ old('telefono', $invitado->telefono) }}"
                       class="w-full rounded-md border border-border-soft bg-white px-3 py-2 text-sm focus:border-orange-brand focus:outline-none">
            </div>

            <div>
                <label class="mb-1 block text-sm font-semibold text-text-dark">Lugares asignados</label>
                <input type="number" name="lugares_asignados" required min="1" max="50"
                       value="{{ old('lugares_asignados', $invitado->lugares_asignados) }}"
                       class="w-32 rounded-md border border-border-soft bg-white px-3 py-2 text-sm focus:border-orange-brand focus:outline-none">
                @if ($invitado->lugares_confirmados !== null)
                    <p class="mt-1 text-xs text-amber-700">
                        ⚠ Este invitado ya confirmó <strong>{{ $invitado->lugares_confirmados }}</strong> lugar(es).
                        No puedes bajar el número por debajo de eso, o se rompería la confirmación.
                    </p>
                @endif
            </div>

            <div class="flex flex-wrap gap-2 pt-2">
                <button type="submit" class="rounded-md bg-orange-brand px-4 py-2.5 text-sm font-bold text-white transition hover:bg-orange-intense">
                    Guardar cambios
                </button>
                <a href="{{ route('panel.invitados.index', $invitacion) }}"
                   class="rounded-md border border-border-soft bg-white px-4 py-2.5 text-sm font-bold text-text-dark transition hover:bg-cream-bg">
                    Cancelar
                </a>
            </div>
        </form>
    </section>

    <section class="mt-6 rounded-lg border border-red-200 bg-red-50 p-6">
        <h2 class="font-display text-lg font-bold text-red-800">Zona peligrosa</h2>

        <div class="mt-3 space-y-3">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm font-semibold text-text-dark">Regenerar link</p>
                    <p class="text-xs text-text-gray">Si el link se filtró, genera uno nuevo. El anterior deja de funcionar.</p>
                </div>
                <form method="POST" action="{{ route('panel.invitados.regenerar-token', [$invitacion, $invitado]) }}"
                      onsubmit="return confirm('¿Regenerar el link? El link anterior ya no funcionará.')">
                    @csrf
                    <button type="submit" class="rounded-md border border-amber-300 bg-white px-3 py-2 text-sm font-bold text-amber-800 transition hover:border-amber-500 hover:bg-amber-50">
                        Regenerar link
                    </button>
                </form>
            </div>

            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between border-t border-red-200 pt-3">
                <div>
                    <p class="text-sm font-semibold text-text-dark">Eliminar de la lista</p>
                    <p class="text-xs text-text-gray">Borra al invitado y su link deja de funcionar. No se puede deshacer.</p>
                </div>
                <form method="POST" action="{{ route('panel.invitados.destroy', [$invitacion, $invitado]) }}"
                      onsubmit="return confirm('¿Eliminar a {{ $invitado->nombre }} de la lista? Esta acción no se puede deshacer.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="rounded-md border border-red-300 bg-white px-3 py-2 text-sm font-bold text-red-700 transition hover:border-red-500 hover:bg-red-100">
                        Eliminar invitado
                    </button>
                </form>
            </div>
        </div>
    </section>
@endsection
