<?php

namespace App\Http\Controllers;

use App\Models\Siniestro;
use App\Models\Poliza;
use App\Models\Contacto;
use App\Models\Comunidad;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

use App\Http\Middleware\CheckPermiso;

class SiniestroController extends Controller
{
    public function __construct()
    {
        // Aplica middleware de permisos a métodos específicos
        $this->middleware(CheckPermiso::class . ':siniestros.ver', ['only' => ['index', 'show']]);
        $this->middleware(CheckPermiso::class . ':siniestros.crear', ['only' => ['store']]);
        $this->middleware(CheckPermiso::class . ':siniestros.editar', ['only' => ['update']]);
        $this->middleware(CheckPermiso::class . ':siniestros.eliminar', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user(); // Obtener el usuario autenticado

        // Verificar si el usuario tiene el rol de administrador
        if ($user->rol->nombre == 'Superadministrador') {
            $siniestros = Siniestro::with('poliza', 'contactos')->get(); // Obtener todos los siniestros con sus relaciones
            $polizas = Poliza::all(); // Obtener todas las pólizas
        } else {
            // Obtener comunidades donde el usuario es propietario O está asignado como usuario
            $comunidades = Comunidad::where('id_propietario', $user->id)
                ->orWhereHas('users', function ($query) use ($user) {
                    $query->where('users.id', $user->id);
                })
                ->get();
            $siniestros = Siniestro::whereIn('id_poliza', Poliza::whereIn('id_comunidad', $comunidades->pluck('id'))->pluck('id'))
                ->with(['poliza', 'contactos'])
                ->get();
            $polizas = Poliza::whereIn('id_comunidad', $comunidades->pluck('id'))->get(); // Obtener pólizas de las comunidades del usuario
        }

        return Inertia::render('siniestros/index', [
            'siniestros' => $siniestros,
            'polizas' => $polizas,
        ]); // Retornar la vista con la lista de siniestros
    }

    /**
     * Show the form for creating a new resource.
     */
    // public function create()
    // {
    //     $polizas = Poliza::all(); // Obtener todas las pólizas
    //     $contactos = Contacto::all(); // Obtener todos los contactos
    //     return view('siniestros.create', compact('polizas', 'contactos')); // Retornar la vista para crear un nuevo siniestro
    // }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->merge([
            'declaracion' => ucfirst(($request->declaracion)),
            'tramitador' => ucfirst(($request->tramitador)),
        ]);

        $request->validate([
            'id_poliza' => 'required|exists:polizas,id',
            'declaracion' => 'required|string|min:10',
            'tramitador' => 'nullable|string|min:2|max:255',
            'expediente' => 'required|string|min:2|max:50',
            'exp_cia' => 'nullable|string|min:2|max:50',
            'exp_asist' => 'nullable|string|min:2|max:50',
            'fecha_ocurrencia' => 'nullable|date',
            'adjunto' => 'nullable|file|max:2048',
            'contactos' => 'nullable|array',

            // Validación de contactos
            'contactos.*.nombre' => 'required|string|min:2|max:100',
            'contactos.*.telefono' => ['required', 'phone:ES,US,FR,GB,DE,IT,PT,MX,AR,BR,INTL'],
        ], [
            'id_poliza.required' => 'La póliza es obligatoria.',
            'declaracion.min' => 'La declaración debe tener al menos 10 caracteres.',
            'tramitador.min' => 'El tramitador debe tener al menos 2 caracteres.',
            'expediente.min' => 'El expediente debe tener al menos 2 caracteres.',
            'exp_cia.min' => 'La compañía debe tener al menos 2 caracteres.',
            'exp_asist.min' => 'El asistente debe tener al menos 2 caracteres.',
            'adjunto.file' => 'El archivo adjunto no es válido.',
            'adjunto.mimes' => 'El archivo adjunto debe ser un archivo de tipo: pdf, jpg, jpeg, png.',
            'adjunto.max' => 'El archivo adjunto no puede ser mayor de 2MB.',
            'contactos.*.nombre.required' => 'El nombre del contacto es obligatorio.',
            'contactos.*.nombre.min' => 'El nombre del contacto debe tener al menos 2 caracteres.',
            'contactos.*.telefono.required' => 'El teléfono del contacto es obligatorio.',
            'contactos.*.telefono' => 'Formato de teléfono incorrecto',
        ]);

        $data = $request->except(['contactos', 'adjunto']);

        // Manejar el archivo adjunto
        if ($request->hasFile('adjunto')) {
            $file = $request->file('adjunto');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/adjuntos', $fileName);
            $data['adjunto'] = true; // Si hay archivo, guardamos true
        } else {
            $data['adjunto'] = false; // Si no hay archivo, guardamos false
        }

        $siniestro = Siniestro::create($data);

        if ($request->has('contactos')) {
            foreach ($request->contactos as $contacto) {
                $siniestro->contactos()->create($contacto);
            }
        }

        return redirect()->route('siniestros.index')
            ->with('success', 'Siniestro creado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $siniestro = Siniestro::with('poliza.compania', 'contactos', 'chats.usuario')->findOrFail($id); // Buscar el siniestro por ID
        $this->authorize('view', $siniestro);
        //dd($siniestro); // Debugging: Verificar el siniestro cargado
        return Inertia::render('siniestros/show', [
            'chats' => $siniestro->chats, // Pasar los chats a la vista
            'authUser' => Auth::id(), // Pasar el ID del usuario autenticado a la vista
            'siniestro' => $siniestro, // Pasar el siniestro a la vista
            'contactos' => $siniestro->contactos, // Pasar los contactos a la vista
            'poliza' => $siniestro->poliza, // Pasar la póliza a la vista
        ]); // Retornar la vista con los detalles del siniestro
    }

    /**
     * Show the form for editing the specified resource.
     */
    // public function edit(Siniestro $siniestro)
    // {
    //     $polizas = Poliza::all(); // Obtener todas las pólizas
    //     $contactos = Contacto::all(); // Obtener todos los contactos
    //     return view('siniestros.edit', compact('siniestro', 'polizas', 'contactos')); // Retornar la vista para editar el siniestro
    // }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $siniestro = Siniestro::findOrFail($id);
        $this->authorize('update', $siniestro);

        $request->merge([
            'declaracion' => ucfirst(($request->declaracion)),
            'tramitador' => ucfirst(($request->tramitador)),
        ]);

        $request->validate([
            'id_poliza' => 'required|exists:polizas,id',
            'declaracion' => 'required|string|min:10',
            'tramitador' => 'nullable|string|min:2|max:255',
            'expediente' => 'required|string|min:2|max:50',
            'exp_cia' => 'nullable|string|min:2|max:50',
            'exp_asist' => 'nullable|string|min:2|max:50',
            'fecha_ocurrencia' => 'nullable|date',
            'adjunto' => 'nullable|file|max:2048',
            'contactos' => 'nullable|array',

            // Validación de contactos
            'contactos.*.nombre' => 'required|string|min:2|max:100',
            'contactos.*.telefono' => ['required', 'phone:ES,US,FR,GB,DE,IT,PT,MX,AR,BR,INTL'],
        ], [
            'id_poliza.required' => 'La póliza es obligatoria.',
            'declaracion.min' => 'La declaración debe tener al menos 2 caracteres.',
            'tramitador.min' => 'El tramitador debe tener al menos 2 caracteres.',
            'expediente.min' => 'El expediente debe tener al menos 2 caracteres.',
            'exp_cia.min' => 'La compañía debe tener al menos 2 caracteres.',
            'exp_asist.min' => 'El asistente debe tener al menos 2 caracteres.',
            'adjunto.file' => 'El archivo adjunto no es válido.',
            'adjunto.mimes' => 'El archivo adjunto debe ser un archivo de tipo: pdf, jpg, jpeg, png.',
            'adjunto.max' => 'El archivo adjunto no puede ser mayor de 2MB.',
            'contactos.*.nombre.required' => 'El nombre del contacto es obligatorio.',
            'contactos.*.nombre.min' => 'El nombre del contacto debe tener al menos 2 caracteres.',
            'contactos.*.telefono.required' => 'El teléfono del contacto es obligatorio.',
            'contactos.*.telefono' => 'Formato de teléfono incorrecto',
        ]);

        $siniestro->update($request->except('contactos', 'adjunto'));
        // Manejar el archivo adjunto
        if ($request->hasFile('adjunto')) {
            $file = $request->file('adjunto');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/adjuntos', $fileName);
            $data['adjunto'] = true; // Si hay archivo, guardamos true
        } else {
            $data['adjunto'] = false; // Si no hay archivo, guardamos false
        }
        $siniestro->adjunto = $data['adjunto'];
        $siniestro->save();

        if ($request->has('contactos')) {
            // Eliminar contactos existentes
            $siniestro->contactos()->delete();

            // Crear nuevos contactos
            foreach ($request->contactos as $contacto) {
                $siniestro->contactos()->create($contacto);
            }
        }

        return redirect()->route('siniestros.index')
            ->with('success', 'Siniestro actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $siniestro = Siniestro::findOrFail($id);
        $this->authorize('delete', $siniestro);

        $siniestro->contactos()->delete(); // Eliminar todos los contactos asociados
        $siniestro->delete(); // Eliminar el siniestro

        return redirect()->route('siniestros.index')
            ->with('success', 'Siniestro eliminado correctamente.');
    }
}
