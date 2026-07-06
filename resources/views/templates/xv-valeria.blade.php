{{--
    Template: XV Años — Valeria (Paquete Esencial · $600 MXN)

    Datos hardcodeados por ahora. Más adelante estos vienen del modelo
    Invitacion / Evento y se pasan como props al renderizar.
--}}

@php
    $evento      = 'Mis XV Años';
    $nombre      = 'Valeria';
    $fechaCorta  = '24 · Agosto · 2025';
    $fechaLarga  = '24 de Agosto de 2025';
    $hora        = '06:00 P.M.';
    $lugar       = 'Salón Los Encinos';
    $direccion   = 'Calle 25 #120, Col. Jardines, Mérida, Yucatán';
    $mapsUrl     = 'https://www.google.com/maps';

    $dressCode         = 'Formal';
    $dressCodeDetalle  = 'Tu presencia es mi mejor regalo, pero si deseas acompañarnos con el estilo sugerido, te esperamos con vestimenta formal.';

    $mesaRegalosTexto = 'Tu cariño es el mejor regalo, pero si deseas obsequiarme algo, aquí puedes encontrar algunas sugerencias.';

    $musicaTitulo  = 'Mi canción especial';
    $musicaTexto   = 'Esta invitación tiene música. Activa el sonido y disfruta.';

    $mensajeEspecial = 'Gracias por ser parte de mi vida y de este momento tan especial. Tu presencia hará que este día sea inolvidable.';

    $whatsappNumber  = '529999999999';
    $whatsappMessage = 'Hola, quiero confirmar mi asistencia a los XV años de Valeria.';
    $waLink          = 'https://wa.me/' . $whatsappNumber . '?text=' . urlencode($whatsappMessage);

    // Fecha objetivo para la cuenta regresiva (Yucatán, UTC-6)
    $eventDateIso = '2025-08-24T18:00:00-06:00';
