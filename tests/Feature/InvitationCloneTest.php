<?php

use App\Models\Invitacion;
use App\Models\InvitationBlock;
use App\Models\InvitationGallery;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

test('an administrator can clone an invitation as an independent editable draft', function () {
    Storage::fake('public');
    Storage::fake('public_uploads');

    $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
    $client = User::factory()->create(['role' => User::ROLE_CLIENT]);
    $source = Invitacion::query()->create([
        'nombre' => 'Mariana',
        'apellido_paterno' => 'Demo',
        'ruta' => 'xv-mariana',
        'user_id' => $client->id,
        'titulo' => 'Mis XV Años',
        'imagen_portada_path' => 'storage/invitaciones/xv-mariana/portada/intro.jpg',
        'estado' => 'publicada',
        'publicada_at' => now(),
    ]);

    Storage::disk('public')->put('invitaciones/xv-mariana/portada/intro.jpg', 'intro');
    Storage::disk('public')->put('invitaciones/xv-mariana/hero/main.jpg', 'hero');
    Storage::disk('public')->put('invitaciones/xv-mariana/gallery/photo.jpg', 'gallery');

    InvitationBlock::query()->create([
        'invitacion_id' => $source->id,
        'tipo' => 'hero',
        'titulo' => 'Portada',
        'config_json' => ['imagen_hero' => 'storage/invitaciones/xv-mariana/hero/main.jpg'],
        'orden' => 10,
        'activo' => true,
    ]);
    InvitationGallery::query()->create([
        'invitacion_id' => $source->id,
        'imagen_path' => 'storage/invitaciones/xv-mariana/gallery/photo.jpg',
        'orden' => 10,
        'activo' => true,
    ]);

    $response = $this
        ->actingAs($admin)
        ->post(route('admin.invitaciones.clone', $source), [
            'nombre_completo' => 'Lucía Hernández López',
            'clone_source' => $source->ruta,
        ]);

    $clone = Invitacion::query()->where('ruta', 'xv-lucia-hernandez-lopez')->firstOrFail();

    $response->assertRedirect(route('admin.invitaciones.edit', $clone));
    expect($clone->nombre_completo)->toBe('Lucía Hernández López')
        ->and($clone->estado)->toBe('borrador')
        ->and($clone->user_id)->toBeNull()
        ->and($clone->publicada_at)->toBeNull()
        ->and($clone->blocks()->count())->toBe(1)
        ->and($clone->gallery()->count())->toBe(1)
        ->and($clone->imagen_portada_path)->toBe('uploads/invitaciones/xv-lucia-hernandez-lopez/portada/intro.jpg')
        ->and($clone->blocks()->first()->config_json['imagen_hero'])->toBe('uploads/invitaciones/xv-lucia-hernandez-lopez/hero/main.jpg')
        ->and($clone->gallery()->first()->imagen_path)->toBe('uploads/invitaciones/xv-lucia-hernandez-lopez/gallery/photo.jpg')
        ->and($source->fresh()->imagen_portada_path)->toBe('storage/invitaciones/xv-mariana/portada/intro.jpg');

    Storage::disk('public_uploads')->assertExists('uploads/invitaciones/xv-lucia-hernandez-lopez/portada/intro.jpg');
    Storage::disk('public_uploads')->assertExists('uploads/invitaciones/xv-lucia-hernandez-lopez/hero/main.jpg');
    Storage::disk('public_uploads')->assertExists('uploads/invitaciones/xv-lucia-hernandez-lopez/gallery/photo.jpg');
});

test('a client cannot clone invitations', function () {
    $client = User::factory()->create(['role' => User::ROLE_CLIENT]);
    $source = Invitacion::query()->create([
        'nombre' => 'Mariana',
        'apellido_paterno' => 'Demo',
        'ruta' => 'xv-mariana',
    ]);

    $this
        ->actingAs($client)
        ->post(route('admin.invitaciones.clone', $source), [
            'nombre_completo' => 'Lucía Hernández',
        ])
        ->assertForbidden();
});
