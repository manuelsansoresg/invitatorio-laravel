@php
    $whatsappNumber  = '529990000000';
    $whatsappMessage = $orden
        ? "Hola Invitatorio, intenté pagar el paquete {$orden->paquete_nombre} (orden #{$orden->id}) pero el pago no se completó. ¿Me pueden ayudar?"
        : 'Hola Invitatorio, tuve un problema al intentar pagar en su sitio. ¿Me pueden ayudar?';
    $whatsappUrl = 'https://wa.me/' . $whatsappNumber . '?text=' . urlencode($whatsappMessage);
@endphp

@extends('layouts.checkout', ['title' => 'Pago no completado'])

@section('content')
    <div class="mx-auto max-w-2xl">
        <div class="overflow-hidden rounded-3xl bg-white shadow-[0_30px_60px_-30px_rgba(43,20,63,0.35)] ring-1 ring-[#F1E6D9]">

            <div class="relative overflow-hidden bg-gradient-to-br from-[#9B1C1C] to-[#6B0F0F] px-6 py-12 text-center text-white sm:px-10 sm:py-14">
                <div class="pointer-events-none absolute inset-0 opacity-20" aria-hidden="true">
                    <div class="absolute -left-10 -top-10 h-40 w-40 rounded-full bg-white/30 blur-3xl"></div>
                    <div class="absolute -right-10 -bottom-10 h-40 w-40 rounded-full bg-[#FCA5A5]/40 blur-3xl"></div>
                </div>

                <div class="relative mx-auto mb-6 flex h-20 w-20 items-center justify-center sm:h-24 sm:w-24">
                    <div class="absolute inset-0 rounded-full bg-white/20 blur-xl" aria-hidden="true"></div>
                    <div class="relative flex h-full w-full items-center justify-center rounded-full bg-white shadow-2xl">
                        <svg class="h-10 w-10 text-[#9B1C1C] sm:h-12 sm:w-12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <circle cx="12" cy="12" r="9"/>
                            <path d="M9 9l6 6M15 9l-6 6"/>
                        </svg>
                    </div>
                </div>

                <h1 class="relative font-display text-3xl font-extrabold leading-tight sm:text-4xl">
                    No pudimos completar el pago
                </h1>
                <p class="relative mt-3 text-base text-white/90 sm:text-lg">
                    Pero no te preocupes, no cobramos nada.
                </p>
            </div>

            <div class="px-6 py-8 text-center sm:px-10 sm:py-10">
                @if ($orden)
                    <p class="text-[13px] uppercase tracking-wider text-[#5F5A66]">Orden</p>
                    <p class="mt-1 font-display text-2xl font-extrabold text-[#2B143F]">
                        #{{ str_pad((string) $orden->id, 6, '0', STR_PAD_LEFT) }}
                    </p>
                    <p class="mt-1 text-sm text-[#5F5A66]">{{ $orden->paquete_nombre }} · {{ $orden->precio_formateado }} MXN</p>
                @endif

                <div class="mt-7 rounded-2xl bg-[#FEF6F6] p-5 text-left text-sm text-[#7F1D1D]">
                    <p class="font-semibold">¿Por qué pudo haber pasado?</p>
                    <ul class="mt-2 list-disc space-y-1 pl-5 text-[13px]">
                        <li>La tarjeta fue rechazada por el banco.</li>
                        <li>Datos de la tarjeta incorrectos.</li>
                        <li>Fondos insuficientes o límite excedido.</li>
                        <li>Cancelaste el pago antes de terminarlo.</li>
                    </ul>
                </div>

                <p class="mt-6 text-[15px] leading-relaxed text-[#5F5A66]">
                    Si fue un error y quieres intentar de nuevo, puedes regresar a la página del paquete.
                    Si necesitas ayuda para completar el pago, escríbenos por WhatsApp.
                </p>

                <div class="mt-7 flex flex-col gap-3 sm:flex-row sm:justify-center">
                    @if ($orden)
                        <a
                            href="{{ route('checkout.show', $orden->paquete) }}"
                            class="inline-flex items-center justify-center gap-2 rounded-lg bg-[#EB7512] px-5 py-3 text-[15px] font-semibold text-white shadow-md shadow-orange-500/25 transition hover:-translate-y-0.5 hover:bg-[#F45A00]"
                        >
                            Intentar de nuevo
                        </a>
                    @endif
                    <a
                        href="{{ $whatsappUrl }}"
                        target="_blank" rel="noopener"
                        class="inline-flex items-center justify-center gap-2 rounded-lg border-2 border-[#25D366] px-5 py-3 text-[15px] font-semibold text-[#1F8B4C] transition hover:bg-[#E7F8EE]"
                    >
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.967-.94 1.164-.173.198-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.71.306 1.263.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.002-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884"/>
                        </svg>
                        Ayuda por WhatsApp
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
