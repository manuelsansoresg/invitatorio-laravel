<?php

use App\Models\Invitacion;
use App\Models\InvitationBlock;
use App\Models\InvitationGallery;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

test('an administrator can delete only the confirmed invitation and its owned files', function () {
    Storage::fake('public');
    Storage::fake('public_uploads');

    $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
    $invitation = Invitacion::query()->create([
        'nombre' => 'Mariana',
        'apellido_paterno' => 'Demo',
        'ruta' => 'xv-mariana',
    ]);
    $otherInvitation = Invitacion::query()->create([
        'nombre' => 'Valentina',
        'apellido_paterno' => 'Demo',
        'ruta' => 'xv-valentina',
    ]);

    InvitationBlock::query()->create([
        'invitacion_id' => $invitation->id,
        'tipo' => 'hero',
        'orden' => 10,
        'activo' => true,
    ]);
    InvitationGallery::query()->create([
        'invitacion_id' => $invitation->id,
        'imagen_path' => 'uploads/invitaciones/xv-mariana/gallery/photo.jpg',
        'orden' => 10,
        'activo' => true,
    ]);
    DB::table('confirmaciones')->insert([
        'invitacion_id' => $invitation->id,
        'nombre' => 'Invitado',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    Storage::disk('public_uploads')->put('uploads/invitaciones/xv-mariana/hero/photo.jpg', 'mariana');
    Storage::disk('public_uploads')->put('uploads/invitaciones/xv-valentina/hero/photo.jpg', 'valentina');

    $this
        ->actingAs($admin)
        ->delete(route('admin.invitaciones.destroy', $invitation), [
            'confirm_route' => 'xv-mariana',
            'delete_source' => 'xv-mariana',
        ])
        ->assertRedirect(route('admin.dashboard'));

    $this->assertDatabaseMissing('invitaciones', ['id' => $invitation->id]);
    $this->assertDatabaseMissing('invitacion_bloques', ['invitacion_id' => $invitation->id]);
    $this->assertDatabaseMissing('invitacion_galeria', ['invitacion_id' => $invitation->id]);
    $this->assertDatabaseMissing('confirmaciones', ['invitacion_id' => $invitation->id]);
    $this->assertDatabaseHas('invitaciones', ['id' => $otherInvitation->id]);
    Storage::disk('public_uploads')->assertMissing('uploads/invitaciones/xv-mariana/hero/photo.jpg');
    Storage::disk('public_uploads')->assertExists('uploads/invitaciones/xv-valentina/hero/photo.jpg');
});

test('an invitation is not deleted when the confirmation route does not match', function () {
    $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
    $invitation = Invitacion::query()->create([
        'nombre' => 'Mariana',
        'apellido_paterno' => 'Demo',
        'ruta' => 'xv-mariana',
    ]);

    $this
        ->actingAs($admin)
        ->from(route('admin.dashboard'))
        ->delete(route('admin.invitaciones.destroy', $invitation), [
            'confirm_route' => 'otra-ruta',
            'delete_source' => 'xv-mariana',
        ])
        ->assertRedirect(route('admin.dashboard'))
        ->assertSessionHasErrorsIn('deleteInvitation', ['confirm_route']);

    $this->assertDatabaseHas('invitaciones', ['id' => $invitation->id]);
});

test('a client cannot delete invitations', function () {
    $client = User::factory()->create(['role' => User::ROLE_CLIENT]);
    $invitation = Invitacion::query()->create([
        'nombre' => 'Mariana',
        'apellido_paterno' => 'Demo',
        'ruta' => 'xv-mariana',
    ]);

    $this
        ->actingAs($client)
        ->delete(route('admin.invitaciones.destroy', $invitation), [
            'confirm_route' => 'xv-mariana',
        ])
        ->assertForbidden();

    $this->assertDatabaseHas('invitaciones', ['id' => $invitation->id]);
});
