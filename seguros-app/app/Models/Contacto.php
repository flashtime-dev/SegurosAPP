<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contacto extends Model
{
    use HasFactory; // Sirve para usar los factories de laravel
    
    protected $table = 'contactos'; // Nombre de la tabla en la base de datos

    protected $fillable = [
        'id_comunidad',
        'nombre',
        'cargo',
        'piso',
        'telefono',
    ];

    // Relación uno a muchos: Un Contacto pertenecen a una Comunidad
    public function comunidad()
    {
        return $this->belongsTo(Comunidad::class, 'id_comunidad', 'id');
    }

    // Relación muchos a muchos: Un Contacto pertenece a muchos siniestro
    public function siniestros()
    {
        return $this->belongsToMany(Siniestro::class, 'siniestros_contactos', 'id_contacto', 'id_siniestro');
    }
}
