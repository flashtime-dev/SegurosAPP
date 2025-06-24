<?php

namespace App\Http\Controllers;

use App\Models\Agente;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;
use Throwable;
use App\Http\Middleware\CheckPermiso;

// Este controlador maneja las operaciones CRUD para los agentes
class AgenteController extends Controller
{
    public function __construct()
    {
        // Aplica middleware de permisos a métodos específicos

        //Agentes
        $this->middleware(CheckPermiso::class . ':agentes.ver', ['only' => ['index']]);
        $this->middleware(CheckPermiso::class . ':agentes.crear', ['only' => ['store']]);
        $this->middleware(CheckPermiso::class . ':agentes.editar', ['only' => ['update']]);
        $this->middleware(CheckPermiso::class . ':agentes.eliminar', ['only' => ['destroy']]);
    }
    /**
     * Metodo para mostrar la lista de agentes.
     */
    public function index()
    {
        try{
            $agentes = Agente::all(); // Obtener todos los agentes
            return Inertia::render('agentes/index', [
                'agentes' => $agentes, // Pasar los agentes a la vista
                'success' => session('success'), // Pasar el mensaje de éxito a la vista
                'error' => session('error'), // Pasar el mensaje de error a la vista
            ]); // Retornar la vista con los datos
        } catch (Throwable $e) {
            Log::error('❌ Error al obtener los agentes: ' . $e->getMessage(), [
                'exception' => $e,
            ]);

            return redirect()->back()->with([
                'error' => [
                    'id' => uniqid(),
                    'mensaje' => 'Error al cargar agentes',
                ]
            ]);
        }
    }


    /**
     * Metodo para guardar un nuevo agente.
     */
    public function store(Request $request)
    {
        // Modificar el nombre del agente para que comience con mayúscula
        $request->merge([
            'nombre' => ucfirst(($request->nombre))
        ]);

        // Validar los datos del formulario
        $request->validate([
            'nombre' => 'required|string|min:2|max:255',
            'telefono' => ['nullable', 'phone:ES,US,FR,GB,DE,IT,PT,MX,AR,BR,INTL'],
            'email' => 'nullable|string|email|max:255|unique:agentes,email|regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/i',
        ], [
            'nombre.min' => 'El nombre debe tener al menos 2 caracteres.',
            'nombre.max' => 'El nombre no puede exceder los 255 caracteres.',
            'nombre.required' => 'El nombre es obligatorio.',
            'email.unique' => 'El email ya está en uso.',
            'email.regex' => 'El formato del email es inválido.',
            'telefono' => 'Formato de teléfono incorrecto',
        ]);

        try {
            Agente::create($request->all()); // Crear un nuevo agente

            Log::info('✅ Agente creado correctamente.', [
                'agente_id' => $request->id,
                'nombre' => $request->nombre,
            ]);

            return redirect()->route('agentes.index')
            ->with([
                    'success' => [
                        'id' => uniqid(),
                        'mensaje' => "Agente creado correctamente",
                    ],
                ]);

        } catch (Throwable $e) {
            Log::error('❌ Error al crear el agente: ' . $e->getMessage(), [
                'exception' => $e,
                'data' => $request->all(),
            ]);

            return back()->with([
                    'error' => [
                        'id' => uniqid(),
                        'mensaje' => "Error al crear el agente",
                    ],
                ]);
            // ->withErrors(['error' => 'Ocurrió un error al crear el agente. Intentalo de nuevo.']);
        }
    }

    public function show($id)
    {
        //error 404 porque este método no está implementado
        return abort(404, 'Método no implementado');
    }

    /**
     * Metodo para actualizar un agente.
     */
    public function update(Request $request, $id)
    {
        // Buscar el agente por ID
        $agente = Agente::findOrFail($id);
        // Modificar el nombre del agente para que comience con mayúscula
        $request->merge([
            'nombre' => ucfirst(($request->nombre))
        ]);

        // Validar los datos del formulario
        $request->validate([
            'nombre' => 'required|string|min:2|max:255',
            'telefono' => ['nullable', 'phone:ES,US,FR,GB,DE,IT,PT,MX,AR,BR,INTL'],
            'email' => [
                'nullable',
                'string',
                'email',
                'max:255',
                'unique:agentes,email,' . $agente->id,
                'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/i',
            ],
        ], [
            'nombre.min' => 'El nombre debe tener al menos 2 caracteres.',
            'nombre.max' => 'El nombre no puede exceder los 255 caracteres.',
            'nombre.required' => 'El nombre es obligatorio.',
            'email.unique' => 'El email ya está en uso.',
            'email.regex' => 'El formato del email es inválido.',
            'telefono' => 'Formato de teléfono incorrecto',
        ]);

        try {
            $agente->update($request->all()); // Actualizar el agente

            Log::info('✅ Agente actualizado correctamente.', [
                'agente_id' => $agente->id,
            ]);

            return redirect()->route('agentes.index')
            ->with([
                    'success' => [
                        'id' => uniqid(),
                        'mensaje' => "Agente actualizado correctamente",
                    ],
                ]);
        } catch (Throwable $e) {
            Log::error('❌ Error al actualizar el agente: ' . $e->getMessage(), [
                'exception' => $e,
                'agente_id' => $id,
                'data' => $request->all(),
            ]);

            return back()->with([
                    'error' => [
                        'id' => uniqid(),
                        'mensaje' => "Error al actualizar el agente",
                    ],
                ]);
            // ->withErrors(['error' => 'Ocurrió un error al actualizar el agente. Intenta nuevamente.']);
        }
    }

    /**
     * Metodo para eliminar un agente.
     */
    public function destroy($id)
    {
        try {
            // Buscar el agente por ID
            $agente = Agente::findOrFail($id);

            // Eliminar el agente
            $agente->delete();

            Log::info('🗑️ Agente eliminado correctamente.', [
                'agente_id' => $id,
            ]);

            return redirect()->route('agentes.index')
            ->with([
                    'success' => [
                        'id' => uniqid(),
                        'mensaje' => "Agente eliminado correctamente",
                    ],
                ]);
        } catch (Throwable $e) {
            Log::error('❌ Error al eliminar el agente: ' . $e->getMessage(), [
                'exception' => $e,
                'agente_id' => $id,
            ]);

            return back()->with([
                    'error' => [
                        'id' => uniqid(),
                        'mensaje' => "Error al eliminar el agente",
                    ],
                ]);
            // ->withErrors(['error' => 'Ocurrió un error al eliminar el agente. Intenta nuevamente.']);
        }
    }
}
