@php
    $blockIndexes = collect($blocks)->mapWithKeys(fn ($block, $index) => [$block['tipo'] => $index]);
    $blockIndex = fn (string $tipo) => $blockIndexes->get($tipo);
    $blockValue = fn (string $tipo, string $field, mixed $default = null) => data_get($blocks, $blockIndexes->get($tipo).'.'.$field, $default);
    $blockConfig = function (string $tipo, string $key, mixed $default = null) use ($blockValue) {
        $json = $blockValue($tipo, 'config_json', '{}');
        $decoded = json_decode($json ?: '{}', true);

        return data_get(is_array($decoded) ? $decoded : [], $key, $default);
    };

    $heroIndex = $blockIndex('hero');
    $countdownIndex = $blockIndex('cuenta_regresiva');
    $eventIndex = $blockIndex('informacion_evento');
    $dressIndex = $blockIndex('dress_code');
    $locationIndex = $blockIndex('ubicacion');
    $giftIndex = $blockIndex('mesa_regalos');
    $musicIndex = $blockIndex('musica');
    $whatsappIndex = $blockIndex('whatsapp');
    $imagePath = fn (mixed $path) => filled($path) && $path !== '__deleted' ? $path : null;
    $coverConfigPath = $blockConfig('hero', 'imagen_intro');
    $coverPath = $imagePath($coverConfigPath ?? ($form['imagen_portada_path'] ?: null));
    $coverUrl = $coverPath ? asset($coverPath) : null;
    $heroPath = $imagePath($blockConfig('hero', 'imagen_hero'));
    $heroUrl = $heroPath ? asset($heroPath) : null;
    $featurePath = $imagePath($blockConfig('hero', 'imagen_parallax'));
    $featureUrl = $featurePath ? asset($featurePath) : null;
    $galleryIndex = $blockIndex('galeria');
    $galleryCount = count($galleryItems);
    $musicPath = filled($form['musica_path'] ?? null) ? $form['musica_path'] : null;
    $musicUrl = $musicPath ? asset($musicPath) : null;
    $previewUrl = route('invitaciones.show', $invitacion);
    $previewFrameUrl = $previewUrl . '?editor_preview=' . $previewNonce;
@endphp

<div
    class="editor-shell"
    x-data="{
        saved: false,
        panelOpen: window.innerWidth >= 1024,
        openPanel() { this.panelOpen = true },
        closePanel() { this.panelOpen = false },
    }"
    x-on:saved.window="saved = true; setTimeout(() => saved = false, 2200)"
    x-on:keydown.escape.window="closePanel()"
