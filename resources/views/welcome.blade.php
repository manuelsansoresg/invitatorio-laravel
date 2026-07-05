<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="theme-color" content="#FFFDF8">
    <meta name="description" content="Invitatorio — invitaciones digitales modernas para toda ocasión. Comparte por WhatsApp con música, cuenta regresiva, ubicación y RSVP.">
    <meta property="og:title" content="Invitatorio — Invitaciones digitales para momentos inolvidables">
    <meta property="og:description" content="Comparte tu evento por WhatsApp con diseño moderno, música, cuenta regresiva, ubicación y confirmación de asistencia.">
    <meta property="og:image" content="/images/hero-desktop.png">
    <meta property="og:type" content="website">

    <title>Invitatorio — Invitaciones digitales para toda ocasión</title>

    @fonts

    {{-- Preload de imágenes críticas del hero --}}
    <link rel="preload" as="image" href="/images/hero-desktop.png" media="(min-width: 1024px)">
    <link rel="preload" as="image" href="/images/hero-movil.png" media="(max-width: 1023px)">
    <link rel="preload" as="image" href="/images/celular.png">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#FFFDF8] text-[#18111F] antialiased">

    {{-- ================================================================
         HERO SECTION (incluye header flotante arriba)
         ================================================================ --}}
    <section class="hero relative min-h-screen w-full overflow-hidden pb-12 lg:pb-20">

        {{-- Background image: cambia por breakpoint (móvil ↔ desktop) --}}
        <div class="absolute inset-0 -z-10 bg-[url('/images/hero-movil.png')] bg-no-repeat bg-center bg-cover
                    lg:bg-[url('/images/hero-desktop.png')]"></div>
        {{-- Sutil overlay para legibilidad del header en zonas claras --}}
        <div class="absolute inset-x-0 top-0 h-40 -z-10 bg-gradient-to-b from-white/55 via-white/15 to-transparent
                    lg:h-48"></div>

        <div class="relative mx-auto max-w-[1240px] px-5 lg:px-8 pt-4 lg:pt-8">

            {{-- ============================================================
                 HEADER DESKTOP (≥1024px)
                 ============================================================ --}}
            <header
                data-header
                class="hidden lg:flex items-center justify-between gap-6
                       bg-white/95 backdrop-blur-sm rounded-2xl
                       shadow-[0_8px_32px_-10px_rgba(43,20,63,0.18)]
                       px-7 py-3.5
                       transition-shadow duration-300"
                data-anim="fade-down" data-anim-delay="0"
            >
                {{-- Logo --}}
                <a href="/" class="shrink-0" aria-label="Invitatorio — ir al inicio">
                    <img
                        src="{{ asset('images/invitatorio_horizontal.png') }}"
                        alt="Invitatorio"
                        class="h-9 w-auto block"
                        loading="eager"
                        decoding="async"
                    >
                </a>

                {{-- Menú centrado --}}
                <nav class="flex items-center gap-7" aria-label="Menú principal">
                    <a href="#" class="nav-link nav-link-active" aria-current="page">Inicio</a>
                    <a href="#disenos" class="nav-link">Diseños</a>
                    <a href="#paquetes" class="nav-link">Paquetes</a>
                    <a href="#como-funciona" class="nav-link">Cómo funciona</a>
                    <a href="#faq" class="nav-link">Preguntas frecuentes</a>
                    <a href="#contacto" class="nav-link">Contacto</a>
                </nav>

                {{-- CTAs derecha --}}
                <div class="flex items-center gap-3">
                    <a href="https://wa.me/529990000000?text=Hola%20Invitatorio%2C%20me%20gustar%C3%ADa%20cotizar%20una%20invitaci%C3%B3n"
                       class="btn-circle"
                       aria-label="Contactar por WhatsApp"
                       target="_blank" rel="noopener">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.967-.94 1.164-.173.198-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.71.306 1.263.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.002-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z"/>
                        </svg>
                    </a>
                    <a href="#cotizar" class="inline-flex items-center justify-center gap-2 rounded-full
                                                 bg-[#EB7512] hover:bg-[#F45A00] text-white font-semibold
                                                 px-5 py-2.5 text-[15px] shadow-md shadow-orange-500/25
                                                 transition-all duration-300 hover:-translate-y-0.5">
                        Cotizar ahora
                    </a>
                </div>
            </header>

            {{-- ============================================================
                 HEADER MÓVIL (<1024px)
                 ============================================================ --}}
            <header data-header class="lg:hidden flex items-center justify-between
                                       bg-white/95 backdrop-blur-sm rounded-2xl
                                       shadow-[0_8px_32px_-10px_rgba(43,20,63,0.18)]
                                       px-4 py-3.5"
                    data-anim="fade-down" data-anim-delay="0">
                <a href="/" class="shrink-0" aria-label="Invitatorio — ir al inicio">
                    <img src="{{ asset('images/invitatorio_horizontal.png') }}"
                         alt="Invitatorio" class="h-9 w-auto block" loading="eager" decoding="async">
                </a>

                <button
                    data-menu-toggle
                    type="button"
                    class="inline-flex items-center justify-center w-10 h-10 rounded-xl
                           bg-[#FFF1E1] text-[#EB7512] hover:bg-[#EB7512] hover:text-white
                           transition-colors duration-200"
                    aria-controls="mobile-menu"
                    aria-expanded="false"
                    aria-label="Abrir menú de navegación"
                >
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" aria-hidden="true">
                        <path d="M3 6h18M3 12h18M3 18h18"/>
                    </svg>
                </button>
            </header>

            {{-- ============================================================
                 MOBILE MENU PANEL (drawer lateral)
                 ============================================================ --}}
            <div data-menu-backdrop
                 class="lg:hidden fixed inset-0 z-40 bg-black/40 opacity-0 pointer-events-none
                        transition-opacity duration-300"
                 aria-hidden="true"></div>

            <aside
                id="mobile-menu"
                data-menu-panel
                class="lg:hidden fixed top-0 right-0 z-50 h-[100dvh] w-[82%] max-w-[340px]
                       bg-white shadow-2xl flex flex-col
                       translate-x-full transition-transform duration-300 ease-out"
                role="dialog"
                aria-modal="true"
                aria-label="Menú móvil"
            >
                <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
                    <img src="{{ asset('images/invitatorio_horizontal.png') }}"
                         alt="Invitatorio" class="h-7 w-auto">
                    <button data-menu-close type="button"
                            class="inline-flex items-center justify-center w-9 h-9 rounded-lg
                                   text-gray-500 hover:bg-gray-100"
                            aria-label="Cerrar menú">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" aria-hidden="true">
                            <path d="M6 6l12 12M6 18L18 6"/>
                        </svg>
                    </button>
                </div>

                <nav class="flex-1 overflow-y-auto px-5 py-6" aria-label="Menú móvil">
                    <ul class="flex flex-col gap-1">
                        <li><a data-menu-link href="#" class="block py-3 px-4 rounded-xl bg-[#F4EEFB] text-[#5A3087] font-semibold">Inicio</a></li>
                        <li><a data-menu-link href="#disenos" class="block py-3 px-4 rounded-xl text-[#18111F] hover:bg-[#FFF1E1] hover:text-[#EB7512] transition-colors">Diseños</a></li>
                        <li><a data-menu-link href="#paquetes" class="block py-3 px-4 rounded-xl text-[#18111F] hover:bg-[#FFF1E1] hover:text-[#EB7512] transition-colors">Paquetes</a></li>
                        <li><a data-menu-link href="#como-funciona" class="block py-3 px-4 rounded-xl text-[#18111F] hover:bg-[#FFF1E1] hover:text-[#EB7512] transition-colors">Cómo funciona</a></li>
                        <li><a data-menu-link href="#faq" class="block py-3 px-4 rounded-xl text-[#18111F] hover:bg-[#FFF1E1] hover:text-[#EB7512] transition-colors">Preguntas frecuentes</a></li>
                        <li><a data-menu-link href="#contacto" class="block py-3 px-4 rounded-xl text-[#18111F] hover:bg-[#FFF1E1] hover:text-[#EB7512] transition-colors">Contacto</a></li>
                    </ul>
                </nav>

                <div class="px-5 py-5 border-t border-gray-100 flex flex-col gap-3">
                    <a data-menu-link href="https://wa.me/529990000000"
                       class="inline-flex items-center justify-center gap-2 w-full py-3 rounded-full
                              border-2 border-[#EB7512] text-[#EB7512] font-semibold
                              hover:bg-[#FFF1E1] transition-colors">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.967-.94 1.164-.173.198-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.71.306 1.263.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.002-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z"/>
                        </svg>
                        Cotizar por WhatsApp
                    </a>
                    <a data-menu-link href="#cotizar"
                       class="inline-flex items-center justify-center w-full py-3 rounded-full
                              bg-[#EB7512] hover:bg-[#F45A00] text-white font-semibold
                              transition-colors shadow-md shadow-orange-500/25">
                        Cotizar ahora
                    </a>
                </div>
            </aside>

            {{-- ============================================================
                 HERO CONTENT
                 ============================================================ --}}
            <div class="grid lg:grid-cols-12 gap-7 lg:gap-10 items-start pt-7 lg:pt-16">

                {{-- =====================  COLUMNA IZQ — TEXTO  ===================== --}}
                <div class="lg:col-span-7 xl:col-span-7 flex flex-col gap-5 lg:gap-8 lg:pt-10">

                    <p class="inline-flex items-center gap-2 self-start text-[11px] sm:text-[13px]
                              font-semibold tracking-[0.18em] sm:tracking-wider uppercase
                              text-[#EB7512]"
                       data-anim="fade-up" data-anim-delay="80">
                        <span class="w-7 sm:w-8 h-px bg-[#EB7512]"></span>
                        Invitaciones digitales para toda ocasión
                    </p>

                    <h1 class="font-display font-extrabold leading-[1.1] lg:leading-[1.05] tracking-tight
                               text-[28px] sm:text-4xl lg:text-6xl xl:text-[64px]"
                        data-anim="fade-up" data-anim-delay="160">
                        <span class="text-[#2B143F]">Invitaciones digitales para momentos </span>
                        <span class="text-[#EB7512]">inolvidables</span>
                    </h1>

                    <p class="text-[15px] sm:text-base lg:text-lg leading-relaxed text-[#5F5A66]
                              max-w-md sm:max-w-xl"
                       data-anim="fade-up" data-anim-delay="260">
                        Comparte tu evento por WhatsApp con diseño moderno, música,
                        cuenta regresiva, ubicación y confirmación de asistencia.
                    </p>

                    {{-- CTAs del hero --}}
                    <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 w-full"
                         data-anim="fade-up" data-anim-delay="360">
                        <a href="#paquetes"
                           class="btn-primary text-base w-full sm:w-auto px-7 py-4 sm:py-3.5">
                            Ver paquetes
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M5 12h14M13 5l7 7-7 7"/>
                            </svg>
                        </a>
                        <a href="https://wa.me/529990000000" target="_blank" rel="noopener"
                           class="btn-secondary text-base w-full sm:w-auto px-7 py-4 sm:py-3.5">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.967-.94 1.164-.173.198-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.71.306 1.263.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.002-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z"/>
                            </svg>
                            Cotizar por WhatsApp
                        </a>
                    </div>

                    {{-- Beneficios (4) --}}
                    <ul class="grid grid-cols-2 lg:flex lg:items-center gap-x-4 gap-y-3 lg:gap-0
                               pt-2 lg:pt-2"
                        data-anim="fade-up" data-anim-delay="460">
                        <li class="flex items-center gap-2.5 sm:gap-3 min-w-0 lg:pr-6 lg:border-r lg:border-gray-200/70">
                            <span class="inline-flex items-center justify-center w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-[#FFF1E1] text-[#EB7512] shrink-0">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>
                                </svg>
                            </span>
                            <span class="text-[13px] sm:text-sm font-medium text-[#18111F] leading-tight whitespace-nowrap">Lista en 24–48 h</span>
                        </li>
                        <li class="flex items-center gap-2.5 sm:gap-3 min-w-0 lg:px-6 lg:border-r lg:border-gray-200/70">
                            <span class="inline-flex items-center justify-center w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-[#F4EEFB] text-[#5A3087] shrink-0">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M4 12v3a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-3"/><path d="M16 6l-4-4-4 4"/><path d="M12 2v14"/>
                                </svg>
                            </span>
                            <span class="text-[13px] sm:text-sm font-medium text-[#18111F] leading-tight whitespace-nowrap">Fácil de compartir</span>
                        </li>
                        <li class="flex items-center gap-2.5 sm:gap-3 min-w-0 lg:px-6 lg:border-r lg:border-gray-200/70">
                            <span class="inline-flex items-center justify-center w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-[#FFF1E1] text-[#EB7512] shrink-0">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M9 11l3 3 8-8"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/>
                                </svg>
                            </span>
                            <span class="text-[13px] sm:text-sm font-medium text-[#18111F] leading-tight whitespace-nowrap">RSVP incluido</span>
                        </li>
                        <li class="flex items-center gap-2.5 sm:gap-3 min-w-0 lg:px-6">
                            <span class="inline-flex items-center justify-center w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-[#F4EEFB] text-[#5A3087] shrink-0">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><path d="M14 14h7v7h-7z"/>
                                </svg>
                            </span>
                            <span class="text-[13px] sm:text-sm font-medium text-[#18111F] leading-tight whitespace-nowrap">Para cualquier evento</span>
                        </li>
                    </ul>
                </div>

                {{-- =====================  COLUMNA DER — CELULAR  ===================== --}}
                <div class="lg:col-span-5 xl:col-span-5 relative flex justify-center lg:justify-end
                            mt-0 lg:mt-0"
                     data-anim="fade-up" data-anim-delay="220">

                    <div class="relative w-[88%] sm:w-[64%] lg:w-[100%] max-w-[420px] aspect-[3/5]
                                anim-float anim-delay-300">

                        {{-- Sombra/glow bajo el celular --}}
                        <div class="absolute -bottom-6 left-1/2 -translate-x-1/2 w-3/4 h-10
                                    bg-[#5A3087]/25 blur-2xl rounded-full"></div>

                        <img
                            src="{{ asset('images/celular.png') }}"
                            alt="Vista previa de invitación digital en un celular"
                            class="relative w-full h-full object-contain drop-shadow-[0_30px_40px_rgba(43,20,63,0.25)]"
                            loading="eager"
                            decoding="async"
                        >
                    </div>

                    {{-- ===== Tarjetas flotantes (solo desktop) ===== --}}
                    {{-- Cuenta regresiva (arriba izquierda del celular) --}}
                    <div class="hidden lg:flex float-card anim-soft-pop anim-delay-600
                                top-[14%] -left-6 w-[210px]"
                         role="group" aria-label="Cuenta regresiva">
                        <span class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-[#FFF1E1] text-[#EB7512] shrink-0">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <circle cx="12" cy="13" r="8"/><path d="M12 9v4l2.5 2.5"/><path d="M9 2h6"/><path d="M19 5l1.5-1.5"/>
                            </svg>
                        </span>
                        <div>
                            <p class="text-[11px] uppercase tracking-wider text-[#5F5A66] font-semibold">Cuenta regresiva</p>
                            <p class="text-[13px] font-semibold text-[#2B143F] leading-snug">Sigue cada día más cerca.</p>
                        </div>
                    </div>

                    {{-- Ubicación (abajo izquierda) --}}
                    <div class="hidden lg:flex float-card anim-soft-pop anim-delay-700
                                bottom-[10%] -left-10 w-[220px]"
                         role="group" aria-label="Ubicación">
                        <span class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-[#FFF1E1] text-[#EB7512] shrink-0">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M12 22s7-7.5 7-13a7 7 0 1 0-14 0c0 5.5 7 13 7 13z"/><circle cx="12" cy="9" r="2.5"/>
                            </svg>
                        </span>
                        <div>
                            <p class="text-[11px] uppercase tracking-wider text-[#5F5A66] font-semibold">Ubicación</p>
                            <p class="text-[13px] font-semibold text-[#2B143F] leading-snug">Llega sin complicaciones.</p>
                        </div>
                    </div>

                    {{-- Confirmar asistencia (lado derecho) --}}
                    <div class="hidden lg:flex float-card anim-soft-pop anim-delay-800
                                top-[42%] -right-8 w-[230px]"
                         role="group" aria-label="Confirmar asistencia">
                        <span class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-[#FFF1E1] text-[#EB7512] shrink-0">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M9 12l2 2 4-4"/><path d="M21 12c0 5-4 9-9 9a9 9 0 1 1 0-18c2.5 0 4.7 1 6.4 2.6"/>
                            </svg>
                        </span>
                        <div>
                            <p class="text-[11px] uppercase tracking-wider text-[#5F5A66] font-semibold">Confirmar asistencia</p>
                            <p class="text-[13px] font-semibold text-[#2B143F] leading-snug">Confirma tu lugar en un clic.</p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>

    {{-- ===========================================================
         CONFIGURACIÓN GLOBAL — variables reusables
         =========================================================== --}}
    @php
        $whatsappNumber  = '529999999999';
        $whatsappMessage = 'Hola, quiero cotizar una invitación digital para mi evento.';
        $whatsappUrl     = 'https://wa.me/' . $whatsappNumber . '?text=' . urlencode($whatsappMessage);
    @endphp

    {{-- ===========================================================
         SECCIÓN 1 — ¿QUÉ INCLUYE?
         =========================================================== --}}
    <section id="incluye" class="section-padding bg-[#FFFDF8] relative">
        <div class="mx-auto max-w-[1200px] lg:max-w-[1280px] px-5 sm:px-8">

            <header class="max-w-3xl mx-auto text-center mb-12 sm:mb-16 reveal">
                <p class="inline-flex items-center gap-2 text-[11px] sm:text-[12px] font-semibold tracking-[0.18em] uppercase text-[#EB7512] mb-4">
                    <span class="w-7 h-px bg-[#EB7512]"></span>
                    Qué incluye
                    <span class="w-7 h-px bg-[#EB7512]"></span>
                </p>
                <h2 class="text-3xl sm:text-4xl lg:text-5xl font-display font-extrabold leading-[1.1] tracking-tight text-[#2B143F]">
                    Todo lo que necesitas en <span class="text-[#EB7512]">una sola invitación</span> digital
                </h2>
                <p class="mt-4 text-[15px] sm:text-base lg:text-lg text-[#5F5A66]">
                    Tu evento con diseño moderno, detalles importantes y funciones prácticas para que tus invitados tengan toda la información en un solo enlace.
                </p>
            </header>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 lg:gap-6">

                {{-- 1. Música --}}
                <article class="card-feature reveal" data-reveal-delay="0">
                    <span class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-[#FFF1E1] text-[#EB7512] mb-4">
                        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M9 18V5l12-2v13"/><circle cx="6" cy="18" r="3"/><circle cx="18" cy="16" r="3"/>
                        </svg>
                    </span>
                    <h3 class="font-display font-bold text-[17px] text-[#2B143F]">Música de fondo</h3>
                    <p class="mt-2 text-sm leading-relaxed text-[#5F5A66]">Agrega una canción especial para ambientar tu invitación.</p>
                </article>

                {{-- 2. Cuenta regresiva --}}
                <article class="card-feature reveal" data-reveal-delay="80">
                    <span class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-[#F4EEFB] text-[#5A3087] mb-4">
                        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <circle cx="12" cy="13" r="8"/><path d="M12 9v4l2.5 2.5"/><path d="M9 2h6"/>
                        </svg>
                    </span>
                    <h3 class="font-display font-bold text-[17px] text-[#2B143F]">Cuenta regresiva</h3>
                    <p class="mt-2 text-sm leading-relaxed text-[#5F5A66]">Tus invitados sabrán cuánto falta para el gran día.</p>
                </article>

                {{-- 3. Ubicación con Maps --}}
                <article class="card-feature reveal" data-reveal-delay="160">
                    <span class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-[#FFF1E1] text-[#EB7512] mb-4">
                        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M12 22s7-7.5 7-13a7 7 0 1 0-14 0c0 5.5 7 13 7 13z"/><circle cx="12" cy="9" r="2.5"/>
                        </svg>
                    </span>
                    <h3 class="font-display font-bold text-[17px] text-[#2B143F]">Ubicación con Maps</h3>
                    <p class="mt-2 text-sm leading-relaxed text-[#5F5A66]">Comparte la dirección exacta con acceso directo al mapa.</p>
                </article>

                {{-- 4. Dress code --}}
                <article class="card-feature reveal" data-reveal-delay="240">
                    <span class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-[#F4EEFB] text-[#5A3087] mb-4">
                        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M6 4l-2 7 4 1v8h12v-8l4-1-2-7-5 2-3-2-3 2-5-2z"/>
                        </svg>
                    </span>
                    <h3 class="font-display font-bold text-[17px] text-[#2B143F]">Dress code</h3>
                    <p class="mt-2 text-sm leading-relaxed text-[#5F5A66]">Indica el estilo de vestimenta de forma clara y elegante.</p>
                </article>

                {{-- 5. Mesa de regalos --}}
                <article class="card-feature reveal" data-reveal-delay="320">
                    <span class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-[#FFF1E1] text-[#EB7512] mb-4">
                        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <rect x="3" y="8" width="18" height="13" rx="1"/><path d="M3 12h18"/><path d="M12 8v13"/><path d="M12 8c-2-3-6-3-6 0h6zM12 8c2-3 6-3 6 0h-6z"/>
                        </svg>
                    </span>
                    <h3 class="font-display font-bold text-[17px] text-[#2B143F]">Mesa de regalos</h3>
                    <p class="mt-2 text-sm leading-relaxed text-[#5F5A66]">Agrega enlaces o datos para recibir detalles de tus invitados.</p>
                </article>

                {{-- 6. Confirmación RSVP --}}
                <article class="card-feature reveal" data-reveal-delay="400">
                    <span class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-[#F4EEFB] text-[#5A3087] mb-4">
                        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M9 12l2 2 4-4"/><path d="M21 12c0 5-4 9-9 9a9 9 0 1 1 0-18c2.5 0 4.7 1 6.4 2.6"/>
                        </svg>
                    </span>
                    <h3 class="font-display font-bold text-[17px] text-[#2B143F]">Confirmación RSVP</h3>
                    <p class="mt-2 text-sm leading-relaxed text-[#5F5A66]">Recibe confirmaciones de asistencia de manera sencilla.</p>
                </article>

                {{-- 7. Galería --}}
                <article class="card-feature reveal" data-reveal-delay="480">
                    <span class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-[#FFF1E1] text-[#EB7512] mb-4">
                        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="9" cy="9" r="2"/><path d="M21 15l-5-5L5 21"/>
                        </svg>
                    </span>
                    <h3 class="font-display font-bold text-[17px] text-[#2B143F]">Galería de fotos</h3>
                    <p class="mt-2 text-sm leading-relaxed text-[#5F5A66]">Comparte momentos especiales dentro de tu invitación.</p>
                </article>

                {{-- 8. WhatsApp --}}
                <article class="card-feature reveal" data-reveal-delay="560">
                    <span class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-[#F4EEFB] text-[#5A3087] mb-4">
                        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.967-.94 1.164-.173.198-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.71.306 1.263.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.002-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884"/>
                        </svg>
                    </span>
                    <h3 class="font-display font-bold text-[17px] text-[#2B143F]">Enlace por WhatsApp</h3>
                    <p class="mt-2 text-sm leading-relaxed text-[#5F5A66]">Comparte tu invitación fácilmente con un solo clic.</p>
                </article>

            </div>
        </div>
    </section>

    {{-- ===========================================================
         SECCIÓN 2 — DISEÑOS / EJEMPLOS
         =========================================================== --}}
    <section id="disenos" class="section-padding relative overflow-hidden">
        {{-- fondo degradado sutil + confeti via CSS --}}
        <div class="absolute inset-0 -z-10 bg-gradient-to-b from-[#FFFDF8] via-[#F4EFF8]/60 to-[#FFFDF8]" aria-hidden="true"></div>
        <div class="absolute inset-0 -z-10 bg-confetti opacity-70 pointer-events-none" aria-hidden="true"></div>

        <div class="mx-auto max-w-[1200px] lg:max-w-[1280px] px-5 sm:px-8">

            <header class="max-w-3xl mx-auto text-center mb-12 sm:mb-16 reveal">
                <p class="inline-flex items-center gap-2 text-[11px] sm:text-[12px] font-semibold tracking-[0.18em] uppercase text-[#EB7512] mb-4">
                    <span class="w-7 h-px bg-[#EB7512]"></span>
                    Diseños
                    <span class="w-7 h-px bg-[#EB7512]"></span>
                </p>
                <h2 class="text-3xl sm:text-4xl lg:text-5xl font-display font-extrabold leading-[1.1] tracking-tight text-[#2B143F]">
                    Diseños para cada <span class="text-[#EB7512]">tipo de celebración</span>
                </h2>
                <p class="mt-4 text-[15px] sm:text-base lg:text-lg text-[#5F5A66]">
                    Creamos invitaciones digitales para eventos elegantes, familiares, divertidos o formales, adaptadas al estilo de cada ocasión.
                </p>
            </header>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 lg:gap-6">

                {{-- 1. Bodas --}}
                <article class="card-category reveal group" data-reveal-delay="0">
                    <span class="inline-flex items-center justify-center w-14 h-14 rounded-xl bg-[#FFF1E1] text-[#EB7512] mb-5 transition-transform duration-300 group-hover:scale-110 group-hover:rotate-3">
                        <svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M12 21s-7-4.5-7-11a4 4 0 0 1 7-2.6A4 4 0 0 1 19 10c0 6.5-7 11-7 11z"/>
                        </svg>
                    </span>
                    <h3 class="font-display font-extrabold text-xl text-[#2B143F]">Bodas</h3>
                    <p class="mt-2 text-[15px] leading-relaxed text-[#5F5A66]">Invitaciones elegantes para compartir cada detalle de tu gran día.</p>
                    <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener" class="mt-5 inline-flex items-center gap-1.5 text-sm font-semibold text-[#EB7512] hover:text-[#F45A00] transition-colors">
                        Solicitar diseño
                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M5 12h14M13 5l7 7-7 7"/>
                        </svg>
                    </a>
                </article>

                {{-- 2. XV años --}}
                <article class="card-category reveal group" data-reveal-delay="80">
                    <span class="inline-flex items-center justify-center w-14 h-14 rounded-xl bg-[#F4EEFB] text-[#5A3087] mb-5 transition-transform duration-300 group-hover:scale-110 group-hover:rotate-3">
                        <svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M3 19l9-14 9 14M7.5 13h9"/><circle cx="12" cy="9" r="1.2" fill="currentColor"/>
                        </svg>
                    </span>
                    <h3 class="font-display font-extrabold text-xl text-[#2B143F]">XV años</h3>
                    <p class="mt-2 text-[15px] leading-relaxed text-[#5F5A66]">Diseños memorables para celebrar una fecha única.</p>
                    <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener" class="mt-5 inline-flex items-center gap-1.5 text-sm font-semibold text-[#EB7512] hover:text-[#F45A00] transition-colors">
                        Solicitar diseño
                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M5 12h14M13 5l7 7-7 7"/>
                        </svg>
                    </a>
                </article>

                {{-- 3. Cumpleaños --}}
                <article class="card-category reveal group" data-reveal-delay="160">
                    <span class="inline-flex items-center justify-center w-14 h-14 rounded-xl bg-[#FFF1E1] text-[#EB7512] mb-5 transition-transform duration-300 group-hover:scale-110 group-hover:rotate-3">
                        <svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M12 3v6"/><path d="M5 9c0 4 2 7 7 7s7-3 7-7"/><rect x="6" y="9" width="12" height="12" rx="2"/>
                            <circle cx="9" cy="14" r="0.5" fill="currentColor"/><circle cx="14" cy="12" r="0.5" fill="currentColor"/>
                            <path d="M19 3l-2 2M7 7l-2-2"/>
                        </svg>
                    </span>
                    <h3 class="font-display font-extrabold text-xl text-[#2B143F]">Cumpleaños</h3>
                    <p class="mt-2 text-[15px] leading-relaxed text-[#5F5A66]">Opciones modernas y divertidas para cualquier edad.</p>
                    <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener" class="mt-5 inline-flex items-center gap-1.5 text-sm font-semibold text-[#EB7512] hover:text-[#F45A00] transition-colors">
                        Solicitar diseño
                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M5 12h14M13 5l7 7-7 7"/>
                        </svg>
                    </a>
                </article>

                {{-- 4. Bautizos --}}
                <article class="card-category reveal group" data-reveal-delay="240">
                    <span class="inline-flex items-center justify-center w-14 h-14 rounded-xl bg-[#F4EEFB] text-[#5A3087] mb-5 transition-transform duration-300 group-hover:scale-110 group-hover:rotate-3">
                        <svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M12 3l3 6 6 1-4.5 4.5L18 21l-6-3-6 3 1.5-6.5L3 10l6-1z"/>
                        </svg>
                    </span>
                    <h3 class="font-display font-extrabold text-xl text-[#2B143F]">Bautizos</h3>
                    <p class="mt-2 text-[15px] leading-relaxed text-[#5F5A66]">Invitaciones tiernas, limpias y fáciles de compartir.</p>
                    <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener" class="mt-5 inline-flex items-center gap-1.5 text-sm font-semibold text-[#EB7512] hover:text-[#F45A00] transition-colors">
                        Solicitar diseño
                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M5 12h14M13 5l7 7-7 7"/>
                        </svg>
                    </a>
                </article>

                {{-- 5. Baby shower --}}
                <article class="card-category reveal group" data-reveal-delay="320">
                    <span class="inline-flex items-center justify-center w-14 h-14 rounded-xl bg-[#FFF1E1] text-[#EB7512] mb-5 transition-transform duration-300 group-hover:scale-110 group-hover:rotate-3">
                        <svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <circle cx="12" cy="9" r="5"/><path d="M9 9h.01M15 9h.01M9 13s1 2 3 2 3-2 3-2"/><path d="M5 19c.5-2 2-3 4-3M19 19c-.5-2-2-3-4-3"/>
                        </svg>
                    </span>
                    <h3 class="font-display font-extrabold text-xl text-[#2B143F]">Baby shower</h3>
                    <p class="mt-2 text-[15px] leading-relaxed text-[#5F5A66]">Diseños especiales para anunciar una celebración llena de ilusión.</p>
                    <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener" class="mt-5 inline-flex items-center gap-1.5 text-sm font-semibold text-[#EB7512] hover:text-[#F45A00] transition-colors">
                        Solicitar diseño
                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M5 12h14M13 5l7 7-7 7"/>
                        </svg>
                    </a>
                </article>

                {{-- 6. Eventos especiales --}}
                <article class="card-category reveal group" data-reveal-delay="400">
                    <span class="inline-flex items-center justify-center w-14 h-14 rounded-xl bg-[#F4EEFB] text-[#5A3087] mb-5 transition-transform duration-300 group-hover:scale-110 group-hover:rotate-3">
                        <svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M5 3v4M19 3v4M7 17l-2 4M17 17l2 4M3 11h18M5 7h14a2 2 0 0 1 2 2v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V9a2 2 0 0 1 2-2z"/>
                        </svg>
                    </span>
                    <h3 class="font-display font-extrabold text-xl text-[#2B143F]">Eventos especiales</h3>
                    <p class="mt-2 text-[15px] leading-relaxed text-[#5F5A66]">Aniversarios, graduaciones, reuniones familiares y más.</p>
                    <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener" class="mt-5 inline-flex items-center gap-1.5 text-sm font-semibold text-[#EB7512] hover:text-[#F45A00] transition-colors">
                        Solicitar diseño
                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M5 12h14M13 5l7 7-7 7"/>
                        </svg>
                    </a>
                </article>

            </div>

            <div class="text-center mt-12 sm:mt-14 reveal">
                <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener" class="btn-primary text-base">
                    Quiero una invitación personalizada
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M5 12h14M13 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </div>
    </section>

    {{-- ===========================================================
         SECCIÓN 3 — CÓMO FUNCIONA
         =========================================================== --}}
    <section id="como-funciona" class="section-padding bg-white relative">
        <div class="mx-auto max-w-[1200px] lg:max-w-[1280px] px-5 sm:px-8">

            <header class="max-w-3xl mx-auto text-center mb-12 sm:mb-16 reveal">
                <p class="inline-flex items-center gap-2 text-[11px] sm:text-[12px] font-semibold tracking-[0.18em] uppercase text-[#EB7512] mb-4">
                    <span class="w-7 h-px bg-[#EB7512]"></span>
                    Proceso
                    <span class="w-7 h-px bg-[#EB7512]"></span>
                </p>
                <h2 class="text-3xl sm:text-4xl lg:text-5xl font-display font-extrabold leading-[1.1] tracking-tight text-[#2B143F]">
                    Tu invitación lista <span class="text-[#EB7512]">en pocos pasos</span>
                </h2>
                <p class="mt-4 text-[15px] sm:text-base lg:text-lg text-[#5F5A66]">
                    Nos encargamos de darle forma a tu evento para que tengas un enlace listo para compartir con tus invitados.
                </p>
            </header>

            <ol class="relative grid grid-cols-1 lg:grid-cols-4 gap-10 lg:gap-6">
                {{-- Línea conectora solo en desktop --}}
                <span class="hidden lg:block absolute left-[12%] right-[12%] top-7 h-[2px] timeline-line"
                      aria-hidden="true"></span>

                {{-- 1. Elige --}}
                <li class="relative flex flex-col items-center text-center reveal" data-reveal-delay="0">
                    <span class="relative inline-flex items-center justify-center w-14 h-14 rounded-full bg-white border-2 border-[#EB7512] text-[#EB7512] text-lg font-display font-extrabold z-10 mb-5 shadow-[0_8px_18px_-8px_rgba(235,117,18,.55)]">
                        1
                    </span>
                    <span class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-[#FFF1E1] text-[#EB7512] mb-3">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/>
                        </svg>
                    </span>
                    <h3 class="font-display font-bold text-[17px] text-[#2B143F]">Elige tu paquete</h3>
                    <p class="mt-2 text-sm leading-relaxed text-[#5F5A66] max-w-xs">Selecciona la opción que mejor se adapte a tu evento.</p>
                </li>

                {{-- 2. Datos --}}
                <li class="relative flex flex-col items-center text-center reveal" data-reveal-delay="120">
                    <span class="relative inline-flex items-center justify-center w-14 h-14 rounded-full bg-white border-2 border-[#5A3087] text-[#5A3087] text-lg font-display font-extrabold z-10 mb-5 shadow-[0_8px_18px_-8px_rgba(90,48,135,.55)]">
                        2
                    </span>
                    <span class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-[#F4EEFB] text-[#5A3087] mb-3">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M12 20h9"/><path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4z"/>
                        </svg>
                    </span>
                    <h3 class="font-display font-bold text-[17px] text-[#2B143F]">Envíanos tus datos</h3>
                    <p class="mt-2 text-sm leading-relaxed text-[#5F5A66] max-w-xs">Comparte nombres, fecha, ubicación, música, fotos y detalles.</p>
                </li>

                {{-- 3. Diseñamos --}}
                <li class="relative flex flex-col items-center text-center reveal" data-reveal-delay="240">
                    <span class="relative inline-flex items-center justify-center w-14 h-14 rounded-full bg-white border-2 border-[#EB7512] text-[#EB7512] text-lg font-display font-extrabold z-10 mb-5 shadow-[0_8px_18px_-8px_rgba(235,117,18,.55)]">
                        3
                    </span>
                    <span class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-[#FFF1E1] text-[#EB7512] mb-3">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M12 19l7-7 3 3-7 7-3-3z"/><path d="M18 13l-1.5-7.5L2 2l3.5 14.5L13 18l5-5z"/><path d="M2 2l7.586 7.586"/><circle cx="11" cy="11" r="2"/>
                        </svg>
                    </span>
                    <h3 class="font-display font-bold text-[17px] text-[#2B143F]">Diseñamos tu invitación</h3>
                    <p class="mt-2 text-sm leading-relaxed text-[#5F5A66] max-w-xs">Creamos una invitación digital moderna, clara y lista para revisar.</p>
                </li>

                {{-- 4. Comparte --}}
                <li class="relative flex flex-col items-center text-center reveal" data-reveal-delay="360">
                    <span class="relative inline-flex items-center justify-center w-14 h-14 rounded-full bg-white border-2 border-[#5A3087] text-[#5A3087] text-lg font-display font-extrabold z-10 mb-5 shadow-[0_8px_18px_-8px_rgba(90,48,135,.55)]">
                        4
                    </span>
                    <span class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-[#F4EEFB] text-[#5A3087] mb-3">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><path d="M8.6 13.5l6.8 4M15.4 6.5l-6.8 4"/>
                        </svg>
                    </span>
                    <h3 class="font-display font-bold text-[17px] text-[#2B143F]">Comparte tu enlace</h3>
                    <p class="mt-2 text-sm leading-relaxed text-[#5F5A66] max-w-xs">Recibe tu invitación y compártela por WhatsApp o redes sociales.</p>
                </li>
            </ol>
        </div>
    </section>

    {{-- ===========================================================
         SECCIÓN 4 — PAQUETES
         =========================================================== --}}
    <section id="paquetes" class="section-padding bg-[#FFFDF8] relative">
        <div class="mx-auto max-w-[1200px] lg:max-w-[1280px] px-5 sm:px-8">

            <header class="max-w-3xl mx-auto text-center mb-12 sm:mb-16 reveal">
                <p class="inline-flex items-center gap-2 text-[11px] sm:text-[12px] font-semibold tracking-[0.18em] uppercase text-[#EB7512] mb-4">
                    <span class="w-7 h-px bg-[#EB7512]"></span>
                    Paquetes
                    <span class="w-7 h-px bg-[#EB7512]"></span>
                </p>
                <h2 class="text-3xl sm:text-4xl lg:text-5xl font-display font-extrabold leading-[1.1] tracking-tight text-[#2B143F]">
                    Elige el <span class="text-[#EB7512]">paquete ideal</span> para tu evento
                </h2>
                <p class="mt-4 text-[15px] sm:text-base lg:text-lg text-[#5F5A66]">
                    Todas nuestras invitaciones incluyen diseño adaptable a celular, música, cuenta regresiva, ubicación, dress code, mesa de regalos y enlace para compartir.
                </p>
            </header>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 lg:gap-5 items-stretch">

                {{-- 1. Esencial --}}
                <article class="pricing-card reveal" data-reveal-delay="0">
                    <header class="mb-6">
                        <h3 class="font-display font-bold text-lg text-[#2B143F]">Invitación Esencial</h3>
                        <p class="mt-2 text-[13px] leading-relaxed text-[#5F5A66]">Perfecta para eventos sencillos que necesitan una invitación bonita, clara y fácil de compartir.</p>
                    </header>
                    <div class="mb-5">
                        <p class="font-display font-extrabold text-4xl text-[#2B143F] leading-none">$600<span class="text-base font-semibold text-[#5F5A66] ml-1">MXN</span></p>
                    </div>
                    <ul class="space-y-2.5 text-sm text-[#18111F] mb-7 flex-1">
                        <li class="flex gap-2"><span class="text-[#EB7512] mt-0.5">✓</span><span>Diseño digital adaptable a celular</span></li>
                        <li class="flex gap-2"><span class="text-[#EB7512] mt-0.5">✓</span><span>Fecha, hora y datos del evento</span></li>
                        <li class="flex gap-2"><span class="text-[#EB7512] mt-0.5">✓</span><span>Música de fondo</span></li>
                        <li class="flex gap-2"><span class="text-[#EB7512] mt-0.5">✓</span><span>Cuenta regresiva</span></li>
                        <li class="flex gap-2"><span class="text-[#EB7512] mt-0.5">✓</span><span>Ubicación con Google Maps</span></li>
                        <li class="flex gap-2"><span class="text-[#EB7512] mt-0.5">✓</span><span>Dress code</span></li>
                        <li class="flex gap-2"><span class="text-[#EB7512] mt-0.5">✓</span><span>Mesa de regalos</span></li>
                        <li class="flex gap-2"><span class="text-[#EB7512] mt-0.5">✓</span><span>Botón de WhatsApp</span></li>
                        <li class="flex gap-2"><span class="text-[#EB7512] mt-0.5">✓</span><span>Enlace personalizado</span></li>
                    </ul>
                    <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener" class="inline-flex w-full items-center justify-center gap-2 rounded-full border-2 border-[#EB7512] text-[#EB7512] font-semibold py-3 transition-all hover:bg-[#EB7512] hover:text-white">
                        Solicitar paquete
                    </a>
                </article>

                {{-- 2. Plus (DESTACADO) --}}
                <article class="pricing-card-featured anim-pulse reveal" data-reveal-delay="120">
                    <span class="absolute -top-3 left-1/2 -translate-x-1/2 inline-flex items-center gap-1.5 rounded-full bg-[#EB7512] text-white text-[11px] font-bold uppercase tracking-wider px-3.5 py-1.5 shadow-lg shadow-orange-500/40">
                        <svg class="w-3 h-3" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M12 2l3 6 6 1-4.5 4.5L18 21l-6-3-6 3 1.5-6.5L3 9l6-1z"/>
                        </svg>
                        Más elegido
                    </span>
                    <header class="mb-6 mt-2">
                        <h3 class="font-display font-bold text-lg text-[#2B143F]">Invitación Plus</h3>
                        <p class="mt-2 text-[13px] leading-relaxed text-[#5F5A66]">Ideal para quienes quieren una invitación más visual y completa.</p>
                    </header>
                    <div class="mb-5">
                        <p class="font-display font-extrabold text-4xl text-[#2B143F] leading-none">$900<span class="text-base font-semibold text-[#5F5A66] ml-1">MXN</span></p>
                    </div>
                    <ul class="space-y-2.5 text-sm text-[#18111F] mb-7 flex-1">
                        <li class="flex gap-2"><span class="text-[#EB7512] mt-0.5">✓</span><span class="font-semibold">Todo lo del paquete Esencial</span></li>
                        <li class="flex gap-2"><span class="text-[#EB7512] mt-0.5">✓</span><span>Galería de fotos</span></li>
                        <li class="flex gap-2"><span class="text-[#EB7512] mt-0.5">✓</span><span>Itinerario del evento</span></li>
                        <li class="flex gap-2"><span class="text-[#EB7512] mt-0.5">✓</span><span>Botón para agregar al calendario</span></li>
                        <li class="flex gap-2"><span class="text-[#EB7512] mt-0.5">✓</span><span>Secciones adicionales</span></li>
                        <li class="flex gap-2"><span class="text-[#EB7512] mt-0.5">✓</span><span>Diseño con más detalles visuales</span></li>
                    </ul>
                    <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener" class="inline-flex w-full items-center justify-center gap-2 rounded-full bg-[#EB7512] hover:bg-[#F45A00] text-white font-semibold py-3 shadow-lg shadow-orange-500/30 transition-all hover:-translate-y-0.5">
                        Solicitar paquete
                    </a>
                </article>

                {{-- 3. Premium --}}
                <article class="pricing-card reveal" data-reveal-delay="240">
                    <header class="mb-6">
                        <h3 class="font-display font-bold text-lg text-[#2B143F]">Invitación Premium</h3>
                        <p class="mt-2 text-[13px] leading-relaxed text-[#5F5A66]">Pensada para bodas, XV años y eventos más formales.</p>
                    </header>
                    <div class="mb-5">
                        <p class="font-display font-extrabold text-4xl text-[#2B143F] leading-none">$1,300<span class="text-base font-semibold text-[#5F5A66] ml-1">MXN</span></p>
                    </div>
                    <ul class="space-y-2.5 text-sm text-[#18111F] mb-7 flex-1">
                        <li class="flex gap-2"><span class="text-[#EB7512] mt-0.5">✓</span><span class="font-semibold">Todo lo del paquete Plus</span></li>
                        <li class="flex gap-2"><span class="text-[#EB7512] mt-0.5">✓</span><span>Confirmación de asistencia RSVP</span></li>
                        <li class="flex gap-2"><span class="text-[#EB7512] mt-0.5">✓</span><span>Lista básica de invitados confirmados</span></li>
                        <li class="flex gap-2"><span class="text-[#EB7512] mt-0.5">✓</span><span>Sección de padres, padrinos o familia</span></li>
                        <li class="flex gap-2"><span class="text-[#EB7512] mt-0.5">✓</span><span>Recomendaciones para invitados</span></li>
                        <li class="flex gap-2"><span class="text-[#EB7512] mt-0.5">✓</span><span>Diseño más elegante y trabajado</span></li>
                    </ul>
                    <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener" class="inline-flex w-full items-center justify-center gap-2 rounded-full border-2 border-[#EB7512] text-[#EB7512] font-semibold py-3 transition-all hover:bg-[#EB7512] hover:text-white">
                        Solicitar paquete
                    </a>
                </article>

                {{-- 4. VIP --}}
                <article class="pricing-card reveal" data-reveal-delay="360">
                    <header class="mb-6">
                        <h3 class="font-display font-bold text-lg text-[#2B143F]">VIP Personalizada</h3>
                        <p class="mt-2 text-[13px] leading-relaxed text-[#5F5A66]">Para quienes buscan una invitación única, más elaborada y personalizada.</p>
                    </header>
                    <div class="mb-5">
                        <p class="font-display font-extrabold text-4xl text-[#2B143F] leading-none"><span class="text-base align-top text-[#5F5A66]">Desde </span>$1,800<span class="text-base font-semibold text-[#5F5A66] ml-1">MXN</span></p>
                    </div>
                    <ul class="space-y-2.5 text-sm text-[#18111F] mb-7 flex-1">
                        <li class="flex gap-2"><span class="text-[#EB7512] mt-0.5">✓</span><span class="font-semibold">Todo lo del paquete Premium</span></li>
                        <li class="flex gap-2"><span class="text-[#EB7512] mt-0.5">✓</span><span>Diseño personalizado según temática</span></li>
                        <li class="flex gap-2"><span class="text-[#EB7512] mt-0.5">✓</span><span>Animaciones especiales</span></li>
                        <li class="flex gap-2"><span class="text-[#EB7512] mt-0.5">✓</span><span>Galería extendida</span></li>
                        <li class="flex gap-2"><span class="text-[#EB7512] mt-0.5">✓</span><span>Secciones especiales</span></li>
                        <li class="flex gap-2"><span class="text-[#EB7512] mt-0.5">✓</span><span>Cambios adicionales</span></li>
                        <li class="flex gap-2"><span class="text-[#EB7512] mt-0.5">✓</span><span>Acompañamiento más directo</span></li>
                    </ul>
                    <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener" class="inline-flex w-full items-center justify-center gap-2 rounded-full border-2 border-[#EB7512] text-[#EB7512] font-semibold py-3 transition-all hover:bg-[#EB7512] hover:text-white">
                        Solicitar paquete
                    </a>
                </article>

            </div>
        </div>
    </section>

    {{-- ===========================================================
         SECCIÓN 5 — BENEFICIOS
         =========================================================== --}}
    <section id="beneficios" class="section-padding bg-white">
        <div class="mx-auto max-w-[1200px] lg:max-w-[1280px] px-5 sm:px-8">
            <div class="grid lg:grid-cols-2 gap-12 lg:gap-16 items-center">

                {{-- Texto --}}
                <div class="reveal">
                    <p class="inline-flex items-center gap-2 text-[11px] sm:text-[12px] font-semibold tracking-[0.18em] uppercase text-[#EB7512] mb-4">
                        <span class="w-7 h-px bg-[#EB7512]"></span>
                        Beneficios
                    </p>
                    <h2 class="text-3xl sm:text-4xl lg:text-5xl font-display font-extrabold leading-[1.1] tracking-tight text-[#2B143F]">
                        Más práctica, más bonita y <span class="text-[#EB7512]">más fácil de compartir</span>
                    </h2>
                    <p class="mt-5 text-[15px] sm:text-base lg:text-lg text-[#5F5A66]">
                        Una invitación digital no solo se ve moderna, también ayuda a que tus invitados tengan toda la información importante siempre a la mano.
                    </p>

                    <ul class="mt-8 space-y-3.5">
                        <li class="flex items-start gap-3 reveal" data-reveal-delay="0">
                            <span class="shrink-0 inline-flex items-center justify-center w-7 h-7 rounded-full bg-[#FFF1E1] text-[#EB7512]">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12l5 5 9-11"/></svg>
                            </span>
                            <span class="text-[15px] text-[#18111F]">Tus invitados reciben todo en un solo enlace</span>
                        </li>
                        <li class="flex items-start gap-3 reveal" data-reveal-delay="80">
                            <span class="shrink-0 inline-flex items-center justify-center w-7 h-7 rounded-full bg-[#FFF1E1] text-[#EB7512]">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12l5 5 9-11"/></svg>
                            </span>
                            <span class="text-[15px] text-[#18111F]">Se comparte fácilmente por WhatsApp</span>
                        </li>
                        <li class="flex items-start gap-3 reveal" data-reveal-delay="160">
                            <span class="shrink-0 inline-flex items-center justify-center w-7 h-7 rounded-full bg-[#FFF1E1] text-[#EB7512]">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12l5 5 9-11"/></svg>
                            </span>
                            <span class="text-[15px] text-[#18111F]">No se pierde la ubicación del evento</span>
                        </li>
                        <li class="flex items-start gap-3 reveal" data-reveal-delay="240">
                            <span class="shrink-0 inline-flex items-center justify-center w-7 h-7 rounded-full bg-[#FFF1E1] text-[#EB7512]">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12l5 5 9-11"/></svg>
                            </span>
                            <span class="text-[15px] text-[#18111F]">Puedes recibir confirmaciones de asistencia</span>
                        </li>
                        <li class="flex items-start gap-3 reveal" data-reveal-delay="320">
                            <span class="shrink-0 inline-flex items-center justify-center w-7 h-7 rounded-full bg-[#FFF1E1] text-[#EB7512]">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12l5 5 9-11"/></svg>
                            </span>
                            <span class="text-[15px] text-[#18111F]">Ahorras en impresión</span>
                        </li>
                        <li class="flex items-start gap-3 reveal" data-reveal-delay="400">
                            <span class="shrink-0 inline-flex items-center justify-center w-7 h-7 rounded-full bg-[#FFF1E1] text-[#EB7512]">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12l5 5 9-11"/></svg>
                            </span>
                            <span class="text-[15px] text-[#18111F]">Puedes incluir música, fotos y detalles especiales</span>
                        </li>
                    </ul>

                    <div class="mt-9 reveal">
                        <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener" class="btn-primary text-base">
                            Cotizar mi invitación
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M5 12h14M13 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>
                </div>

                {{-- Mockup visual de mini cards (derecha) --}}
                <div class="relative reveal" data-reveal-delay="200">
                    <div class="absolute -inset-6 -z-10 rounded-[2.5rem] bg-gradient-to-br from-[#F4EEFB] to-[#FFF1E1]" aria-hidden="true"></div>

                    <div class="relative grid grid-cols-2 gap-4">
                        <div class="space-y-4">
                            <div class="bg-white rounded-2xl p-4 shadow-[0_18px_36px_-22px_rgba(43,20,63,.30)] anim-float-soft">
                                <div class="flex items-center gap-2 text-[#EB7512] text-xs font-semibold uppercase tracking-wider mb-2">
                                    <span class="w-2 h-2 rounded-full bg-[#EB7512]"></span>
                                    Música
                                </div>
                                <div class="flex items-center gap-2 text-[13px] text-[#18111F] font-medium">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M10 8l6 4-6 4z" fill="currentColor"/></svg>
                                    Tu canción especial
                                </div>
                            </div>
                            <div class="bg-white rounded-2xl p-4 shadow-[0_18px_36px_-22px_rgba(43,20,63,.30)] anim-float-soft" style="animation-delay:.6s">
                                <div class="flex items-center gap-2 text-[#5A3087] text-xs font-semibold uppercase tracking-wider mb-2">
                                    <span class="w-2 h-2 rounded-full bg-[#5A3087]"></span>
                                    Ubicación
                                </div>
                                <div class="flex items-center gap-2 text-[13px] text-[#18111F] font-medium">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s7-7.5 7-13a7 7 0 1 0-14 0c0 5.5 7 13 7 13z"/><circle cx="12" cy="9" r="2.5"/></svg>
                                    Abrir en Maps
                                </div>
                            </div>
                        </div>
                        <div class="space-y-4 mt-8">
                            <div class="bg-white rounded-2xl p-4 shadow-[0_18px_36px_-22px_rgba(43,20,63,.30)] anim-float-soft" style="animation-delay:1.2s">
                                <div class="flex items-center gap-2 text-[#EB7512] text-xs font-semibold uppercase tracking-wider mb-2">
                                    <span class="w-2 h-2 rounded-full bg-[#EB7512]"></span>
                                    Confirmación
                                </div>
                                <div class="flex items-center gap-2 text-[13px] text-[#18111F] font-medium">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 12l2 2 4-4"/><circle cx="12" cy="12" r="10"/></svg>
                                    52 confirmados
                                </div>
                            </div>
                            <div class="bg-white rounded-2xl p-4 shadow-[0_18px_36px_-22px_rgba(43,20,63,.30)] anim-float-soft" style="animation-delay:1.8s">
                                <div class="flex items-center gap-2 text-[#5A3087] text-xs font-semibold uppercase tracking-wider mb-2">
                                    <span class="w-2 h-2 rounded-full bg-[#5A3087]"></span>
                                    Cuenta regresiva
                                </div>
                                <div class="text-[13px] text-[#18111F] font-medium">
                                    <span class="font-display font-extrabold text-[#2B143F] text-lg">128</span> días restantes
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- ===========================================================
         SECCIÓN 6 — FAQ (acordeón)
         =========================================================== --}}
    <section id="preguntas" class="section-padding bg-[#FFFDF8]">
        <div class="mx-auto max-w-3xl px-5 sm:px-8">

            <header class="text-center mb-12 sm:mb-14 reveal">
                <p class="inline-flex items-center gap-2 text-[11px] sm:text-[12px] font-semibold tracking-[0.18em] uppercase text-[#EB7512] mb-4">
                    <span class="w-7 h-px bg-[#EB7512]"></span>
                    FAQ
                    <span class="w-7 h-px bg-[#EB7512]"></span>
                </p>
                <h2 class="text-3xl sm:text-4xl lg:text-5xl font-display font-extrabold leading-[1.1] tracking-tight text-[#2B143F]">
                    Preguntas <span class="text-[#EB7512]">frecuentes</span>
                </h2>
                <p class="mt-4 text-[15px] sm:text-base lg:text-lg text-[#5F5A66]">
                    Resolvemos las dudas más comunes antes de crear tu invitación digital.
                </p>
            </header>

            <div class="space-y-3">

                @php
                    $faqs = [
                        ['q' => '¿Cuánto tarda la entrega?', 'a' => 'Depende del paquete y la información enviada, pero normalmente una invitación puede estar lista en 24 a 48 horas.'],
                        ['q' => '¿Puedo pedir cambios?', 'a' => 'Sí. Cada paquete puede incluir ajustes básicos. En paquetes más avanzados se pueden incluir más cambios y personalización.'],
                        ['q' => '¿La invitación incluye música?', 'a' => 'Sí. Todas las invitaciones pueden incluir música de fondo para darle un toque más especial al evento.'],
                        ['q' => '¿Puedo confirmar asistencia?', 'a' => 'Sí. La confirmación de asistencia está disponible en los paquetes que incluyen RSVP.'],
                        ['q' => '¿Se puede abrir desde celular?', 'a' => 'Sí. Las invitaciones están diseñadas principalmente para verse bien en celular y compartirse por WhatsApp.'],
                        ['q' => '¿Qué necesito enviar?', 'a' => 'Necesitamos nombres, fecha, hora, ubicación, dress code, música, datos de mesa de regalos, fotos si aplica y cualquier mensaje especial.'],
                        ['q' => '¿Puedo compartirla por WhatsApp?', 'a' => 'Sí. Recibirás un enlace personalizado listo para enviar por WhatsApp, redes sociales o mensaje.'],
                        ['q' => '¿Se paga anticipo?', 'a' => 'Sí, se puede solicitar anticipo para comenzar el diseño y el resto al entregar la invitación final.'],
                    ];
                @endphp

                @foreach ($faqs as $i => $faq)
                    <div class="faq-item reveal" data-reveal-delay="{{ $i * 60 }}" data-open="false">
                        <button
                            type="button"
                            class="faq-trigger"
                            aria-expanded="false"
                            aria-controls="faq-panel-{{ $i }}"
                        >
                            <span class="text-[15px] sm:text-base">{{ $faq['q'] }}</span>
                            <span class="faq-icon" aria-hidden="true">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" aria-hidden="true">
                                    <path d="M12 5v14M5 12h14"/>
                                </svg>
                            </span>
                        </button>
                        <div id="faq-panel-{{ $i }}" class="faq-content" role="region">
                            <div>
                                <div class="px-5 pb-5 sm:px-6 sm:pb-6 text-[14px] sm:text-[15px] leading-relaxed text-[#5F5A66]">
                                    {{ $faq['a'] }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>

            <div class="text-center mt-10 reveal">
                <p class="text-[15px] text-[#5F5A66]">¿Otra duda?
                    <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener" class="font-semibold text-[#EB7512] hover:text-[#F45A00] transition-colors">Pregúntanos por WhatsApp →</a>
                </p>
            </div>
        </div>
    </section>

    {{-- ===========================================================
         SECCIÓN 7 — CTA FINAL
         =========================================================== --}}
    <section id="contacto" class="relative overflow-hidden">
        {{-- fondo morado con confeti decorativo --}}
        <div class="absolute inset-0 -z-10 bg-gradient-to-br from-[#2B143F] via-[#2B143F] to-[#5A3087]" aria-hidden="true"></div>
        <div class="absolute inset-0 -z-10 opacity-30 bg-confetti pointer-events-none" aria-hidden="true"></div>
        {{-- círculos decorativos --}}
        <div class="absolute -top-20 -left-20 -z-10 w-72 h-72 rounded-full bg-[#EB7512]/15 blur-3xl" aria-hidden="true"></div>
        <div class="absolute -bottom-24 -right-20 -z-10 w-80 h-80 rounded-full bg-[#5A3087]/30 blur-3xl" aria-hidden="true"></div>

        <div class="section-padding mx-auto max-w-3xl px-5 sm:px-8 text-center text-white relative">

            <p class="inline-flex items-center gap-2 text-[11px] sm:text-[12px] font-semibold tracking-[0.18em] uppercase text-[#F09719] mb-5 reveal">
                <span class="w-7 h-px bg-[#F09719]"></span>
                Última parada
                <span class="w-7 h-px bg-[#F09719]"></span>
            </p>

            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-display font-extrabold leading-[1.1] tracking-tight reveal">
                ¿Listo para crear tu <span class="text-[#F09719]">invitación digital</span>?
            </h2>

            <p class="mt-5 text-[15px] sm:text-base lg:text-lg text-white/85 reveal max-w-2xl mx-auto">
                Cuéntanos qué evento tienes y te ayudamos a elegir el paquete ideal para que tu invitación se vea increíble.
            </p>

            <div class="mt-9 flex flex-col sm:flex-row gap-3 sm:gap-4 justify-center reveal">
                <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener" class="btn-primary text-base shadow-lg shadow-orange-500/40">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.967-.94 1.164-.173.198-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.71.306 1.263.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.002-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884"/>
                    </svg>
                    Cotizar por WhatsApp
                </a>
                <a href="#paquetes" class="btn-ghost-light text-base">Ver paquetes</a>
            </div>
        </div>
    </section>

    {{-- ===========================================================
         SECCIÓN 8 — FOOTER
         =========================================================== --}}
    <footer class="bg-[#18111F] text-gray-400 pt-14 sm:pt-16 pb-8 relative overflow-hidden">
        <div class="mx-auto max-w-[1200px] lg:max-w-[1280px] px-5 sm:px-8">

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10 lg:gap-8">

                {{-- Logo + tagline --}}
                <div class="lg:col-span-1">
                    <a href="/" class="inline-block" aria-label="Invitatorio — ir al inicio">
                        <img src="{{ asset('images/invitatorio_horizontal.png') }}"
                             alt="Invitatorio"
                             class="h-9 w-auto block brightness-0 invert"
                             loading="lazy" decoding="async">
                    </a>
                    <p class="mt-4 text-sm leading-relaxed">
                        Invitaciones digitales para momentos inolvidables.
                    </p>
                </div>

                {{-- Menú --}}
                <nav aria-label="Menú del footer">
                    <h3 class="font-display font-bold text-white text-sm uppercase tracking-wider mb-4">Menú</h3>
                    <ul class="space-y-2.5 text-sm">
                        <li><a href="#" class="hover:text-[#EB7512] transition-colors">Inicio</a></li>
                        <li><a href="#disenos" class="hover:text-[#EB7512] transition-colors">Diseños</a></li>
                        <li><a href="#paquetes" class="hover:text-[#EB7512] transition-colors">Paquetes</a></li>
                        <li><a href="#como-funciona" class="hover:text-[#EB7512] transition-colors">Cómo funciona</a></li>
                        <li><a href="#preguntas" class="hover:text-[#EB7512] transition-colors">Preguntas frecuentes</a></li>
                    </ul>
                </nav>

                {{-- Servicios --}}
                <nav aria-label="Servicios del footer">
                    <h3 class="font-display font-bold text-white text-sm uppercase tracking-wider mb-4">Servicios</h3>
                    <ul class="space-y-2.5 text-sm">
                        <li><a href="#disenos" class="hover:text-[#EB7512] transition-colors">Bodas</a></li>
                        <li><a href="#disenos" class="hover:text-[#EB7512] transition-colors">XV años</a></li>
                        <li><a href="#disenos" class="hover:text-[#EB7512] transition-colors">Cumpleaños</a></li>
                        <li><a href="#disenos" class="hover:text-[#EB7512] transition-colors">Bautizos</a></li>
                        <li><a href="#disenos" class="hover:text-[#EB7512] transition-colors">Baby shower</a></li>
                    </ul>
                </nav>

                {{-- Contacto --}}
                <div>
                    <h3 class="font-display font-bold text-white text-sm uppercase tracking-wider mb-4">Contacto</h3>
                    <ul class="space-y-3 text-sm">
                        <li>
                            <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 hover:text-[#EB7512] transition-colors">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.967-.94 1.164-.173.198-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.71.306 1.263.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.002-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884"/>
                                </svg>
                                WhatsApp
                            </a>
                        </li>
                        <li>
                            <a href="mailto:hola@invitatorio.com" class="inline-flex items-center gap-2 hover:text-[#EB7512] transition-colors">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 7l9 6 9-6"/>
                                </svg>
                                hola@invitatorio.com
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <hr class="border-white/10 my-10">

            <p class="text-center text-xs text-gray-500">
                © {{ date('Y') }} Invitatorio. Todos los derechos reservados.
            </p>
        </div>
    </footer>

</body>
</html>
