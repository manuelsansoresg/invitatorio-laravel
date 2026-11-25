@php
    $assignmentError = old('assignment_source') === $invitacion->ruta
        ? $errors->clientAssignment->first('user_id')
        : null;
    $selectedClient = $assignmentError ? old('user_id') : $invitacion->user_id;
@endphp

<details class="mt-2" @if ($assignmentError) open @endif>
    <summary class="cursor-pointer rounded-lg border border-border-soft bg-white px-3 py-2 text-xs font-bold text-purple-brand hover:border-purple-brand">
        {{ $invitacion->cliente ? 'Cambiar cliente' : 'Asignar cliente' }}
    </summary>

    @if ($clientes->isEmpty())
        <p class="mt-2 text-xs text-text-gray">
            <a href="#name" class="font-bold text-purple-brand underline">Crea un usuario con rol Cliente</a> para asignarlo.
        </p>
    @else
        <form method="POST" action="{{ route('admin.invitaciones.cliente.update', $invitacion) }}" class="mt-2">
            @csrf
            @method('PATCH')
            <input type="hidden" name="assignment_source" value="{{ $invitacion->ruta }}">
            <select name="user_id" aria-label="Cliente de {{ $invitacion->nombre_completo }}" class="w-full rounded-md border border-border-soft bg-white px-2 py-2 text-xs text-text-dark">
                <option value="">Sin asignar</option>
                @foreach ($clientes as $cliente)
                    <option value="{{ $cliente->id }}" @selected((string) $selectedClient === (string) $cliente->id)>
                        {{ $cliente->name }} · {{ $cliente->email }}
                    </option>
                @endforeach
            </select>
            @if ($assignmentError)
                <p class="mt-1 text-xs text-red-700" role="alert">{{ $assignmentError }}</p>
            @endif
            <button type="submit" class="mt-2 cursor-pointer rounded-lg bg-purple-brand px-3 py-2 text-xs font-bold text-white hover:bg-purple-dark">
                Guardar cliente
            </button>
        </form>
    @endif
</details>
