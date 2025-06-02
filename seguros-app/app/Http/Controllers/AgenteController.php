<?php

namespace App\Http\Controllers;

use App\Models\Agente;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;
use Throwable;
use Illuminate\Routing\Controller as BaseController;
use App\Http\Middleware\CheckPermiso;

class AgenteController extends BaseController
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
     * Display a listing of the resource.
     */
    public function index()
    {
        $agentes = Agente::all(); // Obtener todos los agentes
        return Inertia::render('agentes/index', [
            'agentes' => $agentes, // Pasar los agentes a la vista
            'success' => session('success'), // Pasar el mensaje de éxito a la vista
            'error' => session('error'), // Pasar el mensaje de error a la vista
        ]); // Retornar la vista con los datos
    }

    /**
     * Show the form for creating a new resource.
     */
    // public function create()
    // {
    //     return Inertia::render('agentes/create'); // Retornar la vista para crear un nuevo agente
    // }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->merge([
            'nombre' => ucfirst(($request->nombre))
        ]);

        $request->validate([
            'nombre' => 'required|string|min:2|max:255',
            'telefono' => ['nullable', 'phone:ES,US,FR,GB,DE,IT,PT,MX,AR,BR,INTL'],
            'email' => 'nullable|string|email|max:255|unique:agentes,email|regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/i',
        ], [
            'nombre.min' => 'El nombre debe tener al menos 2 caracteres.',
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

            return redirect()->route('agentes.index')->with('success', 'Agente creado correctamente.');
        } catch (Throwable $e) {
            Log::error('❌ Error al crear el agente: ' . $e->getMessage(), [
                'exception' => $e,
                'data' => $request->all(),
            ]);

            return back()->withErrors(['error' => 'Ocurrió un error al crear el agente. Intentalo de nuevo.']);
        }
    }

    /**
     * Display the specified resource.
     */
    // public function show(Agente $agente)
    // {
    //     return view('agentes.show', compact('agente')); // Retornar la vista con los detalles del agente
    // }

    /**
     * Show the form for editing the specified resource.
     */
    // public function edit($id)
    // {
    //     $agente = Agente::findOrFail($id); // Buscar el agente por ID
    //     return Inertia::render('agentes/edit', [
    //         'agente' => $agente, // Pasar el agente a la vista
    //     ]); // Retornar la vista para editar el agente
    // }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {

        $agente = Agente::findOrFail($id); // Buscar el agente por ID
        $request->merge([
            'nombre' => ucfirst(($request->nombre))
        ]);

        $request->validate([
            'nombre' => 'required|string|min:2|max:255',
            'telefono' => 'nullable|string|max:15',
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
            'email.unique' => 'El email ya está en uso.',
            'email.regex' => 'El formato del email es inválido.',
        ]);
        try {
            $agente->update($request->all()); // Actualizar el agente

            Log::info('✅ Agente actualizado correctamente.', [
                'agente_id' => $agente->id,
            ]);

            return redirect()->route('agentes.index')->with('success', 'Agente actualizado correctamente.');
        } catch (Throwable $e) {
            Log::error('❌ Error al actualizar el agente: ' . $e->getMessage(), [
                'exception' => $e,
                'agente_id' => $id,
                'data' => $request->all(),
            ]);

            return back()->withErrors(['error' => 'Ocurrió un error al actualizar el agente. Intenta nuevamente.']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $agente = Agente::findOrFail($id); // Buscar el agente por ID
            $agente->delete(); // Eliminar el agente

            Log::info('🗑️ Agente eliminado correctamente.', [
                'agente_id' => $id,
            ]);

            return redirect()->route('agentes.index')->with('success', 'Agente eliminado correctamente.');
        } catch (Throwable $e) {
            Log::error('❌ Error al eliminar el agente: ' . $e->getMessage(), [
                'exception' => $e,
                'agente_id' => $id,
            ]);

            return back()->withErrors(['error' => 'Ocurrió un error al eliminar el agente. Intenta nuevamente.']);
        }
    }
}
