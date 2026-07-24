<?php

use App\Livewire\InvitationEditor;
use App\Models\Invitacion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

uses(RefreshDatabase::class);

test('editor images are uploaded directly inside public', function () {
    Storage::fake('public_uploads');
    Storage::fake('public');

    $invitation = Invitacion::query()->create([
        'nombre' => 'Mariana',
        'apellido_paterno' => 'Demo',
        'ruta' => 'xv-mariana',
    ]);

    Livewire::test(InvitationEditor::class, ['invitacion' => $invitation])
        ->set('coverImage', UploadedFile::fake()->image('portada.jpg', 900, 1200))
        ->assertHasNoErrors();

    $path = $invitation->fresh()->imagen_portada_path;

    expect($path)->toStartWith('uploads/invitaciones/xv-mariana/portada/')
        ->and($path)->not->toStartWith('storage/');

    Storage::disk('public_uploads')->assertExists($path);
    Storage::disk('public')->assertMissing(
        str_starts_with($path, 'storage/') ? substr($path, strlen('storage/')) : $path,
    );
});

test('editor music is uploaded directly inside public', function () {
    Storage::fake('public_uploads');
    Storage::fake('public');

    $invitation = Invitacion::query()->create([
        'nombre' => 'Mariana',
        'apellido_paterno' => 'Demo',
        'ruta' => 'xv-mariana',
    ]);

    Livewire::test(InvitationEditor::class, ['invitacion' => $invitation])
        ->set('musicFile', UploadedFile::fake()->create('cancion.mp3', 512, 'audio/mpeg'))
        ->assertHasNoErrors();

    $path = $invitation->fresh()->musica_path;

    expect($path)->toStartWith('uploads/invitaciones/xv-mariana/musica/')
        ->and($path)->not->toStartWith('storage/');

    Storage::disk('public_uploads')->assertExists($path);
});

test('the featured image can be uploaded, replaced and deleted as a single public file', function () {
    Storage::fake('public_uploads');

    $invitation = Invitacion::query()->create([
        'nombre' => 'Valentina',
        'apellido_paterno' => 'Franco',
        'ruta' => 'xv-valentina',
    ]);

    $component = Livewire::test(InvitationEditor::class, ['invitacion' => $invitation])
        ->set('featureImage', UploadedFile::fake()->image('destacada.jpg', 1600, 1000))
        ->assertHasNoErrors();

    $firstPath = $invitation->blocks()->where('tipo', 'hero')->firstOrFail()->config_json['imagen_parallax'];

    expect($firstPath)->toStartWith('uploads/invitaciones/xv-valentina/destacada/');
    Storage::disk('public_uploads')->assertExists($firstPath);

    $component
        ->set('featureImage', UploadedFile::fake()->image('reemplazo.webp', 1600, 1000))
        ->assertHasNoErrors();

    $replacementPath = $invitation->blocks()->where('tipo', 'hero')->firstOrFail()->config_json['imagen_parallax'];

    expect($replacementPath)->not->toBe($firstPath);
    Storage::disk('public_uploads')->assertMissing($firstPath);
    Storage::disk('public_uploads')->assertExists($replacementPath);

    $component->call('deleteFeatureImage')->assertHasNoErrors();

    expect($invitation->blocks()->where('tipo', 'hero')->firstOrFail()->config_json['imagen_parallax'])
        ->toBe('__deleted');
    Storage::disk('public_uploads')->assertMissing($replacementPath);
});

test('the gallery uploads public images and enforces a maximum of six', function () {
    Storage::fake('public_uploads');

    $invitation = Invitacion::query()->create([
        'nombre' => 'Valentina',
        'apellido_paterno' => 'Franco',
        'ruta' => 'xv-valentina',
    ]);

    $component = Livewire::test(InvitationEditor::class, ['invitacion' => $invitation])
        ->set('galleryImages', [
            UploadedFile::fake()->image('foto-1.jpg', 900, 1200),
            UploadedFile::fake()->image('foto-2.jpg', 900, 1200),
        ])
        ->assertHasNoErrors();

    expect($invitation->gallery()->count())->toBe(2)
        ->and($invitation->blocks()->where('tipo', 'galeria')->where('activo', true)->exists())->toBeTrue();

    $component
        ->set('galleryImages', [
            UploadedFile::fake()->image('foto-3.jpg', 900, 1200),
            UploadedFile::fake()->image('foto-4.jpg', 900, 1200),
            UploadedFile::fake()->image('foto-5.jpg', 900, 1200),
            UploadedFile::fake()->image('foto-6.jpg', 900, 1200),
        ])
        ->assertHasNoErrors();

    expect($invitation->gallery()->count())->toBe(6);

    $component
        ->set('galleryImages', [
            UploadedFile::fake()->image('foto-7.jpg', 900, 1200),
        ])
        ->assertHasErrors(['galleryImages']);

    expect($invitation->gallery()->count())->toBe(6);

    $item = $invitation->gallery()->orderBy('orden')->firstOrFail();
    $deletedPath = $item->imagen_path;

    $component->call('deleteGalleryImage', $item->id)->assertHasNoErrors();

    expect($invitation->gallery()->count())->toBe(5);
    Storage::disk('public_uploads')->assertMissing($deletedPath);
});
