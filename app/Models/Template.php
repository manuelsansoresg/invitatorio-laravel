<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Template extends Model
{
    use HasFactory;

    protected $table = 'templates';

    protected $fillable = [
        'slug',
        'formato',
        'nombre',
        'descripcion',
        'imagen_preview_path',
        'config_json',
        'activo',
        'orden',
    ];

    protected function casts(): array
    {
        return [
            'config_json' => 'array',
            'activo'      => 'boolean',
            'orden'       => 'integer',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function usuarios(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_templates')
            ->withPivot('activo', 'asignado_en')
            ->withTimestamps();
    }

    public function invitaciones(): HasMany
    {
        return $this->hasMany(Invitacion::class);
    }

    // ─────────────── Scopes ───────────────

    public function scopeActivos(Builder $q): Builder
    {
        return $q->where('activo', true);
    }

    public function scopeDelFormato(Builder $q, string $formato): Builder
    {
        return $q->where('formato', $formato);
    }

    /**
     * Templates que un usuario específico puede ver (tiene fila en
     * user_templates con activo=true y el template global está activo).
     * Este es el scope que usa el cliente para listar sus templates.
     */
    public function scopeVisiblesPara(Builder $q, int $userId): Builder
    {
        return $q->where('activo', true)
            ->whereHas('usuarios', function (Builder $sub) use ($userId) {
                $sub->where('users.id', $userId)->where('user_templates.activo', true);
            })
            ->orderBy('orden')
            ->orderBy('nombre');
    }
}
