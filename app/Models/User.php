<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    public const ROLE_ADMIN = 'administrador';

    public const ROLE_CLIENT = 'cliente';

    /**
     * Roles disponibles para los usuarios del panel.
     *
     * @return array<int, string>
     */
    public static function roles(): array
    {
        return [
            self::ROLE_ADMIN,
            self::ROLE_CLIENT,
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function invitaciones(): HasMany
    {
        return $this->hasMany(Invitacion::class);
    }

    public function ordenes(): HasMany
    {
        return $this->hasMany(Orden::class, 'comprador_email', 'email');
    }

    public function suscripciones(): HasMany
    {
        return $this->hasMany(Suscripcion::class)->latest('id');
    }

    /**
     * Suscripción activa del usuario (la primera que esté activa).
     * Si tiene varias, se toma la más reciente.
     */
    public function suscripcionActiva(): ?Suscripcion
    {
        return $this->suscripciones()
            ->get()
            ->first(fn (Suscripcion $s) => $s->esActiva());
    }

    /**
     * Templates que el admin habilitó para este usuario. Solo los
     * activos (pivot.activo = true Y template.activo = true).
     */
    public function templates(): BelongsToMany
    {
        return $this->belongsToMany(Template::class, 'user_templates')
            ->withPivot('activo', 'asignado_en')
            ->withTimestamps();
    }

    public function templatesVisibles()
    {
        return $this->templates()
            ->wherePivot('activo', true)
            ->where('templates.activo', true)
            ->orderBy('templates.orden')
            ->orderBy('templates.nombre');
    }

    /**
     * Órdenes pagadas del usuario. Usado para saber qué paquete
     * compró y qué features tiene habilitadas.
     */
    public function ordenesPagadas(): HasMany
    {
        return $this->hasMany(Orden::class, 'comprador_email', 'email')
            ->where('estado', 'approved')
            ->latest('paid_at');
    }

    /**
     * Devuelve el paquete "activo" del cliente = el de la orden
     * aprobada más reciente. Si no tiene órdenes pagadas, null.
     *
     * Esto es lo que define si puede gestionar invitados, etc.
     */
    public function paqueteActivo(): ?Paquete
    {
        return $this->ordenesPagadas()->first()?->paquete;
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
