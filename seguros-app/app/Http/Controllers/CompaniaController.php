<?php

namespace App\Http\Controllers;

use App\Models\Compania;
use Inertia\Inertia;
use Illuminate\Http\Request;

class CompaniaController extends Controller
{
    public function telefonosAsistencia()
    {
        // Obtener todas las compañías con sus teléfonos
        $companias = Compania::with('telefonos')->get();

        // Pasar los datos a la vista
        return Inertia::render('telefonos-asistencia', [
            'companias' => $companias,
        ]);
    }
}
