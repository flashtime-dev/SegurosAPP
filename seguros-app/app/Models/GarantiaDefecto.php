<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GarantiaDefecto extends Model
{
    use HasFactory; // Sirve para usar los factories de laravel

    protected $table = 'garantias_defectos'; // Nombre de la tabla en la base de datos

    protected $fillable = [ // Atributos que se pueden asignar masivamente
        'id_compania',
        'incendio',
        'danios_electricos',
        'robo',
        'cristales',
        'agua_comun',
        'agua_privada',
        'danios_esteticos_comunes',
        'danios_esteticos_privados',
        'rc_danios_agua',
        'filtraciones',
        'desatascos',
        'fontaneria_sin_danios',
        'averia_maquinaria',
        'control_plagas',
        'defensa_juridica',
        'tiene_api',
        'franquicia',
        'requiere_peritacion',
        'observaciones'
    ];

    protected $casts = [ // Casts para los atributos
        'agua_comun' => 'boolean',
        'agua_privada' => 'boolean',
        'rc_danios_agua' => 'boolean',
        'tiene_api' => 'boolean',
        'requiere_peritacion' => 'boolean',
    ];

    // Relación uno a uno: Una GarantiaDefecto pertenece a una Compañía
    public function compania()
    {
        return $this->belongsTo(Compania::class, 'id_compania', 'id');
    }
}
