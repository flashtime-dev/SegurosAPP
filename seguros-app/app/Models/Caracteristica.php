<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Caracteristica extends Model
{
    use HasFactory; // Sirve para usar los factories de laravel
    
    protected $table = 'caracteristicas'; // Nombre de la tabla en la base de datos

    protected $fillable = [ // Atributos que se pueden asignar masivamente
        'id_comunidad',
        'tipo',
        'num_plantas',
        'num_viviendas',
        'ascensores',
        'piscina',
        'num_plantas_sotano',
        'num_locales',
        'garajes',
        'num_edificios',
        'num_oficinas',
        'pista_deportiva',
        'm2_urbanizacion',
        'anio_construccion',
        'metros_construidos',
        'fecha_renovacion'
    ];

    protected $casts = [
        'num_plantas' => 'integer',
        'num_viviendas' => 'integer',
        'ascensores' => 'boolean',
        'piscina' => 'boolean',
        'num_plantas_sotano' => 'integer',
        'num_locales' => 'integer',
        'garajes' => 'boolean',
        'num_edificios' => 'integer',
        'num_oficinas' => 'integer',
        'pista_deportiva' => 'boolean',
        'm2_urbanizacion' => 'integer',
        'anio_construccion' => 'integer',
        'metros_construidos' => 'integer',
        'fecha_renovacion' => 'date',
    ];

    // Relación inversa: Una Caracteristica pertenece a una Comunidad
    public function comunidad()
    {
        return $this->belongsTo(Comunidad::class, 'id_comunidad', 'id');
    }
}
