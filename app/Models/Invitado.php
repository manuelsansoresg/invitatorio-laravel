<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

/**
 * Invitado en la lista del cliente. Cada uno tiene un token único
 * que se usa en la URL pública: /c/{token}
 *
 * El cliente es dueño de la lista — puede agregar, quitar y editar
 * lugares asignados en cualquier momento. El invitado puede
 * re-confirmar con el mismo link.
 */
class Invitado extends Model
{
    use HasFactory;

    protected $table = 'invitados';

    protected $fillable = [
        'invitacion_id',
        'nombre',
        'telefono',
        'lugares_asignados',
        'lugares_confirmados',
        'token',
        'estado',
        'confirmado_at',
        'ip',
        'user_agent',
    ];

    protected function casts(): array
    {
        return [
            'lugares_asignados'   => 'integer',
            'lugares_confirmados' => 'integer',
            'confirmado_at'       => 'datetime',
        ];
    }

    /**
     * Boot: garantizamos que SIEMPRE haya un token, único, antes de
     * persistir. Si el cliente crea el invitado por admin/panel, el
     * token nace en el modelo. No hay manera de que quede null.
     */
    protected static function booted(): void
    {
        static::creating(function (Invitado $invitado): void {
            if (empty($invitado->token)) {
                $invitado->token = static::generarTokenUnico();
            }
        });
    }

    /**
     * Genera un token único. Loop de hasta 5 intentos para evitar
     * una colisión absurda con Str::random(48) (probabilidad ~ 1/2^288).
     * Si después de 5 intentos no hay token libre, reventamos — algo
     * muy raro estaría pasando.
     */
    public static function generarTokenUnico(): string
    {
        for ($i = 0; $i < 5; $i++) {
            $token = Str::random(48);
            if (! static::where('token', $token)->exists()) {
                return $token;
            }
        }

        throw new \RuntimeException('No fue posible generar un token único para Invitado después de 5 intentos.');
    }

    public function invitacion(): BelongsTo
    {
        return $this->belongsTo(Invitacion::class);
    }

    /**
     * URL pública lista para compartir. Usa el host actual de la app
     * (config('app.url')) — para producción hay que setear APP_URL.
     */
    public function getUrlPublicaAttribute(): string
    {
        return rtrim(config('app.url'), '/') . '/c/' . $this->token;
    }

    /**
     * ¿El invitado ya confirmó? (estado confirmado, sin importar si
     * parcial o total).
     */
    public function getConfirmadoAttribute(): bool
    {
        return $this->estado === 'confirmado';
    }

    /**
     * ¿Confirmó todos los lugares que se le asignaron?
     */
    public function getCompletoAttribute(): bool
    {
        return $this->estado === 'confirmado'
            && $this->lugares_confirmados !== null
            && $this->lugares_confirmados >= $this->lugares_asignados;
    }

    /**
     * ¿Rechazó la invitación?
     */
    public function getRechazadoAttribute(): bool
    {
        return $this->estado === 'no_asistira';
    }

    /**
     * Texto humano del estado para mostrar en panel y vistas.
     */
    public function getEstadoLegibleAttribute(): string
    {
        return match ($this->estado) {
            'pendiente'    => 'Pendiente',
            'confirmado'   => $this->completo
                ? "Confirmado ({$this->lugares_confirmados}/{$this->lugares_asignados})"
                : "Parcial ({$this->lugares_confirmados}/{$this->lugares_asignados})",
            'no_asistira'  => 'No asistirá',
            default        => 'Sin estado',
        };
    }

    /**
     * Color del badge según estado (para Tailwind / clases utility).
     */
    public function getEstadoColorAttribute(): string
    {
        return match ($this->estado) {
            'pendiente'    => 'gray',
            'confirmado'   => $this->completo ? 'green' : 'amber',
            'no_asistira'  => 'red',
            default        => 'gray',
        };
    }

    /**
     * Opciones de lugares que el invitado puede confirmar: 0..asignados.
     * 0 = rechaza amablemente sin cerrar el link.
     */
    public function opcionesLugares(): array
    {
        $max = max(1, (int) $this->lugares_asignados);
        return range(0, $max);
    }
}
