@php
    $siteName = 'Invitatorio';
    $siteUrl = rtrim(config('app.url'), '/');
    $pageUrl = url('/');
    $seoTitle = 'Invitaciones digitales para bodas, XV y eventos | Invitatorio';
    $seoDescription = 'Invitaciones digitales listas para enviar por WhatsApp. Diseños personalizados para bodas, XV años y eventos, con música, Maps y confirmación de asistencia.';
    $seoKeywords = 'invitaciones digitales, invitaciones por WhatsApp, invitaciones para boda, invitaciones para XV años, invitaciones web, RSVP digital, invitaciones digitales México';
    $seoImage = asset('images/hero-desktop.png');
    $seoImageWidth = 1672;
    $seoImageHeight = 941;
    $xpertSystemsUrl = 'https://www.facebook.com/profile.php?id=100068794671008';
    // ⚠️ Reemplazar este número cuando tengas el WhatsApp real del negocio
    $whatsappNumber  = '529990000000';
    $whatsappMessage = 'Hola Invitatorio, quiero cotizar una invitación digital para mi evento.';
    $whatsappUrl     = 'https://wa.me/' . $whatsappNumber . '?text=' . urlencode($whatsappMessage);
    $faqs = [
        ['q' => '¿Cuánto tarda la entrega?', 'a' => 'Las invitaciones web suelen estar listas en 24 a 48 horas. Las de imagen se entregan el mismo día. El video depende de la duración, normalmente entre 2 y 4 días.'],
        ['q' => '¿Puedo pedir cambios después?', 'a' => 'Sí. Cada paquete incluye al menos un ajuste posterior. Si necesitas más cambios, se cotizan por separado antes de hacerlos.'],
        ['q' => '¿La invitación incluye música?', 'a' => 'Sí, todas las invitaciones web pueden llevar música de fondo. Solo nos pasas el nombre de la canción o el enlace.'],
        ['q' => '¿Cómo funciona la confirmación de asistencia?', 'a' => 'Tus invitados confirman con un botón dentro de la invitación. Tú recibes una lista con nombre, teléfono y mensaje opcional.'],
        ['q' => '¿Necesito saber programar o diseñar?', 'a' => 'No. Tú nos envías los datos del evento (fechas, fotos, textos) y nosotros armamos todo. Solo te pedimos aprobar.'],
        ['q' => '¿Se ve bien en celular?', 'a' => 'Sí. Las invitaciones se diseñan primero para celular, que es desde donde se abren el 95% de las veces.'],
        ['q' => '¿Cómo la comparto?', 'a' => 'Te mandamos un enlace listo para enviar por WhatsApp. También puedes usarlo en estados, Instagram o correo.'],
        ['q' => '¿Cómo se paga?', 'a' => 'Se pide un anticipo del 50% para comenzar y el resto se paga al entregar la invitación final. Aceptamos transferencia y otros métodos.'],
    ];
    $eventTypes = [
        ['name' => 'Bodas', 'tagline' => 'Todo claro para tus invitados', 'description' => 'Ceremonia, recepción, dress code, mesa de regalos, itinerario y RSVP en un enlace fácil de compartir.'],
        ['name' => 'XV años', 'tagline' => 'Una invitación con tu estilo', 'description' => 'Fotos, cuenta regresiva, música, padrinos y datos del salón en una página pensada para verse bien en celular.'],
        ['name' => 'Cumpleaños', 'tagline' => 'Rápida, bonita y práctica', 'description' => 'Invitación en imagen, video o web para compartir en WhatsApp, estados o redes sin complicarte.'],
        ['name' => 'Bautizos y baby shower', 'tagline' => 'Delicada y fácil de enviar', 'description' => 'Diseños suaves con fecha, ubicación, detalles del evento, mesa de regalos y confirmación si la necesitas.'],
    ];
    $schema = [
        '@context' => 'https://schema.org',
        '@graph' => [
            [
                '@type' => 'Organization',
                '@id' => $siteUrl . '/#organization',
                'name' => $siteName,
                'url' => $siteUrl,
                'logo' => asset('images/invitatorio_horizontal.png'),
                'description' => 'Estudio de invitaciones digitales para eventos sociales en México.',
                'areaServed' => ['@type' => 'Country', 'name' => 'México'],
                'contactPoint' => [
                    '@type' => 'ContactPoint',
                    'contactType' => 'customer service',
                    'telephone' => '+' . $whatsappNumber,
                    'contactOption' => 'TollFree',
                    'areaServed' => 'MX',
                    'availableLanguage' => ['es'],
                ],
                'sameAs' => [],
            ],
            [
                '@type' => 'WebSite',
                '@id' => $siteUrl . '/#website',
                'url' => $siteUrl,
                'name' => $siteName,
                'inLanguage' => 'es-MX',
                'publisher' => ['@id' => $siteUrl . '/#organization'],
                'creator' => ['@id' => $siteUrl . '/#xpertsystems'],
                'potentialAction' => [
                    '@type' => 'ContactAction',
                    'target' => $whatsappUrl,
                    'name' => 'Cotizar invitación por WhatsApp',
                ],
            ],
            [
                '@type' => 'WebPage',
                '@id' => $pageUrl . '#webpage',
                'url' => $pageUrl,
                'name' => $seoTitle,
                'description' => $seoDescription,
                'inLanguage' => 'es-MX',
                'isPartOf' => ['@id' => $siteUrl . '/#website'],
                'primaryImageOfPage' => ['@id' => $pageUrl . '#primaryimage'],
                'about' => ['@id' => $pageUrl . '#service'],
                'mainEntity' => ['@id' => $pageUrl . '#service'],
                'speakable' => ['@type' => 'SpeakableSpecification', 'xpath' => ['/html/head/title', '/html/head/meta[@name="description"]/@content']],
            ],
            [
                '@type' => 'ImageObject',
                '@id' => $pageUrl . '#primaryimage',
                'url' => $seoImage,
                'contentUrl' => $seoImage,
                'width' => $seoImageWidth,
                'height' => $seoImageHeight,
                'caption' => 'Vista previa de invitación digital de Invitatorio',
            ],
            [
                '@type' => 'Service',
                '@id' => $pageUrl . '#service',
                'name' => 'Invitaciones digitales personalizadas',
                'serviceType' => 'Diseño y creación de invitaciones digitales',
                'provider' => ['@id' => $siteUrl . '/#organization'],
                'areaServed' => ['@type' => 'Country', 'name' => 'México'],
                'audience' => [
                    '@type' => 'Audience',
                    'audienceType' => 'Personas que organizan bodas, XV años, cumpleaños, bautizos, baby shower y eventos sociales',
                ],
                'description' => $seoDescription,
                'offers' => [
                    '@type' => 'AggregateOffer',
                    'priceCurrency' => 'MXN',
                    'lowPrice' => '150',
                    'highPrice' => '1300',
                    'offerCount' => '7',
                    'availability' => 'https://schema.org/InStock',
                ],
                'hasOfferCatalog' => [
                    '@type' => 'OfferCatalog',
                    'name' => 'Tipos de invitaciones digitales',
                    'itemListElement' => array_map(fn ($event) => [
                        '@type' => 'Offer',
                        'itemOffered' => [
                            '@type' => 'Service',
                            'name' => 'Invitación digital para ' . $event['name'],
                            'description' => $event['description'],
                        ],
                    ], $eventTypes),
                ],
            ],
            [
                '@type' => 'BreadcrumbList',
                '@id' => $pageUrl . '#breadcrumb',
                'itemListElement' => [
                    [
                        '@type' => 'ListItem',
                        'position' => 1,
                        'name' => 'Inicio',
                        'item' => $pageUrl,
                    ],
                ],
            ],
            [
                '@type' => 'Organization',
                '@id' => $siteUrl . '/#xpertsystems',
                'name' => 'XpertSystems',
                'url' => $xpertSystemsUrl,
                'sameAs' => [$xpertSystemsUrl],
            ],
            [
                '@type' => 'FAQPage',
                '@id' => $pageUrl . '#faq',
                'mainEntity' => array_map(fn ($faq) => [
                    '@type' => 'Question',
                    'name' => $faq['q'],
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => $faq['a'],
                    ],
                ], $faqs),
            ],
        ],
    ];
