<?php

namespace App\Http\Controllers;

use App\Models\Rol;
use App\Models\TipoPermiso;
use App\Models\Permiso;
use Illuminate\Http\Request;
use Inertia\Inertia;

use Illuminate\Routing\Controller as BaseController;
use App\Http\Middleware\CheckPermiso;
class RolController extends BaseController
{
    public function __construct()
    {
        // Aplica middleware de permisos a métodos específicos

        //Roles
        $this->middleware(CheckPermiso::class . ':roles.ver', ['only' => ['index']]);
        $this->middleware(CheckPermiso::class . ':roles.crear', ['only' => ['store']]);
        $this->middleware(CheckPermiso::class . ':roles.editar', ['only' => ['update']]);
        $this->middleware(CheckPermiso::class . ':roles.eliminar', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Rol::with('permisos')->get(); // Obtener todos los roles con sus permisos
        $tipoPermisos = TipoPermiso::all(); // Obtener todos los tipos de permisos
        $permisos = Permiso::with('tipoPermiso')->get(); // Obtener todos los permisos

        return Inertia::render('roles/index', [
            'roles' => $roles,
            'tipoPermisos' => $tipoPermisos,
            'permisos' => $permisos
        ]); // Retornar la vista con los datos
    }

    /**
     * Show the form for creating a new resource.
     */
    // public function create()
    // {
    //     // Ya no es necesario, se utiliza el modal
    //     abort(404);
    // }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Capitalizar solo la primera palabra del nombre antes de la validación
        $request->merge([
            'nombre' => ucfirst(($request->nombre))
        ]);

        $request->validate([
            'nombre' => 'required|string|min:2|max:50',
            'permisos' => 'nullable|array',
            'permisos.*' => 'exists:permisos,id', // Validar que los permisos existan
        ]);

        $rol = Rol::create([
            'nombre' => $request->nombre,
        ]);

        // se esta mandando un array de permisos con solo los ids
        //dd($request->permisos); // Debugging: Verificar los datos de los permisos

        if ($request->has('permisos')) {
            $rol->permisos()->attach($request->permisos); // Crear la relación entre el rol y los permisos
        } else {
            $rol->permisos()->detach(); // Eliminar la relación entre el rol y los permisos
        }

        return redirect()->route('roles.index')->with('success', 'Rol creado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    // public function show($id)
    // {
    //     $rol = Rol::findOrFail($id); // Buscar el rol por ID
    //     $rol->load('permisos'); // Cargar los permisos del rol
    //     return Inertia::render(
    //         'roles/show', [
    //             'rol' => $rol,
    //             'permisos' => $rol->permisos, // Pasar los permisos del rol a la vista
    //         ]
    //     ); // Retornar la vista con los detalles del rol
    // }

    /**
     * Show the form for editing the specified resource.
     */
    // public function edit($id)
    // {
    //     // Ya no es necesario, se utiliza el modal
    //     abort(404);
    // }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $rol = Rol::findOrFail($id); // Buscar el rol por ID

        // Capitalizar solo la primera palabra del nombre antes de la validación
        $request->merge([
            'nombre' => ucfirst(($request->nombre))
        ]);

        $request->validate([
            'nombre' => 'required|string|min:2|max:50',
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
    public function destroy($id)
    {
        $rol = Rol::findOrFail($id); // Buscar el rol por ID

        // Verificar si el rol tiene permisos asignados
        if ($rol->permisos()->count() > 0) {
            //borrar las relaciones con permisos
            $rol->permisos()->detach(); // Eliminar las relaciones con permisos
        }

        // Eliminar el rol y sus relaciones con permisos
        $rol->permisos()->detach(); // Eliminar las relaciones con permisos
        $rol->delete(); // Eliminar el rol

        return redirect()->route('roles.index')->with('success', 'Rol eliminado correctamente.');
    }
}
