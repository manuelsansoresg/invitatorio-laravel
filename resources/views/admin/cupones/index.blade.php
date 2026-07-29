@extends('layouts.admin', ['title' => 'Cupones', 'wide' => true])

@section('content')
    <div class="mb-8 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.18em] text-orange-brand">Promociones</p>
            <h1 class="mt-2 font-display text-3xl font-extrabold text-purple-dark">Cupones</h1>
            <p class="mt-2 max-w-2xl text-sm text-text-gray">
                Crea códigos de descuento que el cliente puede aplicar al paquete. Comparte el link
                <span class="font-mono text-purple-brand">/comprar/&lt;slug&gt;?coupon=CODIGO</span>
                y el descuento se aplica solo.
            </p>
        </div>
        <a href="{{ route('admin.cupones.create') }}"
           class="inline-flex items-center justify-center gap-2 rounded-md bg-orange-brand px-4 py-2.5 text-sm font-bold text-white transition hover:bg-orange-intense">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 5v14M5 12h14"/></svg>
            Nuevo cupón
        </a>
    </div>

    @if (session('status'))
        <div class="mb-6 rounded-md border border-green-200 bg-green-50 px-4 py-3 text-sm font-semibold text-green-800">
            {{ session('status') }}
        </div>
    @endif

    <div class="overflow-hidden rounded-lg border border-border-soft bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-[1080px] divide-y divide-border-soft text-sm xl:min-w-full">
                <thead class="bg-purple-soft/70 text-left text-xs font-bold uppercase tracking-wide text-purple-dark">
                    <tr>
                        <th class="px-5 py-4">Código</th>
                        <th class="px-5 py-4">Tipo / Valor</th>
                        <th class="px-5 py-4">Aplica a</th>
                        <th class="px-5 py-4">Vigencia</th>
                        <th class="px-5 py-4 text-center">Usos</th>
                        <th class="px-5 py-4 text-center">Estado</th>
                        <th class="px-5 py-4 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-soft">
                    @forelse ($cupones as $cupon)
                        @php
                            $totalPaquetesAsignados = $cupon->paquetes()->count();
                        @endphp
                        <tr>
                            <td class="px-5 py-4">
                                <p class="font-mono text-base font-extrabold text-purple-dark">{{ $cupon->codigo }}</p>
                                @if ($cupon->descripcion)
                                    <p class="mt-0.5 text-xs text-text-gray">{{ $cupon->descripcion }}</p>
                                @endif
                            </td>
                            <td class="px-5 py-4">
                                <span class="rounded-full bg-orange-soft px-2.5 py-1 text-xs font-bold uppercase tracking-wider text-orange-intense">
                                    {{ $cupon->tipo }}
                                </span>
                                <span class="ml-2 font-display text-lg font-extrabold text-purple-dark">{{ $cupon->descuento_legible }}</span>
                                @if ($cupon->minimo_compra_centavos > 0)
                                    <p class="mt-0.5 text-xs text-text-gray">Mínimo: ${{ number_format($cupon->minimo_compra_centavos / 100, 0, '.', ',') }} MXN</p>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-text-gray">
                                @if ($totalPaquetesAsignados === 0)
                                    <span class="font-semibold text-text-dark">Todos los paquetes</span>
                                @else
                                    {{ $totalPaquetesAsignados }} paquete{{ $totalPaquetesAsignados === 1 ? '' : 's' }}
                                @endif
                            </td>
                            <td class="px-5 py-4 text-text-gray">
                                @if ($cupon->fecha_inicio || $cupon->fecha_fin)
                                    <p class="text-xs">
                                        {{ optional($cupon->fecha_inicio)->format('d/m/Y') ?? '—' }}
                                        <span class="text-text-gray">→</span>
                                        {{ optional($cupon->fecha_fin)->format('d/m/Y') ?? '—' }}
                                    </p>
                                @else
                                    <span class="text-xs">Sin límite</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-center">
                                <p class="font-semibold text-text-dark">{{ $cupon->usos_actuales }}{{ $cupon->max_usos ? ' / '.$cupon->max_usos : '' }}</p>
                                <p class="text-xs text-text-gray">{{ $cupon->ordenes_count }} orden{{ $cupon->ordenes_count === 1 ? '' : 'es' }}</p>
                            </td>
                            <td class="px-5 py-4 text-center">
                                @php($color = match($cupon->estado_legible) {
                                    'Vigente'    => 'bg-green-100 text-green-800',
                                    'Programado' => 'bg-amber-100 text-amber-800',
                                    'Vencido'    => 'bg-slate-200 text-slate-700',
                                    'Agotado'    => 'bg-red-100 text-red-700',
                                    'Inactivo'   => 'bg-slate-100 text-slate-500',
                                    default      => 'bg-slate-100 text-slate-500',
                                })
                                <span class="inline-flex rounded-full px-2.5 py-1 text-[11px] font-bold uppercase tracking-wider {{ $color }}">
                                    {{ $cupon->estado_legible }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-right">
                                <div class="inline-flex flex-wrap justify-end gap-2">
                                    <a href="{{ route('admin.cupones.edit', $cupon) }}"
                                       class="inline-flex items-center rounded-lg border border-border-soft bg-white px-3 py-2 text-xs font-bold text-purple-brand transition hover:border-purple-brand hover:bg-purple-soft">
                                        Editar
                                    </a>
                                    <button type="button"
                                            class="inline-flex cursor-pointer items-center rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-xs font-bold text-red-700 transition hover:border-red-300 hover:bg-red-100"
                                            onclick="document.getElementById('delete-cupon-{{ $cupon->id }}').showModal()">
                                        Eliminar
                                    </button>
                                </div>

                                <dialog id="delete-cupon-{{ $cupon->id }}"
                                        aria-labelledby="delete-cupon-title-{{ $cupon->id }}"
                                        class="fixed inset-0 m-auto max-h-[calc(100vh-2rem)] w-[calc(100vw-2rem)] max-w-md overflow-y-auto rounded-2xl border border-red-100 bg-white p-0 text-left shadow-2xl backdrop:bg-slate-950/60"
                                        style="position: fixed; inset: 0; width: min(28rem, calc(100vw - 2rem)); max-height: calc(100vh - 2rem); margin: auto;">
                                    <form method="POST" action="{{ route('admin.cupones.destroy', $cupon) }}" class="p-6">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="delete_source" value="{{ $cupon->codigo }}">

                                        <div class="flex items-start justify-between gap-4">
                                            <div>
                                                <p class="text-xs font-extrabold uppercase tracking-[0.18em] text-red-600">Acción irreversible</p>
                                                <h3 id="delete-cupon-title-{{ $cupon->id }}" class="mt-2 font-display text-xl font-extrabold text-slate-950">Eliminar cupón</h3>
                                            </div>
                                            <button type="button" onclick="this.closest('dialog').close()" aria-label="Cerrar"
                                                    class="grid h-9 w-9 cursor-pointer place-items-center rounded-full border border-border-soft text-slate-500 transition hover:border-red-300 hover:text-red-700">
                                                <span aria-hidden="true">×</span>
                                            </button>
                                        </div>

                                        <div class="mt-5 rounded-xl border border-red-100 bg-red-50 p-4">
                                            <p class="font-mono font-extrabold text-red-800">{{ $cupon->codigo }}</p>
                                            <p class="mt-1 text-xs leading-relaxed text-red-700">
                                                Si ya fue usado en alguna orden, no se podrá eliminar. En ese caso, mejor desactívalo.
                                            </p>
                                        </div>

                                        <label for="delete-confirm-{{ $cupon->id }}" class="mt-5 block text-sm font-bold text-text-dark">
                                            Escribe <span class="font-mono text-red-700">{{ $cupon->codigo }}</span> para confirmar
                                            <input id="delete-confirm-{{ $cupon->id }}"
                                                   name="confirm_codigo"
                                                   value="{{ old('delete_source') === $cupon->codigo ? old('confirm_codigo') : '' }}"
                                                   autocomplete="off" required
                                                   data-confirm-value="{{ $cupon->codigo }}"
                                                   oninput="this.form.querySelector('[data-delete-submit]').disabled = this.value.trim() !== this.dataset.confirmValue"
                                                   class="mt-2 w-full rounded-xl border border-red-200 px-4 py-3 font-mono text-sm outline-none transition focus:border-red-500 focus:ring-4 focus:ring-red-100">
                                        </label>
                                        @if (old('delete_source') === $cupon->codigo)
                                            @error('confirm_codigo')
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
                            <td colspan="7" class="px-4 py-8 text-center text-text-gray">Todavía no hay cupones. Crea el primero.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
