<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TelefonoCompania extends Model
{
    use HasFactory; // Sirve para usar los factories de laravel

    protected $table = 'telefonos_companias'; // Nombre de la tabla en la base de datos
    
    protected $fillable = [ // Atributos que se pueden asignar masivamente
        'id_compania',
        'telefono',
        'descripcion',
    ];

    // Relación inversa: Un TelefonoCompania pertenece a una Compania
    public function compania()
    {
        return $this->belongsTo(Compania::class, 'id_compania', 'id');
    }
}
