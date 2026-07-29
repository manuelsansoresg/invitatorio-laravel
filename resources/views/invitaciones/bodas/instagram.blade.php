@php
    $invitacion = $invitacion ?? null;
    $isEditorPreview = request()->has('editor_preview');
    $blocksRelation = $isEditorPreview ? 'blocks' : 'activeBlocks';
    $blocks = $invitacion?->relationLoaded($blocksRelation)
        ? $invitacion->{$blocksRelation}->keyBy('tipo')
        : collect();

    $block = fn (string $tipo) => $blocks->get($tipo);
    $config = fn (string $tipo, string $key, mixed $default = null) => data_get($block($tipo)?->config_json ?? [], $key, $default);
    $isActive = fn (string $tipo) => ! $invitacion || $blocks->has($tipo);

    $nombre = $invitacion?->nombre ?: 'Ana & Mateo';
    $nombreCompleto = $invitacion?->nombre_completo ?: $nombre;
    $evento = $invitacion?->titulo ?: 'Nuestra boda';
    $subtitulo = $invitacion?->subtitulo ?: 'Nos casamos';
    $fechaCorta = $config('hero', 'fecha_corta', $invitacion?->fecha_evento?->translatedFormat('d · m · Y') ?: '14 · 11 · 2026');
    $fechaLarga = $config('hero', 'fecha_larga', $invitacion?->fecha_evento?->translatedFormat('l, d \\d\\e F \\d\\e Y') ?: 'Sábado, 14 de noviembre de 2026');
    $horaRecepcion = $config('hero', 'hora_recepcion', $invitacion?->hora_evento?->format('g:i A') ?: '6:30 PM');
    $eventDateIso = $config(
        'cuenta_regresiva',
        'event_date_iso',
        ($invitacion?->fecha_evento?->toDateString() ?: '2026-11-14').'T'.($invitacion?->hora_evento?->format('H:i:s') ?: '18:30:00').'-06:00'
    );

    $heroConfig = $block('hero')?->config_json ?? [];
    $cleanImage = fn (?string $path, string $fallback) => filled($path) && $path !== '__deleted' ? $path : $fallback;
    $coverImage = $cleanImage(data_get($heroConfig, 'imagen_intro', $invitacion?->imagen_portada_path), 'images/templates/instagram/hero.webp');
    $heroImage = $cleanImage(data_get($heroConfig, 'imagen_hero'), 'images/templates/instagram/hero.webp');
    $featureImage = $cleanImage(data_get($heroConfig, 'imagen_parallax'), 'images/templates/instagram/details.webp');
    $receptionImage = 'images/templates/instagram/reception.webp';

    $gallery = $invitacion?->relationLoaded('gallery')
        ? $invitacion->gallery->where('activo', true)->sortBy('orden')->values()
        : collect();
    $galleryImages = $gallery->pluck('imagen_path')->filter()->values();
    if ($galleryImages->isEmpty()) {
        $galleryImages = collect([
            'images/templates/instagram/hero.webp',
            'images/templates/instagram/details.webp',
            'images/templates/instagram/reception.webp',
        ]);
    }

    $ceremonyName = $config('ubicacion', 'nombre', $block('ubicacion')?->titulo ?: 'Templo de San Juan');
    $ceremonyAddress = $config('ubicacion', 'direccion', 'Centro Histórico, Mérida, Yucatán');
    $ceremonyTime = $config('ubicacion', 'hora', '5:00 p.m.');
    $ceremonyMapsUrl = $config('ubicacion', 'maps_url', $invitacion?->maps_url ?: 'https://www.google.com/maps');

    $receptionName = $block('informacion_evento')?->titulo ?: ($invitacion?->lugar_nombre ?: 'Hacienda Santa Lucía');
    $receptionAddress = $invitacion?->lugar_direccion ?: 'Mérida, Yucatán';
    $receptionMapsUrl = $invitacion?->maps_url ?: 'https://www.google.com/maps';

    $messageKicker = $config('mensaje', 'kicker', 'Nuestra historia');
    $messageTitle = $block('mensaje')?->titulo ?: 'El mejor capítulo comienza contigo';
    $messageBody = $block('mensaje')?->contenido ?: 'Queremos celebrar el amor, la amistad y la alegría de encontrarnos. Gracias por ser parte de este día.';

    $sponsorGroups = $config('padrinos', 'grupos', [
        ['label' => 'Padres de la novia', 'nombres' => ['María y Alejandro']],
        ['label' => 'Padres del novio', 'nombres' => ['Lucía y Fernando']],
    ]);

    $dressCode = $invitacion?->dress_code ?: $config('dress_code', 'principal', 'Formal tropical');
    $dressDescription = $invitacion?->dress_code_descripcion ?: ($block('dress_code')?->contenido ?: 'Queremos verte elegante y sentirte tú. El blanco queda reservado para la novia.');
    $giftTitle = $block('mesa_regalos')?->titulo ?: 'Mesa de regalos';
    $giftBody = $block('mesa_regalos')?->contenido ?: 'Tu presencia es nuestro mejor regalo. Si deseas tener un detalle con nosotros, aquí encontrarás las opciones.';
    $musicPath = $config('musica', 'path', $invitacion?->musica_path);
    $familyMessage = $invitacion?->mensaje_footer ?: 'Con amor, Ana & Mateo';

    $colorPrimary = $invitacion?->color_primario ?: '#a6664a';
    $colorSecondary = $invitacion?->color_secundario ?: '#efe4d3';
    $colorAccent = $invitacion?->color_acento ?: '#83906f';

    $pageUrl = $invitacion ? route('invitaciones.show', $invitacion) : url('/invitacion/boda-instagram');
    $seoTitle = $evento.' de '.$nombre.' | Invitación';
    $seoDescription = 'Invitación de boda de '.$nombre.'. '.$fechaLarga.'.';
    $seoImage = asset($coverImage);
