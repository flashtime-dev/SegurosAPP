<?php

namespace App\Http\Controllers;

use App\Models\Siniestro;
use App\Models\Poliza;
use App\Models\Contacto;
use App\Models\Comunidad;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use App\Http\Middleware\CheckPermiso;

use Illuminate\Support\Facades\Log;
use Throwable;

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
        try {
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
            Log::info("SiniestroController@index - Siniestros cargados correctamente");

            return Inertia::render('siniestros/index', [
                'siniestros' => $siniestros,
                'polizas' => $polizas,
            ]); // Retornar la vista con la lista de siniestros
        } catch (Throwable $e) {
            Log::error('Error en SiniestroController@index: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->route('home')->with('error', 'Error al cargar la lista de siniestros.');
        }
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
            'declaracion' => ucfirst($request->declaracion),
            'tramitador' => ucfirst($request->tramitador),
        ]);

        $request->validate([
            'id_poliza' => 'required|exists:polizas,id',
            'declaracion' => 'required|string|min:10',
            'tramitador' => 'nullable|string|min:2|max:255',
            'expediente' => 'required|string|min:2|max:50',
            'exp_cia' => 'nullable|string|min:2|max:50',
            'exp_asist' => 'nullable|string|min:2|max:50',
            'fecha_ocurrencia' => 'nullable|date',
            'files' => 'nullable|array',
            'files.*' => 'file|max:2048|mimes:pdf,jpg,jpeg,png',
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
            'files.array' => 'Los archivos deben ser un arreglo.',
            'files.*.file' => 'Cada archivo no es válido.',
            'files.*.mimes' => 'Cada archivo debe ser pdf, jpg, jpeg o png.',
            'files.*.max' => 'Cada archivo no puede ser mayor de 2MB.',
            'contactos.*.nombre.required' => 'El nombre del contacto es obligatorio.',
            'contactos.*.nombre.min' => 'El nombre del contacto debe tener al menos 2 caracteres.',
            'contactos.*.telefono.required' => 'El teléfono del contacto es obligatorio.',
            'contactos.*.telefono' => 'Formato de teléfono incorrecto',
        ]);
        try {
            $data = $request->except(['contactos', 'files']);

            // Determinar si hay archivos adjuntos
            $data['adjunto'] = $request->hasFile('files') && count($request->file('files')) > 0;

            // Crear el siniestro primero
            $siniestro = Siniestro::create($data);

            // Guardar archivos y crear registros en adjuntos_siniestros
            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $file) {
                    $path = $file->storeAs("siniestros/s-{$siniestro->id}", $file->getClientOriginalName());

                    if ($path) {
                        // Si el almacenamiento es exitoso, generar la URL del archivo
                        $pdfUrl = asset('storage/' . $path);
                    }

                    // Crear registro en adjuntos_siniestros
                    $siniestro->adjuntos()->create([
                        'nombre' => $file->getClientOriginalName(),
                        'url_adjunto' => $pdfUrl,
                        // 'id_chat' => null // si lo necesitas puedes agregarlo aquí
                    ]);
                }
            }

            // Crear contactos si hay
            if ($request->has('contactos')) {
                foreach ($request->contactos as $contacto) {
                    $siniestro->contactos()->create($contacto);
                }
            }
            Log::info("SiniestroController@store - Siniestro creado correctamente", ['id' => $siniestro->id]);
            return redirect()->route('siniestros.index')
                ->with('success', "Siniestro creado correctamente.");
        } catch (Throwable $e) {
            Log::error('Error en SiniestroController@store: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()->withInput()->with('error', 'Error al crear el siniestro.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $siniestro = Siniestro::with('poliza.compania', 'contactos', 'chats.usuario', 'adjuntos')->findOrFail($id); // Buscar el siniestro por ID
            $this->authorize('view', $siniestro);
            //dd($siniestro); // Debugging: Verificar el siniestro cargado
            Log::info("SiniestroController@show - Mostrando siniestro ID {$id}");
            return Inertia::render('siniestros/show', [
                'chats' => $siniestro->chats, // Pasar los chats a la vista
                'authUser' => Auth::id(), // Pasar el ID del usuario autenticado a la vista
                'siniestro' => $siniestro, // Pasar el siniestro a la vista
                'contactos' => $siniestro->contactos, // Pasar los contactos a la vista
                'poliza' => $siniestro->poliza, // Pasar la póliza a la vista
            ]); // Retornar la vista con los detalles del siniestro
        } catch (Throwable $e) {
            Log::error('Error en SiniestroController@show: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->route('siniestros.index')->with('error', 'Error al cargar el siniestro.');
        }
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
    public function update(Request $request, Siniestro $siniestro)
    {

        // Normalizar mayúsculas en ciertos campos
        $request->merge([
            'declaracion' => ucfirst($request->declaracion),
            'tramitador'  => ucfirst($request->tramitador),
        ]);

        // Validación
        $request->validate([
            'id_poliza'        => 'required|exists:polizas,id',
            'declaracion'      => 'required|string|min:10',
            'tramitador'       => 'nullable|string|min:2|max:255',
            'expediente'       => 'required|string|min:2|max:50',
            'exp_cia'          => 'nullable|string|min:2|max:50',
            'exp_asist'        => 'nullable|string|min:2|max:50',
            'fecha_ocurrencia' => 'nullable|date',
            'files'            => 'nullable|array',
            'files.*'          => 'file|max:2048|mimes:pdf,jpg,jpeg,png',
            'contactos'        => 'nullable|array',
            // Validación de contactos
            'contactos.*.nombre' => 'required|string|min:2|max:100',
            'contactos.*.telefono' => ['required', 'phone:ES,US,FR,GB,DE,IT,PT,MX,AR,BR,INTL'],
        ], [
            'id_poliza.required'     => 'La póliza es obligatoria.',
            'declaracion.min'        => 'La declaración debe tener al menos 10 caracteres.',
            'tramitador.min'         => 'El tramitador debe tener al menos 2 caracteres.',
            'expediente.min'         => 'El expediente debe tener al menos 2 caracteres.',
            'exp_cia.min'            => 'La compañía debe tener al menos 2 caracteres.',
            'exp_asist.min'          => 'El asistente debe tener al menos 2 caracteres.',
            'files.array'            => 'Los archivos deben ser un arreglo.',
            'files.*.file'           => 'Cada archivo no es válido.',
            'files.*.mimes'          => 'Cada archivo debe ser pdf, jpg, jpeg o png.',
            'files.*.max'            => 'Cada archivo no puede ser mayor de 2MB.',
            'contactos.*.nombre.required' => 'El nombre del contacto es obligatorio.',
            'contactos.*.nombre.min' => 'El nombre del contacto debe tener al menos 2 caracteres.',
            'contactos.*.telefono.required' => 'El teléfono del contacto es obligatorio.',
            'contactos.*.telefono' => 'Formato de teléfono incorrecto',
        ]);

        try {
            // Preparar datos excepto contactos y archivos
            $data = $request->except(['contactos', 'files']);

            // Determinar si hay archivos adjuntos
            $data['adjunto'] = $request->hasFile('files') && count($request->file('files')) > 0;

            // Actualizar el siniestro en la BD
            $siniestro->update($data);

            // Si vienen archivos nuevos, borrar primero los anteriores (físicos y registros)
            if ($request->hasFile('files')) {
                // Eliminar toda la carpeta física de adjuntos de este siniestro
                $carpeta = "siniestros/s-{$siniestro->id}";
                if (Storage::exists($carpeta)) {
                    Storage::deleteDirectory($carpeta);
                }

                // Borrar todos los registros de adjuntos en la BD relacionados con este siniestro
                $siniestro->adjuntos()->delete();

                // Subir y guardar cada archivo nuevo, generando su registro en adjuntos_siniestros
                foreach ($request->file('files') as $file) {
                    // Usamos storeAs para mantener el nombre original dentro de la carpeta del siniestro
                    $ruta = $file->storeAs($carpeta, $file->getClientOriginalName());

                    if ($ruta) {
                        // Generar la URL pública (asumiendo que storage:link ya está creado)
                        $url = asset("storage/{$ruta}");
                    } else {
                        $url = null;
                    }

                    // Crear registro en la relación "adjuntos"
                    $siniestro->adjuntos()->create([
                        'nombre'       => $file->getClientOriginalName(),
                        'url_adjunto'  => $url,
                        // 'id_chat'   => null // si necesitas ese campo, agrégalo
                    ]);
                }
            }

            // Gestionar contactos: eliminar los viejos y crear los nuevos (si vienen)
            if ($request->has('contactos')) {
                // Borrar contactos anteriores
                $siniestro->contactos()->delete();

                // Crear nuevos registros de contactos
                foreach ($request->contactos as $contacto) {
                    $siniestro->contactos()->create($contacto);
                }
            }

            Log::info("SiniestroController@update - Siniestro actualizado correctamente", ['id' => $siniestro->id]);
            return redirect()
                ->route('siniestros.index')
                ->with([
                    'success' => [
                        'id' => uniqid(),
                        'mensaje' => "Siniestro actualizado correctamente.",
                    ],
                ]);
        } catch (Throwable $e) {
            Log::error('Error en SiniestroController@update: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()->withInput()->with('error', 'Error al actualizar el siniestro.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $siniestro = Siniestro::findOrFail($id);
            $this->authorize('delete', $siniestro);

            // Borrar toda la carpeta de archivos (si existe).
            $carpeta = "siniestros/s-{$siniestro->id}";
            if (Storage::exists($carpeta)) {
                Storage::deleteDirectory($carpeta);
            }

            // Eliminar todos los registros de adjuntos en la BD de una sola vez
            $siniestro->adjuntos()->delete();

            // Borrar todos los contactos asociados
            $siniestro->contactos()->delete();

            // Eliminar el siniestro
            $siniestro->delete();

            Log::info("SiniestroController@destroy - Siniestro eliminado correctamente", ['id' => $id]);

            return redirect()
                ->route('siniestros.index')
                ->with('success', 'Siniestro y archivos eliminados correctamente.');
        } catch (Throwable $e) {
            Log::error('Error en SiniestroController@destroy: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->route('siniestros.index')->with('error', 'Error al eliminar el siniestro.');
        }
    }

    public function servePDF($id, $filename)
    {
        try {
            $siniestro = Siniestro::findOrFail($id);
            $this->authorize('view', $siniestro);

            // Validar que el archivo solicitado pertenece al siniestro
            $adjunto = $siniestro->adjuntos()
                ->where('url_adjunto', 'like', "%/s-{$id}/{$filename}")
                ->first();

            if (!$adjunto) {
                Log::warning("Archivo no encontrado o no autorizado para siniestro {$id}: {$filename}");
                abort(404, 'El archivo solicitado no está asociado a este siniestro.');
            }

            // Construir la ruta real del archivo
            $path = storage_path("app/private/siniestros/s-{$id}/{$filename}");

            if (!file_exists($path)) {
                Log::warning("Archivo físico no encontrado: {$path}");
                abort(404, 'Archivo no encontrado en el servidor.');
            }
            Log::info("Archivo PDF servido correctamente: siniestro {$id} - {$filename}");
            return Response::file($path, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . $filename . '"',
            ]);
        } catch (Throwable $e) {
            Log::error("Error en SiniestroController@servePDF: " . $e->getMessage(), [
                'siniestro_id' => $id,
                'filename' => $filename,
                'exception' => $e
            ]);
            return $this->handleException($e, 'SiniestroController@servePDF', 'siniestros.index', 'Error al servir el archivo PDF.');
        }
    }
}
