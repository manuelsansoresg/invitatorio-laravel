<?php

namespace App\Console\Commands;

use App\Models\Invitacion;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

/**
 * Marca como 'vencida' todas las invitaciones que ya pasaron su
 * fecha_caducidad pero siguen figurando como 'publicada'.
 *
 * Programar para correr diario:
 *   $schedule->command('invitaciones:marcar-vencidas')->daily();
 * (configurar en app/Console/Kernel.php)
 *
 * La vista pública ya chequea estaVencida() con la fecha, así que
 * invitaciones que aún no se marcaron igual se muestran como
 * vencidas. Este comando es solo para que el admin/panel las
 * distinga visualmente sin tener que calcular la fecha.
 */
class MarcarInvitacionesVencidas extends Command
{
    protected $signature = 'invitaciones:marcar-vencidas';

    protected $description = 'Marca como vencidas las invitaciones publicadas que ya superaron su fecha_caducidad.';

    public function handle(): int
    {
        $now = Carbon::now();

        $cantidad = Invitacion::query()
            ->where('estado', 'publicada')
            ->whereNotNull('fecha_caducidad')
            ->where('fecha_caducidad', '<', $now)
            ->update(['estado' => 'vencida']);

        $this->info("{$cantidad} invitación(es) marcadas como vencidas.");

        return self::SUCCESS;
    }
}
