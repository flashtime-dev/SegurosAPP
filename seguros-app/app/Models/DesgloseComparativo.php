<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DesgloseComparativo extends Model
{
    use HasFactory; // Sirve para usar los factories de laravel
    
    protected $table = 'desglose_comparativo'; // Nombre de la tabla en la base de datos

    protected $fillable = [ // Atributos que se pueden asignar masivamente
        'id_compania',
        'id_presupuesto',
        'incendio',
        'daños_electricos',
        'robo',
        'cristales',
        'agua_comun',
        'agua_privada',
        'danios_esteticos_comunes',
        'danios_esteticos_privados',
        'rc_daños_agua',
        'filtraciones',
        'desatascos',
        'fontaneria_sin_danios',
        'averia_maquinaria',
        'control_plagas',
        'defensa_juridica',
        'tiene_api',
        'franquicia',
        'requiere_peritacion',
        'observaciones',
        'prima_total',
        'capital_rc',
        'capital_ctdo',
        'capital_cte',
        'pdf_desglose',
    ];

    protected $casts = [
        'agua_comun' => 'boolean',
        'agua_privada' => 'boolean',
        'rc_daños_agua' => 'boolean',
        'tiene_api' => 'boolean',
        'requiere_peritacion' => 'boolean',
        'prima_total' => 'decimal:2',
        'danios_esteticos_comunes' => 'integer',
        'danios_esteticos_privados' => 'integer',
        'capital_rc' => 'integer',
        'capital_ctdo' => 'integer',
        'capital_cte' => 'integer',
    ];

    // Relación inversa: Un DesgloseComparativo pertenece a un Presupuesto
    public function presupuesto()
    {
        return $this->belongsTo(Presupuesto::class, 'id_presupuesto', 'id');
    }

    // Relación inversa: Un DesgloseComparativo pertenece a una Compañía
    public function compania()
    {
        return $this->belongsTo(Compania::class, 'id_compania', 'id');
    }
}
