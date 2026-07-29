@extends('layouts.admin', ['title' => 'Nuevo paquete'])

@section('content')
    <div class="mb-8">
        <p class="text-sm font-semibold uppercase tracking-[0.18em] text-orange-brand">Catálogo</p>
        <h1 class="mt-2 font-display text-3xl font-extrabold text-purple-dark">Nuevo paquete</h1>
        <p class="mt-2 max-w-2xl text-sm text-text-gray">Define el nombre, precio, formato y los beneficios que verá el cliente en la landing.</p>
    </div>

    @include('admin.paquetes._form', [
        'paquete'  => $paquete,
        'formatos' => $formatos,
        'action'   => route('admin.paquetes.store'),
        'method'   => 'POST',
    ])
@endsection
