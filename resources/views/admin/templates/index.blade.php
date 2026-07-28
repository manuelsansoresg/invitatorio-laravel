@extends('layouts.admin', ['title' => 'Templates', 'wide' => true])

@section('content')
    <div class="mb-8 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.18em] text-orange-brand">Catálogo</p>
            <h1 class="mt-2 font-display text-3xl font-extrabold text-purple-dark">Templates</h1>
            <p class="mt-2 max-w-2xl text-sm text-text-gray">
                Los <strong>templates</strong> son los diseños base que el cliente usa para crear sus invitaciones.
                El admin decide a qué cliente se le muestra cada uno. <strong>No se pueden borrar</strong>, solo activar o desactivar.
            </p>
        </div>
        <a href="{{ route('admin.templates.create') }}"
           class="inline-flex items-center justify-center gap-2 rounded-md bg-orange-brand px-4 py-2.5 text-sm font-bold text-white transition hover:bg-orange-intense">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 5v14M5 12h14"/></svg>
            Nuevo template
        </a>
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
                        <th class="px-5 py-4">Orden</th>
                        <th class="px-5 py-4">Formato</th>
                        <th class="px-5 py-4">Nombre / Slug</th>
                        <th class="px-5 py-4 text-center">Activo</th>
                        <th class="px-5 py-4 text-right">Acción</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-soft">
                    @forelse ($templates as $template)
                        <tr>
                            <td class="px-5 py-4 text-text-gray">{{ $template->orden }}</td>
                            <td class="px-5 py-4">
                                <span class="rounded-full bg-orange-soft px-2.5 py-1 text-xs font-bold uppercase tracking-wider text-orange-intense">
                                    {{ $template->formato }}
                                </span>
                            </td>
                            <td class="px-5 py-4">
                                <p class="font-semibold text-text-dark">{{ $template->nombre }}</p>
                                <p class="text-xs text-text-gray">{{ $template->slug }}</p>
                                @if ($template->descripcion)
                                    <p class="mt-0.5 text-xs text-text-gray">{{ $template->descripcion }}</p>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-center">
                                @if ($template->activo)
                                    <span class="inline-flex h-2.5 w-2.5 rounded-full bg-green-500"></span>
                                @else
                                    <span class="inline-flex h-2.5 w-2.5 rounded-full bg-slate-300"></span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-right">
                                <div class="inline-flex flex-wrap justify-end gap-2">
                                    <form method="POST" action="{{ route('admin.templates.toggle', $template) }}" class="inline">
                                        @csrf
                                        <button type="submit"
                                                class="inline-flex cursor-pointer items-center rounded-lg border border-border-soft bg-white px-3 py-2 text-xs font-bold transition {{ $template->activo ? 'text-amber-700 hover:border-amber-300 hover:bg-amber-50' : 'text-green-700 hover:border-green-300 hover:bg-green-50' }}">
                                            {{ $template->activo ? 'Desactivar' : 'Activar' }}
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-text-gray">Todavía no hay templates. Crea el primero.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <p class="mt-4 text-xs text-text-gray">
        Los clientes reciben automáticamente todos los templates activos al comprar. Para bloquear uno, edita el usuario.
    </p>
@endsection
