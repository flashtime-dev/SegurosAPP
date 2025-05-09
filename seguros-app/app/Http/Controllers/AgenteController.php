<?php

namespace App\Http\Controllers;

use App\Models\Agente;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AgenteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $agentes = Agente::all(); // Obtener todos los agentes
        return Inertia::render('agentes/index', [
            'agentes' => $agentes, // Pasar los agentes a la vista
            //'success' => session('success'), // Pasar el mensaje de éxito a la vista
            //'error' => session('error'), // Pasar el mensaje de error a la vista
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
        $request->validate([
            'nombre' => 'required|string|max:255',
            'telefono' => 'nullable|string|max:15',
            'email' => 'nullable|string|email|max:255|unique:agentes,email',
        ]);

        Agente::create($request->all()); // Crear un nuevo agente

        return redirect()->route('agentes.index')->with('success', 'Agente creado correctamente.');
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
        $request->validate([
            'nombre' => 'required|string|max:255',
            'telefono' => 'nullable|string|max:15',
            'email' => 'nullable|string|email|max:255|unique:agentes,email,' . $agente->id,
        ]);

        $agente->update($request->all()); // Actualizar el agente

        return redirect()->route('agentes.index')->with('success', 'Agente actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $agente = Agente::findOrFail($id); // Buscar el agente por ID
        $agente->delete(); // Eliminar el agente
        return redirect()->route('agentes.index')->with('success', 'Agente eliminado correctamente.');
    }
}
