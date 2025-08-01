<?php

namespace Database\Seeders;

use App\Models\Rol;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Rol::create([
            'nombre' => 'Superadministrador'
        ]);
        Rol::create([
            'nombre' => 'Administrador'
        ]);
        Rol::create([
            'nombre' => 'Usuario'
        ]);

    }
}
