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
        // El super administrador tiene todos los permisos, por eso no es necesario asignar permisos específicos.

        // Permisos para el rol de Administrador

            //Permisos de Empleados
            RolPermiso::create([
                'id_rol' => 2,
                'id_permiso' => 5
            ]);
            RolPermiso::create([
                'id_rol' => 2,
                'id_permiso' => 6
            ]);
            RolPermiso::create([
                'id_rol' => 2,
                'id_permiso' => 7
            ]);
            RolPermiso::create([
                'id_rol' => 2,
                'id_permiso' => 8
            ]);

            //Permisos de Comunidades
            RolPermiso::create([
                'id_rol' => 2,
                'id_permiso' => 13
            ]);
            RolPermiso::create([
                'id_rol' => 2,
                'id_permiso' => 14
            ]);
            RolPermiso::create([
                'id_rol' => 2,
                'id_permiso' => 15
            ]);
            RolPermiso::create([
                'id_rol' => 2,
                'id_permiso' => 16
            ]);

            //Permisos de Polizas
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
            //Permiso para chats de polizas
            RolPermiso::create([
                'id_rol' => 2,
                'id_permiso' => 26
            ]);

            //Permisos de Siniestros
            RolPermiso::create([
                'id_rol' => 2,
                'id_permiso' => 31
            ]);
            RolPermiso::create([
                'id_rol' => 2,
                'id_permiso' => 32
            ]);
            RolPermiso::create([
                'id_rol' => 2,
                'id_permiso' => 33
            ]);
            RolPermiso::create([
                'id_rol' => 2,
                'id_permiso' => 34
            ]);
            RolPermiso::create([
                'id_rol' => 2,
                'id_permiso' => 35
            ]);
            //Permisos de chats de siniestros
            RolPermiso::create([
                'id_rol' => 2,
                'id_permiso' => 36
            ]);

        //Permisos para el rol de Usuario
            //Permisos de Polizas
            RolPermiso::create([
                'id_rol' => 3,
                'id_permiso' => 21
            ]);
            RolPermiso::create([
                'id_rol' => 3,
                'id_permiso' => 22
            ]);
            RolPermiso::create([
                'id_rol' => 3,
                'id_permiso' => 23
            ]);
            RolPermiso::create([
                'id_rol' => 3,
                'id_permiso' => 24
            ]);
            RolPermiso::create([
                'id_rol' => 3,
                'id_permiso' => 25
            ]);
            //Permisos para chats de polizas
            RolPermiso::create([
                'id_rol' => 3,
                'id_permiso' => 26
            ]);

            //Permisos de Siniestros
            RolPermiso::create([
                'id_rol' => 3,
                'id_permiso' => 31
            ]);
            RolPermiso::create([
                'id_rol' => 3,
                'id_permiso' => 32
            ]);
            RolPermiso::create([
                'id_rol' => 3,
                'id_permiso' => 33
            ]);
            RolPermiso::create([
                'id_rol' => 3,
                'id_permiso' => 34
            ]);
            RolPermiso::create([
                'id_rol' => 3,
                'id_permiso' => 35
            ]);
            //Permisos de chats de siniestros
            RolPermiso::create([
                'id_rol' => 3,
                'id_permiso' => 36
            ]);

    }
}
