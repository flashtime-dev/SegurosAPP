<?php

namespace Database\Seeders;

use App\Models\Compania;
use App\Models\Comunidad;
use App\Models\Permiso;
use App\Models\Poliza;
use App\Models\RolPermiso;
use App\Models\Subusuario;
use App\Models\TelefonoCompania;
use App\Models\TipoPermiso;
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
            //Datos de prueba
            RolSeeder::class,
            UserSeeder_Prueba::class,
            ComunidadSeeder_Prueba::class,
            CompaniaSeeder::class,
            AgenteSeeder_Prueba::class,
            PolizaSeeder_Prueba::class,
            SubUsuariosSeeder_Prueba::class,
            UsuarioComunidadSeeder_Prueba::class,
            SiniestrosSeeder_Prueba::class,
            ContactosSeeder_Prueba::class,
            TipoPermisoSeeder::class,
            PermisoSeeder::class,
            RolPermisoSeeder::class,
            TelefonoCompaniaSeeder::class,
            ChatPolizaSeeder_Prueba::class,
            ChatSiniestroSeeder_Prueba::class,

            // Aquí van los datos reales

            // RolSeeder::class,
            // UserSeeder::class,
            // ComunidadSeeder::class,
            // CompaniaSeeder::class,
            // AgenteSeeder::class,
            // PolizaSeeder::class,
            // SubUsuariosSeeder::class,
            // UsuarioComunidadSeeder::class,
            // SiniestrosSeeder::class,
            // ContactosSeeder::class,
            // TipoPermisoSeeder::class,
            // PermisoSeeder::class,
            // RolPermisoSeeder::class,
            // TelefonoCompaniaSeeder::class,
        ]);
    }
}
