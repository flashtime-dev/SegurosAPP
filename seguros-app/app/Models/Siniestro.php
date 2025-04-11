<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siniestro extends Model
{
    use HasFactory;

    protected $table = 'siniestros'; // Nombre de la tabla en la base de datos

    protected $fillable = [ // Atributos que se pueden asignar masivamente
        'id_poliza',
        'declaracion',
        'tramitador',
        'expediente',
        'exp_cia',
        'exp_asist',
        'fecha_ocurrencia',
        'adjunto',
    ];

    protected $casts = [ // Casts para los atributos
        'fecha_ocurrencia' => 'date', // Convierte a fecha
        'adjunto' => 'boolean', // Convierte a booleano
    ];

    // Relación uno a muchos: Un Siniestro pertenece a una Poliza
    public function poliza()
    {
        return $this->belongsTo(Poliza::class, 'id_poliza', 'id');
    }

    // Relación muchos a muchos: Un Siniestro pertenece a muchos Contactos
    public function contactos()
    {
        return $this->belongsToMany(Contacto::class, 'siniestros_contactos', 'id_siniestro', 'id_contacto');
    }

    // Relación uno a muchos: Un Siniestro tiene muchos AdjuntoSiniestro
    public function adjuntos()
    {
        return $this->hasMany(AdjuntoSiniestro::class, 'id_siniestro', 'id');
    }
    
    // Relación uno a muchos: Un Siniestro tiene muchos ChatSiniestro
    public function chats()
    {
        return $this->hasMany(ChatSiniestro::class, 'id_siniestro', 'id');
    }
}
