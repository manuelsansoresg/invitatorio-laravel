<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

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
        'suscripcion_id',
        'template_id',
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
        'mostrar_contador_confirmados',
        'publicada_at',
        'fecha_caducidad',
    ];

    protected function casts(): array
    {
        return [
            'fecha_evento' => 'date',
            'hora_evento' => 'datetime:H:i',
            'publicada_at' => 'datetime',
            'mostrar_contador_confirmados' => 'boolean',
            'fecha_caducidad' => 'datetime',
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

    public function template(): BelongsTo
    {
        return $this->belongsTo(Template::class);
    }

    public function suscripcion(): BelongsTo
    {
        return $this->belongsTo(Suscripcion::class);
    }

    /**
     * Helpers de estado y caducidad.
     *
     * Estados:
     *  - borrador   → todavía no se publicó.
     *  - publicada  → publicada_at está seteado. La invitación está "viva".
     *  - vencida    → pasó fecha_caducidad (calculada al publicar).
     *  - archivada  → el cliente o el admin la archivó.
     */
    public function estaPublicada(): bool
    {
        return $this->estado === 'publicada' && $this->publicada_at !== null;
    }

    public function esBorrador(): bool
    {
        return $this->estado === 'borrador';
    }

    public function estaVencida(): bool
    {
        if ($this->estado === 'vencida') {
            return true;
        }
        return $this->fecha_caducidad !== null
            && Carbon::now()->gt($this->fecha_caducidad);
    }

    public function diasParaVencer(): ?int
    {
        if (! $this->fecha_caducidad) {
            return null;
        }
        $diff = Carbon::now()->diffInDays($this->fecha_caducidad, false);
        return (int) $diff;
    }

    /**
     * Lista administrada por el cliente (links únicos por invitado).
     * Es independiente del sistema viejo de Confirmacion (popup).
     */
    public function invitados(): HasMany
    {
        return $this->hasMany(Invitado::class);
    }

    /**
     * Total de lugares confirmados (suma lugares_confirmados).
     */
    public function getLugaresConfirmadosAttribute(): int
    {
        return (int) $this->invitados()
            ->where('estado', 'confirmado')
            ->sum('lugares_confirmados');
    }

    /**
     * Total de lugares asignados (suma lugares_asignados).
     */
    public function getLugaresAsignadosTotalAttribute(): int
    {
        return (int) $this->invitados()->sum('lugares_asignados');
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
