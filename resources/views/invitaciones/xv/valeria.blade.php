{{--
    Invitación digital — Mis XV Años de Valeria
    Single-file, self-contained, no Tailwind dependency.
    All styling lives in the inline <style>; all logic in inline <script>.
    Editable variables: $nombre, $fechaLarga, $horaRecepcion, $horaCeremonia,
    $lugar, $direccion, $mapsUrl, $mapsEmbed, $whatsappNumber, $enlaceCorto.
--}}

@php
    // —— Datos editables ————————————————————————————————————————
    $nombre          = 'Valentina';
    $nombreCompleto  = 'Valentina Franco García';
    $evento          = 'Mis XV Años';
    $fechaCorta      = '02 · Agosto · 2026';
    $fechaLarga      = 'Domingo 02 de agosto de 2026';
    $horaRecepcion   = '10:00 PM';
    $horaCeremonia   = '8:30 PM';
    $lugar           = 'Fiesta El Pedregal';
    $direccion       = 'Fiesta El Pedregal, Hunucmá, Yucatán';
    $mapsUrl         = 'https://www.google.com/maps?q=21.035107,-89.869308';
    $mapsEmbed       = 'https://maps.google.com/maps?hl=es&q=21.035107,-89.869308&z=17&output=embed';

    // Datos de la iglesia (ceremonia religiosa) — ajusta con los reales
    $iglesiaNombre      = 'Capilla de Nuestra Señora de Guadalupe';
    $iglesiaDireccion   = 'Capilla de Nuestra Señora de Guadalupe, Mérida, Yucatán';
    $iglesiaMapsUrl     = 'https://www.google.com/maps/place/Capilla+de+Nuestra+Se%C3%B1ora+de+Guadalupe/@21.0050406,-89.8819956,19z/data=!4m6!3m5!1s0x8f5607f86cf6c17b:0xc640e10929ff792e!8m2!3d21.0052171!4d-89.8807725!16s%2Fg%2F11n6t3b2yj?entry=tts&g_ep=EgoyMDI2MDcxMi4wIPu8ASoASAFQAw%3D%3D&skid=c0c1509c-b6ac-4c25-9dd5-1d1b48d0ef80';
    $iglesiaMapsEmbed   = 'https://www.google.com/maps?q=21.0052171,-89.8807725&output=embed';
    $whatsappNumber  = '529991234567';
    $whatsappText    = 'Hola%2C%20confirmo%20mi%20asistencia%20a%20los%20XV%20de%20' . urlencode($nombre);
    $waLink          = 'https://wa.me/' . $whatsappNumber . '?text=' . $whatsappText;
    
    $dressCode       = 'Formal · Elegante';
    $dressTexto      = 'Te sugerimos vestir en tonos neutros o elegantes para acompañar la armonía del evento.';
    $mesaTexto       = 'Tu presencia es nuestro mejor regalo, pero si deseas tener un detalle, puedes hacerlo aquí.';
    $lluviaCuerpo    = 'Tu presencia es el regalo más importante, pero si deseas tener un detalle conmigo, puedes hacerlo en un sobre el día del evento.';
    $lluviaCierre    = 'Con cariño, gracias por acompañarme.';
    $bienvenidaTxt   = 'Con mucha alegría queremos compartir contigo este día tan especial.';
    $familia         = 'Con cariño, familia de ' . $nombre;

    // Mensaje emocional entre el hero y la sección de evento
    $mensajeKicker   = 'Un sueño especial';
    $mensajeTitulo   = 'Hay momentos que se sueñan desde niña y hoy comienzan a hacerse realidad.';
    $mensajeCuerpo   = 'Con mucha ilusión, ' . $nombreCompleto . ' quiere compartir contigo una noche llena de alegría, recuerdos y momentos inolvidables.';

    // Galería / carrusel — coloca aquí las imágenes que quieras mostrar
    $galeriaImagenes = [
        'images/xv/valeria/slider/valeria-recuerdo-01.jpeg',
        'images/xv/valeria/slider/valeria-recuerdo-02.jpeg',
        'images/xv/valeria/slider/valeria-recuerdo-03.jpeg',
        'images/xv/valeria/slider/valeria-recuerdo-04.jpeg',
        'images/xv/valeria/slider/valeria-recuerdo-05.jpeg',
        'images/xv/valeria/slider/valeria-recuerdo-06.jpeg',
        'images/xv/valeria/slider/valeria-recuerdo-07.jpeg',
        'images/xv/valeria/slider/valeria-recuerdo-08.jpeg',
    ];
    $galeriaTitulo    = 'Galería de recuerdos';
    $galeriaSubtitulo = 'Pequeños momentos que forman parte de esta historia tan especial.';

    // Fecha objetivo del countdown (hora local Mérida, UTC-6 → 20:30 = 8:30 PM)
    $eventDateIso   = '2026-08-02T20:30:00-06:00';
    $pageUrl        = url('/invitacion/xv-valentina');
    $seoTitle       = $evento . ' de ' . $nombre . ' | Invitación digital';
    $seoDescription = 'Invitación digital para ' . $evento . ' de ' . $nombreCompleto . '. ' . $fechaLarga . ' con ceremonia, recepción, ubicación, música y confirmación de asistencia.';
    $seoImage       = asset('images/xv/valeria/foto-intro.jpeg');
    $eventSchema    = [
        '@context' => 'https://schema.org',
        '@type' => 'Event',
        'name' => $evento . ' de ' . $nombre,
        'description' => $seoDescription,
        'startDate' => $eventDateIso,
        'eventAttendanceMode' => 'https://schema.org/OfflineEventAttendanceMode',
        'eventStatus' => 'https://schema.org/EventScheduled',
        'image' => [$seoImage],
        'url' => $pageUrl,
        'location' => [
            '@type' => 'Place',
            'name' => $lugar,
            'address' => [
                '@type' => 'PostalAddress',
                'streetAddress' => $direccion,
                'addressLocality' => 'Mérida',
                'addressRegion' => 'Yucatán',
                'addressCountry' => 'MX',
            ],
        ],
        'organizer' => [
            '@type' => 'Person',
            'name' => $familia,
        ],
    ];
