<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * La invitación pública puede mostrar "X confirmados de Y lugares"
     * como contador social. Es opt-in por invitación porque a algunos
     * organizadores no les gusta presionarlo al invitado.
     */
    public function up(): void
    {
        Schema::table('invitaciones', function (Blueprint $table) {
            $table->boolean('mostrar_contador_confirmados')
                ->default(false)
                ->after('estado');
        });
    }

    public function down(): void
    {
        Schema::table('invitaciones', function (Blueprint $table) {
            $table->dropColumn('mostrar_contador_confirmados');
        });
    }
};