@endphp

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="theme-color" content="#2B143F">
    <meta name="description" content="Invitación digital — Mis XV Años de {{ $nombre }}. {{ $fechaLarga }} · {{ $hora }} · {{ $lugar }}.">

    {{-- Open Graph / WhatsApp preview --}}
    <meta property="og:title" content="Mis XV Años — {{ $nombre }}">
    <meta property="og:description" content="{{ $fechaLarga }} · {{ $hora }} · {{ $lugar }}.">
    <meta property="og:image" content="{{ asset('images/xv/valeria/intro-para-desktop.png') }}">
    <meta property="og:type" content="website">

    <title>Mis XV Años — {{ $nombre }}</title>

    {{-- Preload de la puerta de entrada según breakpoint --}}
    <link rel="preload" as="image" href="{{ asset('images/xv/valeria/intro-para-desktop.png') }}" media="(min-width: 1024px)">
    <link rel="preload" as="image" href="{{ asset('images/xv/valeria/intro-movil.png') }}" media="(max-width: 1023px)">

    {{-- Audio de fondo: se activa al primer click en la puerta de entrada --}}
    <audio id="bg-music" loop preload="none" src="{{ asset('images/xv/valeria/music.mp3') }}"></audio>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#FFFDF8] text-[#18111F] antialiased overflow-hidden" id="page-body">

    {{-- ============================================================
         0) PUERTA DE ENTRADA
         Imagen full-screen. Click en cualquier parte = reproducir música
         + esta imagen se oculta + aparece el hero original del template
         + se muestra todo el contenido de la invitación.
         La imagen ya integra el texto ("Entrar a mi invitación"), por eso
         no agregamos ninguna leyenda encima.
         ============================================================ --}}
    <section
        id="intro-gate"
        class="relative min-h-[100svh] w-full cursor-pointer overflow-hidden"
        role="img"
        aria-label="Entrar a la invitación de {{ $nombre }}"
    >
        <button
            type="button"
            id="intro-trigger"
            data-hero-enter
            aria-label="Entrar a la invitación"
            class="absolute inset-0 z-0 block h-full w-full cursor-pointer appearance-none border-0 bg-[url('/images/xv/valeria/intro-movil.png')] bg-no-repeat bg-center-top bg-cover p-0
                   lg:bg-[url('/images/xv/valeria/intro-para-desktop.png')]
                   focus:outline-none focus-visible:ring-2 focus-visible:ring-white/70"
        ></button>
    </section>


    {{-- ============================================================
         CONTENIDO DE LA INVITACIÓN (oculto hasta "entrar")
         Cuando el usuario hace click en la puerta de entrada, este wrapper
         se muestra y la puerta se oculta.
         ============================================================ --}}
    <div id="invite-content" class="hidden">

    {{-- ============================================================
         1) HERO / PORTADA  (el hero original del template)
         Imagen decorativa full-screen + botón circular con flecha abajo.
         ============================================================ --}}
    <section
        id="hero"
        class="hero relative min-h-[100svh] w-full overflow-hidden"
        role="img"
        aria-label="Invitación — Mis XV Años de {{ $nombre }}. {{ $fechaLarga }}, {{ $hora }}, {{ $lugar }}."
    >
        {{-- Background image: cambia por breakpoint --}}
        <div class="absolute inset-0 -z-10 bg-[url('/images/xv/valeria/valeria-movil.png')] bg-no-repeat bg-center-top bg-cover
                    lg:bg-[url('/images/xv/valeria/valeria-desktop.png')]"></div>

        {{-- Sutil overlay para legibilidad --}}
        <div class="absolute inset-0 -z-10 bg-gradient-to-b from-white/30 via-transparent to-white/40
                    lg:from-white/20 lg:via-transparent lg:to-white/30"></div>

        {{-- Contenido del hero:
             la imagen ya integra texto (Mis XV Años · XV · Años · Valeria · fecha),
             por lo tanto NO se duplica. Solo agregamos el botón de scroll. --}}
        <div class="relative mx-auto flex min-h-[100svh] max-w-[1100px] flex-col items-center
                    px-5 pb-8 pt-10 lg:px-8">

            {{-- Botón circular al fondo para bajar a la cuenta regresiva --}}
            <div class="mt-auto self-center">
                <a
                    href="#cuenta-regresiva"
                    class="anim-fade-in anim-delay-300 anim-float-soft inline-flex h-14 w-14 items-center
                           justify-center rounded-full bg-white/95 text-[#5A3087] shadow-[0_10px_28px_-12px_rgba(43,20,63,0.45)]
                           ring-1 ring-[#5A3087]/15 backdrop-blur transition-transform duration-300 hover:scale-105
                           lg:h-16 lg:w-16"
                    aria-label="Bajar a la cuenta regresiva"
                >
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                         stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M6 9l6 6 6-6"/>
                    </svg>
                </a>
            </div>
        </div>
    </section>


    {{-- ============================================================
         2) CUENTA REGRESIVA
         ============================================================ --}}
    <section
        id="cuenta-regresiva"
        class="section-padding bg-gradient-to-b from-[#FFFDF8] to-[#F4EFF8]"
        aria-label="Cuenta regresiva"
    >
        <div class="mx-auto max-w-[1100px] px-5">
            <div class="reveal text-center">
                <p class="text-[12px] font-semibold uppercase tracking-[0.35em] text-[#F09719]">
                    Muy pronto
                </p>
                <h2 class="mt-3 font-display text-4xl font-bold text-[#2B143F] lg:text-5xl">
                    Faltan
                </h2>
                <p class="mx-auto mt-3 max-w-md text-[#5F5A66]">
                    Cada segundo nos acerca a una noche que vamos a recordar para siempre.
                </p>
            </div>

            <div
                class="reveal mt-12 grid grid-cols-2 gap-4 sm:gap-5 lg:mt-14 lg:grid-cols-4 lg:gap-6"
                data-reveal-delay="120"
                role="timer"
                aria-live="polite"
                data-event-date="{{ $eventDateIso }}"
                id="countdown"
            >
                {{-- Días --}}
                <div class="reveal rounded-2xl bg-white px-4 py-7 text-center shadow-[0_14px_34px_-22px_rgba(43,20,63,0.45)] ring-1 ring-[#EFE7F4] lg:py-9" data-reveal-delay="0">
                    <span data-unit="days" class="font-display text-5xl font-extrabold text-[#5A3087] lg:text-6xl">00</span>
                    <p class="mt-2 text-[11px] font-semibold uppercase tracking-[0.25em] text-[#5F5A66]">Días</p>
                </div>
                {{-- Horas --}}
                <div class="reveal rounded-2xl bg-white px-4 py-7 text-center shadow-[0_14px_34px_-22px_rgba(43,20,63,0.45)] ring-1 ring-[#EFE7F4] lg:py-9" data-reveal-delay="120">
                    <span data-unit="hours" class="font-display text-5xl font-extrabold text-[#5A3087] lg:text-6xl">00</span>
                    <p class="mt-2 text-[11px] font-semibold uppercase tracking-[0.25em] text-[#5F5A66]">Horas</p>
                </div>
                {{-- Minutos --}}
                <div class="reveal rounded-2xl bg-white px-4 py-7 text-center shadow-[0_14px_34px_-22px_rgba(43,20,63,0.45)] ring-1 ring-[#EFE7F4] lg:py-9" data-reveal-delay="240">
                    <span data-unit="minutes" class="font-display text-5xl font-extrabold text-[#5A3087] lg:text-6xl">00</span>
                    <p class="mt-2 text-[11px] font-semibold uppercase tracking-[0.25em] text-[#5F5A66]">Minutos</p>
                </div>
                {{-- Segundos --}}
                <div class="reveal rounded-2xl bg-white px-4 py-7 text-center shadow-[0_14px_34px_-22px_rgba(43,20,63,0.45)] ring-1 ring-[#EFE7F4] lg:py-9" data-reveal-delay="360">
                    <span data-unit="seconds" class="font-display text-5xl font-extrabold text-[#5A3087] lg:text-6xl">00</span>
                    <p class="mt-2 text-[11px] font-semibold uppercase tracking-[0.25em] text-[#5F5A66]">Segundos</p>
                </div>
            </div>

            {{-- Mini detalle dorado --}}
            <div class="reveal mx-auto mt-8 flex w-fit items-center gap-3 text-[#F09719]/80" data-reveal-delay="200">
                <span class="h-px w-12 bg-current"></span>
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                    <path d="M12 2l1.9 5.9H20l-5 3.6 1.9 5.9L12 13.8 7.1 17.4 9 11.5 4 7.9h6.1L12 2z"/>
                </svg>
                <span class="h-px w-12 bg-current"></span>
            </div>
        </div>
    </section>


    {{-- ============================================================
         3) INFORMACIÓN DEL EVENTO
         ============================================================ --}}
    <section id="evento" class="section-padding bg-white" aria-label="Información del evento">
        <div class="mx-auto max-w-[1100px] px-5">
            <div class="reveal text-center">
                <p class="text-[12px] font-semibold uppercase tracking-[0.35em] text-[#F09719]">
                    La cita
                </p>
                <h2 class="mt-3 font-display text-3xl font-bold text-[#2B143F] lg:text-5xl">
                    Acompáñame en este día tan especial
                </h2>
            </div>

            <div class="reveal mt-12 grid gap-4 sm:grid-cols-3 lg:mt-14 lg:gap-6">
                {{-- Fecha --}}
                <div class="reveal relative flex flex-col items-center rounded-2xl bg-[#FFFDF8] px-6 py-8 ring-1 ring-[#EFE7F4] lg:py-10" data-reveal-delay="0">
                    <div class="flex h-14 w-14 items-center justify-center rounded-full bg-[#F4EEFB] text-[#5A3087]">
                        <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                             stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <rect x="3" y="5" width="18" height="16" rx="2"/>
                            <path d="M3 9h18M8 3v4M16 3v4"/>
                        </svg>
                    </div>
                    <p class="mt-5 text-[11px] font-semibold uppercase tracking-[0.3em] text-[#5F5A66]">Fecha</p>
                    <p class="mt-2 font-display text-2xl font-bold text-[#2B143F] lg:text-3xl">
                        24 Agosto 2025
                    </p>
                </div>

                {{-- Hora --}}
                <div class="reveal relative flex flex-col items-center rounded-2xl bg-[#FFFDF8] px-6 py-8 ring-1 ring-[#EFE7F4] lg:py-10" data-reveal-delay="120">
                    <div class="flex h-14 w-14 items-center justify-center rounded-full bg-[#F4EEFB] text-[#5A3087]">
                        <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                             stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <circle cx="12" cy="12" r="9"/>
                            <path d="M12 7v5l3 2"/>
                        </svg>
                    </div>
                    <p class="mt-5 text-[11px] font-semibold uppercase tracking-[0.3em] text-[#5F5A66]">Hora</p>
                    <p class="mt-2 font-display text-2xl font-bold text-[#2B143F] lg:text-3xl">
                        {{ $hora }}
                    </p>
                </div>

                {{-- Evento --}}
                <div class="reveal relative flex flex-col items-center rounded-2xl bg-[#FFFDF8] px-6 py-8 ring-1 ring-[#EFE7F4] lg:py-10" data-reveal-delay="240">
                    <div class="flex h-14 w-14 items-center justify-center rounded-full bg-[#F4EEFB] text-[#5A3087]">
                        <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                             stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M12 3l1.7 4.6L18 8l-3.5 3 1 5L12 13.6 8.5 16l1-5L6 8l4.3-.4L12 3z"/>
                        </svg>
                    </div>
                    <p class="mt-5 text-[11px] font-semibold uppercase tracking-[0.3em] text-[#5F5A66]">Evento</p>
                    <p class="mt-2 font-display text-2xl font-bold text-[#2B143F] lg:text-3xl">
                        Recepción a seguir
                    </p>
                </div>
            </div>
        </div>
    </section>


    {{-- ============================================================
         4) DRESS CODE
         ============================================================ --}}
    <section id="dress-code" class="section-padding bg-[#F4EEFB]" aria-label="Dress code">
        <div class="mx-auto max-w-[820px] px-5">
            <div class="reveal rounded-3xl bg-white px-7 py-10 text-center shadow-[0_22px_44px_-28px_rgba(43,20,63,0.30)] ring-1 ring-[#EFE7F4] lg:px-12 lg:py-14">
                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-[#F4EEFB] text-[#5A3087]">
                    <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"
                         stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M8 3l-3 6 4 2v10h10V11l4-2-3-6-2 1-3 4-2-1V3H8z"/>
                    </svg>
                </div>

                <p class="mt-6 text-[12px] font-semibold uppercase tracking-[0.35em] text-[#F09719]">
                    Dress code
                </p>
                <h2 class="mt-2 font-display text-4xl font-bold text-[#2B143F] lg:text-5xl">
                    {{ $dressCode }}
                </h2>

                <p class="mx-auto mt-5 max-w-md text-[#5F5A66] lg:text-lg">
                    {{ $dressCodeDetalle }}
                </p>

                {{-- Colores sugeridos --}}
                <div class="mt-8 flex flex-wrap items-center justify-center gap-4">
                    <span class="flex items-center gap-2 text-sm text-[#5F5A66]">
                        <span class="h-5 w-5 rounded-full ring-1 ring-[#EFE7F4]" style="background:#C9A6E0"></span>
                        Lila
                    </span>
                    <span class="flex items-center gap-2 text-sm text-[#5F5A66]">
                        <span class="h-5 w-5 rounded-full ring-1 ring-[#EFE7F4]" style="background:#F7C9D6"></span>
                        Rosa suave
                    </span>
                    <span class="flex items-center gap-2 text-sm text-[#5F5A66]">
                        <span class="h-5 w-5 rounded-full ring-1 ring-[#EFE7F4]" style="background:#F09719"></span>
                        Dorado
                    </span>
                    <span class="flex items-center gap-2 text-sm text-[#5F5A66]">
                        <span class="h-5 w-5 rounded-full ring-1 ring-[#EFE7F4]" style="background:#E8DCC4"></span>
                        Beige
                    </span>
                </div>
            </div>
        </div>
    </section>


    {{-- ============================================================
         5) UBICACIÓN
         ============================================================ --}}
    <section id="ubicacion" class="section-padding bg-white" aria-label="Ubicación">
        <div class="mx-auto max-w-[1100px] px-5">
            <div class="reveal text-center">
                <p class="text-[12px] font-semibold uppercase tracking-[0.35em] text-[#F09719]">
                    Dónde
                </p>
                <h2 class="mt-3 font-display text-4xl font-bold text-[#2B143F] lg:text-5xl">
                    Ubicación
                </h2>
            </div>

            <div class="reveal mt-12 grid items-stretch gap-6 lg:mt-14 lg:grid-cols-[1fr_1.2fr]">
                {{-- Datos --}}
                <div class="flex flex-col justify-center rounded-2xl bg-[#FFFDF8] px-7 py-9 ring-1 ring-[#EFE7F4] lg:px-10 lg:py-12">
                    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-[#F4EEFB] text-[#5A3087]">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                             stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M12 22s-7-7.5-7-13a7 7 0 1 1 14 0c0 5.5-7 13-7 13z"/>
                            <circle cx="12" cy="9" r="2.5"/>
                        </svg>
                    </div>
                    <p class="mt-5 text-[11px] font-semibold uppercase tracking-[0.3em] text-[#5F5A66]">
                        Lugar
                    </p>
                    <h3 class="mt-2 font-display text-2xl font-bold text-[#2B143F] lg:text-3xl">
                        {{ $lugar }}
                    </h3>
                    <p class="mt-3 text-[#5F5A66]">
                        {{ $direccion }}
                    </p>

                    <a
                        href="{{ $mapsUrl }}"
                        target="_blank" rel="noopener"
                        class="mt-7 inline-flex w-fit items-center gap-2 rounded-full bg-[#5A3087] px-6 py-3 font-semibold text-white shadow-[0_14px_28px_-14px_rgba(90,48,135,0.55)] transition-all duration-300 hover:-translate-y-0.5 hover:bg-[#2B143F]"
                    >
                        Ver en mapa
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                             stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M7 17L17 7M9 7h8v8"/>
                        </svg>
                    </a>
                </div>

                {{-- Mockup de mapa --}}
                <div class="relative overflow-hidden rounded-2xl bg-[#EEF1F5] ring-1 ring-[#EFE7F4]" aria-hidden="true">
                    {{-- Líneas decorativas tipo calles --}}
                    <svg class="absolute inset-0 h-full w-full" viewBox="0 0 600 400" preserveAspectRatio="xMidYMid slice">
                        <defs>
                            <pattern id="streets" width="80" height="80" patternUnits="userSpaceOnUse">
                                <path d="M0 40 H80 M40 0 V80" stroke="#D9DEE6" stroke-width="1"/>
                            </pattern>
                        </defs>
                        <rect width="600" height="400" fill="url(#streets)"/>
                        <path d="M0 120 Q200 80 400 200 T600 260" stroke="#C9D1DC" stroke-width="3" fill="none"/>
                        <path d="M0 320 Q150 280 350 320 T600 300" stroke="#C9D1DC" stroke-width="2" fill="none"/>
                        <circle cx="300" cy="200" r="120" fill="#F4EEFB" opacity=".7"/>
                        <rect x="80"  y="60"  width="100" height="60" rx="6" fill="#DCE7F0"/>
                        <rect x="420" y="100" width="110" height="70" rx="6" fill="#DCE7F0"/>
                        <rect x="120" y="280" width="120" height="60" rx="6" fill="#DCE7F0"/>
                        <rect x="380" y="260" width="130" height="70" rx="6" fill="#DCE7F0"/>
                    </svg>

                    {{-- Pin central --}}
                    <div class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-full">
                        <div class="relative">
                            <div class="absolute -inset-3 animate-ping rounded-full bg-[#5A3087]/30"></div>
                            <svg class="relative h-12 w-12 drop-shadow-[0_8px_14px_rgba(43,20,63,0.45)]" viewBox="0 0 24 24" fill="#5A3087" aria-hidden="true">
                                <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5a2.5 2.5 0 1 1 0-5 2.5 2.5 0 0 1 0 5z"/>
                            </svg>
                        </div>
                    </div>

                    {{-- Etiqueta sobre el pin --}}
                    <div class="absolute bottom-6 left-1/2 -translate-x-1/2 rounded-full bg-white px-5 py-2 text-sm font-semibold text-[#2B143F] shadow-lg ring-1 ring-[#EFE7F4]">
                        {{ $lugar }}
                    </div>
                </div>
            </div>
        </div>
    </section>


    {{-- ============================================================
         6) MESA DE REGALOS
         ============================================================ --}}
    <section id="mesa-regalos" class="section-padding bg-[#FFF7F4]" aria-label="Mesa de regalos">
        <div class="mx-auto max-w-[820px] px-5">
            <div class="reveal rounded-3xl bg-white px-7 py-10 text-center shadow-[0_22px_44px_-28px_rgba(43,20,63,0.30)] ring-1 ring-[#EFE7F4] lg:px-12 lg:py-14">
                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-[#F4EEFB] text-[#5A3087]">
                    <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"
                         stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <rect x="3" y="8" width="18" height="13" rx="2"/>
                        <path d="M3 12h18M12 8v13"/>
                        <path d="M12 8c-2 0-4-1.5-4-3.5S10 2 12 4c2-2 4-1.5 4 .5S14 8 12 8z"/>
                    </svg>
                </div>

                <p class="mt-6 text-[12px] font-semibold uppercase tracking-[0.35em] text-[#F09719]">
                    Detalle
                </p>
                <h2 class="mt-2 font-display text-4xl font-bold text-[#2B143F] lg:text-5xl">
                    Mesa de regalos
                </h2>

                <p class="mx-auto mt-5 max-w-md text-[#5F5A66] lg:text-lg">
                    {{ $mesaRegalosTexto }}
                </p>

                <a
                    href="#"
                    class="mt-7 inline-flex items-center gap-2 rounded-full bg-gradient-to-r from-[#5A3087] to-[#8B5BC9] px-7 py-3.5 font-semibold text-white shadow-[0_18px_30px_-14px_rgba(90,48,135,0.55)] transition-all duration-300 hover:-translate-y-0.5 hover:shadow-[0_22px_38px_-14px_rgba(90,48,135,0.65)]"
                >
                    Ver detalles
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                         stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M5 12h14M13 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </div>
    </section>


    {{-- ============================================================
         7) MÚSICA DE FONDO
         ============================================================ --}}
    <section id="musica" class="section-padding bg-white" aria-label="Música">
        <div class="mx-auto max-w-[820px] px-5">
            <div class="reveal rounded-3xl bg-gradient-to-br from-[#2B143F] to-[#5A3087] px-7 py-10 text-center text-white shadow-[0_24px_48px_-22px_rgba(43,20,63,0.55)] lg:px-12 lg:py-14">
                <p class="text-[12px] font-semibold uppercase tracking-[0.35em] text-[#F09719]">
                    Mi canción
                </p>
                <h2 class="mt-2 font-display text-4xl font-bold lg:text-5xl">
                    Música de fondo
                </h2>
                <p class="mx-auto mt-4 max-w-md text-white/80 lg:text-lg">
                    {{ $musicaTexto }}
                </p>

                {{-- Reproductor --}}
                <div class="mt-8 flex flex-col items-center gap-5" data-music-player>
                    <button
                        type="button"
                        data-music-toggle
                        aria-label="Reproducir música"
                        class="group inline-flex h-16 w-16 items-center justify-center rounded-full bg-white text-[#5A3087] shadow-[0_18px_36px_-14px_rgba(0,0,0,0.55)] transition-transform duration-300 hover:scale-105"
                    >
                        <svg class="h-7 w-7" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" data-icon-play>
                            <path d="M8 5v14l11-7z"/>
                        </svg>
                        <svg class="hidden h-7 w-7" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" data-icon-pause>
                            <path d="M6 5h4v14H6zM14 5h4v14h-4z"/>
                        </svg>
                    </button>

                    <p class="font-display text-lg font-semibold text-white/95 lg:text-xl">
                        {{ $musicaTitulo }}
                    </p>

                    {{-- Barra decorativa tipo onda de audio --}}
                    <div class="flex h-10 items-end gap-1.5" aria-hidden="true" data-music-wave>
                        @for ($i = 0; $i < 24; $i++)
                            <span
                                class="block w-1 origin-bottom rounded-full bg-white/60 transition-transform duration-300"
                                style="height: {{ 30 + (($i * 17) % 70) }}%"
                            ></span>
                        @endfor
                    </div>
                </div>

                {{-- El elemento <audio> real vive en el <head> (id="bg-music") para que
                     pueda ser controlado desde el hero, esta sección y el botón flotante. --}}
            </div>
        </div>
    </section>


    {{-- ============================================================
         8) MENSAJE ESPECIAL
         ============================================================ --}}
    <section id="mensaje" class="section-padding bg-[#F4EEFB]" aria-label="Mensaje especial">
        <div class="mx-auto max-w-[820px] px-5">
            <div class="reveal relative rounded-3xl bg-white px-7 py-10 text-center shadow-[0_22px_44px_-28px_rgba(43,20,63,0.30)] ring-1 ring-[#EFE7F4] lg:px-12 lg:py-14">

                {{-- Comillas decorativas --}}
                <svg class="absolute left-6 top-6 h-10 w-10 text-[#F09719]/30 lg:left-8 lg:top-8" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                    <path d="M7 7h4v4H8c0 2 1 3 3 3v3c-4 0-6-3-6-6V7zm9 0h4v4h-3c0 2 1 3 3 3v3c-4 0-6-3-6-6V7z"/>
                </svg>
                <svg class="absolute right-6 bottom-6 h-10 w-10 rotate-180 text-[#F09719]/30 lg:right-8 lg:bottom-8" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                    <path d="M7 7h4v4H8c0 2 1 3 3 3v3c-4 0-6-3-6-6V7zm9 0h4v4h-3c0 2 1 3 3 3v3c-4 0-6-3-6-6V7z"/>
                </svg>

                {{-- Separador dorado superior --}}
                <div class="mx-auto flex w-fit items-center gap-3 text-[#F09719]/70">
                    <span class="h-px w-10 bg-current"></span>
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                        <path d="M12 2l1.9 5.9H20l-5 3.6 1.9 5.9L12 13.8 7.1 17.4 9 11.5 4 7.9h6.1L12 2z"/>
                    </svg>
                    <span class="h-px w-10 bg-current"></span>
                </div>

                <h2 class="mt-6 font-display text-3xl font-bold text-[#2B143F] lg:text-4xl">
                    Mensaje especial
                </h2>

                <p class="mx-auto mt-6 max-w-xl text-lg leading-relaxed text-[#5F5A66] lg:text-xl lg:leading-relaxed">
                    {{ $mensajeEspecial }}
                </p>

                <p class="mt-6 font-display text-xl font-semibold text-[#5A3087]">
                    — {{ $nombre }}
                </p>
            </div>
        </div>
    </section>


    {{-- ============================================================
         9) WHATSAPP / CONFIRMACIÓN
         ============================================================ --}}
    <section id="whatsapp" class="section-padding bg-white" aria-label="Confirmar por WhatsApp">
        <div class="mx-auto max-w-[820px] px-5">
            <div class="reveal rounded-3xl bg-gradient-to-br from-[#5A3087] to-[#2B143F] px-7 py-10 text-center text-white shadow-[0_26px_50px_-22px_rgba(43,20,63,0.55)] lg:px-12 lg:py-14">
                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-white/10">
                    <svg class="h-7 w-7" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.967-.94 1.164-.173.198-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.71.306 1.263.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.002-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884"/>
                    </svg>
                </div>

                <h2 class="mt-5 font-display text-3xl font-bold lg:text-5xl">
                    ¿Tienes dudas?
                </h2>
                <p class="mx-auto mt-4 max-w-md text-white/85 lg:text-lg">
                    Estoy feliz de resolver cualquier duda y confirmar tu compañía.
                </p>

                <div class="mt-8 flex flex-col items-center gap-3 sm:flex-row sm:justify-center">
                    <a
                        href="{{ $waLink }}"
                        target="_blank" rel="noopener"
                        class="inline-flex w-full items-center justify-center gap-2 rounded-full bg-white px-7 py-3.5 font-semibold text-[#2B143F] shadow-lg transition-all duration-300 hover:-translate-y-0.5 sm:w-auto"
                    >
                        Escríbeme por WhatsApp
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                             stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M5 12h14M13 5l7 7-7 7"/>
                        </svg>
                    </a>
                    <a
                        href="{{ $waLink }}"
                        target="_blank" rel="noopener"
                        class="inline-flex w-full items-center justify-center gap-2 rounded-full border-2 border-white/55 px-7 py-3.5 font-semibold text-white transition-all duration-300 hover:border-white hover:bg-white/10 sm:w-auto"
                    >
                        Confirmar asistencia
                    </a>
                </div>

                {{-- Compartir invitación --}}
                <div class="mt-10 border-t border-white/15 pt-6">
                    <button
                        type="button"
                        data-share-btn
                        class="inline-flex items-center gap-2 text-sm font-medium text-white/85 transition-colors hover:text-white"
                    >
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                             stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <circle cx="18" cy="5" r="3"/>
                            <circle cx="6" cy="12" r="3"/>
                            <circle cx="18" cy="19" r="3"/>
                            <path d="M8.6 13.5l6.8 4M15.4 6.5l-6.8 4"/>
                        </svg>
                        Compartir invitación
                    </button>
                    <span data-share-feedback class="ml-3 hidden text-sm font-medium text-[#F09719]">
                        ¡Enlace copiado!
                    </span>
                </div>
            </div>
        </div>
    </section>


    {{-- ============================================================
         10) FOOTER
         ============================================================ --}}
    <footer class="bg-[#2B143F] text-white">
        <div class="relative mx-auto max-w-[1100px] px-5 py-14 text-center">
            {{-- Decoración dorada --}}
            <div class="mx-auto flex w-fit items-center gap-3 text-[#F09719]/80">
                <span class="h-px w-10 bg-current"></span>
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                    <path d="M12 2l1.9 5.9H20l-5 3.6 1.9 5.9L12 13.8 7.1 17.4 9 11.5 4 7.9h6.1L12 2z"/>
                </svg>
                <span class="h-px w-10 bg-current"></span>
            </div>

            <p class="mt-5 font-display text-3xl font-bold lg:text-4xl">
                ¡No faltes!
            </p>
            <p class="mt-2 text-white/80 lg:text-lg">
                Será un día inolvidable
            </p>

            <p class="mt-8 font-display text-xl font-semibold lg:text-2xl">
                {{ $nombre }} <span class="text-[#F09719]">—</span> {{ $evento }}
            </p>
            <p class="mt-1 text-sm text-white/70">
                {{ $fechaLarga }}
            </p>

            {{-- Mini iconos decorativos --}}
            <div class="mt-7 flex items-center justify-center gap-4 text-white/70">
                <a href="{{ $waLink }}" target="_blank" rel="noopener" aria-label="WhatsApp" class="transition-colors hover:text-white">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.967-.94 1.164-.173.198-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.71.306 1.263.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.002-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884"/>
                    </svg>
                </a>
                <span class="h-1 w-1 rounded-full bg-white/40"></span>
                <a href="#" aria-label="Instagram" class="transition-colors hover:text-white">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <rect x="3" y="3" width="18" height="18" rx="5"/>
                        <circle cx="12" cy="12" r="4"/>
                        <circle cx="17.5" cy="6.5" r="1" fill="currentColor"/>
                    </svg>
                </a>
            </div>

            <p class="mt-8 text-xs text-white/55">
                © 2025 {{ $nombre }} · {{ $evento }}
            </p>
        </div>
    </footer>

    </div>{{-- /#invite-content --}}


    {{-- ============================================================
         BOTÓN FLOTANTE DE BOCINA (play/pause de la música)
         Se muestra sólo DESPUÉS de entrar a la invitación.
         ============================================================ --}}
    <button
        type="button"
        id="music-fab"
        data-music-toggle
        aria-label="Reproducir música"
        class="fixed bottom-5 left-5 z-40 inline-flex h-14 w-14 items-center justify-center
               rounded-full bg-white/95 text-[#5A3087] shadow-[0_18px_36px_-14px_rgba(90,48,135,0.55)]
               ring-1 ring-[#5A3087]/15 backdrop-blur transition-all duration-300 hover:scale-105
               lg:bottom-7 lg:left-7 lg:h-16 lg:w-16
               opacity-0 pointer-events-none"
    >
        {{-- Icono "bocina activa" (música sonando) --}}
        <svg class="h-7 w-7 lg:h-8 lg:w-8" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" data-icon-on>
            <path d="M3 10v4h4l5 5V5L7 10H3zm13.5 2c0-1.77-1.02-3.29-2.5-4.03v8.05c1.48-.73 2.5-2.25 2.5-4.02zM14 3.23v2.06c2.89.86 5 3.54 5 6.71s-2.11 5.85-5 6.71v2.06c4.01-.91 7-4.49 7-8.77s-2.99-7.86-7-8.77z"/>
        </svg>
        {{-- Icono "bocina muda" (música pausada) --}}
        <svg class="hidden h-7 w-7 lg:h-8 lg:w-8" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" data-icon-off>
            <path d="M16.5 12c0-1.77-1.02-3.29-2.5-4.03v2.21l2.45 2.45c.03-.2.05-.41.05-.63zm2.5 0c0 .94-.2 1.82-.54 2.64l1.51 1.51C20.63 14.91 21 13.5 21 12c0-4.28-2.99-7.86-7-8.77v2.06c2.89.86 5 3.54 5 6.71zM4.27 3L3 4.27 7.73 9H3v6h4l5 5v-6.73l4.25 4.25c-.67.52-1.42.93-2.25 1.17v2.06c1.38-.31 2.63-.95 3.69-1.81L19.73 21 21 19.73l-9-9L4.27 3zM12 4L9.91 6.09 12 8.18V4z"/>
        </svg>
        {{-- Onda pequeña al lado de la bocina cuando suena --}}
        <span class="pointer-events-none absolute -right-1 -top-1 flex h-3 w-3" data-music-dot aria-hidden="true">
            <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-[#F09719] opacity-75"></span>
            <span class="relative inline-flex h-3 w-3 rounded-full bg-[#F09719]"></span>
        </span>
    </button>


    {{-- ============================================================
         (Sin botón flotante de WhatsApp.
         El botón de "Confirmar por WhatsApp" vive dentro del contenido,
         en la sección 9, como parte del template.)
         ============================================================ --}}


    {{-- ============================================================
         JS inline: countdown + música + share
         (vanilla, sin librerías, respeta prefers-reduced-motion)
         ============================================================ --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            'use strict';

            /* ---------- Cuenta regresiva ---------- */
            const countdown = document.getElementById('countdown');
            if (countdown) {
                const target = new Date(countdown.dataset.eventDate).getTime();
                const els = {
                    days:    countdown.querySelector('[data-unit="days"]'),
                    hours:   countdown.querySelector('[data-unit="hours"]'),
                    minutes: countdown.querySelector('[data-unit="minutes"]'),
                    seconds: countdown.querySelector('[data-unit="seconds"]'),
                };

                const pad = (n) => String(Math.max(0, n)).padStart(2, '0');

                const tick = () => {
                    const diff = target - Date.now();
                    const past = diff <= 0;
                    const totalSec = past ? 0 : Math.floor(diff / 1000);

                    els.days.textContent    = pad(past ? 0 : Math.floor(totalSec / 86400));
                    els.hours.textContent   = pad(past ? 0 : Math.floor((totalSec % 86400) / 3600));
                    els.minutes.textContent = pad(past ? 0 : Math.floor((totalSec % 3600) / 60));
                    els.seconds.textContent = pad(totalSec % 60);
                };

                tick();
                setInterval(tick, 1000);
            }

            /* ---------- Música: controlador único compartido por hero / sección / FAB ----------
               El <audio> vive en el <head> (id="bg-music"). Puntos de control:
                 · [data-hero-enter]   → click en el hero (reproduce + muestra contenido + revela flotantes)
                 · [data-music-toggle] → play/pause (sección 7 y FAB flotante)
               Todos se sincronizan a través de updateUI(). */
            const audio = document.getElementById('bg-music');
            const heroTriggers = document.querySelectorAll('[data-hero-enter]');
            const toggles  = document.querySelectorAll('[data-music-toggle]');
            const wave     = document.querySelector('[data-music-wave]');
            const fab      = document.getElementById('music-fab');
            const inviteContent = document.getElementById('invite-content');
            const pageBody      = document.getElementById('page-body');
            const introGate     = document.getElementById('intro-gate');

            let entered = false;

            const revealInvitation = () => {
                // Oculta la puerta de entrada
                introGate?.classList.add('hidden');
                // Muestra el contenido (que ahora incluye el hero original)
                inviteContent?.classList.remove('hidden');
                // Habilita el scroll
                pageBody?.classList.remove('overflow-hidden');
                // Revela el FAB de la bocina
                if (fab) fab.classList.remove('opacity-0', 'pointer-events-none');
                entered = true;
                // Scroll suave al hero para enfocar la entrada
                const heroEl = document.getElementById('hero');
                if (heroEl) heroEl.scrollIntoView({ behavior: 'smooth', block: 'start' });
            };

            if (audio) {
                let playing = false;
                let firstPlayDone = false;

                const updateUI = () => {
                    toggles.forEach((btn) => {
                        const iconOn  = btn.querySelector('[data-icon-on],  [data-icon-pause]');
                        const iconOff = btn.querySelector('[data-icon-off], [data-icon-play]');
                        btn.setAttribute('aria-label', playing ? 'Pausar música' : 'Reproducir música');
                        if (iconOn)  iconOn.classList.toggle('hidden', !playing);
                        if (iconOff) iconOff.classList.toggle('hidden',  playing);
                    });
                    wave?.classList.toggle('is-active', playing);
                };

                const play = () => {
                    audio.play().then(() => {
                        playing = true;
                        firstPlayDone = true;
                        updateUI();
                    }).catch(() => { /* autoplay bloqueado / archivo no listo */ });
                };

                const pause = () => {
                    audio.pause();
                    playing = false;
                    updateUI();
                };

                const toggle = () => (playing ? pause() : play());

                // Click en el hero (cualquier parte) = "entrar a la invitación"
                heroTriggers.forEach((el) => {
                    el.addEventListener('click', (e) => {
                        if (!entered) revealInvitation();
                        if (!playing) play();
                    });
                });

                // Los toggles sí alternan play/pause
                toggles.forEach((btn) => btn.addEventListener('click', toggle));

                // Si el audio termina o se pausa desde otra pestaña, sincronizar UI
                audio.addEventListener('pause', () => { playing = false; updateUI(); });
                audio.addEventListener('play',  () => { playing = true;  firstPlayDone = true; updateUI(); });

                updateUI();
            }

            /* ---------- Web Share API con fallback a clipboard ---------- */
            const shareBtn = document.querySelector('[data-share-btn]');
            if (shareBtn) {
                const feedback = document.querySelector('[data-share-feedback]');
                const showCopied = () => {
                    if (!feedback) return;
                    feedback.classList.remove('hidden');
                    setTimeout(() => feedback.classList.add('hidden'), 2200);
                };

                shareBtn.addEventListener('click', async () => {
                    const data = {
                        title: 'Mis XV Años — {{ $nombre }}',
                        text:  'Te invito a mis XV años · {{ $fechaLarga }} · {{ $hora }} · {{ $lugar }}',
                        url:   window.location.href,
                    };

                    if (navigator.share) {
                        try { await navigator.share(data); return; } catch (_) { /* user cancel */ }
                    }

                    try {
                        await navigator.clipboard.writeText(window.location.href);
                        showCopied();
                    } catch (_) {
                        // Fallback ultra básico
                        const ta = document.createElement('textarea');
                        ta.value = window.location.href;
                        document.body.appendChild(ta);
                        ta.select();
                        try { document.execCommand('copy'); showCopied(); } catch (e) {}
                        document.body.removeChild(ta);
                    }
                });
            }
        });
    </script>

    <style>
        /* Animación de la onda de audio cuando está activa */
        [data-music-wave].is-active span {
            animation: xv-wave 1s ease-in-out infinite;
        }
        [data-music-wave].is-active span:nth-child(2n)     { animation-duration: 1.2s; animation-delay: .15s; }
        [data-music-wave].is-active span:nth-child(3n)     { animation-duration: .8s;  animation-delay: .3s;  }
        [data-music-wave].is-active span:nth-child(5n)     { animation-duration: 1.4s; animation-delay: .05s; }

        @keyframes xv-wave {
            0%, 100% { transform: scaleY(0.35); }
            50%      { transform: scaleY(1);    }
        }

        @media (prefers-reduced-motion: reduce) {
            [data-music-wave].is-active span { animation: none !important; }
        }
    </style>
</body>
</html>