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

    // Relacion N:M: Un contacto puede formar parte de muchos siniestros
    public function siniestros()
    {
        return $this->belongsToMany(
            Siniestro::class,         // Modelo relacionado
            'contacto_siniestro',     // Tabla pivot
            'id_contacto',            // Clave foránea de Contacto en la pivot
            'id_siniestro'            // Clave foránea de Siniestro en la pivot
        )->withTimestamps();          // Si la tabla pivot tiene created_at y updated_at
    }
}
