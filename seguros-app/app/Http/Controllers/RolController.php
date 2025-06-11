<?php

namespace App\Http\Controllers;

use App\Models\Rol;
use App\Models\TipoPermiso;
use App\Models\Permiso;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller as BaseController;
use App\Http\Middleware\CheckPermiso;
use Throwable;
use Illuminate\Support\Facades\Log;

// Este controlador maneja las operaciones CRUD para los roles en la aplicación.
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
     * Mostrar una lista de los recursos.
     */
    public function index()
    {
        try {
            $roles = Rol::with('permisos')->get(); // Obtener todos los roles con sus permisos
            $tipoPermisos = TipoPermiso::all(); // Obtener todos los tipos de permisos
            $permisos = Permiso::with('tipoPermiso')->get(); // Obtener todos los permisos
            Log::info('Roles listados correctamente', ['count' => $roles->count()]);

            return Inertia::render('roles/index', [
                'roles' => $roles,
                'tipoPermisos' => $tipoPermisos,
                'permisos' => $permisos
            ]); // Retornar la vista con los datos
        } catch (Throwable $t) {
            Log::error('Error al listar roles: ' . $t->getMessage(), ['exception' => $t]);
            return redirect()->route('roles.index')->with([
                    'error' => [
                        'id' => uniqid(),
                        'mensaje' => "Error al listar los roles",
                    ],
                ]);
            // ->with('error', 'Error al listar los roles.');
        }
    }

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
        try {
            // Crear un nuevo rol con el nombre proporcionado
            $rol = Rol::create([
                'nombre' => $request->nombre,
            ]);

            // se esta mandando un array de permisos con solo los ids
            //dd($request->permisos); // Debugging: Verificar los datos de los permisos

            // Si se proporcionan permisos
            if ($request->has('permisos')) {
                $rol->permisos()->attach($request->permisos); // Crear la relación entre el rol y los permisos
            } else {
                // Si no se proporcionan permisos, se eliminan las relaciones existentes
                $rol->permisos()->detach(); // Eliminar la relación entre el rol y los permisos
            }

            Log::info('Rol creado correctamente', ['rol_id' => $rol->id, 'user_id' => Auth::id()]);
            return redirect()->route('roles.index')->with([
                    'success' => [
                        'id' => uniqid(),
                        'mensaje' => "Rol creado correctamente",
                    ],
                ]);
        } catch (Throwable $t) {
            Log::error('Error al crear rol: ' . $t->getMessage(), ['exception' => $t]);
            return redirect()->route('roles.index')->with([
                    'error' => [
                        'id' => uniqid(),
                        'mensaje' => "Error al crear el rol",
                    ],
                ]);
        }
    }

    /**
     *  Actualizar el recurso especificado en almacenamiento.
     */
    public function update(Request $request, $id)
    {
        // Validar que el ID del rol exista
        $rol = Rol::findOrFail($id); // Buscar el rol por ID

        // Capitalizar solo la primera palabra del nombre antes de la validación
        $request->merge([
            'nombre' => ucfirst(($request->nombre))
        ]);

        // Validar los datos de entrada
        $request->validate([
            'nombre' => 'required|string|min:2|max:50',
            'permisos' => 'nullable|array',
            'permisos.*' => 'exists:permisos,id', // Validar que los permisos existan
        ]);

        try {
            // Actualizar el rol con el nuevo nombre
            $rol->update([
                'nombre' => $request->nombre,
            ]);

            // Si se proporcionan permisos
            if ($request->has('permisos')) {
                $rol->permisos()->sync($request->permisos); // Actualizar permisos del rol
            }

            Log::info('Rol actualizado correctamente', ['rol_id' => $rol->id, 'user_id' => Auth::id()]);
            return redirect()->route('roles.index')->with([
                    'success' => [
                        'id' => uniqid(),
                        'mensaje' => "Rol actualizado correctamente",
                    ],
                ]);
        } catch (Throwable $t) {
            Log::error('Error al actualizar rol: ' . $t->getMessage(), ['exception' => $t]);
            return redirect()->route('roles.index')
            ->with([
                    'error' => [
                        'id' => uniqid(),
                        'mensaje' => "Error al actualizar el rol",
                    ],
                ]);
        }
    }

    /**
     * Eliminar el recurso especificado de almacenamiento.
     */
    public function destroy($id)
    {
        try {
            $rol = Rol::findOrFail($id); // Buscar el rol por ID
            // Verificar si el rol tiene permisos asignados
            if ($rol->permisos()->count() > 0) {
                //borrar las relaciones con permisos
                $rol->permisos()->detach(); // Eliminar las relaciones con permisos
            }

            // Eliminar el rol y sus relaciones con permisos
            $rol->permisos()->detach(); // Eliminar las relaciones con permisos
            $rol->delete(); // Eliminar el rol
            Log::info('Rol eliminado correctamente', ['rol_id' => $id, 'user_id' => Auth::id()]);
            return redirect()->route('roles.index')
            ->with([
                    'success' => [
                        'id' => uniqid(),
                        'mensaje' => "Rol eliminado correctamente",
                    ],
                ]);
        } catch (Throwable $t) {
            Log::error('Error al eliminar rol: ' . $t->getMessage(), ['exception' => $t]);
            return redirect()->route('roles.index')
            ->with([
                    'error' => [
                        'id' => uniqid(),
                        'mensaje' => "Error al eliminar el rol",
                    ],
                ]);
        }
    }
}
