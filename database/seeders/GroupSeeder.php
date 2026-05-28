<?php

namespace Database\Seeders;

use App\Models\Group;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GroupSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $adminGroup = Group::create([
            'name' => 'Administradores',
            'slug' => 'admins',
            'description' => 'Grupo de administradores do sistema',
        ]);

        $userGroup = Group::create([
            'name' => 'Padrão',
            'slug' => 'padrao',
            'description' => 'Grupo padrão para usuários',
        ]);

        $admin = User::where('email', 'admin@dtikanban.local')->first();
        if ($admin) {
            $adminGroup->users()->attach($admin->id);
        }

        $user = User::where('email', 'user@dtikanban.local')->first();
        if ($user) {
            $userGroup->users()->attach($user->id);
        }
    }
}
