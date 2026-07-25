<?php

use App\Livewire\InvitationEditor;
use App\Models\Invitacion;
use App\Models\InvitationBlock;
use Database\Seeders\ValentinaInvitationSeeder;
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

test('event date time and countdown always use the current invitation values', function () {
    $invitation = Invitacion::query()->create([
        'nombre' => 'Valeria',
        'apellido_paterno' => 'Mendez',
        'ruta' => 'xv-valeria-dinamica',
        'titulo' => 'Mis XV Años',
        'fecha_evento' => '2026-08-01',
        'hora_evento' => '22:00',
        'template_key' => 'xv-valeria',
        'estado' => 'borrador',
    ]);

    InvitationBlock::query()->create([
        'invitacion_id' => $invitation->id,
        'tipo' => 'hero',
        'config_json' => [
            'fecha_corta' => '01 · Agosto · 2026',
            'fecha_larga' => 'Sábado 01 de agosto de 2026',
            'hora_recepcion' => '10:00 PM',
        ],
        'orden' => 1,
        'activo' => true,
    ]);

    InvitationBlock::query()->create([
        'invitacion_id' => $invitation->id,
        'tipo' => 'cuenta_regresiva',
        'config_json' => [
            'event_date_iso' => '2026-08-01T22:00:00-06:00',
        ],
        'orden' => 2,
        'activo' => true,
    ]);

    Livewire::test(InvitationEditor::class, ['invitacion' => $invitation])
        ->set('form.fecha_evento', '2026-09-23')
        ->set('form.hora_evento', '20:15')
        ->assertHasNoErrors()
        ->assertDispatched('saved');

    $this
        ->get(route('invitaciones.show', $invitation))
        ->assertOk()
        ->assertSee('23 · septiembre · 2026')
        ->assertSee('Miércoles 23 de septiembre de 2026')
        ->assertSee('8:15 PM')
        ->assertSee('data-event-date="2026-09-23T20:15:00-06:00"', false)
        ->assertDontSee('01 · Agosto · 2026');
});

