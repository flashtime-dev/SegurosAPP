<?php

namespace Database\Seeders;

use App\Models\UsuarioComunidad;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsuarioComunidadSeeder_Prueba extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        UsuarioComunidad::create([
            'id_usuario' => 3, // SuperAdmin
            'id_comunidad' => 1 // Comunidad Los Pinos
        ]);

        UsuarioComunidad::create([
            'id_usuario' => 2, // Seguros Axarquía
            'id_comunidad' => 1 // Comunidad Los Pinos
        ]);

        UsuarioComunidad::create([
            'id_usuario' => 3, // Laura Gómez
            'id_comunidad' => 2 // Comunidad El Roble
        ]);

        UsuarioComunidad::create([
            'id_usuario' => 4, // Carlos Pérez
            'id_comunidad' => 2 // Comunidad El Roble
        ]);

        UsuarioComunidad::create([
            'id_usuario' => 5, // Ana Torres
            'id_comunidad' => 3 // Comunidad La Marina
        ]);

        UsuarioComunidad::create([
            'id_usuario' => 6, // Luis Ramírez
            'id_comunidad' => 3 // Comunidad La Marina
        ]);

        UsuarioComunidad::create([
            'id_usuario' => 7, // Marta Sánchez
            'id_comunidad' => 1 // Comunidad Los Pinos
        ]);

        UsuarioComunidad::create([
            'id_usuario' => 8, // Pedro Ortega
            'id_comunidad' => 4 // Comunidad Las Flores
        ]);

        UsuarioComunidad::create([
            'id_usuario' => 9, // Nuria López
            'id_comunidad' => 2 // Comunidad El Roble
        ]);

        UsuarioComunidad::create([
            'id_usuario' => 10, // Javier Ruiz
            'id_comunidad' => 5 // Comunidad San Miguel
        ]);
    }
}
