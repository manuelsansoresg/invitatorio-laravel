<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('invitaciones', function (Blueprint $table) {
            $table->foreignId('user_id')
                ->nullable()
                ->after('ruta')
                ->constrained('users')
                ->nullOnDelete();
        });

        $invitaciones = DB::table('invitaciones')->get();

        foreach ($invitaciones as $invitacion) {
            $userId = null;

            if (filled($invitacion->cliente_email ?? null)) {
                $userId = DB::table('users')
                    ->whereRaw('LOWER(TRIM(email)) = ?', [mb_strtolower(trim($invitacion->cliente_email))])
                    ->value('id');
            }

            if (! $userId) {
                $nombre = mb_strtolower(trim($invitacion->nombre));

                $userId = DB::table('users')
                    ->where('role', 'cliente')
                    ->whereRaw('LOWER(TRIM(name)) = ?', [$nombre])
                    ->value('id');
            }

            if ($userId) {
                DB::table('invitaciones')
                    ->where('id', $invitacion->id)
                    ->update(['user_id' => $userId]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invitaciones', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');
        });
    }
};
