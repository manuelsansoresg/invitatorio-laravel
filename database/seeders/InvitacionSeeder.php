<?php

namespace Database\Seeders;

use App\Models\Invitacion;
use Illuminate\Database\Seeder;

class InvitacionSeeder extends Seeder
{
    /**
     * Seed de la invitación de Valentina para que el formulario
     * de confirmación pueda registrar invitados desde el primer momento.
     */
    public function run(): void
    {
        $invitaciones = [
            [
                'ruta' => 'xv-valentina',
                'nombre' => 'Valentina',
                'apellido_paterno' => 'Franco',
                'apellido_materno' => 'García',
            ],
            [
                'ruta' => 'xv-mariana',
                'nombre' => 'Mariana',
                'apellido_paterno' => 'Demo',
                'apellido_materno' => null,
            ],
        ];

        foreach ($invitaciones as $invitacion) {
            Invitacion::updateOrCreate(
                ['ruta' => $invitacion['ruta']],
                collect($invitacion)->except('ruta')->all(),
            );
        }
    }
}
