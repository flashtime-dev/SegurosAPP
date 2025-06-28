<?php

namespace App\Http\Controllers;

use App\Models\Siniestro;
use App\Models\Poliza;
use App\Models\Comunidad;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use App\Http\Middleware\CheckPermiso;

use Illuminate\Support\Facades\Log;
use Throwable;
use Illuminate\Auth\Access\AuthorizationException;

/**
 * Controlador para gestionar las operaciones relacionadas con los siniestros.
 * Incluye métodos para listar, crear, actualizar, eliminar y mostrar siniestros.
 */
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
     * Muestra una lista de los siniestros.
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
                // Obtener comunidades donde el usuario es propietario o está asignado como usuario
                $comunidades = Comunidad::where('id_propietario', $user->id)
                    ->orWhereHas('users', function ($query) use ($user) {
                        $query->where('users.id', $user->id);
                    })
                    ->with('users')
                    ->get();

                //obtener siniestros de las pólizas de las comunidades del usuario
                $siniestros = Siniestro::whereIn('id_poliza', Poliza::whereIn('id_comunidad', $comunidades->pluck('id'))->pluck('id'))
                    ->with(['poliza', 'contactos'])
                    ->get();

                // Obtener pólizas de las comunidades del usuario
                $polizas = Poliza::whereIn('id_comunidad', $comunidades->pluck('id'))->get();
                //dd($polizas); // Debugging: Verificar las pólizas cargadas
            }
            Log::info("SiniestroController@index - Siniestros cargados correctamente");

            return Inertia::render('siniestros/index', [
                'siniestros' => $siniestros,
                'polizas' => $polizas,
            ]); // Retornar la vista con la lista de siniestros
        } catch (Throwable $e) {
            Log::error('Error en SiniestroController@index: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()->with([
                'error' => [
                    'id' => uniqid(),
                    'mensaje' => "Error al cargar la lista de siniestros",
                ],
            ]);
        }
    }

    /**
     *  Almacena un nuevo siniestro en la base de datos.
     */
    public function store(Request $request)
    {
        // Capitalizar solo la primera palabra de los campos declaracion y tramitador
        $request->merge([
            'declaracion' => ucfirst($request->declaracion),
        ]);

        // Validación de los datos del siniestro
        $request->validate([
            'id_poliza' => 'required|exists:polizas,id',
            'declaracion' => 'required|string|min:10|max:1000',
            'expediente' => 'required|string|min:2|max:50',
            'exp_cia' => 'nullable|string|min:2|max:50',
            'exp_asist' => 'nullable|string|min:2|max:50',
            'fecha_ocurrencia' => 'nullable|date|before_or_equal:today',
            'files' => 'nullable|array',
            'files.*' => 'file|max:2048|mimes:pdf,jpg,jpeg,png',
            'contactos' => 'nullable|array',
            // Validación de contactos
            'contactos.*.nombre' => 'required|string|min:2|max:255',
            'contactos.*.cargo' => 'nullable|string|min:3|max:100',
            'contactos.*.piso' => 'nullable|string|min:1|max:100',
            'contactos.*.telefono' => ['required', 'phone:ES,US,FR,GB,DE,IT,PT,MX,AR,BR,INTL'],
        ], [
            'id_poliza.required' => 'La póliza es obligatoria.',
            'id_poliza.exists' => 'La póliza seleccionada no es válida.',
            'declaracion.required' => 'La declaración es obligatoria.',
            'declaracion.min' => 'La declaración debe tener al menos 10 caracteres.',
            'declaracion.max' => 'La declaración no puede exceder los 1000 caracteres.',
            'expediente.required' => 'El tramitador es obligatorio.',
            'expediente.min' => 'El expediente debe tener al menos 2 caracteres.',
            'expediente.max' => 'El expediente no puede exceder los 50 caracteres.',
            'exp_cia.min' => 'El expediente CIA debe tener al menos 2 caracteres.',
            'exp_cia.max' => 'El expediente CIA no puede exceder los 50 caracteres.',
            'exp_asist.min' => 'El expediente asistencia debe tener al menos 2 caracteres.',
            'exp_asist.max' => 'El expediente asistencia no puede exceder los 50 caracteres.',
            'fecha_efecto.before_or_equal' => 'La fecha de efecto no puede ser mayor a hoy.',
            'files.array' => 'Los archivos deben ser un array.',
            'files.*.file' => 'El archivo debe ser pdf, jpg, jpeg o png.',
            'files.*.mimes' => 'El archivo debe ser pdf, jpg, jpeg o png.',
            'files.*.max' => 'El archivo excede el límite de 2MB.',
            'contactos.*.nombre.required' => 'El nombre es obligatorio.',
            'contactos.*.nombre.min' => 'El nombre debe tener al menos 2 caracteres.',
            'contactos.*.nombre.max' => 'El nombre no puede exceder los 255 caracteres.',
            'contactos.*.cargo.min' => 'El cargo debe tener al menos 3 caracteres.',
            'contactos.*.cargo.max' => 'El cargo no puede exceder los 100 caracteres.',
            'contactos.*.piso.min' => 'El piso debe tener al menos 1 carácter.',
            'contactos.*.piso.max' => 'El piso no puede exceder los 100 caracteres.',
            'contactos.*.telefono.required' => 'El teléfono es obligatorio.',
            'contactos.*.telefono' => 'Formato de teléfono incorrecto',
        ]);
        try {
            // Preparar datos excepto contactos y archivos
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

                    // Cambiar el adjunto a true
                    $siniestro->adjunto = true;
                    $siniestro->save();
                }
            }

            // Crear contactos si hay
            if ($request->has('contactos')) {
                foreach ($request->contactos as $contacto) {
                    $siniestro->contactos()->create($contacto);
                }
            }

            Log::info("SiniestroController@store - Siniestro creado correctamente", ['id' => $siniestro->id]);
            // Redirigir a la lista de siniestros con un mensaje de éxito
            return redirect()->route('siniestros.index')->with([
                'success' => [
                    'id' => uniqid(),
                    'mensaje' => "Siniestro creado correctamente",
                ],
            ]);
        } catch (Throwable $e) {
            Log::error('Error en SiniestroController@store: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()->withInput()->with([
                'error' => [
                    'id' => uniqid(),
                    'mensaje' => "Error al crear el siniestro",
                ],
            ]);
        }
    }

    /**
     * Mostrar los detalles de un siniestro específico.
     */
    public function show($id)
    {
        try {
            $siniestro = Siniestro::with('poliza.compania', 'contactos', 'chats.usuario', 'adjuntos')->findOrFail($id); // Buscar el siniestro por ID
            //dd($siniestro); // Debugging: Verificar el siniestro cargado

            // Verificar si el usuario tiene permiso para ver el siniestro
            $this->authorize('view', $siniestro);

            Log::info("SiniestroController@show - Mostrando siniestro ID {$id}");

            return Inertia::render('siniestros/show', [
                'chats' => $siniestro->chats, // Pasar los chats a la vista
                'authUser' => Auth::id(), // Pasar el ID del usuario autenticado a la vista
                'siniestro' => $siniestro, // Pasar el siniestro a la vista
                'contactos' => $siniestro->contactos, // Pasar los contactos a la vista
                'poliza' => $siniestro->poliza, // Pasar la póliza a la vista
            ]); // Retornar la vista con los detalles del siniestro
        } catch (AuthorizationException $e) {
            Log::warning("Acceso denegado al siniestro ID {$id} por el usuario ID " . Auth::id());

            return redirect()->route('siniestros.index')->with([
                'error' => [
                    'id' => uniqid(),
                    'mensaje' => "No tienes acceso a este siniestro",
                ],
            ]);
        } catch (Throwable $e) {
            Log::error('Error en SiniestroController@show: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->route('siniestros.index')->with([
                'error' => [
                    'id' => uniqid(),
                    'mensaje' => "Error al cargar el siniestro",
                ],
            ]);
        }
    }

    /**
     *  Actualiza un siniestro existente en la base de datos.
     */
    public function update(Request $request, Siniestro $siniestro)
    {

        // Normalizar mayúsculas en ciertos campos
        $request->merge([
            'declaracion' => ucfirst($request->declaracion),
        ]);

        // Validación
        $request->validate([
            'id_poliza'        => 'required|exists:polizas,id',
            'declaracion'      => 'required|string|min:10|max:1000',
            'expediente'       => 'required|string|min:2|max:50',
            'exp_cia'          => 'nullable|string|min:2|max:50',
            'exp_asist'        => 'nullable|string|min:2|max:50',
            'fecha_ocurrencia' => 'nullable|date|before_or_equal:today',
            'files'            => 'nullable|array',
            'files.*'          => 'file|max:2048|mimes:pdf,jpg,jpeg,png',
            'contactos'        => 'nullable|array',
            // Validación de contactos
            'contactos.*.nombre' => 'required|string|min:2|max:255',
            'contactos.*.cargo' => 'nullable|string|min:3|max:100',
            'contactos.*.piso' => 'nullable|string|min:1|max:100',
            'contactos.*.telefono' => ['required', 'phone:ES,US,FR,GB,DE,IT,PT,MX,AR,BR,INTL'],
        ], [
            'id_poliza.required' => 'La póliza es obligatoria.',
            'id_poliza.exists' => 'La póliza seleccionada no es válida.',
            'declaracion.required' => 'La declaración es obligatoria.',
            'declaracion.min' => 'La declaración debe tener al menos 10 caracteres.',
            'declaracion.max' => 'La declaración no puede exceder los 1000 caracteres.',
            'expediente.required' => 'El tramitador es obligatorio.',
            'expediente.min' => 'El expediente debe tener al menos 2 caracteres.',
            'expediente.max' => 'El expediente no puede exceder los 50 caracteres.',
            'exp_cia.min' => 'El expediente CIA debe tener al menos 2 caracteres.',
            'exp_cia.max' => 'El expediente CIA no puede exceder los 50 caracteres.',
            'exp_asist.min' => 'El expediente asistencia debe tener al menos 2 caracteres.',
            'exp_asist.max' => 'El expediente asistencia no puede exceder los 50 caracteres.',
            'fecha_efecto.before_or_equal' => 'La fecha de efecto no puede ser mayor a hoy.',
            'files.array' => 'Los archivos deben ser un array.',
            'files.*.file' => 'Algún archivo no es válido.',
            'files.*.mimes' => 'Cada archivo debe ser pdf, jpg, jpeg o png.',
            'files.*.max' => 'Los archivos no pueden ser mayor de 2MB.',
            'contactos.*.nombre.required' => 'El nombre es obligatorio.',
            'contactos.*.nombre.min' => 'El nombre debe tener al menos 2 caracteres.',
            'contactos.*.nombre.max' => 'El nombre no puede exceder los 255 caracteres.',
            'contactos.*.cargo.min' => 'El cargo debe tener al menos 3 caracteres.',
            'contactos.*.cargo.max' => 'El cargo no puede exceder los 100 caracteres.',
            'contactos.*.piso.min' => 'El piso debe tener al menos 1 carácter.',
            'contactos.*.piso.max' => 'El piso no puede exceder los 100 caracteres.',
            'contactos.*.telefono.required' => 'El teléfono es obligatorio.',
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

                // Cambiar el adjunto a true
                $siniestro->adjunto = true;
                $siniestro->save();
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
                        'mensaje' => "Siniestro actualizado correctamente",
                    ],
                ]);
        } catch (Throwable $e) {
            Log::error('Error en SiniestroController@update: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()->withInput()->with([
                'error' => [
                    'id' => uniqid(),
                    'mensaje' => "Error al actualizar el siniestro",
                ],
            ]);
        }
    }

    /**
     * Elimina un siniestro y sus archivos asociados.
     */
    public function destroy($id)
    {
        try {
            // Buscar el siniestro por ID 
            $siniestro = Siniestro::findOrFail($id);

            // Verificar si el usuario tiene permiso para eliminar el siniestro
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
                ->route('siniestros.index')->with([
                    'success' => [
                        'id' => uniqid(),
                        'mensaje' => "Siniestro y archivos eliminados correctamente",
                    ],
                ]);
        } catch (Throwable $e) {
            Log::error('Error en SiniestroController@destroy: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->route('siniestros.index')->with([
                'error' => [
                    'id' => uniqid(),
                    'mensaje' => "Error al eliminar el siniestro",
                ],
            ]);
        }
    }

    /**
     *  Devuelve un archivo PDF asociado a un siniestro.
     */
    public function servePDF($id, $filename)
    {
        try {
            // Buscar el siniestro por ID
            $siniestro = Siniestro::findOrFail($id);

            // Verificar si el usuario tiene permiso para ver el siniestro
            $this->authorize('view', $siniestro);

            // Validar que el archivo solicitado pertenece al siniestro
            $adjunto = $siniestro->adjuntos()
                ->where('url_adjunto', 'like', "%/s-{$id}/{$filename}")
                ->first();

            // Si no se encuentra el adjunto, abortar con error 404
            if (!$adjunto) {
                Log::warning("Archivo no encontrado o no autorizado para siniestro {$id}: {$filename}");
                abort(404, 'El archivo solicitado no está asociado a este siniestro.');
            }

            // Construir la ruta real del archivo
            $path = storage_path("app/private/siniestros/s-{$id}/{$filename}");

            // Verificar si el archivo físico existe
            if (!file_exists($path)) {
                Log::warning("Archivo físico no encontrado: {$path}");
                abort(404, 'Archivo no encontrado en el servidor.');
            }

            // Determinar el tipo de contenido según la extensión del archivo
            $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            $contentType = match ($extension) {
                'pdf'  => 'application/pdf',
                'jpg'  => 'image/jpeg',
                'jpeg' => 'image/jpeg',
                'png'  => 'image/png',
                default => 'application/octet-stream',
            };

            // Registrar el acceso al archivo
            Log::info("Archivo servido correctamente: siniestro {$id} - {$filename}");

            // Devolver el archivo PDF como respuesta
            return Response::file($path, [
                'Content-Type' => $contentType,
                'Content-Disposition' => 'inline; filename="' . $filename . '"',
            ]);
        } catch (Throwable $e) {
            Log::error("Error en SiniestroController@servePDF: " . $e->getMessage(), [
                'siniestro_id' => $id,
                'filename' => $filename,
                'exception' => $e
            ]);
            return redirect()->route('siniestros.index')->with([
                'error' => [
                    'id' => uniqid(),
                    'mensaje' => "Error al mostrar el archivo Adjunto",
                ],
            ]);
        }
    }

    /**
     * Cierra un siniestro cambiando su estado a "Cerrado".
     */
    public function cerrar($id)
    {
        try {
            // Buscar el siniestro por ID
            $siniestro = Siniestro::findOrFail($id);

            // Verificar si el usuario tiene permiso para actualizar el siniestro
            $this->authorize('update', $siniestro);

            // Verificar si el siniestro ya está cerrado (Esto no se utiliza)
            // if ($siniestro->estado === 'Cerrado') {
            //     return back()->with([
            //         'info' => [
            //             'id' => uniqid(),
            //             'mensaje' => 'El siniestro ya está cerrado'
            //         ],
            //     ]);
            // }

            // Cambiar el estado a cerrado
            $siniestro->estado = 'Cerrado';
            $siniestro->save();

            Log::info("SiniestroController@cerrar - Siniestro cerrado correctamente", ['id' => $id]);
            return redirect()->route('siniestros.index')->with([
                'success' => [
                    'id' => uniqid(),
                    'mensaje' => "Siniestro cerrado correctamente",
                ],
            ]);
        } catch (Throwable $e) {
            Log::error("Error al cerrar siniestro: {$e->getMessage()}", ['id' => $id]);
            return redirect()->route('siniestros.index')->with([
                'error' => [
                    'id' => uniqid(),
                    'mensaje' => "Error al cerrar el siniestro",
                ],
            ]);
        }
    }

    /**
     * Reabre un siniestro cambiando su estado a "Abierto".
     */
    public function reabrir($id)
    {
        try {
            // Buscar el siniestro por ID
            $siniestro = Siniestro::findOrFail($id);

            // Verificar si el usuario tiene permiso para actualizar el siniestro
            $this->authorize('update', $siniestro);

            // Verificar si el siniestro ya está abierto (No se utiliza))
            // if ($siniestro->estado === 'Abierto') {
            //     return back()->with([
            //         'info' => [
            //             'id' => uniqid(),
            //             'mensaje' => 'El siniestro ya está abierto',
            //         ],
            //     ]);
            // }

            // Cambiar el estado a abierto
            $siniestro->estado = 'Abierto';
            $siniestro->save();

            Log::info("SiniestroController@reabrir - Siniestro reabierto correctamente", ['id' => $id]);
            return redirect()->route('siniestros.index')->with([
                'success' => [
                    'id' => uniqid(),
                    'mensaje' => "Siniestro reabierto correctamente",
                ],
            ]);
        } catch (Throwable $e) {
            Log::error("Error al reabrir siniestro: {$e->getMessage()}", ['id' => $id]);
            return redirect()->route('siniestros.index')->with([
                'error' => [
                    'id' => uniqid(),
                    'mensaje' => "Error al reabrir el siniestro",
                ],
            ]);
        }
    }
}
