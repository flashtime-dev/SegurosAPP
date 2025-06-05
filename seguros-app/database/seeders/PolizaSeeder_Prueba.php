<?php

namespace Database\Seeders;

use App\Models\Poliza;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PolizaSeeder_Prueba extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear pólizas
        Poliza::create([
            'id_compania' => 1,
            'id_comunidad' => 1,
            'id_agente' => 1,
            'alias' => 'Póliza de Vida',
            'numero' => '1234567890',
            'fecha_efecto' => now()->subYear(),
            'cuenta' => '123456789012345678901234',
            'forma_pago' => 'Anual',
            'prima_neta' => 1000.00,
            'prima_total' => 1200.00,
            'pdf_poliza' => null,
            'observaciones' => 'Póliza principal de vida',
            'estado' => 'En Vigor',
        ]);

        Poliza::create([
            'id_compania' => 1,
            'id_comunidad' => 2,
            'id_agente' => 2,
            'alias' => 'Póliza de Salud',
            'numero' => '0987654321',
            'fecha_efecto' => now()->subMonths(6),
            'cuenta' => null,
            'forma_pago' => 'Mensual',
            'prima_neta' => 500.00,
            'prima_total' => 600.00,
            'pdf_poliza' => null,
            'observaciones' => 'Cobertura completa para salud',
            'estado' => 'Anulada',
        ]);

        Poliza::create([
            'id_compania' => 2,
            'id_comunidad' => 3,
            'id_agente' => 3,
            'alias' => 'Póliza de Automóvil',
            'numero' => '1122334455',
            'fecha_efecto' => now()->subMonths(3),
            'cuenta' => '987654321098765432109876',
            'forma_pago' => 'Semestral',
            'prima_neta' => 800.00,
            'prima_total' => 960.00,
            'pdf_poliza' => null,
            'observaciones' => 'Cobertura total para vehículo',
            'estado' => 'Externa',
        ]);

        Poliza::create([
            'id_compania' => 3,
            'id_comunidad' => 4,
            'id_agente' => 4,
            'alias' => 'Póliza de Hogar',
            'numero' => '5566778899',
            'fecha_efecto' => now()->subYear(2),
            'cuenta' => null,
            'forma_pago' => 'Trimestral',
            'prima_neta' => 300.00,
            'prima_total' => 360.00,
            'pdf_poliza' => null,
            'observaciones' => 'Cobertura para daños en el hogar',
            'estado' => 'Solicitada',
        ]);

        Poliza::create([
            'id_compania' => 2,
            'id_comunidad' => 5,
            'id_agente' => 5,
            'alias' => 'Póliza de Viaje',
            'numero' => '6677889900',
            'fecha_efecto' => now()->subDays(10),
            'cuenta' => '56473829105647382910',
            'forma_pago' => 'Bianual',
            'prima_neta' => 150.00,
            'prima_total' => 180.00,
            'pdf_poliza' => null,
            'observaciones' => 'Cobertura para viaje internacional',
            'estado' => 'Vencida',
        ]);
    }
}
