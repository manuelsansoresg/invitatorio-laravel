<?php

namespace App\Console\Commands;

use App\Models\Paquete;
use Illuminate\Console\Command;

/**
 * Activa o desactiva el flag `permite_gestionar_invitados` de un paquete.
 *
 * La flag define si los clientes con ese paquete pueden ver/editar la
 * lista de invitados con link único. Por default los paquetes nuevos
 * nacen con la flag en false. Actívala para que el feature esté
 * disponible.
 *
 * Uso:
 *   php artisan paquetes:gestionar-invitados web-plus --on
 *   php artisan paquetes:gestionar-invitados web-plus --off
 *   php artisan paquetes:gestionar-invitados web-plus        # toggle
 *   php artisan paquetes:gestionar-invitados --all-on        # activa todos
 *
 * El cambio es instantáneo. No requiere migrar ni reiniciar el server.
 */
class PaqueteToggleGestionarInvitados extends Command
{
    protected $signature = 'paquetes:gestionar-invitados
        {slug? : Slug del paquete (ej: web-plus). Si se omite con --all-* opera sobre todos.}
        {--on : Forzar ON}
        {--off : Forzar OFF}
        {--all-on : Activar la flag en todos los paquetes}
        {--all-off : Desactivar la flag en todos los paquetes}';

    protected $description = 'Toggle del flag permite_gestionar_invitados en uno o todos los paquetes.';

    public function handle(): int
    {
        // Modo masivo
        if ($this->option('all-on') || $this->option('all-off')) {
            $valor = $this->option('all-on');
            $count = Paquete::query()->update(['permite_gestionar_invitados' => $valor]);
            $this->info("Flag actualizada en {$count} paquete(s): " . ($valor ? 'ON' : 'OFF'));
            $this->table(
                ['Slug', 'Permite gestionar invitados'],
                Paquete::orderBy('orden')->get(['slug', 'permite_gestionar_invitados'])
                    ->map(fn ($p) => [$p->slug, $p->permite_gestionar_invitados ? '✓ ON' : '✗ OFF'])
                    ->toArray()
            );
            return self::SUCCESS;
        }

        $slug = $this->argument('slug');
        if (! $slug) {
            $this->error('Especifica un slug o usa --all-on / --all-off.');
            $this->line('Ejemplos:');
            $this->line('  php artisan paquetes:gestionar-invitados web-plus --on');
            $this->line('  php artisan paquetes:gestionar-invitados web-plus --off');
            $this->line('  php artisan paquetes:gestionar-invitados web-plus        # toggle');
            return self::FAILURE;
        }

        $paquete = Paquete::where('slug', $slug)->first();
        if (! $paquete) {
            $this->error("No existe un paquete con slug «{$slug}».");
            $this->line('Paquetes disponibles:');
            $this->table(
                ['Slug', 'Nombre'],
                Paquete::orderBy('orden')->get(['slug', 'nombre'])
                    ->map(fn ($p) => [$p->slug, $p->nombre])
                    ->toArray()
            );
            return self::FAILURE;
        }

        // Resolver el nuevo valor
        if ($this->option('on')) {
            $nuevo = true;
        } elseif ($this->option('off')) {
            $nuevo = false;
        } else {
            $nuevo = ! $paquete->permite_gestionar_invitados;
        }

        $paquete->permite_gestionar_invitados = $nuevo;
        $paquete->save();

        $estado = $nuevo ? '✓ ACTIVADA' : '✗ DESACTIVADA';
        $this->info("Paquete «{$paquete->nombre}» ({$paquete->slug}): gestión de invitados {$estado}.");

        return self::SUCCESS;
    }
}
