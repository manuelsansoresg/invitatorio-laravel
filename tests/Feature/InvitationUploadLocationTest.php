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
