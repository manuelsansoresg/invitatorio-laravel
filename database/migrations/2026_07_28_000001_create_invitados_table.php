<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Lista administrada por el cliente: cada invitado tiene un token único
     * que el cliente comparte por WhatsApp/Facebook. El invitado abre el
     * link, ve su nombre y confirma cuántos van de los lugares asignados.
     *
     * Pensada para ser LIBRE (sin tope por paquete) — la columna
     * `lugares_asignados` la controla el cliente. El paquete solo define
     * el precio del evento, no limita la lista.
     */
    public function up(): void
    {
        Schema::create('invitados', function (Blueprint $table) {
            $table->id();

            $table->foreignId('invitacion_id')
                ->constrained('invitaciones')
                ->cascadeOnDelete();

            $table->string('nombre', 120);
            $table->string('telefono', 30)->nullable(); // opcional, para WhatsApp
            $table->unsignedTinyInteger('lugares_asignados')->default(1);
            $table->unsignedTinyInteger('lugares_confirmados')->nullable();

            /**
             * Token único público. Se genera en el modelo con Str::random(48).
             * NO es un id numérico — no se puede enumerar.
             * La URL queda como: /c/{token}
             */
            $table->string('token', 64)->unique();

            /**
             * pendiente        → aún no ha abierto el link o no ha confirmado
             * confirmado       → confirmó cuántos van (parcial o total)
             * no_asistira      → rechazó la invitación
             */
            $table->enum('estado', ['pendiente', 'confirmado', 'no_asistira'])
                ->default('pendiente');

            $table->timestamp('confirmado_at')->nullable();
            $table->string('ip', 45)->nullable();           // IPv6-safe
            $table->string('user_agent', 255)->nullable();

            $table->timestamps();

            $table->index('invitacion_id');
            $table->index('estado');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invitados');
    }
};
