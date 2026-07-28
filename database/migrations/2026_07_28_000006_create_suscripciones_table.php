<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabla: suscripciones
     *
     * Una suscripción es el "derecho" que tiene un usuario para crear
     * y publicar N invitaciones durante un período, según el paquete
     * que compró.
     *
     * Origen:
     *  - motivo = 'compra' → creada automáticamente al aprobarse el
     *    pago de una orden (vía webhook de MP).
     *  - motivo = 'manual' → creada por el admin (cortesía, prueba,
     *    premio) sin orden asociada.
     *
     * Cupo:
     *  - max_invitaciones: cuántas invitaciones puede PUBLICAR con
     *    esta suscripción. Los borradores no gastan cupo.
     *  - invitaciones_usadas: cuántas invitaciones publicó con esta
     *    suscripción (cuenta invitaciones, no borradores).
     *
     * Vigencia:
     *  - fecha_inicio: cuándo se activó.
     *  - fecha_fin: hasta cuándo está vigente (null = sin límite).
     *
     * Estado (computado al vuelo en el modelo, no se guarda):
     *  - activa   → vigente y con cupo.
     *  - agotada  → sin cupo.
     *  - vencida  → pasó fecha_fin.
     *  - cancelada→ el admin la canceló.
     */
    public function up(): void
    {
        Schema::create('suscripciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->foreignId('paquete_id')
                ->constrained('paquetes')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->foreignId('orden_id')
                ->nullable()
                ->constrained('ordenes')
                ->cascadeOnUpdate()
                ->nullOnDelete()
                ->comment('Orden que pagó esta suscripción. Null si fue manual.');

            $table->enum('motivo', ['compra', 'manual', 'regalo'])->default('compra');
            $table->unsignedInteger('max_invitaciones');
            $table->unsignedInteger('invitaciones_usadas')->default(0);

            $table->timestamp('fecha_inicio')->useCurrent();
            $table->timestamp('fecha_fin')->nullable();
            $table->boolean('cancelada')->default(false);
            $table->string('notas_admin', 500)->nullable();

            $table->timestamps();

            $table->index(['user_id', 'cancelada']);
            $table->index(['fecha_fin', 'cancelada']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('suscripciones');
    }
};
