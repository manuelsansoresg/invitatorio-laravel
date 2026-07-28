<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" index="noindex, nofollow" />
    <title>{{ $title ?? 'Confirma tu asistencia' }} | Invitatorio</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
    @vite(['resources/css/app.css'])
    @livewireStyles
</head>
<body class="min-h-screen bg-gradient-to-br from-orange-brand via-orange-intense to-purple-dark text-text-dark antialiased">
    <main class="mx-auto flex min-h-screen w-full max-w-xl flex-col items-center justify-center px-5 py-10">
        <header class="mb-6 flex items-center gap-3 text-white">
            <img src="{{ asset('images/invitatorio.png') }}" alt="Invitatorio" class="h-12 w-12 rounded-full shadow-lg">
            <span class="font-display text-2xl font-extrabold">Invitatorio</span>
        </header>

        <div class="w-full rounded-2xl bg-white p-6 shadow-2xl sm:p-8">
            @hasSection('content')
                @yield('content')
            @endif
        </div>

        <footer class="mt-6 text-center text-xs text-white/80">
            Hecho con ♥ para que tu evento fluya.
        </footer>
    </main>
    @livewireScripts
</body>
</html>
