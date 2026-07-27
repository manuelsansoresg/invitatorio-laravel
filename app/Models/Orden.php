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
            'paid_at' => 'datetime',
        ];
    }

    public function paquete(): BelongsTo
    {
        return $this->belongsTo(Paquete::class);
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
