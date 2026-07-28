<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
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
