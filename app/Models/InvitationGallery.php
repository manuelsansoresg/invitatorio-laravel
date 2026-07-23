<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InvitationGallery extends Model
{
    use HasFactory;

    protected $table = 'invitacion_galeria';

    protected $fillable = [
        'invitacion_id',
        'imagen_path',
        'titulo',
        'descripcion',
        'orden',
        'activo',
    ];

    protected function casts(): array
    {
        return [
            'activo' => 'boolean',
        ];
    }

    public function invitacion(): BelongsTo
    {
        return $this->belongsTo(Invitacion::class);
    }
}
