@php
    $assignmentError = old('assignment_source') === $invitacion->ruta
        ? $errors->clientAssignment->first('user_id')
        : null;
@endphp

<form method="POST" action="{{ route('admin.invitaciones.cliente.update', $invitacion) }}">
    @csrf
    @method('PATCH')
    <input type="hidden" name="assignment_source" value="{{ $invitacion->ruta }}">
    <select name="user_id" aria-label="Asignar correo a {{ $invitacion->nombre_completo }}"
        onchange="this.form.requestSubmit()" class="w-full cursor-pointer rounded-md border border-border-soft bg-white px-2 py-2 text-xs text-text-dark">
        <option value="">{{ $invitacion->user_id === null ? 'Asignar correo' : 'Sin asignar' }}</option>
        @foreach ($clientes as $cliente)
            <option value="{{ $cliente->id }}" @selected($invitacion->user_id === $cliente->id)>{{ $cliente->email }}</option>
        @endforeach
    </select>
    @if ($assignmentError)
        <p class="mt-1 text-xs text-red-700" role="alert">{{ $assignmentError }}</p>
    @endif
    <noscript>
        <button type="submit" class="mt-2 rounded-lg bg-purple-brand px-3 py-2 text-xs font-bold text-white">Guardar</button>
    </noscript>
</form>
