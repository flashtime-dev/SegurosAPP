<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siniestro extends Model
{
    use HasFactory;

    protected $table = 'siniestros'; // Nombre de la tabla en la base de datos

    protected $fillable = [ // Atributos que se pueden asignar masivamente
        'id_poliza',
        'declaracion',
        'expediente',
        'exp_cia',
        'exp_asist',
        'fecha_ocurrencia',
        'adjunto',
        'estado',
        'tipologia',
        'urgencia'
    ];

    protected $casts = [ // Casts para los atributos
        'fecha_ocurrencia' => 'date', // Convierte a fecha
        'adjunto' => 'boolean', // Convierte a booleano
    ];

    // Relación uno a muchos: Un Siniestro pertenece a una Poliza
    public function poliza()
    {
        return $this->belongsTo(Poliza::class, 'id_poliza', 'id');
    }

    // Relacion N:M: un siniestro puede tener muchos contactos
    public function contactos()
    {
        return $this->belongsToMany(
            Contacto::class,           // Modelo relacionado
            'contacto_siniestro',      // Tabla pivot
            'id_siniestro',            // Clave foránea de Siniestro en la pivot
            'id_contacto'              // Clave foránea de Contacto en la pivot
        )->withTimestamps();           // Si la tabla pivot tiene created_at y updated_at
    }

    // Relación uno a muchos: Un Siniestro tiene muchos AdjuntoSiniestro
    public function adjuntos()
    {
        return $this->hasMany(AdjuntoSiniestro::class, 'id_siniestro', 'id');
    }

    // Relación uno a muchos: Un Siniestro tiene muchos ChatSiniestro
    public function chats()
    {
        return $this->hasMany(ChatSiniestro::class, 'id_siniestro', 'id');
    }

    // Relación uno a muchos: Una Comunidad tiene muchos Usuarios
    public function usuarios()
    {
        return $this->belongsToMany(
            User::class,       // Modelo relacionado
            'usuarios_siniestros', // Nombre de la tabla intermedia
            'id_siniestro',    // Clave foránea del modelo actual (Comunidad) en la tabla intermedia
            'id_usuario'       // Clave foránea del modelo relacionado (User) en la tabla intermedia
        )->withTimestamps(); // Incluye created_at y updated_at en la tabla intermedia
    }
}
