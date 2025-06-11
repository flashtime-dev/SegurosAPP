<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Rol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use App\Http\Middleware\CheckPermiso;
use App\Models\Subusuario;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Throwable;

class UserController extends BaseController
{
    public function __construct()
    {
        // Aplica middleware de permisos a métodos específicos

        //Usuarios
        $this->middleware(CheckPermiso::class . ':usuarios.ver', ['only' => ['index']]);
        $this->middleware(CheckPermiso::class . ':usuarios.crear|empleados.crear', ['only' => ['store']]);
        $this->middleware(CheckPermiso::class . ':usuarios.editar|empleados.editar', ['only' => ['update']]);
        $this->middleware(CheckPermiso::class . ':usuarios.eliminar|empleados.eliminar', ['only' => ['destroy']]);

        //Empleados
        $this->middleware(CheckPermiso::class . ':empleados.ver', ['only' => ['empleados']]);
        // $this->middleware(CheckPermiso::class . ':empleados.crear', ['only' => ['store']]);
        // $this->middleware(CheckPermiso::class . ':empleados.editar', ['only' => ['update']]);
        // $this->middleware(CheckPermiso::class . ':empleados.eliminar', ['only' => ['destroy']]);
    }

    public function index()
    {
        try {
            $users = User::with('rol', 'subusuarios.usuario:id,id_rol,name,email,address,phone,state', 'usuarioCreador')->get(['id', 'id_rol', 'name', 'email', 'address', 'phone', 'state']);
            Log::info('Usuarios cargados correctamente.');
            //dd($users); // Debugging: Verificar los datos de los usuarios
            return Inertia::render('usuarios/index', [
                'users' => $users,
                'roles' => Rol::all(),
            ]);
        } catch (Throwable $e) {
            Log::error('Error al obtener usuarios: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()->with([
                'error' => [
                    'id' => uniqid(),
                    'mensaje' => "Error al cargar usuarios",
                ],
            ]);
            // ->withErrors(['error' => 'Error al cargar usuarios.']);
        }
    }

    public function empleados()
    {
        try {
            $user = Auth::user();
            $id_usuario_creador = Subusuario::where('id', $user->id)->value('id_usuario_creador') ?? 0; // Obtener el id_usuario_creador del usuario autenticado, si existe
            $users = User::with(
                'rol',
                'subusuarios.usuario:id,id_rol,name,email,address,phone,state',
                'usuarioCreador.usuarioCreador:id,id_rol,name,email,address,phone,state'
            )
                ->whereHas('usuarioCreador', function ($query) use ($user, $id_usuario_creador) {
                    $query->where('id_usuario_creador', $user->id)
                        ->orWhere('id_usuario_creador', $id_usuario_creador);
                })
                ->get(['id', 'id_rol', 'name', 'email', 'address', 'phone', 'state']);

            $userLogged = User::findOrFail($user->id); // Obtener el usuario autenticado
            $userLogged->load(
                'rol',
                'subusuarios.usuario:id,id_rol,name,email,address,phone,state',
                'usuarioCreador.usuarioCreador:id,id_rol,name,email,address,phone,state'
            ); // Cargar el rol del usuario autenticado

            Log::info('Empleados cargados correctamente para el usuario: ' . $user->id);
            //dd($userLogged); // Debugging: Verificar los datos del usuario autenticado
            return Inertia::render('empleados/index', [
                'user' => $userLogged,
                'users' => $users,
                'roles' => Rol::all(),
            ]);
        } catch (Throwable $e) {
            Log::error('Error al obtener empleados: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()->with([
                'error' => [
                    'id' => uniqid(),
                    'mensaje' => "Error al cargar empleados",
                ],
            ]);
            // ->withErrors(['error' => 'Error al cargar empleados.']);
        }
    }

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
            'password' => 'required|string|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#_.-])[A-Za-z\d@$!%*?&#_.-]{8,}$/',
            'id_rol' => 'required|exists:roles,id',
            'address' => 'nullable|string|min:3|max:255',
            'phone' => ['nullable', 'phone:ES,US,FR,GB,DE,IT,PT,MX,AR,BR,INTL'],
            'state' => 'required|boolean',
            'id_usuario_creador' => 'nullable|exists:users,id', // Validar que el id_usuario_creador exista
        ], [
            'name.min' => 'El nombre debe tener al menos 2 caracteres',
            'email.required' => 'El correo electrónico es obligatorio',
            'email.unique' => 'El correo electrónico ya está en uso',
            'email.regex' => 'El formato del correo electrónico es inválido',
            'id_rol.required' => 'Debes seleccionar un rol',
            'password.confirmed' => 'Las contraseñas no coinciden',
            'password.regex' => 'La contraseña debe ser de 8 carácteres y contener al menos: una letra mayúscula, una minúscula, un número y un carácter especial (@$!%*?&#_.-)',
            'phone' => 'Formato de teléfono incorrecto',
        ]);
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'id_rol' => $request->id_rol,
                'address' => $request->address,
                'phone' => $request->phone,
                'state' => $request->boolean('state'), // Convertir a booleano
            ]);

            if ($user && $request->filled('id_usuario_creador')) {
                Subusuario::create([
                    'id' => $user->id,
                    'id_usuario_creador' => $request->id_usuario_creador,
                ]);
            }
            Log::info('Usuario creado correctamente: ID ' . $user->id);
            return redirect()->back()->with([
                'success' => [
                    'id' => uniqid(),
                    'mensaje' => "Usuario creado correctamente",
                ],
            ]);
            //return redirect()->route('usuarios.index')->with('success', 'Usuario creado correctamente.');
        } catch (Throwable $e) {
            Log::error('Error al crear usuario: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()->withInput()->with([
                'error' => [
                    'id' => uniqid(),
                    'mensaje' => "Error al crear el usuario",
                ],
            ]);
            // ->withErrors(['error' => 'Error al crear el usuario.']);
        }
    }

    /**
     * Display the specified resource.
     */
    // public function show($id)
    // {
    //     $user = User::findOrFail($id); // Buscar el usuario por ID
    //     $user->load('rol'); // Cargar el rol del usuario
    //     $roles = Rol::all(); // Obtener todos los roles
    //     return Inertia::render('usuarios/show', [
    //         'user' => $user, // Pasar el usuario a la vista
    //         'rol' => $user->rol, // Pasar el rol del usuario a la vista
    //         'roles' => $roles, // Pasar todos los roles a la vista
    //     ]); // Retornar la vista con los detalles del usuario
    // }

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
            'password' => 'nullable|string|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#_.-])[A-Za-z\d@$!%*?&#_.-]{8,}$/', // Contraseña opcional
            'id_rol' => 'required|exists:roles,id',
            'address' => 'nullable|string|min:3|max:255',
            'phone' => ['nullable', 'phone:ES,US,FR,GB,DE,IT,PT,MX,AR,BR,INTL'],
            'state' => 'required|boolean',
            'id_usuario_creador' => 'nullable|exists:users,id', // Validar que el id_usuario_creador exista
        ], [
            'name.min' => 'El nombre debe tener al menos 2 caracteres',
            'email.regex' => 'El formato del correo electrónico es inválido.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'password.regex' => 'La contraseña debe ser de 8 carácteres y contener al menos: una letra mayúscula, una minúscula, un número y un carácter especial (@$!%*?&#_.-)',
            'phone' => 'Formato de teléfono incorrecto',
        ]);
        try {
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

            if ($user && $request->filled('id_usuario_creador')) {
                // Si el usuario tiene un id_usuario_creador, actualizarlo
                $subusuario = Subusuario::where('id', $user->id)->first();
                if ($subusuario) {
                    $subusuario->update([
                        'id_usuario_creador' => $request->id_usuario_creador,
                    ]);
                } else {
                    // Si no existe, crear uno nuevo
                    Subusuario::create([
                        'id' => $user->id,
                        'id_usuario_creador' => $request->id_usuario_creador,
                    ]);
                }
            }
            Log::info('Usuario actualizado correctamente: ID ' . $user->id);
            return redirect()->back()->with([
                'success' => [
                    'id' => uniqid(),
                    'mensaje' => "Usuario actualizado correctamente",
                ],
            ]);
            //return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado correctamente.');
        } catch (Throwable $e) {
            Log::error('Error al actualizar usuario: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()->withInput()->with([
                'error' => [
                    'id' => uniqid(),
                    'mensaje' => "Error al actualizar el usuario",
                ],
            ]);
            // ->withErrors(['error' => 'Error al actualizar el usuario.']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id); // Buscar el usuario por ID
            $user->delete(); // Eliminar el usuario
            Log::info('Usuario eliminado correctamente: ID ' . $id);
            return redirect()->back()->with([
                'success' => [
                    'id' => uniqid(),
                    'mensaje' => "Usuario eliminado correctamnete",
                ],
            ]);
            //return redirect()->route('usuarios.index')->with('success', 'Usuario eliminado correctamente.');
        } catch (Throwable $e) {
            Log::error('Error al eliminar usuario: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()->with([
                'error' => [
                    'id' => uniqid(),
                    'mensaje' => "Error al eliminar el usuario",
                ],
            ]);
            // ->withErrors(['error' => 'Error al eliminar el usuario.']);
        }
    }
}
