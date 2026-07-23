<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Invitación por defecto para la XVañera (Valentina)
        $this->call([
            AdminUserSeeder::class,
            InvitacionSeeder::class,
            ValentinaInvitationSeeder::class,
        ]);
    }
}
