<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabla ordenes: agregar campos de cupón.
     *
     * - cupon_id: FK real al cupón (nullable, restrictOnDelete para que no
     *   se pueda borrar un cupón que ya se usó; si quieres permitirlo
     *   cámbialo a nullOnDelete más adelante).
     * - cupon_codigo: snapshot del código al momento de la compra. Se guarda
     *   aparte del cupon_id para que reportes y PDFs sigan legibles aunque
     *   el cupón se renombre después.
     * - descuento_centavos: lo que se restó al subtotal.
     * - subtotal_centavos: paquete_precio_centavos (alias semántico del
     *   campo existente, para que el "antes/después" sea explícito en código).
     * - total_final_centavos: lo que efectivamente se cobró en MP
     *   (= subtotal - descuento; nunca negativo).
     */
    public function up(): void
    {
        Schema::table('ordenes', function (Blueprint $table) {
            $table->foreignId('cupon_id')
                ->nullable()
                ->after('paquete_id')
                ->constrained('cupones')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->string('cupon_codigo', 40)->nullable()->after('cupon_id');
            $table->unsignedInteger('descuento_centavos')->default(0)->after('paquete_precio_centavos');
            $table->unsignedInteger('total_final_centavos')->nullable()->after('descuento_centavos');
        });
    }

    public function down(): void
    {
        Schema::table('ordenes', function (Blueprint $table) {
            $table->dropForeign(['cupon_id']);
            $table->dropColumn(['cupon_id', 'cupon_codigo', 'descuento_centavos', 'total_final_centavos']);
        });
    }
};
