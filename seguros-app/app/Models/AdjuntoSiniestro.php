<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdjuntoSiniestro extends Model
{
    use HasFactory;
    
    protected $table = 'adjuntos_siniestros'; // Nombre de la tabla en la base de datos
    
    protected $fillable = [ // Atributos que se pueden asignar masivamente
        'id_siniestro',
        'id_chat',
        'nombre',
        'url_adjunto',
    ];

    // Relación uno a muchos: Un AdjuntoSiniestro pertenece a un Siniestro
    public function siniestro()
    {
        return $this->belongsTo(Siniestro::class, 'id_siniestro', 'id');
    }

    // Relación uno a muchos: Un AdjuntoSiniestro pertenece a un ChatSiniestro
    public function chat()
    {
        return $this->belongsTo(ChatSiniestro::class, 'id_chat', 'id');
    }
    //
}
