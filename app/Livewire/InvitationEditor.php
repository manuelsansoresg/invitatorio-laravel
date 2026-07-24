<?php

namespace App\Livewire;

use App\Models\Invitacion;
use App\Models\InvitationBlock;
use App\Models\InvitationGallery;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class InvitationEditor extends Component
{
    use WithFileUploads;

    public Invitacion $invitacion;

    public array $form = [];

    public array $blocks = [];

    public $coverImage;

    public $heroImage;

    public $featureImage;

    public array $galleryImages = [];

    public array $galleryItems = [];

    public $musicFile;

    public int $previewNonce;

    public int $imageActionNonce = 0;

    public function mount(Invitacion $invitacion): void
    {
        $this->invitacion = $invitacion->load(['blocks', 'gallery']);
        $this->previewNonce = time();

        $this->form = [
            'nombre' => $this->invitacion->nombre,
            'apellido_paterno' => $this->invitacion->apellido_paterno,
            'apellido_materno' => $this->invitacion->apellido_materno,
            'ruta' => $this->invitacion->ruta,
            'tipo_evento' => $this->invitacion->tipo_evento,
            'titulo' => $this->invitacion->titulo,
            'subtitulo' => $this->invitacion->subtitulo,
            'fecha_evento' => $this->invitacion->fecha_evento?->format('Y-m-d'),
            'hora_evento' => $this->invitacion->hora_evento?->format('H:i'),
            'lugar_nombre' => $this->invitacion->lugar_nombre,
            'lugar_direccion' => $this->invitacion->lugar_direccion,
            'maps_url' => $this->invitacion->maps_url,
            'dress_code' => $this->invitacion->dress_code,
            'dress_code_descripcion' => $this->invitacion->dress_code_descripcion,
            'mensaje_principal' => $this->invitacion->mensaje_principal,
            'mensaje_footer' => $this->invitacion->mensaje_footer,
            'whatsapp_numero' => $this->invitacion->whatsapp_numero,
            'whatsapp_mensaje' => $this->invitacion->whatsapp_mensaje,
            'musica_path' => $this->invitacion->musica_path,
            'musica_titulo' => $this->invitacion->musica_titulo,
            'imagen_portada_path' => $this->invitacion->imagen_portada_path,
            'archivo_final_path' => $this->invitacion->archivo_final_path,
            'color_primario' => $this->invitacion->color_primario ?: '#5A3087',
            'color_secundario' => $this->invitacion->color_secundario ?: '#F4EFF8',
            'color_acento' => $this->invitacion->color_acento ?: '#C9A05A',
            'template_key' => $this->invitacion->template_key,
            'estado' => $this->invitacion->estado ?: 'borrador',
        ];

        $this->blocks = $this->invitacion->blocks
            ->map(fn (InvitationBlock $block) => [
                'id' => $block->id,
                'tipo' => $block->tipo,
                'titulo' => $block->titulo,
                'contenido' => $block->contenido,
                'config_json' => json_encode($block->config_json ?: [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                'orden' => $block->orden,
                'activo' => $block->activo,
            ])
            ->values()
            ->all();

        $this->refreshGalleryItems();
    }

    public function updatedCoverImage(): void
    {
        $this->validate([
            'coverImage' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $previousPath = $this->form['imagen_portada_path'] ?? null;
        $path = $this->storePublicUpload($this->coverImage, 'portada');

        $this->form['imagen_portada_path'] = $path;
        $this->setHeroConfigValue('imagen_intro', $path);
        $this->persistHeroConfig();
        $this->invitacion->update(['imagen_portada_path' => $this->form['imagen_portada_path']]);
        $this->deleteOwnedPublicFile($previousPath);
        $this->bumpPreview();
        $this->coverImage = null;

        session()->flash('status', 'Imagen de portada actualizada.');
    }

    public function updatedHeroImage(): void
    {
        $this->validate([
            'heroImage' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $heroIndex = $this->ensureHeroBlock();
        $previousPath = $this->heroConfig($heroIndex)['imagen_hero'] ?? null;
        $path = $this->storePublicUpload($this->heroImage, 'hero');

        $this->setHeroConfigValue('imagen_hero', $path);
        $this->persistHeroConfig();
        $this->deleteOwnedPublicFile($previousPath);
        $this->bumpPreview();
        $this->heroImage = null;

        session()->flash('status', 'Imagen principal actualizada.');
    }

    public function deleteCoverImage(): void
    {
        $this->deleteOwnedPublicFile($this->form['imagen_portada_path'] ?? null);
        $this->form['imagen_portada_path'] = null;

        $heroIndex = $this->heroBlockIndex();

        if ($heroIndex !== null) {
            $config = $this->heroConfig($heroIndex);
            $this->deleteOwnedPublicFile($config['imagen_intro'] ?? null);
            $config['imagen_intro'] = '__deleted';
            $this->blocks[$heroIndex]['config_json'] = json_encode($config, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        }

        $this->invitacion->update(['imagen_portada_path' => null]);
        $this->persistHeroConfig();
        $this->imageActionNonce++;
        $this->bumpPreview();
        $this->dispatch('saved');

        session()->flash('status', 'Imagen de portada eliminada.');
    }

    public function deleteHeroImage(): void
    {
        $heroIndex = $this->heroBlockIndex();

        if ($heroIndex === null) {
            return;
        }

        $config = $this->heroConfig($heroIndex);
        $this->deleteOwnedPublicFile($config['imagen_hero'] ?? null);

        $config['imagen_hero'] = '__deleted';
        $this->blocks[$heroIndex]['config_json'] = json_encode($config, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        $this->persistHeroConfig();
        $this->imageActionNonce++;
        $this->bumpPreview();
        $this->dispatch('saved');

        session()->flash('status', 'Imagen principal eliminada.');
    }

    public function updatedFeatureImage(): void
    {
        $this->validate([
            'featureImage' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:8192'],
        ]);

        $heroIndex = $this->ensureHeroBlock();
        $previousPath = $this->heroConfig($heroIndex)['imagen_parallax'] ?? null;
        $path = $this->storePublicUpload($this->featureImage, 'destacada');

        $this->setHeroConfigValue('imagen_parallax', $path);
        $this->persistHeroConfig();
        $this->deleteOwnedPublicFile($previousPath);
        $this->featureImage = null;
        $this->imageActionNonce++;
        $this->bumpPreview();
        $this->dispatch('saved');

        session()->flash('status', 'Imagen destacada actualizada.');
    }

    public function deleteFeatureImage(): void
    {
        $heroIndex = $this->heroBlockIndex();

        if ($heroIndex === null) {
            return;
        }

        $config = $this->heroConfig($heroIndex);
        $this->deleteOwnedPublicFile($config['imagen_parallax'] ?? null);
        $config['imagen_parallax'] = '__deleted';
        $this->blocks[$heroIndex]['config_json'] = json_encode($config, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        $this->persistHeroConfig();
        $this->featureImage = null;
        $this->imageActionNonce++;
        $this->bumpPreview();
        $this->dispatch('saved');

        session()->flash('status', 'Imagen destacada eliminada.');
    }

    public function updatedGalleryImages(): void
    {
        $this->resetValidation('galleryImages');
        $currentCount = $this->invitacion->gallery()->count();
        $remaining = max(0, 6 - $currentCount);

        if ($remaining === 0) {
            $this->addError('galleryImages', 'La galería permite un máximo de 6 imágenes.');
            $this->galleryImages = [];

            return;
        }

        $this->validate([
            'galleryImages' => ['required', 'array', 'min:1', 'max:'.$remaining],
            'galleryImages.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:8192'],
        ], [
            'galleryImages.max' => 'Solo puedes adjuntar '.$remaining.' '.str('imagen')->plural($remaining).' más.',
            'galleryImages.*.image' => 'Cada archivo debe ser una imagen válida.',
            'galleryImages.*.max' => 'Cada imagen puede pesar máximo 8 MB.',
        ]);

        $this->ensureGalleryBlock();
        $nextOrder = ((int) $this->invitacion->gallery()->max('orden')) + 1;

        foreach ($this->galleryImages as $image) {
            $path = $this->storePublicUpload($image, 'galeria');
            $title = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);

            $this->invitacion->gallery()->create([
                'imagen_path' => $path,
                'titulo' => filled($title) ? $title : 'Foto '.$nextOrder,
                'orden' => $nextOrder++,
                'activo' => true,
            ]);
        }

        $this->galleryImages = [];
        $this->refreshGalleryItems();
        $this->imageActionNonce++;
        $this->bumpPreview();
        $this->dispatch('saved');

        session()->flash('status', 'Galería actualizada.');
    }

    public function deleteGalleryImage(int $galleryId): void
    {
        $this->resetValidation('galleryImages');
        $galleryItem = $this->invitacion->gallery()
            ->whereKey($galleryId)
            ->firstOrFail();

        $this->deleteOwnedPublicFile($galleryItem->imagen_path);
        $galleryItem->delete();
        $this->normalizeGalleryOrder();
        $this->refreshGalleryItems();
        $this->imageActionNonce++;
        $this->bumpPreview();
        $this->dispatch('saved');

        session()->flash('status', 'Imagen eliminada de la galería.');
    }

    public function updatedMusicFile(): void
    {
        $this->validate([
            'musicFile' => [
                'file',
                'mimes:mp3,m4a,wav,ogg',
                'mimetypes:audio/mpeg,audio/mp4,audio/x-m4a,audio/wav,audio/x-wav,audio/ogg,application/ogg',
                'max:15360',
            ],
        ]);

        $previousPath = $this->form['musica_path'] ?? null;
        $path = $this->storePublicUpload($this->musicFile, 'musica');

        $this->form['musica_path'] = $path;
        $this->invitacion->update(['musica_path' => $path]);
        $this->deleteOwnedPublicFile($previousPath);
        $this->musicFile = null;
        $this->bumpPreview();
        $this->dispatch('saved');

        session()->flash('status', 'Música actualizada.');
    }

    public function deleteMusicFile(): void
    {
        $this->deleteOwnedPublicFile($this->form['musica_path'] ?? null);
        $this->form['musica_path'] = null;
        $this->invitacion->update(['musica_path' => null]);
        $this->musicFile = null;
        $this->bumpPreview();
        $this->dispatch('saved');

        session()->flash('status', 'Música eliminada.');
    }

    private function setHeroConfigValue(string $key, mixed $value): void
    {
        $heroIndex = $this->ensureHeroBlock();

        $config = $this->heroConfig($heroIndex);
        $config[$key] = $value;

        $this->blocks[$heroIndex]['config_json'] = json_encode($config, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    private function heroBlockIndex(): ?int
    {
        foreach ($this->blocks as $index => $block) {
            if (($block['tipo'] ?? null) === 'hero') {
                return $index;
            }
        }

        return null;
    }

    private function ensureHeroBlock(): int
    {
        $heroIndex = $this->heroBlockIndex();

        if ($heroIndex !== null) {
            return $heroIndex;
        }

        $orden = ((int) collect($this->blocks)->max(fn (array $block) => (int) ($block['orden'] ?? 0))) + 10;
        $block = $this->invitacion->blocks()->create([
            'tipo' => 'hero',
            'titulo' => $this->form['titulo'] ?? 'Portada',
            'contenido' => $this->form['mensaje_principal'] ?? null,
            'config_json' => [],
            'orden' => $orden,
            'activo' => true,
        ]);

        $this->blocks[] = [
            'id' => $block->id,
            'tipo' => $block->tipo,
            'titulo' => $block->titulo,
            'contenido' => $block->contenido,
            'config_json' => json_encode([], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            'orden' => $block->orden,
            'activo' => $block->activo,
        ];

        return (int) array_key_last($this->blocks);
    }

    private function ensureGalleryBlock(): void
    {
        foreach ($this->blocks as $index => $blockData) {
            if (($blockData['tipo'] ?? null) !== 'galeria') {
                continue;
            }

            if (! ($blockData['activo'] ?? false)) {
                $this->blocks[$index]['activo'] = true;
                InvitationBlock::query()
                    ->where('invitacion_id', $this->invitacion->id)
                    ->whereKey($blockData['id'])
                    ->update(['activo' => true]);
            }

            return;
        }

        $order = ((int) collect($this->blocks)->max(fn (array $block) => (int) ($block['orden'] ?? 0))) + 10;
        $block = $this->invitacion->blocks()->create([
            'tipo' => 'galeria',
            'titulo' => 'Galería de recuerdos',
            'contenido' => 'Pequeños momentos que forman parte de esta historia tan especial.',
            'config_json' => [],
            'orden' => $order,
            'activo' => true,
        ]);

        $this->blocks[] = [
            'id' => $block->id,
            'tipo' => $block->tipo,
            'titulo' => $block->titulo,
            'contenido' => $block->contenido,
            'config_json' => json_encode([], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            'orden' => $block->orden,
            'activo' => true,
        ];
    }

    private function heroConfig(int $heroIndex): array
    {
        $decoded = json_decode($this->blocks[$heroIndex]['config_json'] ?: '{}', true);

        return is_array($decoded) ? $decoded : [];
    }

    private function persistHeroConfig(): void
    {
        $heroIndex = $this->heroBlockIndex();

        if ($heroIndex === null) {
            return;
        }

        $config = json_decode($this->blocks[$heroIndex]['config_json'] ?: '{}', true);

        InvitationBlock::query()
            ->where('invitacion_id', $this->invitacion->id)
            ->whereKey($this->blocks[$heroIndex]['id'])
            ->update([
                'config_json' => json_encode(
                    is_array($config) ? $config : [],
                    JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES,
                ),
            ]);
    }

    private function refreshGalleryItems(): void
    {
        $this->galleryItems = $this->invitacion->gallery()
            ->orderBy('orden')
            ->get()
            ->map(fn (InvitationGallery $item) => [
                'id' => $item->id,
                'imagen_path' => $item->imagen_path,
                'titulo' => $item->titulo,
                'orden' => $item->orden,
            ])
            ->values()
            ->all();
    }

    private function normalizeGalleryOrder(): void
    {
        $this->invitacion->gallery()
            ->orderBy('orden')
            ->get()
            ->values()
            ->each(fn (InvitationGallery $item, int $index) => $item->update(['orden' => $index + 1]));
    }

    private function storePublicUpload(mixed $file, string $folder): string
    {
        return $file->store(
            'uploads/invitaciones/'.trim($this->invitacion->ruta, '/').'/'.trim($folder, '/'),
            'public_uploads',
        );
    }

    private function deleteOwnedPublicFile(?string $path): void
    {
        if (! is_string($path) || $path === '') {
            return;
        }

        $route = trim($this->invitacion->ruta, '/');
        $publicPrefix = 'uploads/invitaciones/'.$route.'/';

        if (str_starts_with($path, $publicPrefix)) {
            Storage::disk('public_uploads')->delete($path);

            return;
        }

        $legacyPrefix = 'storage/invitaciones/'.$route.'/';

        if (str_starts_with($path, $legacyPrefix)) {
            Storage::disk('public')->delete(substr($path, strlen('storage/')));
        }
    }

    private function bumpPreview(): void
    {
        $this->previewNonce++;
    }

    public function updated(string $property): void
    {
        if (str_starts_with($property, 'form.') || str_starts_with($property, 'blocks.')) {
            $this->save(false);
        }
    }

    public function save(bool $showStatus = true): void
    {
        $validated = $this->validate([
            'form.nombre' => ['required', 'string', 'max:80'],
            'form.apellido_paterno' => ['required', 'string', 'max:80'],
            'form.apellido_materno' => ['nullable', 'string', 'max:80'],
            'form.ruta' => ['required', 'string', 'max:120', Rule::unique('invitaciones', 'ruta')->ignore($this->invitacion->id)],
            'form.tipo_evento' => ['nullable', 'string', 'max:255'],
            'form.titulo' => ['nullable', 'string', 'max:255'],
            'form.subtitulo' => ['nullable', 'string', 'max:255'],
            'form.fecha_evento' => ['nullable', 'date'],
            'form.hora_evento' => ['nullable', 'date_format:H:i'],
            'form.lugar_nombre' => ['nullable', 'string', 'max:255'],
            'form.lugar_direccion' => ['nullable', 'string'],
            'form.maps_url' => ['nullable', 'string'],
            'form.dress_code' => ['nullable', 'string', 'max:255'],
            'form.dress_code_descripcion' => ['nullable', 'string'],
            'form.mensaje_principal' => ['nullable', 'string'],
            'form.mensaje_footer' => ['nullable', 'string'],
            'form.whatsapp_numero' => ['nullable', 'string', 'max:255'],
            'form.whatsapp_mensaje' => ['nullable', 'string'],
            'form.musica_path' => ['nullable', 'string', 'max:255'],
            'form.musica_titulo' => ['nullable', 'string', 'max:255'],
            'form.imagen_portada_path' => ['nullable', 'string', 'max:255'],
            'form.archivo_final_path' => ['nullable', 'string', 'max:255'],
            'form.color_primario' => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'form.color_secundario' => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'form.color_acento' => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'form.template_key' => ['nullable', 'string', 'max:255'],
            'form.estado' => ['required', Rule::in(['borrador', 'en_edicion', 'publicada', 'pausada'])],
            'blocks.*.id' => ['required', 'integer', 'exists:invitacion_bloques,id'],
            'blocks.*.titulo' => ['nullable', 'string', 'max:255'],
            'blocks.*.contenido' => ['nullable', 'string'],
            'blocks.*.config_json' => ['nullable', 'json'],
            'blocks.*.orden' => ['required', 'integer', 'min:0'],
            'blocks.*.activo' => ['boolean'],
        ]);

        $form = $validated['form'];
        $form['publicada_at'] = $form['estado'] === 'publicada'
            ? ($this->invitacion->publicada_at ?: now())
            : null;

        $this->invitacion->update($form);

        foreach (($validated['blocks'] ?? []) as $blockData) {
            InvitationBlock::query()
                ->where('invitacion_id', $this->invitacion->id)
                ->whereKey($blockData['id'])
                ->update([
                    'titulo' => $blockData['titulo'] ?? null,
                    'contenido' => $blockData['contenido'] ?? null,
                    'config_json' => filled($blockData['config_json'] ?? null)
                        ? json_encode(
                            json_decode($blockData['config_json'], true),
                            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES,
                        )
                        : null,
                    'orden' => $blockData['orden'],
                    'activo' => (bool) ($blockData['activo'] ?? false),
                ]);
        }

        $this->invitacion->refresh();
        $this->bumpPreview();
        $this->dispatch('saved');

        if ($showStatus) {
            session()->flash('status', 'Invitación actualizada correctamente.');
        }
    }

    public function render()
    {
        return view('livewire.invitation-editor')
            ->layout('layouts.editor', ['title' => 'Editor de invitación']);
    }
}
