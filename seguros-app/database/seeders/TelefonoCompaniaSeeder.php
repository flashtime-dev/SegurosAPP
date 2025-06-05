<?php

namespace Database\Seeders;

use App\Models\TelefonoCompania;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TelefonoCompaniaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Crear telefonos de compañias
        TelefonoCompania::create([
            'id_compania' => 1,
            'descripcion' => 'Asistencia',
            'telefono' => '+34638930465'
        ]);
        TelefonoCompania::create([
            'id_compania' => 1,
            'descripcion' => 'Allianz',
            'telefono' => '+34913255258'
        ]);
        TelefonoCompania::create([
            'id_compania' => 1,
            'descripcion' => 'Diversos',
            'telefono' => '+34900103080'
        ]);
        TelefonoCompania::create([
            'id_compania' => 2,
            'descripcion' => 'Asistencia',
            'telefono' => '+34917286710'
        ]);
        TelefonoCompania::create([
            'id_compania' => 3,
            'descripcion' => 'Asistencia',
            'telefono' => '+34915955455'
        ]);
        TelefonoCompania::create([
            'id_compania' => 4,
            'descripcion' => 'Asistencia',
            'telefono' => '+34912721923'
        ]);
        TelefonoCompania::create([
            'id_compania' => 5,
            'descripcion' => 'Asistencia',
            'telefono' => '+34913601423'
        ]);
        TelefonoCompania::create([
            'id_compania' => 6,
            'descripcion' => 'Asistencia',
            'telefono' => '+34900243657'
        ]);
        TelefonoCompania::create([
            'id_compania' => 7,
            'descripcion' => 'Asistencia',
            'telefono' => '+34913939057'
        ]);
        TelefonoCompania::create([
            'id_compania' => 7,
            'descripcion' => 'Control de Plagas',
            'telefono' => '+34917697258'
        ]);
        TelefonoCompania::create([
            'id_compania' => 8,
            'descripcion' => 'Asistencia',
            'telefono' => '+34918365365'
        ]);
        TelefonoCompania::create([
            'id_compania' => 9,
            'descripcion' => 'Asistencia',
            'telefono' => '+34917572404'
        ]);
        TelefonoCompania::create([
            'id_compania' => 10,
            'descripcion' => 'Asistencia',
            'telefono' => '+34918271530'
        ]);
        TelefonoCompania::create([
            'id_compania' => 11,
            'descripcion' => 'Asistencia',
            'telefono' => '+34917039009'
        ]);
        TelefonoCompania::create([
            'id_compania' => 12,
            'descripcion' => 'Asistencia',
            'telefono' => '+34932220212'
        ]);
        TelefonoCompania::create([
            'id_compania' => 13,
            'descripcion' => 'Asistencia',
            'telefono' => '+34915200500'
        ]);
        TelefonoCompania::create([
            'id_compania' => 14,
            'descripcion' => 'Asistencia',
            'telefono' => '+34900203010'
        ]);
        TelefonoCompania::create([
            'id_compania' => 14,
            'descripcion' => 'Asistencia Fijo',
            'telefono' => '+34915160500'
        ]);
        TelefonoCompania::create([
            'id_compania' => 15,
            'descripcion' => 'Asistencia',
            'telefono' => '+34914547400'
        ]);
        TelefonoCompania::create([
            'id_compania' => 16,
            'descripcion' => 'Asistencia',
            'telefono' => '+34900242020'
        ]);
        TelefonoCompania::create([
            'id_compania' => 17,
            'descripcion' => 'Asistencia',
            'telefono' => '+34934165046'
        ]);
        TelefonoCompania::create([
            'id_compania' => 18,
            'descripcion' => 'Asistencia',
            'telefono' => '+34911774558'
        ]);
    }
}
