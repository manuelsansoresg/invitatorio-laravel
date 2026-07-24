<?php

namespace Database\Seeders;

use App\Models\Invitacion;
use App\Models\InvitationBlock;
use App\Models\InvitationGallery;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class ValentinaInvitationSeeder extends Seeder
{
    public function run(): void
    {
        $this->ensurePublicAssetsExist();

        DB::transaction(function (): void {
            $invitation = Invitacion::query()->updateOrCreate(
                ['ruta' => 'xv-valentina'],
                [
                    'nombre' => 'Valentina',
                    'apellido_paterno' => 'Franco',
                    'apellido_materno' => 'García',
                    'tipo_evento' => 'xv',
                    'titulo' => 'Mis XV Años',
                    'subtitulo' => 'Te invitamos',
                    'fecha_evento' => '2026-08-01',
                    'hora_evento' => '22:00:00',
                    'lugar_nombre' => 'El Pedregal',
                    'lugar_direccion' => 'Calle 5#211 x 26A y 28 Hunucmá, Yucatán',
                    'maps_url' => 'https://www.google.com/maps?q=21.035107,-89.869308',
                    'dress_code' => 'Dress code a tu estilo',
                    'dress_code_descripcion' => 'Solo el rosa está reservado para la quinceañera',
                    'mensaje_principal' => 'Con mucha alegría queremos compartir contigo este día tan especial.',
                    'mensaje_footer' => 'Con cariño, familia de Valentina',
                    'whatsapp_numero' => '529991234567',
                    'whatsapp_mensaje' => 'Hola, confirmo mi asistencia a los XV de Valentina',
                    'musica_path' => 'music/music.mp3',
                    'musica_titulo' => 'Música de fondo',
                    'imagen_portada_path' => 'images/xv/valeria/foto-intro.jpeg',
                    'archivo_final_path' => null,
                    'color_primario' => '#D77C78',
                    'color_secundario' => '#F8D8D4',
                    'color_acento' => '#E8AAA6',
                    'template_key' => 'xv-valeria',
                    'estado' => 'publicada',
                    'publicada_at' => now(),
                ],
            );

            $this->restoreBlocks($invitation);
            $this->restoreGallery($invitation);
        });
    }

    private function restoreBlocks(Invitacion $invitation): void
    {
        $blocks = [
            [
                'tipo' => 'hero',
                'titulo' => 'Mis XV Años',
                'contenido' => 'Con mucha alegría queremos compartir contigo este día tan especial.',
                'orden' => 10,
                'config_json' => [
                    'fecha_corta' => '01 · Agosto · 2026',
                    'fecha_larga' => 'Sábado 01 de agosto de 2026',
                    'hora_recepcion' => '10:00 PM',
                    'imagen_intro' => 'images/xv/valeria/foto-intro.jpeg',
                    'imagen_hero' => 'images/xv/valeria/valeria-hero.jpeg',
                    'imagen_parallax' => 'images/xv/valeria/parallax.jpeg',
                ],
            ],
            [
                'tipo' => 'cuenta_regresiva',
                'titulo' => 'Cuenta regresiva',
                'contenido' => 'Falta muy poco para este gran día.',
                'orden' => 20,
                'config_json' => [
                    'event_date_iso' => '2026-08-01T22:00:00-06:00',
                ],
            ],
            [
                'tipo' => 'mensaje',
                'titulo' => 'Hay momentos que se sueñan desde niña y hoy comienzan a hacerse realidad.',
                'contenido' => 'Con mucha ilusión, Valentina Franco García quiere compartir contigo una noche llena de alegría, recuerdos y momentos inolvidables.',
                'orden' => 30,
                'config_json' => [
                    'kicker' => 'Un sueño especial',
                ],
            ],
            [
                'tipo' => 'galeria',
                'titulo' => 'Galería de recuerdos',
                'contenido' => 'Pequeños momentos que forman parte de esta historia tan especial.',
                'orden' => 40,
                'config_json' => [],
            ],
            [
                'tipo' => 'ubicacion',
                'titulo' => 'Misa de acción de gracias',
                'contenido' => 'Acompáñanos a dar gracias por este momento tan especial.',
                'orden' => 50,
                'config_json' => [
                    'variante' => 'iglesia',
                    'kicker' => 'Dónde',
                    'nombre' => 'Capilla de Guadalupe de Hunucmá',
                    'direccion' => 'Calle 5#211 x 26A y 28 Hunucmá, Yucatán',
                    'hora' => '8:30 p.m.',
                    'celebrante' => 'Pbro. Raymundo Abelardo Pérez Bojórquez',
                    'maps_url' => 'https://www.google.com/maps/place/Capilla+de+Nuestra+Se%C3%B1ora+de+Guadalupe/@21.0050406,-89.8819956,19z/data=!4m6!3m5!1s0x8f5607f86cf6c17b:0xc640e10929ff792e!8m2!3d21.0052171!4d-89.8807725!16s%2Fg%2F11n6t3b2yj',
                    'maps_embed' => 'https://www.google.com/maps?q=21.0052171,-89.8807725&output=embed',
                ],
            ],
            [
                'tipo' => 'padrinos',
                'titulo' => 'Familia y padrinos',
                'contenido' => 'Gracias por acompañarme y formar parte de este momento tan especial.',
                'orden' => 60,
                'config_json' => [
                    'kicker' => 'Con mucho cariño',
                    'grupos' => [
                        ['label' => 'Padres', 'nombres' => ['Dailly García Pérez', 'José Luís Franco Osalde'], 'destacado' => true],
                        ['label' => 'Padrinos', 'nombres' => ['Daniella Traconis Alcocer', 'Enrique Massa Pérez']],
                        ['label' => 'Padrinos', 'nombres' => ['Nelvy Gabriela Franco Ceballos', 'Moisés Ramón López López']],
                    ],
                ],
            ],
            [
                'tipo' => 'informacion_evento',
                'titulo' => 'El Pedregal',
                'contenido' => 'Recepción para celebrar juntos.',
                'orden' => 70,
                'config_json' => [
                    'kicker' => 'Recepción',
                    'maps_embed' => 'https://maps.google.com/maps?hl=es&q=21.035107,-89.869308&z=17&output=embed',
                ],
            ],
            [
                'tipo' => 'dress_code',
                'titulo' => 'Dress code',
                'contenido' => 'Solo el rosa está reservado para la quinceañera',
                'orden' => 80,
                'config_json' => [
                    'kicker' => 'Vestimenta',
                    'principal' => 'Dress code a tu estilo',
                    'color_reservado' => '#F7C9D6',
                ],
            ],
            [
                'tipo' => 'mesa_regalos',
                'titulo' => 'Lluvia de sobres',
                'contenido' => 'Tu presencia es el mejor regalo. Si deseas obsequiarme un detalle, habrán sobres para regalos en efectivo.',
                'orden' => 90,
                'config_json' => [
                    'kicker' => 'Detalle',
                    'cierre' => 'Con cariño, gracias por acompañarme.',
                ],
            ],
            [
                'tipo' => 'whatsapp',
                'titulo' => 'Confirma tu asistencia',
                'contenido' => 'Nos encantará contar contigo en este día tan especial.',
                'orden' => 100,
                'config_json' => [
                    'kicker' => 'RSVP',
                ],
            ],
            [
                'tipo' => 'musica',
                'titulo' => 'Música de fondo',
                'contenido' => null,
                'orden' => 110,
                'config_json' => [
                    'path' => 'music/music.mp3',
                ],
            ],
        ];

        $types = collect($blocks)->pluck('tipo');

        InvitationBlock::query()
            ->where('invitacion_id', $invitation->id)
            ->whereNotIn('tipo', $types)
            ->delete();

        foreach ($blocks as $block) {
            InvitationBlock::query()->updateOrCreate(
                [
                    'invitacion_id' => $invitation->id,
                    'tipo' => $block['tipo'],
                ],
                $block + ['activo' => true],
            );
        }
    }

    private function restoreGallery(Invitacion $invitation): void
    {
        InvitationGallery::query()
            ->where('invitacion_id', $invitation->id)
            ->whereNotBetween('orden', [1, 6])
            ->delete();

        foreach (range(1, 6) as $index) {
            InvitationGallery::query()->updateOrCreate(
                [
                    'invitacion_id' => $invitation->id,
                    'orden' => $index,
                ],
                [
                    'imagen_path' => sprintf('images/xv/valeria/slider/valeria-recuerdo-%02d.jpeg', $index),
                    'titulo' => 'Recuerdo '.$index,
                    'descripcion' => null,
                    'activo' => true,
                ],
            );
        }
    }

    private function ensurePublicAssetsExist(): void
    {
        $assets = [
            'images/xv/valeria/foto-intro.jpeg',
            'images/xv/valeria/valeria-hero.jpeg',
            'images/xv/valeria/parallax.jpeg',
            'music/music.mp3',
        ];

        foreach (range(1, 8) as $index) {
            $assets[] = sprintf('images/xv/valeria/slider/valeria-recuerdo-%02d.jpeg', $index);
        }

        $missing = collect($assets)
            ->reject(fn (string $path) => is_file(public_path($path)))
            ->values();

        if ($missing->isNotEmpty()) {
            throw new RuntimeException(
                'No se puede restaurar la invitación de Valentina. Faltan archivos públicos: '.$missing->implode(', '),
            );
        }
    }
}
