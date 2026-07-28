@extends('layouts.admin', ['title' => 'Mis invitaciones', 'wide' => true])

@section('content')
    <div class="mb-8 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.18em] text-orange-brand">Mi panel</p>
            <h1 class="mt-2 font-display text-3xl font-extrabold text-purple-dark">Mis invitaciones</h1>
            <p class="mt-2 max-w-2xl text-sm text-text-gray">
                Cada invitación que publiques consume 1 cupo de tu suscripción. Si te quedas sin cupo, compra otro paquete o pídele al admin que te asigne una suscripción manual.
            </p>
        </div>
        <a href="{{ route('panel.invitaciones.create') }}"
           class="inline-flex items-center justify-center gap-2 rounded-md bg-orange-brand px-4 py-2.5 text-sm font-bold text-white transition hover:bg-orange-intense">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 5v14M5 12h14"/></svg>
            Nueva invitación
        </a>
    </div>

    {{-- Banner de suscripción --}}
    @if ($suscripcion)
        @php($color = match($suscripcion->estado) {
            'activa'    => 'border-green-200 bg-green-50 text-green-900',
            'agotada'   => 'border-amber-200 bg-amber-50 text-amber-900',
            'vencida'   => 'border-red-200 bg-red-50 text-red-900',
            'cancelada' => 'border-slate-200 bg-slate-50 text-slate-700',
            default     => 'border-slate-200 bg-slate-50 text-slate-700',
        })
        <div class="mb-6 flex flex-col gap-2 rounded-md border px-4 py-3 sm:flex-row sm:items-center sm:justify-between {{ $color }}">
            <p class="text-sm font-semibold">
                Suscripción: <span class="font-bold">{{ $suscripcion->paquete->nombre ?? '—' }}</span>
                · {{ $suscripcion->invitaciones_usadas }} / {{ $suscripcion->max_invitaciones }} invitaciones usadas
                · {{ $suscripcion->estado_legible }}
            </p>
            @if ($suscripcion->fecha_fin)
                <p class="text-xs">Vence: {{ $suscripcion->fecha_fin->format('d/m/Y') }}</p>
            @endif
        </div>
    @else
        <div class="mb-6 rounded-md border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-900">
            <p class="font-semibold">Aún no tienes una suscripción activa.</p>
            <p class="mt-1 text-xs">Compra un paquete en la <a href="{{ url('/#paquetes') }}" class="underline">landing</a> o pídele al admin que te asigne una cortesía.</p>
        </div>
    @endif

    @if (session('status'))
        <div class="mb-6 rounded-md border border-green-200 bg-green-50 px-4 py-3 text-sm font-semibold text-green-800">
            {{ session('status') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-6 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    <div class="overflow-hidden rounded-lg border border-border-soft bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-[860px] divide-y divide-border-soft text-sm xl:min-w-full">
                <thead class="bg-purple-soft/70 text-left text-xs font-bold uppercase tracking-wide text-purple-dark">
                    <tr>
                        <th class="px-5 py-4">Invitación</th>
                        <th class="px-5 py-4">Template</th>
                        <th class="px-5 py-4 text-center">Estado</th>
                        <th class="px-5 py-4">Vence</th>
                        <th class="px-5 py-4 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-soft">
                    @forelse ($invitaciones as $inv)
                        <tr>
                            <td class="px-5 py-4">
                                <p class="font-semibold text-text-dark">{{ $inv->nombre_completo ?: '— sin nombre —' }}</p>
                                <p class="text-xs text-text-gray">/{{ $inv->ruta }}</p>
                            </td>
                            <td class="px-5 py-4 text-text-gray">{{ $inv->template?->nombre ?? '—' }}</td>
                            <td class="px-5 py-4 text-center">
                                @php($estado = $inv->estaVencida() ? 'vencida' : $inv->estado)
                                <span class="inline-flex rounded-full px-2.5 py-1 text-[11px] font-bold uppercase tracking-wider
                                    {{ match($estado) {
                                        'borrador'  => 'bg-amber-100 text-amber-800',
                                        'publicada' => 'bg-green-100 text-green-800',
                                        'vencida'   => 'bg-red-100 text-red-700',
                                        'archivada' => 'bg-slate-200 text-slate-700',
                                        default     => 'bg-slate-100 text-slate-500',
                                    } }}">
                                    {{ $estado }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-text-gray">
                                @if ($inv->fecha_caducidad)
                                    {{ $inv->fecha_caducidad->format('d/m/Y') }}
                                    @if ($inv->diasParaVencer() !== null)
                                        <p class="text-xs {{ $inv->diasParaVencer() < 30 ? 'text-amber-700' : 'text-text-gray' }}">
                                            {{ $inv->diasParaVencer() }} días
                                        </p>
                                    @endif
                                @else
                                    <span class="text-xs">—</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-right">
                                <div class="inline-flex flex-wrap justify-end gap-2">
                                    @if ($inv->esBorrador() && $inv->template?->formato === 'web')
                                        <a href="{{ route('admin.invitaciones.edit', $inv) }}"
                                           class="inline-flex items-center rounded-lg border border-border-soft bg-white px-3 py-2 text-xs font-bold text-purple-brand transition hover:border-purple-brand hover:bg-purple-soft">
                                            Editar
                                        </a>
                                    @endif
                                    @if ($inv->esBorrador() && in_array($inv->template?->formato, ['imagen', 'video'], true))
                                        <a href="{{ route('panel.invitaciones.datos', $inv) }}"
                                           class="inline-flex items-center rounded-lg border border-border-soft bg-white px-3 py-2 text-xs font-bold text-purple-brand transition hover:border-purple-brand hover:bg-purple-soft">
                                            Llenar datos
                                        </a>
                                    @endif
                                    @if ($inv->estaPublicada())
                                        <a href="{{ url('/invitacion/'.$inv->ruta) }}" target="_blank" rel="noopener"
                                           class="inline-flex items-center rounded-lg border border-border-soft bg-white px-3 py-2 text-xs font-bold text-purple-brand transition hover:border-purple-brand hover:bg-purple-soft">
                                            Ver pública
                                        </a>
                                    @endif
                                    @if ($inv->esBorrador() && $suscripcion?->puedePublicar())
                                        <form method="POST" action="{{ route('panel.invitaciones.publish', $inv) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="inline-flex cursor-pointer items-center rounded-lg bg-green-600 px-3 py-2 text-xs font-bold text-white transition hover:bg-green-700">
                                                Publicar
                                            </button>
                                        </form>
                                    @endif
                                    @if ($inv->esBorrador())
                                        <form method="POST" action="{{ route('panel.invitaciones.destroy', $inv) }}" class="inline"
                                              onsubmit="return confirm('¿Eliminar la invitación en borrador?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex cursor-pointer items-center rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-xs font-bold text-red-700 transition hover:border-red-300 hover:bg-red-100">
                                                Eliminar
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-text-gray">
                                Todavía no tienes invitaciones. <a href="{{ route('panel.invitaciones.create') }}" class="font-semibold text-purple-brand hover:text-orange-brand">Crear la primera →</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
