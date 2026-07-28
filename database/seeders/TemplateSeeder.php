<?php

namespace Database\Seeders;

use App\Models\Template;
use Illuminate\Database\Seeder;

/**
 * Templates iniciales para Invitatorio.
 *
 * Estos son los diseños base que se ofrecerán a los clientes.
 * Se crean con activo=true. El admin los puede desactivar después
 * o asignarlos selectivamente a cada usuario.
 */
class TemplateSeeder extends Seeder
{
    public function run(): void
    {
        $templates = [
            [
                'slug'        => 'valeria-elegante',
                'formato'     => 'web',
                'nombre'      => 'Valeria elegante',
                'descripcion' => 'El diseño clásico de Invitatorio: elegante, formal, con paleta púrpura y dorado.',
                'orden'       => 1,
                'config_json' => [
                    'color_primario'   => '#5A3087',
                    'color_secundario' => '#F4EFF8',
                    'color_acento'     => '#C9A05A',
                    'titulo_default'   => 'Te invitamos a celebrar',
                ],
            ],
            [
                'slug'        => 'pergamino-bodas',
                'formato'     => 'web',
                'nombre'      => 'Pergamino de bodas',
                'descripcion' => 'Estilo papel envejecido con líneas doradas. Ideal para bodas formales.',
                'orden'       => 2,
                'config_json' => [
                    'color_primario'   => '#8B6F3D',
                    'color_secundario' => '#FAF5E9',
                    'color_acento'     => '#B8932E',
                    'titulo_default'   => 'Nuestra boda',
                ],
            ],
            [
                'slug'        => 'xv-moderno',
                'formato'     => 'web',
                'nombre'      => 'XV moderno',
                'descripcion' => 'Para XV años: juvenil, con animaciones y mucho color.',
                'orden'       => 3,
                'config_json' => [
                    'color_primario'   => '#EB7512',
                    'color_secundario' => '#FFF1E1',
                    'color_acento'     => '#F45A00',
                    'titulo_default'   => 'Mis XV años',
                ],
            ],
            [
                'slug'        => 'imagen-formato-vertical',
                'formato'     => 'imagen',
                'nombre'      => 'Imagen vertical (WhatsApp)',
                'descripcion' => 'Para paquetes de imagen: el cliente llena los datos y el admin produce el archivo.',
                'orden'       => 1,
                'config_json' => [
                    'titulo_default' => 'Te esperamos',
                ],
            ],
            [
                'slug'        => 'video-aniversario',
                'formato'     => 'video',
                'nombre'      => 'Video animado',
                'descripcion' => 'Para paquetes de video: datos del cliente → el admin produce el MP4.',
                'orden'       => 1,
                'config_json' => [
                    'titulo_default' => 'Nuestra historia',
                ],
            ],
        ];

        foreach ($templates as $t) {
            Template::updateOrCreate(
                ['slug' => $t['slug']],
                array_merge($t, ['activo' => true])
            );
        }
    }
}
