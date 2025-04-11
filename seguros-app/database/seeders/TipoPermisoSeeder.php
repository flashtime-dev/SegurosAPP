<?php

namespace Database\Seeders;

use App\Models\TipoPermiso;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TipoPermisoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear tipos de permisos
        TipoPermiso::create([
            'nombre' => 'Usuarios'
        ]);
        TipoPermiso::create([
            'nombre' => 'Permisos'
        ]);
        TipoPermiso::create([
            'nombre' => 'Roles'
        ]);
        TipoPermiso::create([
            'nombre' => 'Tipo de Permisos'
        ]);
        TipoPermiso::create([
            'nombre' => 'Compañias'
        ]);
        TipoPermiso::create([
            'nombre' => 'Comunidades'
        ]);
        TipoPermiso::create([
            'nombre' => 'Polizas'
        ]);
        TipoPermiso::create([
            'nombre' => 'Siniestros'
        ]);
    }
}