@endphp

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="theme-color" content="#FFFDF8" media="(prefers-color-scheme: light)">
    <meta name="theme-color" content="#2B143F" media="(prefers-color-scheme: dark)">
    <meta name="description" content="{{ $seoDescription }}">
    <meta name="keywords" content="{{ $seoKeywords }}">
    <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1">
    <meta name="author" content="{{ $siteName }}">
    <meta name="application-name" content="{{ $siteName }}">
    <meta name="publisher" content="{{ $siteName }}">
    <meta name="geo.region" content="MX">
    <meta name="geo.placename" content="México">
    <meta name="format-detection" content="telephone=no">
    <link rel="canonical" href="{{ $pageUrl }}">
    <link rel="alternate" hreflang="es-mx" href="{{ $pageUrl }}">
    <link rel="alternate" hreflang="x-default" href="{{ $pageUrl }}">
    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/favicon.svg') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon-32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon-16.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/apple-touch-icon.png') }}">

    {{-- Open Graph --}}
    <meta property="og:title" content="{{ $seoTitle }}">
    <meta property="og:description" content="{{ $seoDescription }}">
    <meta property="og:image" content="{{ $seoImage }}">
    <meta property="og:image:secure_url" content="{{ $seoImage }}">
    <meta property="og:image:width" content="{{ $seoImageWidth }}">
    <meta property="og:image:height" content="{{ $seoImageHeight }}">
    <meta property="og:image:alt" content="Vista previa de invitación digital de bodas o XV años en celular">
    <meta property="og:url" content="{{ $pageUrl }}">
    <meta property="og:site_name" content="{{ $siteName }}">
    <meta property="og:locale" content="es_MX">
    <meta property="og:type" content="website">

    {{-- Twitter / X --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $seoTitle }}">
    <meta name="twitter:description" content="{{ $seoDescription }}">
    <meta name="twitter:image" content="{{ $seoImage }}">
    <meta name="twitter:image:alt" content="Vista previa de invitación digital de bodas o XV años en celular">

    <title>{{ $seoTitle }}</title>

    @fonts

    {{-- Preload crítico para LCP --}}
    <link rel="preconnect" href="{{ $siteUrl }}" crossorigin>
    <link rel="preload" as="image" href="/images/celular.png">
    <link rel="preload" as="image" href="/images/invitatorio_horizontal.png">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script type="application/ld+json">
        @json($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT)
    </script>