>
    <style>
        [x-cloak] { display: none !important; }
        html, body { overflow: hidden; }
        .editor-shell {
            --editor-panel-width: clamp(390px, 31vw, 500px);
            position: fixed;
            inset: 0;
            overflow: hidden;
            background: #f7f4f0;
        }
        .editor-stage {
            position: absolute;
            inset: 0;
            background: #fff;
            transition: right .28s ease;
        }
        .editor-stage iframe {
            display: block;
            width: 100%;
            height: 100%;
            border: 0;
            background: #fff;
        }
        .editor-panel {
            position: absolute;
            z-index: 40;
            inset: 0 0 0 auto;
            display: flex;
            width: var(--editor-panel-width);
            flex-direction: column;
            border-left: 1px solid #e8e1ee;
            background: #fffdfa;
            box-shadow: -18px 0 48px rgba(35, 18, 51, .13);
            transform: translateX(100%);
            transition: transform .28s ease;
        }
        .editor-panel.is-open { transform: translateX(0); }
        .editor-panel-header {
            position: relative;
            z-index: 2;
            flex: none;
            border-bottom: 1px solid #ece6f1;
            background: rgba(255, 253, 250, .96);
            padding: 1rem;
            backdrop-filter: blur(14px);
        }
        .editor-panel-scroll {
            min-height: 0;
            flex: 1 1 auto;
            overflow-y: auto;
            overscroll-behavior: contain;
            padding: 1rem;
            scrollbar-gutter: stable;
        }
        .editor-toggle {
            position: fixed;
            z-index: 50;
            right: 1rem;
            top: 1rem;
            display: inline-flex;
            align-items: center;
            gap: .55rem;
            border: 1px solid rgba(90, 48, 135, .18);
            border-radius: 999px;
            background: #fff;
            padding: .7rem .9rem;
            color: #5A3087;
            font-size: .82rem;
            font-weight: 900;
            box-shadow: 0 12px 34px rgba(35, 18, 51, .18);
            cursor: pointer;
        }
        .editor-overlay {
            position: fixed;
            z-index: 30;
            inset: 0;
            background: rgba(20, 13, 27, .42);
            backdrop-filter: blur(2px);
        }
        .editor-input {
            width: 100%;
            border-radius: 0.8rem;
            border: 1px solid #e7e2ee;
            background: #fff;
            padding: 0.78rem 0.95rem;
            font-size: 0.92rem;
            color: #1f1930;
            outline: none;
            transition: border-color .2s ease, box-shadow .2s ease;
        }
        .editor-input:focus {
            border-color: #7c3aed;
            box-shadow: 0 0 0 4px rgba(124, 58, 237, .10);
        }
        .editor-card {
            border: 1px solid #ebe6f2;
            background: #fff;
            border-radius: 1.2rem;
            box-shadow: 0 8px 24px rgba(43, 20, 63, .045);
            scroll-margin-top: 1rem;
        }
        .editor-section-pill {
            display: inline-flex;
            align-items: center;
            border-radius: 999px;
            background: #f4eefb;
            color: #5A3087;
            padding: .35rem .75rem;
            font-size: .72rem;
            font-weight: 900;
            letter-spacing: .08em;
            text-transform: uppercase;
        }
        .editor-field-card {
            border: 1px solid #eee7f6;
            background: linear-gradient(180deg, #ffffff 0%, #fffdfa 100%);
            border-radius: 1.1rem;
            padding: 1rem;
        }
        @media (min-width: 1024px) {
            .editor-stage.with-panel { right: var(--editor-panel-width); }
        }
        @media (max-width: 1023px) {
            .editor-shell { --editor-panel-width: min(92vw, 440px); }
        }
        @media (max-width: 640px) {
            .editor-shell { --editor-panel-width: 100vw; }
            .editor-panel-header { padding: .8rem; }
            .editor-panel-scroll { padding: .75rem; }
            .editor-card { border-radius: 1rem; }
            .editor-field-card { padding: .85rem; }
        }
    </style>

    <div class="editor-stage" :class="panelOpen && 'with-panel'">
        <iframe
            wire:key="preview-frame-full-{{ $previewNonce }}"
            src="{{ $previewFrameUrl }}"
            title="Invitación de {{ $invitacion->nombre_completo }}"
        ></iframe>
    </div>

    <div x-show="panelOpen" x-cloak class="editor-overlay lg:hidden" x-on:click="closePanel()"></div>

    <button x-show="! panelOpen" x-cloak type="button" class="editor-toggle" x-on:click="openPanel()" aria-label="Abrir panel de edición">
        <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
        Editar invitación
    </button>

    <aside class="editor-panel" :class="panelOpen && 'is-open'" aria-label="Panel de edición">
        <header class="editor-panel-header">
            <div class="flex items-start justify-between gap-3">
                <div class="min-w-0">
                    <p class="text-[.68rem] font-extrabold uppercase tracking-[0.2em] text-orange-brand">Personalizar invitación</p>
                    <h1 class="mt-1 truncate font-display text-xl font-extrabold text-purple-dark">{{ $invitacion->nombre_completo }}</h1>
                    <p class="mt-1 text-xs font-semibold text-slate-500">Los cambios se guardan y reflejan automáticamente.</p>
                </div>
                <button type="button" class="grid h-10 w-10 shrink-0 cursor-pointer place-items-center rounded-full border border-border-soft bg-white text-purple-brand transition hover:border-purple-brand" x-on:click="closePanel()" aria-label="Cerrar panel de edición">
                    <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2"><path d="m15 18-6-6 6-6"/></svg>
                </button>
            </div>

            <div class="mt-4 grid grid-cols-[auto_1fr] gap-2">
                <a href="{{ route('admin.dashboard') }}" class="grid min-h-11 place-items-center rounded-xl border border-border-soft bg-white px-4 text-sm font-bold text-purple-brand transition hover:border-purple-brand">Volver</a>
                <div class="grid min-h-11 place-items-center rounded-xl bg-purple-soft px-5 text-sm font-extrabold text-purple-brand">
                    <span wire:loading.remove>Guardado automático</span>
                    <span wire:loading>Guardando cambios...</span>
                </div>
            </div>

            @if (session('status'))
                <div class="mt-3 rounded-xl border border-green-200 bg-green-50 px-3 py-2 text-xs font-bold text-green-800">{{ session('status') }}</div>
            @endif
        </header>

        <div class="editor-panel-scroll">
            <section class="space-y-4">
            <div id="section-cover" class="editor-card p-5 md:p-7">
                <span class="editor-section-pill">Editando portada</span>
                <h2 class="font-display text-xl font-extrabold text-slate-900">Portada</h2>
                <p class="mt-1 text-sm font-medium text-slate-500">Personaliza la portada de tu invitación.</p>

                <div class="editor-field-card mt-7" wire:key="cover-image-card-{{ $imageActionNonce }}">
                    <p class="text-sm font-extrabold text-slate-900">Imagen de portada / intro</p>
                    <p class="mt-1 text-sm font-medium text-slate-500">Esta imagen aparece en la primera portada antes de abrir la invitación.</p>

                    <div class="mt-4 grid gap-4">
                        <div class="h-72 w-full max-w-sm overflow-hidden rounded-xl border border-border-soft bg-purple-soft">
                            @if ($coverUrl)
                                <img src="{{ $coverUrl }}" alt="Portada" class="h-full w-full object-cover">
                            @endif
                        </div>
                        <input
                            id="coverImageInput"
                            type="file"
                            wire:model="coverImage"
                            accept="image/png,image/jpeg,image/webp"
                            style="display: none;"
                        >
                        <label for="coverImageInput" class="grid min-h-40 w-full cursor-pointer place-items-center rounded-xl border-2 border-dashed border-purple-brand/25 bg-white text-center text-purple-brand transition hover:border-purple-brand hover:bg-purple-soft/40">
                            <span>
                                <svg viewBox="0 0 24 24" class="mx-auto h-9 w-9" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 16V4"/><path d="M7 9l5-5 5 5"/><path d="M5 20h14"/></svg>
                                <span class="mt-2 block font-extrabold" wire:loading.remove wire:target="coverImage">Subir imagen</span>
                                <span class="mt-2 block font-extrabold" wire:loading wire:target="coverImage">Subiendo...</span>
                                <span class="mt-1 block text-xs font-semibold text-slate-500">PNG, JPG o WEBP<br>Máx. 5MB</span>
                            </span>
                        </label>
                        @error('coverImage')
                            <p class="text-sm font-semibold text-red-600">{{ $message }}</p>
                        @enderror
                        <button
                            type="button"
                            wire:click="deleteCoverImage"
                            wire:loading.attr="disabled"
                            wire:target="deleteCoverImage"
                            class="cursor-pointer rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-extrabold text-red-700 transition hover:border-red-300 hover:bg-red-100 disabled:cursor-not-allowed"
                        >
                            <span wire:loading.remove wire:target="deleteCoverImage">Eliminar imagen de portada</span>
                            <span wire:loading wire:target="deleteCoverImage">Eliminando...</span>
                        </button>
                    </div>
                </div>

                <div class="editor-field-card mt-4" wire:key="hero-image-card-{{ $imageActionNonce }}">
                    <p class="text-sm font-extrabold text-slate-900">Archivo de portada</p>
                    <p class="mt-1 text-sm font-medium text-slate-500">Puedes pegar la ruta del archivo que se usará como imagen principal.</p>
                    <label class="mt-4 block text-sm font-bold text-slate-800">
                        Ruta de imagen
                        <input wire:model.live.debounce.400ms="form.imagen_portada_path" class="editor-input mt-2" placeholder="uploads/invitaciones/{{ $invitacion->ruta }}/portada/archivo.jpg">
                    </label>
                </div>

                <div class="editor-field-card mt-4">
                    <p class="text-sm font-extrabold text-slate-900">Imagen principal del hero</p>
                    <p class="mt-1 text-sm font-medium text-slate-500">Esta es la foto grande que aparece en la sección principal de la invitación.</p>

                    <div class="mt-4 grid gap-4">
                        <div class="h-96 w-full overflow-hidden rounded-xl border border-border-soft bg-purple-soft">
                            @if ($heroUrl)
                                <img src="{{ $heroUrl }}" alt="Imagen principal del hero" class="h-full w-full object-cover object-top">
                            @endif
                        </div>

                        <input
                            id="heroImageInput"
                            type="file"
                            wire:model="heroImage"
                            accept="image/png,image/jpeg,image/webp"
                            style="display: none;"
                        >
                        <label for="heroImageInput" class="grid min-h-40 w-full cursor-pointer place-items-center rounded-xl border-2 border-dashed border-purple-brand/25 bg-white text-center text-purple-brand transition hover:border-purple-brand hover:bg-purple-soft/40">
                            <span>
                                <svg viewBox="0 0 24 24" class="mx-auto h-9 w-9" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 16V4"/><path d="M7 9l5-5 5 5"/><path d="M5 20h14"/></svg>
                                <span class="mt-2 block font-extrabold" wire:loading.remove wire:target="heroImage">Adjuntar imagen principal</span>
                                <span class="mt-2 block font-extrabold" wire:loading wire:target="heroImage">Subiendo...</span>
                                <span class="mt-1 block text-xs font-semibold text-slate-500">PNG, JPG o WEBP<br>Máx. 5MB</span>
                            </span>
                        </label>
                        @error('heroImage')
                            <p class="text-sm font-semibold text-red-600">{{ $message }}</p>
                        @enderror

                        <button
                            type="button"
                            wire:click="deleteHeroImage"
                            wire:loading.attr="disabled"
                            wire:target="deleteHeroImage"
                            class="cursor-pointer rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-extrabold text-red-700 transition hover:border-red-300 hover:bg-red-100 disabled:cursor-not-allowed"
                        >
                            <span wire:loading.remove wire:target="deleteHeroImage">Eliminar imagen principal</span>
                            <span wire:loading wire:target="deleteHeroImage">Eliminando...</span>
                        </button>
                    </div>

                    <label class="mt-4 block text-sm font-bold text-slate-800">
                        Ruta de imagen principal
                        <input value="{{ $heroPath ?? '' }}" readonly class="editor-input mt-2 bg-slate-50" placeholder="Sin imagen principal adjunta">
                    </label>
                </div>

                <div class="editor-field-card mt-4" wire:key="feature-image-card-{{ $imageActionNonce }}">
                    <p class="text-sm font-extrabold text-slate-900">Imagen destacada debajo del contador</p>
                    <p class="mt-1 text-sm font-medium text-slate-500">Solo puede existir una imagen en esta sección.</p>

                    @if ($featureUrl)
                        <div class="mt-4 h-64 w-full overflow-hidden rounded-xl border border-border-soft bg-purple-soft">
                            <img src="{{ $featureUrl }}" alt="Imagen destacada" class="h-full w-full object-cover">
                        </div>
                    @endif

                    <input
                        id="featureImageInput"
                        type="file"
                        wire:model="featureImage"
                        accept="image/png,image/jpeg,image/webp"
                        style="display: none;"
                    >
                    <label for="featureImageInput" class="mt-4 grid min-h-36 w-full cursor-pointer place-items-center rounded-xl border-2 border-dashed border-purple-brand/25 bg-white text-center text-purple-brand transition hover:border-purple-brand hover:bg-purple-soft/40">
                        <span>
                            <svg viewBox="0 0 24 24" class="mx-auto h-9 w-9" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 16V4"/><path d="M7 9l5-5 5 5"/><path d="M5 20h14"/></svg>
                            <span class="mt-2 block font-extrabold" wire:loading.remove wire:target="featureImage">{{ $featurePath ? 'Reemplazar imagen destacada' : 'Adjuntar imagen destacada' }}</span>
                            <span class="mt-2 block font-extrabold" wire:loading wire:target="featureImage">Subiendo...</span>
                            <span class="mt-1 block text-xs font-semibold text-slate-500">PNG, JPG o WEBP<br>Máx. 8MB</span>
                        </span>
                    </label>
                    @error('featureImage')
                        <p class="mt-2 text-sm font-semibold text-red-600">{{ $message }}</p>
                    @enderror

                    @if ($featurePath)
                        <button
                            type="button"
                            wire:click="deleteFeatureImage"
                            wire:loading.attr="disabled"
                            wire:target="deleteFeatureImage"
                            class="mt-4 w-full cursor-pointer rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-extrabold text-red-700 transition hover:border-red-300 hover:bg-red-100 disabled:cursor-not-allowed"
                        >
                            <span wire:loading.remove wire:target="deleteFeatureImage">Eliminar imagen destacada</span>
                            <span wire:loading wire:target="deleteFeatureImage">Eliminando...</span>
                        </button>
                    @endif
                </div>
            </div>

            <div id="section-gallery" class="editor-card p-5 md:p-7" wire:key="gallery-card-{{ $imageActionNonce }}">
                <span class="editor-section-pill">Editando galería</span>
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h2 class="font-display text-xl font-extrabold text-slate-900">Galería</h2>
                        <p class="mt-1 text-sm font-medium text-slate-500">Adjunta, reemplaza o elimina hasta 6 imágenes.</p>
                    </div>
                    <span class="shrink-0 rounded-full bg-purple-soft px-3 py-1 text-xs font-extrabold text-purple-brand">{{ $galleryCount }}/6</span>
                </div>

                @if (! is_null($galleryIndex))
                    <div class="mt-5 grid gap-3">
                        <label class="block text-sm font-extrabold text-slate-800">
                            Título
                            <input wire:model.live.debounce.400ms="blocks.{{ $galleryIndex }}.titulo" class="editor-input mt-2">
                        </label>
                        <label class="block text-sm font-extrabold text-slate-800">
                            Descripción
                            <textarea wire:model.live.debounce.400ms="blocks.{{ $galleryIndex }}.contenido" rows="2" class="editor-input mt-2"></textarea>
                        </label>
                    </div>
                @endif

                @if ($galleryCount > 0)
                    <div class="mt-5 grid grid-cols-2 gap-3">
                        @foreach ($galleryItems as $galleryItem)
                            <article class="overflow-hidden rounded-xl border border-border-soft bg-white" wire:key="gallery-item-{{ $galleryItem['id'] }}">
                                <img src="{{ asset($galleryItem['imagen_path']) }}" alt="{{ $galleryItem['titulo'] ?: 'Imagen de galería' }}" class="aspect-square w-full object-cover">
                                <button
                                    type="button"
                                    wire:click="deleteGalleryImage({{ $galleryItem['id'] }})"
                                    wire:loading.attr="disabled"
                                    wire:target="deleteGalleryImage({{ $galleryItem['id'] }})"
                                    class="w-full cursor-pointer border-t border-red-100 bg-red-50 px-3 py-2 text-xs font-extrabold text-red-700 transition hover:bg-red-100 disabled:cursor-not-allowed"
                                >
                                    Eliminar
                                </button>
                            </article>
                        @endforeach
                    </div>
                @else
                    <div class="mt-5 rounded-xl border border-dashed border-border-soft bg-slate-50 px-4 py-8 text-center text-sm font-semibold text-slate-500">
                        Todavía no hay imágenes en la galería.
                    </div>
                @endif

                @if ($galleryCount < 6)
                    <input
                        id="galleryImagesInput"
                        type="file"
                        wire:model="galleryImages"
                        accept="image/png,image/jpeg,image/webp"
                        multiple
                        style="display: none;"
                    >
                    <label for="galleryImagesInput" class="mt-5 grid min-h-36 w-full cursor-pointer place-items-center rounded-xl border-2 border-dashed border-purple-brand/25 bg-white text-center text-purple-brand transition hover:border-purple-brand hover:bg-purple-soft/40">
                        <span>
                            <svg viewBox="0 0 24 24" class="mx-auto h-9 w-9" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 16V4"/><path d="M7 9l5-5 5 5"/><path d="M5 20h14"/></svg>
                            <span class="mt-2 block font-extrabold" wire:loading.remove wire:target="galleryImages">Adjuntar imágenes</span>
                            <span class="mt-2 block font-extrabold" wire:loading wire:target="galleryImages">Subiendo...</span>
                            <span class="mt-1 block text-xs font-semibold text-slate-500">Puedes seleccionar {{ 6 - $galleryCount }} más<br>Máx. 8MB por imagen</span>
                        </span>
                    </label>
                @endif
                @error('galleryImages')
                    <p class="mt-2 text-sm font-semibold text-red-600">{{ $message }}</p>
                @enderror
                @error('galleryImages.*')
                    <p class="mt-2 text-sm font-semibold text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div id="section-event" class="editor-card p-5 md:p-7">
                <span class="editor-section-pill">Editando datos del evento</span>
                <h2 class="font-display text-xl font-extrabold text-slate-900">Datos del evento</h2>
                <p class="mt-1 text-sm font-medium text-slate-500">Cuéntales a tus invitados los detalles de tu celebración.</p>

                <div class="mt-6 grid gap-4">
                    <label class="block text-sm font-extrabold text-slate-800">
                        Nombre de la homenajeada
                        <input wire:model.live.debounce.400ms="form.nombre" class="editor-input mt-2">
                        @error('form.nombre') <span class="mt-1 block text-xs text-red-600">{{ $message }}</span> @enderror
                    </label>

                    <label class="block text-sm font-extrabold text-slate-800">
                        Evento
                        <input wire:model.live.debounce.400ms="form.titulo" class="editor-input mt-2">
                    </label>

                    <label class="block text-sm font-extrabold text-slate-800">
                        Fecha
                        <input type="date" wire:model.live="form.fecha_evento" class="editor-input mt-2">
                    </label>

                    <label class="block text-sm font-extrabold text-slate-800">
                        Hora
                        <input type="time" wire:model.live="form.hora_evento" class="editor-input mt-2">
                    </label>

                    <label class="block text-sm font-extrabold text-slate-800">
                        Lugar
                        <input wire:model.live.debounce.400ms="form.lugar_nombre" class="editor-input mt-2">
                    </label>

                    <label class="block text-sm font-extrabold text-slate-800">
                        Subtítulo (opcional)
                        <input wire:model.live.debounce.400ms="form.subtitulo" maxlength="80" class="editor-input mt-2">
                        <span class="mt-1 block text-right text-xs font-semibold text-slate-400">{{ strlen($form['subtitulo'] ?? '') }}/80</span>
                    </label>
                </div>
            </div>

            <div id="section-countdown" class="editor-card p-5 md:p-7">
                <span class="editor-section-pill">Editando cuenta regresiva</span>
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h2 class="font-display text-xl font-extrabold text-slate-900">Cuenta regresiva</h2>
                        <p class="mt-1 text-sm font-medium text-slate-500">Muestra el tiempo restante para tu gran día.</p>
                    </div>
                    @if (! is_null($countdownIndex))
                        <label class="flex items-center gap-3 text-sm font-bold text-slate-700">
                            Mostrar cuenta regresiva
                            <input type="checkbox" wire:model.live="blocks.{{ $countdownIndex }}.activo" class="h-5 w-9 rounded-full border-border-soft text-purple-brand focus:ring-purple-brand">
                        </label>
                    @endif
                </div>

                <div class="mt-6 grid gap-4">
                    <label class="block text-sm font-extrabold text-slate-800">
                        Fecha del evento
                        <input type="date" wire:model.live="form.fecha_evento" class="editor-input mt-2">
                    </label>
                    @if (! is_null($countdownIndex))
                        <label class="block text-sm font-extrabold text-slate-800">
                            Mensaje (opcional)
                            <input wire:model.live.debounce.400ms="blocks.{{ $countdownIndex }}.titulo" maxlength="60" class="editor-input mt-2" placeholder="¡Ya casi llega el gran día!">
                            <span class="mt-1 block text-right text-xs font-semibold text-slate-400">{{ strlen(data_get($blocks, $countdownIndex.'.titulo', '')) }}/60</span>
                        </label>
                    @endif
                </div>
            </div>

            <div id="section-dress" class="editor-card p-5 md:p-7">
                <span class="editor-section-pill">Editando dress code</span>
                <h2 class="font-display text-xl font-extrabold text-slate-900">Dress code</h2>
                <p class="mt-1 text-sm font-medium text-slate-500">Define la vestimenta sugerida.</p>

                <div class="mt-6 grid gap-4">
                    <label class="block text-sm font-extrabold text-slate-800">
                        Dress code
                        <input wire:model.live.debounce.400ms="form.dress_code" class="editor-input mt-2" placeholder="Formal">
                    </label>
                    @if (! is_null($dressIndex))
                        <label class="flex items-end gap-3 text-sm font-bold text-slate-700">
                            <span class="pb-3">Mostrar bloque</span>
                            <input type="checkbox" wire:model.live="blocks.{{ $dressIndex }}.activo" class="mb-3 h-5 w-9 rounded-full border-border-soft text-purple-brand focus:ring-purple-brand">
                        </label>
                    @endif
                    <label class="block text-sm font-extrabold text-slate-800">
                        Descripción
                        <textarea wire:model.live.debounce.400ms="form.dress_code_descripcion" rows="3" class="editor-input mt-2"></textarea>
                    </label>
                </div>
            </div>

            <div id="section-location" class="editor-card p-5 md:p-7">
                <span class="editor-section-pill">Editando ubicación</span>
                <h2 class="font-display text-xl font-extrabold text-slate-900">Ubicación</h2>
                <p class="mt-1 text-sm font-medium text-slate-500">Agrega la dirección y el enlace de Google Maps.</p>

                <div class="mt-6 grid gap-4">
                    <label class="block text-sm font-extrabold text-slate-800">
                        Dirección
                        <textarea wire:model.live.debounce.400ms="form.lugar_direccion" rows="3" class="editor-input mt-2"></textarea>
                    </label>
                    <label class="block text-sm font-extrabold text-slate-800">
                        Google Maps URL
                        <textarea wire:model.live.debounce.400ms="form.maps_url" rows="2" class="editor-input mt-2"></textarea>
                    </label>
                </div>
            </div>

            <div id="section-gifts" class="editor-card p-5 md:p-7">
                <span class="editor-section-pill">Editando mesa de regalos</span>
                <h2 class="font-display text-xl font-extrabold text-slate-900">Mesa de regalos</h2>
                <p class="mt-1 text-sm font-medium text-slate-500">Configura regalos, sobres o detalles.</p>
                @if (! is_null($giftIndex))
                    <div class="mt-6 grid gap-4">
                        <label class="flex items-center gap-3 text-sm font-bold text-slate-700">
                            <input type="checkbox" wire:model.live="blocks.{{ $giftIndex }}.activo" class="h-5 w-9 rounded-full border-border-soft text-purple-brand focus:ring-purple-brand">
                            Mostrar mesa de regalos
                        </label>
                        <label class="block text-sm font-extrabold text-slate-800">
                            Título
                            <input wire:model.live.debounce.400ms="blocks.{{ $giftIndex }}.titulo" class="editor-input mt-2">
                        </label>
                        <label class="block text-sm font-extrabold text-slate-800">
                            Mensaje
                            <textarea wire:model.live.debounce.400ms="blocks.{{ $giftIndex }}.contenido" rows="3" class="editor-input mt-2"></textarea>
                        </label>
                    </div>
                @endif
            </div>

            <div id="section-music" class="editor-card p-5 md:p-7">
                <span class="editor-section-pill">Editando música</span>
                <h2 class="font-display text-xl font-extrabold text-slate-900">Música</h2>
                <p class="mt-1 text-sm font-medium text-slate-500">Agrega música de fondo a la invitación.</p>
                <div class="mt-6 grid gap-4">
                    <label class="block text-sm font-extrabold text-slate-800">
                        Título
                        <input wire:model.live.debounce.400ms="form.musica_titulo" class="editor-input mt-2">
                    </label>

                    @if ($musicUrl)
                        <audio controls preload="metadata" class="w-full">
                            <source src="{{ $musicUrl }}">
                        </audio>
                    @endif

                    <input
                        id="musicFileInput"
                        type="file"
                        wire:model="musicFile"
                        accept=".mp3,.m4a,.wav,.ogg,audio/mpeg,audio/mp4,audio/wav,audio/ogg"
                        style="display: none;"
                    >
                    <label for="musicFileInput" class="grid min-h-36 w-full cursor-pointer place-items-center rounded-xl border-2 border-dashed border-purple-brand/25 bg-white text-center text-purple-brand transition hover:border-purple-brand hover:bg-purple-soft/40">
                        <span>
                            <svg viewBox="0 0 24 24" class="mx-auto h-9 w-9" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 16V4"/><path d="M7 9l5-5 5 5"/><path d="M5 20h14"/></svg>
                            <span class="mt-2 block font-extrabold" wire:loading.remove wire:target="musicFile">Adjuntar música</span>
                            <span class="mt-2 block font-extrabold" wire:loading wire:target="musicFile">Subiendo...</span>
                            <span class="mt-1 block text-xs font-semibold text-slate-500">MP3, M4A, WAV u OGG<br>Máx. 15MB</span>
                        </span>
                    </label>
                    @error('musicFile')
                        <p class="text-sm font-semibold text-red-600">{{ $message }}</p>
                    @enderror

                    @if ($musicPath)
                        <button
                            type="button"
                            wire:click="deleteMusicFile"
                            wire:loading.attr="disabled"
                            wire:target="deleteMusicFile"
                            class="cursor-pointer rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-extrabold text-red-700 transition hover:border-red-300 hover:bg-red-100 disabled:cursor-not-allowed"
                        >
                            <span wire:loading.remove wire:target="deleteMusicFile">Eliminar música</span>
                            <span wire:loading wire:target="deleteMusicFile">Eliminando...</span>
                        </button>
                    @endif

                    <label class="block text-sm font-extrabold text-slate-800">
                        Ruta del archivo
                        <input wire:model.live.debounce.400ms="form.musica_path" class="editor-input mt-2" placeholder="uploads/invitaciones/{{ $invitacion->ruta }}/musica/cancion.mp3">
                    </label>
                </div>
            </div>

            <div id="section-whatsapp" class="editor-card p-5 md:p-7">
                <span class="editor-section-pill">Editando WhatsApp</span>
                <h2 class="font-display text-xl font-extrabold text-slate-900">WhatsApp</h2>
                <p class="mt-1 text-sm font-medium text-slate-500">Configura el botón de confirmación.</p>
                <div class="mt-6 grid gap-4">
                    <label class="block text-sm font-extrabold text-slate-800">
                        Número
                        <input wire:model.live.debounce.400ms="form.whatsapp_numero" class="editor-input mt-2">
                    </label>
                    <label class="block text-sm font-extrabold text-slate-800">
                        Mensaje
                        <textarea wire:model.live.debounce.400ms="form.whatsapp_mensaje" rows="3" class="editor-input mt-2"></textarea>
                    </label>
                </div>
            </div>

            <div id="section-colors" class="editor-card p-5 md:p-7">
                <span class="editor-section-pill">Editando colores</span>
                <h2 class="font-display text-xl font-extrabold text-slate-900">Colores</h2>
                <p class="mt-1 text-sm font-medium text-slate-500">Ajusta la paleta visual del diseño.</p>
                <div class="mt-6 grid gap-4">
                    <label class="block text-sm font-extrabold text-slate-800">
                        Primario
                        <input type="color" wire:model.live="form.color_primario" class="mt-2 h-12 w-full rounded-xl border border-border-soft p-1">
                        <input type="text" wire:model.live.debounce.300ms="form.color_primario" placeholder="#5A3087" class="editor-input mt-2 font-mono uppercase">
                        @error('form.color_primario') <span class="mt-1 block text-xs text-red-600">Usa formato hexadecimal, ejemplo #5A3087.</span> @enderror
                    </label>
                    <label class="block text-sm font-extrabold text-slate-800">
                        Secundario
                        <input type="color" wire:model.live="form.color_secundario" class="mt-2 h-12 w-full rounded-xl border border-border-soft p-1">
                        <input type="text" wire:model.live.debounce.300ms="form.color_secundario" placeholder="#F4EFF8" class="editor-input mt-2 font-mono uppercase">
                        @error('form.color_secundario') <span class="mt-1 block text-xs text-red-600">Usa formato hexadecimal, ejemplo #F4EFF8.</span> @enderror
                    </label>
                    <label class="block text-sm font-extrabold text-slate-800">
                        Acento
                        <input type="color" wire:model.live="form.color_acento" class="mt-2 h-12 w-full rounded-xl border border-border-soft p-1">
                        <input type="text" wire:model.live.debounce.300ms="form.color_acento" placeholder="#C9A05A" class="editor-input mt-2 font-mono uppercase">
                        @error('form.color_acento') <span class="mt-1 block text-xs text-red-600">Usa formato hexadecimal, ejemplo #C9A05A.</span> @enderror
                    </label>
                </div>
            </div>

            @if (auth()->user()?->isAdmin())
                <details class="editor-card p-5 md:p-7">
                    <summary class="cursor-pointer font-display text-lg font-extrabold text-slate-900">Configuración avanzada de bloques</summary>
                    <div class="mt-5 space-y-4">
                        @foreach ($blocks as $index => $block)
                            <div class="rounded-xl border border-border-soft p-4">
                                <div class="mb-4 flex items-center justify-between gap-4">
                                    <div>
                                        <p class="text-xs font-bold uppercase tracking-wide text-orange-brand">{{ $block['tipo'] }}</p>
                                        <p class="font-bold text-slate-900">{{ $block['titulo'] ?: 'Bloque sin título' }}</p>
                                    </div>
                                    <label class="flex items-center gap-2 text-sm font-bold text-slate-700">
                                        <input type="checkbox" wire:model.live="blocks.{{ $index }}.activo" class="rounded border-border-soft text-purple-brand">
                                        Activo
                                    </label>
                                </div>
                                <textarea wire:model.blur="blocks.{{ $index }}.config_json" rows="8" spellcheck="false" class="w-full rounded-xl border border-border-soft bg-slate-950 px-3 py-2 font-mono text-xs text-slate-100 outline-none"></textarea>
                                @error('blocks.'.$index.'.config_json') <span class="mt-1 block text-xs text-red-600">{{ $message }}</span> @enderror
                            </div>
                        @endforeach
                    </div>
                </details>
            @endif
            </section>
        </div>
    </aside>

    <div x-show="saved" x-cloak class="fixed bottom-5 left-1/2 z-[60] -translate-x-1/2 rounded-full bg-green-600 px-4 py-2 text-sm font-bold text-white shadow-lg">Cambios guardados</div>
</div>
