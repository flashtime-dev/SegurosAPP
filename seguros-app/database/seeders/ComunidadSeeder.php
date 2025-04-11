<?php

namespace Database\Seeders;

use App\Models\Comunidad;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ComunidadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Crear comunidades
        Comunidad::create([
            'nombre' => 'Comunidad 1',
            'cif' => 'CIF12345678',
            'direccion' => 'Calle Ejemplo 123',
            'ubi_catastral' => 'Ubicación Catastral 1',
            'ref_catastral' => 'RefCatast 1',
            'telefono' => '123456789'
        ]);
    }
}
