@extends('layouts.checkout', ['title' => 'Pagar paquete ' . $paquete->nombre])

@section('content')
    @php
        $whatsappNumber  = '529992685617';
        $whatsappMessage = 'Hola Invitatorio, tengo una duda sobre el paquete ' . $paquete->nombre . '.';
        $whatsappUrl     = 'https://wa.me/' . $whatsappNumber . '?text=' . urlencode($whatsappMessage);

        $fmtLabel = match ($paquete->formato) {
            'web'    => 'Invitación web',
            'imagen' => 'Invitación en imagen',
            'video'  => 'Invitación en video',
            default  => 'Invitación',
        };

        $subtotalFmt       = '$' . number_format($paquete->precio_centavos / 100, 0, '.', ',');
        $descuentoFmt      = '$' . number_format($descuentoCentavos / 100, 0, '.', ',');
        $totalFinalFmt     = '$' . number_format($totalFinalCentavos / 100, 0, '.', ',');
        $cuponAplicado     = $couponOk && $coupon;

        $authUser    = $authUser ?? null;
        $isLoggedIn  = (bool) $authUser;
        // Para el caso "ya registrado": al fallar el POST, el server
        // hace back()->with('login_url', ...) con la URL correcta que
        // incluye el slug del paquete. Si no, usamos la default.
        $loginUrl    = session('login_url')
            ?: route('login', ['next' => route('checkout.show', $paquete, false)]);

        // Regla de producto: web = cliente se autogestiona en el panel.
        // imagen/video = nosotros diseñamos y entregamos. Esto afecta
        // los bullets de "Beneficios del pago" en la columna derecha.
        $esWebCheckout = $paquete->formato === 'web';
    @endphp

    <div class="grid gap-8 lg:grid-cols-[1.1fr_0.9fr] lg:gap-10">
        {{-- ══════════════════════ COLUMNA IZQ: FORM ══════════════════════ --}}
        <section>
            <nav class="mb-4 flex items-center gap-2 text-[12px] font-semibold uppercase tracking-wider text-[#EB7512]">
                <a href="{{ url('/#paquetes') }}" class="hover:text-[#F45A00] transition-colors">← Paquetes</a>
            </nav>

            <h1 class="font-display text-3xl font-extrabold leading-tight text-[#2B143F] sm:text-4xl">
                Estás a un paso de <span class="text-[#EB7512]">comprar tu paquete</span>
            </h1>
            <p class="mt-3 text-[15px] text-[#5F5A66]">
                Llena tus datos y te llevamos a Mercado Pago para completar el pago de forma segura.
            </p>

            {{-- Stepper --}}
            <ol class="mt-6 flex items-center gap-3 text-[12px] font-semibold uppercase tracking-wider text-[#5F5A66] sm:gap-5">
                <li class="flex items-center gap-2 text-[#EB7512]">
                    <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-[#EB7512] text-[11px] font-extrabold text-white">1</span>
                    Tus datos
                </li>
                <span class="h-px flex-1 bg-[#F1E6D9]" aria-hidden="true"></span>
                <li class="flex items-center gap-2 text-[#5F5A66]">
                    <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-[#F1E6D9] text-[#2B143F]">2</span>
                    Pago en MP
                </li>
                <span class="h-px flex-1 bg-[#F1E6D9]" aria-hidden="true"></span>
                <li class="flex items-center gap-2 text-[#5F5A66]">
                    <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-[#F1E6D9] text-[#2B143F]">3</span>
                    Confirmación
                </li>
            </ol>

            {{-- Errores --}}
            @if ($errors->any())
                <div class="mt-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    <p class="font-semibold">Revisa estos datos:</p>
                    <ul class="mt-1 list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Banner de cupón si viene por URL --}}
            @if ($cuponAplicado)
                <div class="mt-6 flex items-start gap-3 rounded-lg border border-[#A8E1B5] bg-[#E7F8EE] px-4 py-3 text-sm text-[#0F5F33]">
                    <svg class="mt-0.5 h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M20 6L9 17l-5-5"/>
                    </svg>
                    <div>
                        <p class="font-bold">¡Cupón <span class="font-mono">{{ $coupon->codigo }}</span> aplicado!</p>
                        <p class="mt-0.5 text-[13px]">Tienes {{ $coupon->descuento_legible }} de descuento sobre el precio de este paquete.</p>
                    </div>
                </div>
            @elseif ($couponCodigo === null && filled($couponMensaje))
                <div class="mt-6 flex items-start gap-3 rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">
                    <svg class="mt-0.5 h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <circle cx="12" cy="12" r="9"/><path d="M12 8v4M12 16h.01"/>
                    </svg>
                    <div>
                        <p class="font-bold">No pudimos aplicar tu cupón.</p>
                        <p class="mt-0.5 text-[13px]">{{ $couponMensaje }}</p>
                    </div>
                </div>
            @endif

            {{-- Si NO está logueado, le recordamos que ya tiene cuenta? --}}
            @unless ($isLoggedIn)
                <div class="mt-6 flex items-center justify-between gap-3 rounded-lg border border-[#F1E6D9] bg-[#FFFDF8] px-4 py-3 text-sm">
                    <div class="flex items-center gap-2 text-[#5F5A66]">
                        <svg class="h-4 w-4 text-[#5A3087]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                            <circle cx="12" cy="7" r="4"/>
                        </svg>
                        <span>¿Ya tienes cuenta con nosotros?</span>
                    </div>
                    <a href="{{ $loginUrl }}" class="font-semibold text-[#EB7512] hover:text-[#F45A00]">Inicia sesión →</a>
                </div>
            @endunless

            {{-- Form --}}

            {{-- Si ya está logueado, mostramos quién está comprando (FUERA del form de checkout) --}}
            @if ($isLoggedIn)
                <div class="mt-6 flex items-center justify-between gap-3 rounded-lg border border-[#A8E1B5] bg-[#E7F8EE] px-4 py-3 text-sm">
                    <div class="flex items-center gap-2.5 text-[#0F5F33]">
                        <span class="inline-flex h-7 w-7 items-center justify-center rounded-full bg-[#1F8B4C] text-xs font-extrabold text-white">
                            {{ mb_strtoupper(mb_substr($authUser->name, 0, 1)) }}
                        </span>
                        <div>
                            <p class="font-semibold">Comprando como {{ $authUser->name }}</p>
                            <p class="text-[12px] text-[#0F5F33]/80">{{ $authUser->email }}</p>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" class="m-0 p-0">
                        @csrf
                        <button type="submit" class="text-[12px] font-semibold text-[#1F8B4C] underline-offset-2 hover:underline">¿No eres tú? Cerrar sesión</button>
                    </form>
                </div>
            @endif

            <form method="POST" action="{{ route('checkout.buy', $paquete) }}" class="mt-6 space-y-5">
                @csrf

                {{-- Campo de cupón (opcional, oculto si ya viene aplicado por URL) --}}
                <details class="group rounded-lg border border-[#F1E6D9] bg-white" @if($cuponAplicado || $couponCodigo) open @endif>
                    <summary class="flex cursor-pointer list-none items-center justify-between px-4 py-3 [&::-webkit-details-marker]:hidden">
                        <span class="flex items-center gap-2 text-sm font-semibold text-[#2B143F]">
                            <svg class="h-4 w-4 text-[#EB7512]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M20 12V8a2 2 0 0 0-2-2h-4l-2-2H6a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-4"/>
                                <path d="M14 14h.01"/>
                            </svg>
                            ¿Tienes un cupón?
                        </span>
                        <svg class="h-4 w-4 text-[#5F5A66] transition group-open:rotate-180" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M6 9l6 6 6-6"/>
                        </svg>
                    </summary>
                    <div class="border-t border-[#F1E6D9] px-4 py-4">
                        <label for="coupon" class="block text-sm font-semibold text-[#2B143F]">Código de descuento</label>
                        <div class="mt-2 flex gap-2">
                            <input
                                type="text"
                                name="coupon"
                                id="coupon"
                                maxlength="40"
                                autocomplete="off"
                                placeholder="VERANO20"
                                value="{{ old('coupon', $couponCodigo ?? '') }}"
                                class="flex-1 rounded-lg border border-[#F1E6D9] bg-white px-4 py-3 font-mono text-sm uppercase outline-none transition focus:border-[#EB7512] focus:ring-2 focus:ring-[#FFF1E1]"
                            >
                        </div>
                        <p class="mt-1.5 text-xs text-[#5F5A66]">Déjalo vacío si no tienes. Se aplica al confirmar el pago.</p>
                    </div>
                </details>

                <div>
                    <label for="comprador_nombre" class="mb-1.5 block text-sm font-semibold text-[#2B143F]">
                        Nombre completo <span class="text-[#EB7512]">*</span>
                    </label>
                    <input
                        type="text"
                        name="comprador_nombre"
                        id="comprador_nombre"
                        value="{{ old('comprador_nombre', $isLoggedIn ? $authUser->name : '') }}"
                        @unless($isLoggedIn) required @endunless
                        autocomplete="name"
                        placeholder="¿Cómo te llamamos?"
                        class="w-full rounded-lg border border-[#F1E6D9] bg-white px-4 py-3 text-[15px] text-[#18111F] placeholder-[#9CA3AF] outline-none transition focus:border-[#EB7512] focus:ring-2 focus:ring-[#FFF1E1]"
                    >
                </div>

                <div>
                    <label for="comprador_email" class="mb-1.5 block text-sm font-semibold text-[#2B143F]">
                        Email <span class="text-[#EB7512]">*</span>
                    </label>
                    <input
                        type="email"
                        name="comprador_email"
                        id="comprador_email"
                        value="{{ old('comprador_email', $isLoggedIn ? $authUser->email : '') }}"
                        @unless($isLoggedIn) required @endunless
                        autocomplete="email"
                        placeholder="tunombre@correo.com"
                        class="w-full rounded-lg border border-[#F1E6D9] bg-white px-4 py-3 text-[15px] text-[#18111F] placeholder-[#9CA3AF] outline-none transition focus:border-[#EB7512] focus:ring-2 focus:ring-[#FFF1E1]"
                    >
                    <p class="mt-1.5 text-xs text-[#5F5A66]">
                        @if ($isLoggedIn)
                            Tu cuenta está activa en este email. Te enviaremos la confirmación aquí.
                        @else
                            Te enviaremos la confirmación y los siguientes pasos a este correo. Será tu usuario para entrar al panel.
                        @endif
                    </p>
                </div>

                {{-- Password solo si NO está logueado (ya tiene cuenta) --}}
                @unless ($isLoggedIn)
                    <div>
                        <label for="password" class="mb-1.5 block text-sm font-semibold text-[#2B143F]">
                            Crea una contraseña <span class="text-[#EB7512]">*</span>
                        </label>
                        <div class="relative">
                            <input
                                type="password"
                                name="password"
                                id="password"
                                required
                                minlength="8"
                                autocomplete="new-password"
                                placeholder="Mínimo 8 caracteres"
                                class="w-full rounded-lg border border-[#F1E6D9] bg-white px-4 py-3 pr-12 text-[15px] text-[#18111F] placeholder-[#9CA3AF] outline-none transition focus:border-[#EB7512] focus:ring-2 focus:ring-[#FFF1E1]"
                            >
                            <button
                                type="button"
                                data-toggle-password="password"
                                class="absolute inset-y-0 right-0 flex items-center px-3 text-[#5F5A66] hover:text-[#2B143F]"
                                aria-label="Mostrar u ocultar contraseña"
                            >
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                    <circle cx="12" cy="12" r="3"/>
                                </svg>
                            </button>
                        </div>
                        <p class="mt-1.5 text-xs text-[#5F5A66]">La usarás para entrar a tu panel y editar tu invitación cuando quieras.</p>
                    </div>

                    <div>
                        <label for="password_confirmation" class="mb-1.5 block text-sm font-semibold text-[#2B143F]">
                            Repite la contraseña <span class="text-[#EB7512]">*</span>
                        </label>
                        <div class="relative">
                            <input
                                type="password"
                                name="password_confirmation"
                                id="password_confirmation"
                                required
                                minlength="8"
                                autocomplete="new-password"
                                placeholder="Escríbela de nuevo"
                                class="w-full rounded-lg border border-[#F1E6D9] bg-white px-4 py-3 pr-12 text-[15px] text-[#18111F] placeholder-[#9CA3AF] outline-none transition focus:border-[#EB7512] focus:ring-2 focus:ring-[#FFF1E1]"
                            >
                            <button
                                type="button"
                                data-toggle-password="password_confirmation"
                                class="absolute inset-y-0 right-0 flex items-center px-3 text-[#5F5A66] hover:text-[#2B143F]"
                                aria-label="Mostrar u ocultar contraseña"
                            >
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                    <circle cx="12" cy="12" r="3"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                @endunless

                <div>
                    <label for="comprador_telefono" class="mb-1.5 block text-sm font-semibold text-[#2B143F]">
                        WhatsApp (opcional)
                    </label>
                    <input
                        type="tel"
                        name="comprador_telefono"
                        id="comprador_telefono"
                        value="{{ old('comprador_telefono') }}"
                        autocomplete="tel"
                        inputmode="tel"
                        placeholder="55 1234 5678"
                        class="w-full rounded-lg border border-[#F1E6D9] bg-white px-4 py-3 text-[15px] text-[#18111F] placeholder-[#9CA3AF] outline-none transition focus:border-[#EB7512] focus:ring-2 focus:ring-[#FFF1E1]"
                    >
                </div>

                <div>
                    <label for="tipo_evento" class="mb-1.5 block text-sm font-semibold text-[#2B143F]">
                        ¿Qué vas a celebrar?
                    </label>
                    <select
                        name="tipo_evento"
                        id="tipo_evento"
                        class="w-full appearance-none rounded-lg border border-[#F1E6D9] bg-white bg-no-repeat px-4 py-3 text-[15px] text-[#18111F] outline-none transition focus:border-[#EB7512] focus:ring-2 focus:ring-[#FFF1E1]"
                        style="background-image: url('data:image/svg+xml;utf8,<svg xmlns=&quot;http://www.w3.org/2000/svg&quot; viewBox=&quot;0 0 24 24&quot; fill=&quot;none&quot; stroke=&quot;%235F5A66&quot; stroke-width=&quot;2&quot; stroke-linecap=&quot;round&quot;><path d=&quot;M6 9l6 6 6-6&quot;/></svg>'); background-position: right 0.9rem center; background-size: 1rem; padding-right: 2.5rem;"
                    >
                        <option value="">— Selecciona —</option>
                        @foreach (($formatos ?? []) as $value => $label)
                            <option value="{{ $value }}" @selected(old('tipo_evento') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <label class="flex items-start gap-3 text-sm text-[#18111F]">
                    <input
                        type="checkbox"
                        name="terminos"
                        value="1"
                        required
                        class="mt-0.5 h-4 w-4 rounded border-[#F1E6D9] text-[#EB7512] focus:ring-2 focus:ring-[#FFF1E1]"
                        @checked(old('terminos'))
                    >
                    <span>
                        Acepto los
                        <a href="{{ route('politicas') }}" target="_blank" rel="noopener" class="font-semibold text-[#EB7512] hover:text-[#F45A00] underline-offset-2 hover:underline">términos y políticas de privacidad</a>
                        y autorizo el cargo por el paquete seleccionado.
                    </span>
                </label>

                <button
                    type="submit"
                    class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-[#EB7512] px-6 py-3.5 text-base font-semibold text-white shadow-lg shadow-orange-500/30 transition-all hover:-translate-y-0.5 hover:bg-[#F45A00] focus:outline-none focus:ring-2 focus:ring-[#FFF1E1] focus:ring-offset-2"
                >
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <rect x="2" y="5" width="20" height="14" rx="2"/>
                        <path d="M2 10h20"/>
                    </svg>
                    @if ($cuponAplicado)
                        Pagar {{ $totalFinalFmt }} MXN con Mercado Pago
                    @else
                        Pagar {{ $paquete->precio_formateado }} MXN con Mercado Pago
                    @endif
                </button>

                <p class="text-center text-[12px] text-[#5F5A66]">
                    Vas a ser redirigido al sitio seguro de Mercado Pago.
                </p>
            </form>
        </section>

        {{-- ══════════════════════ COLUMNA DER: RESUMEN ══════════════════════ --}}
        <aside class="lg:sticky lg:top-8 lg:self-start">
            <div class="overflow-hidden rounded-2xl border border-[#F1E6D9] bg-white shadow-[0_18px_40px_-22px_rgba(43,20,63,0.25)]">

                {{-- Header con el formato --}}
                <div class="bg-gradient-to-br from-[#2B143F] to-[#5A3087] px-6 py-5 text-white">
                    <p class="text-[11px] font-semibold uppercase tracking-[0.18em] text-[#F09719]">
                        {{ $fmtLabel }}
                    </p>
                    <h2 class="mt-1 font-display text-2xl font-extrabold leading-tight">
                        {{ $paquete->nombre }}
                    </h2>
                    <p class="mt-1.5 text-sm text-white/85">
                        {{ $paquete->descripcion }}
                    </p>
                </div>

                {{-- Resumen de precio (con o sin descuento) --}}
                <div class="border-b border-[#F1E6D9] px-6 py-5">
                    <p class="mb-3 text-[11px] font-semibold uppercase tracking-wider text-[#5F5A66]">Resumen</p>

                    @if ($cuponAplicado)
                        <div class="space-y-2 text-sm">
                            <div class="flex items-baseline justify-between">
                                <span class="text-[#5F5A66]">Subtotal</span>
                                <span class="font-medium text-[#18111F]">{{ $subtotalFmt }} MXN</span>
                            </div>
                            <div class="flex items-baseline justify-between">
                                <span class="text-[#0F5F33]">
                                    Cupón
                                    <span class="ml-1 inline-flex items-center rounded-md bg-[#E7F8EE] px-1.5 py-0.5 font-mono text-[10px] font-bold uppercase tracking-wider text-[#1F8B4C]">
                                        {{ $coupon->codigo }}
                                    </span>
                                </span>
                                <span class="font-medium text-[#0F5F33]">-{{ $descuentoFmt }} MXN</span>
                            </div>
                            <div class="mt-3 flex items-baseline justify-between border-t border-[#F1E6D9] pt-3">
                                <span class="text-sm font-semibold text-[#5F5A66]">Total a pagar</span>
                                <span class="font-display text-3xl font-extrabold leading-none text-[#2B143F]">
                                    {{ $totalFinalFmt }}
                                    <span class="text-base font-semibold text-[#5F5A66]">MXN</span>
                                </span>
                            </div>
                            <p class="text-right text-[11px] font-semibold text-[#1F8B4C]">
                                Te ahorras {{ $descuentoFmt }} MXN
                            </p>
                        </div>
                    @else
                        <div class="flex items-baseline justify-between">
                            <span class="text-sm font-semibold text-[#5F5A66]">Total a pagar</span>
                            <span class="font-display text-3xl font-extrabold leading-none text-[#2B143F]">
                                {{ $paquete->precio_formateado }}
                                <span class="text-base font-semibold text-[#5F5A66]">MXN</span>
                            </span>
                        </div>
                    @endif
                </div>

                {{-- Items --}}
                <div class="px-6 py-5">
                    <p class="mb-3 text-[11px] font-semibold uppercase tracking-wider text-[#5F5A66]">Qué incluye</p>
                    <ul class="space-y-2.5 text-sm text-[#18111F]">
                        @foreach ($paquete->items ?? [] as $item)
                            <li class="flex gap-2.5">
                                <svg class="mt-0.5 h-4 w-4 shrink-0 text-[#EB7512]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M20 6L9 17l-5-5"/>
                                </svg>
                                <span class="{{ str_starts_with($item, 'Todo lo') ? 'font-semibold' : '' }}">{{ $item }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>

                {{-- Beneficios del pago (cambian según formato) --}}
                <div class="space-y-2.5 border-t border-[#F1E6D9] bg-[#FFFDF8] px-6 py-5 text-[13px] text-[#5F5A66]">
                    <div class="flex items-center gap-2.5">
                        <svg class="h-4 w-4 text-[#5A3087]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                        </svg>
                        <span>Pago 100% seguro con encriptación SSL</span>
                    </div>
                    @if ($esWebCheckout)
                        <div class="flex items-center gap-2.5">
                            <svg class="h-4 w-4 text-[#5A3087]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4z"/>
                            </svg>
                            <span>Diseñas tu invitación tú mismo desde el panel</span>
                        </div>
                        <div class="flex items-center gap-2.5">
                            <svg class="h-4 w-4 text-[#5A3087]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"/><polyline points="16 6 12 2 8 6"/><line x1="12" y1="2" x2="12" y2="15"/>
                            </svg>
                            <span>Publica y comparte el link por WhatsApp al instante</span>
                        </div>
                    @else
                        <div class="flex items-center gap-2.5">
                            <svg class="h-4 w-4 text-[#5A3087]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>
                            </svg>
                            <span>Te contactamos por WhatsApp en menos de 24h</span>
                        </div>
                        <div class="flex items-center gap-2.5">
                            <svg class="h-4 w-4 text-[#5A3087]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M9 12l2 2 4-4"/><path d="M21 12c0 5-4 9-9 9a9 9 0 1 1 0-18c2.5 0 4.7 1 6.4 2.6"/>
                            </svg>
                            <span>Diseñamos y entregamos el archivo listo para compartir</span>
                        </div>
                    @endif
                    <div class="flex items-center gap-2.5">
                        <svg class="h-4 w-4 text-[#5A3087]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <rect x="2" y="5" width="20" height="14" rx="2"/><path d="M2 10h20"/>
                        </svg>
                        <span>Tarjeta, transferencia, OXXO y Mercado Pago</span>
                    </div>
                </div>
            </div>

            {{-- Mini ayuda --}}
            <p class="mt-4 text-center text-[13px] text-[#5F5A66]">
                ¿Dudas antes de pagar?
                <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener" class="font-semibold text-[#EB7512] hover:text-[#F45A00]">Pregúntanos por WhatsApp →</a>
            </p>
        </aside>
    </div>

    @once
        @push('scripts')
            <script>
                // Toggle mostrar/ocultar contraseña en los campos del form.
                document.querySelectorAll('[data-toggle-password]').forEach(function (btn) {
                    btn.addEventListener('click', function () {
                        var targetId = btn.getAttribute('data-toggle-password');
                        var input = document.getElementById(targetId);
                        if (! input) return;
                        input.type = input.type === 'password' ? 'text' : 'password';
                    });
                });
            </script>
        @endpush
    @endonce
@endsection
