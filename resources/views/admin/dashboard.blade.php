@extends('layouts.admin', ['title' => 'Panel admin', 'wide' => true])

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
                <table class="min-w-[1180px] divide-y divide-border-soft text-sm xl:min-w-full">
                    <thead class="bg-purple-soft/70 text-left text-xs font-bold uppercase tracking-wide text-purple-dark">
                        <tr>
                            <th class="min-w-48 px-5 py-4">Nombre</th>
                            <th class="min-w-32 px-5 py-4">Ruta</th>
                            <th class="min-w-36 px-5 py-4 text-center">Confirmaciones</th>
                            <th class="min-w-[300px] px-5 py-4">Cliente</th>
                            <th class="min-w-32 px-5 py-4">Creada</th>
                            <th class="min-w-[280px] px-5 py-4">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border-soft">
                        @forelse ($invitaciones as $invitacion)
                            <tr>
                                <td class="px-5 py-5 font-semibold text-text-dark">{{ $invitacion->nombre_completo }}</td>
                                <td class="px-5 py-5 text-text-gray">{{ $invitacion->ruta }}</td>
                                <td class="px-5 py-5 text-center font-semibold text-text-gray">{{ $invitacion->confirmaciones_count }}</td>
                                <td class="px-5 py-5 align-top">
                                    <div class="flex min-w-[270px] items-center justify-between gap-4">
                                        @if ($invitacion->cliente)
                                            <div class="min-w-0">
                                                <div class="flex items-center gap-2">
                                                    <span class="h-2.5 w-2.5 shrink-0 rounded-full bg-green-500"></span>
                                                    <p class="truncate font-bold text-text-dark">{{ $invitacion->cliente->name }}</p>
                                                </div>
                                                <p class="mt-1 truncate text-xs text-text-gray">{{ $invitacion->cliente->email }}</p>
                                            </div>
                                        @else
                                            <div>
                                                <p class="font-bold text-text-dark">Sin cliente asignado</p>
                                                <p class="mt-1 text-xs text-text-gray">Disponible para asignar.</p>
                                            </div>
                                        @endif

                                        <button
                                            type="button"
                                            class="shrink-0 cursor-pointer text-sm font-extrabold text-purple-brand underline decoration-purple-brand/30 underline-offset-4 transition hover:text-orange-brand"
                                            onclick="document.getElementById('assign-client-{{ $invitacion->id }}').showModal()"
                                        >
                                            {{ $invitacion->cliente ? 'Editar' : 'Asignar' }}
                                        </button>
                                    </div>

                                    <dialog
                                        id="assign-client-{{ $invitacion->id }}"
                                        aria-labelledby="assign-client-title-{{ $invitacion->id }}"
                                        class="fixed inset-0 m-auto max-h-[calc(100vh-2rem)] w-[calc(100vw-2rem)] max-w-md overflow-y-auto rounded-2xl border border-border-soft bg-white p-0 text-left shadow-2xl backdrop:bg-slate-950/50"
                                        style="position: fixed; inset: 0; width: min(28rem, calc(100vw - 2rem)); max-height: calc(100vh - 2rem); margin: auto;"
                                    >
                                        <form method="POST" action="{{ route('admin.invitaciones.cliente.update', $invitacion) }}" class="p-6">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="assignment_source" value="{{ $invitacion->ruta }}">

                                            <div class="flex items-start justify-between gap-4">
                                                <div>
                                                    <p class="text-xs font-extrabold uppercase tracking-[0.18em] text-orange-brand">Cliente de la invitación</p>
                                                    <h3 id="assign-client-title-{{ $invitacion->id }}" class="mt-2 font-display text-xl font-extrabold text-purple-dark">
                                                        {{ $invitacion->cliente ? 'Editar asignación' : 'Asignar cliente' }}
                                                    </h3>
                                                </div>
                                                <button type="button" onclick="this.closest('dialog').close()" aria-label="Cerrar" class="grid h-9 w-9 cursor-pointer place-items-center rounded-full border border-border-soft text-slate-500 transition hover:border-purple-brand hover:text-purple-brand">
                                                    <span aria-hidden="true">×</span>
                                                </button>
                                            </div>

                                            <p class="mt-3 text-sm leading-relaxed text-text-gray">
                                                Selecciona quién administrará <strong>{{ $invitacion->nombre_completo }}</strong>. También puedes dejarla sin cliente.
                                            </p>

                                            <label for="assignment-client-{{ $invitacion->id }}" class="mt-5 block text-sm font-bold text-text-dark">
                                                Cliente
                                                <select id="assignment-client-{{ $invitacion->id }}" name="user_id" class="mt-2 w-full cursor-pointer rounded-xl border border-border-soft bg-white px-4 py-3 text-sm outline-none transition focus:border-purple-brand focus:ring-4 focus:ring-purple-soft">
                                                    <option value="">Sin cliente</option>
                                                    @foreach ($clientes as $cliente)
                                                        <option value="{{ $cliente->id }}" @selected((string) (old('assignment_source') === $invitacion->ruta ? old('user_id') : $invitacion->user_id) === (string) $cliente->id)>
                                                            {{ $cliente->name }} · {{ $cliente->email }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </label>
                                            @if (old('assignment_source') === $invitacion->ruta)
                                                @error('user_id', 'clientAssignment')
                                                    <p class="mt-2 text-sm font-semibold text-red-600">{{ $message }}</p>
                                                @enderror
                                            @endif

                                            <div class="mt-6 grid grid-cols-2 gap-3">
                                                <button type="button" onclick="this.closest('dialog').close()" class="cursor-pointer rounded-xl border border-border-soft bg-white px-4 py-3 text-sm font-bold text-purple-brand transition hover:border-purple-brand">
                                                    Cancelar
                                                </button>
                                                <button type="submit" class="cursor-pointer rounded-xl bg-purple-brand px-4 py-3 text-sm font-extrabold text-white transition hover:bg-purple-dark">
                                                    Guardar asignación
                                                </button>
                                            </div>
                                        </form>
                                    </dialog>
                                </td>
                                <td class="whitespace-nowrap px-5 py-5 text-text-gray">{{ $invitacion->created_at?->format('d/m/Y') }}</td>
                                <td class="px-5 py-5 align-middle">
                                    <div class="flex min-w-[265px] flex-wrap gap-2">
                                        <a href="{{ route('panel.confirmados.index') }}" class="inline-flex items-center rounded-lg border border-border-soft bg-white px-3 py-2 text-xs font-bold text-purple-brand transition hover:border-purple-brand hover:bg-purple-soft">
                                            Confirmados
                                        </a>
                                        <a href="{{ route('admin.invitaciones.edit', $invitacion) }}" class="inline-flex items-center rounded-lg border border-border-soft bg-white px-3 py-2 text-xs font-bold text-purple-brand transition hover:border-purple-brand hover:bg-purple-soft">
                                            Editar
                                        </a>
                                        <button
                                            type="button"
                                            class="inline-flex cursor-pointer items-center rounded-lg border border-border-soft bg-white px-3 py-2 text-xs font-bold text-purple-brand transition hover:border-purple-brand hover:bg-purple-soft"
                                            onclick="document.getElementById('clone-invitation-{{ $invitacion->id }}').showModal()"
                                        >
                                            Clonar
                                        </button>
                                        <a href="{{ url('/invitacion/'.$invitacion->ruta) }}" class="inline-flex items-center rounded-lg bg-purple-brand px-3 py-2 text-xs font-bold text-white transition hover:bg-purple-dark" target="_blank" rel="noopener">
                                            Abrir
                                        </a>
                                        <button
                                            type="button"
                                            class="inline-flex cursor-pointer items-center rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-xs font-bold text-red-700 transition hover:border-red-300 hover:bg-red-100"
                                            onclick="document.getElementById('delete-invitation-{{ $invitacion->id }}').showModal()"
                                        >
                                            Eliminar
                                        </button>
                                    </div>

                                    <dialog
                                        id="clone-invitation-{{ $invitacion->id }}"
                                        aria-labelledby="clone-title-{{ $invitacion->id }}"
                                        class="fixed inset-0 m-auto max-h-[calc(100vh-2rem)] w-[calc(100vw-2rem)] max-w-md overflow-y-auto rounded-2xl border border-border-soft bg-white p-0 text-left shadow-2xl backdrop:bg-slate-950/50"
                                        style="position: fixed; inset: 0; width: min(28rem, calc(100vw - 2rem)); max-height: calc(100vh - 2rem); margin: auto;"
                                    >
                                        <form method="POST" action="{{ route('admin.invitaciones.clone', $invitacion) }}" class="p-6">
                                            @csrf
                                            <input type="hidden" name="clone_source" value="{{ $invitacion->ruta }}">

                                            <div class="flex items-start justify-between gap-4">
                                                <div>
                                                    <p class="text-xs font-extrabold uppercase tracking-[0.18em] text-orange-brand">Clonar invitación</p>
                                                    <h3 id="clone-title-{{ $invitacion->id }}" class="mt-2 font-display text-xl font-extrabold text-purple-dark">Nueva invitación editable</h3>
                                                </div>
                                                <button
                                                    type="button"
                                                    class="grid h-9 w-9 cursor-pointer place-items-center rounded-full border border-border-soft text-slate-500 transition hover:border-purple-brand hover:text-purple-brand"
                                                    onclick="this.closest('dialog').close()"
                                                    aria-label="Cerrar"
                                                >
                                                    <span aria-hidden="true">×</span>
                                                </button>
                                            </div>

                                            <p class="mt-3 text-sm leading-relaxed text-text-gray">
                                                Se copiará <strong>{{ $invitacion->nombre_completo }}</strong> como borrador independiente. La ruta se generará automáticamente con el nuevo nombre.
                                            </p>

                                            <label class="mt-5 block text-sm font-bold text-text-dark" for="clone-name-{{ $invitacion->id }}">
                                                Nombre completo
                                                <input
                                                    id="clone-name-{{ $invitacion->id }}"
                                                    name="nombre_completo"
                                                    value="{{ old('clone_source') === $invitacion->ruta ? old('nombre_completo') : '' }}"
                                                    required
                                                    autocomplete="off"
                                                    placeholder="Ej. Valeria Hernández López"
                                                    class="mt-2 w-full rounded-xl border border-border-soft px-4 py-3 text-sm outline-none transition focus:border-purple-brand focus:ring-4 focus:ring-purple-soft"
                                                >
                                            </label>
                                            @if (old('clone_source') === $invitacion->ruta)
                                                @error('nombre_completo', 'cloneInvitation')
                                                    <p class="mt-2 text-sm font-semibold text-red-600">{{ $message }}</p>
                                                @enderror
                                            @endif

                                            <div class="mt-6 grid grid-cols-2 gap-3">
                                                <button type="button" onclick="this.closest('dialog').close()" class="cursor-pointer rounded-xl border border-border-soft bg-white px-4 py-3 text-sm font-bold text-purple-brand transition hover:border-purple-brand">
                                                    Cancelar
                                                </button>
                                                <button type="submit" class="cursor-pointer rounded-xl bg-purple-brand px-4 py-3 text-sm font-extrabold text-white transition hover:bg-purple-dark">
                                                    Crear clon
                                                </button>
                                            </div>
                                        </form>
                                    </dialog>

                                    <dialog
                                        id="delete-invitation-{{ $invitacion->id }}"
                                        aria-labelledby="delete-title-{{ $invitacion->id }}"
                                        class="fixed inset-0 m-auto max-h-[calc(100vh-2rem)] w-[calc(100vw-2rem)] max-w-md overflow-y-auto rounded-2xl border border-red-100 bg-white p-0 text-left shadow-2xl backdrop:bg-slate-950/60"
                                        style="position: fixed; inset: 0; width: min(28rem, calc(100vw - 2rem)); max-height: calc(100vh - 2rem); margin: auto;"
                                    >
                                        <form method="POST" action="{{ route('admin.invitaciones.destroy', $invitacion) }}" class="p-6">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="delete_source" value="{{ $invitacion->ruta }}">

                                            <div class="flex items-start justify-between gap-4">
                                                <div>
                                                    <p class="text-xs font-extrabold uppercase tracking-[0.18em] text-red-600">Acción irreversible</p>
                                                    <h3 id="delete-title-{{ $invitacion->id }}" class="mt-2 font-display text-xl font-extrabold text-slate-950">Eliminar invitación</h3>
                                                </div>
                                                <button type="button" onclick="this.closest('dialog').close()" aria-label="Cerrar" class="grid h-9 w-9 cursor-pointer place-items-center rounded-full border border-border-soft text-slate-500 transition hover:border-red-300 hover:text-red-700">
                                                    <span aria-hidden="true">×</span>
                                                </button>
                                            </div>

                                            <div class="mt-5 rounded-xl border border-red-100 bg-red-50 p-4">
                                                <p class="font-extrabold text-red-800">{{ $invitacion->nombre_completo }}</p>
                                                <p class="mt-1 text-sm text-red-700">Ruta: <strong>{{ $invitacion->ruta }}</strong></p>
                                                <p class="mt-2 text-xs leading-relaxed text-red-700">
                                                    Se eliminarán la invitación, sus {{ $invitacion->confirmaciones_count }} confirmaciones, bloques, galería y archivos propios.
                                                </p>
                                            </div>

                                            <label for="delete-confirm-{{ $invitacion->id }}" class="mt-5 block text-sm font-bold text-text-dark">
                                                Escribe <span class="font-mono text-red-700">{{ $invitacion->ruta }}</span> para confirmar
                                                <input
                                                    id="delete-confirm-{{ $invitacion->id }}"
                                                    name="confirm_route"
                                                    value="{{ old('delete_source') === $invitacion->ruta ? old('confirm_route') : '' }}"
                                                    autocomplete="off"
                                                    required
                                                    data-confirm-value="{{ $invitacion->ruta }}"
                                                    oninput="this.form.querySelector('[data-delete-submit]').disabled = this.value.trim() !== this.dataset.confirmValue"
                                                    class="mt-2 w-full rounded-xl border border-red-200 px-4 py-3 font-mono text-sm outline-none transition focus:border-red-500 focus:ring-4 focus:ring-red-100"
                                                >
                                            </label>
                                            @if (old('delete_source') === $invitacion->ruta)
                                                @error('confirm_route', 'deleteInvitation')
                                                    <p class="mt-2 text-sm font-semibold text-red-600">{{ $message }}</p>
                                                @enderror
                                            @endif

                                            <div class="mt-6 grid grid-cols-2 gap-3">
                                                <button type="button" onclick="this.closest('dialog').close()" class="cursor-pointer rounded-xl border border-border-soft bg-white px-4 py-3 text-sm font-bold text-purple-brand transition hover:border-purple-brand">
                                                    Cancelar
                                                </button>
                                                <button
                                                    type="submit"
                                                    data-delete-submit
                                                    @disabled(old('delete_source') !== $invitacion->ruta || old('confirm_route') !== $invitacion->ruta)
                                                    class="cursor-pointer rounded-xl bg-red-600 px-4 py-3 text-sm font-extrabold text-white transition hover:bg-red-700 disabled:cursor-not-allowed disabled:opacity-40"
                                                >
                                                    Eliminar definitivamente
                                                </button>
                                            </div>
                                        </form>
                                    </dialog>
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

        @if ($errors->cloneInvitation->any() && old('clone_source'))
            @php($failedClone = $invitaciones->firstWhere('ruta', old('clone_source')))
            @if ($failedClone)
                <script>
                    document.addEventListener('DOMContentLoaded', () => {
                        document.getElementById('clone-invitation-{{ $failedClone->id }}')?.showModal();
                    });
                </script>
            @endif
        @endif

        @if ($errors->clientAssignment->any() && old('assignment_source'))
            @php($failedAssignment = $invitaciones->firstWhere('ruta', old('assignment_source')))
            @if ($failedAssignment)
                <script>
                    document.addEventListener('DOMContentLoaded', () => {
                        document.getElementById('assign-client-{{ $failedAssignment->id }}')?.showModal();
                    });
                </script>
            @endif
        @endif

        @if ($errors->deleteInvitation->any() && old('delete_source'))
            @php($failedDeletion = $invitaciones->firstWhere('ruta', old('delete_source')))
            @if ($failedDeletion)
                <script>
                    document.addEventListener('DOMContentLoaded', () => {
                        document.getElementById('delete-invitation-{{ $failedDeletion->id }}')?.showModal();
                    });
                </script>
            @endif
        @endif
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
