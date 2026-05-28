<?php

namespace Database\Seeders;

use App\Models\User;
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
        User::factory()->create([
            'name' => 'Administrador DTI',
            'email' => 'admin@dtikanban.local',
            'password' => 'Admin123!',
            'is_admin' => true,
        ]);

        User::factory()->create([
            'name' => 'Usuário Padrão',
            'email' => 'user@dtikanban.local',
            'password' => 'User12345',
        ]);

        $this->call([GroupSeeder::class]);
    }
}
