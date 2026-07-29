<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="robots" content="noindex, nofollow">
    <meta name="theme-color" content="#FFFDF8">
    <title>{{ $title ?? 'Checkout' }} · Invitatorio</title>
    <meta name="description" content="Pago seguro de tu invitación digital con Mercado Pago.">
    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/favicon.svg') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="min-h-screen bg-[#FFFDF8] text-[#18111F] antialiased">

    {{-- Header minimalista con logo + link de regreso --}}
    <header class="border-b border-[#F1E6D9] bg-white">
        <div class="mx-auto flex max-w-5xl items-center justify-between px-5 py-4 lg:px-8">
            <a href="{{ url('/') }}" class="inline-flex items-center gap-2.5" aria-label="Volver al inicio de Invitatorio">
                <img src="{{ asset('images/invitatorio_horizontal.png') }}"
                     alt="Invitatorio"
                     class="h-8 w-auto sm:h-9"
                     loading="eager" decoding="async">
            </a>
            <a href="{{ url('/') }}" class="hidden items-center gap-1.5 text-sm font-semibold text-[#5F5A66] transition-colors hover:text-[#EB7512] sm:inline-flex">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M19 12H5M12 19l-7-7 7-7"/>
                </svg>
                Volver al sitio
            </a>
        </div>
    </header>

    {{-- Contenido principal. Soporta tanto @extends/@section como <x-layouts.checkout>. --}}
    <main class="mx-auto w-full max-w-5xl px-5 py-10 lg:px-8 lg:py-14">
        @hasSection('content')
            @yield('content')
        @else
            {{ $slot ?? '' }}
        @endif
    </main>

    {{-- Footer minimalista --}}
    <footer class="border-t border-[#F1E6D9] bg-white">
        <div class="mx-auto flex max-w-5xl flex-col items-center justify-between gap-3 px-5 py-6 text-xs text-[#5F5A66] sm:flex-row lg:px-8">
            <p>© {{ date('Y') }} Invitatorio · Hecho con cariño en México.</p>
            <div class="flex items-center gap-2">
                <svg class="h-3.5 w-3.5 text-[#009EE3]" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 14.5l-4-4 1.41-1.41L11 13.67l5.59-5.59L18 9.5l-7 7z"/>
                </svg>
                <span>Pago seguro procesado con Mercado Pago</span>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
