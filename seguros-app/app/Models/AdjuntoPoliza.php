<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdjuntoPoliza extends Model
{
    use HasFactory;
    
    protected $table = 'adjuntos_polizas'; // Nombre de la tabla en la base de datos

    protected $fillable = [ // Atributos que se pueden asignar masivamente
        'id_chat',
        'nombre',
        'url_adjunto',
    ];

    // Relación uno a muchos: Un AdjuntoPoliza pertenece a un ChatPoliza
    public function chat()
    {
        return $this->belongsTo(ChatPoliza::class, 'id_chat', 'id');
    }
}
