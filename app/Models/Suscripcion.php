<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

class Suscripcion extends Model
{
    use HasFactory;

    protected $table = 'suscripciones';

    public const MOTIVO_COMPRA = 'compra';
    public const MOTIVO_MANUAL = 'manual';
    public const MOTIVO_REGALO = 'regalo';

    protected $fillable = [
        'user_id',
        'paquete_id',
        'orden_id',
        'motivo',
        'max_invitaciones',
        'invitaciones_usadas',
        'fecha_inicio',
        'fecha_fin',
        'cancelada',
        'notas_admin',
    ];

    protected function casts(): array
    {
        return [
            'max_invitaciones'    => 'integer',
            'invitaciones_usadas' => 'integer',
            'fecha_inicio'        => 'datetime',
            'fecha_fin'           => 'datetime',
            'cancelada'           => 'boolean',
        ];
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function paquete(): BelongsTo
    {
        return $this->belongsTo(Paquete::class);
    }

    public function orden(): BelongsTo
    {
        return $this->belongsTo(Orden::class);
    }

    public function invitaciones(): HasMany
    {
        return $this->hasMany(Invitacion::class);
    }

    // ─────────────── Estado computado ───────────────

    /**
     * Estados posibles:
     *  - activa    → vigente, con cupo y no cancelada.
     *  - agotada   → sin cupo (invitaciones_usadas >= max).
     *  - vencida   → pasó fecha_fin.
     *  - cancelada → el admin la canceló.
     */
    public function getEstadoAttribute(): string
    {
        if ($this->cancelada) {
            return 'cancelada';
        }
        $now = Carbon::now();
        if ($this->fecha_fin && $this->fecha_fin->lt($now)) {
            return 'vencida';
        }
        if ($this->invitaciones_usadas >= $this->max_invitaciones) {
            return 'agotada';
        }
        return 'activa';
    }

    public function getEstadoLegibleAttribute(): string
    {
        return match ($this->estado) {
            'activa'     => 'Activa',
            'agotada'    => 'Agotada (sin cupo)',
            'vencida'    => 'Vencida',
            'cancelada'  => 'Cancelada',
            default      => 'Desconocida',
        };
    }

    public function esActiva(): bool
    {
        return $this->estado === 'activa';
    }

    public function invitacionesDisponibles(): int
    {
        return max(0, (int) $this->max_invitaciones - (int) $this->invitaciones_usadas);
    }

    public function tieneCupoDisponible(): bool
    {
        return $this->invitacionesDisponibles() > 0;
    }

    /**
     * El usuario puede publicar una invitación si la suscripción está
     * activa Y tiene cupo. Es lo que valida el botón "Publicar".
     */
    public function puedePublicar(): bool
    {
        return $this->esActiva() && $this->tieneCupoDisponible();
    }

    public function getCupoUsadoPorcentajeAttribute(): int
    {
        if ($this->max_invitaciones <= 0) {
            return 0;
        }
        return (int) min(100, round($this->invitaciones_usadas / $this->max_invitaciones * 100));
    }

    // ─────────────── Scopes ───────────────

    public function scopeActivas(Builder $q): Builder
    {
        return $q->where('cancelada', false)
            ->where(function (Builder $sub) {
                $sub->whereNull('fecha_fin')->orWhere('fecha_fin', '>=', Carbon::now());
            })
            ->whereColumn('invitaciones_usadas', '<', 'max_invitaciones');
    }

    public function scopeDelUsuario(Builder $q, int $userId): Builder
    {
        return $q->where('user_id', $userId);
    }
}
