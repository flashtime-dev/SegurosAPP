<?php

namespace App\Http\Controllers;

use App\Models\Compania;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;
use App\Http\Middleware\CheckPermiso;
use Illuminate\Http\Request;

use Throwable;

// Este controlador maneja las operaciones relacionadas con las compañías de seguros
// y sus teléfonos de asistencia.
class CompaniaController extends Controller
{
    public function __construct()
    {
        // Aplica middleware de permisos a métodos específicos

        //Usuarios y Empleados
        $this->middleware(CheckPermiso::class . ':compania.ver', ['only' => ['index']]);
        $this->middleware(CheckPermiso::class . ':compania.crear', ['only' => ['store']]);
        $this->middleware(CheckPermiso::class . ':compania.editar', ['only' => ['update']]);
        $this->middleware(CheckPermiso::class . ':compania.eliminar', ['only' => ['destroy']]);
    }

    /**
     * Muestra la lista de todos los usuarios.
     */
    public function index()
    {
        try {
            // Obtener todas las compañías con sus teléfonos
            $companias = Compania::with('telefonos')->get();

            // Pasar los datos a la vista
            return Inertia::render('companias/index', [
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

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|min:2|max:255',
            'url_logo' => 'required|string|max:255',
            'telefonos' => 'array',
            'telefonos.*.telefono' => 'nullable|phone:ES,US,FR,GB,DE,IT,PT,MX,AR,BR,INTL',
            'telefonos.*.descripcion' => 'required|string|min:2|max:255',
        ],[
            'nombre.min' => 'El nombre debe tener al menos 2 caracteres',
            'nombre.max' => 'El nombre no puede exeder los 255 caracters',
            'nombre.required' => 'El nombre es obligatorio',
            'url_logo.required' => 'La URL del logo es obligatoria',
            'url_logo.max' => 'La URL del logo no puede exeder los 255 caracteres',
            'telefonos.*.telefono' => 'Formato de teléfono incorrecto',
            'telefonos.*.descripcion.required' => 'La descripción del teléfono es obligatoria',
            'telefonos.*.descripcion.min' => 'La descripción del teléfono debe tener al menos 2 caracteres',
            'telefonos.*.descripcion.max' => 'La descripción del teléfono no puede exeder los 255 caracteres',
        ]);

        try {
            $compania = Compania::create([
                'nombre' => $validated['nombre'],
                'url_logo' => $validated['url_logo'] ?? null,
            ]);

            // Guardar teléfonos
            if (!empty($validated['telefonos'])) {
                foreach ($validated['telefonos'] as $tel) {
                    $compania->telefonos()->create($tel);
                }
            }

            return redirect()->route('companias.index')->with([
                'success' => [
                    'id' => uniqid(),
                    'mensaje' => "Compañía creada correctamente",
                ],
            ]);
        } catch (Throwable $e) {
            Log::error('❌ Error al crear compañía:', ['exception' => $e]);
            return redirect()->back()->with([
                'error' => [
                    'id' => uniqid(),
                    'mensaje' => "Error al crear la compañía",
                ],
            ])->withInput();
        }
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|min:2|max:255',
            'url_logo' => 'required|string|max:255',
            'telefonos' => 'array',
            'telefonos.*.telefono' => 'nullable|phone:ES,US,FR,GB,DE,IT,PT,MX,AR,BR,INTL',
            'telefonos.*.descripcion' => 'required|string|min:2|max:255',
        ],[
            'nombre.min' => 'El nombre debe tener al menos 2 caracteres',
            'nombre.max' => 'El nombre no puede exeder los 255 caracters',
            'nombre.required' => 'El nombre es obligatorio',
            'url_logo.required' => 'La URL del logo es obligatoria',
            'url_logo.max' => 'La URL del logo no puede exeder los 255 caracteres',
            'telefonos.*.telefono' => 'Formato de teléfono incorrecto',
            'telefonos.*.descripcion.required' => 'La descripción del teléfono es obligatoria',
            'telefonos.*.descripcion.min' => 'La descripción del teléfono debe tener al menos 2 caracteres',
            'telefonos.*.descripcion.max' => 'La descripción del teléfono no puede exeder los 255 caracteres',
        ]);

        try {
            $compania = Compania::findOrFail($id);
            $compania->update([
                'nombre' => $validated['nombre'],
                'url_logo' => $validated['url_logo'] ?? null,
            ]);

            // Actualizar teléfonos: eliminar todos y volver a crear
            $compania->telefonos()->delete();
            if (!empty($validated['telefonos'])) {
                foreach ($validated['telefonos'] as $tel) {
                    $compania->telefonos()->create($tel);
                }
            }

            return redirect()->route('companias.index')->with([
                'success' => [
                    'id' => uniqid(),
                    'mensaje' => "Compañía actualizada correctamente",
                ],
            ]);
        } catch (Throwable $e) {
            Log::error('❌ Error al actualizar compañía:', ['exception' => $e]);
            return redirect()->back()->with([
                'error' => [
                    'id' => uniqid(),
                    'mensaje' => "Error al actualizar la compañía",
                ],
            ])->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $compania = Compania::findOrFail($id);
            $compania->telefonos()->delete();
            $compania->delete();
            return redirect()->route('companias.index')->with([
                'success' => [
                    'id' => uniqid(),
                    'mensaje' => "Compañía eliminada correctamente",
                ],
            ]);
        } catch (Throwable $e) {
            Log::error('❌ Error al eliminar compañía:', ['exception' => $e]);
            return redirect()->back()->with([
                'error' => [
                    'id' => uniqid(),
                    'mensaje' => "Error al eliminar la compañía",
                ],
            ]);
        }
    }

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
