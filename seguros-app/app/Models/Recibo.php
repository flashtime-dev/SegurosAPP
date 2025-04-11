<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recibo extends Model
{
    use HasFactory; // Sirve para usar los factories de laravel

    protected $table = 'recibos'; // Nombre de la tabla en la base de datos

    protected $fillable = [ // Atributos que se pueden asignar masivamente
        'id_poliza',
        'num_recibo',
        'fecha_recibo',
        'estado',
        'prima_neta',
        'prima_total',
        'observaciones',
    ];

    protected $casts = [ // Casts para los atributos
        'fecha_recibo' => 'date', // Convierte a fecha
        'prima_neta' => 'decimal:2', // Convierte a decimal con 2 decimales
        'prima_total' => 'decimal:2', // Convierte a decimal con 2 decimales
    ];

    // Relación uno a muchos: Un Recibo pertenece a una Poliza
    public function poliza()
    {
        return $this->belongsTo(Poliza::class, 'id_poliza', 'id');
    }
}
