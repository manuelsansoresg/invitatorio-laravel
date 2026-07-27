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
        // No correr durante comandos artisan (migrate, seed, etc.) para
        // no entrar en loop ni ralentizar los comandos manuales.
        if ($this->app->runningInConsole() && ! $this->isHttpRequestFromConsole()) {
            return;
        }

        $marker = storage_path('app/.setup-completed');

        // Ya se inicializó antes
        if (file_exists($marker)) {
            return;
        }

        try {
            // Verificar si las tablas base existen. La tabla 'migrations'
            // está siempre presente después de la primera migración.
            $needsSetup = ! Schema::hasTable('migrations')
                || ! Schema::hasTable('users')
                || ! Schema::hasTable('paquetes');

            if (! $needsSetup) {
                // La BD ya está lista. Marcamos y salimos.
                $this->markSetupCompleted();
                return;
            }

            Log::info('Auto-setup: BD no inicializada, corriendo migraciones y seeders.');

            Artisan::call('migrate', ['--force' => true]);
            Artisan::call('db:seed', ['--class' => 'PaqueteSeeder', '--force' => true]);
            Artisan::call('db:seed', ['--class' => 'AdminUserSeeder', '--force' => true]);

            $this->markSetupCompleted();

            Log::info('Auto-setup: completado.');
        } catch (Throwable $e) {
            // No romper la app si algo falla — solo loguear.
            Log::error('Auto-setup falló', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }

    private function markSetupCompleted(): void
    {
        $dir = storage_path('app');
        if (! is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }
        @file_put_contents($dir . '/.setup-completed', date('c') . PHP_EOL);
    }

    /**
     * Detecta si estamos en un request HTTP servido desde CLI (artisan serve),
     * en cuyo caso SÍ queremos correr el auto-setup para que el dev local
     * funcione sin pasos manuales.
     */
    private function isHttpRequestFromConsole(): bool
    {
        return $this->app->runningInConsole()
            && isset($_SERVER['SERVER_NAME'])
            && ! in_array($_SERVER['SERVER_NAME'] ?? '', ['', 'cli'], true);
    }
}
