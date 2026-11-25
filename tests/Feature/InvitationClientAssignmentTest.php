<?php

use App\Models\Invitacion;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('an administrator can assign edit and remove an invitation client', function (string $route) {
    $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
    $client = User::factory()->create(['role' => User::ROLE_CLIENT]);
    $invitation = Invitacion::query()->create([
        'nombre' => 'Mariana',
        'apellido_paterno' => 'Demo',
        'ruta' => $route,
    ]);

    $this->actingAs($admin)
        ->get(route('admin.dashboard'))
        ->assertOk()
        ->assertSeeText('Asignar correo')
        ->assertSee('action="'.route('admin.invitaciones.cliente.update', $invitation).'"', false)
        ->assertSee('onchange="this.form.requestSubmit()"', false)
        ->assertSee('<option value="'.$client->id.'" >'.$client->email.'</option>', false)
        ->assertSee('<option value="'.$admin->id.'" >'.$admin->email.'</option>', false);

    $this
        ->actingAs($admin)
        ->patch(route('admin.invitaciones.cliente.update', $invitation), [
            'user_id' => $client->id,
            'assignment_source' => $invitation->ruta,
        ])
        ->assertRedirect(route('admin.dashboard'));

    expect($invitation->fresh()->user_id)->toBe($client->id);

    $this->get(route('admin.dashboard'))
        ->assertOk()
        ->assertSee('value="'.$client->id.'" selected', false);

    $this
        ->patch(route('admin.invitaciones.cliente.update', $invitation), [
            'user_id' => $admin->id,
            'assignment_source' => $invitation->ruta,
        ])
        ->assertRedirect(route('admin.dashboard'));

    expect($invitation->fresh()->user_id)->toBe($admin->id);

    $this
        ->patch(route('admin.invitaciones.cliente.update', $invitation), [
            'user_id' => '',
            'assignment_source' => $invitation->ruta,
        ])
        ->assertRedirect(route('admin.dashboard'));

    expect($invitation->fresh()->user_id)->toBeNull();
})->with(['xv-mariana', 'bautizo/plantilla-aurora']);

test('an administrator cannot assign an account that does not exist', function () {
    $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
    $invitation = Invitacion::query()->create([
        'nombre' => 'Mariana',
        'apellido_paterno' => 'Demo',
        'ruta' => 'xv-mariana',
    ]);

    $this
        ->actingAs($admin)
        ->from(route('admin.dashboard'))
        ->patch(route('admin.invitaciones.cliente.update', $invitation), [
            'user_id' => $admin->id + 1,
            'assignment_source' => $invitation->ruta,
        ])
        ->assertRedirect(route('admin.dashboard'))
        ->assertSessionHasErrorsIn('clientAssignment', ['user_id']);

    expect($invitation->fresh()->user_id)->toBeNull();
});
