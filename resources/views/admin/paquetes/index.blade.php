@extends('layouts.admin', ['title' => 'Paquetes', 'wide' => true])

@section('content')
    <div class="mb-8 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.18em] text-orange-brand">Catálogo</p>
            <h1 class="mt-2 font-display text-3xl font-extrabold text-purple-dark">Paquetes</h1>
            <p class="mt-2 max-w-2xl text-sm text-text-gray">
                Son los planes que el cliente ve en la landing. Los precios están en <strong>centavos</strong> para evitar floats en dinero (ej. 60000 = $600 MXN).
            </p>
        </div>
        <a href="{{ route('admin.paquetes.create') }}"
           class="inline-flex items-center justify-center gap-2 rounded-md bg-orange-brand px-4 py-2.5 text-sm font-bold text-white transition hover:bg-orange-intense">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 5v14M5 12h14"/></svg>
            Nuevo paquete
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
                        <th class="px-5 py-4 text-right">Precio</th>
                        <th class="px-5 py-4">Badge</th>
                        <th class="px-5 py-4 text-center">Destacado</th>
                        <th class="px-5 py-4 text-center">Activo</th>
                        <th class="px-5 py-4 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-soft">
                    @forelse ($paquetes as $paquete)
                        <tr>
                            <td class="px-5 py-4 text-text-gray">{{ $paquete->orden }}</td>
                            <td class="px-5 py-4">
                                <span class="rounded-full bg-orange-soft px-2.5 py-1 text-xs font-bold uppercase tracking-wider text-orange-intense">
                                    {{ $paquete->formato }}
                                </span>
                            </td>
                            <td class="px-5 py-4">
                                <p class="font-semibold text-text-dark">{{ $paquete->nombre }}</p>
                                <p class="text-xs text-text-gray">{{ $paquete->slug }}</p>
                            </td>
                            <td class="px-5 py-4 text-right font-display text-lg font-extrabold text-purple-dark">
                                {{ $paquete->precio_formateado }}
                            </td>
                            <td class="px-5 py-4 text-text-gray">{{ $paquete->badge ?? '—' }}</td>
                            <td class="px-5 py-4 text-center">
                                @if ($paquete->destacado)
                                    <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-orange-soft text-orange-intense">★</span>
                                @else
                                    <span class="text-text-gray">—</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-center">
                                @if ($paquete->activo)
                                    <span class="inline-flex h-2.5 w-2.5 rounded-full bg-green-500"></span>
                                @else
                                    <span class="inline-flex h-2.5 w-2.5 rounded-full bg-slate-300"></span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-right">
                                <div class="inline-flex flex-wrap justify-end gap-2">
                                    <a href="{{ route('admin.paquetes.edit', $paquete) }}"
                                       class="inline-flex items-center rounded-lg border border-border-soft bg-white px-3 py-2 text-xs font-bold text-purple-brand transition hover:border-purple-brand hover:bg-purple-soft">
                                        Editar
                                    </a>
                                    <a href="{{ route('checkout.show', $paquete) }}" target="_blank" rel="noopener"
                                       class="inline-flex items-center rounded-lg border border-border-soft bg-white px-3 py-2 text-xs font-bold text-purple-brand transition hover:border-purple-brand hover:bg-purple-soft">
                                        Ver
                                    </a>
                                    <button type="button"
                                            class="inline-flex cursor-pointer items-center rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-xs font-bold text-red-700 transition hover:border-red-300 hover:bg-red-100"
                                            onclick="document.getElementById('delete-paquete-{{ $paquete->id }}').showModal()">
                                        Eliminar
                                    </button>
                                </div>

                                <dialog id="delete-paquete-{{ $paquete->id }}"
                                        aria-labelledby="delete-paquete-title-{{ $paquete->id }}"
                                        class="fixed inset-0 m-auto max-h-[calc(100vh-2rem)] w-[calc(100vw-2rem)] max-w-md overflow-y-auto rounded-2xl border border-red-100 bg-white p-0 text-left shadow-2xl backdrop:bg-slate-950/60"
                                        style="position: fixed; inset: 0; width: min(28rem, calc(100vw - 2rem)); max-height: calc(100vh - 2rem); margin: auto;">
                                    <form method="POST" action="{{ route('admin.paquetes.destroy', $paquete) }}" class="p-6">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="delete_source" value="{{ $paquete->slug }}">

                                        <div class="flex items-start justify-between gap-4">
                                            <div>
                                                <p class="text-xs font-extrabold uppercase tracking-[0.18em] text-red-600">Acción irreversible</p>
                                                <h3 id="delete-paquete-title-{{ $paquete->id }}" class="mt-2 font-display text-xl font-extrabold text-slate-950">Eliminar paquete</h3>
                                            </div>
                                            <button type="button" onclick="this.closest('dialog').close()" aria-label="Cerrar"
                                                    class="grid h-9 w-9 cursor-pointer place-items-center rounded-full border border-border-soft text-slate-500 transition hover:border-red-300 hover:text-red-700">
                                                <span aria-hidden="true">×</span>
                                            </button>
                                        </div>

                                        <div class="mt-5 rounded-xl border border-red-100 bg-red-50 p-4">
                                            <p class="font-extrabold text-red-800">{{ $paquete->nombre }}</p>
                                            <p class="mt-1 text-sm text-red-700">Slug: <strong>{{ $paquete->slug }}</strong></p>
                                            <p class="mt-2 text-xs leading-relaxed text-red-700">
                                                Si este paquete tiene órdenes o invitaciones asociadas, la eliminación será rechazada.
                                            </p>
                                        </div>

                                        <label for="delete-confirm-{{ $paquete->id }}" class="mt-5 block text-sm font-bold text-text-dark">
                                            Escribe <span class="font-mono text-red-700">{{ $paquete->slug }}</span> para confirmar
                                            <input id="delete-confirm-{{ $paquete->id }}"
                                                   name="confirm_slug"
                                                   value="{{ old('delete_source') === $paquete->slug ? old('confirm_slug') : '' }}"
                                                   autocomplete="off"
                                                   required
                                                   data-confirm-value="{{ $paquete->slug }}"
                                                   oninput="this.form.querySelector('[data-delete-submit]').disabled = this.value.trim() !== this.dataset.confirmValue"
                                                   class="mt-2 w-full rounded-xl border border-red-200 px-4 py-3 font-mono text-sm outline-none transition focus:border-red-500 focus:ring-4 focus:ring-red-100">
                                        </label>
                                        @if (old('delete_source') === $paquete->slug)
                                            @error('confirm_slug')
                                                <p class="mt-2 text-sm font-semibold text-red-600">{{ $message }}</p>
                                            @enderror
                                        @endif

                                        <div class="mt-6 grid grid-cols-2 gap-3">
                                            <button type="button" onclick="this.closest('dialog').close()"
                                                    class="cursor-pointer rounded-xl border border-border-soft bg-white px-4 py-3 text-sm font-bold text-purple-brand transition hover:border-purple-brand">
                                                Cancelar
                                            </button>
                                            <button type="submit" data-delete-submit disabled
                                                    class="cursor-pointer rounded-xl bg-red-600 px-4 py-3 text-sm font-extrabold text-white transition hover:bg-red-700 disabled:cursor-not-allowed disabled:opacity-40">
                                                Eliminar definitivamente
                                            </button>
                                        </div>
                                    </form>
                                </dialog>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-8 text-center text-text-gray">Todavía no hay paquetes. Crea el primero.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <p class="mt-4 text-xs text-text-gray">
        ¿Quieres crear cupones para aplicar descuentos a estos paquetes? <a href="{{ route('admin.cupones.index') }}" class="font-semibold text-purple-brand hover:text-orange-brand">Ir a cupones →</a>
    </p>
@endsection
