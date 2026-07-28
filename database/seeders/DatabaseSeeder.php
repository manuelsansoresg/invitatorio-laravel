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
        // Orden importante: primero admin → paquetes → templates → cupones
        // → invitaciones (las invitaciones referencian paquetes y pueden
        // referenciar templates).
        $this->call([
            AdminUserSeeder::class,
            PaqueteSeeder::class,
            TemplateSeeder::class,
            CuponSeeder::class,
            InvitacionSeeder::class,
            ValentinaInvitationSeeder::class,
            InstagramWeddingInvitationSeeder::class,
        ]);
    }
}
