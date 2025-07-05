<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Poliza extends Model
{
    use HasFactory; // Sirve para usar los factories de laravel

    protected $table = 'polizas'; // Nombre de la tabla en la base de datos

    protected $fillable = [ // Atributos que se pueden asignar masivamente
        'id_compania',
        'id_comunidad',
        'id_agente',
        'alias',
        'numero',
        'fecha_efecto',
        'cuenta',
        'forma_pago',
        'prima_neta',
        'prima_total',
        'pdf_poliza',
        'observaciones',
        'estado',
    ];

    protected $casts = [ // Casts para los atributos
        'fecha_efecto' => 'date', // Convierte a fecha
        'prima_neta' => 'float', // Convierte a float
        'prima_total' => 'float', // Convierte a float
    ];

    // Relación uno a muchos: Una Poliza pertenece a una Compañía
    public function compania()
    {
        return $this->belongsTo(Compania::class, 'id_compania', 'id');
    }

    // Relación uno a muchos: Una Poliza pertenece a una Comunidad
    public function comunidad()
    {
        return $this->belongsTo(Comunidad::class, 'id_comunidad', 'id');
    }

    // Relación uno a muchos: Una Poliza pertenece a un Agente
    public function agente()
    {
        return $this->belongsTo(Agente::class, 'id_agente', 'id');
    }

    // Relación uno a muchos: Una Poliza tiene muchos recibos
    public function recibos()
    {
        return $this->hasMany(Recibo::class, 'id_poliza', 'id');
    }

    // Relación uno a muchos: Una Poliza tiene muchos siniestros
    public function siniestros()
    {
        return $this->hasMany(Siniestro::class, 'id_poliza', 'id');
    }

    // Relación uno a muchos: Una Poliza tiene muchos chats
    public function chats()
    {
        return $this->hasMany(ChatPoliza::class, 'id_poliza', 'id');
    }

    // Relación muchos a muchos: Una Poliza tiene muchos Usuarios
    public function usuarios()
    {
        return $this->belongsToMany(
            User::class,       // Modelo relacionado
            'usuarios_polizas', // Nombre de la tabla intermedia
            'id_poliza',    // Clave foránea del modelo actual (Comunidad) en la tabla intermedia
            'id_usuario'       // Clave foránea del modelo relacionado (User) en la tabla intermedia
        )->withTimestamps(); // Incluye created_at y updated_at en la tabla intermedia
    }
    
}
