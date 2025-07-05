<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Solicitud extends Model
{
    use HasFactory; // Sirve para usar los factories de laravel

    protected $table = 'solicitudes'; // Nombre de la tabla en la base de datos

    protected $fillable = [ // Atributos que se pueden asignar masivamente
        'id_comunidad', // Clave foránea del propietario
        'titulo',
        'descripcion',
        'direccion',
        'fecha',
        'estado',
    ];

    // Relacion 1:N: Una solicitud pertenece a una comunidas
    public function comunidad()
    {
        return $this->belongsTo(Comunidad::class, 'id_comunidad', 'id');
    }

    // Relacion N:M: Una solicitud puede tener muchos reparadores
    public function reparadores()
    {
        return $this->belongsToMany(
            Reparador::class,         // Modelo relacionado
            'reparador_solicitud',    // Tabla pivot
            'id_solicitud',           // Clave foránea de Solicitud en la pivot
            'id_reparador'            // Clave foránea de Reparador en la pivot
        )->withTimestamps();          // Si la tabla pivot tiene created_at y updated_at
    }
}
