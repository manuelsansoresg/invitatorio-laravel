<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Paquete extends Model
{
    use HasFactory;

    protected $table = 'paquetes';

    protected $fillable = [
        'slug',
        'formato',
        'nombre',
        'descripcion',
        'precio_centavos',
        'badge',
        'destacado',
        'items',
        'permite_gestionar_invitados',
        'activo',
        'orden',
    ];

    protected function casts(): array
    {
        return [
            'items'      => 'array',
            'destacado'  => 'boolean',
            'activo'     => 'boolean',
            'precio_centavos' => 'integer',
            'orden'      => 'integer',
            'permite_gestionar_invitados' => 'boolean',
        ];
    }

    /**
     * Slug como llave de route model binding: /comprar/{paquete:slug}
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Precio en formato moneda: $1,300.00 MXN
     */
    public function getPrecioFormateadoAttribute(): string
    {
        return '$' . number_format($this->precio_centavos / 100, 0, '.', ',');
    }

    /**
     * Precio tal cual se lo mandamos a Mercado Pago: 1300.00
     * (MP acepta el precio como float, nosotros lo controlamos).
     */
    public function getPrecioDecimalAttribute(): float
    {
        return round($this->precio_centavos / 100, 2);
    }

    public function ordenes(): HasMany
    {
        return $this->hasMany(Orden::class);
    }

    /**
     * Scopes convenientes para la landing
     */
    public function scopeActivos(Builder $q): Builder
    {
        return $q->where('activo', true)->orderBy('orden');
    }

    public function scopeDelFormato(Builder $q, string $formato): Builder
    {
        return $q->where('formato', $formato);
    }
}
