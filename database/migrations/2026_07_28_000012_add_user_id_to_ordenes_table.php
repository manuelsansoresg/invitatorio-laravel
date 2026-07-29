<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabla: ordenes
     *
     * Agrega el FK user_id para vincular la orden directamente con el
     * usuario que la creó. Esto reemplaza el matching por email que
     * teníamos antes (frágil: case-sensitive, se rompe si cambia el
     * email, no permite que un user compre para otro email, etc).
     *
     * El campo `comprador_email` se conserva tal cual: es la "snapshot"
     * al momento de la compra (para recibos, reportes, contacto).
     * Si en el futuro cambia el email del user, la orden sigue mostrando
     * el email con el que se pagó.
     *
     * Backfill: para las órdenes ya existentes (pagadas o pending)
     * intentamos vincularlas con el user cuyo email coincide.
     * Usamos LOWER() para ser case-insensitive aunque MySQL con
     * utf8mb4_unicode_ci ya lo sea por default, por las dudas.
     */
    public function up(): void
    {
        Schema::table('ordenes', function (Blueprint $table) {
            $table->foreignId('user_id')
                ->nullable()
                ->after('paquete_id')
                ->constrained('users')
                ->nullOnDelete();

            $table->index('user_id');
        });

        // Backfill: vincular órdenes existentes con su user por email.
        // Solo si el user existe Y la orden no tiene user_id ya.
        DB::statement('
            UPDATE ordenes o
            INNER JOIN users u ON LOWER(u.email) = LOWER(o.comprador_email)
            SET o.user_id = u.id
            WHERE o.user_id IS NULL
        ');
    }

    public function down(): void
    {
        Schema::table('ordenes', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropIndex(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};
