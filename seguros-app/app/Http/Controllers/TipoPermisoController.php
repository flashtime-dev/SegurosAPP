<?php

namespace App\Http\Controllers;

use App\Models\TipoPermiso;
use Illuminate\Http\Request;

class TipoPermisoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tipoPermisos = TipoPermiso::all(); // Obtener todos los tipos de permisos
        return view('tipo_permisos.index', compact('tipoPermisos')); // Retornar la vista con los datos
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('tipo_permisos.create'); // Retornar la vista para crear un nuevo tipo de permiso
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
        ]);

        TipoPermiso::create($request->all()); // Crear un nuevo tipo de permiso

        return redirect()->route('tipo_permisos.index')->with('success', 'Tipo de permiso creado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(TipoPermiso $tipoPermiso)
    {
        return view('tipo_permisos.show', compact('tipoPermiso')); // Retornar la vista con los detalles del tipo de permiso
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TipoPermiso $tipoPermiso)
    {
        return view('tipo_permisos.edit', compact('tipoPermiso')); // Retornar la vista para editar el tipo de permiso
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TipoPermiso $tipoPermiso)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
        ]);

        $tipoPermiso->update($request->all()); // Actualizar el tipo de permiso

        return redirect()->route('tipo_permisos.index')->with('success', 'Tipo de permiso actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TipoPermiso $tipoPermiso)
    {
        $tipoPermiso->delete(); // Eliminar el tipo de permiso

        return redirect()->route('tipo_permisos.index')->with('success', 'Tipo de permiso eliminado correctamente.');
    }
}
