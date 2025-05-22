<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subusuario extends Model
{
    use HasFactory; // Sirve para usar los factories de laravel

    protected $table = 'subusuarios'; // Nombre de la tabla en la base de datos

    protected $fillable = [ // Atributos que se pueden asignar masivamente
        'id',
        'id_usuario_creador'
    ];

    // Relación inversa: Un Subusuario pertenece a un Usuario
    public function usuarioCreador()
    {
    return $this->belongsTo(User::class, 'id_usuario_creador', 'id');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'id', 'id');
    }
}
