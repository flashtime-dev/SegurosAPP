<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agente extends Model
{
    use HasFactory; // Sirve para usar los factories de laravel

    protected $table = 'agentes'; // Nombre de la tabla en la base de datos
    
    protected $fillable = [ // Atributos que se pueden asignar masivamente
        'nombre',
        'telefono',
        'email',
    ];

    //relacion uno a muchos: Un Agente tiene muchas Polizas
    public function polizas()
    {
        return $this->hasMany(Poliza::class, 'id_agente', 'id');
    }
}
