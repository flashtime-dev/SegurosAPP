<?php

namespace Database\Seeders;

use App\Models\Siniestro;
use Illuminate\Database\Seeder;

class SiniestrosSeeder_Prueba extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $siniestros = [
            [
                'id_poliza' => 1,
                'declaracion' => 'Accidente leve en carretera',
                'expediente' => 'EXP-2025-001',
                'exp_cia' => 'CIA-1001',
                'exp_asist' => 'ASIST-5001',
                'fecha_ocurrencia' => '2025-05-20',
                'adjunto' => false,
                'estado' => 'abierto',
            ],
            [
                'id_poliza' => 2,
                'declaracion' => 'Daños por incendio en vivienda',
                'expediente' => 'EXP-2025-002',
                'exp_cia' => 'CIA-1002',
                'exp_asist' => 'ASIST-5002',
                'fecha_ocurrencia' => '2025-04-15',
                'adjunto' => false,
                'estado' => 'cerrado',
            ],
            [
                'id_poliza' => 1,
                'declaracion' => 'Robo con violencia',
                'expediente' => 'EXP-2025-003',
                'exp_cia' => 'CIA-1001',
                'exp_asist' => 'ASIST-5003',
                'fecha_ocurrencia' => '2025-03-30',
                'adjunto' => false,
                'estado' => 'Cerrado',
            ],
            [
                'id_poliza' => 3,
                'declaracion' => 'Accidente de tráfico con heridos',
                'expediente' => 'EXP-2025-004',
                'exp_cia' => 'CIA-1003',
                'exp_asist' => 'ASIST-5004',
                'fecha_ocurrencia' => '2025-01-10',
                'adjunto' => false,
                'estado' => 'abierto',
            ],
        ];

        foreach ($siniestros as $siniestro) {
            Siniestro::create($siniestro);
        }
    }
}
