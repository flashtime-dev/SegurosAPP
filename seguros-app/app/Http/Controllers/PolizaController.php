<?php

namespace App\Http\Controllers;

use Throwable;
use Illuminate\Support\Facades\Log;
use App\Models\Poliza;
use App\Models\Compania;
use App\Models\Comunidad;
use App\Models\Agente;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use App\Http\Middleware\CheckPermiso;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use App\Mail\SolicitudAnulacionPoliza;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;

// Este controlador maneja las operaciones CRUD para las pólizas de seguros
class PolizaController extends Controller
{
    public function __construct()
    {
        // Aplica middleware de permisos a métodos específicos

        //Polizas
        $this->middleware(CheckPermiso::class . ':polizas.ver', ['only' => ['index', 'show']]);
        $this->middleware(CheckPermiso::class . ':polizas.crear', ['only' => ['store']]);
        $this->middleware(CheckPermiso::class . ':polizas.editar', ['only' => ['update']]);
        $this->middleware(CheckPermiso::class . ':polizas.eliminar', ['only' => ['destroy']]);
    }

    /**
     * Muestra una lista de las pólizas, dependiendo del rol del usuario.
     * Si es un superadministrador, muestra todas las pólizas.
     * Si es un usuario normal, muestra solo las pólizas de las comunidades donde es propietario o está asignado.
     */
    
    public function index()
    {
        try{
            $user = Auth::user(); // Obtener el usuario autenticado

            // Verificar si el usuario tiene el rol de administrador
            if ($user->rol->nombre == 'Superadministrador') {
                $polizas = Poliza::with(['compania', 'comunidad', 'agente'])->get();
                $companias = Compania::all();
                $comunidades = Comunidad::all();
                $agentes = Agente::all();
            } else {
                // Obtener comunidades donde el usuario es propietario O está asignado como usuario
                $comunidades = Comunidad::where('id_propietario', $user->id)
                    ->orWhereHas('users', function ($query) use ($user) {
                        $query->where('users.id', $user->id);
                    })
                    ->get();
                $polizas = Poliza::whereIn('id_comunidad', $comunidades->pluck('id'))
                    ->with(['compania', 'comunidad', 'agente'])
                    ->get();
                $companias = Compania::all();
                $agentes = Agente::all();
            }

            Log::info('Polizas listadas', ['user_id' => $user->id, 'count' => $polizas->count()]);
            return Inertia::render('polizas/index', [
                'polizas' => $polizas,
                'companias' => $companias,
                'comunidades' => $comunidades,
                'agentes' => $agentes,
            ]);
        } catch (Throwable $e) {
            Log::error('❌ Error al listar las pólizas: ' . $e->getMessage(), [
                'exception' => $e,
                'user_id' => Auth::id(),
            ]);

            return redirect()->back()->with([
                'error' => [
                    'id' => uniqid(),
                    'mensaje' => 'Error al cargar las pólizas',
                ]
            ]);
        }
    }

