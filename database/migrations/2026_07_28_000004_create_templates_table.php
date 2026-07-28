<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabla: templates
     *
     * Catálogo de "templates" o diseños base que el admin puede ofrecer a
     * los clientes. Reemplaza al campo libre `invitaciones.template_key`
     * (que era un string sin estructura) por una entidad de primera clase.
     *
     * Notas de diseño:
     *  - Un template es un "diseño base", nunca se borra. La columna
     *    `activo` lo esconde del catálogo sin perder histórico.
     *  - `formato` = web | imagen | video | general. La mayoría será
     *    "web" (los digitales), pero dejamos el campo abierto.
     *  - `config_json` guarda defaults del template (paleta, secciones,
     *    copy) que se copian al crear una invitación a partir de él.
     */
    public function up(): void
    {
        Schema::create('templates', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 80)->unique();
            $table->enum('formato', ['web', 'imagen', 'video', 'general'])
                ->default('web')
                ->comment('web = autoeditable con InvitationEditor; imagen/video = formulario simple + admin produce.');
            $table->string('nombre', 80);
            $table->string('descripcion', 255)->nullable();
            $table->string('imagen_preview_path', 255)->nullable();
            $table->json('config_json')->nullable()
                ->comment('Defaults del template (paleta, copy, secciones) que se copian al crear la invitación.');
            $table->boolean('activo')->default(true);
            $table->unsignedInteger('orden')->default(0);
            $table->timestamps();

            $table->index(['activo', 'formato', 'orden']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('templates');
    }
};
