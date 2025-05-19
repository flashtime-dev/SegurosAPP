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
        Permiso::create([
            'nombre' => 'permisos.crear',
            'descripcion' => 'Crear Permisos',
            'id_tipo' => 2
        ]);
        Permiso::create([
            'nombre' => 'permisos.ver',
            'descripcion' => 'Ver Permisos',
            'id_tipo' => 2
        ]);
        Permiso::create([
            'nombre' => 'permisos.editar',
            'descripcion' => 'Actualizar Permisos',
            'id_tipo' => 2
        ]);
        Permiso::create([
            'nombre' => 'permisos.eliminar',
            'descripcion' => 'Eliminar Permisos',
            'id_tipo' => 2
        ]);
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
        Permiso::create([
            'nombre' => 'tipospermisos.crear',
            'descripcion' => 'Crear Tipo de Permisos',
            'id_tipo' => 4
        ]);
        Permiso::create([
            'nombre' => 'tipospermisos.ver',
            'descripcion' => 'Ver Tipo de Permisos',
            'id_tipo' => 4
        ]);
        Permiso::create([
            'nombre' => 'tipospermisos.editar',
            'descripcion' => 'Actualizar Tipo de Permisos',
            'id_tipo' => 4
        ]);
        Permiso::create([
            'nombre' => 'tipospermisos.eliminar',
            'descripcion' => 'Eliminar Tipo de Permisos',
            'id_tipo' => 4
        ]);
        Permiso::create([
            'nombre' => 'companias.crear',
            'descripcion' => 'Crear Compañias',
            'id_tipo' => 5
        ]);
        Permiso::create([
            'nombre' => 'companias.ver',
            'descripcion' => 'Ver Compañias',
            'id_tipo' => 5
        ]);
        Permiso::create([
            'nombre' => 'companias.editar',
            'descripcion' => 'Actualizar Compañias',
            'id_tipo' => 5
        ]);
        Permiso::create([
            'nombre' => 'companias.eliminar',
            'descripcion' => 'Eliminar Compañias',
            'id_tipo' => 5
        ]);
        Permiso::create([
            'nombre' => 'comunidades.crear',
            'descripcion' => 'Crear Comunidades',
            'id_tipo' => 6
        ]);
        Permiso::create([
            'nombre' => 'comunidades.ver',
            'descripcion' => 'Ver Comunidades',
            'id_tipo' => 6
        ]);
        Permiso::create([
            'nombre' => 'comunidades.editar',
            'descripcion' => 'Actualizar Comunidades',
            'id_tipo' => 6
        ]);
        Permiso::create([
            'nombre' => 'comunidades.eliminar',
            'descripcion' => 'Eliminar Comunidades',
            'id_tipo' => 6
        ]);
        Permiso::create([
            'nombre' => 'polizas.crear',
            'descripcion' => 'Crear Polizas',
            'id_tipo' => 7
        ]);
        Permiso::create([
            'nombre' => 'polizas.ver',
            'descripcion' => 'Ver Polizas',
            'id_tipo' => 7
        ]);
        Permiso::create([
            'nombre' => 'polizas.editar',
            'descripcion' => 'Actualizar Polizas',
            'id_tipo' => 7
        ]);
        Permiso::create([
            'nombre' => 'polizas.eliminar',
            'descripcion' => 'Eliminar Polizas',
            'id_tipo' => 7
        ]);
        Permiso::create([
            'nombre' => 'siniestros.crear',
            'descripcion' => 'Crear Siniestros',
            'id_tipo' => 8
        ]);
        Permiso::create([
            'nombre' => 'siniestros.ver',
            'descripcion' => 'Ver Siniestros',
            'id_tipo' => 8
        ]);
        Permiso::create([
            'nombre' => 'siniestros.editar',
            'descripcion' => 'Actualizar Siniestros',
            'id_tipo' => 8
        ]);
        Permiso::create([
            'nombre' => 'siniestros.eliminar',
            'descripcion' => 'Eliminar Siniestros',
            'id_tipo' => 8
        ]);
    }
}
