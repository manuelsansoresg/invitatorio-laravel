<?php

use App\Models\Invitacion;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('an administrator can assign edit and remove an invitation client', function () {
    $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
    $client = User::factory()->create(['role' => User::ROLE_CLIENT]);
    $invitation = Invitacion::query()->create([
        'nombre' => 'Mariana',
        'apellido_paterno' => 'Demo',
        'ruta' => 'xv-mariana',
    ]);

    $this
        ->actingAs($admin)
        ->patch(route('admin.invitaciones.cliente.update', $invitation), [
            'user_id' => $client->id,
            'assignment_source' => $invitation->ruta,
        ])
        ->assertRedirect(route('admin.dashboard'));

    expect($invitation->fresh()->user_id)->toBe($client->id);

    $this
        ->actingAs($admin)
        ->patch(route('admin.invitaciones.cliente.update', $invitation), [
            'user_id' => '',
            'assignment_source' => $invitation->ruta,
        ])
        ->assertRedirect(route('admin.dashboard'));

    expect($invitation->fresh()->user_id)->toBeNull();
});

test('an administrator cannot assign another administrator as a client', function () {
    $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
    $otherAdmin = User::factory()->create(['role' => User::ROLE_ADMIN]);
    $invitation = Invitacion::query()->create([
        'nombre' => 'Mariana',
        'apellido_paterno' => 'Demo',
        'ruta' => 'xv-mariana',
    ]);

    $this
        ->actingAs($admin)
        ->from(route('admin.dashboard'))
        ->patch(route('admin.invitaciones.cliente.update', $invitation), [
            'user_id' => $otherAdmin->id,
            'assignment_source' => $invitation->ruta,
        ])
        ->assertRedirect(route('admin.dashboard'))
        ->assertSessionHasErrorsIn('clientAssignment', ['user_id']);

    expect($invitation->fresh()->user_id)->toBeNull();
});
