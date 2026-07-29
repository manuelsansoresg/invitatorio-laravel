<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Confirmacion extends Model
{
    use HasFactory;

    /**
     * Nombre real de la tabla (plural en español).
     */
    protected $table = 'confirmaciones';

    protected $fillable = [
        'invitacion_id',
        'invitado_id',
        'nombre',
        'telefono',
        'mensaje',
        'numero_invitados',
        'asistira',
        'ip',
        'user_agent',
    ];

    protected function casts(): array
    {
        return [
            'numero_invitados' => 'integer',
            'asistira' => 'boolean',
        ];
    }

    /**
     * Una confirmación pertenece a una invitación.
     */
    public function invitacion(): BelongsTo
    {
        return $this->belongsTo(Invitacion::class);
    }

    /**
     * Si la confirmación vino de un invitado con link único, queda
     * asociada para que el panel lleve el control por invitado.
     */
    public function invitado(): BelongsTo
    {
        return $this->belongsTo(Invitado::class);
    }
}
