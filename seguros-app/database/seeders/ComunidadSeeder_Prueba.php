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
            'cif' => 'H12345678',
            'direccion' => 'Av. Los Pinos 45',
            'ubi_catastral' => 'Zona Norte 12B',
            'ref_catastral' => '1234567890ABCDEFGHIJ',
            'telefono' => '+34912985674',
        ]);

        Comunidad::create([
            'nombre' => 'Comunidad El Roble',
            'cif' => 'H87654321',
            'direccion' => 'Calle El Roble 78',
            'ubi_catastral' => 'Zona Sur 3A',
            'ref_catastral' => '2234555890ABCDEFGRTJ',
            'telefono' => '+34923456789',
        ]);

        Comunidad::create([
            'nombre' => 'Comunidad La Marina',
            'cif' => 'H13579246',
            'direccion' => 'Paseo Marina 22',
            'ubi_catastral' => 'Zona Este 7C',
            'ref_catastral' => '1888567890ABCDEFGKLM',
            'telefono' => '+34934567890',
        ]);

        Comunidad::create([
            'nombre' => 'Comunidad Las Flores',
            'cif' => 'H24681357',
            'direccion' => 'Plaza Flores 9',
            'ubi_catastral' => 'Zona Oeste 4D',
            'ref_catastral' => '1233367890ABCDEFLPOA',
            'telefono' => '+34945678901',
        ]);

        Comunidad::create([
            'nombre' => 'Comunidad San Miguel',
            'cif' => 'B97531024',
            'direccion' => 'Calle San Miguel 100',
            'ubi_catastral' => 'Zona Centro 1E',
            'ref_catastral' => '1777567890ABCDEFWQSX',
            'telefono' => '+34956789012',
        ]);
    }
}
