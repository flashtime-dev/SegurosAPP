<?php

namespace App\Http\Controllers;

use App\Models\Comunidad;
use App\Models\Subusuario;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Throwable;

use App\Http\Middleware\CheckPermiso;

class ComunidadController extends Controller
{
    public function __construct()
    {
        // Aplica middleware de permisos a métodos específicos

        //Comunidades
        $this->middleware(CheckPermiso::class . ':comunidades.ver', ['only' => ['index']]);
        $this->middleware(CheckPermiso::class . ':comunidades.crear', ['only' => ['store']]);
        $this->middleware(CheckPermiso::class . ':comunidades.editar', ['only' => ['update']]);
        $this->middleware(CheckPermiso::class . ':comunidades.eliminar', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $user = Auth::user(); // Obtener el usuario autenticado
            $id_usuario_creador = Subusuario::where('id', $user->id)->value('id_usuario_creador') ?? 0; // Obtener el id_usuario_creador del usuario autenticado, si existe
            // Verificar si el usuario tiene el rol de administrador
            if ($user->rol->nombre == 'Superadministrador') {
                $comunidades = Comunidad::with('users')->get(); // Obtener todas las comunidades
                $usuarios = User::all(); // Obtener todos los usuarios
            } else {
                // Obtener comunidades donde el usuario es propietario O está asignado como usuario
                $comunidades = Comunidad::where('id_propietario', $user->id)
                    ->orWhereHas('users', function ($query) use ($user) {
                        $query->where('users.id', $user->id);
                    })
                    ->with('users')
                    ->get();
                $empleados = Subusuario::where('id_usuario_creador', $user->id)
                    ->orWhere('id_usuario_creador', $id_usuario_creador)
                    ->where('id', '!=', $user->id) // Excluir al usuario autenticado
                    ->get(); // Obtener empleados relacionados con el usuario autenticado
    
                if ($empleados->isEmpty()) {
                    $usuarios = []; // Obtener todos los usuarios si no hay empleados
                } else {
                    $usuarios = User::whereIn('id', $empleados->pluck('id'))->get(); // Obtener usuarios según los empleados
                }
            }
            

            Log::info('✅ Comunidades cargadas correctamente.', [
                'user_id' => $user->id,
                'total_comunidades' => $comunidades->count(),
                'total_usuarios' => count($usuarios),
            ]);

            return Inertia::render('comunidades/index', [
                'comunidades' => $comunidades,
                'usuarios' => $usuarios,
            ]); // Retornar la vista con los datos
        } catch (Throwable $e) {
            Log::error('❌ Error al cargar comunidades:', ['exception' => $e]);
            return redirect()->back()->with([
                    'error' => [
                        'id' => uniqid(),
                        'mensaje' => "Error al cargar las comunidades",
                    ],
                ]);
            // ->withErrors('Ocurrió un error al cargar comunidades.');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    // public function create()
    // {
    //     $usuarios = User::all(); // Obtener todos los usuarios
    //     return Inertia::render('comunidades/create', [
    //         'usuarios' => $usuarios, // Pasar los usuarios a la vista
    //     ]); // Retornar la vista para crear una nueva comunidad
    // }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $user = Auth::user(); // Obtener el usuario autenticado
        $id_usuario_creador = Subusuario::where('id', $user->id)->value('id_usuario_creador') ?? 0; // Obtener el id_usuario_creador del usuario autenticado, si existe

        $request->merge([
            'nombre' => ucfirst(($request->nombre)),
            'cif' => strtoupper($request->cif),
            'direccion' => ucfirst(($request->direccion)),
            'ubi_catastral' => ucfirst(($request->ubi_catastral)),
            'ref_catastral' => strtoupper($request->ref_catastral)
        ]);

        $request->validate([
            'nombre' => 'required|string|min:2|max:255',
            'cif' => 'required|string|regex:/^[ABCDEFGHJKLMNPQRSUVW]\d{8}$/|unique:comunidades,cif',
            'direccion' => 'nullable|string|min:3|max:255',
            'ubi_catastral' => 'nullable|string|min:3|max:255',
            'ref_catastral' => 'nullable|string|regex:/^[0-9A-Z]{20}$/',
            'telefono' => ['nullable', 'phone:ES,US,FR,GB,DE,IT,PT,MX,AR,BR,INTL'],
            'usuarios' => 'array', // Validar que usuarios sea un array
            'usuarios.*' => 'exists:users,id', // Validar que cada usuario exista
        ], [
            'nombre.min' => 'El nombre debe tener al menos 2 caracteres.',
            'nombre.max' => 'El nombre no puede tener más de 255 caracteres.',
            'nombre.required' => 'El nombre es obligatorio.',
            'cif.required' => 'El CIF es obligatorio',
            'cif.regex' => 'El CIF debe comenzar con una letra válida y seguir con 8 dígitos.',
            'cif.unique' => 'El CIF ya está en uso.',
            'direccion.min' => 'La dirección debe tener al menos 3 caracteres.',
            'direccion.max' => 'La dirección no puede exceder de 255 caracteres.',
            'ubi_catastral.min' => 'La ubicación catastral debe tener al menos 3 caracteres.',
            'ubi_catastral.max' => 'La ubicación catastral no puede exceder de 255 caracteres.',
            'ref_catastral.regex' => 'La referencia catastral debe tener 20 caracteres alfanuméricos.',
            'telefono' => 'Formato de teléfono incorrecto',
            'usuarios.exists' => 'Alguno de los usuarios seleccionados no existe.',
        ]);

        try {
            if ($id_usuario_creador != 0) {
                $request->merge(['id_propietario' => $id_usuario_creador]); // Asignar el ID del usuario creador al request
            }else{
                $request->merge(['id_propietario' => $user->id]); // Asignar el ID del propietario al request
            }
            // Si el usuario autenticado es subusuario administrador
            if ($id_usuario_creador != 0) {
                // Agregar el usuario autenticado a la lista de usuarios
                $request->merge(['usuarios' => array_merge($request->usuarios ?? [], [$user->id])]); // Asegurarse de que el usuario autenticado esté en la lista de usuarios
            }
            // Crear la comunidad sin los usuarios
            $comunidad = Comunidad::create($request->except('usuarios'));

            

            // Sincronizar los usuarios seleccionados con la tabla pivote
            if ($request->has('usuarios')) {
                $comunidad->users()->sync($request->usuarios);
            }

            Log::info('✅ Comunidad creada correctamente.', [
                'comunidad_id' => $comunidad->id,
                'user_id' => $user->id,
            ]);

            return redirect()->route('comunidades.index')
            ->with([
                    'success' => [
                        'id' => uniqid(),
                        'mensaje' => "Comunidad creada correctamente",
                    ],
                ]);
        } catch (Throwable $e) {
            Log::error('❌ Error al crear comunidad:', ['exception' => $e]);
            return redirect()->back()->with([
                    'error' => [
                        'id' => uniqid(),
                        'mensaje' => "Error al crear la comunidad",
                    ],
                ]);
                // ->withErrors('Ocurrió un error al crear la comunidad.');
        }
    }

    /**
     * Display the specified resource.
     */
    // public function show($id)
    // {
    //     $comunidad = Comunidad::findOrFail($id); // Buscar la comunidad por ID
    //     return Inertia::render('Comunidad/Show', ['comunidad' => $comunidad]);
    // }

    /**
     * Show the form for editing the specified resource.
     */
    // public function edit($id)
    // {
    //     $comunidad = Comunidad::findOrFail($id); // Buscar la comunidad por ID
    //     $usuarios = User::all(); // Obtener todos los usuarios
    //     return Inertia::render('comunidades/edit', [
    //         'comunidad' => $comunidad, // Pasar la comunidad a la vista
    //         'usuarios' => $usuarios, // Pasar los usuarios a la vista
    //     ]); // Retornar la vista para editar la comunidad
    // }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {

        $comunidad = Comunidad::findOrFail($id); // Buscar la comunidad por ID
        $this->authorize('update', $comunidad);

        $request->merge([
            'nombre' => ucfirst(($request->nombre)),
            'cif' => strtoupper($request->cif),
            'direccion' => ucfirst(($request->direccion)),
            'ubi_catastral' => ucfirst(($request->ubi_catastral)),
            'ref_catastral' => strtoupper($request->ref_catastral)
        ]);

        $request->validate([
            'nombre' => 'required|string|min:2|max:255',
            'cif' => 'required|string|regex:/^[ABCDEFGHJKLMNPQRSUVW]\d{8}$/|unique:comunidades,cif',
            'direccion' => 'nullable|string|min:3|max:255',
            'ubi_catastral' => 'nullable|string|min:3|max:255',
            'ref_catastral' => 'nullable|string|regex:/^[0-9A-Z]{20}$/',
            'telefono' => ['nullable', 'phone:ES,US,FR,GB,DE,IT,PT,MX,AR,BR,INTL'],
            'usuarios' => 'array', // Validar que usuarios sea un array
            'usuarios.*' => 'exists:users,id', // Validar que cada usuario exista
        ], [
            'nombre.min' => 'El nombre debe tener al menos 2 caracteres.',
            'nombre.max' => 'El nombre no puede tener más de 255 caracteres.',
            'nombre.required' => 'El nombre es obligatorio.',
            'cif.required' => 'El CIF es obligatorio',
            'cif.regex' => 'El CIF debe comenzar con una letra válida y seguir con 8 dígitos.',
            'cif.unique' => 'El CIF ya está en uso.',
            'direccion.min' => 'La dirección debe tener al menos 3 caracteres.',
            'direccion.max' => 'La dirección no puede exceder de 255 caracteres.',
            'ubi_catastral.min' => 'La ubicación catastral debe tener al menos 3 caracteres.',
            'ubi_catastral.max' => 'La ubicación catastral no puede exceder de 255 caracteres.',
            'ref_catastral.regex' => 'La referencia catastral debe tener 20 caracteres alfanuméricos.',
            'telefono' => 'Formato de teléfono incorrecto',
            'usuarios.exists' => 'Alguno de los usuarios seleccionados no existe.',
        ]);
        try {
            // Actualizar los datos de la comunidad excepto los usuarios
            $comunidad->update($request->except('usuarios'));

            // Sincronizar los usuarios seleccionados con la tabla pivote
            if ($request->has('usuarios')) {
                $comunidad->users()->sync($request->usuarios);
            }

            Log::info('✅ Comunidad actualizada correctamente.', [
                'comunidad_id' => $comunidad->id,
                'user_id' => Auth::id(),
            ]);

            return redirect()->route('comunidades.index')
            ->with([
                    'success' => [
                        'id' => uniqid(),
                        'mensaje' => "Comunidad actualizada correctamente",
                    ],
                ]);
        } catch (Throwable $e) {
            Log::error('❌ Error al actualizar comunidad:', ['exception' => $e]);
            return redirect()->back()->with([
                    'error' => [
                        'id' => uniqid(),
                        'mensaje' => "Error al actualizar la comunidad",
                    ],
                ]);
            // ->withErrors('Ocurrió un error al actualizar la comunidad.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $comunidad = Comunidad::findOrFail($id); // Buscar la comunidad por ID
            $comunidad->users()->detach(); // Desvincular usuarios de la comunidad
            $comunidad->caracteristica()->delete(); // Eliminar la característica asociada
            $comunidad->presupuestos()->delete(); // Eliminar los presupuestos asociados
            $comunidad->polizas()->delete(); // Eliminar las pólizas asociadas
            $comunidad->delete(); // Eliminar la comunidad

            Log::info('✅ Comunidad eliminada correctamente.', [
                'comunidad_id' => $id,
                'user_id' => Auth::id(),
            ]);

            return redirect()->route('comunidades.index')->with([
                    'success' => [
                        'id' => uniqid(),
                        'mensaje' => "Comunidad eliminada correctamente",
                    ],
                ]);
        } catch (Throwable $e) {
            Log::error('❌ Error al eliminar comunidad:', ['exception' => $e]);
            return redirect()->back()->with([
                    'error' => [
                        'id' => uniqid(),
                        'mensaje' => "Error al eliminar la comunidad",
                    ],
                ]);
            // ->withErrors('Ocurrió un error al eliminar la comunidad.');
        }
    }
}
