<?php

namespace Database\Seeders;

use App\Models\Comunidad;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ComunidadSeeder_Prueba extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Crear comunidades
        Comunidad::create([
            'nombre' => 'Comunidad Los Pinos',
            'cif' => 'CIFA12345678',
            'direccion' => 'Av. Los Pinos 45',
            'ubi_catastral' => 'Zona Norte 12B',
            'ref_catastral' => 'RefCatast-001',
            'telefono' => '912345678',
        ]);

        Comunidad::create([
            'nombre' => 'Comunidad El Roble',
            'cif' => 'CIFB87654321',
            'direccion' => 'Calle El Roble 78',
            'ubi_catastral' => 'Zona Sur 3A',
            'ref_catastral' => 'RefCatast-002',
            'telefono' => '923456789',
        ]);

        Comunidad::create([
            'nombre' => 'Comunidad La Marina',
            'cif' => 'CIFC13579246',
            'direccion' => 'Paseo Marina 22',
            'ubi_catastral' => 'Zona Este 7C',
            'ref_catastral' => 'RefCatast-003',
            'telefono' => '934567890',
        ]);

        Comunidad::create([
            'nombre' => 'Comunidad Las Flores',
            'cif' => 'CIFD24681357',
            'direccion' => 'Plaza Flores 9',
            'ubi_catastral' => 'Zona Oeste 4D',
            'ref_catastral' => 'RefCatast-004',
            'telefono' => '945678901',
        ]);

        Comunidad::create([
            'nombre' => 'Comunidad San Miguel',
            'cif' => 'CIFE97531024',
            'direccion' => 'Calle San Miguel 100',
            'ubi_catastral' => 'Zona Centro 1E',
            'ref_catastral' => 'RefCatast-005',
            'telefono' => '956789012',
        ]);
    }
}
