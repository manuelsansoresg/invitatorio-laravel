<?php

namespace App\Console\Commands;

use App\Models\Template;
use App\Models\User;
use Illuminate\Console\Command;

/**
 * Asigna todos los templates ACTIVOS a uno o todos los usuarios.
 *
 * Uso:
 *   php artisan templates:asignar-todos                  # todos los usuarios
 *   php artisan templates:asignar-todos --user=manuel   # solo uno
 *   php artisan templates:asignar-todos --reset          # primero desactiva los actuales
 *
 * Útil para:
 *  - Onboarding inicial: que todos vean todos los templates.
 *  - Cuando agregas un template nuevo y quieres dárselo a todos.
 */
class AsignarTemplatesAUsuario extends Command
{
    protected $signature = 'templates:asignar-todos
        {--user= : Email o ID de un usuario específico (opcional)}
        {--reset : Primero desactiva los pivot existentes}';

    protected $description = 'Asigna todos los templates activos a un usuario o a todos.';

    public function handle(): int
    {
        $templates = Template::query()->where('activo', true)->get();
        if ($templates->isEmpty()) {
            $this->warn('No hay templates activos. Crea algunos primero desde /admin/templates.');
            return self::SUCCESS;
        }

        $users = $this->option('user')
            ? User::query()
                ->when(is_numeric($this->option('user')),
                    fn ($q) => $q->where('id', (int) $this->option('user')))
                ->when(! is_numeric($this->option('user')),
                    fn ($q) => $q->where('email', $this->option('user')))
                ->get()
            : User::query()->get();

        if ($users->isEmpty()) {
            $this->error('No se encontró el usuario.');
            return self::FAILURE;
        }

        $reset = (bool) $this->option('reset');
        $count = 0;

        foreach ($users as $user) {
            if ($reset) {
                $user->templates()->updateExistingPivot(
                    $user->templates->pluck('id')->all(),
                    ['activo' => false]
                );
            }

            foreach ($templates as $t) {
                $user->templates()->syncWithoutDetaching([
                    $t->id => [
                        'activo'      => true,
                        'asignado_en' => now(),
                    ],
                ]);
                $count++;
            }
            $this->info("  ✓ {$user->email} → {$templates->count()} templates");
        }

        $this->newLine();
        $this->info("Listo: {$count} asignaciones creadas para {$users->count()} usuario(s).");

        return self::SUCCESS;
    }
}
