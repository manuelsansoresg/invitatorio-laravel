@extends('layouts.admin', ['title' => 'Nueva invitación', 'wide' => true])

@section('content')
    <div class="mb-8">
        <p class="text-sm font-semibold uppercase tracking-[0.18em] text-orange-brand">Mi panel</p>
        <h1 class="mt-2 font-display text-3xl font-extrabold text-purple-dark">Elige un template</h1>
        <p class="mt-2 max-w-2xl text-sm text-text-gray">
            Estos son los templates que el administrador habilitó para tu cuenta. Si necesitas uno que no está, pídele al admin que te lo habilite.
        </p>
    </div>

    @if ($errors->any())
        <div class="mb-6 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    @if (! $suscripcion)
        <div class="mb-6 rounded-md border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-900">
            <p class="font-semibold">No tienes una suscripción activa.</p>
            <p class="mt-1 text-xs">Compra un paquete en la <a href="{{ url('/#paquetes') }}" class="underline">landing</a> o pídele al admin que te asigne una cortesía para poder crear invitaciones.</p>
        </div>
    @endif

    @if ($templates->isEmpty())
        <div class="rounded-md border border-border-soft bg-white p-8 text-center text-text-gray">
            <p class="font-semibold">No tienes templates disponibles todavía.</p>
            <p class="mt-2 text-sm">Pídele al admin que te habilite alguno desde la sección de Usuarios.</p>
        </div>
    @else
        <form method="POST" action="{{ route('panel.invitaciones.store') }}">
            @csrf
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($templates as $t)
                    <label class="group flex cursor-pointer flex-col overflow-hidden rounded-lg border-2 border-border-soft bg-white transition hover:border-orange-brand has-[:checked]:border-orange-brand has-[:checked]:ring-4 has-[:checked]:ring-orange-soft">
                        <input type="radio" name="template_id" value="{{ $t->id }}" required class="sr-only" @checked(old('template_id') == $t->id)>
                        <div class="aspect-video w-full bg-gradient-to-br from-purple-soft to-orange-soft">
                            @if ($t->imagen_preview_path)
                                <img src="{{ asset($t->imagen_preview_path) }}" alt="{{ $t->nombre }}" class="h-full w-full object-cover">
                            @else
                                <div class="flex h-full items-center justify-center">
                                    <span class="font-display text-2xl font-extrabold text-purple-dark">{{ $t->nombre }}</span>
                                </div>
                            @endif
                        </div>
                        <div class="flex flex-1 flex-col p-4">
                            <p class="font-display text-base font-extrabold text-purple-dark">{{ $t->nombre }}</p>
                            <p class="mt-1 text-xs uppercase tracking-wider text-orange-intense">{{ $t->formato }}</p>
                            @if ($t->descripcion)
                                <p class="mt-2 text-sm text-text-gray">{{ $t->descripcion }}</p>
                            @endif
                        </div>
                    </label>
                @endforeach
            </div>

            <div class="mt-6 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-end">
                <a href="{{ route('panel.invitaciones.index') }}" class="rounded-md border border-border-soft bg-white px-5 py-3 text-center text-sm font-bold text-purple-brand transition hover:border-purple-brand">
                    Cancelar
                </a>
                <button type="submit" @disabled(! $suscripcion)
                        class="rounded-md bg-orange-brand px-6 py-3 text-sm font-bold text-white transition hover:bg-orange-intense disabled:cursor-not-allowed disabled:opacity-50">
                    Crear con este template
                </button>
            </div>
        </form>
    @endif
@endsection
