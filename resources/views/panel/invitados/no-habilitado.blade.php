@extends('layouts.admin', ['title' => 'Gestión de invitados · ' . $invitacion->nombre_completo])

@section('content')
    <div class="mb-6">
        <p class="text-sm font-semibold uppercase tracking-[0.18em] text-orange-brand">Gestión de invitados</p>
        <h1 class="mt-2 font-display text-3xl font-extrabold text-purple-dark">
            {{ $invitacion->nombre_completo }}
        </h1>
    </div>

    <section class="rounded-lg border-2 border-amber-200 bg-amber-50 p-8 shadow-sm">
        <div class="flex items-start gap-4">
            <div class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-amber-200 text-2xl">
                🔒
            </div>
            <div class="flex-1">
                <h2 class="font-display text-xl font-bold text-amber-900">
                    Tu paquete no incluye gestión de invitados
                </h2>
                <p class="mt-2 text-sm text-amber-800">
                    La función de "Lista de confirmados con link único por invitado" está disponible
                    solo en algunos paquetes.
                    @if ($paquete)
                        Tu paquete actual es <strong class="font-bold">{{ $paquete->nombre }}</strong>.
                    @else
                        Aún no tienes un paquete activo asociado a tu cuenta.
                    @endif
                </p>
                <p class="mt-2 text-sm text-amber-800">
                    Cuando contrates un paquete que lo incluya, esta pantalla se activará
                    automáticamente y podrás dar de alta a tus invitados con su link
                    personalizado de WhatsApp/Facebook.
                </p>

                <div class="mt-5 flex flex-wrap gap-2">
                    <a href="{{ route('panel.confirmados.index') }}"
                       class="inline-flex items-center justify-center rounded-md border border-amber-300 bg-white px-4 py-2.5 text-sm font-bold text-amber-900 transition hover:border-amber-500 hover:bg-amber-100">
                        ← Volver a confirmados
                    </a>

                    @if ($esAdmin && $paquete)
                        <form method="POST"
                              action="{{ route('admin.paquetes.toggle-gestionar-invitados', $paquete) }}"
                              onsubmit="return confirm('¿Activar gestión de invitados en «{{ $paquete->nombre }}»?')">
                            @csrf
                            <button type="submit"
                                    class="inline-flex items-center justify-center rounded-md bg-orange-brand px-4 py-2.5 text-sm font-bold text-white transition hover:bg-orange-intense">
                                ⚙ Activar en «{{ $paquete->nombre }}» (admin)
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection
