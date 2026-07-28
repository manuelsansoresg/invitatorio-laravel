<?php

namespace Database\Seeders;

use App\Models\Cupon;
use App\Models\Paquete;
use Illuminate\Database\Seeder;

/**
 * Cupones de ejemplo para Invitatorio.
 *
 * Se crean tres cupones típicos que sirven para probar el flujo:
 *  - LANZAMIENTO20 → 20% off en todos los paquetes (sin paquetes asignados = aplica a todos)
 *  - WEB200        → $200 MXN off solo en paquetes web
 *  - IMAGEN100     → $100 MXN off solo en paquetes de imagen
 */
class CuponSeeder extends Seeder
{
    public function run(): void
    {
        $paquetesWeb    = Paquete::query()->where('formato', 'web')->pluck('id')->all();
        $paquetesImagen = Paquete::query()->where('formato', 'imagen')->pluck('id')->all();

        $cupones = [
            [
                'codigo'        => 'LANZAMIENTO20',
                'descripcion'   => 'Cupón de lanzamiento: 20% off en todo el catálogo.',
                'tipo'          => Cupon::TIPO_PORCENTAJE,
                'valor'         => 20,
                'paquetes'      => [],
            ],
            [
                'codigo'        => 'WEB200',
                'descripcion'   => '$200 MXN de descuento en paquetes web.',
                'tipo'          => Cupon::TIPO_PRECIO,
                'valor'         => 20000, // $200 MXN
                'paquetes'      => $paquetesWeb,
            ],
            [
                'codigo'        => 'IMAGEN100',
                'descripcion'   => '$100 MXN de descuento en paquetes de imagen.',
                'tipo'          => Cupon::TIPO_PRECIO,
                'valor'         => 10000, // $100 MXN
                'paquetes'      => $paquetesImagen,
            ],
        ];

        foreach ($cupones as $c) {
            $cupon = Cupon::updateOrCreate(
                ['codigo' => $c['codigo']],
                [
                    'descripcion'   => $c['descripcion'],
                    'tipo'          => $c['tipo'],
                    'valor'         => $c['valor'],
                    'minimo_compra_centavos' => 0,
                    'activo'        => true,
                ]
            );
            $cupon->paquetes()->sync($c['paquetes']);
        }
    }
}
