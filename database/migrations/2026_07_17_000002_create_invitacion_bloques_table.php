<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invitacion_bloques', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invitacion_id')->constrained('invitaciones')->cascadeOnDelete();
            $table->string('tipo');
            $table->string('titulo')->nullable();
            $table->longText('contenido')->nullable();
            $table->json('config_json')->nullable();
            $table->unsignedInteger('orden')->default(0);
            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->index(['invitacion_id', 'tipo']);
            $table->index(['invitacion_id', 'orden']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invitacion_bloques');
    }
};
