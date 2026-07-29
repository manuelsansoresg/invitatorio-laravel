@extends('layouts.admin', ['title' => 'Nuevo cupón'])

@section('content')
    <div class="mb-8">
        <p class="text-sm font-semibold uppercase tracking-[0.18em] text-orange-brand">Promociones</p>
        <h1 class="mt-2 font-display text-3xl font-extrabold text-purple-dark">Nuevo cupón</h1>
        <p class="mt-2 max-w-2xl text-sm text-text-gray">
            El cupón se aplica con <span class="font-mono text-purple-brand">?coupon=CODIGO</span> en la URL de checkout.
            Si no asignas paquetes, aplica a todos.
        </p>
    </div>

    @include('admin.cupones._form', [
        'cupon'                   => $cupon,
        'paquetes'                => $paquetes,
        'paqueteIdsSeleccionados' => $paqueteIdsSeleccionados,
        'action'                  => route('admin.cupones.store'),
        'method'                  => 'POST',
    ])
@endsection
