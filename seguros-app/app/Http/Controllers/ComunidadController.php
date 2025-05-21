<?php

namespace App\Http\Controllers;

use App\Models\Comunidad;
use App\Models\Subusuario;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class ComunidadController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user(); // Obtener el usuario autenticado
        
        // Verificar si el usuario tiene el rol de administrador
        if ($user->rol->nombre == 'Superadministrador') {
            $comunidades = Comunidad::with('users')->get(); // Obtener todas las comunidades
            $usuarios = User::all(); // Obtener todos los usuarios
        } else {
            // Obtener comunidades donde el usuario es propietario O está asignado como usuario
            $comunidades = Comunidad::where('id_propietario', $user->id)
                ->orWhereHas('users', function($query) use ($user) {
                    $query->where('users.id', $user->id);
                })
                ->with('users')
                ->get();
            $empleados = Subusuario::where('id_usuario_creador', $user->id)->get(); // Obtener empleados
            
            if ($empleados->isEmpty()) {
                $usuarios = []; // Obtener todos los usuarios si no hay empleados
            } else {
                $usuarios = User::where('id', $empleados->pluck('id'))->get(); // Obtener usuarios según los empleados
            }
        }
        

        return Inertia::render('comunidades/index', [
            'comunidades' => $comunidades,
            'usuarios' => $usuarios,
        ]); // Retornar la vista con los datos
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $usuarios = User::all(); // Obtener todos los usuarios
        return Inertia::render('comunidades/create', [
            'usuarios' => $usuarios, // Pasar los usuarios a la vista
        ]); // Retornar la vista para crear una nueva comunidad
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user(); // Obtener el usuario autenticado

        $request->merge([
            'nombre' => ucfirst($request->nombre),
            'cif' => strtoupper($request->cif),
            'direccion' => ucfirst($request->direccion),
            'ubi_catastral' => ucfirst($request->ubi_catastral),
            'ref_catastral' => strtoupper($request->ref_catastral)
        ]);

        $request->validate([
            'nombre' => 'required|string|min:2|max:255',
            'cif' => 'required|string|regex:/^[ABCDEFGHJKLMNPQRSUVW]\d{8}$/|unique:comunidades,cif',
            'direccion' => 'nullable|string|max:255',
            'ubi_catastral' => 'nullable|string|max:255',
            'ref_catastral' => 'nullable|string|regex:/^[0-9A-Z]{20}$/',
            'telefono' => 'nullable|string|max:15',
            'usuarios' => 'array', // Validar que usuarios sea un array
            'usuarios.*' => 'exists:users,id', // Validar que cada usuario exista
        ]);
        
        $request->merge(['id_propietario' => $user->id]); // Asignar el ID del propietario al request

        // Crear la comunidad sin los usuarios
        $comunidad = Comunidad::create($request->except('usuarios'));
        
        // Sincronizar los usuarios seleccionados con la tabla pivote
        if ($request->has('usuarios')) {
            $comunidad->users()->sync($request->usuarios);
        }

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
        $usuarios = User::all(); // Obtener todos los usuarios
        return Inertia::render('comunidades/edit', [
            'comunidad' => $comunidad, // Pasar la comunidad a la vista
            'usuarios' => $usuarios, // Pasar los usuarios a la vista
        ]); // Retornar la vista para editar la comunidad
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $comunidad = Comunidad::findOrFail($id); // Buscar la comunidad por ID
        
        $request->merge([
            'nombre' => ucfirst($request->nombre),
            'cif' => strtoupper($request->cif),
            'direccion' => ucfirst($request->direccion),
            'ubi_catastral' => ucfirst($request->ubi_catastral),
            'ref_catastral' => strtoupper($request->ref_catastral)
        ]);
            
        $request->validate([
            'nombre' => 'required|string|min:2|max:255',
            'cif' => 'required|string|regex:/^[ABCDEFGHJKLMNPQRSUVW]\d{8}$/|unique:comunidades,cif,' . $comunidad->id,
            'direccion' => 'nullable|string|max:255',
            'ubi_catastral' => 'nullable|string|max:255',
            'ref_catastral' => 'nullable|string|regex:/^[0-9A-Z]{20}$/',
            'telefono' => 'nullable|string|max:15',
            'usuarios' => 'array', // Validar que usuarios sea un array
            'usuarios.*' => 'exists:users,id', // Validar que cada usuario exista
        ]);

        // Actualizar los datos de la comunidad excepto los usuarios
        $comunidad->update($request->except('usuarios'));

        // Sincronizar los usuarios seleccionados con la tabla pivote
        if ($request->has('usuarios')) {
            $comunidad->users()->sync($request->usuarios);
        }

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
        $comunidad->presupuestos()->delete(); // Eliminar los presupuestos asociados
        $comunidad->polizas()->delete(); // Eliminar las pólizas asociadas
        $comunidad->delete(); // Eliminar la comunidad
        return redirect()->route('comunidades.index')->with('success', 'Comunidad eliminada correctamente.');
    }
}
