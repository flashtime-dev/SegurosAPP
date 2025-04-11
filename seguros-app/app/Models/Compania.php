<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Compania extends Model
{
    use HasFactory; // Sirve para usar los factories de laravel

    protected $table = 'companias'; // Nombre de la tabla en la base de datos

    protected $fillable = [ // Atributos que se pueden asignar masivamente
        'nombre',
        'url_logo',
    ];

    // Relación uno a muchos: Una Compañía tiene muchos DesgloseComparativo
    public function desgloseComparativo()
    {
        return $this->hasMany(DesgloseComparativo::class, 'id_compania', 'id');
    }

    // Relación uno a muchos: Una Compañía tiene una GarantiaDefecto
    public function garantiaDefecto()
    {
        return $this->hasOne(GarantiaDefecto::class, 'id_compania', 'id');
    }
    
    // Relación uno a muchos: Una Compañía tiene muchas Polizas
    public function polizas()
    {
        return $this->hasMany(Poliza::class, 'id_compania', 'id');
    }

    // Relación uno a muchos: Una Compañía tiene muchos TelefonoCompania
    public function telefonos()
    {
        return $this->hasMany(TelefonoCompania::class, 'id_compania', 'id');
    }
}
