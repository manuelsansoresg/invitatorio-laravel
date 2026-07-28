<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Switch por paquete: define si el cliente puede dar de alta
     * invitados con links únicos de confirmación.
     *
     * Default false para que los paquetes viejos NO hereden la
     * funcionalidad automáticamente — quien la quiera la activa
     * explícitamente desde el admin de paquetes.
     */
    public function up(): void
    {
        Schema::table('paquetes', function (Blueprint $table) {
            $table->boolean('permite_gestionar_invitados')
                ->default(false)
                ->after('items');
        });
    }

    public function down(): void
    {
        Schema::table('paquetes', function (Blueprint $table) {
            $table->dropColumn('permite_gestionar_invitados');
        });
    }
};
