<?php

namespace App\Console\Commands;

use App\Models\Orden;
use App\Models\Suscripcion;
use App\Models\User;
use App\Services\SuscripcionService;
use Illuminate\Console\Command;

/**
 * Crea suscripciones retroactivas para las órdenes ya existentes
 * cuyo comprador tiene un user con el mismo email.
 *
 * Caso: ya hay órdenes aprobadas antes de este feature, y el
 * cliente correspondiente tiene cuenta de usuario. Sin este comando,
 * esas órdenes no generan suscripción y el cliente no podría
 * publicar invitaciones.
 *
 * Política retroactiva generosa: max_invitaciones=99, dias_caducidad
 * heredados del paquete. Si el admin quiere algo más estricto, edita
 * la suscripción desde el panel de Usuarios.
 *
 * Uso:
 *   php artisan suscripciones:retroactivas
 */
class SuscripcionesRetroactivas extends Command
{
    protected $signature = 'suscripciones:retroactivas {--dry-run : Solo muestra qué haría, sin crear nada}';

    protected $description = 'Crea suscripciones para órdenes ya aprobadas cuyos compradores tienen user con el mismo email.';

    public function __construct(
        private readonly SuscripcionService $suscripciones,
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $dryRun = (bool) $this->option('dry-run');

        $ordenes = Orden::query()
            ->where('estado', 'approved')
            ->whereDoesntHave('suscripcion')
            ->whereNotNull('comprador_email')
            ->get();

        $this->info("Encontradas {$ordenes->count()} órdenes aprobadas sin suscripción.");

        $creadas = 0;
        $sinUser = 0;

        foreach ($ordenes as $orden) {
            $user = User::query()->where('email', $orden->comprador_email)->first();
            if (! $user) {
                $sinUser++;
                $this->warn("  · Orden #{$orden->id} ({$orden->comprador_email}): el comprador no tiene user con ese email — se omite.");
                continue;
            }

            if ($dryRun) {
                $this->line("  · [dry-run] Crearía suscripción para user #{$user->id} desde orden #{$orden->id}.");
                $creadas++;
                continue;
            }

            $suscripcion = $this->suscripciones->crearSuscripcionPorCompra($orden);
            if ($suscripcion) {
                // Política retroactiva generosa: cupo casi ilimitado.
                $suscripcion->update([
                    'max_invitaciones' => 99,
                    'notas_admin'     => "Retroactivo. Generada desde orden #{$orden->id} ya aprobada antes del feature de suscripciones.",
                ]);
                $creadas++;
                $this->info("  ✓ Suscripción #{$suscripcion->id} creada para {$user->email} (orden #{$orden->id}).");
            }
        }

        $this->newLine();
        $this->info("Resultado: {$creadas} suscripción(es) creada(s), {$sinUser} omitida(s) por no encontrar user.");

        if ($dryRun) {
            $this->warn("Modo dry-run: no se creó nada en la BD. Quita --dry-run para aplicar.");
        }

        return self::SUCCESS;
    }
}
