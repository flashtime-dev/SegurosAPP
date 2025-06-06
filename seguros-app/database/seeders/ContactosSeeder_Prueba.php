<?php

namespace Database\Seeders;

use App\Models\Contacto;
use Illuminate\Database\Seeder;

class ContactosSeeder_Prueba extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $contactos = [
            [
                'id_siniestro' => 1,
                'nombre' => 'Ana Martínez',
                'cargo' => 'Coordinadora de siniestros',
                'piso' => '3',
                'telefono' => '+34622156789',
            ],
            [
                'id_siniestro' => 1,
                'nombre' => 'José Fernández',
                'cargo' => 'Responsable de asistencia',
                'piso' => '1',
                'telefono' => '+34987654321',
            ],
            [
                'id_siniestro' => 2,
                'nombre' => 'Laura Gómez',
                'cargo' => 'Jefa de riesgos',
                'piso' => '5',
                'telefono' => '+34611223344',
            ],
            [
                'id_siniestro' => 3,
                'nombre' => 'Carlos Pérez',
                'cargo' => 'Técnico de campo',
                'piso' => '2',
                'telefono' => '+34999887766',
            ],
            [
                'id_siniestro' => 4,
                'nombre' => 'Marta Sánchez',
                'cargo' => 'Supervisor',
                'piso' => '4',
                'telefono' => '+34777665544',
            ],
        ];

        foreach ($contactos as $contacto) {
            Contacto::create($contacto);
        }
    }
}
