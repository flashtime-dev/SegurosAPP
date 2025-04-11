<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comunidad extends Model
{
    use HasFactory; // Sirve para usar los factories de laravel

    protected $table = 'comunidades'; // Nombre de la tabla en la base de datos

    protected $fillable = [ // Atributos que se pueden asignar masivamente
        'nombre',
        'cif',
        'direccion',
        'ubi_catastral',
        'ref_catastral',
        'telefono',
        'descripcion',
    ];

    // Relación uno a muchos: Una Comunidad tiene muchos Usuarios
    public function users()
    {
        return $this->belongsToMany(
            User::class,       // Modelo relacionado
            'usuarios_comunidades', // Nombre de la tabla intermedia
            'id_comunidad',    // Clave foránea del modelo actual (Comunidad) en la tabla intermedia
            'id_usuario'       // Clave foránea del modelo relacionado (User) en la tabla intermedia
        )->withTimestamps(); // Incluye created_at y updated_at en la tabla intermedia
    }

    // Relación uno a uno: Una Comunidad tiene una Caracteristica
    public function caracteristica()
    {
        return $this->hasOne(Caracteristica::class, 'id_comunidad', 'id');
    }

    // Relación uno a muchos: Una Comunidad tiene muchos Contactos
    public function contactos()
    {
        return $this->hasMany(Contacto::class, 'id_comunidad', 'id');
    }

    // Relación uno a muchos: Una Comunidad tiene muchos presupuestos
    public function presupuestos()
    {
        return $this->hasMany(Presupuesto::class, 'id_comunidad', 'id');
    }

    // Relación uno a muchos: Una Comunidad tiene muchas Polizas
    public function polizas()
    {
        return $this->hasMany(Poliza::class, 'id_comunidad', 'id');
    }
    
}