    /**
     * Almacena una nueva póliza en la base de datos.
     */
    public function store(Request $request)
    {
        // Capitalizar solo la primera palabra del alias antes de la validación
        $request->merge([
            'alias' => ucfirst(($request->alias)),
            'id_agente' => $request->id_agente === 'null' ? null : $request->id_agente,
        ]);

        // Validar los campos del formulario
        $request->validate([
            'id_compania' => 'required|exists:companias,id',
            'id_comunidad' => 'required|exists:comunidades,id',
            'id_agente' => 'nullable|exists:agentes,id',
            'alias' => 'nullable|string|min:2|max:255',
            'numero' => 'required|string|max:20',
            'fecha_efecto' => 'required|date|before_or_equal:today',
            'cuenta' => 'nullable|string|min:20|max:24',
            'forma_pago' => 'required|in:Bianual,Anual,Semestral,Trimestral,Mensual',
            'prima_neta' => 'required|numeric|min:0',
            'prima_total' => 'required|numeric|min:0',
            'pdf_poliza' => 'nullable|file|mimes:pdf|max:2048', // Validar que sea un archivo PDF
            'observaciones' => 'nullable|string|max:1000',
            'estado' => 'required|in:En Vigor,Anulada,Solicitada,Externa,Vencida',
        ], [
            'id_compania.required' => 'La compañía es obligatoria.',
            'id_compania.exists' => 'La compañía seleccionada no es válida.',
            'id_comunidad.required' => 'La comunidad es obligatoria.',
            'id_comunidad.exists' => 'La comunidad seleccionada no es válida.',
            'id_agente.exists' => 'El agente seleccionado no es válido.',
            'alias.min' => 'El alias debe tener al menos 2 caracteres.',
            'alias.max' => 'El alias no debe exceder los 255 carácteres.',
            'numero.required' => 'El número de póliza es obligatorio.',
            'numero.max' => 'El número de póliza no debe exceder los 20 carácteres.',
            'fecha_efecto.required' => 'La fecha de efecto es obligatoria.',
            'fecha_efecto.before_or_equal' => 'La fecha de efecto no puede ser mayor a hoy.',
            'cuenta.min' => 'La cuenta debe tener al menos 20 caracteres.',
            'cuenta.max' => 'La cuenta no debe execeder los 24 carácteres.',
            'forma_pago.required' => 'La forma de pago es obligatoria.',
            'prima_neta.required' => 'La prima neta es obligatoria.',
            'prima_neta.min' => 'La prima neta debe ser un número positivo.',
            'prima_total.required' => 'La prima total es obligatoria.',
            'prima_total.min' => 'La prima total debe ser un número positivo.',
            'pdf_poliza.mimes' => 'El archivo debe ser un PDF.',
            'pdf_poliza.max' => 'El archivo no debe exceder los 2MB.',
            'pdf_poliza.file' => 'El archivo no es válido.',
            'pdf_poliza.max' => 'El archivo no debe execer los 2MB.',
            'observaciones.max' => 'Las observaciones no deben exceder los 1000 carácteres.',
            'estado.required' => 'El estado es obligatorio.',
        ]);

        try {
            // Inicializar la variable para la URL del PDF
            $pdfUrl = null;

            // Verificar si se subió un archivo PDF
            if ($request->hasFile('pdf_poliza')) {
                // Obtener el archivo PDF del formulario
                $pdf_poliza = $request->file('pdf_poliza');

                // Intentar almacenar el archivo en el servidor
                $path = $pdf_poliza->storeAs('polizas', $pdf_poliza->getClientOriginalName());

                // Verificar si el almacenamiento fue exitoso
                if ($path) {
                    // Si el almacenamiento es exitoso, generar la URL del archivo
                    $pdfUrl = asset('storage/' . $path);
                }
            }

            // Crear la póliza con los datos del formulario y la URL del PDF
            Poliza::create(array_merge($request->all(), ['pdf_poliza' => $pdfUrl]));

            Log::info('Póliza creada', ['poliza_id' => $request->id, 'user_id' => Auth::id()]);

            return redirect()->route('polizas.index')->with([
                'success' => [
                    'id' => uniqid(),
                    'mensaje' => "Póliza creada correctamente",
                ],
            ]);
        } catch (Throwable  $e) {
            Log::error('Error al crear póliza: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->route('polizas.index')->with([
                'error' => [
                    'id' => uniqid(),
                    'mensaje' => "Error al crear la póliza",
                ],
            ]);
        }
    }

    /**
     * Muestra los detalles de una póliza específica.
     */
    public function show(string $id)
    {
        try {
            // Cargar la póliza con sus relaciones necesarias
            $poliza = Poliza::with(['compania', 'comunidad', 'siniestros', 'agente', 'chats.usuario'])->findOrFail($id);
            
            // Aplicar politica de autorización
            $this->authorize('view', $poliza);

            $siniestros = $poliza->siniestros;
            $chats = $poliza->chats()->with('usuario')->orderBy('created_at')->get();
            $authUser = Auth::id();
            Log::info('Mostrando póliza', ['poliza_id' => $id, 'user_id' => $authUser]);

            return Inertia::render('polizas/show', [
                'poliza' => $poliza,
                'siniestros' => $siniestros,
                'chats' => $chats,
                'authUser' => $authUser,
            ]);
        } catch (AuthorizationException $e) {
            Log::warning("Acceso denegado a la Póliza ID {$id} por el usuario ID " . Auth::id());

            return redirect()->route('polizas.index')->with([
                'error' => [
                    'id' => uniqid(),
                    'mensaje' => "No tienes acceso a esta póliza",
                ],
            ]);
        } catch (Throwable $e) {
            Log::error('Error en PolizaController@show: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->route('polizas.index')->with([
                'error' => [
                    'id' => uniqid(),
                    'mensaje' => "Error al cargar la póliza",
                ],
            ]);
        }
    }

    /**
     * Actualiza una póliza existente en la base de datos.
     */
    public function update(Request $request, string $id)
    {
        // Busca la póliza por ID o lanza una excepción si no se encuentra
        $poliza = Poliza::findOrFail($id);

        // Verifica autorización para actualizar la póliza
        $this->authorize('update', $poliza);

        // Capitalizar solo la primera palabra del alias antes de la validación
        $request->merge([
            'alias' => ucfirst(($request->alias)),
            'id_agente' => $request->id_agente === 'null' ? null : $request->id_agente,
        ]);

        $request->validate([
            'id_compania' => 'required|exists:companias,id',
            'id_comunidad' => 'required|exists:comunidades,id',
            'id_agente' => 'nullable|exists:agentes,id',
            'alias' => 'nullable|string|min:2|max:255',
            'numero' => 'required|string|max:20',
            'fecha_efecto' => 'required|date|before_or_equal:today',
            'cuenta' => 'nullable|string|min:20|max:24',
            'forma_pago' => 'required|in:Bianual,Anual,Semestral,Trimestral,Mensual',
            'prima_neta' => 'required|numeric|min:0',
            'prima_total' => 'required|numeric|min:0',
            'pdf_poliza' => 'nullable|file|mimes:pdf|max:2048', // Validar que sea un archivo PDF
            'observaciones' => 'nullable|string|max:1000',
            'estado' => 'required|in:En Vigor,Anulada,Solicitada,Externa,Vencida',
        ], [
            'id_compania.required' => 'La compañía es obligatoria.',
            'id_compania.exists' => 'La compañía seleccionada no es válida.',
            'id_comunidad.required' => 'La comunidad es obligatoria.',
            'id_comunidad.exists' => 'La comunidad seleccionada no es válida.',
            'id_agente.exists' => 'El agente seleccionado no es válido.',
            'alias.min' => 'El alias debe tener al menos 2 caracteres.',
            'alias.max' => 'El alias no debe exceder los 255 carácteres.',
            'numero.required' => 'El número de póliza es obligatorio.',
            'numero.max' => 'El número de póliza no debe exceder los 20 carácteres.',
            'fecha_efecto.required' => 'La fecha de efecto es obligatoria.',
            'fecha_efecto.before_or_equal' => 'La fecha de efecto no puede ser mayor a hoy.',
            'cuenta.min' => 'La cuenta debe tener al menos 20 caracteres.',
            'cuenta.max' => 'La cuenta no debe execeder los 24 carácteres.',
            'forma_pago.required' => 'La forma de pago es obligatoria.',
            'prima_neta.required' => 'La prima neta es obligatoria.',
            'prima_neta.min' => 'La prima neta debe ser un número positivo.',
            'prima_total.required' => 'La prima total es obligatoria.',
            'prima_total.min' => 'La prima total debe ser un número positivo.',
            'pdf_poliza.mimes' => 'El archivo debe ser un PDF.',
            'pdf_poliza.max' => 'El archivo no debe exceder los 2MB.',
            'pdf_poliza.file' => 'El archivo no es válido.',
            'pdf_poliza.max' => 'El archivo no debe execer los 2MB.',
            'observaciones.max' => 'Las observaciones no deben exceder los 1000 carácteres.',
            'estado.required' => 'El estado es obligatorio.',
        ]);
        try {
            // Prepara los datos que se van a actualizar (sin incluir el campo pdf_poliza)
            $data = $request->except('pdf_poliza');

            // Si se subió un nuevo PDF, procesamos borrado del antiguo y guardado del nuevo
            if ($request->hasFile('pdf_poliza')) {
                // Borrar el PDF viejo (si existía)
                if ($poliza->pdf_poliza) {
                    // Obtenemos la ruta relativa en el disco privado
                    // (asumimos que el campo $poliza->pdf_poliza almacena algo como "polizas/archivo.pdf")
                    if (Storage::disk('private')->exists($poliza->pdf_poliza)) {
                        Storage::disk('private')->delete($poliza->pdf_poliza);
                    }
                }

                // Guardar el nuevo PDF en el disco "private" dentro de la carpeta "polizas"
                $archivo   = $request->file('pdf_poliza');
                $filename  = time() . '_' . $archivo->getClientOriginalName();
                $path      = $archivo->storeAs('polizas', $filename, 'private');
                // Almacenamos en la BD la ruta relativa (p.ej. "polizas/1652345678_documento.pdf")
                $data['pdf_poliza'] = $path;
            }

            // Actualiza el modelo con $data (que incluye o no el campo pdf_poliza)
            $poliza->update($data);
            Log::info('Póliza actualizada', ['poliza_id' => $poliza->id, 'user_id' => Auth::id()]);

            return redirect()->route('polizas.index')->with([
                'success' => [
                    'id' => uniqid(),
                    'mensaje' => "Póliza actualizada correctamente",
                ],
            ]);
        } catch (Throwable $e) {
            Log::error('Error al actualizar póliza: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->route('polizas.index')->with([
                'error' => [
                    'id' => uniqid(),
                    'mensaje' => "Error al actualizar la póliza",
                ],
            ]);
        }
    }

    /**
     * Elimina una póliza de la base de datos y su archivo PDF asociado.
     */
    public function destroy($id)
    {
        try {
            // Busca la póliza por ID o lanza una excepción si no se encuentra
            $poliza = Poliza::findOrFail($id);

            // Verifica autorización para eliminar la póliza
            $this->authorize('delete', $poliza);

            // Si existe un PDF asociado, lo borramos del disco privado
            if ($poliza->pdf_poliza) {
                if (Storage::disk('private')->exists($poliza->pdf_poliza)) {
                    Storage::disk('private')->delete($poliza->pdf_poliza);
                }
            }

            // Eliminamos el registro de la BD
            $poliza->delete();
            Log::info('Póliza eliminada', ['poliza_id' => $id, 'user_id' => Auth::id()]);

            return redirect()->route('polizas.index')->with([
                'success' => [
                    'id' => uniqid(),
                    'mensaje' => "Póliza eliminada correctamente",
                ],
            ]);
        } catch (Throwable $e) {
            Log::error('Error al eliminar póliza: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->route('polizas.index')->with([
                'error' => [
                    'id' => uniqid(),
                    'mensaje' => "Error al eliminar la póliza",
                ],
            ]);
        }
    }

    /**
     * Solicita la anulación de una póliza enviando un correo al propietario de la comunidad.
     */
    public function solicitarAnulacion(Poliza $poliza)
    {
        try {
            // Obtener el propietario de la comunidad
            $propietario = User::find($poliza->comunidad->id_propietario);

            if (!$propietario || !$propietario->email) {
                return redirect()->route('polizas.index')->with([
                    'error' => [
                        'id' => uniqid(),
                        'mensaje' => "El propietario no tiene un email asociado",
                    ],
                ]);
            }

            // Enviar el correo de solicitud de anulación al email del propietario
            Mail::to($propietario->email)->send(new SolicitudAnulacionPoliza($poliza));

            // Registrar la acción en los logs
            Log::info('Solicitud de anulación enviada', ['poliza_id' => $poliza->id, 'email' => $propietario->email]);

            return redirect()->route('polizas.index')->with([
                'success' => [
                    'id' => uniqid(),
                    'mensaje' => "Solicitud de anulación enviada correctamente para la póliza con la comunidad "
                        . $poliza->comunidad->nombre . ($poliza->numero ? " y número " . $poliza->numero : ", sin número"),
                ],
            ]);
        } catch (Throwable $e) {
            Log::error('Error al enviar solicitud de anulación: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->route('polizas.index')->with([
                'error' => [
                    'id' => uniqid(),
                    'mensaje' => "Error al enviar la solicitud de anulación para la póliza con la comunidad "
                        . $poliza->comunidad->nombre . ($poliza->numero ? " y número " . $poliza->numero : ", sin número"),
                ],
            ]);
        }
    }

    public function servePDF($id)
    {
        try {
            // Busca la póliza por ID o lanza una excepción si no se encuentra
            $poliza = Poliza::findOrFail($id);

            // Verifica autorización
            $this->authorize('view', $poliza);

            // Obtiene solo el nombre del archivo desde la URL
            $filename = basename($poliza->pdf_poliza);
            $path = storage_path("app/private/polizas/{$filename}");

            // Verifica si existe el archivo
            if (!file_exists($path)) {
                abort(404, 'Archivo no encontrado.');
            }

            Log::info('Sirviendo archivo PDF', ['poliza_id' => $id, 'file' => $filename]);

            // Retorna el archivo como respuesta de descarga
            // Response::file() permite servir archivos directamente desde el servidor
            // con el tipo de contenido adecuado y la opción de mostrar en línea
            // o descargar según el navegador del usuario.
            return Response::file($path, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . $filename . '"'
            ]);
        } catch (Throwable $e) {
            Log::error('Error al mostrar el archivo PDF: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->route('polizas.index')->with([
                'error' => [
                    'id' => uniqid(),
                    'mensaje' => "Error al mostrar el archivo PDF",
                ],
            ]);
        }
    }
}
