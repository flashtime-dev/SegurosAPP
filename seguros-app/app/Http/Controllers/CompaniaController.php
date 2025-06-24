<?php

namespace App\Http\Controllers;

use App\Models\Compania;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;
use Throwable;

// Este controlador maneja las operaciones relacionadas con las compañías de seguros
// y sus teléfonos de asistencia.
class CompaniaController extends Controller
{
    // Metodo para mostrar la lista de compañías de seguros y sus teléfonos de asistencia.
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
            ]);
        } catch (Throwable $e) {
            Log::error('❌ Error al cargar los teléfonos de asistencia:', [
                'exception' => $e,
            ]);

            // Redirigir a una página de error o mostrar un mensaje amigable
            return redirect()->back()->with([
                'error' => [
                    'id' => uniqid(),
                    'mensaje' => "Error al cargar los teléfonos",
                ],
            ]);
        }
    }
}
