<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['invitacion_id', 'nombre', 'ip', 'user_agent'])]
class Confirmacion extends Model
{
    use HasFactory;

    /**
     * Nombre real de la tabla (plural en español).
     */
    protected $table = 'confirmaciones';

    /**
     * Una confirmación pertenece a una invitación.
     */
    public function invitacion(): BelongsTo
    {
        return $this->belongsTo(Invitacion::class);
    }
}
