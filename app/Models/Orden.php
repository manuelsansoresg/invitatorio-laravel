<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Orden extends Model
{
    use HasFactory;

    protected $table = 'ordenes';

    protected $fillable = [
        'paquete_id',
        'paquete_nombre',
        'paquete_precio_centavos',
        'descuento_centavos',
        'total_final_centavos',
        'cupon_id',
        'cupon_codigo',
        'comprador_nombre',
        'comprador_email',
        'comprador_telefono',
        'tipo_evento',
        'estado',
        'mp_preference_id',
        'mp_payment_id',
        'mp_payment_type',
        'mp_status',
        'mp_status_detail',
        'ip',
        'user_agent',
        'paid_at',
    ];

    protected function casts(): array
    {
        return [
            'paquete_precio_centavos' => 'integer',
            'descuento_centavos'      => 'integer',
            'total_final_centavos'    => 'integer',
            'paid_at'                 => 'datetime',
        ];
    }

    public function paquete(): BelongsTo
    {
        return $this->belongsTo(Paquete::class);
    }

    public function cupon(): BelongsTo
    {
        return $this->belongsTo(Cupon::class);
    }

    /**
     * Subtotal (precio del paquete antes de descuento).
     */
    public function getSubtotalCentavosAttribute(): int
    {
        return (int) $this->paquete_precio_centavos;
    }

    /**
     * Total final que efectivamente se cobró. Si por algún motivo el
     * campo quedó null (órdenes viejas), caemos al subtotal.
     */
    public function getTotalFinalCentavosAttribute(): int
    {
        if ($this->attributes['total_final_centavos'] === null) {
            return (int) $this->paquete_precio_centavos;
        }
        return (int) $this->attributes['total_final_centavos'];
    }

    public function tieneCupon(): bool
    {
        return $this->cupon_id !== null && (int) $this->descuento_centavos > 0;
    }

    public function suscripcion(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Suscripcion::class);
    }

    /**
     * Precio formateado para mostrar en la UI
     */
    public function getPrecioFormateadoAttribute(): string
    {
        return '$' . number_format($this->paquete_precio_centavos / 100, 0, '.', ',');
    }

    public function getPrecioDecimalAttribute(): float
    {
        return round($this->paquete_precio_centavos / 100, 2);
    }

    public function estaPagada(): bool
    {
        return $this->estado === 'approved';
    }

    public function estaPendiente(): bool
    {
        return in_array($this->estado, ['pending', 'in_process', 'authorized'], true);
    }

    public function fallo(): bool
    {
        return in_array($this->estado, ['rejected', 'cancelled'], true);
    }
}
