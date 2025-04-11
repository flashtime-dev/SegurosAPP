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
            'nombre' => 'Crear Usuarios',
            'id_tipo' => 1
        ]);
        Permiso::create([
            'nombre' => 'Ver Usuarios',
            'id_tipo' => 1
        ]);
        Permiso::create([
            'nombre' => 'Actualizar Usuarios',
            'id_tipo' => 1
        ]);
        Permiso::create([
            'nombre' => 'Eliminar Usuarios',
            'id_tipo' => 1
        ]);
        Permiso::create([
            'nombre' => 'Crear Permisos',
            'id_tipo' => 2
        ]);
        Permiso::create([
            'nombre' => 'Ver Permisos',
            'id_tipo' => 2
        ]);
        Permiso::create([
            'nombre' => 'Actualizar Permisos',
            'id_tipo' => 2
        ]);
        Permiso::create([
            'nombre' => 'Eliminar Permisos',
            'id_tipo' => 2
        ]);
        Permiso::create([
            'nombre' => 'Crear Roles',
            'id_tipo' => 3
        ]);
        Permiso::create([
            'nombre' => 'Ver Roles',
            'id_tipo' => 3
        ]);
        Permiso::create([
            'nombre' => 'Actualizar Roles',
            'id_tipo' => 3
        ]);
        Permiso::create([
            'nombre' => 'Eliminar Roles',
            'id_tipo' => 3
        ]);
        Permiso::create([
            'nombre' => 'Crear Tipo de Permisos',
            'id_tipo' => 4
        ]);
        Permiso::create([
            'nombre' => 'Ver Tipo de Permisos',
            'id_tipo' => 4
        ]);
        Permiso::create([
            'nombre' => 'Actualizar Tipo de Permisos',
            'id_tipo' => 4
        ]);
        Permiso::create([
            'nombre' => 'Eliminar Tipo de Permisos',
            'id_tipo' => 4
        ]);
        Permiso::create([
            'nombre' => 'Crear Compañias',
            'id_tipo' => 5
        ]);
        Permiso::create([
            'nombre' => 'Ver Compañias',
            'id_tipo' => 5
        ]);
        Permiso::create([
            'nombre' => 'Actualizar Compañias',
            'id_tipo' => 5
        ]);
        Permiso::create([
            'nombre' => 'Eliminar Compañias',
            'id_tipo' => 5
        ]);
        Permiso::create([
            'nombre' => 'Crear Comunidades',
            'id_tipo' => 6
        ]);
        Permiso::create([
            'nombre' => 'Ver Comunidades',
            'id_tipo' => 6
        ]);
        Permiso::create([
            'nombre' => 'Actualizar Comunidades',
            'id_tipo' => 6
        ]);
        Permiso::create([
            'nombre' => 'Eliminar Comunidades',
            'id_tipo' => 6
        ]);
        Permiso::create([
            'nombre' => 'Crear Polizas',
            'id_tipo' => 7
        ]);
        Permiso::create([
            'nombre' => 'Ver Polizas',
            'id_tipo' => 7
        ]);
        Permiso::create([
            'nombre' => 'Actualizar Polizas',
            'id_tipo' => 7
        ]);
        Permiso::create([
            'nombre' => 'Eliminar Polizas',
            'id_tipo' => 7
        ]);
        Permiso::create([
            'nombre' => 'Crear Siniestros',
            'id_tipo' => 8
        ]);
        Permiso::create([
            'nombre' => 'Ver Siniestros',
            'id_tipo' => 8
        ]);
        Permiso::create([
            'nombre' => 'Actualizar Siniestros',
            'id_tipo' => 8
        ]);
        Permiso::create([
            'nombre' => 'Eliminar Siniestros',
            'id_tipo' => 8
        ]);
    }
}
