@extends('layouts.admin', ['title' => 'Gestionar usuario', 'wide' => true])

@section('content')
    <div class="mb-8 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.18em] text-orange-brand">Usuarios</p>
            <h1 class="mt-2 font-display text-3xl font-extrabold text-purple-dark">{{ $user->name }}</h1>
            <p class="mt-2 text-sm text-text-gray">{{ $user->email }} · <span class="rounded-full bg-purple-soft px-2 py-0.5 text-xs font-bold text-purple-brand">{{ $user->role }}</span></p>
        </div>
        <a href="{{ route('admin.users.index') }}" class="text-sm font-semibold text-purple-brand hover:text-orange-brand">← Volver al listado</a>
    </div>

    @if (session('status'))
        <div class="mb-6 rounded-md border border-green-200 bg-green-50 px-4 py-3 text-sm font-semibold text-green-800">
            {{ session('status') }}
        </div>
    @endif

    <div class="grid gap-8 lg:grid-cols-2">
        {{-- ══════════════════════ DATOS BÁSICOS ══════════════════════ --}}
        <section class="rounded-lg border border-border-soft bg-white p-5 shadow-sm">
            <h2 class="mb-4 font-display text-lg font-bold text-text-dark">Datos básicos</h2>
            <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label for="name" class="block text-sm font-semibold text-text-dark">Nombre</label>
                    <input id="name" name="name" value="{{ old('name', $user->name) }}" required maxlength="255"
                           class="mt-2 w-full rounded-md border border-border-soft px-3 py-2.5 text-sm outline-none focus:border-orange-brand focus:ring-2 focus:ring-orange-soft">
                </div>
                <div>
                    <label for="email" class="block text-sm font-semibold text-text-dark">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required maxlength="255"
                           class="mt-2 w-full rounded-md border border-border-soft px-3 py-2.5 text-sm outline-none focus:border-orange-brand focus:ring-2 focus:ring-orange-soft">
                </div>
                <div>
                    <label for="role" class="block text-sm font-semibold text-text-dark">Rol</label>
                    <select id="role" name="role" required
                            class="mt-2 w-full cursor-pointer rounded-md border border-border-soft bg-white px-3 py-2.5 text-sm outline-none focus:border-orange-brand focus:ring-2 focus:ring-orange-soft">
                        @foreach ($roles as $r)
                            <option value="{{ $r }}" @selected(old('role', $user->role) === $r)>{{ ucfirst($r) }}</option>
                        @endforeach
                    </select>
                </div>
                <label class="flex cursor-pointer items-start gap-3 rounded-md p-2 transition hover:bg-cream-bg">
                    <input type="hidden" name="activo" value="0">
                    <input type="checkbox" name="activo" value="1" @checked(old('activo', $user->activo))
                           class="mt-0.5 h-4 w-4 rounded border-border-soft text-orange-brand focus:ring-orange-soft">
                    <span>
                        <span class="block text-sm font-semibold text-text-dark">Cuenta activa</span>
                        <span class="block text-xs text-text-gray">Si está apagada, el usuario no puede iniciar sesión.</span>
                    </span>
                </label>
                <button type="submit" class="w-full rounded-md bg-purple-brand px-4 py-2.5 text-sm font-bold text-white transition hover:bg-purple-dark">
                    Guardar cambios
                </button>
            </form>

            <div class="mt-6 border-t border-border-soft pt-5">
                <h3 class="mb-3 font-display text-base font-bold text-text-dark">Cambiar contraseña</h3>
                <form method="POST" action="{{ route('admin.users.password', $user) }}" class="space-y-3">
                    @csrf
                    @method('PUT')
                    <div>
                        <label for="new_password" class="block text-sm font-semibold text-text-dark">Nueva contraseña</label>
                        <input id="new_password" name="password" type="password" required minlength="8"
                               class="mt-2 w-full rounded-md border border-border-soft px-3 py-2.5 text-sm outline-none focus:border-orange-brand focus:ring-2 focus:ring-orange-soft">
                    </div>
                    <div>
                        <label for="new_password_confirmation" class="block text-sm font-semibold text-text-dark">Confirmar</label>
                        <input id="new_password_confirmation" name="password_confirmation" type="password" required minlength="8"
                               class="mt-2 w-full rounded-md border border-border-soft px-3 py-2.5 text-sm outline-none focus:border-orange-brand focus:ring-2 focus:ring-orange-soft">
                    </div>
                    <button type="submit" class="w-full rounded-md border border-amber-200 bg-amber-50 px-4 py-2.5 text-sm font-bold text-amber-800 transition hover:border-amber-300 hover:bg-amber-100">
                        Resetear contraseña
                    </button>
                </form>
            </div>
        </section>

        {{-- ══════════════════════ SUSCRIPCIÓN ══════════════════════ --}}
        <section class="rounded-lg border border-border-soft bg-white p-5 shadow-sm">
            <h2 class="mb-4 font-display text-lg font-bold text-text-dark">Suscripción</h2>

            @if ($user->suscripciones->isEmpty())
                <p class="mb-4 text-sm text-text-gray">Este usuario aún no tiene suscripciones. Créale una manual o espera a que compre un paquete.</p>
            @else
                <div class="space-y-3">
                    @foreach ($user->suscripciones as $s)
                        <div class="rounded-md border border-border-soft p-3">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <p class="font-semibold text-text-dark">{{ $s->paquete->nombre ?? '—' }}</p>
                                    <p class="text-xs text-text-gray">
                                        {{ $s->motivo }} ·
                                        {{ $s->max_invitaciones }} invit ·
                                        {{ $s->invitaciones_usadas }} usadas
                                    </p>
                                    @if ($s->fecha_fin)
                                        <p class="mt-0.5 text-xs text-text-gray">Vence: {{ $s->fecha_fin->format('d/m/Y') }}</p>
                                    @endif
                                </div>
                                <div class="text-right">
                                    <span class="inline-flex rounded-full px-2.5 py-1 text-[11px] font-bold uppercase tracking-wider
                                        {{ match($s->estado) { 'activa' => 'bg-green-100 text-green-800', 'agotada' => 'bg-amber-100 text-amber-800', 'vencida' => 'bg-red-100 text-red-700', 'cancelada' => 'bg-slate-200 text-slate-700', default => 'bg-slate-100 text-slate-500' } }}">
                                        {{ $s->estado_legible }}
                                    </span>
                                    @if (! $s->cancelada)
                                        <form method="POST" action="{{ route('admin.users.suscripcion.cancel', [$user, $s]) }}" class="mt-2">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-xs font-semibold text-red-700 hover:underline"
                                                    onclick="return confirm('¿Cancelar la suscripción #{{ $s->id }}? El cupo no se devuelve.')">
                                                Cancelar
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                            @if ($s->notas_admin)
                                <p class="mt-2 text-xs text-text-gray">{{ $s->notas_admin }}</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif

            <div class="mt-6 border-t border-border-soft pt-5">
                <h3 class="mb-3 font-display text-base font-bold text-text-dark">Crear suscripción manual</h3>
                <form method="POST" action="{{ route('admin.users.suscripcion.store', $user) }}" class="space-y-3">
                    @csrf
                    <div>
                        <label for="paquete_id" class="block text-sm font-semibold text-text-dark">Paquete</label>
                        <select id="paquete_id" name="paquete_id" required
                                class="mt-2 w-full cursor-pointer rounded-md border border-border-soft bg-white px-3 py-2.5 text-sm outline-none focus:border-orange-brand focus:ring-2 focus:ring-orange-soft">
                            @foreach ($paquetes as $p)
                                <option value="{{ $p->id }}">{{ $p->nombre }} ({{ $p->cupo_legible }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label for="max_invitaciones" class="block text-sm font-semibold text-text-dark">Cupo (opcional)</label>
                            <input id="max_invitaciones" name="max_invitaciones" type="number" min="1" step="1"
                                   placeholder="Heredado del paquete"
                                   class="mt-2 w-full rounded-md border border-border-soft px-3 py-2.5 text-sm outline-none focus:border-orange-brand focus:ring-2 focus:ring-orange-soft">
                        </div>
                        <div>
                            <label for="fecha_fin" class="block text-sm font-semibold text-text-dark">Vence (opcional)</label>
                            <input id="fecha_fin" name="fecha_fin" type="date"
                                   class="mt-2 w-full rounded-md border border-border-soft px-3 py-2.5 text-sm outline-none focus:border-orange-brand focus:ring-2 focus:ring-orange-soft">
                        </div>
                    </div>
                    <div>
                        <label for="motivo" class="block text-sm font-semibold text-text-dark">Motivo</label>
                        <select id="motivo" name="motivo" required
                                class="mt-2 w-full cursor-pointer rounded-md border border-border-soft bg-white px-3 py-2.5 text-sm outline-none focus:border-orange-brand focus:ring-2 focus:ring-orange-soft">
                            <option value="manual">Cortesía / interna</option>
                            <option value="regalo">Regalo / premio</option>
                        </select>
                    </div>
                    <div>
                        <label for="notas_admin" class="block text-sm font-semibold text-text-dark">Nota interna</label>
                        <textarea id="notas_admin" name="notas_admin" rows="2" maxlength="500"
                                  placeholder="Ej. Cortesía por colaboración en redes."
                                  class="mt-2 w-full rounded-md border border-border-soft px-3 py-2.5 text-sm outline-none focus:border-orange-brand focus:ring-2 focus:ring-orange-soft"></textarea>
                    </div>
                    <button type="submit" class="w-full rounded-md bg-orange-brand px-4 py-2.5 text-sm font-bold text-white transition hover:bg-orange-intense">
                        Crear suscripción
                    </button>
                </form>
            </div>
        </section>
    </div>

    {{-- ══════════════════════ TEMPLATES ══════════════════════ --}}
    <section class="mt-8 rounded-lg border border-border-soft bg-white p-5 shadow-sm">
        <div class="mb-4 flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h2 class="font-display text-lg font-bold text-text-dark">Templates que puede ver este usuario</h2>
                <p class="text-sm text-text-gray">Marca los que el cliente podrá usar para crear invitaciones. Si el admin quita uno después, las invitaciones viejas siguen funcionando pero el cliente no lo verá en el catálogo.</p>
            </div>
            <button type="button" class="text-sm font-semibold text-purple-brand hover:text-orange-brand"
                    onclick="document.querySelectorAll('input[name=&quot;template_activo[]&quot;]').forEach(c => c.checked = true)">
                ✓ Habilitar todos
            </button>
        </div>

        <form method="POST" action="{{ route('admin.users.templates', $user) }}">
            @csrf
            @method('PUT')
            @php
                $asignados = $user->templates->pluck('pivot.activo', 'id');
            @endphp

            @if ($templates->isEmpty())
                <p class="text-sm text-text-gray">No hay templates en el catálogo todavía. <a href="{{ route('admin.templates.create') }}" class="font-semibold text-purple-brand hover:text-orange-brand">Crear uno →</a></p>
            @else
                <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($templates as $t)
                        @php($isActive = $asignados[$t->id] ?? false)
                        <label class="flex cursor-pointer items-start gap-3 rounded-md border border-border-soft p-3 transition hover:border-orange-brand has-[:checked]:border-orange-brand has-[:checked]:bg-orange-soft/40">
                            <input type="hidden" name="template_activo[{{ $t->id }}]" value="0">
                            <input type="checkbox" name="template_activo[{{ $t->id }}]" value="1" @checked($isActive || old("template_activo.$t->id", false))
                                   class="mt-0.5 h-4 w-4 rounded border-border-soft text-orange-brand focus:ring-orange-soft">
                            <span class="min-w-0">
                                <span class="block text-sm font-semibold text-text-dark">{{ $t->nombre }}</span>
                                <span class="block text-xs text-text-gray">{{ $t->formato }} · {{ $t->slug }}</span>
                                @if ($t->descripcion)
                                    <span class="mt-0.5 block text-xs text-text-gray">{{ $t->descripcion }}</span>
                                @endif
                            </span>
                        </label>
                    @endforeach
                </div>

                <div class="mt-5 flex justify-end">
                    <button type="submit" class="rounded-md bg-purple-brand px-5 py-2.5 text-sm font-bold text-white transition hover:bg-purple-dark">
                        Guardar templates
                    </button>
                </div>
            @endif
        </form>
    </section>

    {{-- ══════════════════════ ÓRDENES RECIENTES ══════════════════════ --}}
    @if ($user->ordenes->isNotEmpty())
        <section class="mt-8 rounded-lg border border-border-soft bg-white p-5 shadow-sm">
            <h2 class="mb-4 font-display text-lg font-bold text-text-dark">Órdenes recientes</h2>
            <table class="w-full divide-y divide-border-soft text-sm">
                <thead class="text-left text-xs font-bold uppercase tracking-wide text-text-gray">
                    <tr>
                        <th class="pb-2">Orden</th>
                        <th class="pb-2">Paquete</th>
                        <th class="pb-2 text-center">Estado MP</th>
                        <th class="pb-2 text-right">Fecha</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-soft">
                    @foreach ($user->ordenes as $o)
                        <tr>
                            <td class="py-2 font-semibold text-text-dark">#{{ str_pad((string) $o->id, 6, '0', STR_PAD_LEFT) }}</td>
                            <td class="py-2 text-text-gray">{{ $o->paquete_nombre }}</td>
                            <td class="py-2 text-center">
                                <span class="rounded-full px-2 py-0.5 text-xs font-bold
                                    {{ $o->estado === 'approved' ? 'bg-green-100 text-green-800' : ($o->estado === 'pending' ? 'bg-amber-100 text-amber-800' : 'bg-slate-100 text-slate-500') }}">
                                    {{ $o->estado }}
                                </span>
                            </td>
                            <td class="py-2 text-right text-text-gray">{{ $o->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </section>
    @endif
@endsection
