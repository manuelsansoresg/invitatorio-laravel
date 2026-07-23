<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invitacion_galeria', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invitacion_id')->constrained('invitaciones')->cascadeOnDelete();
            $table->string('imagen_path');
            $table->string('titulo')->nullable();
            $table->text('descripcion')->nullable();
            $table->unsignedInteger('orden')->default(0);
            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->index(['invitacion_id', 'orden']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invitacion_galeria');
    }
};
