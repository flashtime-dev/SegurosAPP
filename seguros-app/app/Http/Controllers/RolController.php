<?php

namespace App\Http\Controllers;

use App\Models\Rol;
use App\Models\Permiso;
use Illuminate\Http\Request;

class RolController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Rol::with('permisos')->get(); // Obtener todos los roles con sus permisos
        return view('roles.index', compact('roles')); // Retornar la vista con los datos
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $permisos = Permiso::all(); // Obtener todos los permisos
        return view('roles.create', compact('permisos')); // Retornar la vista para crear un nuevo rol
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'permisos' => 'nullable|array',
            'permisos.*' => 'exists:permisos,id', // Validar que los permisos existan
        ]);

        $rol = Rol::create([
            'nombre' => $request->nombre,
        ]);

        if ($request->has('permisos')) {
            $rol->permisos()->sync($request->permisos); // Asignar permisos al rol
        }

        return redirect()->route('roles.index')->with('success', 'Rol creado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Rol $rol)
    {
        $rol->load('permisos'); // Cargar los permisos del rol
        return view('roles.show', compact('rol')); // Retornar la vista con los detalles del rol
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Rol $rol)
    {
        $permisos = Permiso::all(); // Obtener todos los permisos
        $rol->load('permisos'); // Cargar los permisos del rol
        return view('roles.edit', compact('rol', 'permisos')); // Retornar la vista para editar el rol
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Rol $rol)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'permisos' => 'nullable|array',
            'permisos.*' => 'exists:permisos,id', // Validar que los permisos existan
        ]);

        $rol->update([
            'nombre' => $request->nombre,
        ]);

        if ($request->has('permisos')) {
            $rol->permisos()->sync($request->permisos); // Actualizar permisos del rol
        }

        return redirect()->route('roles.index')->with('success', 'Rol actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Rol $rol)
    {
        $rol->permisos()->detach(); // Eliminar las relaciones con permisos
        $rol->delete(); // Eliminar el rol

        return redirect()->route('roles.index')->with('success', 'Rol eliminado correctamente.');
    }
}
