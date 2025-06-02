<?php

namespace App\Http\Controllers;

use App\Models\Compania;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class CompaniaController extends Controller
{
    public function telefonosAsistencia()
    {
        try {
            // Obtener todas las compañías con sus teléfonos
            $companias = Compania::with('telefonos')->get();

            Log::info('✅ Teléfonos de asistencia cargados correctamente.', [
                'total_companias' => $companias->count()
            ]);

            // Pasar los datos a la vista
            return Inertia::render('telefonos-asistencia', [
                'companias' => $companias,
                'success' => 'Teléfonos de asistencia cargados correctamente.',
            ]);
        } catch (Throwable $e) {
            Log::error('❌ Error al cargar los teléfonos de asistencia:', [
                'exception' => $e,
            ]);

            // Puedes redirigir a una página de error o mostrar un mensaje amigable
            return redirect()->back()->withErrors([
                'general' => 'Ocurrió un error al cargar los teléfonos de asistencia. Intenta más tarde.',
            ]);
        }
    }
}
