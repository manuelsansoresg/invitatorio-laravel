<?php

namespace App\Http\Controllers;

use App\Models\Paquete;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;

/**
 * Diagnóstico del entorno en producción.
 *
 * ⚠️ ELIMINAR este controller después de resolver el problema.
 *
 * Devuelve JSON con:
 *   - Versión PHP y Laravel
 *   - Estado de conexión a BD
 *   - Tabla 'paquetes' existe? cuántos hay?
 *   - Rutas registradas que contengan "comprar"
 */
class DiagnoseController extends Controller
{
    public function show()
    {
        $data = [
            'php'              => PHP_VERSION,
            'laravel'          => app()->version(),
            'app_url'          => config('app.url'),
            'app_env'          => config('app.env'),
            'database_default' => config('database.default'),
            'db_connection'    => config('database.connections.' . config('database.default') . '.database'),
            'paquetes_table'   => Schema::hasTable('paquetes'),
            'paquetes_count'   => 0,
            'paquetes_activos' => 0,
            'rutas_con_comprar' => [],
            'paquete_buscado'  => null,
            'composer_paths'   => $this->composerPaths(),
        ];

        try {
            DB::connection()->getPdo();
            $data['db_ok'] = true;
        } catch (\Throwable $e) {
            $data['db_ok'] = false;
            $data['db_error'] = $e->getMessage();
        }

        if ($data['paquetes_table']) {
            $data['paquetes_count']   = Paquete::count();
            $data['paquetes_activos'] = Paquete::where('activo', true)->count();
            $data['paquetes_lista']   = Paquete::select('slug', 'activo', 'formato')->get()->toArray();
        }

        // Buscar el paquete "web-plus" manualmente
        if ($data['paquetes_table']) {
            $p = Paquete::where('slug', 'web-plus')->first();
            $data['paquete_buscado'] = $p ? [
                'id' => $p->id, 'slug' => $p->slug, 'activo' => $p->activo,
            ] : 'no encontrado';
        }

        // Rutas registradas con "comprar"
        foreach (Route::getRoutes() as $route) {
            if (str_contains($route->uri(), 'comprar')) {
                $data['rutas_con_comprar'][] = [
                    'uri'    => $route->uri(),
                    'method' => implode('|', $route->methods()),
                    'name'   => $route->getName(),
                    'action' => $route->getActionName(),
                ];
            }
        }

        return response()->json($data, 200, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    private function composerPaths(): array
    {
        $paths = [];
        foreach (['autoload_classmap.php', 'autoload_psr4.php'] as $file) {
            $f = base_path("vendor/composer/{$file}");
            if (is_file($f)) {
                $content = file_get_contents($f);
                // Buscar la línea $baseDir = ...
                if (preg_match('/\$baseDir\s*=\s*[^;]+;/m', $content, $m)) {
                    $paths[$file] = trim($m[0]);
                }
            }
        }
        return $paths;
    }
}
