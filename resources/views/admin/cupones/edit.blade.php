@extends('layouts.admin', ['title' => 'Editar cupón'])

@section('content')
    <div class="mb-8 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.18em] text-orange-brand">Promociones</p>
            <h1 class="mt-2 font-display text-3xl font-extrabold text-purple-dark">
                Cupón <span class="font-mono text-orange-brand">{{ $cupon->codigo }}</span>
            </h1>
            <p class="mt-2 max-w-2xl text-sm text-text-gray">
                Link rápido para probar:
                <button type="button" class="font-mono text-purple-brand underline decoration-dotted" onclick="navigator.clipboard.writeText(this.dataset.url); this.textContent='¡Copiado!'"
                        data-url="{{ route('checkout.show', ['paquete' => 'web-plus', 'coupon' => $cupon->codigo]) }}">
                    {{ route('checkout.show', ['paquete' => 'web-plus', 'coupon' => $cupon->codigo]) }}
                </button>
            </p>
        </div>
    </div>

    @if (session('status'))
        <div class="mb-6 rounded-md border border-green-200 bg-green-50 px-4 py-3 text-sm font-semibold text-green-800">
            {{ session('status') }}
        </div>
    @endif

    @include('admin.cupones._form', [
        'cupon'                   => $cupon,
        'paquetes'                => $paquetes,
        'paqueteIdsSeleccionados' => $paqueteIdsSeleccionados,
        'action'                  => route('admin.cupones.update', $cupon),
        'method'                  => 'PUT',
    ])
@endsection
