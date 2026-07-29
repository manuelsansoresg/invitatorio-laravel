@extends('layouts.checkout', ['title' => 'Invitación caducada'])

@section('content')
    <div class="mx-auto max-w-xl text-center">
        <div class="overflow-hidden rounded-3xl bg-white p-8 shadow-[0_30px_60px_-30px_rgba(43,20,63,0.35)] ring-1 ring-[#F1E6D9] sm:p-12">
            <div class="mx-auto mb-6 flex h-20 w-20 items-center justify-center rounded-full bg-amber-50">
                <svg class="h-10 w-10 text-amber-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <circle cx="12" cy="12" r="9"/><path d="M12 8v4l3 2"/>
                </svg>
            </div>

            <h1 class="font-display text-3xl font-extrabold text-[#2B143F] sm:text-4xl">Esta invitación caducó</h1>
            <p class="mt-3 text-base text-[#5F5A66]">
                La invitación <strong>{{ $invitacion->nombre_completo }}</strong> dejó de estar disponible el
                <strong>{{ $invitacion->fecha_caducidad?->format('d/m/Y') }}</strong>.
            </p>
            <p class="mt-2 text-sm text-[#5F5A66]">
                Si quieres reactivarla, escríbenos por WhatsApp y te decimos cómo renovar.
            </p>

            <a href="https://wa.me/529990000000?text={{ urlencode('Hola, quiero renovar mi invitación '.$invitacion->ruta) }}"
               target="_blank" rel="noopener"
               class="mt-6 inline-flex items-center justify-center gap-2 rounded-lg bg-[#EB7512] px-6 py-3 text-base font-semibold text-white shadow-md shadow-orange-500/25 transition hover:-translate-y-0.5 hover:bg-[#F45A00]">
                Renovar por WhatsApp →
            </a>
        </div>
    </div>
@endsection
