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
            'crear equipos',
            'editar equipos',
            'borrar equipos',

            'crear jugadores',
            'editar jugadores',
            'borrar jugadores',

            'crear torneos',
            'editar torneos',
            'borrar torneos',

            'crear partidos',
            'editar partidos',
            'borrar partidos',
            'registrar acciones',            

            'gestionar usuarios',
            'gestionar roles y permisos'
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Crear roles y asignar permisos
        $adminRole = Role::create(['name' => 'Administrador']);
        $adminRole->givePermissionTo(Permission::all());

        $directorRole = Role::create(['name' => 'Director de Liga']);
        $directorRole->givePermissionTo([
            'crear equipos', 'editar equipos', 'borrar equipos',
            'crear jugadores', 'editar jugadores', 'borrar jugadores',
            'crear torneos', 'editar torneos', 'borrar torneos',
            'crear partidos', 'editar partidos', 'registrar acciones', 'borrar partidos'
        ]);

        $mesaTecnicaRole = Role::create(['name' => 'Mesa Técnica']);
        $mesaTecnicaRole->givePermissionTo([
            'editar partidos', 'registrar acciones'
        ]);

        $capitanRole = Role::create(['name' => 'Capitán']);
        $capitanRole->givePermissionTo([
            'editar equipos',
            'crear jugadores', 'editar jugadores', 'borrar jugadores'
        ]);
    }
}

