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

</body>
</html>
