@extends('layouts.admin', ['title' => 'Entrar'])

@section('content')
    <section class="mx-auto max-w-md">
        <div class="mb-8 text-center">
            <p class="text-sm font-semibold uppercase tracking-[0.18em] text-orange-brand">Acceso administrativo</p>
            <h1 class="mt-3 font-display text-3xl font-extrabold text-purple-dark">Entrar al panel</h1>
        </div>

        <form method="POST" action="{{ route('login.store') }}" class="rounded-lg border border-border-soft bg-white p-6 shadow-sm">
            @csrf

            <div class="space-y-5">
                <div>
                    <label for="email" class="block text-sm font-semibold text-text-dark">Correo</label>
                    <input
                        id="email"
                        name="email"
                        type="email"
                        value="{{ old('email') }}"
                        autocomplete="email"
                        required
                        autofocus
                        class="mt-2 w-full rounded-md border border-border-soft bg-white px-4 py-3 text-sm outline-none transition focus:border-orange-brand focus:ring-2 focus:ring-orange-soft"
                    >
                    @error('email')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-semibold text-text-dark">Contraseña</label>
                    <input
                        id="password"
                        name="password"
                        type="password"
                        autocomplete="current-password"
                        required
                        class="mt-2 w-full rounded-md border border-border-soft bg-white px-4 py-3 text-sm outline-none transition focus:border-orange-brand focus:ring-2 focus:ring-orange-soft"
                    >
                    @error('password')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <label class="flex items-center gap-2 text-sm text-text-gray">
                    <input type="checkbox" name="remember" value="1" class="rounded border-border-soft text-purple-brand focus:ring-orange-brand">
                    Mantener sesión iniciada
                </label>

                <button type="submit" class="w-full rounded-md bg-purple-brand px-4 py-3 text-sm font-bold text-white transition hover:bg-purple-dark">
                    Entrar
                </button>
            </div>
        </form>
    </section>
@endsection
