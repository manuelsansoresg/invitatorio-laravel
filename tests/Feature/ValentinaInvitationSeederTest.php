<?php

use App\Models\Invitacion;
use App\Models\User;
use Database\Seeders\ValentinaInvitationSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

uses(RefreshDatabase::class);

test('valentina seeder restores the complete invitation without losing its owner or confirmations', function () {
    $client = User::factory()->create(['role' => User::ROLE_CLIENT]);
    $invitation = Invitacion::query()->create([
        'nombre' => 'Valentina',
        'apellido_paterno' => 'Franco',
        'apellido_materno' => 'García',
        'ruta' => 'xv-valentina',
        'user_id' => $client->id,
    ]);

    DB::table('confirmaciones')->insert([
        'invitacion_id' => $invitation->id,
        'nombre' => 'Invitado existente',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $this->seed(ValentinaInvitationSeeder::class);

    $restored = $invitation->fresh();

    expect($restored->id)->toBe($invitation->id)
        ->and($restored->user_id)->toBe($client->id)
        ->and($restored->confirmaciones()->count())->toBe(1)
        ->and($restored->titulo)->toBe('Mis XV Años')
        ->and($restored->template_key)->toBe('xv-valeria')
        ->and($restored->imagen_portada_path)->toBe('images/xv/valeria/foto-intro.jpeg')
        ->and($restored->color_primario)->toBe('#D77C78')
        ->and($restored->blocks()->count())->toBe(11)
        ->and($restored->gallery()->count())->toBe(6)
        ->and($restored->blocks()->where('tipo', 'hero')->first()->config_json['imagen_hero'])
        ->toBe('images/xv/valeria/valeria-hero.jpeg');
});
