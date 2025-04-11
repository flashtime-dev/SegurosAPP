<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    use HasFactory;

    protected $table = 'roles';

    protected $fillable = [
        'nombre'
    ];

    // Relación muchos a muchos con Permiso
    public function permisos()
    {
        return $this->belongsToMany(
            Permiso::class,   // 1. Modelo relacionado
            'roles_permisos', // 2. Nombre de la tabla pivote
            'id_rol',          // 3. Clave foránea del modelo actual (Rol) en la tabla pivote
            'id_permiso'       // 4. Clave foránea del modelo relacionado (Permiso) en la tabla pivote
        )->withTimestamps();  // 5. Agrega las columnas created_at y updated_at a la tabla pivote
    }

    // Relación: Un Rol tiene muchos Usuarios
    public function users()
    {
        return $this->hasMany(User::class, 'id_rol', 'id');
    }
}
