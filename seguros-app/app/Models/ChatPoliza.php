<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatPoliza extends Model
{
    use HasFactory;

    protected $table = 'chats_polizas'; // Nombre de la tabla en la base de datos

    protected $fillable = [ // Atributos que se pueden asignar masivamente
        'id_poliza',
        'id_usuario',
        'mensaje',
        'adjunto',
    ];

    protected $casts = [ // Casts para los atributos
        'adjunto' => 'boolean', // Convierte a booleano
    ];

    // Relación uno a muchos: Un ChatPoliza pertenece a una Poliza
    public function poliza()
    {
        return $this->belongsTo(Poliza::class, 'id_poliza', 'id');
    }

    // Relación uno a muchos: Un ChatPoliza tiene muchos AdjuntosPoliza
    public function adjuntos()
    {
        return $this->hasMany(AdjuntoPoliza::class, 'id_chat', 'id');
    }

    // Relación uno a muchos: Un ChatPoliza pertenece a un Usuario
    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario', 'id');
    }
}
