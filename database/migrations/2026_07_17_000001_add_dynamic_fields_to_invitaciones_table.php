<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('invitaciones', function (Blueprint $table) {
            if (! Schema::hasColumn('invitaciones', 'tipo_evento')) {
                $table->string('tipo_evento')->nullable()->after('cliente_email');
            }
            if (! Schema::hasColumn('invitaciones', 'titulo')) {
                $table->string('titulo')->nullable()->after('tipo_evento');
            }
            if (! Schema::hasColumn('invitaciones', 'subtitulo')) {
                $table->string('subtitulo')->nullable()->after('titulo');
            }
            if (! Schema::hasColumn('invitaciones', 'fecha_evento')) {
                $table->date('fecha_evento')->nullable()->after('subtitulo');
            }
            if (! Schema::hasColumn('invitaciones', 'hora_evento')) {
                $table->time('hora_evento')->nullable()->after('fecha_evento');
            }
            if (! Schema::hasColumn('invitaciones', 'lugar_nombre')) {
                $table->string('lugar_nombre')->nullable()->after('hora_evento');
            }
            if (! Schema::hasColumn('invitaciones', 'lugar_direccion')) {
                $table->text('lugar_direccion')->nullable()->after('lugar_nombre');
            }
            if (! Schema::hasColumn('invitaciones', 'maps_url')) {
                $table->text('maps_url')->nullable()->after('lugar_direccion');
            }
            if (! Schema::hasColumn('invitaciones', 'dress_code')) {
                $table->string('dress_code')->nullable()->after('maps_url');
            }
            if (! Schema::hasColumn('invitaciones', 'dress_code_descripcion')) {
                $table->text('dress_code_descripcion')->nullable()->after('dress_code');
            }
            if (! Schema::hasColumn('invitaciones', 'mensaje_principal')) {
                $table->text('mensaje_principal')->nullable()->after('dress_code_descripcion');
            }
            if (! Schema::hasColumn('invitaciones', 'mensaje_footer')) {
                $table->text('mensaje_footer')->nullable()->after('mensaje_principal');
            }
            if (! Schema::hasColumn('invitaciones', 'whatsapp_numero')) {
                $table->string('whatsapp_numero')->nullable()->after('mensaje_footer');
            }
            if (! Schema::hasColumn('invitaciones', 'whatsapp_mensaje')) {
                $table->text('whatsapp_mensaje')->nullable()->after('whatsapp_numero');
            }
            if (! Schema::hasColumn('invitaciones', 'musica_path')) {
                $table->string('musica_path')->nullable()->after('whatsapp_mensaje');
            }
            if (! Schema::hasColumn('invitaciones', 'musica_titulo')) {
                $table->string('musica_titulo')->nullable()->after('musica_path');
            }
            if (! Schema::hasColumn('invitaciones', 'imagen_portada_path')) {
                $table->string('imagen_portada_path')->nullable()->after('musica_titulo');
            }
            if (! Schema::hasColumn('invitaciones', 'archivo_final_path')) {
                $table->string('archivo_final_path')->nullable()->after('imagen_portada_path');
            }
            if (! Schema::hasColumn('invitaciones', 'color_primario')) {
                $table->string('color_primario', 20)->nullable()->default('#5A3087')->after('archivo_final_path');
            }
            if (! Schema::hasColumn('invitaciones', 'color_secundario')) {
                $table->string('color_secundario', 20)->nullable()->default('#F4EFF8')->after('color_primario');
            }
            if (! Schema::hasColumn('invitaciones', 'color_acento')) {
                $table->string('color_acento', 20)->nullable()->default('#C9A05A')->after('color_secundario');
            }
            if (! Schema::hasColumn('invitaciones', 'template_key')) {
                $table->string('template_key')->nullable()->after('color_acento');
            }
            if (! Schema::hasColumn('invitaciones', 'estado')) {
                $table->string('estado')->default('borrador')->after('template_key');
            }
            if (! Schema::hasColumn('invitaciones', 'publicada_at')) {
                $table->timestamp('publicada_at')->nullable()->after('estado');
            }
        });
    }

    public function down(): void
    {
        Schema::table('invitaciones', function (Blueprint $table) {
            foreach ([
                'publicada_at',
                'estado',
                'template_key',
                'color_acento',
                'color_secundario',
                'color_primario',
                'archivo_final_path',
                'imagen_portada_path',
                'musica_titulo',
                'musica_path',
                'whatsapp_mensaje',
                'whatsapp_numero',
                'mensaje_footer',
                'mensaje_principal',
                'dress_code_descripcion',
                'dress_code',
                'maps_url',
                'lugar_direccion',
                'lugar_nombre',
                'hora_evento',
                'fecha_evento',
                'subtitulo',
                'titulo',
                'tipo_evento',
            ] as $column) {
                if (Schema::hasColumn('invitaciones', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
