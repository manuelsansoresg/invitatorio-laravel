<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabla: ordenes
     *
     * Una orden = un intento de compra de un paquete. Se crea en cuanto
     * el usuario confirma el form de checkout y antes de mandarlo a MP.
     * El estado se actualiza vía webhook cuando MP notifica el pago.
     *
     * Guardamos campos "snapshot" del paquete (nombre, precio_centavos)
     * para que la orden siga siendo legible aunque el catálogo cambie.
     */
    public function up(): void
    {
        Schema::create('ordenes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paquete_id')
                ->constrained('paquetes')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            // Snapshot del paquete al momento de la compra
            $table->string('paquete_nombre', 80);
            $table->unsignedInteger('paquete_precio_centavos');

            // Datos del comprador (lo que llenó en el form de checkout)
            $table->string('comprador_nombre', 120);
            $table->string('comprador_email', 160);
            $table->string('comprador_telefono', 40)->nullable();
            $table->string('tipo_evento', 60)->nullable()
                ->comment('Contexto opcional: boda, xv, bautizo, etc.');

            // Estado del pago (alineado con los status de MP)
            $table->enum('estado', [
                'pending',    // preference creada, aún no paga
                'approved',   // pago aprobado
                'authorized', // autorizado pero todavía no capturado
                'in_process', // MP está revisando
                'rejected',   // rechazado
                'cancelled',  // cancelado por el usuario
                'refunded',   // devuelto
            ])->default('pending');

            // IDs de Mercado Pago
            $table->string('mp_preference_id', 80)->nullable();
            $table->string('mp_payment_id', 80)->nullable()->index();
            $table->string('mp_payment_type', 40)->nullable()
                ->comment('credit_card, debit_card, ticket, bank_transfer, etc.');
            $table->string('mp_status', 40)->nullable();
            $table->string('mp_status_detail', 80)->nullable();

            // Metadata útil
            $table->ipAddress('ip')->nullable();
            $table->string('user_agent', 255)->nullable();

            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            $table->index(['estado', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ordenes');
    }
};
