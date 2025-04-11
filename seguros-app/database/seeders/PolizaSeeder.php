<?php

namespace Database\Seeders;

use App\Models\Poliza;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PolizaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear pólizas
        Poliza::create([
            'id_compania' => 1, // ID de la compañía
            'id_comunidad' => 1, // ID de la comunidad
            'id_agente' => 1, // ID del agente
            'alias' => 'Póliza de Vida',
            'numero' => '1234567890',
            'fecha_efecto' => now(),
            'cuenta' => '123456789012345678901234',
            'forma_pago' => 'Anual',
            'prima_neta' => 1000.00,
            'prima_total' => 1200.00,
            'pdf_poliza' => null,
            'observaciones' => null,
            'estado' => 'En Vigor'
        ]);
        Poliza::create([
            'id_compania' => 1, // ID de la compañía
            'id_comunidad' => 1, // ID de la comunidad
            'id_agente' => 1, // ID del agente
            'alias' => 'Póliza de Salud',
            'numero' => '0987654321',
            'fecha_efecto' => now(),
            'cuenta' => null,
            'forma_pago' => 'Mensual',
            'prima_neta' => 500.00,
            'prima_total' => 600.00,
            'pdf_poliza' => null,
            'observaciones' => null,
            'estado' => 'Anulada'
        ]);
    }
}
