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
        $permissions = [
            'crear equipos', 'editar equipos', 'borrar equipos',
            'crear jugadores', 'editar jugadores', 'borrar jugadores',
            'crear torneos', 'editar torneos', 'borrar torneos',
            'crear partidos', 'editar partidos', 'registrar acciones', 'borrar partidos',
            'gestion roles', 'crear roles', 'editar roles', 'borrar roles',
            'crear permisos', 'editar permisos', 'borrar permisos',            
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
        
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

