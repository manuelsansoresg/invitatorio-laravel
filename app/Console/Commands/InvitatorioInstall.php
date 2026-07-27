<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

/**
 * Setup rápido del proyecto Invitatorio.
 *
 * Lo que hace:
 *   1. Corre las migraciones pendientes
 *   2. Puebla los paquetes (precio, descripción, items)
 *   3. Puebla el usuario admin por defecto
 *
 * Uso:
 *   php artisan invitatorio:install
 *   php artisan invitatorio:install --fresh   # ⚠️ BORRA toda la BD
 *
 * Es el comando que hay que correr después de clonar el repo, o cada
 * vez que se cambia APP_URL y se quiere empezar de nuevo.
 */
class InvitatorioInstall extends Command
{
    protected $signature = 'invitatorio:install
                            {--fresh : Drop all tables before migrating (DESTRUCTIVO)}';

    protected $description = 'Setup de Invitatorio: migraciones + seeders base (paquetes, admin user).';

    public function handle(): int
    {
        $this->info('🛠  Invitatorio — Setup');
        $this->newLine();

        if ($this->option('fresh')) {
            if (! $this->confirm('⚠️  --fresh BORRA TODA LA BASE DE DATOS. ¿Continuar?', false)) {
                $this->warn('Cancelado.');
                return self::FAILURE;
            }
            $this->warn('Borrando todas las tablas…');
            Artisan::call('migrate:fresh', ['--force' => true]);
        } else {
            $this->line('→ Corriendo migraciones pendientes…');
            Artisan::call('migrate', ['--force' => true]);
        }

        $this->line('→ Sembrando paquetes…');
        Artisan::call('db:seed', ['--class' => 'PaqueteSeeder', '--force' => true]);

        $this->line('→ Sembrando usuario admin…');
        Artisan::call('db:seed', ['--class' => 'AdminUserSeeder', '--force' => true]);

        $this->newLine();
        $this->info('✓ Listo. La landing debería mostrar los 7 paquetes.');
        $this->line('  Si no los ves, prueba: php artisan optimize:clear');

        return self::SUCCESS;
    }
}
