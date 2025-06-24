<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatSiniestro extends Model
{
    use HasFactory;

    protected $table = 'chats_siniestros'; // Nombre de la tabla en la base de datos

    protected $fillable = [ // Atributos que se pueden asignar masivamente
        'id_siniestro',
        'id_usuario',
        'mensaje',
        'adjunto',
    ];

    protected $casts = [ // Casts para los atributos
        'adjunto' => 'boolean', // Convierte a booleano
    ];

    // Relación uno a muchos: Un ChatSiniestro pertenece a un Siniestro
    public function siniestro()
    {
        return $this->belongsTo(Siniestro::class, 'id_siniestro', 'id');
    }

    // Relación uno a muchos: Un ChatSiniestro pertenece a un Usuario
    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario', 'id');
    }
    
    // Relación uno a muchos: Un ChatSiniestro tiene muchos AdjuntoSiniestro
    public function adjuntos()
    {
        return $this->hasMany(AdjuntoSiniestro::class, 'id_chat', 'id');
    }
}
