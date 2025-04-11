<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permiso extends Model
{
    use HasFactory; // Sirve para usar los factories de laravel
    protected $table = 'permisos'; // Nombre de la tabla en la base de datos

    protected $fillable = [ // Atributos que se pueden asignar masivamente
        'id_tipo',
        'nombre'
    ];

    // Relación inversa: Un Permiso pertenece a un TipoPermiso
    public function tipoPermiso()
    {
        return $this->belongsTo(TipoPermiso::class, 'id_tipo', 'id');
    }

    // Relación muchos a muchos con Rol
    public function roles()
    {
        return $this->belongsToMany(Rol::class, 'roles_permisos', 'id_permiso', 'id_rol');
    }
}
