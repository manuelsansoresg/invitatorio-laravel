<?php

use App\Models\Invitacion;
use Database\Seeders\InstagramWeddingInvitationSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('instagram wedding template renders every active invitation section as a story', function () {
    $this->seed(InstagramWeddingInvitationSeeder::class);

    $invitation = Invitacion::query()->where('ruta', 'boda-instagram')->firstOrFail();

    $this->get(route('invitaciones.show', $invitation))
        ->assertOk()
        ->assertSee('Invitación de boda en formato historias')
        ->assertSee('data-label="Portada"', false)
        ->assertSee('data-label="Cuenta regresiva"', false)
        ->assertSee('data-label="Ceremonia"', false)
        ->assertSee('data-label="Recepción"', false)
        ->assertSee('data-label="Dress code"', false)
        ->assertSee('data-label="Confirmación"', false)
        ->assertSee('data-open-confirm', false)
        ->assertDontSee('Comentar')
        ->assertDontSee('Me gusta');
});

test('instagram wedding seeder creates an editable published demo', function () {
    $this->seed(InstagramWeddingInvitationSeeder::class);

    $invitation = Invitacion::query()->where('ruta', 'boda-instagram')->firstOrFail();

    expect($invitation->template_key)->toBe('instagram')
        ->and($invitation->tipo_evento)->toBe('boda')
        ->and($invitation->blocks()->count())->toBe(11)
        ->and($invitation->gallery()->count())->toBe(3)
        ->and($invitation->imagen_portada_path)->toBe('images/templates/instagram/hero.webp');
});
