@extends('layouts.admin', ['title' => 'Editar template'])

@section('content')
    <div class="mb-8">
        <p class="text-sm font-semibold uppercase tracking-[0.18em] text-orange-brand">Catálogo</p>
        <h1 class="mt-2 font-display text-3xl font-extrabold text-purple-dark">Editar template</h1>
    </div>

    @if (session('status'))
        <div class="mb-6 rounded-md border border-green-200 bg-green-50 px-4 py-3 text-sm font-semibold text-green-800">
            {{ session('status') }}
        </div>
    @endif

    @include('admin.templates._form', [
        'template' => $template,
        'formatos' => $formatos,
        'action'   => route('admin.templates.update', $template),
        'method'   => 'PUT',
    ])
@endsection
