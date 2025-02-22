<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Crear permisos
        $permissions = [
            'Crear Equipos',
            'Editar Equipos',
            'Borrar Equipos',

            'Crear Jugadores',
            'Editar Jugadores',
            'Borrar Jugadores',

            'Crear Torneos',
            'Editar Torneos',
            'Borrar Torneos',

            'Crear Partidos',
            'Editar Partidos',
            'Borrar Partidos',
            'Registrar Acciones',            

            'Gestionar Usuarios',
            'Gestionar Roles y Permisos'
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Crear roles y asignar permisos
        $adminRole = Role::create(['name' => 'Administrador']);
        $adminRole->givePermissionTo(Permission::all());

        $directorRole = Role::create(['name' => 'Director de Liga']);
        $directorRole->givePermissionTo([
            'Crear Equipos', 'Editar Equipos', 'Borrar Equipos',
            'Crear Jugadores', 'Editar Jugadores', 'Borrar Jugadores',
            'Crear Torneos', 'Editar Torneos', 'Borrar Torneos',
            'Crear Partidos', 'Editar Partidos', 'Registrar Acciones', 'Borrar Partidos'
        ]);

        $mesaTecnicaRole = Role::create(['name' => 'Mesa Técnica']);
        $mesaTecnicaRole->givePermissionTo([
            'Registrar Acciones'
        ]);

        $capitanRole = Role::create(['name' => 'Capitán']);
        $capitanRole->givePermissionTo([
            'Crear Jugadores', 'Editar Jugadores'
        ]);
    }
}