@endphp

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="theme-color" content="#f8d8d4">
    <meta name="description" content="{{ $seoDescription }}">
    <meta name="robots" content="index, follow, max-image-preview:large">
    <link rel="canonical" href="{{ $pageUrl }}">
    <meta property="og:title" content="{{ $seoTitle }}">
    <meta property="og:description" content="{{ $seoDescription }}">
    <meta property="og:image" content="{{ $seoImage }}">
    <meta property="og:image:alt" content="Portada de la invitación digital de {{ $nombre }}">
    <meta property="og:url" content="{{ $pageUrl }}">
    <meta property="og:site_name" content="Invitatorio">
    <meta property="og:locale" content="es_MX">
    <meta property="og:type" content="website">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $seoTitle }}">
    <meta name="twitter:description" content="{{ $seoDescription }}">
    <meta name="twitter:image" content="{{ $seoImage }}">

    <title>{{ $seoTitle }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;0,700;1,500;1,600&family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link rel="preload" as="image" href="{{ asset('images/xv/valeria/foto-intro.jpeg') }}">

    <script type="application/ld+json">
        @json($eventSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT)
    </script>

    <style>
        /* ============================================================
           PALETA + TOKENS
           ============================================================ */
        :root {
            --bg: #f8d8d4;
            --bg-soft: #fff4f1;
            --card: #fffaf8;
            --rose: #d77c78;
            --rose-soft: #e8aaa6;
            --rose-dark: #c96965;
            --text: #3f4648;
            --muted: #8b8f91;
            --border: rgba(217, 124, 120, 0.35);
            --shadow-card: 0 24px 70px rgba(165, 105, 100, 0.14);
            --shadow-cover: 0 30px 60px -30px rgba(201, 105, 101, .35),
                            0 12px 28px -18px rgba(217, 124, 120, .25);
            --shadow-btn:  0 10px 24px -10px rgba(201, 105, 101, .45);
            --shadow-btn-hover: 0 14px 30px rgba(201, 105, 101, .28);
            --radius-card: 32px;
            --radius-btn: 14px;
        }

        /* ============================================================
           BASE
           ============================================================ */
        * { box-sizing: border-box; }
        html {
            scroll-behavior: smooth;
            -webkit-text-size-adjust: 100%;
        }
        html, body { margin: 0; padding: 0; }
        body {
            font-family: 'Montserrat', sans-serif;
            color: var(--text);
            background:
                radial-gradient(circle at top left, #fff1ee 0%, transparent 38%),
                radial-gradient(circle at bottom right, #f3bdb8 0%, transparent 42%),
                linear-gradient(135deg, #fff8f6 0%, #f8d8d4 100%);
            background-attachment: fixed;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            min-height: 100vh;
            overflow: hidden;
        }
        body.invitation-opened { overflow: auto; }

        h1, h2, h3, h4 {
            font-family: 'Cormorant Garamond', serif;
            font-weight: 500;
            letter-spacing: -0.01em;
            margin: 0;
            color: var(--text);
        }
        p { margin: 0; }
        a { color: inherit; text-decoration: none; }
        img { display: block; max-width: 100%; }
        .hidden { display: none !important; }

        /* ============================================================
           0) PORTADA
           ============================================================ */
        .intro-section {
            min-height: 100vh;
            min-height: 100svh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px 16px;
            transition: opacity .6s ease, transform .6s ease;
        }
        .intro-section.intro-hide {
            opacity: 0;
            pointer-events: none;
            transform: translateY(-12px);
        }
        .intro-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 24px;
            box-shadow: var(--shadow-cover);
            max-width: 1100px;
            width: 100%;
            overflow: hidden;
            display: grid;
            grid-template-columns: 1fr;
        }
        @media (min-width: 1024px) {
            .intro-card { grid-template-columns: 315px 1fr; }
        }
        .intro-image-wrap {
            width: 100%;
            height: 380px;
            overflow: hidden;
            background: #fffaf8;
            position: relative;
        }
        @media (min-width: 640px)  { .intro-image-wrap { height: 440px; } }
        @media (min-width: 1024px) {
            .intro-image-wrap {
                height: 560px;
                width: 315px;
            }
        }
        .intro-image {
            position: relative;
            z-index: 1;
            width: 100%;
            height: 100%;
            object-fit: contain;
            object-position: center center;
        }
        .intro-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 36px 24px 28px;
            text-align: center;
        }
        @media (min-width: 640px)  { .intro-content { padding: 48px 40px; } }
        @media (min-width: 1024px) { .intro-content { padding: 56px; } }

        .intro-eyebrow {
            font-family: 'Montserrat', sans-serif;
            font-size: 11px;
            font-weight: 500;
            letter-spacing: 0.32em;
            text-transform: uppercase;
            color: var(--rose);
            display: inline-flex;
            align-items: center;
            gap: 14px;
        }
        .intro-eyebrow::before,
        .intro-eyebrow::after {
            content: '';
            display: block;
            width: 36px;
            height: 1px;
            background: var(--rose);
            opacity: 0.85;
        }
        .intro-title {
            font-family: 'Cormorant Garamond', serif;
            font-weight: 500;
            font-size: 44px;
            line-height: 1.02;
            color: var(--text);
            margin: 20px 0 0;
        }
        @media (min-width: 640px)  { .intro-title { font-size: 52px; } }
        @media (min-width: 1024px) { .intro-title { font-size: 60px; } }
        .intro-divider {
            width: 84px;
            height: 1px;
            background: var(--border);
            margin: 20px auto 0;
        }
        .intro-name {
            font-family: 'Cormorant Garamond', serif;
            font-style: italic;
            font-weight: 500;
            font-size: 40px;
            line-height: 1;
            color: var(--rose);
            margin: 14px 0 0;
        }
        @media (min-width: 640px)  { .intro-name { font-size: 48px; } }
        @media (min-width: 1024px) { .intro-name { font-size: 56px; } }
        .intro-date {
            font-family: 'Montserrat', sans-serif;
            font-size: 12px;
            font-weight: 500;
            letter-spacing: 0.22em;
            text-transform: uppercase;
            color: var(--text);
            margin: 24px 0 0;
        }
        .intro-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #d99591, #c97f7b);
            color: #ffffff;
            font-family: 'Montserrat', sans-serif;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 0.16em;
            text-transform: uppercase;
            padding: 14px 38px;
            border: 0;
            border-radius: var(--radius-btn);
            box-shadow: var(--shadow-btn);
            cursor: pointer;
            transition: transform .3s ease, box-shadow .3s ease, filter .3s ease;
            margin-top: 30px;
        }
        .intro-button:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-btn-hover);
            filter: brightness(1.03);
        }
        .intro-button:active { transform: translateY(0); }

        .intro-hint {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            margin-top: 28px;
        }
        .intro-arrow {
            color: var(--rose);
            animation: introBounce 1.8s ease-in-out infinite;
        }
        .intro-cta-hint {
            font-family: 'Montserrat', sans-serif;
            font-size: 10px;
            font-weight: 500;
            letter-spacing: 0.22em;
            text-transform: uppercase;
            color: var(--muted);
        }
        @keyframes introBounce {
            0%, 100% { transform: translateY(0); }
            50%      { transform: translateY(6px); }
        }

        /* ============================================================
           SECCIONES DEL CONTENIDO
           ============================================================ */
        .invite-section {
            width: min(1100px, calc(100% - 32px));
            margin: 0 auto;
            padding: 48px 0;
        }
        @media (min-width: 768px) {
            .invite-section { padding: 64px 0; }
        }
        .section-card {
            background: rgba(255, 250, 248, 0.88);
            border: 1px solid var(--border);
            border-radius: var(--radius-card);
            padding: clamp(28px, 5vw, 56px);
            box-shadow: var(--shadow-card);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }
        .eyebrow {
            font-family: 'Montserrat', sans-serif;
            font-size: 11px;
            font-weight: 500;
            letter-spacing: 0.32em;
            text-transform: uppercase;
            color: var(--rose);
            text-align: center;
        }
        .section-title {
            font-family: 'Cormorant Garamond', serif;
            font-weight: 500;
            font-size: clamp(32px, 5vw, 46px);
            line-height: 1.1;
            color: var(--text);
            text-align: center;
            margin-top: 10px;
        }
        .section-divider {
            width: 84px;
            height: 1px;
            background: var(--border);
            margin: 20px auto 0;
        }

        /* — 1) HERO — */
        .xv-hero {
            min-height: 100vh;
            min-height: 100svh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px 18px;
        }
        @media (min-width: 768px) {
            .xv-hero { padding: 32px 24px; }
        }

        .xv-hero-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 32px;
            box-shadow: var(--shadow-cover);
            max-width: 1180px;
            width: 100%;
            padding: clamp(20px, 3vw, 28px);
            display: grid;
            grid-template-columns: 1fr;
            gap: clamp(24px, 4vw, 48px);
            align-items: center;
            position: relative;
            animation: heroFadeUp .9s ease both;
        }
        @media (min-width: 768px) {
            .xv-hero-card {
                padding: clamp(32px, 4vw, 56px);
            }
        }
        @media (min-width: 1024px) {
            .xv-hero-card {
                grid-template-columns: 1fr 0.95fr;
                gap: clamp(32px, 5vw, 72px);
                padding: clamp(36px, 4vw, 64px);
            }
        }

        /* — Corona — */
        .hero-crown {
            position: absolute;
            top: clamp(14px, 2.4vw, 22px);
            left: clamp(16px, 3vw, 30px);
            color: var(--rose);
            font-size: clamp(20px, 2.2vw, 26px);
            line-height: 1;
            opacity: 0.75;
            user-select: none;
        }

        /* — Nav desktop — */
        .hero-nav {
            position: absolute;
            top: clamp(14px, 2.4vw, 22px);
            right: clamp(20px, 3vw, 36px);
            display: none;
            gap: 26px;
            align-items: center;
        }
        @media (min-width: 768px) {
            .hero-nav { display: flex; }
        }
        .hero-nav a {
            font-family: 'Montserrat', sans-serif;
            font-size: 11px;
            font-weight: 500;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: var(--text);
            opacity: 0.7;
            transition: opacity .2s ease, color .2s ease;
            position: relative;
        }
        .hero-nav a:hover { opacity: 1; color: var(--rose-dark); }
        .hero-nav a.active {
            opacity: 1;
            color: var(--rose-dark);
        }
        .hero-nav a.active::after {
            content: '';
            position: absolute;
            left: 50%;
            bottom: -7px;
            transform: translateX(-50%);
            width: 16px;
            height: 2px;
            background: var(--rose);
            border-radius: 2px;
        }

        /* — Menú hamburguesa móvil — */
        .hero-menu {
            position: absolute;
            top: clamp(14px, 2.4vw, 22px);
            right: clamp(16px, 3vw, 26px);
            width: 40px;
            height: 40px;
            display: inline-flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 5px;
            background: transparent;
            border: 0;
            cursor: pointer;
            z-index: 5;
        }
        @media (min-width: 768px) {
            .hero-menu { display: none; }
        }
        .hero-menu span {
            display: block;
            width: 22px;
            height: 1.5px;
            background: var(--rose-dark);
            border-radius: 2px;
        }

        /* — Foto — */
        .hero-photo {
            animation: heroImageIn 1s ease .15s both;
            order: 1;
        }
        @media (min-width: 1024px) {
            .hero-photo { order: 2; }
        }
        .hero-photo-frame {
            border: 1px solid var(--border);
            border-radius: 34px;
            padding: 10px;
            background: linear-gradient(180deg, #fffafa, #ffe7e3);
        }
        .hero-photo-img {
            width: 100%;
            height: 360px;
            object-fit: cover;
            object-position: center top;
            border-radius: 26px;
            display: block;
        }
        @media (min-width: 1024px) {
            .hero-photo-img { height: 620px; }
        }

        /* — Imagen parallax — */
        .xv-parallax-section {
            width: min(100% - 32px, 1180px);
            margin: 0 auto;
            min-height: min(120vh, 980px);
            padding: 0 0 80px;
        }
        .xv-parallax-frame {
            position: sticky;
            top: clamp(18px, 8vh, 72px);
            overflow: visible;
            border-radius: 0;
            background: transparent;
        }
        .xv-parallax-img {
            display: block;
            width: 100%;
            height: auto;
        }
        @media (max-width: 768px) {
            .xv-parallax-section {
                width: calc(100% - 28px);
                min-height: 82vh;
                padding-bottom: 44px;
            }
        }

        /* — Contenido textual — */
        .hero-content {
            text-align: center;
            order: 2;
            animation: heroFadeUp .8s ease .3s both;
        }
        @media (min-width: 1024px) {
            .hero-content {
                order: 1;
                text-align: left;
            }
        }

        .hero-kicker {
            font-family: 'Montserrat', sans-serif;
            font-size: 12px;
            font-weight: 500;
            letter-spacing: 0.32em;
            text-transform: uppercase;
            color: var(--rose-dark);
        }
        .hero-divider {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin: 18px auto;
            max-width: 280px;
        }
        .hero-divider span {
            flex: 1;
            height: 1px;
            background: var(--rose);
            opacity: 0.6;
        }
        .hero-divider i {
            display: inline-block;
            width: 4px;
            height: 4px;
            background: var(--rose);
            border-radius: 50%;
            opacity: 0.75;
            font-style: normal;
        }
        .hero-divider.small {
            max-width: 180px;
            margin: 14px auto;
        }
        @media (min-width: 1024px) {
            .hero-divider { margin: 18px 0; }
            .hero-divider.small { margin: 14px 0; }
        }

        .hero-title {
            font-family: 'Cormorant Garamond', serif;
            font-weight: 500;
            font-style: italic;
            font-size: clamp(64px, 18vw, 88px);
            line-height: 1;
            color: var(--rose);
            margin: 0;
        }
        @media (min-width: 1024px) {
            .hero-title { font-size: clamp(80px, 10vw, 120px); }
        }

        .hero-message {
            font-family: 'Montserrat', sans-serif;
            font-size: 16px;
            line-height: 1.7;
            color: var(--text);
            max-width: 460px;
            margin: 12px auto 0;
        }
        @media (min-width: 1024px) {
            .hero-message { margin: 12px 0 0; }
        }

        .hero-date {
            font-family: 'Montserrat', sans-serif;
            font-size: 12px;
            font-weight: 500;
            letter-spacing: 0.32em;
            text-transform: uppercase;
            color: var(--rose-dark);
            margin-top: 14px;
        }

        .hero-button {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: linear-gradient(135deg, #d77c78, #c96965);
            color: #fff;
            font-family: 'Montserrat', sans-serif;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 0.16em;
            text-transform: uppercase;
            padding: 14px 28px;
            border-radius: var(--radius-btn);
            box-shadow: var(--shadow-btn);
            transition: transform .3s ease, box-shadow .3s ease;
            margin-top: 22px;
            text-decoration: none;
        }
        .hero-button:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-btn-hover);
        }
        .hero-button span {
            display: inline-block;
            transition: transform .3s ease;
            font-size: 14px;
        }
        .hero-button:hover span { transform: translateX(3px); }
        @media (max-width: 768px) {
            .hero-button {
                width: 100%;
                justify-content: center;
            }
        }

        /* — Hero: bloque fecha + hora — */
        .hero-event-block {
            margin-top: 22px;
        }
        .hero-event-date {
            font-family: 'Montserrat', sans-serif;
            font-size: 12px;
            font-weight: 500;
            letter-spacing: 0.32em;
            text-transform: uppercase;
            color: var(--text);
            line-height: 1.5;
        }
        .hero-event-time {
            font-family: 'Cormorant Garamond', serif;
            font-style: italic;
            font-size: 20px;
            font-weight: 500;
            color: var(--rose-dark);
            margin-top: 2px;
        }
        .hero-event-time .time-label {
            font-family: 'Montserrat', sans-serif;
            font-style: normal;
            font-size: 10px;
            font-weight: 500;
            letter-spacing: 0.28em;
            text-transform: uppercase;
            color: var(--muted);
            margin-right: 8px;
            vertical-align: middle;
        }

        /* — Hero: countdown animado — */
        .hero-countdown {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 8px;
            margin-top: 18px;
        }
        @media (max-width: 540px) {
            .hero-countdown { grid-template-columns: repeat(2, 1fr); gap: 10px; }
        }

        .hero-countdown-box {
            background: rgba(255, 244, 241, 0.55);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 14px 6px;
            text-align: center;
            transition: border-color .3s ease, background .3s ease, transform .3s ease;
        }
        .hero-countdown-box:hover {
            border-color: var(--rose);
            background: rgba(255, 244, 241, 0.85);
            transform: translateY(-1px);
        }

        .hero-countdown-num {
            display: block;
            font-family: 'Cormorant Garamond', serif;
            font-size: clamp(26px, 3.4vw, 34px);
            font-weight: 600;
            color: var(--rose-dark);
            font-variant-numeric: tabular-nums;
            line-height: 1;
            transition: color .25s ease;
            transform-origin: center;
        }

        /* Animación de tick: cuando JS actualiza el número */
        .hero-countdown-num.tick {
            animation: heroCountdownTick .5s cubic-bezier(.22,.61,.36,1);
        }
        @keyframes heroCountdownTick {
            0%   { transform: scale(1);    color: var(--rose-dark); }
            45%  { transform: scale(1.16); color: var(--rose); }
            100% { transform: scale(1);    color: var(--rose-dark); }
        }

        .hero-countdown-lbl {
            display: block;
            font-family: 'Montserrat', sans-serif;
            font-size: 9px;
            font-weight: 500;
            letter-spacing: 0.25em;
            text-transform: uppercase;
            color: var(--muted);
            margin-top: 6px;
        }

        /* Indicador "live" sutil en el box de segundos */
        .hero-countdown-box.live {
            position: relative;
        }
        .hero-countdown-box.live::after {
            content: '';
            position: absolute;
            top: 8px;
            right: 8px;
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: var(--rose);
            box-shadow: 0 0 0 0 rgba(217, 124, 120, 0.55);
            animation: livePulse 1.6s ease-out infinite;
        }
        @keyframes livePulse {
            0%   { box-shadow: 0 0 0 0   rgba(217, 124, 120, 0.55); }
            70%  { box-shadow: 0 0 0 8px rgba(217, 124, 120, 0); }
            100% { box-shadow: 0 0 0 0   rgba(217, 124, 120, 0); }
        }

        @keyframes heroFadeUp {
            from {
                opacity: 0;
                transform: translateY(24px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        @keyframes heroImageIn {
            from {
                opacity: 0;
                transform: scale(0.96);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }
        @media (prefers-reduced-motion: reduce) {
            .xv-hero-card,
            .hero-photo,
            .hero-content {
                animation: none !important;
                opacity: 1 !important;
                transform: none !important;
            }
        }

        /* — 2) Mensaje emocional (Valentina) — */
        .xv-message-section {
            width: min(1100px, calc(100% - 32px));
            margin: 0 auto;
            padding: 72px 0 36px;
        }
        .xv-message-card {
            position: relative;
            overflow: hidden;
            text-align: center;
            background: rgba(255, 250, 248, 0.84);
            border: 1px solid var(--border);
            border-radius: 36px;
            padding: clamp(34px, 6vw, 76px);
            box-shadow: 0 24px 70px rgba(165, 105, 100, 0.13);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }
        /* Círculos decorativos muy sutiles (clipeados por overflow) */
        .xv-message-card::before,
        .xv-message-card::after {
            content: "";
            position: absolute;
            width: 220px;
            height: 220px;
            border: 1px solid rgba(217, 124, 120, 0.18);
            border-radius: 999px;
            pointer-events: none;
            z-index: 0;
        }
        .xv-message-card::before {
            top: -120px;
            left: -90px;
        }
        .xv-message-card::after {
            bottom: -130px;
            right: -90px;
        }
        .xv-message-card > * {
            position: relative;
            z-index: 1;
        }

        .message-kicker {
            display: block;
            margin-bottom: 14px;
            font-family: 'Montserrat', sans-serif;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 0.34em;
            text-transform: uppercase;
            color: var(--rose);
        }
        .xv-message-card h2 {
            margin: 0;
            font-family: 'Cormorant Garamond', serif;
            font-size: clamp(34px, 5vw, 58px);
            font-weight: 500;
            line-height: 1.05;
            color: var(--text);
        }
        .xv-message-card p {
            max-width: 620px;
            margin: 0 auto;
            font-family: 'Montserrat', sans-serif;
            font-size: clamp(15px, 2vw, 18px);
            line-height: 1.9;
            color: var(--text);
        }
        .xv-message-card p strong {
            font-weight: 600;
            color: var(--rose-dark);
        }

        .message-divider {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            margin: 28px auto;
        }
        .message-divider span {
            width: 74px;
            height: 1px;
            background: rgba(217, 124, 120, 0.42);
        }
        .message-divider i {
            width: 7px;
            height: 7px;
            display: block;
            border-radius: 999px;
            background: var(--rose);
            font-style: normal;
        }

        @media (max-width: 768px) {
            .xv-message-section {
                width: calc(100% - 28px);
                max-width: 620px;
                padding: 48px 0 24px;
            }
            .xv-message-card {
                border-radius: 28px;
                padding: 34px 22px;
            }
            .xv-message-card::before {
                width: 160px;
                height: 160px;
                top: -90px;
                left: -70px;
            }
            .xv-message-card::after {
                width: 160px;
                height: 160px;
                bottom: -100px;
                right: -70px;
            }
        }

        /* — 3) Galería / Carrusel — */
        .xv-gallery-section {
            width: min(1080px, calc(100% - 32px));
            margin: 0 auto;
            padding: 48px 0;
        }
        .xv-gallery-card {
            background: rgba(255, 250, 248, 0.86);
            border: 1px solid var(--border);
            border-radius: 34px;
            padding: clamp(26px, 5vw, 58px);
            box-shadow: 0 24px 70px rgba(165, 105, 100, 0.13);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }
        .gallery-heading {
            text-align: center;
            margin-bottom: 30px;
        }
        .gallery-heading .section-kicker {
            font-family: 'Montserrat', sans-serif;
            font-size: 11px;
            font-weight: 500;
            letter-spacing: 0.34em;
            text-transform: uppercase;
            color: var(--rose);
        }
        .gallery-heading h2 {
            font-family: 'Cormorant Garamond', serif;
            font-size: clamp(38px, 5vw, 62px);
            font-weight: 500;
            color: var(--text);
            margin: 10px 0 0;
            line-height: 1.05;
        }
        .gallery-heading p {
            max-width: 520px;
            margin: 12px auto 0;
            font-family: 'Montserrat', sans-serif;
            font-size: 14px;
            line-height: 1.8;
            color: var(--muted);
        }
        .gallery-slider {
            position: relative;
            display: grid;
            place-items: center;
            margin-top: 6px;
        }
        .gallery-frame {
            width: 100%;
            overflow: hidden;
            border-radius: 0;
            border: 0;
            background: #fffaf8;
            box-shadow: none;
        }
        .gallery-frame img {
            display: block;
            width: 100%;
            height: min(68vh, 720px);
            min-height: 460px;
            object-fit: contain;
            object-position: center center;
            transition: opacity 0.35s ease, transform 0.35s ease;
        }
        .gallery-frame img.is-changing {
            opacity: 0;
            transform: scale(0.985);
        }
        .gallery-btn {
            position: absolute;
            top: 50%;
            z-index: 2;
            width: 44px;
            height: 44px;
            border: 1px solid rgba(217, 124, 120, 0.38);
            border-radius: 999px;
            background: rgba(255, 250, 248, 0.92);
            color: var(--rose-dark);
            font-size: 28px;
            line-height: 1;
            display: grid;
            place-items: center;
            cursor: pointer;
            box-shadow: 0 12px 30px rgba(165, 105, 100, 0.16);
            transition: transform 0.25s ease, box-shadow 0.25s ease, background 0.25s ease;
            padding: 0;
        }
        .gallery-btn:hover {
            transform: translateY(-50%) scale(1.04);
            box-shadow: 0 16px 36px rgba(165, 105, 100, 0.22);
            background: #fff;
        }
        .gallery-btn-prev {
            left: -22px;
            transform: translateY(-50%);
        }
        .gallery-btn-prev:hover {
            transform: translateY(-50%) scale(1.04);
        }
        .gallery-btn-next {
            right: -22px;
            transform: translateY(-50%);
        }
        .gallery-btn-next:hover {
            transform: translateY(-50%) scale(1.04);
        }
        .gallery-dots {
            display: flex;
            justify-content: center;
            gap: 9px;
            margin-top: 22px;
        }
        .gallery-dot {
            width: 8px;
            height: 8px;
            border: 0;
            padding: 0;
            border-radius: 999px;
            background: rgba(217, 124, 120, 0.28);
            cursor: pointer;
            transition: width 0.25s ease, background 0.25s ease;
        }
        .gallery-dot.is-active {
            width: 26px;
            background: var(--rose);
        }
        .gallery-thumbs {
            display: flex;
            justify-content: center;
            gap: 12px;
            margin-top: 18px;
            flex-wrap: wrap;
        }
        .gallery-thumb {
            width: 72px;
            height: 72px;
            padding: 3px;
            border: 1px solid transparent;
            border-radius: 18px;
            background: transparent;
            cursor: pointer;
            opacity: 0.68;
            transition: opacity 0.25s ease, border-color 0.25s ease, transform 0.25s ease;
        }
        .gallery-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 14px;
        }
        .gallery-thumb.is-active {
            opacity: 1;
            border-color: var(--rose);
            transform: translateY(-2px);
        }
        @media (max-width: 768px) {
            .xv-gallery-section {
                width: calc(100% - 28px);
                max-width: 620px;
                padding: 36px 0;
            }
            .xv-gallery-card {
                border-radius: 28px;
                padding: 24px 18px;
            }
            .gallery-heading { margin-bottom: 22px; }
            .gallery-frame { border-radius: 24px; }
            .gallery-frame img {
                height: min(64vh, 560px);
                min-height: 360px;
            }
            .gallery-btn {
                width: 38px;
                height: 38px;
                font-size: 24px;
            }
            .gallery-btn-prev { left: 10px; }
            .gallery-btn-next { right: 10px; }
            .gallery-thumbs { display: none; }
        }

        /* — 4) Padrinos — */
        .xv-sponsors-section {
            width: min(1080px, calc(100% - 32px));
            margin: 0 auto;
            padding: 48px 0;
        }
        .xv-sponsors-card {
            position: relative;
            overflow: hidden;
            text-align: center;
            background: rgba(255, 250, 248, 0.86);
            border: 1px solid var(--border);
            border-radius: 34px;
            padding: clamp(34px, 5vw, 64px);
            box-shadow: 0 24px 70px rgba(165, 105, 100, 0.13);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }
        .xv-sponsors-card::before,
        .xv-sponsors-card::after {
            content: "";
            position: absolute;
            width: 190px;
            height: 190px;
            border: 1px solid rgba(217, 124, 120, 0.16);
            border-radius: 999px;
            pointer-events: none;
            z-index: 0;
        }
        .xv-sponsors-card::before {
            top: -105px;
            left: -80px;
        }
        .xv-sponsors-card::after {
            bottom: -110px;
            right: -80px;
        }
        .xv-sponsors-card > * {
            position: relative;
            z-index: 1;
        }

        .xv-sponsors-card .section-kicker {
            display: block;
            margin-bottom: 10px;
            font-family: 'Montserrat', sans-serif;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.34em;
            text-transform: uppercase;
            color: var(--rose);
        }
        .xv-sponsors-card h2 {
            margin: 0;
            font-family: 'Cormorant Garamond', serif;
            font-size: clamp(38px, 5vw, 58px);
            font-weight: 500;
            line-height: 1;
            color: var(--text);
        }

        .sponsors-divider {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            margin: 22px auto 0;
        }
        .sponsors-divider span {
            width: 68px;
            height: 1px;
            background: rgba(217, 124, 120, 0.42);
        }
        .sponsors-divider i {
            width: 7px;
            height: 7px;
            display: block;
            border-radius: 999px;
            background: var(--rose);
            font-style: normal;
        }

        .sponsors-intro {
            max-width: 520px;
            margin: 16px auto 0;
            font-family: 'Montserrat', sans-serif;
            font-size: 14px;
            line-height: 1.8;
            color: var(--muted);
        }

        .sponsors-list {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 18px;
            margin-top: 34px;
        }
        .sponsor-item {
            position: relative;
            padding: 30px 24px;
            border: 1px solid rgba(217, 124, 120, 0.28);
            border-radius: 26px;
            background: rgba(255, 244, 241, 0.58);
            transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
        }
        .sponsor-item:hover {
            transform: translateY(-3px);
            border-color: rgba(217, 124, 120, 0.48);
            box-shadow: 0 18px 38px rgba(165, 105, 100, 0.12);
        }
        .sponsor-item > span {
            display: block;
            margin-bottom: 14px;
            font-family: 'Montserrat', sans-serif;
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 0.28em;
            text-transform: uppercase;
            color: var(--rose);
        }
        .sponsor-item h3 {
            margin: 0;
            font-family: 'Cormorant Garamond', serif;
            font-size: clamp(25px, 3vw, 34px);
            font-weight: 500;
            line-height: 1.1;
            color: var(--text);
        }
        .sponsor-item small {
            display: block;
            margin: 10px 0;
            font-family: 'Cormorant Garamond', serif;
            font-size: 26px;
            line-height: 1;
            color: var(--rose);
        }

        /* Bloque de Padres — full-width dentro del grid */
        .sponsor-item.sponsor-parents {
            grid-column: 1 / -1;
            background: rgba(255, 244, 241, 0.78);
        }
        .sponsor-item.sponsor-parents h3 {
            font-size: clamp(28px, 3.4vw, 38px);
        }
        .sponsor-item.sponsor-parents small {
            font-size: 30px;
            margin: 12px 0;
        }

        @media (max-width: 768px) {
            .xv-sponsors-section {
                width: calc(100% - 28px);
                max-width: 620px;
                padding: 36px 0;
            }
            .xv-sponsors-card {
                border-radius: 28px;
                padding: 34px 20px;
            }
            .sponsors-list {
                grid-template-columns: 1fr;
                gap: 16px;
                margin-top: 28px;
            }
            .sponsor-item {
                padding: 26px 18px;
                border-radius: 22px;
            }
            .sponsors-divider span {
                width: 48px;
            }
        }

        /* — 5) Evento — */
        .evento-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 14px;
            margin-top: 32px;
        }
        @media (min-width: 768px) {
            .evento-grid { grid-template-columns: repeat(3, 1fr); gap: 18px; }
        }
        .evento-item {
            background: var(--bg-soft);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 28px 20px;
            text-align: center;
        }
        .evento-icon {
            color: var(--rose);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.6);
            margin-bottom: 14px;
        }
        .evento-icon svg { width: 22px; height: 22px; }
        .evento-label {
            font-family: 'Montserrat', sans-serif;
            font-size: 11px;
            font-weight: 500;
            letter-spacing: 0.25em;
            text-transform: uppercase;
            color: var(--muted);
        }
        .evento-value {
            font-family: 'Cormorant Garamond', serif;
            font-size: 22px;
            font-weight: 500;
            color: var(--text);
            margin-top: 6px;
        }

        /* — 3) Countdown — */
        .countdown-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
            margin-top: 32px;
        }
        @media (min-width: 768px) {
            .countdown-grid { grid-template-columns: repeat(4, 1fr); gap: 16px; }
        }
        .countdown-box {
            background: var(--bg-soft);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 24px 12px;
            text-align: center;
        }
        .countdown-number {
            display: block;
            font-family: 'Cormorant Garamond', serif;
            font-size: clamp(40px, 7vw, 56px);
            font-weight: 600;
            line-height: 1;
            color: var(--rose-dark);
            font-variant-numeric: tabular-nums;
        }
        .countdown-label {
            display: block;
            font-family: 'Montserrat', sans-serif;
            font-size: 10px;
            font-weight: 500;
            letter-spacing: 0.25em;
            text-transform: uppercase;
            color: var(--muted);
            margin-top: 10px;
        }

        /* — 4) Ubicación — */
        .ubicacion-name {
            font-family: 'Cormorant Garamond', serif;
            font-size: clamp(22px, 3.5vw, 28px);
            color: var(--text);
            margin-top: 10px;
            text-align: center;
        }
        .ubicacion-addr {
            font-family: 'Montserrat', sans-serif;
            font-size: 15px;
            color: var(--muted);
            margin-top: 6px;
            text-align: center;
        }
        .ubicacion-cta {
            display: flex;
            justify-content: center;
            margin-top: 24px;
        }
        .ubicacion-map {
            margin-top: 28px;
            border-radius: 24px;
            overflow: hidden;
            border: 1px solid var(--border);
            box-shadow: 0 12px 32px -16px rgba(165, 105, 100, 0.2);
            background: var(--bg-soft);
        }
        .ubicacion-map iframe {
            display: block;
            width: 100%;
            height: 280px;
            border: 0;
        }
        @media (min-width: 768px) {
            .ubicacion-map iframe { height: 380px; }
        }

        /* — 5) Dress code — */
        .dress-card {
            position: relative;
            overflow: hidden;
        }
        .dress-card::before,
        .dress-card::after {
            content: "";
            position: absolute;
            width: 170px;
            height: 170px;
            border: 1px solid rgba(217, 124, 120, 0.14);
            border-radius: 999px;
            pointer-events: none;
            z-index: 0;
        }
        .dress-card::before {
            top: -90px;
            left: -70px;
        }
        .dress-card::after {
            bottom: -95px;
            right: -70px;
        }
        .dress-card > * { position: relative; z-index: 1; }

        .dress-main {
            font-family: 'Cormorant Garamond', serif;
            font-style: italic;
            font-weight: 500;
            font-size: clamp(28px, 4.5vw, 40px);
            line-height: 1.15;
            color: var(--rose);
            text-align: center;
            margin: 22px auto 0;
        }

        /* Callout: rosa reservado */
        .dress-reserved {
            display: flex;
            align-items: center;
            gap: 14px;
            margin: 30px auto 0;
            max-width: 460px;
            padding: 14px 18px;
            background: rgba(255, 250, 248, 0.65);
            border: 1px solid rgba(217, 124, 120, 0.32);
            border-radius: 18px;
            text-align: left;
        }
        .dress-reserved-circle-wrap {
            position: relative;
            flex-shrink: 0;
            width: 42px;
            height: 42px;
        }
        .dress-reserved-circle {
            position: absolute;
            inset: 0;
            border-radius: 50%;
            background: #F7C9D6;
            border: 2px solid rgba(255, 255, 255, 0.85);
            box-shadow: 0 4px 10px -4px rgba(165, 105, 100, 0.35);
        }
        .dress-reserved-x {
            position: absolute;
            inset: -4px;
            width: calc(100% + 8px);
            height: calc(100% + 8px);
            color: var(--rose-dark);
        }
        .dress-reserved-text {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }
        .dress-reserved-text strong {
            font-family: 'Cormorant Garamond', serif;
            font-weight: 600;
            font-size: 17px;
            color: var(--text);
            line-height: 1.3;
        }

        @media (max-width: 480px) {
            .dress-reserved {
                flex-direction: column;
                text-align: center;
                gap: 10px;
                padding: 16px 14px;
            }
            .dress-reserved-text { align-items: center; }
        }

        /* — 6) Mesa de regalos — */
        .mesa-text {
            font-family: 'Montserrat', sans-serif;
            font-size: 15px;
            line-height: 1.75;
            color: var(--text);
            max-width: 520px;
            margin: 16px auto 0;
            text-align: center;
        }
        .mesa-buttons {
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin-top: 28px;
            align-items: center;
        }
        @media (min-width: 640px) {
            .mesa-buttons { flex-direction: row; justify-content: center; }
        }

        /* — 6) Lluvia de sobres — */
        .lluvia-card .lluvia-text {
            max-width: 540px;
            margin: 20px auto 0;
            font-family: 'Montserrat', sans-serif;
            font-size: 15px;
            line-height: 1.85;
            color: var(--text);
            text-align: center;
        }

        /* Sobre minimalista — CSS puro (sin imágenes) */
        .sobre-card {
            position: relative;
            width: 200px;
            height: 140px;
            margin: 36px auto 28px;
            background: linear-gradient(180deg, #fffaf8 0%, #fff0ed 100%);
            border: 1.5px solid var(--rose);
            border-radius: 14px;
            box-shadow:
                0 18px 40px -18px rgba(165, 105, 100, 0.32),
                inset 0 -6px 14px rgba(217, 124, 120, 0.06);
            overflow: hidden;
        }

        /* Solapa del sobre (triángulo superior) */
        .sobre-card::before {
            content: "";
            position: absolute;
            top: -1px;
            left: -1px;
            right: -1px;
            height: 72px;
            background: linear-gradient(180deg,
                rgba(255, 250, 248, 0.98) 0%,
                rgba(255, 244, 241, 0.98) 100%);
            clip-path: polygon(0 0, 100% 0, 50% 100%);
            border-bottom: 1.5px solid var(--rose);
        }

        /* Líneas diagonales internas del cuerpo del sobre (V hacia el centro) */
        .sobre-card::after {
            content: "";
            position: absolute;
            top: 72px;
            left: 1.5px;
            right: 1.5px;
            bottom: 1.5px;
            background:
                linear-gradient(123deg,
                    transparent calc(50% - 0.8px),
                    rgba(217, 124, 120, 0.45) calc(50% - 0.8px) calc(50% + 0.8px),
                    transparent calc(50% + 0.8px)),
                linear-gradient(57deg,
                    transparent calc(50% - 0.8px),
                    rgba(217, 124, 120, 0.45) calc(50% - 0.8px) calc(50% + 0.8px),
                    transparent calc(50% + 0.8px));
            pointer-events: none;
        }

        .sobre-heart {
            position: absolute;
            top: 60%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: var(--rose-dark);
            font-size: 38px;
            line-height: 1;
            text-shadow: 0 2px 6px rgba(217, 124, 120, 0.2);
            z-index: 1;
        }

        .lluvia-card .lluvia-cierre {
            max-width: 460px;
            margin: 4px auto 0;
            font-family: 'Cormorant Garamond', serif;
            font-style: italic;
            font-size: 18px;
            line-height: 1.5;
            color: var(--rose-dark);
            text-align: center;
        }

        /* Botón opcional de datos bancarios — comentado en HTML por defecto */
        .lluvia-btn {
            margin-top: 24px;
            max-width: 280px;
        }

        @media (max-width: 480px) {
            .sobre-card {
                width: 170px;
                height: 120px;
            }
            .sobre-card::before { height: 62px; }
            .sobre-card::after { top: 62px; }
            .sobre-heart { font-size: 32px; }
        }

        /* — 7) Confirmación — */
        .confirm-card {
            background: linear-gradient(135deg, rgba(252, 228, 225, 0.95) 0%, rgba(255, 244, 243, 0.95) 100%);
        }
        .confirm-text {
            font-family: 'Montserrat', sans-serif;
            font-size: 15px;
            line-height: 1.75;
            color: var(--text);
            max-width: 480px;
            margin: 16px auto 0;
            text-align: center;
        }
        .confirm-cta {
            display: flex;
            justify-content: center;
            margin-top: 28px;
        }

        /* — 7b) Modal de confirmación — */
        .confirm-modal {
            position: fixed;
            inset: 0;
            z-index: 100;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 16px;
            animation: confirmModalFade .35s ease;
        }
        .confirm-modal[hidden] { display: none !important; }
        @keyframes confirmModalFade {
            from { opacity: 0; }
            to   { opacity: 1; }
        }
        .confirm-modal-backdrop {
            position: absolute;
            inset: 0;
            background: rgba(63, 70, 72, 0.42);
            backdrop-filter: blur(6px);
            -webkit-backdrop-filter: blur(6px);
        }
        .confirm-modal-content {
            position: relative;
            width: 100%;
            max-width: 440px;
            max-height: calc(100vh - 32px);
            max-height: calc(100svh - 32px);
            overflow-y: auto;
            background: linear-gradient(180deg, #fffaf8 0%, #fff4f1 100%);
            border: 1px solid var(--border);
            border-radius: 28px;
            box-shadow:
                0 30px 70px -20px rgba(165, 105, 100, 0.35),
                0 12px 28px -18px rgba(217, 124, 120, 0.25);
            padding: clamp(28px, 5vw, 44px) clamp(22px, 4vw, 36px) clamp(24px, 4vw, 36px);
            animation: confirmModalPop .45s cubic-bezier(.22,.61,.36,1);
        }
        @keyframes confirmModalPop {
            from { opacity: 0; transform: translateY(16px) scale(0.97); }
            to   { opacity: 1; transform: translateY(0) scale(1); }
        }
        .confirm-modal-close {
            position: absolute;
            top: 14px;
            right: 14px;
            width: 36px;
            height: 36px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 250, 248, 0.7);
            border: 1px solid var(--border);
            color: var(--rose-dark);
            font-size: 22px;
            line-height: 1;
            border-radius: 50%;
            cursor: pointer;
            transition: background .25s ease, transform .25s ease;
        }
        .confirm-modal-close:hover {
            background: #fff;
            transform: rotate(90deg);
        }

        .confirm-modal-header {
            text-align: center;
            margin-bottom: 22px;
        }
        .confirm-modal-kicker {
            display: inline-block;
            font-family: 'Montserrat', sans-serif;
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 0.34em;
            text-transform: uppercase;
            color: var(--rose);
        }
        .confirm-modal-header h2 {
            font-family: 'Cormorant Garamond', serif;
            font-style: italic;
            font-weight: 500;
            font-size: clamp(38px, 7vw, 48px);
            line-height: 1;
            color: var(--rose);
            margin: 10px 0 0;
        }
        .confirm-modal-divider {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin: 18px auto 16px;
            max-width: 220px;
        }
        .confirm-modal-divider span {
            flex: 1;
            height: 1px;
            background: rgba(217, 124, 120, 0.42);
        }
        .confirm-modal-divider i {
            width: 6px;
            height: 6px;
            background: var(--rose);
            border-radius: 50%;
            font-style: normal;
        }
        .confirm-modal-date {
            font-family: 'Montserrat', sans-serif;
            font-size: 12px;
            font-weight: 500;
            letter-spacing: 0.28em;
            text-transform: uppercase;
            color: var(--text);
            line-height: 1.5;
        }
        .confirm-modal-time {
            font-family: 'Cormorant Garamond', serif;
            font-style: italic;
            font-size: 18px;
            font-weight: 500;
            color: var(--rose-dark);
            margin-top: 4px;
        }
        .confirm-modal-time .time-label {
            font-family: 'Montserrat', sans-serif;
            font-style: normal;
            font-size: 9px;
            font-weight: 500;
            letter-spacing: 0.26em;
            text-transform: uppercase;
            color: var(--muted);
            margin-right: 8px;
            vertical-align: middle;
        }

        .confirm-modal-intro {
            font-family: 'Montserrat', sans-serif;
            font-size: 13.5px;
            line-height: 1.7;
            color: var(--muted);
            text-align: center;
            max-width: 360px;
            margin: 0 auto 22px;
        }

        .confirm-form {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .confirm-label {
            font-family: 'Montserrat', sans-serif;
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 0.28em;
            text-transform: uppercase;
            color: var(--rose);
            text-align: center;
        }
        .confirm-input {
            width: 100%;
            padding: 14px 18px;
            font-family: 'Cormorant Garamond', serif;
            font-size: 18px;
            color: var(--text);
            background: rgba(255, 255, 255, 0.7);
            border: 1.5px solid rgba(217, 124, 120, 0.32);
            border-radius: 14px;
            outline: 0;
            text-align: center;
            transition: border-color .25s ease, box-shadow .25s ease, background .25s ease;
        }
        .confirm-input::placeholder {
            color: rgba(139, 143, 145, 0.7);
            font-style: italic;
        }
        .confirm-input:focus {
            border-color: var(--rose);
            background: #fff;
            box-shadow: 0 0 0 4px rgba(217, 124, 120, 0.12);
        }
        .confirm-input.is-invalid {
            border-color: #c96965;
            background: rgba(255, 235, 233, 0.7);
        }

        .confirm-error {
            font-family: 'Montserrat', sans-serif;
            font-size: 12px;
            color: #b04a47;
            text-align: center;
            margin: 0;
        }

        .confirm-submit {
            position: relative;
            margin-top: 6px;
        }
        .confirm-submit[disabled] {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none !important;
        }
        .confirm-submit-spinner {
            display: none;
            width: 14px;
            height: 14px;
            border: 2px solid rgba(255, 255, 255, 0.45);
            border-top-color: #fff;
            border-radius: 50%;
            margin-left: 8px;
            animation: confirmSpin .7s linear infinite;
        }
        .confirm-submit.is-loading .confirm-submit-label { opacity: 0.8; }
        .confirm-submit.is-loading .confirm-submit-spinner { display: inline-block; }
        @keyframes confirmSpin {
            to { transform: rotate(360deg); }
        }

        /* Estado de éxito */
        .confirm-success-state {
            text-align: center;
            padding: 8px 0;
        }
        .confirm-success-icon {
            width: 64px;
            height: 64px;
            margin: 0 auto 14px;
        }
        .confirm-success-icon svg { width: 100%; height: 100%; }
        .confirm-check-bg {
            stroke: var(--rose);
            stroke-width: 2;
            stroke-dasharray: 166;
            stroke-dashoffset: 166;
            animation: confirmCheckCircle .8s cubic-bezier(.22,.61,.36,1) forwards;
        }
        .confirm-check-mark {
            stroke: var(--rose-dark);
            stroke-width: 3;
            stroke-linecap: round;
            stroke-linejoin: round;
            stroke-dasharray: 48;
            stroke-dashoffset: 48;
            animation: confirmCheckMark .5s 0.5s cubic-bezier(.22,.61,.36,1) forwards;
        }
        @keyframes confirmCheckCircle {
            to { stroke-dashoffset: 0; }
        }
        @keyframes confirmCheckMark {
            to { stroke-dashoffset: 0; }
        }
        .confirm-success-state h3 {
            font-family: 'Cormorant Garamond', serif;
            font-weight: 500;
            font-size: clamp(26px, 5vw, 32px);
            color: var(--text);
            margin: 0 0 8px;
        }
        .confirm-success-state p {
            font-family: 'Montserrat', sans-serif;
            font-size: 14px;
            color: var(--muted);
            line-height: 1.6;
            margin: 0 auto 22px;
            max-width: 320px;
        }
        .confirm-success-state .btn {
            max-width: 240px;
        }

        body.confirm-modal-open {
            overflow: hidden;
        }

        /* — 8) Enlace — */
        .enlace-text {
            font-family: 'Montserrat', sans-serif;
            font-size: 15px;
            color: var(--muted);
            max-width: 480px;
            margin: 16px auto 0;
            text-align: center;
        }
        .enlace-url {
            font-family: 'Montserrat', sans-serif;
            font-size: 17px;
            font-weight: 500;
            color: var(--rose-dark);
            background: var(--bg-soft);
            border: 1px solid var(--border);
            border-radius: var(--radius-btn);
            padding: 14px 20px;
            max-width: 360px;
            margin: 22px auto 0;
            text-align: center;
            word-break: break-all;
        }
        .enlace-cta {
            display: flex;
            justify-content: center;
            margin-top: 22px;
        }
        .copy-message {
            font-family: 'Montserrat', sans-serif;
            font-size: 13px;
            font-weight: 500;
            color: var(--rose-dark);
            text-align: center;
            margin-top: 16px;
            opacity: 0;
            transition: opacity .3s ease;
        }
        .copy-message.is-visible { opacity: 1; }

        /* — 9) Footer — */
        .invite-footer {
            width: min(1100px, calc(100% - 32px));
            margin: 0 auto;
            padding: 32px 0 56px;
            text-align: center;
        }
        .footer-line {
            width: 60px;
            height: 1px;
            background: var(--border);
            margin: 0 auto 24px;
        }
        .footer-family {
            font-family: 'Cormorant Garamond', serif;
            font-style: italic;
            font-size: 22px;
            color: var(--rose);
        }
        .footer-event {
            font-family: 'Montserrat', sans-serif;
            font-size: 11px;
            letter-spacing: 0.32em;
            text-transform: uppercase;
            color: var(--muted);
            margin-top: 8px;
        }

        /* ============================================================
           BOTONES (compartidos)
           ============================================================ */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            font-family: 'Montserrat', sans-serif;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 0.16em;
            text-transform: uppercase;
            padding: 14px 28px;
            border-radius: var(--radius-btn);
            border: 0;
            cursor: pointer;
            transition: transform .3s ease, box-shadow .3s ease, background .3s ease, color .3s ease;
            text-decoration: none;
            width: 100%;
            max-width: 320px;
        }
        .btn-primary {
            background: linear-gradient(135deg, #d99591, #c97f7b);
            color: #ffffff;
            box-shadow: var(--shadow-btn);
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-btn-hover);
        }
        .btn-primary:active { transform: translateY(0); }
        .btn-outline {
            background: transparent;
            color: var(--rose-dark);
            border: 1.5px solid var(--rose);
            box-shadow: none;
        }
        .btn-outline:hover {
            background: var(--bg-soft);
            transform: translateY(-2px);
        }
        .btn svg { width: 18px; height: 18px; }

        /* ============================================================
           FAB DE MÚSICA (bottom-right)
           ============================================================ */
        .music-fab {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 50;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 52px;
            height: 52px;
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 50%;
            color: var(--rose-dark);
            box-shadow: 0 14px 30px -10px rgba(201, 127, 123, 0.4);
            cursor: pointer;
            transition: transform .3s ease, box-shadow .3s ease, color .3s ease;
            opacity: 0;
            pointer-events: none;
        }
        @media (min-width: 768px) {
            .music-fab { bottom: 28px; right: 28px; width: 56px; height: 56px; }
        }
        body.invitation-opened .music-fab {
            opacity: 1;
            pointer-events: auto;
        }
        .music-fab:hover {
            transform: scale(1.06);
            box-shadow: 0 18px 36px -10px rgba(201, 127, 123, 0.5);
        }
        .music-fab svg { width: 22px; height: 22px; }
        .music-fab .icon-paused { display: none; }
        .music-fab.is-paused .icon-playing { display: none; }
        .music-fab.is-paused .icon-paused { display: inline; }

        /* ============================================================
           REVEAL ON SCROLL
           ============================================================ */
        .reveal {
            opacity: 0;
            transform: translateY(28px);
            transition: opacity .8s cubic-bezier(.22,.61,.36,1),
                        transform .8s cubic-bezier(.22,.61,.36,1);
        }
        .reveal.is-visible {
            opacity: 1;
            transform: translateY(0);
        }

        /* ============================================================
           RESPONSIVE — afinaciones extra
           ============================================================ */
        @media (max-width: 768px) {
            .invite-section { padding: 36px 0; }
            .section-card { padding: 28px 20px; border-radius: 24px; }
            .intro-card { border-radius: 22px; }
            .ubicacion-map iframe { height: 240px; }
        }

        /* ============================================================
           REDUCED MOTION
           ============================================================ */
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }
            .reveal { opacity: 1 !important; transform: none !important; }
        }
    </style>
