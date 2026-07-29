@extends('layouts.admin', ['title' => 'Confirmados · ' . $invitacion->nombre_completo])

@section('content')
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.18em] text-orange-brand">
                Confirmados e invitados
            </p>
            <h1 class="mt-2 font-display text-3xl font-extrabold text-purple-dark">
                {{ $invitacion->nombre_completo }}
            </h1>
            <p class="mt-1 text-sm text-text-gray">
                <a href="{{ url('/invitacion/' . $invitacion->ruta) }}" target="_blank" class="text-purple-brand hover:text-orange-brand">{{ url('/invitacion/' . $invitacion->ruta) }}</a>
            </p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="text-sm font-semibold text-purple-brand hover:text-orange-brand">← Volver al dashboard</a>
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

    {{-- ═══════════════ SECCIÓN 1: CONFIRMADOS (popup de la invitación) ═══════════════ --}}
    <section class="mb-10">
        <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-display text-xl font-bold text-text-dark">Confirmados</h2>
                <p class="text-xs text-text-gray">Personas que confirmaron asistencia desde el popup de la invitación.</p>
            </div>
            <span class="rounded-full bg-purple-soft px-3 py-1 text-sm font-semibold text-purple-brand">
                {{ $confirmaciones->count() }} {{ $confirmaciones->count() === 1 ? 'confirmación' : 'confirmaciones' }}
            </span>
        </div>

        <div class="rounded-lg border border-border-soft bg-white shadow-sm">
            @if ($confirmaciones->isEmpty())
                <div class="p-8 text-center">
                    <p class="text-sm text-text-gray">Nadie ha confirmado desde el popup de la invitación todavía.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-border-soft text-sm">
                        <thead class="bg-purple-soft/40 text-left text-xs font-bold uppercase tracking-wide text-text-gray">
                            <tr>
                                <th class="px-4 py-3">Nombre</th>
                                <th class="px-4 py-3">Acompañantes</th>
                                <th class="px-4 py-3">Mensaje</th>
                                <th class="px-4 py-3">Fecha</th>
                                <th class="px-4 py-3 text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border-soft">
                            @foreach ($confirmaciones as $c)
                                <tr>
                                    <td class="px-4 py-3 font-semibold text-text-dark">{{ $c->nombre ?? 'Anónimo' }}</td>
                                    <td class="px-4 py-3 text-center text-text-dark">{{ $c->acompanantes ?? 0 }}</td>
                                    <td class="px-4 py-3 text-xs text-text-gray">{{ $c->mensaje ?? '—' }}</td>
                                    <td class="px-4 py-3 text-xs text-text-gray">{{ $c->created_at?->format('d/m/Y H:i') }}</td>
                                    <td class="px-4 py-3 text-right">
                                        <form method="POST" action="{{ route('panel.confirmados.destroy', $c) }}"
                                              onsubmit="return confirm('¿Eliminar esta confirmación?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-xs font-bold text-red-700 hover:underline">Eliminar</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </section>

    {{-- ═══════════════ SECCIÓN 2: GESTIÓN DE INVITADOS (link único por invitado) ═══════════════ --}}
    <section class="mb-10">
        <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h2 class="font-display text-xl font-bold text-text-dark">Invitados con link único</h2>
                @if ($permiteInvitados)
                    <p class="text-xs text-text-gray">Cada invitado tiene un link que puedes compartir por WhatsApp o Facebook. Él confirma cuántos van de los lugares asignados.</p>
                @else
                    <p class="text-xs text-amber-700">Tu paquete no incluye esta función. Solo se muestra en paquetes que lo habilitan.</p>
                @endif
            </div>
            @if ($permiteInvitados)
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
            @endif
        </div>

        @if ($permiteInvitados)
            {{-- Métricas --}}
            <div class="mb-4 grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
                <div class="rounded-md border border-border-soft bg-white p-4 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-wide text-text-gray">Invitados</p>
                    <p class="mt-1 font-display text-2xl font-extrabold text-purple-dark">{{ $totalesInvitados['total'] }}</p>
                </div>
                <div class="rounded-md border border-border-soft bg-white p-4 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-wide text-text-gray">Lugares asignados</p>
                    <p class="mt-1 font-display text-2xl font-extrabold text-purple-dark">{{ $totalesInvitados['lugares_asignados'] }}</p>
                </div>
                <div class="rounded-md border border-border-soft bg-white p-4 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-wide text-text-gray">Confirmados</p>
                    <p class="mt-1 font-display text-2xl font-extrabold text-green-700">
                        {{ $totalesInvitados['lugares_confirmados'] }}<span class="text-sm text-text-gray">/{{ $totalesInvitados['lugares_asignados'] }}</span>
                    </p>
                </div>
                <div class="rounded-md border border-border-soft bg-white p-4 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-wide text-text-gray">Pendientes</p>
                    <p class="mt-1 font-display text-2xl font-extrabold text-orange-brand">{{ $totalesInvitados['pendientes'] }}</p>
                </div>
            </div>

            {{-- Bulk add colapsable --}}
            <section id="bulk-add" class="mb-4 hidden rounded-lg border border-border-soft bg-white p-6 shadow-sm">
                <h3 class="mb-2 font-display text-lg font-bold text-text-dark">Pegar lista de invitados</h3>
                <p class="mb-3 text-xs text-text-gray">
                    Formato: <code class="rounded bg-cream-bg px-1.5 py-0.5">Nombre, lugares</code>. Una línea por invitado.
                </p>
                <form method="POST" action="{{ route('panel.invitados.store-bulk', $invitacion) }}">
                    @csrf
                    <textarea name="texto" rows="6" required
                              class="w-full rounded-md border border-border-soft bg-white px-3 py-2 font-mono text-sm focus:border-orange-brand focus:outline-none"
                              placeholder="Manuel Sansores, 3&#10;Pablo Manzanero, 2&#10;Familia Arceo, 5">Manuel Sansores, 3
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

            {{-- Tabla de invitados --}}
            <div class="rounded-lg border border-border-soft bg-white shadow-sm">
                @if ($invitados->isEmpty())
                    <div class="p-8 text-center">
                        <p class="text-sm text-text-gray">Aún no has agregado invitados. Usa "+ Agregar" o "Pegar lista".</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-border-soft text-sm">
                            <thead class="bg-cream-bg text-left text-xs font-bold uppercase tracking-wide text-text-gray">
                                <tr>
                                    <th class="px-4 py-3">Invitado</th>
                                    <th class="px-4 py-3 text-center">Lugares</th>
                                    <th class="px-4 py-3">Estado</th>
                                    <th class="px-4 py-3">Compartir link</th>
                                    <th class="px-4 py-3 text-right">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-border-soft">
                                @foreach ($invitados as $inv)
                                    <tr class="hover:bg-cream-bg/30">
                                        <td class="px-4 py-3 font-bold text-text-dark">{{ $inv->nombre }}</td>
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
                                        </td>
                                        <td class="px-4 py-3" data-share-url="{{ $inv->url_publica }}" data-nombre="{{ $inv->nombre }}" data-lugares="{{ $inv->lugares_asignados }}">
                                            <div class="flex flex-wrap gap-1">
                                                <button type="button" onclick="compartir(this, 'whatsapp')"
                                                        class="rounded-md bg-green-500 px-2 py-1 text-xs font-bold text-white hover:bg-green-600">WhatsApp</button>
                                                <button type="button" onclick="compartir(this, 'facebook')"
                                                        class="rounded-md bg-blue-600 px-2 py-1 text-xs font-bold text-white hover:bg-blue-700">Facebook</button>
                                                <button type="button" onclick="copiarLink(this)"
                                                        class="rounded-md border border-border-soft bg-white px-2 py-1 text-xs font-bold text-text-dark hover:border-orange-brand hover:text-orange-brand">Copiar</button>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-right">
                                            <div class="inline-flex gap-1">
                                                <a href="{{ route('panel.invitados.edit', [$invitacion, $inv]) }}"
                                                   class="rounded-md border border-border-soft bg-white px-2 py-1 text-xs font-bold text-text-dark hover:border-orange-brand hover:text-orange-brand">Editar</a>
                                                <form method="POST" action="{{ route('panel.invitados.destroy', [$invitacion, $inv]) }}"
                                                      onsubmit="return confirm('¿Eliminar a {{ $inv->nombre }}? El link dejará de funcionar.')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="rounded-md border border-red-200 bg-white px-2 py-1 text-xs font-bold text-red-700 hover:border-red-500 hover:bg-red-50">Borrar</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        @else
            <div class="rounded-lg border-2 border-amber-200 bg-amber-50 p-6 shadow-sm">
                <div class="flex items-start gap-4">
                    <div class="text-2xl">🔒</div>
                    <div class="flex-1">
                        <h3 class="font-display text-lg font-bold text-amber-900">Función no incluida en tu paquete</h3>
                        <p class="mt-2 text-sm text-amber-800">
                            @if ($paquete)
                                Tu paquete actual es <strong>{{ $paquete->nombre }}</strong>. Activa la flag "Permite gestionar invitados" en
                                <a href="{{ route('admin.paquetes.edit', $paquete) }}" class="underline">la edición de este paquete</a>
                                para que los clientes con este paquete puedan usar la lista con link único.
                            @else
                                No tienes un paquete activo asignado a tu cuenta. Cuando compres un paquete que lo incluya, esta sección se activará automáticamente.
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        @endif
    </section>

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
