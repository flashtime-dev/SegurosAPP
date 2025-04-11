<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoPermiso extends Model
{
    use HasFactory; // Sirve para usar los factories de laravel
    
    protected $table = 'tipos_permisos'; // Nombre de la tabla en la base de datos

    protected $fillable = [ // Atributos que se pueden asignar masivamente
        'nombre'
    ];

    // Relación uno a muchos: Un TipoPermiso tiene muchos Permisos
    public function permisos()
    {
        return $this->hasMany(
            Permiso::class, // 1. Modelo relacionado
            'id_tipo',      // 2. Clave foránea en la tabla del modelo relacionado (permiso)
            'id'            // 3. Clave primaria del modelo actual
        );
    }

}
