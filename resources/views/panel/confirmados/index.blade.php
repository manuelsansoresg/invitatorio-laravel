@extends('layouts.admin', ['title' => 'Confirmados'])

@section('content')
    <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.18em] text-orange-brand">{{ $isAdmin ? 'Vista administrador' : 'Panel cliente' }}</p>
            <h1 class="mt-2 font-display text-3xl font-extrabold text-purple-dark">Lista de confirmados</h1>
            <p class="mt-2 max-w-2xl text-sm text-text-gray">
                @if ($isAdmin)
                    Aquí aparecen las confirmaciones de todas las invitaciones registradas.
                @else
                    Aquí aparecen únicamente las confirmaciones de las invitaciones asignadas a tu correo.
                @endif
            </p>
        </div>

        <a href="{{ route('panel.confirmados.pdf') }}" class="inline-flex items-center justify-center rounded-md bg-orange-brand px-4 py-3 text-sm font-bold text-white transition hover:bg-orange-intense">
            Exportar PDF
        </a>
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

    <section class="mb-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        <div class="rounded-lg border border-border-soft bg-white p-5 shadow-sm">
            <p class="text-sm font-semibold text-text-gray">Total confirmados</p>
            <p class="mt-2 font-display text-3xl font-extrabold text-purple-dark">{{ $confirmaciones->count() }}</p>
        </div>
        <div class="rounded-lg border border-border-soft bg-white p-5 shadow-sm">
            <p class="text-sm font-semibold text-text-gray">Invitaciones visibles</p>
            <p class="mt-2 font-display text-3xl font-extrabold text-purple-dark">{{ $invitaciones->count() }}</p>
        </div>
        <div class="rounded-lg border border-border-soft bg-white p-5 shadow-sm">
            <p class="text-sm font-semibold text-text-gray">Última actualización</p>
            <p class="mt-2 font-display text-lg font-extrabold text-purple-dark">{{ now()->format('d/m/Y H:i') }}</p>
        </div>
    </section>

    <section class="mb-8">
        <h2 class="mb-4 font-display text-xl font-bold text-text-dark">Invitaciones</h2>
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @forelse ($invitaciones as $invitacion)
                <div class="rounded-lg border border-border-soft bg-white p-5 shadow-sm">
                    <p class="font-bold text-text-dark">{{ $invitacion->nombre_completo }}</p>
                    <p class="mt-1 text-sm text-text-gray">{{ $invitacion->ruta }}</p>
                    <p class="mt-4 text-sm font-semibold text-orange-intense">{{ $invitacion->confirmaciones_count }} confirmaciones</p>
                </div>
            @empty
                <div class="rounded-lg border border-border-soft bg-white p-5 text-sm text-text-gray shadow-sm sm:col-span-2 lg:col-span-3">
                    No tienes invitaciones asignadas por ahora.
                </div>
            @endforelse
        </div>
    </section>

    <section>
        <div class="mb-4 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="font-display text-xl font-bold text-text-dark">Personas confirmadas</h2>
            <div class="flex flex-wrap items-center gap-3">
                <span class="rounded-full bg-purple-soft px-3 py-1 text-sm font-semibold text-purple-brand">{{ $confirmaciones->count() }} registros</span>
                @if ($confirmaciones->isNotEmpty())
                    <button
                        type="submit"
                        form="confirmaciones-bulk-delete"
                        class="rounded-md border border-red-200 px-3 py-2 text-sm font-bold text-red-700 transition hover:border-red-500 hover:bg-red-50"
                        onclick="return confirm('¿Seguro que quieres borrar las confirmaciones seleccionadas? Esta acción no se puede deshacer.')"
                    >
                        Borrar seleccionados
                    </button>
                @endif
            </div>
        </div>

        <div class="overflow-hidden rounded-lg border border-border-soft bg-white shadow-sm">
            <div class="overflow-x-auto">
                <form id="confirmaciones-bulk-delete" method="POST" action="{{ route('panel.confirmados.destroy-selected') }}">
                    @csrf
                    @method('DELETE')

                    <table class="min-w-full divide-y divide-border-soft text-sm">
                        <thead class="bg-purple-soft/70 text-left text-xs font-bold uppercase tracking-wide text-purple-dark">
                            <tr>
                                <th class="w-12 px-4 py-3">
                                    <input
                                        id="select-all-confirmaciones"
                                        type="checkbox"
                                        class="rounded border-border-soft text-purple-brand focus:ring-orange-brand"
                                        aria-label="Seleccionar todas las confirmaciones"
                                    >
                                </th>
                                <th class="px-4 py-3">Nombre</th>
                                <th class="px-4 py-3">Invitación</th>
                                <th class="px-4 py-3">Fecha</th>
                                <th class="px-4 py-3 text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border-soft">
                            @forelse ($confirmaciones as $confirmacion)
                                <tr>
                                    <td class="px-4 py-3">
                                        <input
                                            name="confirmaciones[]"
                                            value="{{ $confirmacion->id }}"
                                            type="checkbox"
                                            class="confirmacion-checkbox rounded border-border-soft text-purple-brand focus:ring-orange-brand"
                                            aria-label="Seleccionar confirmación de {{ $confirmacion->nombre }}"
                                        >
                                    </td>
                                    <td class="px-4 py-3 font-semibold text-text-dark">{{ $confirmacion->nombre }}</td>
                                    <td class="px-4 py-3 text-text-gray">{{ $confirmacion->invitacion?->nombre_completo ?? 'Sin invitación' }}</td>
                                    <td class="px-4 py-3 text-text-gray">{{ $confirmacion->created_at?->format('d/m/Y H:i') }}</td>
                                    <td class="px-4 py-3 text-right">
                                        <button
                                            type="submit"
                                            form="delete-confirmacion-{{ $confirmacion->id }}"
                                            class="font-bold text-red-700 hover:text-red-900"
                                            onclick="return confirm('¿Seguro que quieres borrar esta confirmación? Esta acción no se puede deshacer.')"
                                        >
                                            Borrar
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-8 text-center text-text-gray">Todavía no hay confirmaciones registradas.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </form>
            </div>
        </div>

        @foreach ($confirmaciones as $confirmacion)
            <form id="delete-confirmacion-{{ $confirmacion->id }}" method="POST" action="{{ route('panel.confirmados.destroy', $confirmacion) }}" class="hidden">
                @csrf
                @method('DELETE')
            </form>
        @endforeach
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const selectAll = document.getElementById('select-all-confirmaciones');
            const checkboxes = [...document.querySelectorAll('.confirmacion-checkbox')];

            if (!selectAll) {
                return;
            }

            selectAll.addEventListener('change', () => {
                checkboxes.forEach((checkbox) => {
                    checkbox.checked = selectAll.checked;
                });
            });

            checkboxes.forEach((checkbox) => {
                checkbox.addEventListener('change', () => {
                    selectAll.checked = checkboxes.length > 0 && checkboxes.every((item) => item.checked);
                    selectAll.indeterminate = checkboxes.some((item) => item.checked) && !selectAll.checked;
                });
            });
        });
    </script>
@endsection
