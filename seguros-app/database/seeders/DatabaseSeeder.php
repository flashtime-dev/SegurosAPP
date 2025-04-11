<?php

namespace Database\Seeders;

use App\Models\Compania;
use App\Models\Comunidad;
use App\Models\Poliza;
use App\Models\TelefonoCompania;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            TipoPermisoSeeder::class,
            PermisoSeeder::class,
            RolSeeder::class,
            RolPermisoSeeder::class,
            UserSeeder::class,
            CompaniaSeeder::class,
            TelefonoCompaniaSeeder::class
        ]);
    }
}
