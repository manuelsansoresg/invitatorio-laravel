<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Asocia una Confirmacion con un Invitado (lista con link único).
     *
     * Cuando el invitado abre /c/{token}, la app lo redirige a la
     * invitación pública con ?invitado_token=X. Si confirma desde ahí,
     * la confirmación queda ligada al invitado para que el panel
     * del cliente pueda llevar el control "este invitado de mi
     * lista confirmó N personas".
     *
     * Nullable: una confirmación sin invitado_token sigue funcionando
     * como antes (es el caso del modal de "Confirmar asistencia"
     * abierto por cualquier visitante que no está en la lista).
     */
    public function up(): void
    {
        Schema::table('confirmaciones', function (Blueprint $table) {
            $table->foreignId('invitado_id')
                ->nullable()
                ->after('invitacion_id')
                ->constrained('invitados')
                ->nullOnDelete();

            $table->index('invitado_id');
        });
    }

    public function down(): void
    {
        Schema::table('confirmaciones', function (Blueprint $table) {
            $table->dropForeign(['invitado_id']);
            $table->dropIndex(['invitado_id']);
            $table->dropColumn('invitado_id');
        });
    }
};
