<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TipoReparador extends Model
{
    use HasFactory; // Sirve para usar los factories de laravel

    protected $table = 'tipos_permisos'; // Nombre de la tabla en la base de datos

    protected $fillable = [ // Atributos que se pueden asignar masivamente
        'nombre',
    ];

    // Relacion 1:N: muchos reparadores son de un mismo reparador
    public function reparadores()
    {
        return $this->hasMany(Reparador::class, 'id_tipo_reparador', 'id');
    }
}
