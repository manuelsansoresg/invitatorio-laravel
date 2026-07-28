@extends('layouts.admin', ['title' => 'Datos de la invitación', 'wide' => true])

@section('content')
    <div class="mb-8">
        <p class="text-sm font-semibold uppercase tracking-[0.18em] text-orange-brand">Mi panel</p>
        <h1 class="mt-2 font-display text-3xl font-extrabold text-purple-dark">Datos para tu {{ $invitacion->template?->formato === 'video' ? 'video' : 'imagen' }}</h1>
        <p class="mt-2 max-w-2xl text-sm text-text-gray">
            Llena los datos principales. Con esto el administrador arma el archivo final y lo sube. Te avisaremos cuando esté listo.
        </p>
    </div>

    @if ($errors->any())
        <div class="mb-6 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            <ul class="mt-1 list-disc pl-5">
                @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('panel.invitaciones.datos.store', $invitacion) }}" class="grid gap-8 lg:grid-cols-[1.4fr_1fr]">
        @csrf
        <div class="space-y-6">
            <div class="rounded-lg border border-border-soft bg-white p-5 shadow-sm">
                <h2 class="mb-4 font-display text-lg font-bold text-text-dark">Festejado</h2>
                <div class="grid gap-4 sm:grid-cols-3">
                    <div class="sm:col-span-2">
                        <label for="nombre" class="block text-sm font-semibold text-text-dark">Nombre <span class="text-orange-brand">*</span></label>
                        <input id="nombre" name="nombre" value="{{ old('nombre', $invitacion->nombre) }}" required maxlength="80"
                               class="mt-2 w-full rounded-md border border-border-soft px-3 py-2.5 text-sm outline-none focus:border-orange-brand focus:ring-2 focus:ring-orange-soft">
                    </div>
                    <div>
                        <label for="apellido_paterno" class="block text-sm font-semibold text-text-dark">Apellido paterno</label>
                        <input id="apellido_paterno" name="apellido_paterno" value="{{ old('apellido_paterno', $invitacion->apellido_paterno) }}" maxlength="80"
                               class="mt-2 w-full rounded-md border border-border-soft px-3 py-2.5 text-sm outline-none focus:border-orange-brand focus:ring-2 focus:ring-orange-soft">
                    </div>
                    <div>
                        <label for="apellido_materno" class="block text-sm font-semibold text-text-dark">Apellido materno</label>
                        <input id="apellido_materno" name="apellido_materno" value="{{ old('apellido_materno', $invitacion->apellido_materno) }}" maxlength="80"
                               class="mt-2 w-full rounded-md border border-border-soft px-3 py-2.5 text-sm outline-none focus:border-orange-brand focus:ring-2 focus:ring-orange-soft">
                    </div>
                    <div class="sm:col-span-2">
                        <label for="tipo_evento" class="block text-sm font-semibold text-text-dark">Tipo de evento</label>
                        <input id="tipo_evento" name="tipo_evento" value="{{ old('tipo_evento', $invitacion->tipo_evento) }}" maxlength="60"
                               placeholder="XV años, boda, bautizo, etc."
                               class="mt-2 w-full rounded-md border border-border-soft px-3 py-2.5 text-sm outline-none focus:border-orange-brand focus:ring-2 focus:ring-orange-soft">
                    </div>
                </div>
            </div>

            <div class="rounded-lg border border-border-soft bg-white p-5 shadow-sm">
                <h2 class="mb-4 font-display text-lg font-bold text-text-dark">Evento</h2>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div class="sm:col-span-2">
                        <label for="titulo" class="block text-sm font-semibold text-text-dark">Título de la invitación</label>
                        <input id="titulo" name="titulo" value="{{ old('titulo', $invitacion->titulo) }}" maxlength="160"
                               placeholder="Mis XV años"
                               class="mt-2 w-full rounded-md border border-border-soft px-3 py-2.5 text-sm outline-none focus:border-orange-brand focus:ring-2 focus:ring-orange-soft">
                    </div>
                    <div>
                        <label for="fecha_evento" class="block text-sm font-semibold text-text-dark">Fecha</label>
                        <input id="fecha_evento" name="fecha_evento" type="date" value="{{ old('fecha_evento', optional($invitacion->fecha_evento)->format('Y-m-d')) }}"
                               class="mt-2 w-full rounded-md border border-border-soft px-3 py-2.5 text-sm outline-none focus:border-orange-brand focus:ring-2 focus:ring-orange-soft">
                    </div>
                    <div>
                        <label for="hora_evento" class="block text-sm font-semibold text-text-dark">Hora</label>
                        <input id="hora_evento" name="hora_evento" type="time" value="{{ old('hora_evento', optional($invitacion->hora_evento)->format('H:i')) }}"
                               class="mt-2 w-full rounded-md border border-border-soft px-3 py-2.5 text-sm outline-none focus:border-orange-brand focus:ring-2 focus:ring-orange-soft">
                    </div>
                    <div>
                        <label for="lugar_nombre" class="block text-sm font-semibold text-text-dark">Lugar</label>
                        <input id="lugar_nombre" name="lugar_nombre" value="{{ old('lugar_nombre', $invitacion->lugar_nombre) }}" maxlength="160"
                               placeholder="Salón Los Pinos"
                               class="mt-2 w-full rounded-md border border-border-soft px-3 py-2.5 text-sm outline-none focus:border-orange-brand focus:ring-2 focus:ring-orange-soft">
                    </div>
                    <div>
                        <label for="lugar_direccion" class="block text-sm font-semibold text-text-dark">Dirección</label>
                        <input id="lugar_direccion" name="lugar_direccion" value="{{ old('lugar_direccion', $invitacion->lugar_direccion) }}" maxlength="200"
                               placeholder="Calle 123, Col. Centro"
                               class="mt-2 w-full rounded-md border border-border-soft px-3 py-2.5 text-sm outline-none focus:border-orange-brand focus:ring-2 focus:ring-orange-soft">
                    </div>
                </div>
            </div>

            <div class="rounded-lg border border-border-soft bg-white p-5 shadow-sm">
                <h2 class="mb-4 font-display text-lg font-bold text-text-dark">Mensajes y dress code</h2>
                <div>
                    <label for="mensaje_principal" class="block text-sm font-semibold text-text-dark">Mensaje principal</label>
                    <textarea id="mensaje_principal" name="mensaje_principal" rows="3" maxlength="1000"
                              placeholder="Texto que aparecerá en la invitación. Ej. 'Te invitamos a celebrar...' "
                              class="mt-2 w-full rounded-md border border-border-soft px-3 py-2.5 text-sm outline-none focus:border-orange-brand focus:ring-2 focus:ring-orange-soft">{{ old('mensaje_principal', $invitacion->mensaje_principal) }}</textarea>
                </div>
                <div class="mt-4">
                    <label for="dress_code" class="block text-sm font-semibold text-text-dark">Dress code</label>
                    <input id="dress_code" name="dress_code" value="{{ old('dress_code', $invitacion->dress_code) }}" maxlength="80"
                           placeholder="Formal, cóctel, etc."
                           class="mt-2 w-full rounded-md border border-border-soft px-3 py-2.5 text-sm outline-none focus:border-orange-brand focus:ring-2 focus:ring-orange-soft">
                </div>
            </div>
        </div>

        <aside class="space-y-6">
            <div class="rounded-lg border border-border-soft bg-white p-5 shadow-sm">
                <h2 class="mb-4 font-display text-lg font-bold text-text-dark">Contacto</h2>
                <div>
                    <label for="whatsapp_numero" class="block text-sm font-semibold text-text-dark">WhatsApp</label>
                    <input id="whatsapp_numero" name="whatsapp_numero" value="{{ old('whatsapp_numero', $invitacion->whatsapp_numero) }}" maxlength="40"
                           placeholder="55 1234 5678"
                           class="mt-2 w-full rounded-md border border-border-soft px-3 py-2.5 text-sm outline-none focus:border-orange-brand focus:ring-2 focus:ring-orange-soft">
                </div>
                <div class="mt-4">
                    <label for="whatsapp_mensaje" class="block text-sm font-semibold text-text-dark">Mensaje predeterminado</label>
                    <textarea id="whatsapp_mensaje" name="whatsapp_mensaje" rows="2" maxlength="160"
                              class="mt-2 w-full rounded-md border border-border-soft px-3 py-2.5 text-sm outline-none focus:border-orange-brand focus:ring-2 focus:ring-orange-soft">{{ old('whatsapp_mensaje', $invitacion->whatsapp_mensaje) }}</textarea>
                </div>
            </div>

            <div class="rounded-lg border border-border-soft bg-white p-5 shadow-sm">
                <h2 class="mb-4 font-display text-lg font-bold text-text-dark">Notas para el admin</h2>
                <textarea name="notas_cliente" rows="3" maxlength="1000"
                          placeholder="Colores, tipografía, referencias visuales, etc."
                          class="w-full rounded-md border border-border-soft px-3 py-2.5 text-sm outline-none focus:border-orange-brand focus:ring-2 focus:ring-orange-soft">{{ old('notas_cliente', $invitacion->notas_cliente) }}</textarea>
                <p class="mt-1.5 text-xs text-text-gray">Esta nota NO sale en la invitación. Es solo para que el admin sepa qué estilo buscas.</p>
            </div>

            <div class="flex flex-col gap-2">
                <button type="submit" class="w-full rounded-md bg-orange-brand px-4 py-3 text-sm font-bold text-white transition hover:bg-orange-intense">
                    Guardar datos
                </button>
                <a href="{{ route('panel.invitaciones.index') }}" class="w-full rounded-md border border-border-soft bg-white px-4 py-3 text-center text-sm font-bold text-purple-brand transition hover:border-purple-brand">
                    Volver
                </a>
            </div>
        </aside>
    </form>
@endsection
