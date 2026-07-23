<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('invitaciones')) {
            return;
        }

        $moveOwnedPath = function (mixed $path, string $route): mixed {
            if (! is_string($path) || $path === '') {
                return $path;
            }

            $route = trim($route, '/');
            $legacyPrefix = 'storage/invitaciones/'.$route.'/';

            if (! str_starts_with($path, $legacyPrefix)) {
                return $path;
            }

            $sourcePath = substr($path, strlen('storage/'));
            $targetPath = 'uploads/invitaciones/'.$route.'/'.substr($path, strlen($legacyPrefix));
            $legacyDisk = Storage::disk('public');
            $publicDisk = Storage::disk('public_uploads');

            if ($publicDisk->exists($targetPath)) {
                $legacyDisk->delete($sourcePath);

                return $targetPath;
            }

            if (! $legacyDisk->exists($sourcePath)) {
                return $path;
            }

            $copied = $publicDisk->put($targetPath, $legacyDisk->get($sourcePath));

            if (! $copied || ! $publicDisk->exists($targetPath)) {
                return $path;
            }

            $legacyDisk->delete($sourcePath);

            return $targetPath;
        };

        $moveConfigPaths = function (mixed $value, string $route) use (&$moveConfigPaths, $moveOwnedPath): mixed {
            if (is_array($value)) {
                return array_map(
                    fn (mixed $item) => $moveConfigPaths($item, $route),
                    $value,
                );
            }

            return $moveOwnedPath($value, $route);
        };

        DB::table('invitaciones')
            ->orderBy('id')
            ->get()
            ->each(function (object $invitation) use ($moveOwnedPath, $moveConfigPaths): void {
                $updates = [];

                foreach (['musica_path', 'imagen_portada_path', 'archivo_final_path'] as $column) {
                    if (! property_exists($invitation, $column)) {
                        continue;
                    }

                    $movedPath = $moveOwnedPath($invitation->{$column}, $invitation->ruta);

                    if ($movedPath !== $invitation->{$column}) {
                        $updates[$column] = $movedPath;
                    }
                }

                if ($updates !== []) {
                    DB::table('invitaciones')->where('id', $invitation->id)->update($updates);
                }

                if (Schema::hasTable('invitacion_bloques')) {
                    DB::table('invitacion_bloques')
                        ->where('invitacion_id', $invitation->id)
                        ->whereNotNull('config_json')
                        ->get()
                        ->each(function (object $block) use ($invitation, $moveConfigPaths): void {
                            $config = json_decode($block->config_json, true);

                            if (! is_array($config)) {
                                return;
                            }

                            $movedConfig = $moveConfigPaths($config, $invitation->ruta);

                            if ($movedConfig !== $config) {
                                DB::table('invitacion_bloques')
                                    ->where('id', $block->id)
                                    ->update([
                                        'config_json' => json_encode(
                                            $movedConfig,
                                            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES,
                                        ),
                                    ]);
                            }
                        });
                }

                if (Schema::hasTable('invitacion_galeria')) {
                    DB::table('invitacion_galeria')
                        ->where('invitacion_id', $invitation->id)
                        ->get()
                        ->each(function (object $galleryItem) use ($invitation, $moveOwnedPath): void {
                            $movedPath = $moveOwnedPath($galleryItem->imagen_path, $invitation->ruta);

                            if ($movedPath !== $galleryItem->imagen_path) {
                                DB::table('invitacion_galeria')
                                    ->where('id', $galleryItem->id)
                                    ->update(['imagen_path' => $movedPath]);
                            }
                        });
                }
            });
    }

    public function down(): void
    {
        // Los archivos públicos no regresan a storage para conservar la regla de publicación directa.
    }
};
