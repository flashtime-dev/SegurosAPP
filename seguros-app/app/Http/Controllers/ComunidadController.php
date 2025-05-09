<?php

namespace App\Http\Controllers;

use App\Models\Comunidad;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ComunidadController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $comunidades = Comunidad::all(); // Obtener todas las comunidades
        return Inertia::render('comunidades/index', [
            'comunidades' => $comunidades,
        ]); // Retornar la vista con los datos
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('comunidades/create'); // Retornar la vista para crear una nueva comunidad
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
    public function show($id)
    {
        $comunidad = Comunidad::findOrFail($id); // Buscar la comunidad por ID
        return Inertia::render('Comunidad/Show', ['comunidad' => $comunidad]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $comunidad = Comunidad::findOrFail($id); // Buscar la comunidad por ID
        return Inertia::render('comunidades/edit', [
            'comunidad' => $comunidad, // Pasar la comunidad a la vista
        ]); // Retornar la vista para editar la comunidad
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $comunidad = Comunidad::findOrFail($id); // Buscar la comunidad por ID
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
    public function destroy($id)
    {
        $comunidad = Comunidad::findOrFail($id); // Buscar la comunidad por ID
        $comunidad->users()->detach(); // Desvincular usuarios de la comunidad
        $comunidad->caracteristica()->delete(); // Eliminar la característica asociada
        $comunidad->contactos()->delete(); // Eliminar los contactos asociados
        $comunidad->presupuestos()->delete(); // Eliminar los presupuestos asociados
        $comunidad->polizas()->delete(); // Eliminar las pólizas asociadas
        $comunidad->delete(); // Eliminar la comunidad
        return redirect()->route('comunidades.index')->with('success', 'Comunidad eliminada correctamente.');
    }
}
