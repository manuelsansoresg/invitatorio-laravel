<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>{{ $title ?? 'Editor de invitación' }} | Invitatorio</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
    @vite(['resources/css/app.css'])
    @livewireStyles
</head>
<body class="m-0 overflow-hidden bg-white text-text-dark antialiased">
    {{ $slot }}
    @livewireScripts
</body>
</html>
