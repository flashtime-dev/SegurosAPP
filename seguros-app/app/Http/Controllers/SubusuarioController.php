<?php

namespace App\Http\Controllers;

use App\Models\Subusuario;
use App\Models\User;
use Illuminate\Http\Request;

class SubusuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $subusuarios = Subusuario::with('usuarioCreador')->get(); // Obtener todos los subusuarios con su usuario creador
        return view('subusuarios.index', compact('subusuarios')); // Retornar la vista con los datos
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $usuarios = User::all(); // Obtener todos los usuarios
        return view('subusuarios.create', compact('usuarios')); // Retornar la vista para crear un nuevo subusuario
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:users,id|unique:subusuarios,id',
            'id_usuario_creador' => 'required|exists:users,id',
        ]);

        Subusuario::create($request->all()); // Crear un nuevo subusuario

        return redirect()->route('subusuarios.index')->with('success', 'Subusuario creado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Subusuario $subusuario)
    {
        $subusuario->load('usuarioCreador'); // Cargar el usuario creador del subusuario
        return view('subusuarios.show', compact('subusuario')); // Retornar la vista con los detalles del subusuario
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Subusuario $subusuario)
    {
        $usuarios = User::all(); // Obtener todos los usuarios
        return view('subusuarios.edit', compact('subusuario', 'usuarios')); // Retornar la vista para editar el subusuario
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Subusuario $subusuario)
    {
        $request->validate([
            'id' => 'required|exists:users,id|unique:subusuarios,id,' . $subusuario->id . ',id_usuario_creador,' . $subusuario->id_usuario_creador,
            'id_usuario_creador' => 'required|exists:users,id',
        ]);

        $subusuario->update($request->all()); // Actualizar el subusuario

        return redirect()->route('subusuarios.index')->with('success', 'Subusuario actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subusuario $subusuario)
    {
        $subusuario->delete(); // Eliminar el subusuario
        return redirect()->route('subusuarios.index')->with('success', 'Subusuario eliminado correctamente.');
    }
}
