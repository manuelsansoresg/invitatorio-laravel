<?php

namespace Database\Seeders;

use App\Models\Invitacion;
use App\Models\InvitationBlock;
use App\Models\InvitationGallery;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InstagramWeddingInvitationSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            $invitation = Invitacion::query()->updateOrCreate(
                ['ruta' => 'boda-instagram'],
                [
                    'nombre' => 'Ana & Mateo',
                    'apellido_paterno' => '',
                    'apellido_materno' => '',
                    'tipo_evento' => 'boda',
                    'titulo' => 'Nuestra boda',
                    'subtitulo' => 'Nos casamos',
                    'fecha_evento' => '2026-11-14',
                    'hora_evento' => '18:30:00',
                    'lugar_nombre' => 'Hacienda Santa Lucía',
                    'lugar_direccion' => 'Mérida, Yucatán',
                    'maps_url' => 'https://www.google.com/maps/search/?api=1&query=Mérida+Yucatán',
                    'dress_code' => 'Formal tropical',
                    'dress_code_descripcion' => 'Queremos verte elegante y sentirte tú. El blanco queda reservado para la novia.',
                    'mensaje_principal' => 'Hay historias que merecen celebrarse para siempre. Queremos vivir la nuestra contigo.',
                    'mensaje_footer' => 'Con amor, Ana & Mateo',
                    'whatsapp_numero' => '529991234567',
                    'whatsapp_mensaje' => 'Hola, confirmo mi asistencia a la boda de Ana y Mateo',
                    'musica_path' => 'music/music.mp3',
                    'musica_titulo' => 'Nuestra canción',
                    'imagen_portada_path' => 'images/templates/instagram/hero.webp',
                    'color_primario' => '#A6664A',
                    'color_secundario' => '#EFE4D3',
                    'color_acento' => '#83906F',
                    'template_key' => 'instagram',
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
                'titulo' => 'Sí, para siempre',
                'contenido' => 'Nos elegimos para compartir la vida y queremos que seas testigo de este comienzo.',
                'orden' => 10,
                'config_json' => [
                    'fecha_corta' => '14 · 11 · 2026',
                    'fecha_larga' => 'Sábado, 14 de noviembre de 2026',
                    'hora_recepcion' => '6:30 PM',
                    'imagen_intro' => 'images/templates/instagram/hero.webp',
                    'imagen_hero' => 'images/templates/instagram/hero.webp',
                    'imagen_parallax' => 'images/templates/instagram/details.webp',
                ],
            ],
            [
                'tipo' => 'cuenta_regresiva',
                'titulo' => 'Falta muy poco',
                'contenido' => 'Cada día nos acerca a la celebración.',
                'orden' => 20,
                'config_json' => ['event_date_iso' => '2026-11-14T18:30:00-06:00'],
            ],
            [
                'tipo' => 'mensaje',
                'titulo' => 'El mejor capítulo comienza contigo',
                'contenido' => 'Queremos celebrar el amor, la amistad y la alegría de encontrarnos. Gracias por ser parte de este día.',
                'orden' => 30,
                'config_json' => ['kicker' => 'Nuestra historia'],
            ],
            [
                'tipo' => 'galeria',
                'titulo' => 'Momentos nuestros',
                'contenido' => 'Un vistazo a la historia que nos trajo hasta aquí.',
                'orden' => 40,
                'config_json' => [],
            ],
            [
                'tipo' => 'ubicacion',
                'titulo' => 'Templo de San Juan',
                'contenido' => 'Acompáñanos a celebrar nuestra ceremonia.',
                'orden' => 50,
                'config_json' => [
                    'kicker' => 'Ceremonia',
                    'nombre' => 'Templo de San Juan',
                    'direccion' => 'Centro Histórico, Mérida, Yucatán',
                    'hora' => '5:00 p.m.',
                    'maps_url' => 'https://www.google.com/maps/search/?api=1&query=Templo+de+San+Juan+Mérida',
                ],
            ],
            [
                'tipo' => 'padrinos',
                'titulo' => 'Junto a quienes amamos',
                'contenido' => 'Gracias por enseñarnos a amar y acompañarnos hasta este día.',
                'orden' => 60,
                'config_json' => [
                    'kicker' => 'Con gratitud',
                    'grupos' => [
                        ['label' => 'Padres de la novia', 'nombres' => ['María y Alejandro']],
                        ['label' => 'Padres del novio', 'nombres' => ['Lucía y Fernando']],
                    ],
                ],
            ],
            [
                'tipo' => 'informacion_evento',
                'titulo' => 'Hacienda Santa Lucía',
                'contenido' => 'Cena, brindis y una pista lista para celebrar juntos.',
                'orden' => 70,
                'config_json' => ['kicker' => 'Después de la ceremonia'],
            ],
            [
                'tipo' => 'dress_code',
                'titulo' => 'Dress code',
                'contenido' => 'Queremos verte elegante y sentirte tú. El blanco queda reservado para la novia.',
                'orden' => 80,
                'config_json' => [
                    'kicker' => 'Código de vestimenta',
                    'principal' => 'Formal tropical',
                ],
            ],
            [
                'tipo' => 'mesa_regalos',
                'titulo' => 'Mesa de regalos',
                'contenido' => 'Tu presencia es nuestro mejor regalo. Si deseas tener un detalle con nosotros, aquí encontrarás las opciones.',
                'orden' => 90,
                'config_json' => [
                    'kicker' => 'Un detalle',
                    'cierre' => 'Gracias por celebrar esta nueva etapa con nosotros.',
                ],
            ],
            [
                'tipo' => 'whatsapp',
                'titulo' => '¿Celebras con nosotros?',
                'contenido' => 'Confirma tu asistencia y ayúdanos a preparar cada detalle.',
                'orden' => 100,
                'config_json' => ['kicker' => 'RSVP'],
            ],
            [
                'tipo' => 'musica',
                'titulo' => 'Nuestra canción',
                'contenido' => null,
                'orden' => 110,
                'config_json' => ['path' => 'music/music.mp3'],
            ],
        ];

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
        $images = [
            'images/templates/instagram/hero.webp',
            'images/templates/instagram/details.webp',
            'images/templates/instagram/reception.webp',
        ];

        foreach ($images as $index => $image) {
            InvitationGallery::query()->updateOrCreate(
                [
                    'invitacion_id' => $invitation->id,
                    'orden' => $index + 1,
                ],
                [
                    'imagen_path' => $image,
                    'titulo' => 'Nuestra historia '.($index + 1),
                    'descripcion' => null,
                    'activo' => true,
                ],
            );
        }
    }
}
