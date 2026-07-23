<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invitacion extends Model
{
    use HasFactory;

    /**
     * Nombre real de la tabla (Laravel habría generado "invitacions"
     * por la pluralización inglesa por defecto; usamos la forma en
     * español que sí existe en la migración).
     */
    protected $table = 'invitaciones';

    protected $fillable = [
        'nombre',
        'apellido_paterno',
        'apellido_materno',
        'ruta',
        'user_id',
        'cliente_email',
        'tipo_evento',
        'titulo',
        'subtitulo',
        'fecha_evento',
        'hora_evento',
        'lugar_nombre',
        'lugar_direccion',
        'maps_url',
        'dress_code',
        'dress_code_descripcion',
        'mensaje_principal',
        'mensaje_footer',
        'whatsapp_numero',
        'whatsapp_mensaje',
        'musica_path',
        'musica_titulo',
        'imagen_portada_path',
        'archivo_final_path',
        'color_primario',
        'color_secundario',
        'color_acento',
        'template_key',
        'estado',
        'publicada_at',
    ];

    protected function casts(): array
    {
        return [
            'fecha_evento' => 'date',
            'hora_evento' => 'datetime:H:i',
            'publicada_at' => 'datetime',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'ruta';
    }

    public function setClienteEmailAttribute(?string $value): void
    {
        $this->attributes['cliente_email'] = filled($value)
            ? mb_strtolower(trim($value))
            : null;
    }

    /**
     * Una invitación tiene muchas confirmaciones de asistencia.
     */
    public function confirmaciones(): HasMany
    {
        return $this->hasMany(Confirmacion::class);
    }

    public function confirmations(): HasMany
    {
        return $this->confirmaciones();
    }

    public function blocks(): HasMany
    {
        return $this->hasMany(InvitationBlock::class)->orderBy('orden');
    }

    public function activeBlocks(): HasMany
    {
        return $this->blocks()->where('activo', true);
    }

    public function gallery(): HasMany
    {
        return $this->hasMany(InvitationGallery::class)->orderBy('orden');
    }

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Nombre completo de la XVañera.
     */
    public function getNombreCompletoAttribute(): string
    {
        return trim(implode(' ', array_filter([
            $this->nombre,
            $this->apellido_paterno,
            $this->apellido_materno,
        ])));
    }
}
