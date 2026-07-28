@extends('layouts.publico', ['title' => 'Hola, ' . $invitado->nombre])

@section('content')
    @if (session('status'))
        <div class="mb-4 rounded-md border border-green-200 bg-green-50 px-4 py-3 text-sm font-semibold text-green-800">
            {{ session('status') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-4 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm font-semibold text-red-700">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="text-center">
        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-orange-brand">Confirmación de asistencia</p>
        <h1 class="mt-2 font-display text-3xl font-extrabold text-purple-dark">
            Hola, {{ $invitado->nombre }}
        </h1>
        <p class="mt-3 text-sm text-text-gray">
            Estás invitado(a) a
            <strong class="text-text-dark">{{ $invitacion->titulo ?? $invitacion->nombre_completo }}</strong>
            @if ($invitacion->fecha_evento)
                el <strong class="text-text-dark">{{ $invitacion->fecha_evento->format('d/m/Y') }}</strong>
            @endif
            @if ($invitacion->lugar_nombre)
                en <strong class="text-text-dark">{{ $invitacion->lugar_nombre }}</strong>
            @endif
        </p>
    </div>

    <div class="my-6 rounded-xl bg-cream-bg p-5 text-center">
        <p class="text-xs font-semibold uppercase tracking-wide text-text-gray">Lugares reservados a tu nombre</p>
        <p class="mt-1 font-display text-5xl font-extrabold text-purple-dark">
            {{ $invitado->lugares_asignados }}
        </p>
        @if ($invitado->estado === 'confirmado' && $invitado->lugares_confirmados !== null)
            <p class="mt-2 text-sm text-text-gray">
                Ya confirmaste <strong class="text-green-700">{{ $invitado->lugares_confirmados }}</strong> lugar(es).
                @if ($invitado->lugares_confirmados < $invitado->lugares_asignados)
                    Puedes re-confirmar abajo.
                @else
                    ¡Ya están todos confirmados!
                @endif
            </p>
        @elseif ($invitado->estado === 'no_asistira')
            <p class="mt-2 text-sm text-text-gray">
                Marcaste que no podrás asistir. Si cambiaste de opinión, puedes confirmar abajo.
            </p>
        @endif
    </div>

    <form method="POST" action="{{ route('confirmar.confirmar', $invitado->token) }}" class="space-y-4">
        @csrf

        <fieldset>
            <legend class="mb-3 text-center text-sm font-semibold text-text-dark">
                ¿Cuántos van a venir de los {{ $invitado->lugares_asignados }} lugar(es)?
            </legend>

            <div class="grid grid-cols-{{ min($invitado->lugares_asignados + 1, 6) }} gap-2">
                @for ($i = 1; $i <= $invitado->lugares_asignados; $i++)
                    <label class="cursor-pointer">
                        <input type="radio" name="lugares" value="{{ $i }}" required
                               class="peer sr-only"
                               @checked(old('lugares', $invitado->lugares_confirmados) == $i)>
                        <div class="flex h-16 items-center justify-center rounded-lg border-2 border-border-soft bg-white text-2xl font-extrabold text-text-dark transition peer-checked:border-orange-brand peer-checked:bg-orange-brand peer-checked:text-white peer-hover:border-orange-brand">
                            {{ $i }}
                        </div>
                    </label>
                @endfor
            </div>
            <p class="mt-2 text-center text-xs text-text-gray">
                {{ $invitado->lugares_asignados === 1 ? 'Solo tú' : "Del 1 al {$invitado->lugares_asignados}" }}
            </p>
        </fieldset>

        <div class="rounded-md border border-border-soft bg-cream-bg/50 p-3">
            <p class="text-xs font-semibold text-text-dark">¿No podrás venir?</p>
            <label class="mt-1 inline-flex cursor-pointer items-center gap-2 text-sm text-text-gray">
                <input type="radio" name="lugares" value="0" required
                       class="h-4 w-4 border-border-soft text-red-500 focus:ring-red-500"
                       @checked(old('lugares', $invitado->lugares_confirmados) == 0)>
                <span>No podré asistir</span>
            </label>
        </div>

        <button type="submit"
                class="w-full rounded-md bg-orange-brand px-4 py-3 text-base font-extrabold text-white shadow-md transition hover:bg-orange-intense">
            Confirmar
        </button>

        <p class="text-center text-xs text-text-gray">
            Puedes cambiar tu respuesta las veces que necesites con este mismo link.
        </p>
    </form>
@endsection
