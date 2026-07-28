@extends('layouts.publico', ['title' => '¡Gracias!'])

@section('content')
    @if (session('status'))
        <div class="mb-4 rounded-md border border-green-200 bg-green-50 px-4 py-3 text-sm font-semibold text-green-800">
            {{ session('status') }}
        </div>
    @endif

    <div class="text-center">
        <div class="mx-auto mb-4 flex h-20 w-20 items-center justify-center rounded-full bg-green-100">
            <span class="text-5xl">✓</span>
        </div>
        <h1 class="font-display text-3xl font-extrabold text-purple-dark">
            @if ($invitado->estado === 'no_asistira')
                Gracias por avisar
            @else
                ¡Listo, {{ explode(' ', $invitado->nombre)[0] }}!
            @endif
        </h1>
        <p class="mt-3 text-base text-text-gray">
            @if ($invitado->estado === 'no_asistira')
                Entendemos. Te echaremos de menos en
                <strong class="text-text-dark">{{ $invitacion->titulo ?? $invitacion->nombre_completo }}</strong>.
                Si cambias de opinión, vuelve a este link y avísanos.
            @else
                Confirmaste <strong class="text-text-dark">{{ $invitado->lugares_confirmados }} lugar(es)</strong>
                para <strong class="text-text-dark">{{ $invitacion->titulo ?? $invitacion->nombre_completo }}</strong>.
            @endif
        </p>

        @if ($invitado->estado === 'confirmado' && $invitacion->fecha_evento)
            <div class="mt-6 rounded-xl bg-cream-bg p-4 text-left">
                <p class="text-xs font-semibold uppercase tracking-wide text-text-gray">Cuándo</p>
                <p class="mt-1 font-semibold text-text-dark">{{ $invitacion->fecha_evento->format('l d \d\e F, Y') }}</p>
                @if ($invitacion->hora_evento)
                    <p class="text-sm text-text-gray">{{ \Carbon\Carbon::parse($invitacion->hora_evento)->format('H:i') }} hrs</p>
                @endif

                @if ($invitacion->lugar_nombre)
                    <p class="mt-3 text-xs font-semibold uppercase tracking-wide text-text-gray">Dónde</p>
                    <p class="mt-1 font-semibold text-text-dark">{{ $invitacion->lugar_nombre }}</p>
                    @if ($invitacion->lugar_direccion)
                        <p class="text-sm text-text-gray">{{ $invitacion->lugar_direccion }}</p>
                    @endif
                    @if ($invitacion->maps_url)
                        <a href="{{ $invitacion->maps_url }}" target="_blank" rel="noopener"
                           class="mt-2 inline-block text-sm font-semibold text-orange-brand hover:text-orange-intense">
                            Ver en Google Maps →
                        </a>
                    @endif
                @endif
            </div>
        @endif

        <a href="{{ route('confirmar.show', $invitado->token) }}"
           class="mt-6 inline-block text-sm font-semibold text-text-gray underline hover:text-orange-brand">
            Cambiar mi respuesta
        </a>
    </div>
@endsection
