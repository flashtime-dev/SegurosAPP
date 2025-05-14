<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contacto extends Model
{
    use HasFactory; // Sirve para usar los factories de laravel

    protected $table = 'contactos'; // Nombre de la tabla en la base de datos

    protected $fillable = [
        'id_siniestro',
        'nombre',
        'cargo',
        'piso',
        'telefono',
    ];

    // Relación uno a muchos: Un Contacto pertenecen a una Comunidad
    public function siniestro()
    {
        return $this->belongsTo(Siniestro::class, 'id_siniestro', 'id');
    }
}
