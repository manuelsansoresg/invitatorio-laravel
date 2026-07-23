<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>{{ $title ?? 'Panel admin' }} | Invitatorio</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
    @vite(['resources/css/app.css'])
    @livewireStyles
</head>
<body class="min-h-screen bg-cream-bg text-text-dark antialiased">
    <div class="min-h-screen">
        <header class="border-b border-border-soft bg-white/90">
            <div class="mx-auto flex w-full max-w-6xl flex-col gap-4 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
                <a href="{{ auth()->user()?->isAdmin() ? route('admin.dashboard') : route('panel.confirmados.index') }}" class="flex items-center gap-3">
                    <img src="{{ asset('images/invitatorio.png') }}" alt="Invitatorio" class="h-10 w-10 rounded-full">
                    <span class="font-display text-lg font-extrabold">Panel Invitatorio</span>
                </a>

                @auth
                    <div class="flex flex-wrap items-center gap-3 text-sm text-text-gray">
                        <a href="{{ route('panel.confirmados.index') }}" class="font-semibold text-purple-brand hover:text-orange-brand">Confirmados</a>
                        @if (auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="font-semibold text-purple-brand hover:text-orange-brand">Admin</a>
                        @endif
                        <span>{{ auth()->user()->name }}</span>
                        <span class="rounded-full bg-purple-soft px-3 py-1 font-semibold text-purple-brand">{{ auth()->user()->role }}</span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="rounded-md border border-border-soft px-3 py-2 font-semibold text-text-dark transition hover:border-orange-brand hover:text-orange-brand">
                                Salir
                            </button>
                        </form>
                    </div>
                @endauth
            </div>
        </header>

        <main class="mx-auto w-full {{ ($wide ?? false) ? 'max-w-[1920px]' : 'max-w-6xl' }} px-5 py-8">
            @hasSection('content')
                @yield('content')
            @else
                {{ $slot ?? '' }}
            @endif
        </main>
    </div>
    @livewireScripts
</body>
</html>
