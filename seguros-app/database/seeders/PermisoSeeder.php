<?php

namespace Database\Seeders;

use App\Models\Permiso;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermisoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Usuarios
        Permiso::create([
            'nombre' => 'usuarios.crear',
            'descripcion' => 'Crear Usuarios',
            'id_tipo' => 1
        ]);
        Permiso::create([
            'nombre' => 'usuarios.ver',
            'descripcion' => 'Ver Usuarios',
            'id_tipo' => 1
        ]);
        Permiso::create([
            'nombre' => 'usuarios.editar',
            'descripcion' => 'Actualizar Usuarios',
            'id_tipo' => 1
        ]);
        Permiso::create([
            'nombre' => 'usuarios.eliminar',
            'descripcion' => 'Eliminar Usuarios',
            'id_tipo' => 1
        ]);

        //Empleados
        Permiso::create([
            'nombre' => 'empleados.crear',
            'descripcion' => 'Crear Empleados',
            'id_tipo' => 2
        ]);
        Permiso::create([
            'nombre' => 'empleados.ver',
            'descripcion' => 'Ver Empleados',
            'id_tipo' => 2
        ]);
        Permiso::create([
            'nombre' => 'empleados.editar',
            'descripcion' => 'Actualizar Empleados',
            'id_tipo' => 2
        ]);
        Permiso::create([
            'nombre' => 'empleados.eliminar',
            'descripcion' => 'Eliminar Empleados',
            'id_tipo' => 2
        ]);

        //Roles
        Permiso::create([
            'nombre' => 'roles.crear',
            'descripcion' => 'Crear Roles',
            'id_tipo' => 3
        ]);
        Permiso::create([
            'nombre' => 'roles.ver',
            'descripcion' => 'Ver Roles',
            'id_tipo' => 3
        ]);
        Permiso::create([
            'nombre' => 'roles.editar',
            'descripcion' => 'Actualizar Roles',
            'id_tipo' => 3
        ]);
        Permiso::create([
            'nombre' => 'roles.eliminar',
            'descripcion' => 'Eliminar Roles',
            'id_tipo' => 3
        ]);

        //Comunidades
        Permiso::create([
            'nombre' => 'comunidades.crear',
            'descripcion' => 'Crear Comunidades',
            'id_tipo' => 4
        ]);
        Permiso::create([
            'nombre' => 'comunidades.ver',
            'descripcion' => 'Ver Comunidades',
            'id_tipo' => 4
        ]);
        Permiso::create([
            'nombre' => 'comunidades.editar',
            'descripcion' => 'Actualizar Comunidades',
            'id_tipo' => 4
        ]);
        Permiso::create([
            'nombre' => 'comunidades.eliminar',
            'descripcion' => 'Eliminar Comunidades',
            'id_tipo' => 4
        ]);

        //Agentes
        Permiso::create([
            'nombre' => 'agentes.crear',
            'descripcion' => 'Crear Agentes',
            'id_tipo' => 5
        ]);
        Permiso::create([
            'nombre' => 'agentes.ver',
            'descripcion' => 'Ver Agentes',
            'id_tipo' => 5
        ]);
        Permiso::create([
            'nombre' => 'agentes.editar',
            'descripcion' => 'Actualizar Agentes',
            'id_tipo' => 5
        ]);
        Permiso::create([
            'nombre' => 'agentes.eliminar',
            'descripcion' => 'Eliminar Agentes',
            'id_tipo' => 5
        ]);

        //Polizas
        Permiso::create([
            'nombre' => 'polizas.crear',
            'descripcion' => 'Crear Polizas',
            'id_tipo' => 6
        ]);
        Permiso::create([
            'nombre' => 'polizas.ver',
            'descripcion' => 'Ver Polizas',
            'id_tipo' => 6
        ]);
        Permiso::create([
            'nombre' => 'polizas.detalles',
            'descripcion' => 'Ver Detalles de Polizas',
            'id_tipo' => 6
        ]);
        Permiso::create([
            'nombre' => 'polizas.editar',
            'descripcion' => 'Actualizar Polizas',
            'id_tipo' => 6
        ]);
        Permiso::create([
            'nombre' => 'polizas.eliminar',
            'descripcion' => 'Eliminar Polizas',
            'id_tipo' => 6
        ]);

        //ChatsPolizas
        Permiso::create([
            'nombre' => 'chats_polizas.crear',
            'descripcion' => 'Escribir Chats de Polizas',
            'id_tipo' => 6
        ]);
        
        //Siniestros
        Permiso::create([
            'nombre' => 'siniestros.crear',
            'descripcion' => 'Crear Siniestros',
            'id_tipo' => 7
        ]);
        Permiso::create([
            'nombre' => 'siniestros.ver',
            'descripcion' => 'Ver Siniestros',
            'id_tipo' => 7
        ]);

        Permiso::create([
            'nombre' => 'siniestros.detalles',
            'descripcion' => 'Ver Detalles de Siniestros',
            'id_tipo' => 7
        ]);

        Permiso::create([
            'nombre' => 'siniestros.editar',
            'descripcion' => 'Actualizar Siniestros',
            'id_tipo' => 7
        ]);
        Permiso::create([
            'nombre' => 'siniestros.eliminar',
            'descripcion' => 'Eliminar Siniestros',
            'id_tipo' => 7
        ]);

        //ChatsSiniestros
        Permiso::create([
            'nombre' => 'chats_siniestros.crear',
            'descripcion' => 'Escribir Chats de Siniestros',
            'id_tipo' => 7
        ]);
    }
}
