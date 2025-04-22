<?php

namespace App\Http\Controllers;

use App\Models\Permiso;
use App\Models\TipoPermiso;
use Illuminate\Http\Request;

class PermisoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $permisos = Permiso::with('tipoPermiso')->get(); // Obtener todos los permisos con su tipo
        return view('permisos.index', compact('permisos')); // Retornar la vista con los datos
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $tiposPermisos = TipoPermiso::all(); // Obtener todos los tipos de permisos
        return view('permisos.create', compact('tiposPermisos')); // Retornar la vista para crear un nuevo permiso
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_tipo' => 'nullable|exists:tipos_permisos,id',
            'nombre' => 'required|string|max:255',
        ]);

        Permiso::create($request->all()); // Crear un nuevo permiso

        return redirect()->route('permisos.index')->with('success', 'Permiso creado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Permiso $permiso)
    {
        return view('permisos.show', compact('permiso')); // Retornar la vista con los detalles del permiso
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Permiso $permiso)
    {
        $tiposPermisos = TipoPermiso::all(); // Obtener todos los tipos de permisos
        return view('permisos.edit', compact('permiso', 'tiposPermisos')); // Retornar la vista para editar el permiso
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Permiso $permiso)
    {
        $request->validate([
            'id_tipo' => 'nullable|exists:tipos_permisos,id',
            'nombre' => 'required|string|max:255',
        ]);

        $permiso->update($request->all()); // Actualizar el permiso

        return redirect()->route('permisos.index')->with('success', 'Permiso actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Permiso $permiso)
    {
        $permiso->delete(); // Eliminar el permiso

        return redirect()->route('permisos.index')->with('success', 'Permiso eliminado correctamente.');
    }
}
