@php
    $whatsappNumber  = '529992685617';
    $whatsappMessage = $orden
        ? "Hola Invitatorio, acabo de pagar el paquete {$orden->paquete_nombre} (orden #{$orden->id}). Mi nombre es {$orden->comprador_nombre}."
        : 'Hola Invitatorio, acabo de realizar un pago en el sitio.';
    $whatsappUrl = 'https://wa.me/' . $whatsappNumber . '?text=' . urlencode($whatsappMessage);

    // Regla de producto: para WEB el cliente se autogestiona con
    // templates en su panel. Para IMAGEN/VIDEO nosotros (admin)
    // diseñamos y entregamos el archivo. El copy del "qué sigue"
    // y los CTAs cambian según el formato del paquete.
    $formato = $orden?->paquete?->formato;
    $esWeb   = $formato === 'web';
@endphp

@extends('layouts.checkout', ['title' => 'Pago confirmado'])

@section('content')
    <div class="mx-auto max-w-3xl">
        {{-- ══════════════ HERO DE CONFIRMACIÓN ══════════════ --}}
        <div class="overflow-hidden rounded-3xl bg-white shadow-[0_30px_60px_-30px_rgba(43,20,63,0.35)] ring-1 ring-[#F1E6D9]">

            {{-- Banda superior con checkmark animado --}}
            <div class="relative overflow-hidden bg-gradient-to-br from-[#1F8B4C] to-[#0F5F33] px-6 py-12 text-center text-white sm:px-10 sm:py-14">
                {{-- Halo de fondo --}}
                <div class="pointer-events-none absolute inset-0 opacity-20" aria-hidden="true">
                    <div class="absolute -left-10 -top-10 h-40 w-40 rounded-full bg-white/30 blur-3xl"></div>
                    <div class="absolute -right-10 -bottom-10 h-40 w-40 rounded-full bg-[#F09719]/40 blur-3xl"></div>
                </div>

                {{-- Checkmark --}}
                <div class="relative mx-auto mb-6 flex h-20 w-20 items-center justify-center sm:h-24 sm:w-24">
                    <div class="absolute inset-0 rounded-full bg-white/20 blur-xl" aria-hidden="true"></div>
                    <div class="relative flex h-full w-full items-center justify-center rounded-full bg-white shadow-2xl">
                        <svg class="h-10 w-10 text-[#1F8B4C] sm:h-12 sm:w-12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M20 6L9 17l-5-5" class="check-draw" />
                        </svg>
                    </div>
                </div>

                <h1 class="relative font-display text-3xl font-extrabold leading-tight sm:text-4xl lg:text-5xl">
                    ¡Listo! Tu pago se procesó
                </h1>
                <p class="relative mt-3 text-base text-white/90 sm:text-lg">
                    Gracias por confiar en Invitatorio 🎉
                </p>
            </div>

            {{-- ══════════════ RESUMEN DE LA ORDEN ══════════════ --}}
            @if ($orden)
                <div class="px-6 py-8 sm:px-10 sm:py-10">

                    <div class="mb-6 flex items-center justify-between gap-4 border-b border-[#F1E6D9] pb-5">
                        <div>
                            <p class="text-[11px] font-semibold uppercase tracking-[0.18em] text-[#5F5A66]">Orden</p>
                            <p class="mt-0.5 font-display text-2xl font-extrabold text-[#2B143F]">
                                #{{ str_pad((string) $orden->id, 6, '0', STR_PAD_LEFT) }}
                            </p>
                        </div>
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-[#E7F8EE] px-3 py-1.5 text-[11px] font-bold uppercase tracking-wider text-[#1F8B4C]">
                            <span class="h-1.5 w-1.5 rounded-full bg-[#1F8B4C]"></span>
                            Pagado
                        </span>
                    </div>

                    <dl class="divide-y divide-[#F1E6D9] text-sm">
                        <div class="grid grid-cols-2 gap-3 py-3">
                            <dt class="text-[#5F5A66]">Paquete</dt>
                            <dd class="text-right font-semibold text-[#2B143F]">{{ $orden->paquete_nombre }}</dd>
                        </div>

                        @if ($orden->tieneCupon())
                            <div class="grid grid-cols-2 gap-3 py-3">
                                <dt class="text-[#5F5A66]">Subtotal</dt>
                                <dd class="text-right font-medium text-[#18111F]">
                                    {{ $orden->precio_formateado }} <span class="text-xs text-[#5F5A66]">MXN</span>
                                </dd>
                            </div>
                            <div class="grid grid-cols-2 gap-3 py-3">
                                <dt class="text-[#0F5F33]">
                                    Cupón
                                    <span class="ml-1 inline-flex items-center rounded-md bg-[#E7F8EE] px-1.5 py-0.5 font-mono text-[10px] font-bold uppercase tracking-wider text-[#1F8B4C]">
                                        {{ $orden->cupon_codigo }}
                                    </span>
                                </dt>
                                <dd class="text-right font-medium text-[#0F5F33]">
                                    -${{ number_format($orden->descuento_centavos / 100, 0, '.', ',') }} <span class="text-xs">MXN</span>
                                </dd>
                            </div>
                            <div class="grid grid-cols-2 gap-3 py-3">
                                <dt class="text-[#5F5A66]">Total pagado</dt>
                                <dd class="text-right font-display text-lg font-extrabold text-[#2B143F]">
                                    ${{ number_format($orden->total_final_centavos / 100, 0, '.', ',') }} <span class="text-sm font-semibold text-[#5F5A66]">MXN</span>
                                </dd>
                            </div>
                        @else
                            <div class="grid grid-cols-2 gap-3 py-3">
                                <dt class="text-[#5F5A66]">Total</dt>
                                <dd class="text-right font-display text-lg font-extrabold text-[#2B143F]">
                                    {{ $orden->precio_formateado }} <span class="text-sm font-semibold text-[#5F5A66]">MXN</span>
                                </dd>
                            </div>
                        @endif

                        <div class="grid grid-cols-2 gap-3 py-3">
                            <dt class="text-[#5F5A66]">Nombre</dt>
                            <dd class="text-right font-medium text-[#18111F]">{{ $orden->comprador_nombre }}</dd>
                        </div>
                        <div class="grid grid-cols-2 gap-3 py-3">
                            <dt class="text-[#5F5A66]">Email</dt>
                            <dd class="text-right break-all font-medium text-[#18111F]">{{ $orden->comprador_email }}</dd>
                        </div>
                        <div class="grid grid-cols-2 gap-3 py-3">
                            <dt class="text-[#5F5A66]">Fecha de pago</dt>
                            <dd class="text-right font-medium text-[#18111F]">
                                {{ optional($orden->paid_at)->format('d/m/Y H:i') ?? now()->format('d/m/Y H:i') }}
                            </dd>
                        </div>
                    </dl>

                    {{-- ══════════════ QUÉ SIGUE AHORA ══════════════ --}}
                    <div class="mt-8 rounded-2xl border border-[#F1E6D9] bg-[#FFFDF8] p-5 sm:p-6">
                        <p class="mb-4 inline-flex items-center gap-2 text-[11px] font-semibold uppercase tracking-[0.18em] text-[#EB7512]">
                            <span class="h-px w-6 bg-[#EB7512]"></span>
                            ¿Qué sigue?
                        </p>
                        <ol class="space-y-4">
                            @if ($esWeb)
                                <li class="flex gap-4">
                                    <span class="inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-[#FFF1E1] text-sm font-extrabold text-[#EB7512]">1</span>
                                    <div>
                                        <p class="font-semibold text-[#2B143F]">Entra a tu panel</p>
                                        <p class="mt-0.5 text-[13px] text-[#5F5A66]">Ya tu cuenta está activa. Usa el botón de abajo para entrar.</p>
                                    </div>
                                </li>
                                <li class="flex gap-4">
                                    <span class="inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-[#F4EEFB] text-sm font-extrabold text-[#5A3087]">2</span>
                                    <div>
                                        <p class="font-semibold text-[#2B143F]">Elige un template</p>
                                        <p class="mt-0.5 text-[13px] text-[#5F5A66]">Tienes varios disponibles en tu panel. Escoge el que más te guste y personalízalo con tus datos.</p>
                                    </div>
                                </li>
                                <li class="flex gap-4">
                                    <span class="inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-[#FFF1E1] text-sm font-extrabold text-[#EB7512]">3</span>
                                    <div>
                                        <p class="font-semibold text-[#2B143F]">Publica y comparte</p>
                                        <p class="mt-0.5 text-[13px] text-[#5F5A66]">Cuando esté lista, te damos un link que puedes mandar por WhatsApp a tus invitados.</p>
                                    </div>
                                </li>
                            @else
                                <li class="flex gap-4">
                                    <span class="inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-[#FFF1E1] text-sm font-extrabold text-[#EB7512]">1</span>
                                    <div>
                                        <p class="font-semibold text-[#2B143F]">Te escribimos por WhatsApp en menos de 24h</p>
                                        <p class="mt-0.5 text-[13px] text-[#5F5A66]">Con los datos que necesitamos para empezar tu invitación (nombres, fecha, fotos, etc).</p>
                                    </div>
                                </li>
                                <li class="flex gap-4">
                                    <span class="inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-[#F4EEFB] text-sm font-extrabold text-[#5A3087]">2</span>
                                    <div>
                                        <p class="font-semibold text-[#2B143F]">Diseñamos tu invitación</p>
                                        <p class="mt-0.5 text-[13px] text-[#5F5A66]">La armamos con los datos que nos mandes y te mostramos un preview para que la apruebes.</p>
                                    </div>
                                </li>
                                <li class="flex gap-4">
                                    <span class="inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-[#FFF1E1] text-sm font-extrabold text-[#EB7512]">3</span>
                                    <div>
                                        <p class="font-semibold text-[#2B143F]">Recibes el archivo listo</p>
                                        <p class="mt-0.5 text-[13px] text-[#5F5A66]">Te lo mandamos en alta calidad para que lo compartas por WhatsApp con tus invitados.</p>
                                    </div>
                                </li>
                            @endif
                        </ol>
                    </div>

                    {{-- CTAs: cambian según formato del paquete y si está logueado --}}
                    <div class="mt-7 flex flex-col gap-3 sm:flex-row">
                        @auth
                            @if ($esWeb)
                                <a
                                    href="{{ route('panel.invitaciones.index') }}"
                                    class="inline-flex flex-1 items-center justify-center gap-2 rounded-lg bg-[#1F8B4C] px-5 py-3 text-[15px] font-semibold text-white shadow-md shadow-green-700/25 transition hover:-translate-y-0.5 hover:bg-[#0F5F33]"
                                >
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                                        <polyline points="9 22 9 12 15 12 15 22"/>
                                    </svg>
                                    Ir a mi panel
                                </a>
                                <a
                                    href="{{ $whatsappUrl }}"
                                    target="_blank" rel="noopener"
                                    class="inline-flex flex-1 items-center justify-center gap-2 rounded-lg border-2 border-[#EB7512] px-5 py-3 text-[15px] font-semibold text-[#EB7512] transition hover:bg-[#FFF1E1]"
                                >
                                    Escribir por WhatsApp
                                </a>
                            @else
                                <a
                                    href="{{ $whatsappUrl }}"
                                    target="_blank" rel="noopener"
                                    class="inline-flex flex-1 items-center justify-center gap-2 rounded-lg bg-[#EB7512] px-5 py-3 text-[15px] font-semibold text-white shadow-md shadow-orange-500/25 transition hover:-translate-y-0.5 hover:bg-[#F45A00]"
                                >
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.967-.94 1.164-.173.198-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.71.306 1.263.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.002-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884"/>
                                    </svg>
                                    Acelerar por WhatsApp
                                </a>
                                <a
                                    href="{{ route('panel.invitaciones.index') }}"
                                    class="inline-flex flex-1 items-center justify-center gap-2 rounded-lg border-2 border-[#1F8B4C] px-5 py-3 text-[15px] font-semibold text-[#1F8B4C] transition hover:bg-[#E7F8EE]"
                                >
                                    Ir a mi panel
                                </a>
                            @endif
                        @else
                            {{-- Edge case: cliente no logueado en la success (pago legacy o sesión perdida) --}}
                            <a
                                href="{{ route('login', ['next' => route('panel.invitaciones.index', [], false)]) }}"
                                class="inline-flex flex-1 items-center justify-center gap-2 rounded-lg bg-[#EB7512] px-5 py-3 text-[15px] font-semibold text-white shadow-md shadow-orange-500/25 transition hover:-translate-y-0.5 hover:bg-[#F45A00]"
                            >
                                Iniciar sesión
                            </a>
                            <a
                                href="{{ url('/') }}"
                                class="inline-flex flex-1 items-center justify-center gap-2 rounded-lg border-2 border-[#EB7512] px-5 py-3 text-[15px] font-semibold text-[#EB7512] transition hover:bg-[#FFF1E1]"
                            >
                                Volver al inicio
                            </a>
                        @endauth
                    </div>

                </div>
            @else
                {{-- Fallback si por algo no tenemos la orden en sesión --}}
                <div class="px-6 py-10 text-center sm:px-10">
                    <p class="text-[15px] text-[#5F5A66]">
                        Recibimos tu pago correctamente. En breve te contactamos al correo registrado en Mercado Pago.
                    </p>
                    <a
                        href="{{ url('/') }}"
                        class="mt-6 inline-flex items-center justify-center rounded-lg bg-[#EB7512] px-6 py-3 text-[15px] font-semibold text-white transition hover:bg-[#F45A00]"
                    >
                        Volver al inicio
                    </a>
                </div>
            @endif
        </div>

        {{-- Recibo --}}
        <p class="mt-6 text-center text-[12px] text-[#5F5A66]">
            Guarda el número de orden para cualquier seguimiento. También te enviamos un comprobante al correo registrado.
        </p>
    </div>

    {{-- Animación del checkmark: se dibuja la palomita al cargar la página --}}
    @once
        @push('head')
            <style>
                .check-draw {
                    stroke-dasharray: 30;
                    stroke-dashoffset: 30;
                    animation: check-draw 0.6s cubic-bezier(0.65, 0, 0.45, 1) 0.2s forwards;
                }
                @keyframes check-draw {
                    to { stroke-dashoffset: 0; }
                }
            </style>
        @endpush
    @endonce
@endsection
