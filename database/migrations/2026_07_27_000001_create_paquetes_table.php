<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabla: paquetes
     *
     * Catálogo de paquetes de invitación que se ofrecen en la landing.
     * Antes vivían hardcodeados en welcome.blade.php; ahora están en DB
     * para poder leerlos desde el checkout, asignarles un id estable
     * que se manda a Mercado Pago y mantener histórico cuando cambien
     * de precio.
     */
    public function up(): void
    {
        Schema::create('paquetes', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 80)->unique()->comment('Identificador URL-safe: web-esencial, web-plus, etc.');
            $table->enum('formato', ['web', 'imagen', 'video'])
                ->comment('Categoría del paquete, usada para el tab de la landing.');
            $table->string('nombre', 80);
            $table->string('descripcion', 255);
            // Guardamos el precio en centavos (entero) para evitar floats
            // en dinero. 60_000 = $600.00 MXN, 130_000 = $1,300.00 MXN.
            $table->unsignedInteger('precio_centavos');
            $table->string('badge', 40)->nullable()->comment('Etiqueta: "Más elegida", "Básica", etc.');
            $table->boolean('destacado')->default(false)->comment('Si va resaltado en la card de pricing.');
            $table->json('items')->comment('Lista de beneficios incluidos en el paquete.');
            $table->boolean('activo')->default(true);
            $table->unsignedInteger('orden')->default(0)->comment('Para ordenar dentro de cada formato.');
            $table->timestamps();

            $table->index(['formato', 'activo', 'orden']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paquetes');
    }
};
