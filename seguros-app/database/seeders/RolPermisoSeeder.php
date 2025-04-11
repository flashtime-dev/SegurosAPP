<?php

namespace Database\Seeders;

use App\Models\RolPermiso;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolPermisoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Asignar permisos a los roles
        RolPermiso::create([
            'id_rol' => 2,
            'id_permiso' => 1
        ]);
        RolPermiso::create([
            'id_rol' => 2,
            'id_permiso' => 2
        ]);
        RolPermiso::create([
            'id_rol' => 2,
            'id_permiso' => 3
        ]);
        RolPermiso::create([
            'id_rol' => 2,
            'id_permiso' => 4
        ]);
        RolPermiso::create([
            'id_rol' => 2,
            'id_permiso' => 17
        ]);
        RolPermiso::create([
            'id_rol' => 2,
            'id_permiso' => 18
        ]);
        RolPermiso::create([
            'id_rol' => 2,
            'id_permiso' => 19
        ]);
        RolPermiso::create([
            'id_rol' => 2,
            'id_permiso' => 20
        ]);
        RolPermiso::create([
            'id_rol' => 2,
            'id_permiso' => 21
        ]);
        RolPermiso::create([
            'id_rol' => 2,
            'id_permiso' => 22
        ]);
        RolPermiso::create([
            'id_rol' => 2,
            'id_permiso' => 23
        ]);
        RolPermiso::create([
            'id_rol' => 2,
            'id_permiso' => 24
        ]);
        RolPermiso::create([
            'id_rol' => 2,
            'id_permiso' => 25
        ]);
        RolPermiso::create([
            'id_rol' => 2,
            'id_permiso' => 26
        ]);
        RolPermiso::create([
            'id_rol' => 2,
            'id_permiso' => 27
        ]);
        RolPermiso::create([
            'id_rol' => 2,
            'id_permiso' => 28
        ]);
        RolPermiso::create([
            'id_rol' => 2,
            'id_permiso' => 29
        ]);
        RolPermiso::create([
            'id_rol' => 2,
            'id_permiso' => 30
        ]);
        RolPermiso::create([
            'id_rol' => 2,
            'id_permiso' => 31
        ]);
        RolPermiso::create([
            'id_rol' => 2,
            'id_permiso' => 32
        ]);
        RolPermiso::create([
            'id_rol' => 3,
            'id_permiso' => 26
        ]);
        RolPermiso::create([
            'id_rol' => 3,
            'id_permiso' => 27
        ]);
        RolPermiso::create([
            'id_rol' => 3,
            'id_permiso' => 29
        ]);
        RolPermiso::create([
            'id_rol' => 3,
            'id_permiso' => 30
        ]);
        RolPermiso::create([
            'id_rol' => 3,
            'id_permiso' => 31
        ]);
    }
}
