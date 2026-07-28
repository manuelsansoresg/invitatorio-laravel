@extends('layouts.admin', ['title' => 'Editar paquete'])

@section('content')
    <div class="mb-8 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.18em] text-orange-brand">Catálogo</p>
            <h1 class="mt-2 font-display text-3xl font-extrabold text-purple-dark">Editar paquete</h1>
            <p class="mt-2 max-w-2xl text-sm text-text-gray">Cambios aplican de inmediato. Las órdenes viejas conservan el precio snapshot que tenían.</p>
        </div>
        <a href="{{ route('checkout.show', $paquete) }}" target="_blank" rel="noopener"
           class="inline-flex items-center justify-center gap-2 rounded-md border border-border-soft bg-white px-4 py-2.5 text-sm font-bold text-purple-brand transition hover:border-purple-brand">
            Ver checkout público →
        </a>
    </div>

    @if (session('status'))
        <div class="mb-6 rounded-md border border-green-200 bg-green-50 px-4 py-3 text-sm font-semibold text-green-800">
            {{ session('status') }}
        </div>
    @endif

    @include('admin.paquetes._form', [
        'paquete'  => $paquete,
        'formatos' => $formatos,
        'action'   => route('admin.paquetes.update', $paquete),
        'method'   => 'PUT',
    ])
@endsection
