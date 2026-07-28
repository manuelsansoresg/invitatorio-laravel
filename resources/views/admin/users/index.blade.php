@extends('layouts.admin', ['title' => 'Usuarios', 'wide' => true])

@section('content')
    <div class="mb-8 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.18em] text-orange-brand">Administración</p>
            <h1 class="mt-2 font-display text-3xl font-extrabold text-purple-dark">Usuarios</h1>
            <p class="mt-2 max-w-2xl text-sm text-text-gray">Edita roles, asigna templates y revisa la suscripción de cada cliente.</p>
        </div>
    </div>

    @if (session('status'))
        <div class="mb-6 rounded-md border border-green-200 bg-green-50 px-4 py-3 text-sm font-semibold text-green-800">
            {{ session('status') }}
        </div>
    @endif

    <div class="overflow-hidden rounded-lg border border-border-soft bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-[920px] divide-y divide-border-soft text-sm xl:min-w-full">
                <thead class="bg-purple-soft/70 text-left text-xs font-bold uppercase tracking-wide text-purple-dark">
                    <tr>
                        <th class="px-5 py-4">Nombre</th>
                        <th class="px-5 py-4">Email</th>
                        <th class="px-5 py-4 text-center">Rol</th>
                        <th class="px-5 py-4 text-center">Suscripción</th>
                        <th class="px-5 py-4 text-center">Invitaciones</th>
                        <th class="px-5 py-4 text-center">Activo</th>
                        <th class="px-5 py-4 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-soft">
                    @forelse ($users as $u)
                        @php
                            $suscripcion = $u->suscripciones->first();
                        @endphp
                        <tr>
                            <td class="px-5 py-4 font-semibold text-text-dark">{{ $u->name }}</td>
                            <td class="px-5 py-4 text-text-gray">{{ $u->email }}</td>
                            <td class="px-5 py-4 text-center">
                                <span class="rounded-full bg-purple-soft px-3 py-1 text-xs font-bold text-purple-brand">{{ $u->role }}</span>
                            </td>
                            <td class="px-5 py-4 text-center">
                                @if ($suscripcion)
                                    <span class="font-semibold text-text-dark">
                                        {{ $suscripcion->invitaciones_usadas }} / {{ $suscripcion->max_invitaciones }}
                                    </span>
                                    <p class="text-xs {{ match($suscripcion->estado) { 'activa' => 'text-green-700', 'agotada' => 'text-amber-700', 'vencida' => 'text-red-700', default => 'text-slate-500' } }}">
                                        {{ $suscripcion->estado_legible }}
                                    </p>
                                @else
                                    <span class="text-xs text-text-gray">Sin suscripción</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-center text-text-gray">{{ $u->invitaciones_count }}</td>
                            <td class="px-5 py-4 text-center">
                                @if ($u->activo)
                                    <span class="inline-flex h-2.5 w-2.5 rounded-full bg-green-500"></span>
                                @else
                                    <span class="inline-flex h-2.5 w-2.5 rounded-full bg-slate-300"></span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-right">
                                <a href="{{ route('admin.users.edit', $u) }}"
                                   class="inline-flex items-center rounded-lg border border-border-soft bg-white px-3 py-2 text-xs font-bold text-purple-brand transition hover:border-purple-brand hover:bg-purple-soft">
                                    Gestionar
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-text-gray">No hay usuarios todavía.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
