<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['nombre', 'apellido_paterno', 'apellido_materno', 'ruta'])]
class Invitacion extends Model
{
    use HasFactory;

    /**
     * Nombre real de la tabla (Laravel habría generado "invitacions"
     * por la pluralización inglesa por defecto; usamos la forma en
     * español que sí existe en la migración).
     */
    protected $table = 'invitaciones';

    /**
     * Una invitación tiene muchas confirmaciones de asistencia.
     */
    public function confirmaciones(): HasMany
    {
        return $this->hasMany(Confirmacion::class);
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
