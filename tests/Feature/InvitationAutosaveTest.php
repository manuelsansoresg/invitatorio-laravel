<?php

use App\Livewire\InvitationEditor;
use App\Models\Invitacion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

test('editing a field automatically persists it and refreshes the preview', function () {
    $invitation = Invitacion::query()->create([
        'nombre' => 'Mariana',
        'apellido_paterno' => 'Demo',
        'ruta' => 'xv-mariana',
        'template_key' => 'xv-mariana',
        'estado' => 'borrador',
    ]);

    Livewire::test(InvitationEditor::class, ['invitacion' => $invitation])
        ->set('form.nombre', 'Marianaa')
        ->assertHasNoErrors()
        ->assertDispatched('saved');

    expect($invitation->fresh()->nombre)->toBe('Marianaa');

    $this
        ->get(route('invitaciones.show', $invitation))
        ->assertOk()
        ->assertSee('Marianaa');
});
