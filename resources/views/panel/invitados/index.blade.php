@extends('layouts.admin', ['title' => 'Invitados · ' . $invitacion->nombre_completo])

@section('content')
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.18em] text-orange-brand">Lista de invitados</p>
            <h1 class="mt-2 font-display text-3xl font-extrabold text-purple-dark">
                {{ $invitacion->nombre_completo }}
            </h1>
            <p class="mt-2 text-sm text-text-gray">
                Cada invitado tiene un link único. Compártelo por WhatsApp o Facebook para que confirme cuántos van.
            </p>
        </div>

        <div class="flex flex-wrap gap-2">
            <a href="{{ route('panel.invitados.create', $invitacion) }}"
               class="inline-flex items-center justify-center rounded-md bg-orange-brand px-4 py-2.5 text-sm font-bold text-white transition hover:bg-orange-intense">
                + Agregar invitado
            </a>
            <button type="button" onclick="document.getElementById('bulk-add').classList.toggle('hidden')"
                    class="inline-flex items-center justify-center rounded-md border border-border-soft bg-white px-4 py-2.5 text-sm font-bold text-text-dark transition hover:border-orange-brand hover:text-orange-brand">
                Pegar lista
            </button>
        </div>
    </div>

    @if (session('status'))
        <div class="mb-6 rounded-md border border-green-200 bg-green-50 px-4 py-3 text-sm font-semibold text-green-800">
            {{ session('status') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-6 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm font-semibold text-red-700">
            {{ $errors->first() }}
        </div>
    @endif

    {{-- Bulk add colapsable --}}
    <section id="bulk-add" class="mb-8 hidden rounded-lg border border-border-soft bg-white p-6 shadow-sm">
        <h2 class="mb-2 font-display text-lg font-bold text-text-dark">Pegar lista de invitados</h2>
        <p class="mb-3 text-xs text-text-gray">
            Una línea por invitado. Formato: <code class="rounded bg-cream-bg px-1.5 py-0.5">Nombre, lugares</code>. Si no pones lugares, asume 1.
        </p>
        <form method="POST" action="{{ route('panel.invitados.store-bulk', $invitacion) }}">
            @csrf
            <textarea name="texto" rows="8" required
                      class="w-full rounded-md border border-border-soft bg-white px-3 py-2 font-mono text-sm focus:border-orange-brand focus:outline-none"
                      placeholder="Manuel Sansores, 3&#10;Pablo Manzanero, 2&#10;Familia Arceo, 5&#10;María López">Manuel Sansores, 3
Pablo Manzanero, 2
Familia Arceo, 5</textarea>
            <div class="mt-3 flex gap-2">
                <button type="submit" class="rounded-md bg-orange-brand px-4 py-2 text-sm font-bold text-white transition hover:bg-orange-intense">
                    Agregar a la lista
                </button>
                <button type="button" onclick="document.getElementById('bulk-add').classList.add('hidden')"
                        class="rounded-md border border-border-soft px-4 py-2 text-sm font-bold text-text-dark transition hover:bg-cream-bg">
                    Cancelar
                </button>
            </div>
        </form>
    </section>

    {{-- Métricas --}}
    <section class="mb-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <div class="rounded-lg border border-border-soft bg-white p-5 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-wide text-text-gray">Invitados</p>
            <p class="mt-2 font-display text-3xl font-extrabold text-purple-dark">{{ $totales['total'] }}</p>
        </div>
        <div class="rounded-lg border border-border-soft bg-white p-5 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-wide text-text-gray">Lugares asignados</p>
            <p class="mt-2 font-display text-3xl font-extrabold text-purple-dark">{{ $totales['lugares_asignados'] }}</p>
        </div>
        <div class="rounded-lg border border-border-soft bg-white p-5 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-wide text-text-gray">Confirmados</p>
            <p class="mt-2 font-display text-3xl font-extrabold text-green-700">
                {{ $totales['lugares_confirmados'] }}<span class="text-base text-text-gray">/{{ $totales['lugares_asignados'] }}</span>
            </p>
        </div>
        <div class="rounded-lg border border-border-soft bg-white p-5 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-wide text-text-gray">Pendientes</p>
            <p class="mt-2 font-display text-3xl font-extrabold text-orange-brand">{{ $totales['pendientes'] }}</p>
        </div>
    </section>

    {{-- Toggle "mostrar contador público" (placeholder, se activa por admin o endpoint dedicado) --}}

    {{-- Tabla de invitados --}}
    <section class="rounded-lg border border-border-soft bg-white shadow-sm">
        @if ($invitados->isEmpty())
            <div class="p-10 text-center">
                <p class="text-base font-semibold text-text-dark">Aún no has agregado invitados.</p>
                <p class="mt-2 text-sm text-text-gray">Empieza con "+ Agregar invitado" o pega una lista con "Pegar lista".</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-cream-bg text-left text-xs font-semibold uppercase tracking-wide text-text-gray">
                        <tr>
                            <th class="px-4 py-3">Invitado</th>
                            <th class="px-4 py-3 text-center">Lugares</th>
                            <th class="px-4 py-3">Estado</th>
                            <th class="px-4 py-3">Link</th>
                            <th class="px-4 py-3 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border-soft">
                        @foreach ($invitados as $inv)
                            <tr class="hover:bg-cream-bg/40">
                                <td class="px-4 py-3">
                                    <p class="font-bold text-text-dark">{{ $inv->nombre }}</p>
                                    @if ($inv->telefono)
                                        <p class="text-xs text-text-gray">{{ $inv->telefono }}</p>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="font-semibold text-text-dark">{{ $inv->lugares_asignados }}</span>
                                    @if ($inv->lugares_confirmados !== null)
                                        <p class="text-xs text-text-gray">→ {{ $inv->lugares_confirmados }}</p>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    @php
                                        $color = $inv->estado_color;
                                        $classes = match($color) {
                                            'green' => 'bg-green-100 text-green-800',
                                            'amber' => 'bg-amber-100 text-amber-800',
                                            'red'   => 'bg-red-100 text-red-800',
                                            default => 'bg-gray-100 text-gray-700',
                                        };
                                    @endphp
                                    <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $classes }}">
                                        {{ $inv->estado_legible }}
                                    </span>
                                    @if ($inv->confirmado_at)
                                        <p class="mt-1 text-xs text-text-gray">{{ $inv->confirmado_at->diffForHumans() }}</p>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex flex-col gap-1" data-share-url="{{ $inv->url_publica }}" data-nombre="{{ $inv->nombre }}" data-lugares="{{ $inv->lugares_asignados }}">
                                        <button type="button"
                                                onclick="compartir(this, 'whatsapp')"
                                                class="inline-flex w-fit items-center gap-1 rounded-md bg-green-500 px-2.5 py-1 text-xs font-bold text-white transition hover:bg-green-600">
                                            WhatsApp
                                        </button>
                                        <button type="button"
                                                onclick="compartir(this, 'facebook')"
                                                class="inline-flex w-fit items-center gap-1 rounded-md bg-blue-600 px-2.5 py-1 text-xs font-bold text-white transition hover:bg-blue-700">
                                            Facebook
                                        </button>
                                        <button type="button"
                                                onclick="copiarLink(this)"
                                                class="inline-flex w-fit items-center gap-1 rounded-md border border-border-soft bg-white px-2.5 py-1 text-xs font-bold text-text-dark transition hover:border-orange-brand hover:text-orange-brand">
                                            Copiar link
                                        </button>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <div class="inline-flex gap-1">
                                        <a href="{{ route('panel.invitados.edit', [$invitacion, $inv]) }}"
                                           class="rounded-md border border-border-soft bg-white px-2.5 py-1 text-xs font-bold text-text-dark transition hover:border-orange-brand hover:text-orange-brand">
                                            Editar
                                        </a>
                                        <form method="POST" action="{{ route('panel.invitados.destroy', [$invitacion, $inv]) }}"
                                              onsubmit="return confirm('¿Eliminar a {{ $inv->nombre }} de la lista? El link dejará de funcionar.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="rounded-md border border-red-200 bg-white px-2.5 py-1 text-xs font-bold text-red-700 transition hover:border-red-500 hover:bg-red-50">
                                                Borrar
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </section>

    <p class="mt-6 text-center text-xs text-text-gray">
        <a href="{{ route('panel.confirmados.index') }}" class="hover:text-orange-brand">← Volver a confirmados</a>
    </p>

    <script>
        function compartir(btn, red) {
            const cell = btn.closest('[data-share-url]');
            const url = cell.dataset.shareUrl;
            const nombre = cell.dataset.nombre;
            const lugares = cell.dataset.lugares;
            const texto = `Hola ${nombre}! Tienes ${lugares} lugar(es) reservado(s) en nuestro evento. Confirma cuántos van aquí: ${url}`;
            if (red === 'whatsapp') {
                window.open(`https://wa.me/?text=${encodeURIComponent(texto)}`, '_blank');
            } else if (red === 'facebook') {
                window.open(`https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(url)}&quote=${encodeURIComponent(texto)}`, '_blank');
            }
        }
        async function copiarLink(btn) {
            const cell = btn.closest('[data-share-url]');
            const url = cell.dataset.shareUrl;
            try {
                await navigator.clipboard.writeText(url);
                const orig = btn.textContent;
                btn.textContent = '¡Copiado!';
                btn.classList.add('border-green-500', 'text-green-700');
                setTimeout(() => {
                    btn.textContent = orig;
                    btn.classList.remove('border-green-500', 'text-green-700');
                }, 1500);
            } catch (e) {
                alert('No pude copiar automáticamente. Link: ' + url);
            }
        }
    </script>
@endsection
