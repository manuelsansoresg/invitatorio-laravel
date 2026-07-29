<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabla pivot: cupon_paquete
     *
     * Relación muchos-a-muchos entre cupones y paquetes.
     * Si un cupón no tiene filas acá, aplica a TODOS los paquetes activos.
     * Si tiene filas, solo aplica a los paquetes listados.
     */
    public function up(): void
    {
        Schema::create('cupon_paquete', function (Blueprint $table) {
            $table->id();

            $table->foreignId('cupon_id')
                ->constrained('cupones')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignId('paquete_id')
                ->constrained('paquetes')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->timestamps();

            $table->unique(['cupon_id', 'paquete_id']);
            $table->index('paquete_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cupon_paquete');
    }
};