</head>
<body id="page-body">

    {{-- ============================================================
         0) PORTADA / INTRO
         ============================================================ --}}
    <section id="intro" class="intro-section" aria-label="Portada — {{ $evento }} de {{ $nombre }}">
        <div class="intro-card">

            {{-- Imagen --}}
            <div class="intro-image-wrap">
                <img
                    src="{{ asset('images/xv/valeria/foto-intro.jpeg') }}"
                    alt="{{ $nombre }} — {{ $evento }}"
                    width="1024"
                    height="1536"
                    class="intro-image"
                    loading="eager"
                    decoding="async"
                    fetchpriority="high"
                >
            </div>

            {{-- Contenido --}}
            <div class="intro-content">
                <p class="intro-eyebrow">Te invitamos</p>
                <h1 class="intro-title">{{ $evento }}</h1>
                <div class="intro-divider" aria-hidden="true"></div>
                <h2 class="intro-name">{{ $nombre }}</h2>
                <p class="intro-date">{{ $fechaCorta }}</p>

                <button type="button" class="intro-button" onclick="openInvitation()">
                    Abrir invitación
                </button>

                <div class="intro-hint" aria-hidden="true">
                    <span class="intro-arrow">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M6 9l6 6 6-6"/>
                        </svg>
                    </span>
                    <p class="intro-cta-hint">Desliza o toca para continuar</p>
                </div>
            </div>
        </div>
    </section>


    {{-- ============================================================
         CONTENIDO PRINCIPAL
         ============================================================ --}}
    <main id="invitation-content" class="hidden">

        {{-- 1) HERO --}}
        <section id="hero-xv" class="xv-hero" aria-label="Hero — {{ $evento }} de {{ $nombre }}">

            <div class="xv-hero-card">

                {{-- Corona decorativa --}}
                <div class="hero-crown" aria-hidden="true">♕</div>

                {{-- Menú desktop (oculto en móvil) --}}
                <nav class="hero-nav" aria-label="Menú principal">
                    <a href="#hero-xv" class="active">Inicio</a>
                    <a href="#iglesia">Iglesia</a>
                    <a href="#salon">Salón</a>
                    <a href="#confirmacion">Confirmar</a>
                </nav>

                {{-- Botón hamburguesa (solo móvil) --}}
                <button class="hero-menu" type="button" aria-label="Abrir menú">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>

                {{-- Contenido textual (mobile: order 2, desktop: order 1) --}}
                <div class="hero-content">
                    <p class="hero-kicker">{{ $evento }}</p>

                    <div class="hero-divider" aria-hidden="true">
                        <span></span><i></i><i></i><i></i><span></span>
                    </div>

                    <h1 class="hero-title">{{ $nombre }}</h1>

                    <div class="hero-divider small" aria-hidden="true">
                        <span></span><i></i><i></i><i></i><span></span>
                    </div>

                    <p class="hero-message">{{ $bienvenidaTxt }}</p>

                    {{-- Fecha + hora ——————————————————————————————— --}}
                    <div class="hero-event-block">
                        <p class="hero-event-date">{{ $fechaLarga }}</p>
                        <p class="hero-event-time">
                            <span class="time-label">Recepción</span>{{ $horaRecepcion }}
                        </p>
                    </div>

                    {{-- Countdown animado ——————————————————————————— --}}
                    <div
                        class="hero-countdown"
                        id="hero-countdown"
                        data-event-date="{{ $eventDateIso }}"
                        role="timer"
                        aria-live="polite"
                        aria-atomic="false"
                    >
                        <div class="hero-countdown-box">
                            <span class="hero-countdown-num" data-unit="days">00</span>
                            <span class="hero-countdown-lbl">Días</span>
                        </div>
                        <div class="hero-countdown-box">
                            <span class="hero-countdown-num" data-unit="hours">00</span>
                            <span class="hero-countdown-lbl">Horas</span>
                        </div>
                        <div class="hero-countdown-box">
                            <span class="hero-countdown-num" data-unit="minutes">00</span>
                            <span class="hero-countdown-lbl">Min</span>
                        </div>
                        <div class="hero-countdown-box live">
                            <span class="hero-countdown-num" data-unit="seconds">00</span>
                            <span class="hero-countdown-lbl">Seg</span>
                        </div>
                    </div>
                </div>

                {{-- Foto (mobile: order 1, desktop: order 2) --}}
                <div class="hero-photo">
                    <div class="hero-photo-frame">
                        <img
                            src="{{ asset('images/xv/valeria/valeria-hero.jpeg') }}"
                            alt="{{ $nombre }} — {{ $evento }}"
                            width="1024"
                            height="1536"
                            class="hero-photo-img"
                            loading="eager"
                            decoding="async"
                            fetchpriority="high"
                        >
                    </div>
                </div>

            </div>
        </section>

        {{-- 1.1) IMAGEN PARALLAX --}}
        <section class="xv-parallax-section reveal" aria-label="Foto especial de {{ $nombre }}">
            <div class="xv-parallax-frame" data-parallax-frame>
                <img
                    src="{{ asset('images/xv/valeria/parallax.jpeg') }}"
                    alt="{{ $nombre }} — foto especial"
                    width="1600"
                    height="1066"
                    class="xv-parallax-img"
                    loading="lazy"
                    decoding="async"
                    data-parallax-img
                >
            </div>
        </section>


        {{-- 2) MENSAJE EMOCIONAL (Valentina) --}}
        <section id="mensaje-valentina" class="xv-message-section reveal" aria-label="Mensaje de Valentina">
            <div class="xv-message-card">

                <span class="message-kicker">{{ $mensajeKicker }}</span>

                <h2>{{ $mensajeTitulo }}</h2>

                <div class="message-divider" aria-hidden="true">
                    <span></span><i></i><span></span>
                </div>

                <p>
                    Con mucha ilusión,
                    <strong>{{ $nombreCompleto }}</strong>
                    quiere compartir contigo una noche llena de alegría,
                    recuerdos y momentos inolvidables.
                </p>

            </div>
        </section>





        {{-- 3) GALERÍA / CARRUSEL --}}
        <section id="galeria" class="xv-gallery-section reveal" aria-label="Galería de recuerdos">
            <div class="xv-gallery-card">

                <div class="gallery-heading">
                    <span class="section-kicker">Galería</span>
                    <h2>{{ $galeriaTitulo }}</h2>
                    <p>{{ $galeriaSubtitulo }}</p>
                </div>

                <div class="gallery-slider" data-gallery>
                    <button class="gallery-btn gallery-btn-prev" type="button" aria-label="Foto anterior">‹</button>

                    <div class="gallery-frame">
                        <img
                            id="galleryImage"
                            src="{{ asset($galeriaImagenes[0]) }}"
                            alt="Galería de {{ $nombre }}"
                        >
                    </div>

                    <button class="gallery-btn gallery-btn-next" type="button" aria-label="Foto siguiente">›</button>
                </div>

                <div class="gallery-dots" id="galleryDots" aria-label="Selector de fotos"></div>

                <div class="gallery-thumbs" id="galleryThumbs" aria-label="Miniaturas"></div>

            </div>
        </section>


        {{-- 4) IGLESIA + GOOGLE MAPS --}}
        <section id="iglesia" class="invite-section reveal" aria-label="Iglesia">
            <div class="section-card">
                <p class="eyebrow">Dónde</p>
                <h2 class="section-title">Iglesia</h2>
                <div class="section-divider" aria-hidden="true"></div>

                <h3 class="ubicacion-name">{{ $iglesiaNombre }}</h3>
                <p class="ubicacion-addr">{{ $iglesiaDireccion }}</p>

                <div class="ubicacion-cta">
                    <a href="{{ $iglesiaMapsUrl }}" target="_blank" rel="noopener" class="btn btn-primary">
                        Ver ubicación
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M7 17L17 7M9 7h8v8"/>
                        </svg>
                    </a>
                </div>

                <div class="ubicacion-map">
                    <iframe
                        src="{{ $iglesiaMapsEmbed }}"
                        title="Mapa de {{ $iglesiaNombre }}"
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                        allowfullscreen
                    ></iframe>
                </div>
            </div>
        </section>


        {{-- 5) PADRINOS --}}
        <section id="padrinos" class="xv-sponsors-section reveal" aria-label="Padrinos">
            <div class="xv-sponsors-card">

                <span class="section-kicker">Con mucho cariño</span>
                <h2>Familia y padrinos</h2>

                <div class="sponsors-divider" aria-hidden="true">
                    <span></span><i></i><span></span>
                </div>

                <p class="sponsors-intro">
                    Gracias por acompañarme y formar parte de este momento tan especial.
                </p>

                <div class="sponsors-list">

                    <div class="sponsor-item sponsor-parents">
                        <span>Padres</span>
                        <h3>Dailly García Pérez</h3>
                        <small>&amp;</small>
                        <h3>José Luís Franco Osalde</h3>
                    </div>

                    <div class="sponsor-item">
                        <span>Padrinos</span>
                        <h3>Daniella Traconis Alcocer</h3>
                        <small>&amp;</small>
                        <h3>Enrique Massa Pérez</h3>
                    </div>

                    <div class="sponsor-item">
                        <span>Padrinos</span>
                        <h3>Nelvy Gabriela Franco Ceballos</h3>
                        <small>&amp;</small>
                        <h3>Moisés Ramón López López</h3>
                    </div>

                </div>

            </div>
        </section>


        {{-- 6) SALÓN DE FIESTAS + GOOGLE MAPS --}}
        <section id="salon" class="invite-section reveal" aria-label="Salón de fiestas">
            <div class="section-card">
                <p class="eyebrow">Recepción</p>
                <h2 class="section-title">Salón de fiestas</h2>
                <div class="section-divider" aria-hidden="true"></div>

                <h3 class="ubicacion-name">{{ $lugar }}</h3>
                <p class="ubicacion-addr">{{ $direccion }}</p>

                <div class="ubicacion-cta">
                    <a href="{{ $mapsUrl }}" target="_blank" rel="noopener" class="btn btn-primary">
                        Ver ubicación
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M7 17L17 7M9 7h8v8"/>
                        </svg>
                    </a>
                </div>

                <div class="ubicacion-map">
                    <iframe
                        src="{{ $mapsEmbed }}"
                        title="Mapa de {{ $lugar }}"
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                        allowfullscreen
                    ></iframe>
                </div>
            </div>
        </section>


        {{-- 7) DRESS CODE --}}
        <section id="dress-code" class="invite-section reveal" aria-label="Dress code">
            <div class="section-card dress-card">
                <p class="eyebrow">Vestimenta</p>
                <h2 class="section-title">Dress code</h2>
                <div class="section-divider" aria-hidden="true"></div>

                {{-- Mensaje 1: dress code a tu estilo --}}
                <p class="dress-main">Dress code a tu estilo</p>

                {{-- Mensaje 2: solo el rosa está reservado para la Xvañera --}}
                <div class="dress-reserved" role="note">
                    <div class="dress-reserved-circle-wrap" aria-hidden="true">
                        <span class="dress-reserved-circle" style="background:#F7C9D6"></span>
                        <svg class="dress-reserved-x" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round">
                            <line x1="6" y1="6" x2="18" y2="18"/>
                            <line x1="6" y1="18" x2="18" y2="6"/>
                        </svg>
                    </div>
                    <div class="dress-reserved-text">
                        <strong>Solo el rosa está reservado para la quinceañera</strong>
                    </div>
                </div>
            </div>
        </section>


        {{-- 8) LLUVIA DE SOBRES --}}
        <section id="lluvia-sobres" class="invite-section reveal" aria-label="Lluvia de sobres">
            <div class="section-card lluvia-card">
                <p class="eyebrow">Detalle</p>
                <h2 class="section-title">Lluvia de sobres</h2>
                <div class="section-divider" aria-hidden="true"></div>

                <p class="lluvia-text">{{ $lluviaCuerpo }}</p>

                {{-- Sobre minimalista hecho con CSS puro --}}
                <div class="sobre-card" aria-hidden="true">
                    <span class="sobre-heart">&hearts;</span>
                </div>

                <p class="lluvia-cierre">{{ $lluviaCierre }}</p>

                {{-- Botón opcional de datos bancarios (solo si hay datos reales)
                <a href="#" target="_blank" rel="noopener" class="btn btn-outline lluvia-btn">
                    Ver datos bancarios
                </a>
                --}}
            </div>
        </section>


        {{-- 9) CONFIRMACIÓN DE ASISTENCIA --}}
        <section id="confirmacion" class="invite-section reveal" aria-label="Confirmar asistencia">
            <div class="section-card confirm-card">
                <p class="eyebrow">RSVP</p>
                <h2 class="section-title">Confirma tu asistencia</h2>
                <div class="section-divider" aria-hidden="true"></div>
                <p class="confirm-text">
                    Nos encantará contar contigo en este día tan especial.
                </p>

                <div class="confirm-cta">
                    <button
                        type="button"
                        id="openConfirmModal"
                        class="btn btn-primary"
                        onclick="openConfirmModal()"
                    >
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M20 6L9 17l-5-5"/>
                        </svg>
                        Confirmar
                    </button>
                </div>
            </div>
        </section>


        {{-- 10) ENLACE PERSONALIZADO --}}
        <section id="enlace" class="invite-section reveal" aria-label="Compartir invitación">
            <div class="section-card">
                <p class="eyebrow">Comparte</p>
                <h2 class="section-title">Invitación digital</h2>
                <div class="section-divider" aria-hidden="true"></div>
                <p class="enlace-text">
                    Comparte esta invitación con el siguiente enlace:
                </p>

                

                <div class="enlace-cta">
                    <button type="button" class="btn btn-primary" onclick="copyInvitationLink()">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="9" y="9" width="13" height="13" rx="2" ry="2"/>
                            <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/>
                        </svg>
                        Copiar enlace
                    </button>
                </div>

                <p id="copyMessage" class="copy-message">Enlace copiado</p>
            </div>
        </section>


        {{-- 11) FOOTER --}}
        <footer class="invite-footer" aria-label="Pie de página">
            <div class="footer-line" aria-hidden="true"></div>
            <p class="footer-family">{{ $familia }}</p>
            <p class="footer-event">XV años · 2026</p>
        </footer>

    </main>


    {{-- ============================================================
         MODAL DE CONFIRMACIÓN
         Se muestra con openConfirmModal() y se cierra con
         closeConfirmModal(). El formulario hace POST a
         /confirmar-asistencia (route name: confirmacion.store).
         ============================================================ --}}
    <div
        id="confirmModal"
        class="confirm-modal"
        role="dialog"
        aria-modal="true"
        aria-labelledby="confirmModalTitle"
        aria-hidden="true"
        hidden
    >
        <div class="confirm-modal-backdrop" onclick="closeConfirmModal()"></div>

        <div class="confirm-modal-content" role="document">

            <button
                type="button"
                class="confirm-modal-close"
                onclick="closeConfirmModal()"
                aria-label="Cerrar"
            >&times;</button>

            {{-- Encabezado con datos de la invitación --}}
            <div class="confirm-modal-header">
                <span class="confirm-modal-kicker">Mis XV años</span>
                <h2 id="confirmModalTitle">{{ $nombre }}</h2>
                <div class="confirm-modal-divider" aria-hidden="true">
                    <span></span><i></i><span></span>
                </div>
                <p class="confirm-modal-date">{{ $fechaLarga }}</p>
                <p class="confirm-modal-time">
                    <span class="time-label">Recepción</span>{{ $horaRecepcion }}
                </p>
            </div>

            {{-- Estado: formulario --}}
            <div id="confirmFormState" class="confirm-form-state">
                <p class="confirm-modal-intro">
                    Confirma tu asistencia y nos ayudarás a tener todo listo para este día tan especial.
                </p>

                <form
                    action="{{ route('confirmacion.store') }}"
                    method="POST"
                    class="confirm-form"
                    id="confirmForm"
                    novalidate
                >
                    @csrf
                    <input type="hidden" name="ruta_invitacion" value="xv-valentina">

                    <label for="confirmNombre" class="confirm-label">Tu nombre</label>
                    <input
                        type="text"
                        id="confirmNombre"
                        name="nombre"
                        class="confirm-input"
                        placeholder="Escribe tu nombre completo"
                        required
                        minlength="3"
                        maxlength="120"
                        autocomplete="name"
                    >

                    <p id="confirmError" class="confirm-error" hidden></p>

                    <button type="submit" class="btn btn-primary confirm-submit" id="confirmSubmitBtn">
                        <span class="confirm-submit-label">Confirmar</span>
                        <span class="confirm-submit-spinner" aria-hidden="true"></span>
                    </button>
                </form>
            </div>

            {{-- Estado: éxito (se muestra tras envío OK) --}}
            <div id="confirmSuccessState" class="confirm-success-state" hidden>
                <div class="confirm-success-icon" aria-hidden="true">
                    <svg viewBox="0 0 52 52">
                        <circle class="confirm-check-bg" cx="26" cy="26" r="24" fill="none"/>
                        <path class="confirm-check-mark" fill="none" d="M14 27l8 8 16-18"/>
                    </svg>
                </div>
                <h3>¡Gracias por confirmar!</h3>
                <p>Te esperamos el <strong>{{ $fechaLarga }}</strong>.</p>
                <button type="button" class="btn btn-outline" onclick="closeConfirmModal()">
                    Cerrar
                </button>
            </div>

        </div>
    </div>


    {{-- ============================================================
         FAB DE MÚSICA (bottom-right)
         Aparece solo después de abrir la invitación
         ============================================================ --}}
    <button
        type="button"
        id="musicToggle"
        class="music-fab"
        onclick="toggleMusic()"
        aria-label="Pausar música"
    >
        {{-- Icono: nota musical activa --}}
        <svg class="icon-playing" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M9 18V5l12-2v13"/>
            <circle cx="6" cy="18" r="3"/>
            <circle cx="18" cy="16" r="3"/>
        </svg>
        {{-- Icono: nota tachada (pausado) --}}
        <svg class="icon-paused" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M9 18V5l12-2v13"/>
            <circle cx="6" cy="18" r="3"/>
            <circle cx="18" cy="16" r="3"/>
            <line x1="3" y1="3" x2="21" y2="21"/>
        </svg>
    </button>


    {{-- ============================================================
         AUDIO DE FONDO
         La música intenta arrancar al primer scroll/click/touch
         y, con certeza, al abrir la invitación.
         ============================================================ --}}
    <audio id="bgMusic" loop preload="auto">
        <source src="{{ asset('music/music.mp3') }}" type="audio/mpeg">
    </audio>


    {{-- ============================================================
         JS — música, openInvitation, countdown, reveal, copy
         ============================================================ --}}
    <script>
        // ---------------- Música ----------------
        let musicStarted = false;

        function startMusic() {
            if (musicStarted) return;

            const music = document.getElementById('bgMusic');
            if (!music) return;

            music.volume = 0.65;
            music.play()
                .then(() => { musicStarted = true; })
                .catch(() => { /* autoplay bloqueado / archivo no listo */ });
        }

        function toggleMusic() {
            const music = document.getElementById('bgMusic');
            const btn   = document.getElementById('musicToggle');
            if (!music) return;

            if (music.paused) {
                music.play().catch(() => {});
                btn?.classList.remove('is-paused');
                btn?.setAttribute('aria-label', 'Pausar música');
            } else {
                music.pause();
                btn?.classList.add('is-paused');
                btn?.setAttribute('aria-label', 'Reproducir música');
            }
        }

        // Música intenta arrancar con la primera interacción del usuario
        window.addEventListener('scroll',    startMusic, { once: true });
        window.addEventListener('click',     startMusic, { once: true });
        window.addEventListener('touchstart', startMusic, { once: true });

        // ---------------- Apertura de la invitación ----------------
        function openInvitation() {
            startMusic();

            const intro   = document.getElementById('intro');
            const content = document.getElementById('invitation-content');

            intro.classList.add('intro-hide');

            setTimeout(() => {
                intro.classList.add('hidden');
                content.classList.remove('hidden');
                document.body.classList.add('invitation-opened');

                // Sincronizar el FAB con el estado real del audio
                const music = document.getElementById('bgMusic');
                const btn   = document.getElementById('musicToggle');
                if (music && btn) {
                    if (music.paused) btn.classList.add('is-paused');
                    else btn.classList.remove('is-paused');
                }
            }, 600);
        }

        // ---------------- Galería / Carrusel ----------------
        (function () {
            'use strict';

            const galleryImages = [
                @foreach ($galeriaImagenes as $img)
                    "{{ asset($img) }}",
                @endforeach
            ];

            let currentGalleryIndex = 0;
            let galleryAutoplay = null;
            const GALLERY_AUTOPLAY_MS = 5000;

            const imageEl  = document.getElementById('galleryImage');
            const dotsEl   = document.getElementById('galleryDots');
            const thumbsEl = document.getElementById('galleryThumbs');

            if (!imageEl || !dotsEl || !thumbsEl || galleryImages.length === 0) return;

            const renderGalleryControls = () => {
                dotsEl.innerHTML = '';
                thumbsEl.innerHTML = '';

                galleryImages.forEach((src, index) => {
                    const dot = document.createElement('button');
                    dot.type = 'button';
                    dot.className = 'gallery-dot' + (index === currentGalleryIndex ? ' is-active' : '');
                    dot.setAttribute('aria-label', 'Ver foto ' + (index + 1));
                    dot.addEventListener('click', () => goToGallerySlide(index));
                    dotsEl.appendChild(dot);

                    const thumb = document.createElement('button');
                    thumb.type = 'button';
                    thumb.className = 'gallery-thumb' + (index === currentGalleryIndex ? ' is-active' : '');
                    thumb.setAttribute('aria-label', 'Ver foto ' + (index + 1));
                    const thumbImg = document.createElement('img');
                    thumbImg.src = src;
                    thumbImg.alt = 'Miniatura ' + (index + 1);
                    thumbImg.loading = 'lazy';
                    thumb.appendChild(thumbImg);
                    thumb.addEventListener('click', () => goToGallerySlide(index));
                    thumbsEl.appendChild(thumb);
                });
            };

            const goToGallerySlide = (index) => {
                currentGalleryIndex = ((index % galleryImages.length) + galleryImages.length) % galleryImages.length;
                imageEl.classList.add('is-changing');
                setTimeout(() => {
                    imageEl.src = galleryImages[currentGalleryIndex];
                    imageEl.classList.remove('is-changing');
                    renderGalleryControls();
                }, 220);
                restartGalleryAutoplay();
            };

            const nextGallerySlide = () => goToGallerySlide(currentGalleryIndex + 1);
            const prevGallerySlide = () => goToGallerySlide(currentGalleryIndex - 1);

            const startGalleryAutoplay = () => {
                if (galleryAutoplay) clearInterval(galleryAutoplay);
                galleryAutoplay = setInterval(nextGallerySlide, GALLERY_AUTOPLAY_MS);
            };

            const restartGalleryAutoplay = () => {
                if (galleryAutoplay) clearInterval(galleryAutoplay);
                startGalleryAutoplay();
            };

            const stopGalleryAutoplay = () => {
                if (galleryAutoplay) {
                    clearInterval(galleryAutoplay);
                    galleryAutoplay = null;
                }
            };

            // Pausar autoplay si el usuario interactúa con teclado
            document.addEventListener('keydown', (e) => {
                if (!imageEl) return;
                if (e.key === 'ArrowLeft')  prevGallerySlide();
                if (e.key === 'ArrowRight') nextGallerySlide();
            });

            // Pausar autoplay cuando la pestaña no está visible
            document.addEventListener('visibilitychange', () => {
                if (document.hidden) stopGalleryAutoplay();
                else startGalleryAutoplay();
            });

            document.addEventListener('DOMContentLoaded', () => {
                const prev = document.querySelector('.gallery-btn-prev');
                const next = document.querySelector('.gallery-btn-next');

                if (prev) prev.addEventListener('click', prevGallerySlide);
                if (next) next.addEventListener('click', nextGallerySlide);

                renderGalleryControls();
                startGalleryAutoplay();
            });
        })();

        // ---------------- Hero countdown (con animación de tick) ----------------
        document.addEventListener('DOMContentLoaded', () => {
            const heroGrid = document.getElementById('hero-countdown');
            if (heroGrid) {
                const target = new Date(heroGrid.dataset.eventDate).getTime();
                const els = {
                    days:    heroGrid.querySelector('[data-unit="days"]'),
                    hours:   heroGrid.querySelector('[data-unit="hours"]'),
                    minutes: heroGrid.querySelector('[data-unit="minutes"]'),
                    seconds: heroGrid.querySelector('[data-unit="seconds"]'),
                };

                const pad = (n) => String(Math.max(0, n)).padStart(2, '0');

                // Actualiza el texto y dispara la animación de tick
                const setUnit = (el, value) => {
                    if (!el || el.textContent === value) return;
                    el.textContent = value;
                    el.classList.remove('tick');
                    // Forzar reflow para reiniciar la animación
                    void el.offsetWidth;
                    el.classList.add('tick');
                };

                const tick = () => {
                    const diff = target - Date.now();
                    const past = diff <= 0;
                    const totalSec = past ? 0 : Math.floor(diff / 1000);

                    setUnit(els.days,    pad(past ? 0 : Math.floor(totalSec / 86400)));
                    setUnit(els.hours,   pad(past ? 0 : Math.floor((totalSec % 86400) / 3600)));
                    setUnit(els.minutes, pad(past ? 0 : Math.floor((totalSec % 3600) / 60)));
                    setUnit(els.seconds, pad(totalSec % 60));
                };

                tick();
                setInterval(tick, 1000);
            }

            // ---------------- Reveal on scroll ----------------
            const revealEls = document.querySelectorAll('.reveal');
            if ('IntersectionObserver' in window) {
                const observer = new IntersectionObserver((entries) => {
                    entries.forEach((entry) => {
                        if (entry.isIntersecting) {
                            entry.target.classList.add('is-visible');
                        }
                    });
                }, { threshold: 0.18 });

                revealEls.forEach((el) => observer.observe(el));
            } else {
                // Fallback para navegadores viejos
                revealEls.forEach((el) => el.classList.add('is-visible'));
            }

            // ---------------- Copiar enlace ----------------
            window.copyInvitationLink = function () {
                const url = window.location.href;
                const copied = document.getElementById('copyMessage');
                const showCopied = () => {
                    if (!copied) return;
                    copied.classList.remove('hidden');
                    copied.classList.add('is-visible');
                    setTimeout(() => {
                        copied.classList.add('hidden');
                        copied.classList.remove('is-visible');
                    }, 2000);
                };

                if (navigator.clipboard && window.isSecureContext) {
                    navigator.clipboard.writeText(url).then(showCopied).catch(() => {
                        fallbackCopy(url, showCopied);
                    });
                } else {
                    fallbackCopy(url, showCopied);
                }
            };

            function fallbackCopy(text, cb) {
                const ta = document.createElement('textarea');
                ta.value = text;
                ta.style.position = 'fixed';
                ta.style.opacity  = '0';
                document.body.appendChild(ta);
                ta.select();
                try { document.execCommand('copy'); cb(); } catch (_) {}
                document.body.removeChild(ta);
            }

            // ---------------- Modal de confirmación ----------------
            let lastFocusedElement = null;

            function openConfirmModal() {
                const modal = document.getElementById('confirmModal');
                if (!modal) return;
                lastFocusedElement = document.activeElement;
                modal.hidden = false;
                modal.setAttribute('aria-hidden', 'false');
                document.body.classList.add('confirm-modal-open');

                // Reset a estado formulario en cada apertura
                const formState    = document.getElementById('confirmFormState');
                const successState = document.getElementById('confirmSuccessState');
                const errorEl      = document.getElementById('confirmError');
                const input        = document.getElementById('confirmNombre');
                const submitBtn    = document.getElementById('confirmSubmitBtn');
                if (formState)    formState.hidden = false;
                if (successState) successState.hidden = true;
                if (errorEl)      { errorEl.hidden = true; errorEl.textContent = ''; }
                if (input)        { input.classList.remove('is-invalid'); input.value = ''; }
                if (submitBtn)    { submitBtn.classList.remove('is-loading'); submitBtn.disabled = false; }

                // Foco al input después de la animación
                setTimeout(() => input && input.focus(), 80);
            }

            function closeConfirmModal() {
                const modal = document.getElementById('confirmModal');
                if (!modal) return;
                modal.hidden = true;
                modal.setAttribute('aria-hidden', 'true');
                document.body.classList.remove('confirm-modal-open');
                if (lastFocusedElement && typeof lastFocusedElement.focus === 'function') {
                    lastFocusedElement.focus();
                }
            }

            // Cerrar con Escape
            document.addEventListener('keydown', (e) => {
                const modal = document.getElementById('confirmModal');
                if (e.key === 'Escape' && modal && !modal.hidden) {
                    closeConfirmModal();
                }
            });

            // Exponer funciones al scope global (los onclick="" las necesitan)
            window.openConfirmModal  = openConfirmModal;
            window.closeConfirmModal = closeConfirmModal;

            // Envío del formulario vía fetch (sin recargar la página)
            const confirmForm = document.getElementById('confirmForm');
            if (confirmForm) {
                confirmForm.addEventListener('submit', async (e) => {
                    e.preventDefault();

                    const formData = new FormData(confirmForm);
                    const submitBtn = document.getElementById('confirmSubmitBtn');
                    const errorEl   = document.getElementById('confirmError');
                    const input     = document.getElementById('confirmNombre');

                    // Validación cliente
                    const nombre = (formData.get('nombre') || '').toString().trim();
                    if (nombre.length < 3) {
                        if (input)    input.classList.add('is-invalid');
                        if (errorEl) {
                            errorEl.textContent = 'Por favor escribe tu nombre (mínimo 3 caracteres).';
                            errorEl.hidden = false;
                        }
                        if (input) input.focus();
                        return;
                    }
                    if (input)    input.classList.remove('is-invalid');
                    if (errorEl)  { errorEl.hidden = true; errorEl.textContent = ''; }

                    if (submitBtn) {
                        submitBtn.classList.add('is-loading');
                        submitBtn.disabled = true;
                    }

                    try {
                        const response = await fetch(confirmForm.action, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                            },
                            credentials: 'same-origin',
                        });

                        const data = await response.json().catch(() => ({}));

                        if (response.ok) {
                            // Éxito
                            document.getElementById('confirmFormState').hidden    = true;
                            document.getElementById('confirmSuccessState').hidden = false;
                        } else if (response.status === 422 && data.errors) {
                            // Error de validación
                            const msg = Object.values(data.errors).flat().join(' ');
                            if (input)    input.classList.add('is-invalid');
                            if (errorEl)  { errorEl.textContent = msg || 'Verifica los datos e inténtalo de nuevo.'; errorEl.hidden = false; }
                            if (submitBtn) { submitBtn.classList.remove('is-loading'); submitBtn.disabled = false; }
                        } else {
                            // Otro error
                            if (errorEl)  { errorEl.textContent = 'No pudimos registrar tu confirmación. Inténtalo de nuevo.'; errorEl.hidden = false; }
                            if (submitBtn) { submitBtn.classList.remove('is-loading'); submitBtn.disabled = false; }
                        }
                    } catch (err) {
                        if (errorEl)  { errorEl.textContent = 'Sin conexión. Verifica tu red e inténtalo de nuevo.'; errorEl.hidden = false; }
                        if (submitBtn) { submitBtn.classList.remove('is-loading'); submitBtn.disabled = false; }
                    }
                });
            }
        });
    </script>
</body>
</html>