</head>
<body class="bg-[#FFFDF8] text-[#18111F] antialiased">

    {{-- ================================================================
         HERO SECTION (incluye header flotante arriba)
         ================================================================ --}}
    <section class="hero relative min-h-screen w-full overflow-hidden pb-12 lg:pb-20">

        <div class="hero-clean-bg absolute inset-0 -z-10" aria-hidden="true"></div>
        <div class="hero-soft-curve absolute inset-x-0 bottom-0 -z-10 h-[24%] sm:h-[30%] lg:h-[34%]" aria-hidden="true"></div>

        <div class="relative mx-auto max-w-[1240px] px-5 lg:px-8 pt-4 lg:pt-8">

            {{-- ============================================================
                 HEADER DESKTOP (≥1024px)
                 ============================================================ --}}
            <header
                data-header
                class="hidden lg:flex items-center justify-between gap-6
                       bg-white/95 backdrop-blur-sm rounded-lg
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
                    <a href="#incluye" class="nav-link">Qué incluye</a>
                    <a href="#disenos" class="nav-link">Diseños</a>
                    <a href="#paquetes" class="nav-link">Paquetes</a>
                    <a href="#como-funciona" class="nav-link">Cómo funciona</a>
                    <a href="#preguntas" class="nav-link">FAQ</a>
                </nav>

                {{-- CTAs derecha --}}
                <div class="flex items-center gap-3">
                    <a href="{{ $whatsappUrl }}"
                       class="btn-circle"
                       aria-label="Contactar por WhatsApp"
                       target="_blank" rel="noopener">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.967-.94 1.164-.173.198-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.71.306 1.263.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.002-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z"/>
                        </svg>
                    </a>
                    <a href="#contacto" class="inline-flex items-center justify-center gap-2 rounded-lg
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
                                       bg-white/95 backdrop-blur-sm rounded-lg
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
                        <li><a data-menu-link href="#incluye" class="block py-3 px-4 rounded-xl text-[#18111F] hover:bg-[#FFF1E1] hover:text-[#EB7512] transition-colors">Qué incluye</a></li>
                        <li><a data-menu-link href="#disenos" class="block py-3 px-4 rounded-xl text-[#18111F] hover:bg-[#FFF1E1] hover:text-[#EB7512] transition-colors">Diseños</a></li>
                        <li><a data-menu-link href="#paquetes" class="block py-3 px-4 rounded-xl text-[#18111F] hover:bg-[#FFF1E1] hover:text-[#EB7512] transition-colors">Paquetes</a></li>
                        <li><a data-menu-link href="#como-funciona" class="block py-3 px-4 rounded-xl text-[#18111F] hover:bg-[#FFF1E1] hover:text-[#EB7512] transition-colors">Cómo funciona</a></li>
                        <li><a data-menu-link href="#preguntas" class="block py-3 px-4 rounded-xl text-[#18111F] hover:bg-[#FFF1E1] hover:text-[#EB7512] transition-colors">Preguntas frecuentes</a></li>
                    </ul>
                </nav>

                <div class="px-5 py-5 border-t border-gray-100 flex flex-col gap-3">
                    <a data-menu-link href="{{ $whatsappUrl }}"
                       class="inline-flex items-center justify-center gap-2 w-full py-3 rounded-lg
                              border-2 border-[#EB7512] text-[#EB7512] font-semibold
                              hover:bg-[#FFF1E1] transition-colors">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.967-.94 1.164-.173.198-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.71.306 1.263.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.002-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z"/>
                        </svg>
                        Cotizar por WhatsApp
                    </a>
                    <a data-menu-link href="#contacto"
                       class="inline-flex items-center justify-center w-full py-3 rounded-lg
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
                        Invitaciones digitales para eventos
                    </p>

                    <h1 class="font-display font-extrabold leading-[1.1] lg:leading-[1.05]
                               text-[28px] sm:text-4xl lg:text-6xl xl:text-[64px]"
                        data-anim="fade-up" data-anim-delay="160">
                        <span class="text-[#2B143F]">Tu invitación digital, </span>
                        <span class="text-[#EB7512]">lista para mandar por WhatsApp</span>
                    </h1>

                    <p class="text-[15px] sm:text-base lg:text-lg leading-relaxed text-[#5F5A66]
                              max-w-md sm:max-w-xl"
                       data-anim="fade-up" data-anim-delay="260">
                        Te la diseñamos con tus fotos, música, ubicación y confirmación
                        de asistencia. Tú nos pasas los datos y recibes un enlace listo para compartir.
                    </p>

                    {{-- CTAs del hero --}}
                    <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 w-full"
                         data-anim="fade-up" data-anim-delay="360">
                        <a href="#paquetes"
                           class="btn-primary text-base w-full sm:w-auto px-7 py-4 sm:py-3.5">
                            Ver precios
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M5 12h14M13 5l7 7-7 7"/>
                            </svg>
                        </a>
                        <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener"
                           class="btn-secondary text-base w-full sm:w-auto px-7 py-4 sm:py-3.5">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.967-.94 1.164-.173.198-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.71.306 1.263.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.002-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z"/>
                            </svg>
                            Pedir cotización
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
                            <span class="text-[13px] sm:text-sm font-medium text-[#18111F] leading-tight whitespace-nowrap">Entrega rápida</span>
                        </li>
                        <li class="flex items-center gap-2.5 sm:gap-3 min-w-0 lg:px-6 lg:border-r lg:border-gray-200/70">
                            <span class="inline-flex items-center justify-center w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-[#F4EEFB] text-[#5A3087] shrink-0">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M4 12v3a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-3"/><path d="M16 6l-4-4-4 4"/><path d="M12 2v14"/>
                                </svg>
                            </span>
                            <span class="text-[13px] sm:text-sm font-medium text-[#18111F] leading-tight whitespace-nowrap">Lista para WhatsApp</span>
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
                            <p class="text-[13px] font-semibold text-[#2B143F] leading-snug">El evento se siente cerca desde el primer clic.</p>
                        </div>
                    </div>

                    {{-- Ubicación (abajo derecha) — antes estaba a la izquierda pero se
                         encimaba con la lista de beneficios del hero en viewports
                         entre 1024-1280px (laptops). Movida al lado derecho para
                         distribuir mejor las 3 tarjetas alrededor del celular. --}}
                    <div class="hidden lg:flex float-card anim-soft-pop anim-delay-700
                                bottom-[10%] -right-8 w-[220px]"
                         role="group" aria-label="Ubicación">
                        <span class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-[#FFF1E1] text-[#EB7512] shrink-0">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M12 22s7-7.5 7-13a7 7 0 1 0-14 0c0 5.5 7 13 7 13z"/><circle cx="12" cy="9" r="2.5"/>
                            </svg>
                        </span>
                        <div>
                            <p class="text-[11px] uppercase tracking-wider text-[#5F5A66] font-semibold">Ubicación</p>
                            <p class="text-[13px] font-semibold text-[#2B143F] leading-snug">Tus invitados llegan sin pedir la dirección.</p>
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
                            <p class="text-[13px] font-semibold text-[#2B143F] leading-snug">Recibe confirmaciones sin perseguir mensajes.</p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>

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
                <h2 class="text-3xl sm:text-4xl lg:text-5xl font-display font-extrabold leading-[1.1] text-[#2B143F]">
                    Todo lo importante del evento <span class="text-[#EB7512]">en un solo enlace</span>
                </h2>
                <p class="mt-4 text-[15px] sm:text-base lg:text-lg text-[#5F5A66]">
                    Tus invitados ven la información clara desde el celular y pueden confirmar, llegar al lugar o guardar los datos sin preguntarte.
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
                    <h3 class="font-display font-bold text-[17px] text-[#2B143F]">Música</h3>
                    <p class="mt-2 text-sm leading-relaxed text-[#5F5A66]">Agregamos la canción que quieres para darle ambiente al momento.</p>
                </article>

                {{-- 2. Cuenta regresiva --}}
                <article class="card-feature reveal" data-reveal-delay="80">
                    <span class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-[#F4EEFB] text-[#5A3087] mb-4">
                        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <circle cx="12" cy="13" r="8"/><path d="M12 9v4l2.5 2.5"/><path d="M9 2h6"/>
                        </svg>
                    </span>
                    <h3 class="font-display font-bold text-[17px] text-[#2B143F]">Cuenta regresiva</h3>
                    <p class="mt-2 text-sm leading-relaxed text-[#5F5A66]">Una cuenta clara para que todos tengan presente la fecha.</p>
                </article>

                {{-- 3. Ubicación con Maps --}}
                <article class="card-feature reveal" data-reveal-delay="160">
                    <span class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-[#FFF1E1] text-[#EB7512] mb-4">
                        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M12 22s7-7.5 7-13a7 7 0 1 0-14 0c0 5.5 7 13 7 13z"/><circle cx="12" cy="9" r="2.5"/>
                        </svg>
                    </span>
                    <h3 class="font-display font-bold text-[17px] text-[#2B143F]">Ubicación con Maps</h3>
                    <p class="mt-2 text-sm leading-relaxed text-[#5F5A66]">La dirección abre directo en Google Maps desde la invitación.</p>
                </article>

                {{-- 4. Dress code --}}
                <article class="card-feature reveal" data-reveal-delay="240">
                    <span class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-[#F4EEFB] text-[#5A3087] mb-4">
                        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M6 4l-2 7 4 1v8h12v-8l4-1-2-7-5 2-3-2-3 2-5-2z"/>
                        </svg>
                    </span>
                    <h3 class="font-display font-bold text-[17px] text-[#2B143F]">Dress code</h3>
                    <p class="mt-2 text-sm leading-relaxed text-[#5F5A66]">Indicas el estilo y tus invitados lo ven claro.</p>
                </article>

                {{-- 5. Mesa de regalos --}}
                <article class="card-feature reveal" data-reveal-delay="320">
                    <span class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-[#FFF1E1] text-[#EB7512] mb-4">
                        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <rect x="3" y="8" width="18" height="13" rx="1"/><path d="M3 12h18"/><path d="M12 8v13"/><path d="M12 8c-2-3-6-3-6 0h6zM12 8c2-3 6-3 6 0h-6z"/>
                        </svg>
                    </span>
                    <h3 class="font-display font-bold text-[17px] text-[#2B143F]">Mesa de regalos</h3>
                    <p class="mt-2 text-sm leading-relaxed text-[#5F5A66]">Enlaces o datos bancarios que ven solo los invitados.</p>
                </article>

                {{-- 6. Confirmación RSVP --}}
                <article class="card-feature reveal" data-reveal-delay="400">
                    <span class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-[#F4EEFB] text-[#5A3087] mb-4">
                        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M9 12l2 2 4-4"/><path d="M21 12c0 5-4 9-9 9a9 9 0 1 1 0-18c2.5 0 4.7 1 6.4 2.6"/>
                        </svg>
                    </span>
                    <h3 class="font-display font-bold text-[17px] text-[#2B143F]">RSVP</h3>
                    <p class="mt-2 text-sm leading-relaxed text-[#5F5A66]">Tus invitados confirman con un toque y tú ves la lista.</p>
                </article>

                {{-- 7. Galería --}}
                <article class="card-feature reveal" data-reveal-delay="480">
                    <span class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-[#FFF1E1] text-[#EB7512] mb-4">
                        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="9" cy="9" r="2"/><path d="M21 15l-5-5L5 21"/>
                        </svg>
                    </span>
                    <h3 class="font-display font-bold text-[17px] text-[#2B143F]">Galería de fotos</h3>
                    <p class="mt-2 text-sm leading-relaxed text-[#5F5A66]">Mostramos tus fotos sin saturar la invitación.</p>
                </article>

                {{-- 8. Compartir por WhatsApp --}}
                <article class="card-feature reveal" data-reveal-delay="560">
                    <span class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-[#F4EEFB] text-[#5A3087] mb-4">
                        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.967-.94 1.164-.173.198-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.71.306 1.263.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.002-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884"/>
                        </svg>
                    </span>
                    <h3 class="font-display font-bold text-[17px] text-[#2B143F]">Lista para compartir</h3>
                    <p class="mt-2 text-sm leading-relaxed text-[#5F5A66]">Recibes un enlace limpio para WhatsApp, estados e Instagram.</p>
                </article>

            </div>
        </div>
    </section>

    {{-- ===========================================================
         FRANJA DE SOCIAL PROOF (justo debajo del hero)
         — Editar los números en el array \$proofStats cuando
         — tengas datos reales que quieras mostrar.
         =========================================================== --}}
    @php
        // ⚠️ Reemplazar estos números con los reales cuando los tengas.
        $proofStats = [
            ['value' => '+30',  'label' => 'invitaciones entregadas'],
            ['value' => '24-48h', 'label' => 'entrega promedio'],
            ['value' => '100%', 'label' => 'personalizadas'],
            ['value' => 'WhatsApp', 'label' => 'listas para compartir'],
        ];
    @endphp
    <section class="relative bg-white border-y border-[#F1E6D9]" aria-label="Datos clave del servicio">
        <div class="mx-auto max-w-[1200px] lg:max-w-[1280px] px-5 sm:px-8 py-10 sm:py-14">
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-y-8 gap-x-4 text-center lg:text-left lg:divide-x lg:divide-[#F1E6D9]">
                @foreach ($proofStats as $stat)
                    <div class="px-2 lg:px-6 {{ $loop->first ? '' : '' }}">
                        <p class="font-display font-extrabold text-2xl sm:text-3xl text-[#EB7512] leading-tight">
                            {{ $stat['value'] }}
                        </p>
                        <p class="mt-2.5 text-[12px] sm:text-[13px] text-[#5F5A66] font-medium leading-snug">
                            {{ $stat['label'] }}
                        </p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ===========================================================
         SECCIÓN 2 — DISEÑOS / GALERÍA DE INVITACIONES REALES
         =========================================================== --}}
    <section id="disenos" class="section-padding relative overflow-hidden">
        <div class="absolute inset-0 -z-10 bg-gradient-to-b from-[#FFFDF8] via-[#F7F2FB]/45 to-[#FFFDF8]" aria-hidden="true"></div>

        <div class="mx-auto max-w-[1200px] lg:max-w-[1280px] px-5 sm:px-8">

            <header class="max-w-3xl mx-auto text-center mb-12 sm:mb-16 reveal">
                <p class="inline-flex items-center gap-2 text-[11px] sm:text-[12px] font-semibold tracking-[0.18em] uppercase text-[#EB7512] mb-4">
                    <span class="w-7 h-px bg-[#EB7512]"></span>
                    Ejemplo real
                    <span class="w-7 h-px bg-[#EB7512]"></span>
                </p>
                <h2 class="text-3xl sm:text-4xl lg:text-5xl font-display font-extrabold leading-[1.1] text-[#2B143F]">
                    Mira cómo <span class="text-[#EB7512]">se ve la invitación</span> que entregamos
                </h2>
                <p class="mt-4 text-[15px] sm:text-base lg:text-lg text-[#5F5A66]">
                    Este es un proyecto real que ya está en manos de nuestra clienta. Toca la imagen para abrir la invitación en vivo.
                </p>
            </header>

            {{-- ⚠️ Galería temporal: solo 1 demo mientras se entregan más invitaciones
                 en la semana. Cuando tengas 2-3 más, vuelve a esta sección, reemplaza
                 el grid de abajo con 2-3 cards (mismo patrón que card-gallery) y
                 restaura el header a "Mira cómo se ven las invitaciones que entregamos". --}}
            <div class="grid grid-cols-1 gap-5 lg:gap-7">

                {{-- Card único: XV años Mariana — caso real destacado --}}
                <a href="/invitacion/xv-mariana" target="_blank" rel="noopener"
                   class="card-gallery block group relative
                          rounded-2xl overflow-hidden bg-white
                          shadow-[0_18px_40px_-22px_rgba(43,20,63,.35)]
                          hover:shadow-[0_28px_50px_-22px_rgba(43,20,63,.45)]
                          hover:-translate-y-1
                          transition-all duration-300">
                    <div class="relative aspect-[4/5] sm:aspect-[16/10] lg:aspect-[21/9] overflow-hidden">
                        <picture>
                            <source srcset="{{ asset('images/gallery/valeria-hero-medium.webp') }}" type="image/webp">
                            <img src="{{ asset('images/gallery/valeria-hero-medium.jpg') }}"
                                 alt="Invitación digital de XV años — Mariana"
                                 class="absolute inset-0 w-full h-full object-cover object-top
                                        transition-transform duration-700 group-hover:scale-[1.03]"
                                 loading="lazy" decoding="async" width="800" height="1200">
                        </picture>
                        {{-- Overlay gradient --}}
                        <div class="absolute inset-0 bg-gradient-to-t from-[#2B143F]/85 via-[#2B143F]/20 to-transparent"
                             aria-hidden="true"></div>
                        {{-- Tag superior izquierda --}}
                        <span class="absolute top-4 left-4 sm:top-6 sm:left-6 inline-flex items-center gap-1.5
                                     rounded-full bg-white/95 backdrop-blur-sm
                                     text-[11px] sm:text-xs font-bold uppercase tracking-wider text-[#5A3087]
                                     px-3 py-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-[#5A3087]"></span>
                            XV años · Web Premium
                        </span>
                        {{-- Badge "Caso real" arriba derecha --}}
                        <span class="hidden sm:inline-flex absolute top-4 right-4 sm:top-6 sm:right-6 items-center gap-1.5
                                     rounded-full bg-[#EB7512] backdrop-blur-sm
                                     text-[11px] sm:text-xs font-bold uppercase tracking-wider text-white
                                     px-3 py-1.5">
                            <svg class="w-3 h-3" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                <path d="M12 2l3 6 6 1-4.5 4.5L18 21l-6-3-6 3 1.5-6.5L3 9l6-1z"/>
                            </svg>
                            Caso real
                        </span>
                        {{-- Texto inferior --}}
                        <div class="absolute bottom-0 left-0 right-0 p-5 sm:p-8 lg:p-10 text-white">
                            <p class="text-[11px] sm:text-xs font-semibold uppercase tracking-[0.18em] text-[#F09719] mb-2">
                                Proyecto entregado
                            </p>
                            <h3 class="font-display font-extrabold text-2xl sm:text-3xl lg:text-4xl leading-tight">
                                XV años Mariana
                            </h3>
                            <p class="mt-2 text-sm sm:text-base lg:text-lg text-white/85 max-w-xl">
                                Música, galería de fotos, mapa, dress code y confirmación de asistencia en un solo enlace.
                            </p>
                            <span class="mt-5 inline-flex items-center gap-1.5 text-sm sm:text-base font-semibold
                                         text-white border-b border-white/40 pb-0.5
                                         group-hover:border-white transition-colors">
                                Ver invitación en vivo
                                <svg class="w-4 h-4 transition-transform group-hover:translate-x-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M7 17L17 7M7 7h10v10"/>
                                </svg>
                            </span>
                        </div>
                    </div>
                </a>

            </div>

            {{-- Nota sobre otros formatos --}}
            <div class="mt-10 sm:mt-12 reveal text-center">
                <p class="text-[14px] sm:text-[15px] text-[#5F5A66]">
                    También hacemos invitaciones en
                    <span class="font-semibold text-[#2B143F]">imagen</span> y
                    <span class="font-semibold text-[#2B143F]">video</span> para estados y redes.
                    <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener"
                       class="font-semibold text-[#EB7512] hover:text-[#F45A00] transition-colors">
                        Pide ejemplos por WhatsApp →
                    </a>
                </p>
            </div>
        </div>
    </section>

    {{-- ===========================================================
         SECCIÓN 2B — TESTIMONIOS
         ⚠️ Reemplazar los textos del array $testimonials con
         ---   mensajes reales de tus clientes. Ideal: nombre,
         ---   evento, ciudad y un fragmento corto y específico.
         =========================================================== --}}
    @php
        $testimonials = [
            [
                'name'    => 'Lucía Hernández',
                'event'   => 'XV años · Mérida',
                'quote'   => 'Mis invitadas quedaron encantadas. La cuenta regresiva y la música le dieron un toque que nunca había visto en una invitación.',
                'rating'  => 5,
            ],
            [
                'name'    => 'Carlos y Daniela',
                'event'   => 'Boda · CDMX',
                'quote'   => 'Nos resolvió toda la logística: los invitados confirmaron por la página y el día del evento no tuvimos que perseguir a nadie. Súper práctico.',
                'rating'  => 5,
            ],
            [
                'name'    => 'Ana Gabriela Ruiz',
                'event'   => 'Bautizo · Guadalajara',
                'quote'   => 'La invitación quedó preciosa y en menos de 24 horas ya la tenía lista para mandar. El proceso fue muy sencillo y rápido.',
                'rating'  => 5,
            ],
        ];
    @endphp
    <section class="section-padding bg-white relative">
        <div class="mx-auto max-w-[1200px] lg:max-w-[1280px] px-5 sm:px-8">

            <header class="max-w-3xl mx-auto text-center mb-12 sm:mb-14 reveal">
                <p class="inline-flex items-center gap-2 text-[11px] sm:text-[12px] font-semibold tracking-[0.18em] uppercase text-[#EB7512] mb-4">
                    <span class="w-7 h-px bg-[#EB7512]"></span>
                    Lo que dicen nuestros clientes
                    <span class="w-7 h-px bg-[#EB7512]"></span>
                </p>
                <h2 class="text-3xl sm:text-4xl lg:text-5xl font-display font-extrabold leading-[1.1] text-[#2B143F]">
                    Eventos reales, <span class="text-[#EB7512]">gente real</span>
                </h2>
            </header>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 lg:gap-6">
                @foreach ($testimonials as $i => $t)
                    <figure class="reveal h-full flex flex-col rounded-2xl bg-[#FFFDF8]
                                   border border-[#F1E6D9] p-6 sm:p-7
                                   shadow-[0_10px_30px_-20px_rgba(43,20,63,.25)]
                                   hover:shadow-[0_18px_36px_-20px_rgba(43,20,63,.35)]
                                   transition-shadow duration-300"
                            data-reveal-delay="{{ $i * 100 }}">

                        {{-- Estrellas --}}
                        <div class="flex items-center gap-0.5 text-[#EB7512] mb-4" aria-label="Calificación {{ $t['rating'] }} de 5">
                            @for ($s = 0; $s < $t['rating']; $s++)
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                    <path d="M12 2l3 6 6 1-4.5 4.5L18 21l-6-3-6 3 1.5-6.5L3 9l6-1z"/>
                                </svg>
                            @endfor
                        </div>

                        {{-- Quote --}}
                        <blockquote class="flex-1">
                            <svg class="w-7 h-7 text-[#FFF1E1] mb-2" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                <path d="M7 7h3l-2 5h2v5H4v-5l3-5zm10 0h3l-2 5h2v5h-6v-5l3-5z"/>
                            </svg>
                            <p class="text-[15px] leading-relaxed text-[#18111F]">
                                “{{ $t['quote'] }}”
                            </p>
                        </blockquote>

                        {{-- Autor --}}
                        <figcaption class="mt-5 pt-5 border-t border-[#F1E6D9] flex items-center gap-3">
                            <span class="inline-flex items-center justify-center w-11 h-11 rounded-full
                                         bg-gradient-to-br from-[#EB7512] to-[#5A3087]
                                         text-white font-display font-bold text-sm shrink-0">
                                {{ mb_substr($t['name'], 0, 1) }}{{ mb_substr(explode(' ', $t['name'])[1] ?? '', 0, 1) }}
                            </span>
                            <div>
                                <p class="font-semibold text-[14px] text-[#2B143F] leading-tight">{{ $t['name'] }}</p>
                                <p class="text-[12px] text-[#5F5A66] mt-0.5">{{ $t['event'] }}</p>
                            </div>
                        </figcaption>
                    </figure>
                @endforeach
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
                <h2 class="text-3xl sm:text-4xl lg:text-5xl font-display font-extrabold leading-[1.1] text-[#2B143F]">
                    De idea a invitación lista <span class="text-[#EB7512]">sin complicarte</span>
                </h2>
                <p class="mt-4 text-[15px] sm:text-base lg:text-lg text-[#5F5A66]">
                    El proceso es simple: eliges formato, nos mandas la información, revisas el diseño y compartes el enlace.
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
                    <h3 class="font-display font-bold text-[17px] text-[#2B143F]">Elige el formato</h3>
                    <p class="mt-2 text-sm leading-relaxed text-[#5F5A66] max-w-xs">Web, imagen o video. El que mejor le quede a tu evento.</p>
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
                    <h3 class="font-display font-bold text-[17px] text-[#2B143F]">Envíanos los datos</h3>
                    <p class="mt-2 text-sm leading-relaxed text-[#5F5A66] max-w-xs">Nombres, fecha, lugar, fotos, música, dress code y mensajes.</p>
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
                    <h3 class="font-display font-bold text-[17px] text-[#2B143F]">Te la armamos</h3>
                    <p class="mt-2 text-sm leading-relaxed text-[#5F5A66] max-w-xs">Diseñamos, revisas y nos dices qué ajustar (al menos un cambio va incluido).</p>
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
                    <h3 class="font-display font-bold text-[17px] text-[#2B143F]">Envíala por WhatsApp</h3>
                    <p class="mt-2 text-sm leading-relaxed text-[#5F5A66] max-w-xs">Recibes el enlace listo y lo compartes con tus invitados.</p>
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
                <h2 class="text-3xl sm:text-4xl lg:text-5xl font-display font-extrabold leading-[1.1] text-[#2B143F]">
                    Elige el formato que mejor va con <span class="text-[#EB7512]">tu evento</span>
                </h2>
                <p class="mt-4 text-[15px] sm:text-base lg:text-lg text-[#5F5A66]">
                    Tenemos opciones en imagen, video y web. Si no sabes cuál conviene, te orientamos por WhatsApp según tu evento.
                </p>
            </header>

            @php
                $packageFormats = [
                    'web' => [
                        'label' => 'Web',
                                'description' => 'La opción más completa: enlace, música, ubicación, fotos y detalles del evento.',
                        'packages' => [
                            [
                                'name' => 'Web Esencial',
                                'description' => 'Para eventos sencillos que necesitan verse bien y compartir todos los datos básicos.',
                                'price' => '$600',
                                'badge' => 'Básica',
                                'featured' => false,
                                'items' => [
                                    'Invitación web adaptable a celular',
                                    'Fecha, hora y datos del evento',
                                    'Música de fondo',
                                    'Cuenta regresiva',
                                    'Mesa de regalos',
                                    'Galería de fotos hasta 3 imágenes',
                                    'Botón de WhatsApp',
                                ],
                            ],
                            [
                                'name' => 'Web Plus',
                                'description' => 'Para quienes quieren una invitación más completa sin llegar al paquete premium.',
                                'price' => '$900',
                                'badge' => 'Más elegida',
                                'featured' => true,
                                'items' => [
                                    'Todo lo de Web Esencial',
                                    'Ubicación con Google Maps',
                                    'Galería de fotos hasta 5 imágenes',
                                    'Itinerario del evento',
                                    'Botón para agregar al calendario',
                                    'Diseño con más detalles visuales',
                                ],
                            ],
                            [
                                'name' => 'Web Premium',
                                'description' => 'Para bodas, XV años y eventos formales donde necesitas confirmaciones y más secciones.',
                                'price' => '$1,300',
                                'badge' => 'Completa',
                                'featured' => false,
                                'items' => [
                                    'Todo lo de Web Plus',
                                    'Galería de fotos hasta 10 imágenes',
                                    'Confirmación de asistencia RSVP',
                                    'Lista básica de invitados confirmados',
                                    'Sección de padres, padrinos o familia',
                                    'Recomendaciones para invitados',
                                    'Diseño más elegante y trabajado',
                                ],
                            ],
                        ],
                    ],
                    'imagen' => [
                        'label' => 'Imagen',
                                'description' => 'Ideal para fiestas rápidas, estados de WhatsApp o invitaciones sencillas.',
                        'packages' => [
                            [
                                'name' => 'Imagen Básica',
                                'description' => 'Para fiestas, cumpleaños o reuniones que necesitan una invitación visual rápida.',
                                'price' => '$150',
                                'badge' => 'Rápida',
                                'featured' => false,
                                'items' => [
                                    'Diseño estático personalizado',
                                    'Formato vertical para WhatsApp',
                                    'Nombres, fecha, hora y lugar',
                                    'Dress code o nota especial',
                                    'Entrega en PNG/JPG',
                                ],
                            ],
                            [
                                'name' => 'Imagen Premium',
                                'description' => 'Para fiestas con temática, baby shower, bautizos o celebraciones que necesitan verse más cuidadas.',
                                'price' => '$250',
                                'badge' => 'Más elegida',
                                'featured' => true,
                                'items' => [
                                    'Todo lo de Imagen Básica',
                                    'Diseño con más detalle visual',
                                    'Versión para historia o estado',
                                    'Versión cuadrada para publicación',
                                    'Un ajuste posterior incluido',
                                ],
                            ],
                        ],
                    ],
                    'video' => [
                        'label' => 'Video',
                                'description' => 'Una versión animada para compartir en estados, historias o por mensaje.',
                        'packages' => [
                            [
                                'name' => 'Video Básico',
                                'description' => 'Para presentar tu evento con una versión animada sencilla.',
                                'price' => '$300',
                                'badge' => 'Animado',
                                'featured' => false,
                                'items' => [
                                    'Video vertical animado',
                                    'Música de fondo',
                                    'Texto con datos principales',
                                    'Fotos o elementos visuales del evento',
                                    'Formato MP4 para redes',
                                ],
                            ],
                            [
                                'name' => 'Video Premium',
                                'description' => 'Para un video simple con más escenas y mejor ritmo visual.',
                                'price' => '$450',
                                'badge' => 'Más elegido',
                                'featured' => true,
                                'items' => [
                                    'Todo lo de Video Básico',
                                    'Animaciones más elaboradas',
                                    'Más escenas o momentos',
                                    'Diseño según temática',
                                    'Un ajuste posterior incluido',
                                ],
                            ],
                        ],
                    ],
                ];
            @endphp

            <div class="reveal" data-pricing-tabs>
                <div class="mx-auto mb-10 flex w-full max-w-xl rounded-lg border border-[#F1E6D9] bg-white p-1.5 shadow-[0_16px_34px_-28px_rgba(43,20,63,.45)]" role="tablist" aria-label="Formatos de invitación">
                    @foreach ($packageFormats as $formatKey => $format)
                        <button
                            type="button"
                            id="pricing-tab-{{ $formatKey }}"
                            class="pricing-tab flex-1 rounded-xl px-3 py-3 text-sm font-bold transition-all duration-200 {{ $loop->first ? 'bg-[#EB7512] text-white shadow-md shadow-orange-500/25' : 'text-[#5F5A66] hover:bg-[#FFF1E1] hover:text-[#EB7512]' }}"
                            role="tab"
                            aria-selected="{{ $loop->first ? 'true' : 'false' }}"
                            aria-controls="pricing-panel-{{ $formatKey }}"
                            data-pricing-tab="{{ $formatKey }}"
                        >
                            {{ $format['label'] }}
                        </button>
                    @endforeach
                </div>

                @foreach ($packageFormats as $formatKey => $format)
                    <div
                        id="pricing-panel-{{ $formatKey }}"
                        class="pricing-panel {{ $loop->first ? '' : 'hidden' }}"
                        role="tabpanel"
                        aria-labelledby="pricing-tab-{{ $formatKey }}"
                        data-pricing-panel="{{ $formatKey }}"
                    >
                        <p class="mb-7 text-center text-sm sm:text-base text-[#5F5A66]">{{ $format['description'] }}</p>

                        <div class="grid grid-cols-1 {{ count($format['packages']) === 3 ? 'lg:grid-cols-3' : 'md:grid-cols-2 max-w-4xl mx-auto' }} gap-5 lg:gap-6 items-stretch">
                            @foreach ($format['packages'] as $package)
                                <article class="{{ $package['featured'] ? 'pricing-card-featured' : 'pricing-card' }} reveal {{ $package['featured'] ? 'anim-pulse' : '' }}" data-reveal-delay="{{ $loop->index * 120 }}">
                                    @if ($package['featured'])
                                        <span class="absolute -top-3 left-1/2 -translate-x-1/2 inline-flex items-center gap-1.5 rounded-full bg-[#EB7512] text-white text-[11px] font-bold uppercase tracking-wider px-3.5 py-1.5 shadow-lg shadow-orange-500/40">
                                            <svg class="w-3 h-3" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                                <path d="M12 2l3 6 6 1-4.5 4.5L18 21l-6-3-6 3 1.5-6.5L3 9l6-1z"/>
                                            </svg>
                                            {{ $package['badge'] }}
                                        </span>
                                    @else
                                        <span class="inline-flex self-start rounded-full bg-[#FFF1E1] px-3 py-1 text-[11px] font-bold uppercase tracking-wider text-[#EB7512] mb-5">{{ $package['badge'] }}</span>
                                    @endif

                                    <header class="mb-6 {{ $package['featured'] ? 'mt-2' : '' }}">
                                        <h3 class="font-display font-bold text-lg text-[#2B143F]">{{ $package['name'] }}</h3>
                                        <p class="mt-2 text-[13px] leading-relaxed text-[#5F5A66]">{{ $package['description'] }}</p>
                                    </header>
                                    <div class="mb-5">
                                        <p class="font-display font-extrabold text-4xl text-[#2B143F] leading-none">{{ $package['price'] }}<span class="text-base font-semibold text-[#5F5A66] ml-1">MXN</span></p>
                                    </div>
                                    <ul class="space-y-2.5 text-sm text-[#18111F] mb-7 flex-1">
                                        @foreach ($package['items'] as $item)
                                            <li class="flex gap-2">
                                                <span class="text-[#EB7512] mt-0.5">✓</span>
                                                <span class="{{ str_starts_with($item, 'Todo lo') ? 'font-semibold' : '' }}">{{ $item }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                    <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener" class="inline-flex w-full items-center justify-center gap-2 rounded-lg {{ $package['featured'] ? 'bg-[#EB7512] hover:bg-[#F45A00] text-white shadow-lg shadow-orange-500/30 hover:-translate-y-0.5' : 'border border-[#EB7512] text-[#EB7512] hover:bg-[#EB7512] hover:text-white' }} font-semibold py-3 transition-all">
                                        Cotizar este paquete
                                    </a>
                                </article>
                            @endforeach
                        </div>
                    </div>
                @endforeach
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
                <h2 class="text-3xl sm:text-4xl lg:text-5xl font-display font-extrabold leading-[1.1] text-[#2B143F]">
                    Preguntas <span class="text-[#EB7512]">frecuentes</span>
                </h2>
                <p class="mt-4 text-[15px] sm:text-base lg:text-lg text-[#5F5A66]">
                    Resolvemos lo básico para que puedas decidir sin darle tantas vueltas.
                </p>
            </header>

            <div class="space-y-3">

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
        <div class="absolute inset-0 -z-10 bg-[#2B143F]" aria-hidden="true"></div>
        <div class="absolute inset-x-0 top-0 -z-10 h-px bg-white/10" aria-hidden="true"></div>

        <div class="section-padding mx-auto max-w-3xl px-5 sm:px-8 text-center text-white relative">

            <p class="inline-flex items-center gap-2 text-[11px] sm:text-[12px] font-semibold tracking-[0.18em] uppercase text-[#F09719] mb-5 reveal">
                <span class="w-7 h-px bg-[#F09719]"></span>
                Cotiza sin compromiso
                <span class="w-7 h-px bg-[#F09719]"></span>
            </p>

            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-display font-extrabold leading-[1.1] reveal">
                Mándanos los datos de tu evento <span class="text-[#F09719]">y te cotizamos hoy</span>
            </h2>

            <p class="mt-5 text-[15px] sm:text-base lg:text-lg text-white/85 reveal max-w-2xl mx-auto">
                Cuéntanos qué celebras, la fecha y el formato que te interesa. Te decimos qué paquete conviene y qué necesitamos para empezar.
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

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-10 lg:gap-8">

                {{-- Logo + tagline --}}
                <div>
                    <a href="/" class="inline-block" aria-label="Invitatorio — ir al inicio">
                        <img src="{{ asset('images/invitatorio_horizontal.png') }}"
                             alt="Invitatorio"
                             class="h-9 w-auto block brightness-0 invert"
                             loading="lazy" decoding="async">
                    </a>
                    <p class="mt-4 text-sm leading-relaxed">
                        Diseño, música y datos de tu evento en un solo enlace.
                    </p>
                </div>

                {{-- Menú --}}
                <nav aria-label="Menú del footer">
                    <h3 class="font-display font-bold text-white text-sm uppercase tracking-wider mb-4">Menú</h3>
                    <ul class="space-y-2.5 text-sm">
                        <li><a href="#" class="hover:text-[#EB7512] transition-colors">Inicio</a></li>
                        <li><a href="#incluye" class="hover:text-[#EB7512] transition-colors">Qué incluye</a></li>
                        <li><a href="#disenos" class="hover:text-[#EB7512] transition-colors">Diseños</a></li>
                        <li><a href="#paquetes" class="hover:text-[#EB7512] transition-colors">Paquetes</a></li>
                        <li><a href="#como-funciona" class="hover:text-[#EB7512] transition-colors">Cómo funciona</a></li>
                        <li><a href="#preguntas" class="hover:text-[#EB7512] transition-colors">Preguntas frecuentes</a></li>
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
                        <li>
                            <a href="/politicas-de-privacidad" class="inline-flex items-center gap-2 hover:text-[#EB7512] transition-colors">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M12 2l8 4v6c0 5-3.5 9.5-8 10-4.5-.5-8-5-8-10V6l8-4z"/>
                                </svg>
                                Políticas de privacidad
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <hr class="border-white/10 my-10">

            <div class="flex flex-col items-center justify-center gap-2 text-center text-xs text-gray-500 sm:flex-row sm:gap-3">
                <p>© {{ date('Y') }} Invitatorio · México · Hecho con cariño para eventos reales.</p>
                <span class="hidden h-3 w-px bg-white/15 sm:block" aria-hidden="true"></span>
                <p>
                    Sitio realizado por
                    <a href="{{ $xpertSystemsUrl }}" target="_blank" rel="noopener" class="font-semibold text-gray-300 hover:text-[#EB7512] transition-colors">
                        XpertSystems
                    </a>
                </p>
            </div>
        </div>
    </footer>

</body>
</html>
