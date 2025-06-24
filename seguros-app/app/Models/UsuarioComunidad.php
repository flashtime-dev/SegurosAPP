<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsuarioComunidad extends Model
{
    protected $table = 'usuarios_comunidades';

    protected $fillable = [
        'id_usuario',
        'id_comunidad'
    ];

}
