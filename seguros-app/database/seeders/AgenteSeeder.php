<?php

namespace Database\Seeders;

use App\Models\Agente;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AgenteSeeder extends Seeder
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
            'telefono' => '616311488',
        ]);

    }
}
