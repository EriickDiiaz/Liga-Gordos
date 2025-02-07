<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        Permission::create(['name' => 'crear equipos']);
        Permission::create(['name' => 'editar equipos']);
        Permission::create(['name' => 'borrar equipos']);
        Permission::create(['name' => 'crear jugadores']);
        Permission::create(['name' => 'editar jugadores']);
        Permission::create(['name' => 'borrar jugadores']);
        Permission::create(['name' => 'crear torneos']);
        Permission::create(['name' => 'editar torneos']);
        Permission::create(['name' => 'borrar torneos']);
        Permission::create(['name' => 'crear partidos']);
        Permission::create(['name' => 'editar partidos']);
        Permission::create(['name' => 'registrar acciones']);
        Permission::create(['name' => 'borrar partidos']);
        Permission::create(['name' => 'crear roles']);
        Permission::create(['name' => 'editar roles']);
        Permission::create(['name' => 'borrar roles']);
        Permission::create(['name' => 'crear permisos']);
        Permission::create(['name' => 'editar permisos']);
        Permission::create(['name' => 'borrar permisos']);
        
        // create roles and assign created permissions

        // Administrador
        $role = Role::create(['name' => 'Administrador']);
        $role->givePermissionTo(Permission::all());

        // Director
        $role = Role::create(['name' => 'Director de Liga']);
        $role->givePermissionTo([
            'crear equipos', 'editar equipos', 'borrar equipos',
            'crear jugadores', 'editar jugadores', 'borrar jugadores',
            'crear torneos', 'editar torneos', 'borrar torneos',
            'crear partidos', 'editar partidos', 'registrar acciones', 'borrar partidos',
        ]);

        // Arbitro
        $role = Role::create(['name' => 'Mesa Técnica']);
        $role->givePermissionTo([
            'editar partidos', 'registrar acciones'
        ]);

        // Capitán
        $role = Role::create(['name' => 'Capitán']);
        $role->givePermissionTo([
            'editar equipos',
            'crear jugadores', 'editar jugadores', 'borrar jugadores',
        ]);
    }
}

