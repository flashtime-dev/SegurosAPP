<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RolPermiso extends Model
{
    protected $table = 'roles_permisos';

    protected $fillable = [
        'id_rol',
        'id_permiso',
    ];

    public function rol()
    {
        return $this->belongsTo(Rol::class);
    }

    public function permiso()
    {
        return $this->belongsTo(Permiso::class);
    }
}
