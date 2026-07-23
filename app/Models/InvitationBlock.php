<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InvitationBlock extends Model
{
    use HasFactory;

    public const TIPOS = [
        'hero',
        'cuenta_regresiva',
        'informacion_evento',
        'dress_code',
        'ubicacion',
        'mesa_regalos',
        'musica',
        'mensaje',
        'whatsapp',
        'galeria',
        'padrinos',
        'itinerario',
    ];

    protected $table = 'invitacion_bloques';

    protected $fillable = [
        'invitacion_id',
        'tipo',
        'titulo',
        'contenido',
        'config_json',
        'orden',
        'activo',
    ];

    protected function casts(): array
    {
        return [
            'config_json' => 'array',
            'activo' => 'boolean',
        ];
    }

    public function invitacion(): BelongsTo
    {
        return $this->belongsTo(Invitacion::class);
    }
}
