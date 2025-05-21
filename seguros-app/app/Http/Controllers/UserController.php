<?php

namespace App\Http\Controllers;

use App\Models\Permiso;
use App\Models\User;
use App\Models\Rol;
use App\Models\TipoPermiso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use App\Http\Middleware\CheckPermiso;

use Illuminate\Routing\Controller as BaseController;

class UserController extends BaseController
{
    public function __construct()
    {
        // Aplica middleware de permisos a métodos específicos
        $this->middleware(CheckPermiso::class . ':usuarios.ver', ['only' => ['index', 'show']]);
        $this->middleware(CheckPermiso::class . ':usuarios.crear', ['only' => ['create', 'store']]);
        $this->middleware(CheckPermiso::class . ':usuarios.editar', ['only' => ['edit', 'update']]);
        $this->middleware(CheckPermiso::class . ':usuarios.eliminar', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with('rol')->get(['id', 'id_rol', 'name', 'email', 'address', 'phone', 'state']); // Obtener todos los usuarios con su r$tipoPermisos = TipoPermiso::all(); // Obtener todos los permisos de los rolesol

        return Inertia::render('usuarios/index', [
            'users' => $users,
            'roles' => Rol::all(),
        ]);
        //return view('users.index', compact('users')); // Retornar la vista con los datos
    }

    /**
     * Show the form for creating a new resource.
     */
    // public function create()
    // {
    //     return Inertia::render('usuarios/create', [
    //          // Obtener todos los roles
    //         'roles' => Rol::all(),
    //     ]); // Retornar la vista para crear un nuevo usuario
    // }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Capitalizar cada palabra del nombre antes de la validación
        $request->merge([
            'name' => ucfirst(($request->name)),
            'address' => ucfirst($request->address)
        ]);

        $request->validate([
            'name' => 'required|string|min:2|max:255',
            'email' => 'required|string|email|max:255|unique:users,email|regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/i',
            'password' => 'required|string|confirmed||regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#_.])[A-Za-z\d@$!%*?&#_.]{8,}$/',
            'id_rol' => 'required|exists:roles,id',
            'address' => 'nullable|string|max:500',
            'phone' => 'nullable|string|max:15',
            'state' => 'nullable|boolean',
        ], [
            'name.min' => 'El nombre debe tener al menos 3 caracteres',
            'email.required' => 'El correo electrónico es obligatorio',
            'email.unique' => 'El correo electrónico ya está en uso',
            'email.regex' => 'El formato del correo electrónico es inválido',
            'id_rol.required' => 'Debes seleccionar un rol',
            'password.confirmed' => 'Las contraseñas no coinciden',
            'password.regex' => 'La contraseña debe ser de 8 carácteres y contener al menos: una letra mayúscula, una minúscula, un número y un carácter especial (@$!%*?&#_.)',
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
    public function show($id)
    {
        $user = User::findOrFail($id); // Buscar el usuario por ID
        $user->load('rol'); // Cargar el rol del usuario
        $roles = Rol::all(); // Obtener todos los roles
        return Inertia::render('usuarios/show', [
            'user' => $user, // Pasar el usuario a la vista
            'rol' => $user->rol, // Pasar el rol del usuario a la vista
            'roles' => $roles, // Pasar todos los roles a la vista
        ]); // Retornar la vista con los detalles del usuario
    }

    /**
     * Show the form for editing the specified resource.
     */
    // public function edit(User $user)
    // {
    //     $roles = Rol::all(); // Obtener todos los roles
    //     return Inertia::render('usuarios/edit',[
    //         'user' => $user, // Pasar el usuario a la vista
    //         'roles' => $roles, // Pasar todos los roles a la vista
    //     ]);
    // }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id); // Buscar el usuario por ID

        // Capitalizar cada palabra del nombre antes de la validación
        $request->merge([
            'name' => ucfirst($request->name),
            'address' => ucfirst($request->address)
        ]);

        // Validar los datos del formulario
        $request->validate([
            'name' => 'required|string|min:2|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users,email,' . $user->id, // Excluir el correo actual del usuario
                'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/i', // Validar formato de correo electrónico
            ],
            'password' => 'nullable|string|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#_.])[A-Za-z\d@$!%*?&#_.]{8,}$/', // Contraseña opcional
            'id_rol' => 'required|exists:roles,id',
            'address' => 'nullable|string|max:500',
            'phone' => 'nullable|string|max:15',
            'state' => 'nullable|boolean',
        ], [
            'name.min' => 'El nombre debe tener al menos 3 caracteres',
            'email.regex' => 'El formato del correo electrónico es inválido.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'password.regex' => 'La contraseña debe ser de 8 carácteres y contener al menos: una letra mayúscula, una minúscula, un número y un carácter especial (@$!%*?&#_.)',
        ]);

        // Actualizar los datos del usuario
        $user->update([
            'name' => $request->name,
            'email' => $request->email ?? $user->email, // Mantener el correo actual si no se cambia
            'password' => $request->filled('password') ? Hash::make($request->password) : $user->password, // Mantener la contraseña actual si no se proporciona una nueva
            'id_rol' => $request->id_rol,
            'address' => $request->address,
            'phone' => $request->phone,
            'state' => $request->state ?? $user->state,
        ]);

        return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado correctamente.');
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
