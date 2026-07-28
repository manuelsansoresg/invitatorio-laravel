<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * Cupón de descuento.
 *
 * Reglas de aplicación (todas se cumplen para que el cupón sea válido):
 *   1. activo = true
 *   2. fecha_inicio <= ahora (si está definida)
 *   3. fecha_fin >= ahora (si está definida)
 *   4. usos_actuales < max_usos (si max_usos está definido)
 *   5. el paquete del checkout está dentro de los permitidos
 *      (o el cupón no tiene paquetes asignados = aplica a todos)
 *   6. subtotal del paquete >= minimo_compra_centavos
 */
class Cupon extends Model
{
    use HasFactory;

    protected $table = 'cupones';

    public const TIPO_PRECIO     = 'precio';
    public const TIPO_PORCENTAJE = 'porcentaje';

    protected $fillable = [
        'codigo',
        'descripcion',
        'tipo',
        'valor',
        'minimo_compra_centavos',
        'fecha_inicio',
        'fecha_fin',
        'max_usos',
        'usos_actuales',
        'activo',
    ];

    protected function casts(): array
    {
        return [
            'valor'                  => 'integer',
            'minimo_compra_centavos' => 'integer',
            'max_usos'               => 'integer',
            'usos_actuales'          => 'integer',
            'activo'                 => 'boolean',
            'fecha_inicio'           => 'datetime',
            'fecha_fin'              => 'datetime',
        ];
    }

    /**
     * Boot: el código se guarda SIEMPRE en mayúsculas, sin espacios,
     * para que "verano 20" y "VERANO 20" se normalicen a "VERANO20".
     */
    protected static function booted(): void
    {
        static::saving(function (self $cupon) {
            if (filled($cupon->codigo)) {
                $cupon->codigo = strtoupper(preg_replace('/\s+/', '', $cupon->codigo));
            }
        });
    }

    // ─────────────── Relaciones ───────────────

    public function paquetes(): BelongsToMany
    {
        return $this->belongsToMany(Paquete::class, 'cupon_paquete')
            ->withTimestamps();
    }

    public function ordenes(): HasMany
    {
        return $this->hasMany(Orden::class);
    }

    // ─────────────── Scopes ───────────────

    public function scopeActivos(Builder $q): Builder
    {
        return $q->where('activo', true);
    }

    public function scopeVigentes(Builder $q): Builder
    {
        $now = Carbon::now();
        return $q
            ->where(function (Builder $q) use ($now) {
                $q->whereNull('fecha_inicio')->orWhere('fecha_inicio', '<=', $now);
            })
            ->where(function (Builder $q) use ($now) {
                $q->whereNull('fecha_fin')->orWhere('fecha_fin', '>=', $now);
            });
    }

    public function scopeConUsosDisponibles(Builder $q): Builder
    {
        return $q->where(function (Builder $q) {
            $q->whereNull('max_usos')->orWhereColumn('usos_actuales', '<', 'max_usos');
        });
    }

    /**
     * Busca un cupón por su código normalizado (mayúsculas, sin espacios).
     */
    public function scopePorCodigo(Builder $q, string $codigo): Builder
    {
        $codigo = strtoupper(preg_replace('/\s+/', '', $codigo));
        return $q->where('codigo', $codigo);
    }

    // ─────────────── Helpers de negocio ───────────────

    /**
     * ¿El cupón está vigente AHORA? (activo + fechas + usos).
     */
    public function estaVigente(): bool
    {
        if (! $this->activo) {
            return false;
        }
        $now = Carbon::now();
        if ($this->fecha_inicio && $this->fecha_inicio->gt($now)) {
            return false;
        }
        if ($this->fecha_fin && $this->fecha_fin->lt($now)) {
            return false;
        }
        if ($this->max_usos !== null && $this->usos_actuales >= $this->max_usos) {
            return false;
        }
        return true;
    }

    /**
     * ¿Aplica a este paquete en particular?
     *  - Si no tiene paquetes asignados → aplica a todos.
     *  - Si tiene → solo a los listados.
     */
    public function aplicaAPaquete(Paquete $paquete): bool
    {
        $asignados = $this->paquetes()->pluck('paquetes.id');
        if ($asignados->isEmpty()) {
            return true;
        }
        return $asignados->contains($paquete->id);
    }

