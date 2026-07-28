@extends('layouts.admin', ['title' => 'Agregar invitado'])

@section('content')
    <div class="mb-6">
        <p class="text-sm font-semibold uppercase tracking-[0.18em] text-orange-brand">Nuevo invitado</p>
        <h1 class="mt-2 font-display text-3xl font-extrabold text-purple-dark">Agregar a la lista</h1>
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
        <form method="POST" action="{{ route('panel.invitados.store', $invitacion) }}" class="space-y-4">
            @csrf

            <div>
                <label class="mb-1 block text-sm font-semibold text-text-dark">Nombre</label>
                <input type="text" name="nombre" required minlength="2" maxlength="120"
                       value="{{ old('nombre') }}"
                       placeholder="Manuel Sansores"
                       class="w-full rounded-md border border-border-soft bg-white px-3 py-2 text-sm focus:border-orange-brand focus:outline-none">
            </div>

            <div>
                <label class="mb-1 block text-sm font-semibold text-text-dark">
                    Teléfono <span class="text-xs font-normal text-text-gray">(opcional, te ayuda a llevar el control)</span>
                </label>
                <input type="text" name="telefono" maxlength="30"
                       value="{{ old('telefono') }}"
                       placeholder="999 123 4567"
                       class="w-full rounded-md border border-border-soft bg-white px-3 py-2 text-sm focus:border-orange-brand focus:outline-none">
            </div>

            <div>
                <label class="mb-1 block text-sm font-semibold text-text-dark">Lugares asignados</label>
                <input type="number" name="lugares_asignados" required min="1" max="50"
                       value="{{ old('lugares_asignados', 1) }}"
                       class="w-32 rounded-md border border-border-soft bg-white px-3 py-2 text-sm focus:border-orange-brand focus:outline-none">
                <p class="mt-1 text-xs text-text-gray">
                    Cuántos lugares reserva este invitado. Después podrá confirmar entre 0 y este número.
                </p>
            </div>

            <div class="flex gap-2 pt-2">
                <button type="submit" class="rounded-md bg-orange-brand px-4 py-2.5 text-sm font-bold text-white transition hover:bg-orange-intense">
                    Agregar invitado
                </button>
                <a href="{{ route('panel.invitados.index', $invitacion) }}"
                   class="rounded-md border border-border-soft bg-white px-4 py-2.5 text-sm font-bold text-text-dark transition hover:bg-cream-bg">
                    Cancelar
                </a>
            </div>
        </form>
    </section>

    <p class="mt-6 text-xs text-text-gray">
        Al guardar, se generará automáticamente un link único que podrás compartir por WhatsApp o Facebook.
    </p>
@endsection
