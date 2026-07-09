<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Tabla: invitaciones
     * Representa la invitación digital de cada XVañera. La columna
     * "ruta" es el slug que se usa en la URL (p. ej. "xv-valentina"),
     * y permite resolver la invitación desde
     * Route::get('/invitacion/{ruta}', ...).
     */
    public function up(): void
    {
        Schema::create('invitaciones', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 80);
            $table->string('apellido_paterno', 80);
            $table->string('apellido_materno', 80)->nullable();
            $table->string('ruta', 120)->unique()->comment('Slug único de la invitación (xv-valentina, etc.)');
            $table->timestamps();

            $table->index('ruta');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invitaciones');
    }
};
