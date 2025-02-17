<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        // Crear el usuario Administrador
        $admin = User::create([
            'name' => 'System Admin',
            'email' => 'admin',
            'password' => Hash::make('password'),
        ]);
        // Asignar el rol de Administrador al usuario admin
        $adminRole = Role::findByName('Administrador');
        $admin->assignRole($adminRole);

        // Crear el usuario Director de Liga
        $director = User::create([
            'name' => 'Director de Liga',
            'email' => 'director',
            'password' => Hash::make('password'),
        ]);
        // Asignar el rol de Director de Liga al usuario director
        $directorRole = Role::findByName('Director de Liga');
        $director->assignRole($directorRole);

        // Crear el usuario Mesa Técnica
        $mesa = User::create([
            'name' => 'Mesa Técnica',
            'email' => 'mesa',
            'password' => Hash::make('password'),
        ]);
        // Asignar el rol de Mesa Técnica al usuario mesa
        $mesaRole = Role::findByName('Mesa Técnica');
        $mesa->assignRole($mesaRole);

        // Crear el usuario Capitán
        $capitan = User::create([
            'name' => 'Capitán',
            'email' => 'capitan',
            'password' => Hash::make('password'),
        ]);

        // Asignar el rol de Capitán al usuario capitan
        $capitanRole = Role::findByName('Capitán');
        $capitan->assignRole($capitanRole);
    }
}