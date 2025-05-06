<?php

namespace App\Http\Controllers;

use App\Models\Permiso;
use App\Models\User;
use App\Models\Rol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with('rol')->get(['id', 'id_rol' ,'name', 'email', 'state']); // Obtener todos los usuarios con su rol
        $roles = Rol::with('permisos')->get(); // Obtener todos los roles con sus permisos
        $permmisos = Permiso::all(); // Obtener todos los permisos de los roles
        //dd($roles); // Debugging: Verificar los datos de los roles
        //dd($users); // Debugging: Verificar los datos de los usuarios
        return Inertia::render('usuarios/index', [
            'users' => $users,
            'roles' => $roles,
            'permisos' => $permmisos,

        ]);
        //return view('users.index', compact('users')); // Retornar la vista con los datos
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('usuarios/create', [
            'roles' => Rol::all(), // Obtener todos los roles
        ]); // Retornar la vista para crear un nuevo usuario
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'id_rol' => 'required|exists:roles,id',
            'address' => 'nullable|string|max:500',
            'phone' => 'nullable|string|max:15',
            'state' => 'nullable|boolean',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'id_rol' => $request->id_rol,
            'address' => $request->address,
            'phone' => $request->phone,
            'state' => $request->boolean('state'), // Convertir a booleano
        ]);


        return redirect()->route('usuarios.index')->with('success', 'Usuario creado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $user->load('rol'); // Cargar el rol del usuario
        return view('users.show', compact('user')); // Retornar la vista con los detalles del usuario
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $roles = Rol::all(); // Obtener todos los roles
        return view('users.edit', compact('user', 'roles')); // Retornar la vista para editar el usuario
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'id_rol' => 'required|exists:roles,id',
            'address' => 'nullable|string|max:500',
            'phone' => 'nullable|string|max:15',
            'state' => 'nullable|boolean',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password ? Hash::make($request->password) : $user->password,
            'id_rol' => $request->id_rol,
            'address' => $request->address,
            'phone' => $request->phone,
            'state' => $request->state ?? $user->state,
        ]);

        return redirect()->route('users.index')->with('success', 'Usuario actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id); // Buscar el usuario por ID
        $user->delete(); // Eliminar el usuario
        return redirect()->route('usuarios.index')->with('success', 'Usuario eliminado correctamente.');
    }
}