    /**
     * Calcula el descuento en centavos para un subtotal dado.
     *  - tipo=precio: descuenta valor (sin pasar de subtotal, no negativo).
     *  - tipo=porcentaje: descuenta valor% del subtotal.
     * No verifica vigencia ni asociación con paquete — eso lo hace
     * quien llama (aplicarACheckout) antes de invocar este método.
     *
     * @return int centavos a descontar (>= 0)
     */
    public function calcularDescuentoCentavos(int $subtotalCentavos): int
    {
        if ($subtotalCentavos <= 0) {
            return 0;
        }

        $descuento = match ($this->tipo) {
            self::TIPO_PRECIO     => (int) $this->valor,
            self::TIPO_PORCENTAJE => (int) floor($subtotalCentavos * ((int) $this->valor) / 100),
            default               => 0,
        };

        // Nunca dejamos descuento negativo ni mayor al subtotal.
        return max(0, min($descuento, $subtotalCentavos));
    }

    /**
     * Texto humano del descuento, para mostrar en la UI.
     * Ej: "$200 MXN" o "20%".
     */
    public function getDescuentoLegibleAttribute(): string
    {
        return match ($this->tipo) {
            self::TIPO_PORCENTAJE => rtrim(rtrim(number_format($this->valor, 2, '.', ''), '0'), '.') . '%',
            self::TIPO_PRECIO     => '$' . number_format($this->valor / 100, 0, '.', ','),
            default               => (string) $this->valor,
        };
    }

    /**
     * Texto humano del estado de vigencia, para listas del admin.
     */
    public function getEstadoLegibleAttribute(): string
    {
        if (! $this->activo) {
            return 'Inactivo';
        }
        if ($this->max_usos !== null && $this->usos_actuales >= $this->max_usos) {
            return 'Agotado';
        }
        $now = Carbon::now();
        if ($this->fecha_inicio && $this->fecha_inicio->gt($now)) {
            return 'Programado';
        }
        if ($this->fecha_fin && $this->fecha_fin->lt($now)) {
            return 'Vencido';
        }
        return 'Vigente';
    }

    /**
     * Resuelve un cupón por código y verifica si puede aplicarse a un
     * paquete. Pensado para usar en el checkout: una sola llamada
     * devuelve el cupón (o null) y el motivo si no aplicó.
     *
     * @return array{ok: bool, cupon: ?self, descuento_centavos: int, mensaje: ?string}
     */
    public static function resolverParaCheckout(?string $codigo, Paquete $paquete): array
    {
        if (! filled($codigo)) {
            return ['ok' => false, 'cupon' => null, 'descuento_centavos' => 0, 'mensaje' => null];
        }

        $cupon = self::porCodigo((string) $codigo)->first();
        if (! $cupon) {
            return ['ok' => false, 'cupon' => null, 'descuento_centavos' => 0, 'mensaje' => 'El cupón no existe.'];
        }
        if (! $cupon->activo) {
            return ['ok' => false, 'cupon' => $cupon, 'descuento_centavos' => 0, 'mensaje' => 'Este cupón está inactivo.'];
        }
        if ($cupon->max_usos !== null && $cupon->usos_actuales >= $cupon->max_usos) {
            return ['ok' => false, 'cupon' => $cupon, 'descuento_centavos' => 0, 'mensaje' => 'Este cupón ya se agotó.'];
        }
        $now = Carbon::now();
        if ($cupon->fecha_inicio && $cupon->fecha_inicio->gt($now)) {
            return ['ok' => false, 'cupon' => $cupon, 'descuento_centavos' => 0, 'mensaje' => 'Este cupón aún no empieza a aplicar.'];
        }
        if ($cupon->fecha_fin && $cupon->fecha_fin->lt($now)) {
            return ['ok' => false, 'cupon' => $cupon, 'descuento_centavos' => 0, 'mensaje' => 'Este cupón ya venció.'];
        }
        if (! $cupon->aplicaAPaquete($paquete)) {
            return ['ok' => false, 'cupon' => $cupon, 'descuento_centavos' => 0, 'mensaje' => 'Este cupón no aplica a este paquete.'];
        }
        if ($cupon->minimo_compra_centavos > 0 && $paquete->precio_centavos < $cupon->minimo_compra_centavos) {
            return [
                'ok' => false,
                'cupon' => $cupon,
                'descuento_centavos' => 0,
                'mensaje' => 'Este cupón requiere una compra mínima de $' . number_format($cupon->minimo_compra_centavos / 100, 0, '.', ',') . ' MXN.',
            ];
        }

        $descuento = $cupon->calcularDescuentoCentavos((int) $paquete->precio_centavos);
        return ['ok' => true, 'cupon' => $cupon, 'descuento_centavos' => $descuento, 'mensaje' => null];
    }
}
