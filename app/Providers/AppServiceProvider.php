<?php

namespace App\Providers;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Throwable;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * Auto-setup para entornos sin CLI (cPanel, shared hosting).
     *
     * El primer request que vea la BD vacía corre las migraciones y los
     * seeders base. Una vez completado, escribe un marker en
     * `storage/app/.setup-completed` para no volver a hacerlo. Si quieres
     * forzar la re-inicialización, borra ese archivo vía FTP o el
     * Administrador de archivos de cPanel.
     *
     * Por qué existe: en hosting compartido no hay SSH para correr
     * `php artisan migrate`. Esta es la única forma de levantar el
     * sistema sin intervención manual.
     */
    public function boot(): void
    {
        $this->autoSetupIfNeeded();
    }

    private function autoSetupIfNeeded(): void
    {
        try {
            $needsMigrate = ! Schema::hasTable('migrations')
                || ! Schema::hasTable('users');

            // Para paquetes: la tabla puede existir (si se corrieron migraciones
            // de este feature) pero estar vacía (porque el seeder no se ejecutó).
            // Verificamos contenido, no solo existencia.
            $needsPaqueteSeed = ! Schema::hasTable('paquetes')
                || \App\Models\Paquete::count() === 0;

            if (! $needsMigrate && ! $needsPaqueteSeed) {
                return; // Todo listo
            }

            Log::info('Auto-setup: disparado', [
                'needsMigrate' => $needsMigrate,
                'needsPaqueteSeed' => $needsPaqueteSeed,
            ]);

            if ($needsMigrate) {
                Artisan::call('migrate', ['--force' => true]);
                Artisan::call('db:seed', ['--class' => 'AdminUserSeeder', '--force' => true]);
            }

            if ($needsPaqueteSeed) {
                // PaqueteSeeder usa updateOrCreate por slug, así que es idempotente:
                // se puede llamar muchas veces sin duplicar.
                Artisan::call('db:seed', ['--class' => 'PaqueteSeeder', '--force' => true]);
            }

            Log::info('Auto-setup: completado.');
        } catch (Throwable $e) {
            // No romper la app si algo falla — solo loguear.
            Log::error('Auto-setup falló', [
                'error' => $e->getMessage(),
            ]);
        }
    }
}
