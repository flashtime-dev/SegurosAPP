<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder_Prueba extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            ['id_rol' => 1, 'name' => 'SuperAdmin', 'email' => 'admin@admin.com', 'password' => bcrypt('admin'), 'state' => 1],
            ['id_rol' => 1, 'name' => 'Seguros Axarquía', 'email' => 'segurosaxarquia@gmail.com', 'password' => bcrypt('password'), 'state' => 1],
            ['id_rol' => 2, 'name' => 'Laura Gómez', 'email' => 'laura.gomez@example.com', 'password' => bcrypt('password'), 'state' => 1],
            ['id_rol' => 2, 'name' => 'Carlos Pérez', 'email' => 'carlos.perez@example.com', 'password' => bcrypt('password'), 'state' => 0],
            ['id_rol' => 3, 'name' => 'Ana Torres', 'email' => 'ana.torres@example.com', 'password' => bcrypt('password'), 'state' => 1],
            ['id_rol' => 3, 'name' => 'Luis Ramírez', 'email' => 'luis.ramirez@example.com', 'password' => bcrypt('password'), 'state' => 1],
            ['id_rol' => 2, 'name' => 'Marta Sánchez', 'email' => 'marta.sanchez@example.com', 'password' => bcrypt('password'), 'state' => 1],
            ['id_rol' => 3, 'name' => 'Pedro Ortega', 'email' => 'pedro.ortega@example.com', 'password' => bcrypt('password'), 'state' => 0],
            ['id_rol' => 2, 'name' => 'Nuria López', 'email' => 'nuria.lopez@example.com', 'password' => bcrypt('password'), 'state' => 1],
            ['id_rol' => 3, 'name' => 'Javier Ruiz', 'email' => 'javier.ruiz@example.com', 'password' => bcrypt('password'), 'state' => 1],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
