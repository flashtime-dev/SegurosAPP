<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use App\Mail\CustomResetPasswordMail;
use Illuminate\Support\Facades\Mail;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'address',
        'phone',
        'state',
        'id_rol',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relación 1:N: Un Usuario tiene un Rol
    public function rol()
    {
        return $this->belongsTo(Rol::class, 'id_rol', 'id');
    }

    // Relación 1:N: Un Usuario tiene muchos Subusarios
    public function subusuarios()
    {
        return $this->hasMany(Subusuario::class, 'id_usuario_creador', 'id');
    }

    public function usuarioCreador()
    {
        return $this->hasOne(Subusuario::class, 'id', 'id');
    }

    // Relación N:N: Un Usuario pertenece a muchas Comunidades
    public function comunidades()
    {
        return $this->belongsToMany(
            Comunidad::class,  // Modelo relacionado
            'usuarios_comunidades', // Nombre de la tabla intermedia
            'id_usuario',      // Clave foránea del modelo actual (User) en la tabla intermedia
            'id_comunidad'     // Clave foránea del modelo relacionado (Comunidad) en la tabla intermedia
        )->withTimestamps(); // Incluye created_at y updated_at en la tabla intermedia
    }

    // Relación 1:N: Un Usuario escribe muchos ChatsPoliza
    public function chatsPoliza()
    {
        return $this->hasMany(ChatPoliza::class, 'id_usuario', 'id');
    }
    
    // Relación 1:N: Un Usuario escribe muchos ChatsSiniestro
    public function chatsSiniestro()
    {
        return $this->hasMany(ChatSiniestro::class, 'id_usuario', 'id');
    }

    /**
     * Sobrescribir el método para enviar el mail de reset personalizado.
     */
    public function sendPasswordResetNotification($token)
    {
        Mail::to($this->email)->send(new CustomResetPasswordMail($this, $token));
    }
}
