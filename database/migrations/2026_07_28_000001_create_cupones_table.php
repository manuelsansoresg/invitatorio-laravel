<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabla: cupones
     *
     * Cupones de descuento aplicables a uno o varios paquetes del catálogo.
     * - tipo: 'precio' (descuento fijo en centavos) o 'porcentaje' (0-100).
     * - Si la tabla pivot cupon_paquete está vacía para un cupón,
     *   el cupón aplica a TODOS los paquetes activos (regla "comodín").
     * - usos_max = null significa ilimitado.
     * - max_usos_total vs max_usos_por_usuario: por ahora solo llevamos
     *   el counter global; si en el futuro se quiere por email, lo agregamos.
     */
    public function up(): void
    {
        Schema::create('cupones', function (Blueprint $table) {
            $table->id();

            // Código que el cliente ve / pega en la URL. Lo guardamos en
            // mayúsculas para que "verano20" y "VERANO20" sean iguales.
            $table->string('codigo', 40)->unique();
            $table->string('descripcion', 255)->nullable()
                ->comment('Nota interna para el admin, no se muestra al cliente.');

            $table->enum('tipo', ['precio', 'porcentaje'])
                ->comment('precio = descuenta valor_centavos; porcentaje = descuenta valor%.');

            // Si tipo=precio: centavos a restar (ej. 20000 = $200 MXN).
            // Si tipo=porcentaje: 1-100.
            $table->unsignedInteger('valor');

            // Suma mínima de subtotal (centavos) para que aplique.
            // 0 o null = sin mínimo.
            $table->unsignedInteger('minimo_compra_centavos')->default(0);

            // Vigencia. Ambas opcionales: null = sin límite.
            $table->timestamp('fecha_inicio')->nullable();
            $table->timestamp('fecha_fin')->nullable();

            // Límite de usos globales. null = ilimitado.
            $table->unsignedInteger('max_usos')->nullable();
            $table->unsignedInteger('usos_actuales')->default(0);

            $table->boolean('activo')->default(true);

            $table->timestamps();

            $table->index(['activo', 'fecha_inicio', 'fecha_fin']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cupones');
    }
};
