<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
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
        'max_invitaciones',
        'dias_caducidad',
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
            'max_invitaciones'  => 'integer',
            'dias_caducidad'    => 'integer',
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

    public function suscripciones(): HasMany
    {
        return $this->hasMany(Suscripcion::class);
    }

    /**
     * Cupones que aplican a este paquete. La regla "sin paquetes
     * asignados = aplica a todos" vive en Cupon::aplicaAPaquete().
     */
    public function cupones(): BelongsToMany
    {
        return $this->belongsToMany(Cupon::class, 'cupon_paquete')
            ->withTimestamps();
    }

    /**
     * Texto humano del cupo y la caducidad para mostrarlo en admin
     * y checkout: "1 invitación · 365 días de vida".
     */
    public function getCupoLegibleAttribute(): string
    {
        $cupo = $this->max_invitaciones === 1
            ? '1 invitación'
            : "{$this->max_invitaciones} invitaciones";
        $dias = $this->dias_caducidad === 1
            ? '1 día de vida'
            : "{$this->dias_caducidad} días de vida";
        return "{$cupo} · {$dias}";
    }

    /**
     * Cupones que aplican a este paquete (algunos cupones aplican a
     * todos los paquetes — esos aparecen acá con la lista vacía en
     * la otra punta; el chequeo de "aplica a este paquete" vive en
     * Cupon::aplicaAPaquete()).
     */
    public function cupones(): BelongsToMany
    {
        return $this->belongsToMany(Cupon::class, 'cupon_paquete')
            ->withTimestamps();
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
