<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            ['id_rol' => 1, 'name' => 'SuperAdmin', 'email' => 'admin@admin.com', 'password' => bcrypt('admin'), 'state' => 1],
            ['id_rol' => 1, 'name' => 'Seguros Axarquía', 'email' => 'segurosaxarquia@gmail.com', 'password' => bcrypt('password'), 'state' => 1],
            ['id_rol' => 3, 'name' => 'AXARQUIA', 'email' => 'info@administraciones-axarquia.es', 'password' => bcrypt('password'), 'state' => 0],
            ['id_rol' => 3, 'name' => 'JUAN CAMPOS', 'email' => 'juancampos@centrored.net', 'password' => bcrypt('password'), 'state' => 0],
            ['id_rol' => 3, 'name' => 'PACO MOLINA', 'email' => 'pacomolina1@hotmail.com', 'password' => bcrypt('password'), 'state' => 0],
            ['id_rol' => 3, 'name' => 'LUCENA', 'email' => 'info@lucenaadministraciones.com', 'password' => bcrypt('password'), 'state' => 0],
            ['id_rol' => 3, 'name' => 'Jose Manuel GARRIDO', 'email' => 'asesoriagarrido@yahoo.es', 'password' => bcrypt('password'), 'state' => 0],
            ['id_rol' => 3, 'name' => 'JOSE LUIS MGS', 'email' => 'velezmar@mgs.es', 'password' => bcrypt('password'), 'state' => 0],
            ['id_rol' => 3, 'name' => 'JOSE MARÍA RUIZ (Ruíz)', 'email' => 'añadiruno2nuevo@gmail.com', 'password' => bcrypt('password'), 'state' => 0],
            ['id_rol' => 3, 'name' => 'SAMUEL MOLERO', 'email' => 'añadirunonuevo@gmail.com', 'password' => bcrypt('password'), 'state' => 0],
            ['id_rol' => 3, 'name' => 'SILVA', 'email' => 'comunidadessilva@hotmail.com', 'password' => bcrypt('password'), 'state' => 0],
            ['id_rol' => 3, 'name' => 'ANTONIO FINCAS CONTRERAS', 'email' => 'acontreras@asesoriafch.com', 'password' => bcrypt('password'), 'state' => 0],
            ['id_rol' => 3, 'name' => 'Eduardo Pareja', 'email' => 'info@administracionespareja.com', 'password' => bcrypt('password'), 'state' => 0],
            ['id_rol' => 3, 'name' => 'Juan Jesús Ranea', 'email' => 'e.axarquia@gmail.com', 'password' => bcrypt('password'), 'state' => 0],
            ['id_rol' => 3, 'name' => 'Diego Hormigos', 'email' => 'admonhormigos@hotmail.com', 'password' => bcrypt('password'), 'state' => 0],
            ['id_rol' => 3, 'name' => 'Jose Fco González (C&G Asociados)', 'email' => 'gonnavadmon@gmail.com', 'password' => bcrypt('password'), 'state' => 0],
            ['id_rol' => 3, 'name' => 'Pigall', 'email' => 'pigalladmon@gmail.com', 'password' => bcrypt('password'), 'state' => 0],
            ['id_rol' => 3, 'name' => 'Atlas XXI', 'email' => 'atlas21velez@hotmail.com', 'password' => bcrypt('password'), 'state' => 0],
            ['id_rol' => 3, 'name' => 'MiFinca.es', 'email' => 'administracion@mifincaes.onmicrosoft.com', 'password' => bcrypt('password'), 'state' => 0],
            ['id_rol' => 3, 'name' => 'RL (Curro)', 'email' => 'info@rladministraciones.com', 'password' => bcrypt('password'), 'state' => 0],
            ['id_rol' => 3, 'name' => 'Mercedes Gonzalez MG', 'email' => 'mg.asesores@gmail.com', 'password' => bcrypt('password'), 'state' => 0],
            
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
