<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Tabla: confirmaciones
     * Almacena los nombres de las personas que confirman asistencia
     * a una invitación concreta. Se vincula a la tabla "invitaciones"
     * por la FK "invitacion_id".
     */
    public function up(): void
    {
        Schema::create('confirmaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invitacion_id')
                ->constrained('invitaciones')
                ->cascadeOnUpdate()
                ->cascadeOnDelete()
                ->comment('FK a la invitación que se confirma');
            $table->string('nombre', 120)->comment('Nombre de quien confirma');
            $table->string('ip', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();

            $table->index('invitacion_id');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('confirmaciones');
    }
};
