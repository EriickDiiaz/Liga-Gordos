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
        // Crear el usuario administrador
        $admin = User::create([
            'name' => 'System Admin',
            'email' => 'admin',
            'password' => Hash::make('password'),
        ]);
    }
}