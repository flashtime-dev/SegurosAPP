<?php

namespace Database\Seeders;

use App\Models\Agente;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AgenteSeeder_Prueba extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear agentes
        Agente::create([
            //Seguros Axarquía,616311488
            'nombre' => 'Seguros Axarquía',
            'telefono' => '+34616311488',
        ]);

        Agente::create([
            'nombre' => 'Juan Martínez',
            'telefono' => '+34600123456',
            'email' => 'juan.martinez@example.com',
        ]);

        Agente::create([
            'nombre' => 'María López',
            'telefono' => '+34600654321',
            'email' => 'maria.lopez@example.com',
        ]);

        Agente::create([
            'nombre' => 'Carlos García',
            'telefono' => '+34611234567',
            'email' => 'carlos.garcia@example.com',
        ]);

        Agente::create([
            'nombre' => 'Laura Fernández',
            'telefono' => '+34622345678',
            'email' => 'laura.fernandez@example.com',
        ]);

        Agente::create([
            'nombre' => 'Ana Ruiz',
            'telefono' => '+34633456789',
            'email' => 'ana.ruiz@example.com',
        ]);

    }
}
