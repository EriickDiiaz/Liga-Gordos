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
            'Inscribir Jugadores',
            'Ver Cedula',

            'Crear Torneos',
            'Editar Torneos',
            'Borrar Torneos',

            'Crear Partidos',
            'Editar Partidos',
            'Borrar Partidos',
            'Registrar Acciones',
            'Gestionar Estadisticas',
            
            'Gestionar Patrocinadores',
            'Crear Patrocinadores',
            'Editar Patrocinadores',
            'Borrar Patrocinadores',

            'Gestionar Noticias',
            'Crear Noticias',
            'Editar Noticias',
            'Borrar Noticias',

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
            'Crear Jugadores', 'Editar Jugadores', 'Borrar Jugadores', 'Inscribir Jugadores', 'Ver Cedula',
            'Crear Torneos', 'Editar Torneos', 'Borrar Torneos',
            'Crear Partidos', 'Editar Partidos', 'Registrar Acciones', 'Gestionar Estadisticas', 'Borrar Partidos',
            'Gestionar Noticias', 'Crear Noticias', 'Editar Noticias', 'Borrar Noticias',
        ]);

        $mesaTecnicaRole = Role::create(['name' => 'Mesa Técnica']);
        $mesaTecnicaRole->givePermissionTo([
            'Registrar Acciones', 'Ver Cedula', 'Gestionar Estadisticas'
        ]);

        $capitanRole = Role::create(['name' => 'Capitán']);
        $capitanRole->givePermissionTo([
            'Crear Jugadores', 'Editar Jugadores', 'Inscribir Jugadores'
        ]);
    }
}

