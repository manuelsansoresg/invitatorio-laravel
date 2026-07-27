@extends('layouts.checkout', ['title' => 'Callback Mercado Pago'])

@section('content')
    <div class="mx-auto max-w-xl text-center">
        <div class="rounded-2xl border border-[#F1E6D9] bg-white p-8 shadow-sm">
            <div class="mx-auto mb-4 inline-flex h-12 w-12 items-center justify-center rounded-full bg-[#FFF1E1] text-[#EB7512]">
                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <circle cx="12" cy="12" r="9"/>
                    <path d="M12 8v4M12 16h.01"/>
                </svg>
            </div>

            <h1 class="font-display text-2xl font-extrabold text-[#2B143F]">Callback de Mercado Pago</h1>
            <p class="mt-2 text-[14px] text-[#5F5A66]">
                Esta URL está reservada para futuras integraciones OAuth. Hoy los pagos se procesan
                directamente con el access token de Invitatorio.
            </p>

            @if ($error)
                <p class="mt-4 rounded-lg bg-red-50 px-4 py-2 text-sm text-red-700">Error reportado: {{ $error }}</p>
            @endif

            <a href="{{ url('/') }}" class="mt-6 inline-flex items-center justify-center rounded-lg bg-[#EB7512] px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-[#F45A00]">
                Volver al inicio
            </a>
        </div>
    </div>
@endsection
