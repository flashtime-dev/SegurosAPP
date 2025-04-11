<?php

namespace Database\Seeders;

use App\Models\UsuarioComunidad;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsuarioComunidadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        UsuarioComunidad::create([
            'id_usuario' => 1, // ID del usuario
            'id_comunidad' => 1 // ID de la comunidad
        ]);
    }
}
