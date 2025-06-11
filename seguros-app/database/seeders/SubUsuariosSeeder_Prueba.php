<?php

namespace Database\Seeders;

use App\Models\Subusuario;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubUsuariosSeeder_Prueba extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subusuarios = [
            ['id' => 3,  'id_usuario_creador' => 3],
            ['id' => 4,  'id_usuario_creador' => 3],
            ['id' => 5,  'id_usuario_creador' => 3],
            ['id' => 6,  'id_usuario_creador' => 3],
            ['id' => 7,  'id_usuario_creador' => 3],
        ];

        foreach ($subusuarios as $subusuario) {
            Subusuario::create($subusuario);
        }
    }
}
