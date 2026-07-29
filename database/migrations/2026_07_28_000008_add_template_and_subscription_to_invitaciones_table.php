<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Invitaciones: agregar template_id, suscripcion_id, fecha_caducidad.
     *
     * - template_id: FK al template base. Se setea al crear la invitación.
     *   Si se borra el template (no debería pasar), restrictOnDelete
     *   lo protege.
     * - suscripcion_id: FK a la suscripción que "paga" esta invitación.
     *   Null para invitaciones legacy (creadas antes de este cambio)
     *   o las que el admin crea manualmente sin paquete.
     * - fecha_caducidad: se calcula al PUBLICAR como
     *   publicada_at + paquete.dias_caducidad. Null mientras es borrador.
     *
     * También aprovechamos para ampliar el enum de estado (borrador
     * y publicada ya existen; agregamos 'vencida' y 'archivada').
     */
    public function up(): void
    {
        Schema::table('invitaciones', function (Blueprint $table) {
            $table->foreignId('template_id')
                ->nullable()
                ->after('template_key')
                ->constrained('templates')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreignId('suscripcion_id')
                ->nullable()
                ->after('user_id')
                ->constrained('suscripciones')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->timestamp('fecha_caducidad')
                ->nullable()
                ->after('publicada_at')
                ->comment('Se calcula al publicar: publicada_at + paquete.dias_caducidad.');

            $table->index('fecha_caducidad');
        });

        // Ampliamos el enum de estado para incluir 'vencida' y 'archivada'.
        // MySQL permite modificar el enum in-place; para SQLite no es
        // necesario porque el tipo es texto. Usamos SQL crudo para
        // mantenerlo portable.
        \DB::statement("ALTER TABLE invitaciones MODIFY COLUMN estado ENUM('borrador','publicada','vencida','archivada') NOT NULL DEFAULT 'borrador'");
    }

    public function down(): void
    {
        \DB::statement("ALTER TABLE invitaciones MODIFY COLUMN estado ENUM('borrador','publicada') NOT NULL DEFAULT 'borrador'");

        Schema::table('invitaciones', function (Blueprint $table) {
            $table->dropForeign(['template_id']);
            $table->dropForeign(['suscripcion_id']);
            $table->dropIndex(['fecha_caducidad']);
            $table->dropColumn(['template_id', 'suscripcion_id', 'fecha_caducidad']);
        });
    }
};
