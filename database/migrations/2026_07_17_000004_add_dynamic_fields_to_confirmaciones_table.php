<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('confirmaciones', function (Blueprint $table) {
            if (! Schema::hasColumn('confirmaciones', 'telefono')) {
                $table->string('telefono')->nullable()->after('nombre');
            }
            if (! Schema::hasColumn('confirmaciones', 'mensaje')) {
                $table->text('mensaje')->nullable()->after('telefono');
            }
            if (! Schema::hasColumn('confirmaciones', 'numero_invitados')) {
                $table->unsignedInteger('numero_invitados')->nullable()->after('mensaje');
            }
            if (! Schema::hasColumn('confirmaciones', 'asistira')) {
                $table->boolean('asistira')->nullable()->after('numero_invitados');
            }
        });
    }

    public function down(): void
    {
        Schema::table('confirmaciones', function (Blueprint $table) {
            foreach (['asistira', 'numero_invitados', 'mensaje', 'telefono'] as $column) {
                if (Schema::hasColumn('confirmaciones', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
