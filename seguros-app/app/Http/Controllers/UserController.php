<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Rol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with('rol')->get(); // Obtener todos los usuarios con su rol
        return view('users.index', compact('users')); // Retornar la vista con los datos
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Rol::all(); // Obtener todos los roles
        return view('users.create', compact('roles')); // Retornar la vista para crear un nuevo usuario
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
            'state' => $request->state ?? 0,
        ]);

        return redirect()->route('users.index')->with('success', 'Usuario creado correctamente.');
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
    public function destroy(User $user)
    {
        $user->delete(); // Eliminar el usuario
        return redirect()->route('users.index')->with('success', 'Usuario eliminado correctamente.');
    }
}