test('all visible valentina sections can be edited with friendly fields', function () {
    $this->seed(ValentinaInvitationSeeder::class);

    $invitation = Invitacion::query()->where('ruta', 'xv-valentina')->firstOrFail();
    $indexes = $invitation->blocks()
        ->get()
        ->values()
        ->mapWithKeys(fn (InvitationBlock $block, int $index) => [$block->tipo => $index]);

    Livewire::test(InvitationEditor::class, ['invitacion' => $invitation])
        ->set('form.mensaje_principal', 'Mensaje principal actualizado')
        ->set('form.lugar_nombre', 'Salón Dinámico')
        ->set('form.lugar_direccion', 'Dirección dinámica del salón')
        ->set('form.maps_url', 'https://maps.google.com/?q=20.1,-89.1')
        ->set('form.dress_code', 'Etiqueta elegante')
        ->set('form.dress_code_descripcion', 'Color azul reservado')
        ->set('form.mensaje_footer', 'Pie dinámico de la invitación')
        ->set('sectionSettings.hero.intro_kicker', 'Acompáñame a celebrar')
        ->set('sectionSettings.hero.intro_boton', 'Entrar a la invitación')
        ->set('sectionSettings.hero.etiqueta_hora', 'Fiesta')
        ->set('sectionSettings.mensaje.kicker', 'Nuestra historia')
        ->set('sectionSettings.galeria.kicker', 'Mis recuerdos')
        ->set('sectionSettings.ubicacion.kicker', 'Ceremonia')
        ->set('sectionSettings.ubicacion.nombre', 'Parroquia Dinámica')
        ->set('sectionSettings.ubicacion.direccion', 'Dirección dinámica de la iglesia')
        ->set('sectionSettings.ubicacion.hora', '7:45 p.m.')
        ->set('sectionSettings.ubicacion.hora_etiqueta', 'Inicio de ceremonia')
        ->set('sectionSettings.ubicacion.celebrante', 'Pbro. Ejemplo')
        ->set('sectionSettings.ubicacion.celebrante_etiqueta', 'Celebrante')
        ->set('sectionSettings.ubicacion.maps_url', 'https://maps.google.com/?q=21.1,-89.2')
        ->set('sectionSettings.ubicacion.boton', 'Abrir mapa de iglesia')
        ->set('sectionSettings.padrinos.kicker', 'Personas especiales')
        ->set('sectionSettings.padrinos.grupos', [[
            'label' => 'Padres',
            'nombres' => ['Persona Uno', 'Persona Dos', '', ''],
            'destacado' => true,
        ]])
        ->set('sectionSettings.informacion_evento.kicker', 'Gran recepción')
        ->set('sectionSettings.informacion_evento.boton', 'Abrir mapa del salón')
        ->set('sectionSettings.dress_code.kicker', 'Vestimenta sugerida')
        ->set('sectionSettings.dress_code.color_reservado', '#123456')
        ->set('sectionSettings.mesa_regalos.kicker', 'Obsequios')
        ->set('sectionSettings.mesa_regalos.cierre', 'Gracias por tu cariño')
        ->set('sectionSettings.whatsapp.kicker', 'Asistencia')
        ->set('sectionSettings.whatsapp.boton', 'Responder invitación')
        ->set('blocks.'.$indexes['mensaje'].'.titulo', 'Título dinámico del mensaje')
        ->set('blocks.'.$indexes['mensaje'].'.contenido', 'Contenido dinámico del mensaje')
        ->set('blocks.'.$indexes['ubicacion'].'.titulo', 'Misa completamente dinámica')
        ->set('blocks.'.$indexes['ubicacion'].'.contenido', 'Descripción dinámica de la ceremonia')
        ->set('blocks.'.$indexes['padrinos'].'.titulo', 'Familia dinámica')
        ->set('blocks.'.$indexes['padrinos'].'.contenido', 'Descripción dinámica de padrinos')
        ->set('blocks.'.$indexes['informacion_evento'].'.titulo', 'Recepción completamente dinámica')
        ->set('blocks.'.$indexes['informacion_evento'].'.contenido', 'Descripción dinámica de la recepción')
        ->set('blocks.'.$indexes['mesa_regalos'].'.titulo', 'Regalos dinámicos')
        ->set('blocks.'.$indexes['mesa_regalos'].'.contenido', 'Mensaje dinámico de regalos')
        ->set('blocks.'.$indexes['whatsapp'].'.titulo', 'Confirma dinámicamente')
        ->set('blocks.'.$indexes['whatsapp'].'.contenido', 'Texto dinámico de confirmación')
        ->assertHasNoErrors()
        ->assertDispatched('saved');

    $this
        ->get(route('invitaciones.show', $invitation))
        ->assertOk()
        ->assertSeeText('Acompáñame a celebrar')
        ->assertSeeText('Mensaje principal actualizado')
        ->assertSeeText('Misa completamente dinámica')
        ->assertSeeText('Parroquia Dinámica')
        ->assertSeeText('Inicio de ceremonia: 7:45 p.m.')
        ->assertSeeText('Celebrante: Pbro. Ejemplo')
        ->assertDontSeeText('Descripción dinámica de la ceremonia')
        ->assertDontSeeText('Dirección dinámica de la iglesia')
        ->assertSeeText('Familia dinámica')
        ->assertSeeText('Persona Uno')
        ->assertSeeText('Recepción completamente dinámica')
        ->assertSeeText('Salón Dinámico')
        ->assertSeeText('Etiqueta elegante')
        ->assertSeeText('Regalos dinámicos')
        ->assertSeeText('Gracias por tu cariño')
        ->assertSeeText('Confirma dinámicamente')
        ->assertSeeText('Responder invitación')
        ->assertSeeText('Pie dinámico de la invitación')
        ->assertSee('src="https://www.google.com/maps?q=21.0052171,-89.8807725&amp;output=embed"', false)
        ->assertSee('src="https://maps.google.com/maps?hl=es&amp;q=21.035107,-89.869308&amp;z=17&amp;output=embed"', false);
});
