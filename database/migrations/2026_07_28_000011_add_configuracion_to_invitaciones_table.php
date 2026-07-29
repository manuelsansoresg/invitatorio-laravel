<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Clasificación de invitaciones para el catálogo de templates y la
     * visibilidad pública.
     *
     * - disponible (boolean, default true): define si la invitación
     *   puede verse públicamente en /invitacion/{ruta}. Si está en
     *   false, la ruta devuelve 404.
     *
     * - es_template (boolean, default false): marca la invitación
     *   como template reusable. Cuando es_template=true Y
     *   disponible=true, la invitación aparece en el catálogo de
     *   templates que se asignan a usuarios nuevos.
     *
     * Combinación:
     *   es_template=false, disponible=true  → invitación normal del cliente
     *   es_template=false, disponible=false → oculta, no se ve
     *   es_template=true,  disponible=true  → template visible en catálogo
     *   es_template=true,  disponible=false → template oculto del catálogo
     */
    public function up(): void
    {
        Schema::table('invitaciones', function (Blueprint $table) {
            $table->boolean('disponible')->default(true)->after('estado');
            $table->boolean('es_template')->default(false)->after('disponible');

            $table->index('es_template');
            $table->index('disponible');
        });
    }

    public function down(): void
    {
        Schema::table('invitaciones', function (Blueprint $table) {
            $table->dropIndex(['es_template']);
            $table->dropIndex(['disponible']);
            $table->dropColumn(['es_template', 'disponible']);
        });
    }
};