@endphp

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="theme-color" content="#161612">
    <meta name="description" content="{{ $seoDescription }}">
    <meta property="og:title" content="{{ $seoTitle }}">
    <meta property="og:description" content="{{ $seoDescription }}">
    <meta property="og:image" content="{{ $seoImage }}">
    <meta property="og:url" content="{{ $pageUrl }}">
    <meta property="og:type" content="website">
    <title>{{ $seoTitle }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Italiana&display=swap" rel="stylesheet">
    <link rel="preload" as="image" href="{{ asset($coverImage) }}">

    <style>
        :root {
            --primary: {{ $colorPrimary }};
            --secondary: {{ $colorSecondary }};
            --accent: {{ $colorAccent }};
            --ink: #fdfaf3;
            --paper: #f4ede2;
            --dark: #151611;
            --safe-top: max(14px, env(safe-area-inset-top));
            --safe-bottom: max(18px, env(safe-area-inset-bottom));
        }

        * { box-sizing: border-box; }
        html, body { width: 100%; height: 100%; margin: 0; overflow: hidden; }
        body {
            font-family: 'DM Sans', sans-serif;
            color: var(--ink);
            background: #11120f;
            -webkit-font-smoothing: antialiased;
            overscroll-behavior: none;
        }
        button, input, select, textarea { font: inherit; }
        button, a { -webkit-tap-highlight-color: transparent; }
        button { color: inherit; }
        [hidden] { display: none !important; }

        .ambient {
            position: fixed;
            inset: -40px;
            background:
                linear-gradient(rgba(9, 10, 8, .72), rgba(9, 10, 8, .86)),
                var(--ambient-image) center / cover;
            filter: blur(28px) saturate(.75);
            transform: scale(1.08);
            transition: background-image .7s ease;
        }

        .story-shell {
            position: relative;
            width: min(100%, 560px);
            height: 100%;
            margin: 0 auto;
            background: var(--dark);
            isolation: isolate;
            overflow: hidden;
            box-shadow: 0 0 80px rgba(0, 0, 0, .45);
        }

        .story-stack { position: absolute; inset: 0; }
        .story {
            --story-position: center;
            position: absolute;
            inset: 0;
            display: grid;
            place-items: center;
            padding: 142px 26px calc(var(--safe-bottom) + 62px);
            opacity: 0;
            visibility: hidden;
            transform: translateX(6%) scale(1.015);
            transition: opacity .4s ease, transform .48s cubic-bezier(.2,.75,.2,1), visibility .4s;
            background:
                linear-gradient(180deg, rgba(10, 11, 9, .34) 0%, rgba(10, 11, 9, .08) 25%, rgba(10, 11, 9, .24) 62%, rgba(10, 11, 9, .82) 100%),
                var(--story-bg) var(--story-position) / cover no-repeat;
        }
        .story::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at 20% 20%, transparent 0 34%, rgba(0,0,0,.2) 100%);
            pointer-events: none;
        }
        .story.is-active {
            opacity: 1;
            visibility: visible;
            transform: translateX(0) scale(1);
            z-index: 2;
        }
        .story.was-active { transform: translateX(-6%) scale(.985); }
        .story--light {
            color: #25241f;
            background:
                radial-gradient(circle at 88% 8%, color-mix(in srgb, var(--accent) 24%, transparent), transparent 34%),
                radial-gradient(circle at 0 100%, color-mix(in srgb, var(--primary) 18%, transparent), transparent 34%),
                linear-gradient(145deg, #f8f1e7, var(--secondary));
        }
        .story--light::before {
            background-image:
                linear-gradient(120deg, transparent 0 48%, rgba(255,255,255,.18) 48% 50%, transparent 50%),
                radial-gradient(circle at 15% 80%, transparent 0 9px, rgba(42,42,32,.08) 10px 11px, transparent 12px);
            background-size: auto, 38px 38px;
            opacity: .55;
        }
        .story--dark {
            background:
                radial-gradient(circle at 80% 12%, color-mix(in srgb, var(--accent) 45%, transparent), transparent 35%),
                linear-gradient(150deg, #283026 0%, #121510 70%);
        }
        .story-content {
            position: relative;
            z-index: 2;
            width: min(100%, 460px);
            max-height: 100%;
            overflow: auto;
            scrollbar-width: none;
            text-align: center;
        }
        .story-content::-webkit-scrollbar { display: none; }
        .story-content--bottom {
            align-self: end;
            padding-bottom: 3vh;
        }
        .story-kicker {
            margin: 0 0 14px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .26em;
            text-transform: uppercase;
            opacity: .74;
        }
        .story-title {
            margin: 0;
            font-family: 'Italiana', serif;
            font-weight: 400;
            font-size: clamp(42px, 11vw, 68px);
            line-height: .98;
            text-wrap: balance;
        }
        .story-title--medium { font-size: clamp(34px, 8.5vw, 52px); line-height: 1.04; }
        .story-copy {
            max-width: 390px;
            margin: 20px auto 0;
            font-size: 15px;
            line-height: 1.65;
            text-wrap: balance;
            opacity: .88;
        }
        .story-rule {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
            width: 150px;
            margin: 22px auto;
        }
        .story-rule::before, .story-rule::after {
            content: '';
            width: 56px;
            height: 1px;
            background: currentColor;
            opacity: .38;
        }
        .story-rule span { width: 5px; height: 5px; border: 1px solid currentColor; transform: rotate(45deg); }

        .story-header {
            position: absolute;
            z-index: 20;
            top: var(--safe-top);
            left: 14px;
            right: 14px;
            color: white;
            filter: drop-shadow(0 2px 12px rgba(0,0,0,.3));
        }
        .progress {
            display: flex;
            gap: 4px;
            height: 3px;
        }
        .progress-button {
            flex: 1 1 0;
            min-width: 0;
            height: 12px;
            margin-top: -4px;
            padding: 4px 0;
            border: 0;
            background: transparent;
            cursor: pointer;
        }
        .progress-track {
            display: block;
            height: 3px;
            border-radius: 99px;
            overflow: hidden;
            background: rgba(255,255,255,.34);
        }
        .progress-fill {
            display: block;
            width: 0;
            height: 100%;
            border-radius: inherit;
            background: #fff;
            transition: width .32s ease;
        }
        .progress-button.is-complete .progress-fill,
        .progress-button.is-current .progress-fill { width: 100%; }
        .profile-bar {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 14px;
        }
        .avatar {
            display: grid;
            place-items: center;
            width: 38px;
            height: 38px;
            flex: 0 0 38px;
            border-radius: 50%;
            border: 2px solid rgba(255,255,255,.92);
            background: var(--primary);
            font-family: 'Italiana', serif;
            font-size: 16px;
            box-shadow: 0 3px 18px rgba(0,0,0,.22);
        }
        .profile-meta { min-width: 0; flex: 1; }
        .profile-name { display: block; font-size: 13px; font-weight: 700; line-height: 1.1; }
        .profile-section { display: block; margin-top: 3px; font-size: 10px; opacity: .72; }
        .header-actions { display: flex; gap: 6px; }
        .icon-button {
            display: grid;
            place-items: center;
            width: 38px;
            height: 38px;
            padding: 0;
            border: 0;
            border-radius: 50%;
            background: rgba(13,13,11,.24);
            backdrop-filter: blur(12px);
            cursor: pointer;
        }
        .icon-button svg { width: 20px; height: 20px; }

        .tap-zone {
            position: absolute;
            z-index: 1;
            top: 112px;
            bottom: 62px;
            width: 22%;
            border: 0;
            background: transparent;
            pointer-events: none;
        }
        .tap-zone--prev { left: 0; }
        .tap-zone--next { right: 0; }

        .story-footer {
            position: absolute;
            z-index: 20;
            left: 18px;
            right: 18px;
            bottom: var(--safe-bottom);
            display: flex;
            align-items: center;
            justify-content: space-between;
            pointer-events: none;
            color: white;
            text-shadow: 0 2px 10px rgba(0,0,0,.35);
        }
        .story-hint {
            display: flex;
            align-items: center;
            gap: 8px;
            margin: 0;
            font-size: 10px;
            font-weight: 600;
            letter-spacing: .12em;
            text-transform: uppercase;
            opacity: .76;
        }
        .story-hint svg { width: 16px; height: 16px; animation: hint 1.8s ease-in-out infinite; }
        .story-count { font-size: 11px; font-variant-numeric: tabular-nums; opacity: .78; }
        @keyframes hint { 50% { transform: translateX(4px); } }

        .date-card {
            display: inline-grid;
            grid-template-columns: auto auto auto;
            align-items: center;
            gap: 14px;
            margin-top: 24px;
            padding: 12px 17px;
            border: 1px solid rgba(255,255,255,.35);
            border-radius: 999px;
            background: rgba(18,18,14,.18);
            backdrop-filter: blur(14px);
            font-size: 12px;
            font-weight: 600;
        }
        .date-card i { width: 3px; height: 3px; border-radius: 50%; background: currentColor; opacity: .65; }

        .countdown {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
            max-width: 350px;
            margin: 28px auto 0;
        }
        .countdown-cell {
            padding: 18px 10px;
            border: 1px solid rgba(37,36,31,.16);
            border-radius: 22px;
            background: rgba(255,255,255,.34);
            backdrop-filter: blur(8px);
        }
        .countdown-number {
            display: block;
            font-family: 'Italiana', serif;
            font-size: 42px;
            line-height: 1;
        }
        .countdown-label {
            display: block;
            margin-top: 7px;
            font-size: 9px;
            font-weight: 700;
            letter-spacing: .18em;
            text-transform: uppercase;
            opacity: .62;
        }

        .quote-mark {
            display: block;
            height: 54px;
            font-family: 'Italiana', serif;
            font-size: 94px;
            line-height: .8;
            opacity: .34;
        }

        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 8px;
            width: min(100%, 390px);
            margin: 22px auto 0;
        }
        .gallery-image {
            position: relative;
            aspect-ratio: .82;
            overflow: hidden;
            padding: 0;
            border: 0;
            border-radius: 18px;
            background: rgba(255,255,255,.12);
            cursor: zoom-in;
        }
        .gallery-image:first-child { grid-row: span 2; aspect-ratio: auto; }
        .gallery-image img { width: 100%; height: 100%; object-fit: cover; transition: transform .45s ease; }
        .gallery-image:hover img { transform: scale(1.035); }
        .gallery-image:nth-child(n+4) { display: none; }

        .info-card {
            width: min(100%, 390px);
            margin: 24px auto 0;
            padding: 22px;
            border: 1px solid rgba(255,255,255,.24);
            border-radius: 26px;
            background: rgba(18,19,15,.3);
            backdrop-filter: blur(18px);
        }
        .story--light .info-card {
            border-color: rgba(37,36,31,.13);
            background: rgba(255,255,255,.38);
        }
        .info-row { display: grid; grid-template-columns: 24px 1fr; gap: 12px; text-align: left; }
        .info-row + .info-row { margin-top: 16px; padding-top: 16px; border-top: 1px solid currentColor; border-color: rgba(127,127,110,.2); }
        .info-row svg { width: 20px; height: 20px; opacity: .72; }
        .info-label { display: block; font-size: 9px; font-weight: 700; letter-spacing: .18em; text-transform: uppercase; opacity: .58; }
        .info-value { display: block; margin-top: 4px; font-size: 14px; line-height: 1.45; }

        .story-button {
            position: relative;
            z-index: 8;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
            min-height: 48px;
            margin-top: 22px;
            padding: 12px 22px;
            border: 1px solid currentColor;
            border-radius: 999px;
            background: rgba(255,255,255,.08);
            color: inherit;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .13em;
            text-decoration: none;
            text-transform: uppercase;
            backdrop-filter: blur(12px);
            cursor: pointer;
            transition: transform .2s ease, background .2s ease;
        }
        .story-button:hover { transform: translateY(-2px); background: rgba(255,255,255,.16); }
        .story-button--solid { border-color: var(--primary); background: var(--primary); color: white; }
        .story-button svg { width: 18px; height: 18px; }

        .names-list {
            display: grid;
            gap: 12px;
            max-width: 380px;
            margin: 24px auto 0;
        }
        .names-card {
            padding: 15px 18px;
            border: 1px solid rgba(37,36,31,.12);
            border-radius: 18px;
            background: rgba(255,255,255,.34);
        }
        .names-label { margin: 0 0 7px; font-size: 9px; font-weight: 700; letter-spacing: .17em; text-transform: uppercase; opacity: .55; }
        .names-value { margin: 0; font-family: 'Italiana', serif; font-size: 20px; line-height: 1.35; }

        .dress-monogram {
            display: grid;
            place-items: center;
            width: 116px;
            height: 116px;
            margin: 25px auto;
            border: 1px solid currentColor;
            border-radius: 50%;
            font-family: 'Italiana', serif;
            font-size: 36px;
        }
        .dress-monogram::after { content: ''; position: absolute; width: 134px; height: 134px; border: 1px solid currentColor; border-radius: 50%; opacity: .18; }

        .chapters {
            position: absolute;
            z-index: 40;
            inset: 0;
            padding: calc(var(--safe-top) + 76px) 22px var(--safe-bottom);
            background: rgba(16,17,14,.9);
            backdrop-filter: blur(22px);
            opacity: 0;
            visibility: hidden;
            transition: opacity .25s, visibility .25s;
        }
        .chapters.is-open { opacity: 1; visibility: visible; }
        .chapters-top { display: flex; align-items: center; justify-content: space-between; }
        .chapters h2 { margin: 0; font-family: 'Italiana', serif; font-weight: 400; font-size: 32px; }
        .chapter-list {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 9px;
            max-height: calc(100% - 66px);
            margin-top: 22px;
            overflow: auto;
        }
        .chapter-button {
            min-height: 70px;
            padding: 13px;
            border: 1px solid rgba(255,255,255,.14);
            border-radius: 18px;
            background: rgba(255,255,255,.06);
            color: white;
            text-align: left;
            cursor: pointer;
        }
        .chapter-button.is-current { border-color: rgba(255,255,255,.72); background: rgba(255,255,255,.13); }
        .chapter-number { display: block; font-size: 9px; opacity: .5; }
        .chapter-label { display: block; margin-top: 7px; font-size: 12px; font-weight: 700; }

        .modal, .lightbox {
            position: fixed;
            z-index: 100;
            inset: 0;
            display: grid;
            place-items: center;
            padding: 20px;
            background: rgba(12,13,10,.78);
            backdrop-filter: blur(16px);
        }
        .modal-card {
            position: relative;
            width: min(100%, 440px);
            max-height: min(92vh, 720px);
            overflow: auto;
            padding: 30px 24px 24px;
            border-radius: 28px;
            background: #f5efe5;
            color: #24251f;
            box-shadow: 0 30px 90px rgba(0,0,0,.34);
        }
        .modal-close { position: absolute; top: 13px; right: 13px; background: rgba(37,36,31,.08); color: #24251f; }
        .modal-kicker { margin: 0; font-size: 10px; font-weight: 700; letter-spacing: .2em; text-transform: uppercase; color: var(--primary); }
        .modal-title { margin: 8px 0 0; font-family: 'Italiana', serif; font-size: 38px; font-weight: 400; }
        .modal-copy { margin: 12px 0 22px; font-size: 14px; line-height: 1.55; color: #66665d; }
        .form-grid { display: grid; gap: 14px; }
        .form-two { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
        .field-label { display: block; margin-bottom: 6px; font-size: 10px; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; }
        .field-control {
            width: 100%;
            min-height: 46px;
            padding: 11px 13px;
            border: 1px solid #d5cfc5;
            border-radius: 13px;
            outline: 0;
            background: #fffdf8;
            color: #24251f;
        }
        textarea.field-control { min-height: 82px; resize: vertical; }
        .field-control:focus { border-color: var(--primary); box-shadow: 0 0 0 3px color-mix(in srgb, var(--primary) 14%, transparent); }
        .form-error { margin: 0; color: #9f332c; font-size: 12px; }
        .submit-button { width: 100%; margin-top: 2px; }
        .success { padding: 25px 0 10px; text-align: center; }
        .success-icon { display: grid; place-items: center; width: 64px; height: 64px; margin: 0 auto 18px; border-radius: 50%; background: var(--accent); color: white; font-size: 28px; }
        .success h3 { margin: 0; font-family: 'Italiana', serif; font-size: 32px; font-weight: 400; }
        .success p { margin: 12px 0 0; color: #66665d; }

        .lightbox { padding: 0; }
        .lightbox img { width: min(100%, 760px); height: min(100%, 1000px); object-fit: contain; }
        .lightbox .icon-button { position: absolute; z-index: 2; top: max(18px, env(safe-area-inset-top)); right: 18px; }

        @media (min-width: 700px) {
            .story-shell { height: calc(100% - 40px); margin-top: 20px; border-radius: 26px; }
            .story { border-radius: 26px; }
        }
        @media (max-height: 720px) {
            .story { padding-top: 120px; padding-bottom: 54px; }
            .story-title { font-size: clamp(36px, 9vw, 52px); }
            .story-copy { margin-top: 14px; line-height: 1.45; }
            .info-card, .countdown, .names-list { margin-top: 16px; }
            .countdown-cell { padding: 12px 8px; }
            .countdown-number { font-size: 34px; }
            .gallery-grid { max-width: 310px; margin-top: 12px; }
        }
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { scroll-behavior: auto !important; animation: none !important; transition-duration: .01ms !important; }
        }
    </style>
</head>
<body>
    <div class="ambient" id="ambient" style="--ambient-image: url('{{ asset($coverImage) }}')" aria-hidden="true"></div>

    <main class="story-shell" id="storyShell" aria-label="Invitación de boda en formato historias">
        <header class="story-header">
            <nav class="progress" id="storyProgress" aria-label="Secciones de la invitación"></nav>
            <div class="profile-bar">
                <div class="avatar" aria-hidden="true">{{ mb_substr($nombre, 0, 1) }}</div>
                <div class="profile-meta">
                    <span class="profile-name">{{ $nombre }}</span>
                    <span class="profile-section" id="currentSectionLabel">Nuestra boda</span>
                </div>
                <div class="header-actions">
                    @if ($isActive('musica') && filled($musicPath))
                        <button class="icon-button" id="musicToggle" type="button" aria-label="Reproducir música">
                            <svg class="music-on" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                <path d="M9 18V5l11-2v13M6 21a3 3 0 1 0 0-6 3 3 0 0 0 0 6Zm11-2a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z"/>
                            </svg>
                        </button>
                    @endif
                    <button class="icon-button" id="chaptersToggle" type="button" aria-label="Ver todas las secciones" aria-expanded="false">
                        <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <circle cx="5" cy="12" r="1.8"/><circle cx="12" cy="12" r="1.8"/><circle cx="19" cy="12" r="1.8"/>
                        </svg>
                    </button>
                </div>
            </div>
        </header>

        <div class="story-stack" id="storyStack">
            <section class="story is-active" data-story data-label="Portada" data-image="{{ asset($coverImage) }}" style="--story-bg: url('{{ asset($coverImage) }}'); --story-position: center;">
                <div class="story-content story-content--bottom">
                    <p class="story-kicker">{{ $subtitulo }}</p>
                    <h1 class="story-title">{{ $nombre }}</h1>
                    <div class="story-rule" aria-hidden="true"><span></span></div>
                    <p class="story-copy">{{ $invitacion?->mensaje_principal ?: 'Hay historias que merecen celebrarse para siempre. Queremos vivir la nuestra contigo.' }}</p>
                    <div class="date-card"><span>{{ $fechaCorta }}</span><i></i><span>{{ $horaRecepcion }}</span></div>
                </div>
            </section>

            @if ($isActive('hero'))
                <section class="story" data-story data-label="La boda" data-image="{{ asset($heroImage) }}" style="--story-bg: url('{{ asset($heroImage) }}');">
                    <div class="story-content">
                        <p class="story-kicker">{{ $evento }}</p>
                        <h2 class="story-title">{{ $block('hero')?->titulo ?: 'Sí, para siempre' }}</h2>
                        <div class="story-rule" aria-hidden="true"><span></span></div>
                        <p class="story-copy">{{ $block('hero')?->contenido ?: 'Nos elegimos para compartir la vida y queremos que seas testigo de este comienzo.' }}</p>
                        <div class="date-card"><span>{{ $fechaLarga }}</span></div>
                    </div>
                </section>
            @endif

            @if ($isActive('cuenta_regresiva'))
                <section class="story story--light" data-story data-label="Cuenta regresiva" data-image="{{ asset($featureImage) }}">
                    <div class="story-content">
                        <p class="story-kicker">{{ $block('cuenta_regresiva')?->titulo ?: 'Falta muy poco' }}</p>
                        <h2 class="story-title story-title--medium">Contando los días</h2>
                        <p class="story-copy">{{ $block('cuenta_regresiva')?->contenido ?: 'Cada día nos acerca a la celebración.' }}</p>
                        <div class="countdown" id="countdown" data-date="{{ $eventDateIso }}" role="timer" aria-live="polite">
                            <div class="countdown-cell"><span class="countdown-number" data-unit="days">00</span><span class="countdown-label">Días</span></div>
                            <div class="countdown-cell"><span class="countdown-number" data-unit="hours">00</span><span class="countdown-label">Horas</span></div>
                            <div class="countdown-cell"><span class="countdown-number" data-unit="minutes">00</span><span class="countdown-label">Minutos</span></div>
                            <div class="countdown-cell"><span class="countdown-number" data-unit="seconds">00</span><span class="countdown-label">Segundos</span></div>
                        </div>
                    </div>
                </section>
            @endif

            @if ($isActive('mensaje'))
                <section class="story" data-story data-label="Nuestra historia" data-image="{{ asset($featureImage) }}" style="--story-bg: url('{{ asset($featureImage) }}'); --story-position: center;">
                    <div class="story-content">
                        <span class="quote-mark" aria-hidden="true">“</span>
                        <p class="story-kicker">{{ $messageKicker }}</p>
                        <h2 class="story-title story-title--medium">{{ $messageTitle }}</h2>
                        <p class="story-copy">{{ $messageBody }}</p>
                    </div>
                </section>
            @endif

            @if ($isActive('galeria') && $galleryImages->isNotEmpty())
                <section class="story story--dark" data-story data-label="Momentos" data-image="{{ asset($galleryImages->first()) }}">
                    <div class="story-content">
                        <p class="story-kicker">Nuestra galería</p>
                        <h2 class="story-title story-title--medium">{{ $block('galeria')?->titulo ?: 'Momentos nuestros' }}</h2>
                        <div class="gallery-grid">
                            @foreach ($galleryImages->take(3) as $index => $image)
                                <button class="gallery-image" type="button" data-lightbox-image="{{ asset($image) }}" aria-label="Ampliar foto {{ $index + 1 }}">
                                    <img src="{{ asset($image) }}" alt="{{ data_get($gallery->get($index), 'titulo', 'Recuerdo de '.$nombre) }}" loading="lazy">
                                </button>
                            @endforeach
                        </div>
                        <p class="story-copy">{{ $block('galeria')?->contenido ?: 'Un vistazo a la historia que nos trajo hasta aquí.' }}</p>
                    </div>
                </section>
            @endif

            @if ($isActive('ubicacion'))
                <section class="story" data-story data-label="Ceremonia" data-image="{{ asset($coverImage) }}" style="--story-bg: url('{{ asset($coverImage) }}'); --story-position: 32% center;">
                    <div class="story-content">
                        <p class="story-kicker">{{ $config('ubicacion', 'kicker', 'Ceremonia') }}</p>
                        <h2 class="story-title story-title--medium">{{ $ceremonyName }}</h2>
                        <div class="info-card">
                            <div class="info-row">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>
                                <div><span class="info-label">Hora</span><span class="info-value">{{ $ceremonyTime }}</span></div>
                            </div>
                            <div class="info-row">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M20 10c0 5-8 11-8 11S4 15 4 10a8 8 0 1 1 16 0Z"/><circle cx="12" cy="10" r="2.5"/></svg>
                                <div><span class="info-label">Dirección</span><span class="info-value">{{ $ceremonyAddress }}</span></div>
                            </div>
                        </div>
                        <a class="story-button" href="{{ $ceremonyMapsUrl }}" target="_blank" rel="noopener">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M20 10c0 5-8 11-8 11S4 15 4 10a8 8 0 1 1 16 0Z"/><circle cx="12" cy="10" r="2.5"/></svg>
                            Cómo llegar
                        </a>
                    </div>
                </section>
            @endif

            @if ($isActive('padrinos'))
                <section class="story story--light" data-story data-label="Familia" data-image="{{ asset($featureImage) }}">
                    <div class="story-content">
                        <p class="story-kicker">{{ $config('padrinos', 'kicker', 'Con gratitud') }}</p>
                        <h2 class="story-title story-title--medium">{{ $block('padrinos')?->titulo ?: 'Junto a quienes amamos' }}</h2>
                        <p class="story-copy">{{ $block('padrinos')?->contenido ?: 'Gracias por enseñarnos a amar y acompañarnos hasta este día.' }}</p>
                        <div class="names-list">
                            @foreach ($sponsorGroups as $group)
                                <div class="names-card">
                                    <p class="names-label">{{ data_get($group, 'label', 'Familia') }}</p>
                                    <p class="names-value">{{ implode(' · ', data_get($group, 'nombres', [])) }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </section>
            @endif

            @if ($isActive('informacion_evento'))
                <section class="story" data-story data-label="Recepción" data-image="{{ asset($receptionImage) }}" style="--story-bg: url('{{ asset($receptionImage) }}');">
                    <div class="story-content">
                        <p class="story-kicker">{{ $config('informacion_evento', 'kicker', 'Después de la ceremonia') }}</p>
                        <h2 class="story-title story-title--medium">{{ $receptionName }}</h2>
                        <p class="story-copy">{{ $block('informacion_evento')?->contenido ?: 'Cena, brindis y una pista lista para celebrar juntos.' }}</p>
                        <div class="info-card">
                            <div class="info-row">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>
                                <div><span class="info-label">Recepción</span><span class="info-value">{{ $horaRecepcion }}</span></div>
                            </div>
                            <div class="info-row">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M20 10c0 5-8 11-8 11S4 15 4 10a8 8 0 1 1 16 0Z"/><circle cx="12" cy="10" r="2.5"/></svg>
                                <div><span class="info-label">Lugar</span><span class="info-value">{{ $receptionAddress }}</span></div>
                            </div>
                        </div>
                        <a class="story-button" href="{{ $receptionMapsUrl }}" target="_blank" rel="noopener">Abrir mapa</a>
                    </div>
                </section>
            @endif

            @if ($isActive('dress_code'))
                <section class="story story--dark" data-story data-label="Dress code" data-image="{{ asset($featureImage) }}">
                    <div class="story-content">
                        <p class="story-kicker">{{ $config('dress_code', 'kicker', 'Código de vestimenta') }}</p>
                        <div class="dress-monogram" aria-hidden="true">A·M</div>
                        <h2 class="story-title story-title--medium">{{ $dressCode }}</h2>
                        <p class="story-copy">{{ $dressDescription }}</p>
                    </div>
                </section>
            @endif

            @if ($isActive('mesa_regalos'))
                <section class="story story--light" data-story data-label="Regalos" data-image="{{ asset($featureImage) }}">
                    <div class="story-content">
                        <p class="story-kicker">{{ $config('mesa_regalos', 'kicker', 'Un detalle') }}</p>
                        <h2 class="story-title story-title--medium">{{ $giftTitle }}</h2>
                        <div class="story-rule" aria-hidden="true"><span></span></div>
                        <p class="story-copy">{{ $giftBody }}</p>
                        @if (filled($config('mesa_regalos', 'url')))
                            <a class="story-button story-button--solid" href="{{ $config('mesa_regalos', 'url') }}" target="_blank" rel="noopener">Ver mesa de regalos</a>
                        @else
                            <p class="story-copy">{{ $config('mesa_regalos', 'cierre', 'Gracias por celebrar esta nueva etapa con nosotros.') }}</p>
                        @endif
                    </div>
                </section>
            @endif

            @if ($isActive('whatsapp'))
                <section class="story" data-story data-label="Confirmación" data-image="{{ asset($receptionImage) }}" style="--story-bg: url('{{ asset($receptionImage) }}');">
                    <div class="story-content">
                        <p class="story-kicker">{{ $config('whatsapp', 'kicker', 'RSVP') }}</p>
                        <h2 class="story-title story-title--medium">{{ $block('whatsapp')?->titulo ?: '¿Celebras con nosotros?' }}</h2>
                        <p class="story-copy">{{ $block('whatsapp')?->contenido ?: 'Confirma tu asistencia y ayúdanos a preparar cada detalle.' }}</p>
                        <button class="story-button story-button--solid" type="button" data-open-confirm>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m5 12 4 4L19 6"/></svg>
                            Confirmar asistencia
                        </button>
                    </div>
                </section>
            @endif

            <section class="story story--dark" data-story data-label="Final" data-image="{{ asset($coverImage) }}">
                <div class="story-content">
                    <p class="story-kicker">{{ $fechaCorta }}</p>
                    <h2 class="story-title">Nos vemos pronto</h2>
                    <div class="story-rule" aria-hidden="true"><span></span></div>
                    <p class="story-copy">{{ $familyMessage }}</p>
                    <button class="story-button" type="button" data-restart>Volver al inicio</button>
                </div>
            </section>
        </div>

        <button class="tap-zone tap-zone--prev" type="button" data-prev aria-label="Sección anterior"></button>
        <button class="tap-zone tap-zone--next" type="button" data-next aria-label="Siguiente sección"></button>

        <footer class="story-footer">
            <p class="story-hint">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="m9 6 6 6-6 6"/></svg>
                Toca, desliza o usa las flechas
            </p>
            <span class="story-count" id="storyCount">1 / 1</span>
        </footer>

        <aside class="chapters" id="chapters" aria-hidden="true">
            <div class="chapters-top">
                <h2>Capítulos</h2>
                <button class="icon-button" id="chaptersClose" type="button" aria-label="Cerrar secciones">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="m6 6 12 12M18 6 6 18"/></svg>
                </button>
            </div>
            <div class="chapter-list" id="chapterList"></div>
        </aside>
    </main>

    @if ($isActive('whatsapp'))
        <div class="modal" id="confirmModal" role="dialog" aria-modal="true" aria-labelledby="confirmTitle" hidden>
            <div class="modal-card">
                <button class="icon-button modal-close" type="button" data-close-confirm aria-label="Cerrar">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="m6 6 12 12M18 6 6 18"/></svg>
                </button>
                <div id="confirmFormState">
                    <p class="modal-kicker">RSVP · {{ $fechaCorta }}</p>
                    <h2 class="modal-title" id="confirmTitle">Confirma tu asistencia</h2>
                    <p class="modal-copy">Cuéntanos si podremos celebrar contigo.</p>
                    <form class="form-grid" id="confirmForm" action="{{ route('confirmacion.store') }}" method="POST" novalidate>
                        @csrf
                        <input type="hidden" name="ruta_invitacion" value="{{ $invitacion?->ruta ?: 'boda-instagram' }}">
                        @if (! empty($invitadoToken))
                            <input type="hidden" name="invitado_token" value="{{ $invitadoToken }}">
                        @endif

                        @if (! empty($invitado))
                            <p class="field-hola">Hola, <strong>{{ $invitado->nombre }}</strong> — tienes <strong>{{ $invitado->lugares_asignados }} {{ $invitado->lugares_asignados == 1 ? 'lugar reservado' : 'lugares reservados' }}</strong>.</p>
                        @endif

                        <label>
                            <span class="field-label">Nombre completo</span>
                            <input class="field-control" id="confirmName" name="nombre" type="text" minlength="3" maxlength="120" {{ empty($invitado) ? 'required' : '' }} autocomplete="name" value="{{ old('nombre', $invitado->nombre ?? '') }}">
                        </label>
                        <div class="form-two">
                            <label>
                                <span class="field-label">¿Asistirás?</span>
                                <select class="field-control" name="asistira">
                                    <option value="1">Sí, asistiré</option>
                                    <option value="0">No podré asistir</option>
                                </select>
                            </label>
                            <label>
                                <span class="field-label">Personas</span>
                                <input class="field-control" name="numero_invitados" type="number" min="1" @if (! empty($invitado)) max="{{ $invitado->lugares_asignados }}" @else max="20" @endif value="{{ old('numero_invitados', $invitado->lugares_asignados ?? 1) }}">
                            </label>
                        </div>
                        <label>
                            <span class="field-label">Mensaje (opcional)</span>
                            <textarea class="field-control" name="mensaje" maxlength="1000" placeholder="Déjanos unas palabras"></textarea>
                        </label>
                        <p class="form-error" id="confirmError" hidden></p>
                        <button class="story-button story-button--solid submit-button" id="confirmSubmit" type="submit">Enviar confirmación</button>
                    </form>
                </div>
                <div class="success" id="confirmSuccess" hidden>
                    <div class="success-icon" aria-hidden="true">✓</div>
                    <h3>¡Gracias por responder!</h3>
                    <p>Tu confirmación quedó registrada. Nos vemos muy pronto.</p>
                    <button class="story-button story-button--solid" type="button" data-close-confirm>Cerrar</button>
                </div>
            </div>
        </div>
    @endif

    <div class="lightbox" id="lightbox" role="dialog" aria-modal="true" aria-label="Foto ampliada" hidden>
        <button class="icon-button" type="button" data-close-lightbox aria-label="Cerrar foto">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="m6 6 12 12M18 6 6 18"/></svg>
        </button>
        <img id="lightboxImage" src="" alt="Foto ampliada">
    </div>

    @if ($isActive('musica') && filled($musicPath))
        <audio id="backgroundMusic" loop preload="metadata">
            <source src="{{ asset($musicPath) }}">
        </audio>
    @endif

    <script>
        (() => {
            'use strict';

            const shell = document.getElementById('storyShell');
            const stories = [...document.querySelectorAll('[data-story]')];
            const progress = document.getElementById('storyProgress');
            const chapterList = document.getElementById('chapterList');
            const sectionLabel = document.getElementById('currentSectionLabel');
            const storyCount = document.getElementById('storyCount');
            const ambient = document.getElementById('ambient');
            const chapters = document.getElementById('chapters');
            const chaptersToggle = document.getElementById('chaptersToggle');
            let current = 0;
            let touchStartX = 0;
            let touchStartY = 0;
            let wheelLocked = false;

            const clampIndex = (index) => Math.max(0, Math.min(stories.length - 1, index));

            stories.forEach((story, index) => {
                const label = story.dataset.label || `Sección ${index + 1}`;

                const progressButton = document.createElement('button');
                progressButton.type = 'button';
                progressButton.className = 'progress-button';
                progressButton.setAttribute('aria-label', `Ir a ${label}`);
                progressButton.innerHTML = '<span class="progress-track"><span class="progress-fill"></span></span>';
                progressButton.addEventListener('click', () => goTo(index));
                progress.appendChild(progressButton);

                const chapterButton = document.createElement('button');
                chapterButton.type = 'button';
                chapterButton.className = 'chapter-button';
                chapterButton.innerHTML = `<span class="chapter-number">${String(index + 1).padStart(2, '0')}</span><span class="chapter-label">${label}</span>`;
                chapterButton.addEventListener('click', () => {
                    goTo(index);
                    closeChapters();
                });
                chapterList.appendChild(chapterButton);
            });

            const progressButtons = [...progress.children];
            const chapterButtons = [...chapterList.children];

            function goTo(index) {
                const nextIndex = clampIndex(index);
                stories.forEach((story, storyIndex) => {
                    story.classList.toggle('is-active', storyIndex === nextIndex);
                    story.classList.toggle('was-active', storyIndex < nextIndex);
                    story.setAttribute('aria-hidden', storyIndex === nextIndex ? 'false' : 'true');
                });
                progressButtons.forEach((button, buttonIndex) => {
                    button.classList.toggle('is-complete', buttonIndex < nextIndex);
                    button.classList.toggle('is-current', buttonIndex === nextIndex);
                    button.setAttribute('aria-current', buttonIndex === nextIndex ? 'step' : 'false');
                });
                chapterButtons.forEach((button, buttonIndex) => button.classList.toggle('is-current', buttonIndex === nextIndex));

                current = nextIndex;
                const active = stories[current];
                sectionLabel.textContent = active.dataset.label;
                storyCount.textContent = `${current + 1} / ${stories.length}`;
                ambient.style.setProperty('--ambient-image', `url("${active.dataset.image}")`);
            }

            const next = () => goTo(current + 1);
            const previous = () => goTo(current - 1);

            document.querySelector('[data-next]').addEventListener('click', next);
            document.querySelector('[data-prev]').addEventListener('click', previous);
            document.querySelector('[data-restart]').addEventListener('click', () => goTo(0));

            shell.addEventListener('click', event => {
                if (event.target.closest('a, button, input, select, textarea, .story-header, .story-footer, .chapters')) return;
                const bounds = shell.getBoundingClientRect();
                const relativeX = event.clientX - bounds.left;
                relativeX < bounds.width / 2 ? previous() : next();
            });

            shell.addEventListener('touchstart', (event) => {
                touchStartX = event.changedTouches[0].clientX;
                touchStartY = event.changedTouches[0].clientY;
            }, { passive: true });

            shell.addEventListener('touchend', (event) => {
                const deltaX = event.changedTouches[0].clientX - touchStartX;
                const deltaY = event.changedTouches[0].clientY - touchStartY;
                if (Math.abs(deltaX) > 45 && Math.abs(deltaX) > Math.abs(deltaY) * 1.2) {
                    deltaX < 0 ? next() : previous();
                }
            }, { passive: true });

            shell.addEventListener('wheel', (event) => {
                if (wheelLocked || Math.abs(event.deltaY) < 20) return;
                const scrollArea = event.target.closest('.story-content, .chapter-list, .modal-card');
                if (scrollArea) {
                    const canScrollDown = scrollArea.scrollTop + scrollArea.clientHeight < scrollArea.scrollHeight - 2;
                    const canScrollUp = scrollArea.scrollTop > 2;
                    if ((event.deltaY > 0 && canScrollDown) || (event.deltaY < 0 && canScrollUp)) return;
                }
                wheelLocked = true;
                event.deltaY > 0 ? next() : previous();
                window.setTimeout(() => wheelLocked = false, 550);
            }, { passive: true });

            document.addEventListener('keydown', (event) => {
                if (!document.getElementById('confirmModal')?.hidden || !document.getElementById('lightbox').hidden) {
                    if (event.key === 'Escape') closeOverlays();
                    return;
                }
                if (chapters.classList.contains('is-open')) {
                    if (event.key === 'Escape') closeChapters();
                    return;
                }
                if (['ArrowRight', 'ArrowDown', 'PageDown', ' '].includes(event.key)) {
                    event.preventDefault();
                    next();
                }
                if (['ArrowLeft', 'ArrowUp', 'PageUp'].includes(event.key)) {
                    event.preventDefault();
                    previous();
                }
                if (event.key === 'Home') goTo(0);
                if (event.key === 'End') goTo(stories.length - 1);
            });

            function openChapters() {
                chapters.classList.add('is-open');
                chapters.setAttribute('aria-hidden', 'false');
                chaptersToggle.setAttribute('aria-expanded', 'true');
            }
            function closeChapters() {
                chapters.classList.remove('is-open');
                chapters.setAttribute('aria-hidden', 'true');
                chaptersToggle.setAttribute('aria-expanded', 'false');
            }
            chaptersToggle.addEventListener('click', openChapters);
            document.getElementById('chaptersClose').addEventListener('click', closeChapters);

            const lightbox = document.getElementById('lightbox');
            const lightboxImage = document.getElementById('lightboxImage');
            document.querySelectorAll('[data-lightbox-image]').forEach(button => {
                button.addEventListener('click', () => {
                    lightboxImage.src = button.dataset.lightboxImage;
                    lightbox.hidden = false;
                });
            });
            document.querySelector('[data-close-lightbox]').addEventListener('click', () => {
                lightbox.hidden = true;
                lightboxImage.src = '';
            });
            lightbox.addEventListener('click', event => {
                if (event.target === lightbox) {
                    lightbox.hidden = true;
                    lightboxImage.src = '';
                }
            });

            const confirmModal = document.getElementById('confirmModal');
            const openConfirm = () => {
                if (!confirmModal) return;
                confirmModal.hidden = false;
                window.setTimeout(() => document.getElementById('confirmName')?.focus(), 50);
            };
            const closeConfirm = () => {
                if (confirmModal) confirmModal.hidden = true;
            };
            document.querySelectorAll('[data-open-confirm]').forEach(button => button.addEventListener('click', openConfirm));
            document.querySelectorAll('[data-close-confirm]').forEach(button => button.addEventListener('click', closeConfirm));
            confirmModal?.addEventListener('click', event => {
                if (event.target === confirmModal) closeConfirm();
            });

            function closeOverlays() {
                closeConfirm();
                lightbox.hidden = true;
                lightboxImage.src = '';
            }

            const form = document.getElementById('confirmForm');
            form?.addEventListener('submit', async event => {
                event.preventDefault();
                const error = document.getElementById('confirmError');
                const submit = document.getElementById('confirmSubmit');
                const name = document.getElementById('confirmName');
                error.hidden = true;

                if (name.value.trim().length < 3) {
                    error.textContent = 'Escribe tu nombre completo para continuar.';
                    error.hidden = false;
                    name.focus();
                    return;
                }

                submit.disabled = true;
                submit.textContent = 'Enviando…';
                try {
                    const response = await fetch(form.action, {
                        method: 'POST',
                        body: new FormData(form),
                        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                        credentials: 'same-origin',
                    });
                    const payload = await response.json().catch(() => ({}));
                    if (!response.ok) throw new Error(Object.values(payload.errors || {}).flat().join(' ') || payload.error || 'No pudimos guardar tu confirmación.');
                    document.getElementById('confirmFormState').hidden = true;
                    document.getElementById('confirmSuccess').hidden = false;
                } catch (exception) {
                    error.textContent = exception.message || 'No pudimos guardar tu confirmación. Inténtalo de nuevo.';
                    error.hidden = false;
                } finally {
                    submit.disabled = false;
                    submit.textContent = 'Enviar confirmación';
                }
            });

            const countdown = document.getElementById('countdown');
            if (countdown) {
                const target = new Date(countdown.dataset.date).getTime();
                const updateCountdown = () => {
                    const total = Math.max(0, Math.floor((target - Date.now()) / 1000));
                    const values = {
                        days: Math.floor(total / 86400),
                        hours: Math.floor((total % 86400) / 3600),
                        minutes: Math.floor((total % 3600) / 60),
                        seconds: total % 60,
                    };
                    Object.entries(values).forEach(([unit, value]) => {
                        const element = countdown.querySelector(`[data-unit="${unit}"]`);
                        if (element) element.textContent = String(value).padStart(2, '0');
                    });
                };
                updateCountdown();
                window.setInterval(updateCountdown, 1000);
            }

            const music = document.getElementById('backgroundMusic');
            const musicToggle = document.getElementById('musicToggle');
            musicToggle?.addEventListener('click', async () => {
                if (!music) return;
                if (music.paused) {
                    try {
                        await music.play();
                        musicToggle.setAttribute('aria-label', 'Pausar música');
                        musicToggle.style.background = 'rgba(166,102,74,.72)';
                    } catch (_) {}
                } else {
                    music.pause();
                    musicToggle.setAttribute('aria-label', 'Reproducir música');
                    musicToggle.style.background = '';
                }
            });

            goTo(0);
        })();
    </script>
</body>
</html>
