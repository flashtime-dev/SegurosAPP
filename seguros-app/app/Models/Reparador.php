<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Reparador extends Model
{
    use HasFactory; // Sirve para usar los factories de laravel

    protected $table = 'reparadores'; // Nombre de la tabla en la base de datos

    protected $fillable = [ // Atributos que se pueden asignar masivamente
        'id_tipo_reparador', // Clave foránea del propietario
        'nombre',
        'telefono',
        'direccion',
        'email',
    ];

    // Relacion N:M: un reparador puede tener muchas solicitudes
    public function solicitudes()
    {
        return $this->belongsToMany(
            Solicitud::class,         // Modelo relacionado
            'reparador_solicitud',    // Tabla pivot
            'id_reparador',           // Clave foránea de Reparador en la pivot
            'id_solicitud'            // Clave foránea de Solicitud en la pivot
        )->withTimestamps();          // Si la tabla pivot tiene created_at y updated_at
    }

    // Relacion 1:N: un reparador es un tipo de reparador
    public function tipo()
    {
        return $this->belongsTo(TipoReparador::class, 'id_tipo_reparador', 'id');
    }
}
