<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presupuesto extends Model
{
    use HasFactory; // Sirve para usar los factories de laravel
    
    protected $table = 'presupuestos'; // Nombre de la tabla en la base de datos

    protected $fillable = [
        'id_comunidad',
        'fecha',
        'observaciones',
        'comentarios',
        'pdf_presupuesto',
    ];

    // Relación inversa: Un Presupuesto pertenece a una Comunidad
    public function comunidad()
    {
        return $this->belongsTo(Comunidad::class, 'id_comunidad', 'id');
    }

    // Relación uno a muchos: Un Presupuesto tiene muchos DesglosesComparativos
    public function desglosesComparativos()
    {
        return $this->hasMany(DesgloseComparativo::class, 'id_presupuesto', 'id');
    }
}
