<?php

namespace Database\Seeders;

use App\Models\Paquete;
use Illuminate\Database\Seeder;

/**
 * Catálogo de paquetes de Invitatorio.
 *
 * Mantenemos la misma información que estaba hardcodeada en
 * welcome.blade.php. Cualquier cambio de precio/contenido se hace
 * acá y se corre `php artisan db:seed --class=PaqueteSeeder`.
 */
class PaqueteSeeder extends Seeder
{
    public function run(): void
    {
        $paquetes = [
            // ──────────── WEB ────────────
            [
                'slug'        => 'web-esencial',
                'formato'     => 'web',
                'nombre'      => 'Web Esencial',
                'descripcion' => 'Para eventos sencillos que necesitan verse bien y compartir todos los datos básicos.',
                'precio_centavos' => 60000,
                'badge'       => 'Básica',
                'destacado'   => false,
                'items'       => [
                    'Invitación web adaptable a celular',
                    'Fecha, hora y datos del evento',
                    'Música de fondo',
                    'Cuenta regresiva',
                    'Mesa de regalos',
                    'Galería de fotos hasta 3 imágenes',
                    'Botón de WhatsApp',
                ],
                'orden' => 1,
            ],
            [
                'slug'        => 'web-plus',
                'formato'     => 'web',
                'nombre'      => 'Web Plus',
                'descripcion' => 'Para quienes quieren una invitación más completa sin llegar al paquete premium.',
                'precio_centavos' => 90000,
                'badge'       => 'Más elegida',
                'destacado'   => true,
                'items'       => [
                    'Todo lo de Web Esencial',
                    'Ubicación con Google Maps',
                    'Galería de fotos hasta 5 imágenes',
                    'Itinerario del evento',
                    'Botón para agregar al calendario',
                    'Diseño con más detalles visuales',
                ],
                'orden' => 2,
            ],
            [
                'slug'        => 'web-premium',
                'formato'     => 'web',
                'nombre'      => 'Web Premium',
                'descripcion' => 'Para bodas, XV años y eventos formales donde necesitas confirmaciones y más secciones.',
                'precio_centavos' => 130000,
                'badge'       => 'Completa',
                'destacado'   => false,
                'items'       => [
                    'Todo lo de Web Plus',
                    'Galería de fotos hasta 10 imágenes',
                    'Confirmación de asistencia RSVP',
                    'Lista básica de invitados confirmados',
                    'Sección de padres, padrinos o familia',
                    'Recomendaciones para invitados',
                    'Diseño más elegante y trabajado',
                ],
                'orden' => 3,
            ],

            // ──────────── IMAGEN ────────────
            [
                'slug'        => 'imagen-basica',
                'formato'     => 'imagen',
                'nombre'      => 'Imagen Básica',
                'descripcion' => 'Para fiestas, cumpleaños o reuniones que necesitan una invitación visual rápida.',
                'precio_centavos' => 15000,
                'badge'       => 'Rápida',
                'destacado'   => false,
                'items'       => [
                    'Diseño estático personalizado',
                    'Formato vertical para WhatsApp',
                    'Nombres, fecha, hora y lugar',
                    'Dress code o nota especial',
                    'Entrega en PNG/JPG',
                ],
                'orden' => 1,
            ],
            [
                'slug'        => 'imagen-premium',
                'formato'     => 'imagen',
                'nombre'      => 'Imagen Premium',
                'descripcion' => 'Para fiestas con temática, baby shower, bautizos o celebraciones que necesitan verse más cuidadas.',
                'precio_centavos' => 25000,
                'badge'       => 'Más elegida',
                'destacado'   => true,
                'items'       => [
                    'Todo lo de Imagen Básica',
                    'Diseño con más detalle visual',
                    'Versión para historia o estado',
                    'Versión cuadrada para publicación',
                    'Un ajuste posterior incluido',
                ],
                'orden' => 2,
            ],

            // ──────────── VIDEO ────────────
            [
                'slug'        => 'video-basico',
                'formato'     => 'video',
                'nombre'      => 'Video Básico',
                'descripcion' => 'Para presentar tu evento con una versión animada sencilla.',
                'precio_centavos' => 30000,
                'badge'       => 'Animado',
                'destacado'   => false,
                'items'       => [
                    'Video vertical animado',
                    'Música de fondo',
                    'Texto con datos principales',
                    'Fotos o elementos visuales del evento',
                    'Formato MP4 para redes',
                ],
                'orden' => 1,
            ],
            [
                'slug'        => 'video-premium',
                'formato'     => 'video',
                'nombre'      => 'Video Premium',
                'descripcion' => 'Para un video simple con más escenas y mejor ritmo visual.',
                'precio_centavos' => 45000,
                'badge'       => 'Más elegido',
                'destacado'   => true,
                'items'       => [
                    'Todo lo de Video Básico',
                    'Animaciones más elaboradas',
                    'Más escenas o momentos',
                    'Diseño según temática',
                    'Un ajuste posterior incluido',
                ],
                'orden' => 2,
            ],
        ];

        foreach ($paquetes as $p) {
            Paquete::updateOrCreate(
                ['slug' => $p['slug']],
                array_merge($p, ['activo' => true])
            );
        }
    }
}
