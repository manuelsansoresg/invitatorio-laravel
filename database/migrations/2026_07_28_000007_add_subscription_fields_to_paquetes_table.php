<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Paquetes: agregar campos de suscripción.
     *
     * - max_invitaciones: cuántas invitaciones puede PUBLICAR el usuario
     *   con este paquete. Default 1 (cada paquete = 1 invitación).
     * - dias_caducidad: cuántos días vive cada invitación DESDE que
     *   se publica. Default 365 (un año).
     */
    public function up(): void
    {
        Schema::table('paquetes', function (Blueprint $table) {
            $table->unsignedInteger('max_invitaciones')
                ->default(1)
                ->after('precio_centavos')
                ->comment('Cupo de invitaciones publicables con este paquete.');
            $table->unsignedInteger('dias_caducidad')
                ->default(365)
                ->after('max_invitaciones')
                ->comment('Días de vida de cada invitación desde su publicación.');
        });
    }

    public function down(): void
    {
        Schema::table('paquetes', function (Blueprint $table) {
            $table->dropColumn(['max_invitaciones', 'dias_caducidad']);
        });
    }
};
