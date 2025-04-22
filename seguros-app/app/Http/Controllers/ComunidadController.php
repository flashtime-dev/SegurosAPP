<?php

namespace App\Http\Controllers;

use App\Models\Comunidad;
use Illuminate\Http\Request;

class ComunidadController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $comunidades = Comunidad::all(); // Obtener todas las comunidades
        return view('comunidades.index', compact('comunidades')); // Retornar la vista con los datos
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('comunidades.create'); // Retornar la vista para crear una nueva comunidad
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'cif' => 'required|string|max:15|unique:comunidades,cif',
            'direccion' => 'nullable|string|max:255',
            'ubi_catastral' => 'nullable|string|max:255',
            'ref_catastral' => 'nullable|string|max:20',
            'telefono' => 'nullable|string|max:15',
        ]);

        Comunidad::create($request->all()); // Crear una nueva comunidad

        return redirect()->route('comunidades.index')->with('success', 'Comunidad creada correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Comunidad $comunidad)
    {
        return view('comunidades.show', compact('comunidad')); // Retornar la vista con los detalles de la comunidad
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Comunidad $comunidad)
    {
        return view('comunidades.edit', compact('comunidad')); // Retornar la vista para editar la comunidad
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Comunidad $comunidad)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'cif' => 'required|string|max:15|unique:comunidades,cif,' . $comunidad->id,
            'direccion' => 'nullable|string|max:255',
            'ubi_catastral' => 'nullable|string|max:255',
            'ref_catastral' => 'nullable|string|max:20',
            'telefono' => 'nullable|string|max:15',
        ]);

        $comunidad->update($request->all()); // Actualizar la comunidad

        return redirect()->route('comunidades.index')->with('success', 'Comunidad actualizada correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comunidad $comunidad)
    {
        $comunidad->delete(); // Eliminar la comunidad
        return redirect()->route('comunidades.index')->with('success', 'Comunidad eliminada correctamente.');
    }
}
