<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabla: user_templates
     *
     * Pivot usuario ↔ template. Define QUÉ templates puede ver y usar
     * un cliente concreto. Si un template no tiene fila para un usuario,
     * ese usuario NO lo ve (ni en el catálogo, ni al crear invitación).
     *
     * El campo `activo` permite "bloquear" sin perder la asignación:
     * el admin puede desactivar temporalmente un template para un
     * usuario (ej. problema de calidad) sin tener que reasignarlo después.
     */
    public function up(): void
    {
        Schema::create('user_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignId('template_id')
                ->constrained('templates')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->boolean('activo')->default(true);
            $table->timestamp('asignado_en')->useCurrent();
            $table->timestamps();

            $table->unique(['user_id', 'template_id']);
            $table->index(['user_id', 'activo']);
            $table->index('template_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_templates');
    }
};
