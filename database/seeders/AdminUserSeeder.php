<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Crea o actualiza el usuario administrador principal del panel.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'manuelsansoresg@gmail.com'],
            [
                'name' => 'manuel sansores',
                'password' => Hash::make('demor00txx'),
                'role' => User::ROLE_ADMIN,
            ],
        );
    }
}
