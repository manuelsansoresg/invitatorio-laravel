@extends('layouts.admin', ['title' => 'Panel admin'])

@section('content')
    <div class="mb-8 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.18em] text-orange-brand">Administración</p>
            <h1 class="mt-2 font-display text-3xl font-extrabold text-purple-dark">Invitaciones y usuarios</h1>
        </div>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('panel.confirmados.index') }}" class="text-sm font-semibold text-purple-brand hover:text-orange-brand">Ver confirmados</a>
            <a href="{{ url('/') }}" class="text-sm font-semibold text-purple-brand hover:text-orange-brand">Ver sitio público</a>
        </div>
    </div>

    @if (session('status'))
        <div class="mb-6 rounded-md border border-green-200 bg-green-50 px-4 py-3 text-sm font-semibold text-green-800">
            {{ session('status') }}
        </div>
    @endif

    <section class="mb-10">
        <div class="mb-4 flex items-center justify-between gap-4">
            <h2 class="font-display text-xl font-bold text-text-dark">Invitaciones disponibles</h2>
            <span class="rounded-full bg-orange-soft px-3 py-1 text-sm font-semibold text-orange-intense">{{ $invitaciones->count() }} disponibles</span>
        </div>

        <div class="overflow-hidden rounded-lg border border-border-soft bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-border-soft text-sm">
                    <thead class="bg-purple-soft/70 text-left text-xs font-bold uppercase tracking-wide text-purple-dark">
                        <tr>
                            <th class="px-4 py-3">Nombre</th>
                            <th class="px-4 py-3">Ruta</th>
                            <th class="px-4 py-3">Confirmaciones</th>
                            <th class="px-4 py-3">Cliente</th>
                            <th class="px-4 py-3">Creada</th>
                            <th class="px-4 py-3">Vista</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border-soft">
                        @forelse ($invitaciones as $invitacion)
                            <tr>
                                <td class="px-4 py-3 font-semibold text-text-dark">{{ $invitacion->nombre_completo }}</td>
                                <td class="px-4 py-3 text-text-gray">{{ $invitacion->ruta }}</td>
                                <td class="px-4 py-3 text-text-gray">{{ $invitacion->confirmaciones_count }}</td>
                                <td class="px-4 py-3">
                                    <form method="POST" action="{{ route('admin.invitaciones.cliente.update', $invitacion) }}" class="flex min-w-60 gap-2">
                                        @csrf
                                        @method('PATCH')
                                        <input
                                            name="cliente_email"
                                            type="email"
                                            value="{{ old('cliente_email', $invitacion->cliente_email) }}"
                                            placeholder="correo del cliente"
                                            class="w-full rounded-md border border-border-soft px-3 py-2 text-xs outline-none focus:border-orange-brand focus:ring-2 focus:ring-orange-soft"
                                        >
                                        <button type="submit" class="rounded-md bg-purple-brand px-3 py-2 text-xs font-bold text-white transition hover:bg-purple-dark">
                                            Guardar
                                        </button>
                                    </form>
                                </td>
                                <td class="px-4 py-3 text-text-gray">{{ $invitacion->created_at?->format('d/m/Y') }}</td>
                                <td class="px-4 py-3">
                                    <a href="{{ route('panel.confirmados.index') }}" class="mr-3 font-semibold text-purple-brand hover:text-orange-brand">
                                        Confirmados
                                    </a>
                                    <a href="{{ url('/invitacion/'.$invitacion->ruta) }}" class="font-semibold text-purple-brand hover:text-orange-brand" target="_blank" rel="noopener">
                                        Abrir
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-8 text-center text-text-gray">Todavía no hay invitaciones registradas.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <div class="grid gap-8 lg:grid-cols-[minmax(0,1fr)_minmax(320px,420px)]">
        <section>
            <h2 class="mb-4 font-display text-xl font-bold text-text-dark">Usuarios registrados</h2>
            <div class="overflow-hidden rounded-lg border border-border-soft bg-white shadow-sm">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-border-soft text-sm">
                        <thead class="bg-purple-soft/70 text-left text-xs font-bold uppercase tracking-wide text-purple-dark">
                            <tr>
                                <th class="px-4 py-3">Nombre</th>
                                <th class="px-4 py-3">Correo</th>
                                <th class="px-4 py-3">Rol</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border-soft">
                            @foreach ($users as $user)
                                <tr>
                                    <td class="px-4 py-3 font-semibold text-text-dark">{{ $user->name }}</td>
                                    <td class="px-4 py-3 text-text-gray">{{ $user->email }}</td>
                                    <td class="px-4 py-3">
                                        <span class="rounded-full bg-purple-soft px-3 py-1 text-xs font-bold text-purple-brand">{{ $user->role }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <section>
            <h2 class="mb-4 font-display text-xl font-bold text-text-dark">Crear usuario</h2>
            <form method="POST" action="{{ route('admin.users.store') }}" class="rounded-lg border border-border-soft bg-white p-5 shadow-sm">
                @csrf

                <div class="space-y-4">
                    <div>
                        <label for="name" class="block text-sm font-semibold text-text-dark">Nombre</label>
                        <input id="name" name="name" value="{{ old('name') }}" required class="mt-2 w-full rounded-md border border-border-soft px-3 py-2.5 text-sm outline-none focus:border-orange-brand focus:ring-2 focus:ring-orange-soft">
                        @error('name')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="new-email" class="block text-sm font-semibold text-text-dark">Correo</label>
                        <input id="new-email" name="email" type="email" value="{{ old('email') }}" required class="mt-2 w-full rounded-md border border-border-soft px-3 py-2.5 text-sm outline-none focus:border-orange-brand focus:ring-2 focus:ring-orange-soft">
                        @error('email')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="role" class="block text-sm font-semibold text-text-dark">Rol</label>
                        <select id="role" name="role" required class="mt-2 w-full rounded-md border border-border-soft px-3 py-2.5 text-sm outline-none focus:border-orange-brand focus:ring-2 focus:ring-orange-soft">
                            @foreach ($roles as $role)
                                <option value="{{ $role }}" @selected(old('role') === $role)>{{ ucfirst($role) }}</option>
                            @endforeach
                        </select>
                        @error('role')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="new-password" class="block text-sm font-semibold text-text-dark">Contraseña</label>
                        <input id="new-password" name="password" type="password" required class="mt-2 w-full rounded-md border border-border-soft px-3 py-2.5 text-sm outline-none focus:border-orange-brand focus:ring-2 focus:ring-orange-soft">
                        @error('password')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="password-confirmation" class="block text-sm font-semibold text-text-dark">Confirmar contraseña</label>
                        <input id="password-confirmation" name="password_confirmation" type="password" required class="mt-2 w-full rounded-md border border-border-soft px-3 py-2.5 text-sm outline-none focus:border-orange-brand focus:ring-2 focus:ring-orange-soft">
                    </div>

                    <button type="submit" class="w-full rounded-md bg-purple-brand px-4 py-3 text-sm font-bold text-white transition hover:bg-purple-dark">
                        Crear usuario
                    </button>
                </div>
            </form>
        </section>
    </div>
@endsection
