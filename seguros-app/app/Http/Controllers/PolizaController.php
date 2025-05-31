<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Poliza;
use App\Models\Compania;
use App\Models\Comunidad;
use App\Models\Agente;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use App\Http\Middleware\CheckPermiso;

use App\Mail\SolicitudAnulacionPoliza;
use Illuminate\Support\Facades\Mail;
use App\Models\User;

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
     * Display a listing of the resource.
     */
    public function index()
    {
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

        return Inertia::render('polizas/index', [
            'polizas' => $polizas,
            'companias' => $companias,
            'comunidades' => $comunidades,
            'agentes' => $agentes,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    // public function create()
    // {
    //     $companias = Compania::all();
    //     $comunidades = Comunidad::all();
    //     $agentes = Agente::all();

    //     return Inertia::render('polizas/create', compact('companias', 'comunidades', 'agentes'));
    // }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        // Capitalizar solo la primera palabra del alias antes de la validación
        $request->merge([
            'alias' => ucfirst(($request->alias))
        ]);

        // Validar los campos del formulario
        $request->validate([
            'id_compania' => 'required|exists:companias,id',
            'id_comunidad' => 'required|exists:comunidades,id',
            'id_agente' => 'nullable|exists:agentes,id',
            'alias' => 'nullable|string|min:2|max:255',
            'numero' => 'nullable|string|max:20',
            'fecha_efecto' => 'required|date',
            'cuenta' => 'nullable|string|max:24',
            'forma_pago' => 'required|in:Bianual,Anual,Semestral,Trimestral,Mensual',
            'prima_neta' => 'required|numeric|min:0',
            'prima_total' => 'required|numeric|min:0',
            'pdf_poliza' => 'nullable|file|mimes:pdf|max:2048', // Validar que sea un archivo PDF
            'observaciones' => 'nullable|string',
            'estado' => 'required|in:En Vigor,Anulada,Solicitada,Externa,Vencida',
        ], [
            'id_compania.required' => 'La compañía es obligatoria.',
            'id_comunidad.required' => 'La comunidad es obligatoria.',
            'alias.min' => 'El alias debe tener al menos 2 caracteres.',
            'cuenta.min' => 'La cuenta debe tener al menos 20 caracteres.',
            'forma_pago.required' => 'La forma de pago es obligatoria.',
            'pdf_poliza.mimes' => 'El archivo debe ser un PDF.',
            'pdf_poliza.max' => 'El archivo no debe exceder los 2MB.',
            'pdf_poliza.file' => 'El archivo no es válido.',
            'estado.required' => 'El estado es obligatorio.',
        ]);

        // Inicializar la variable para la URL del PDF
        $pdfUrl = null;

        // Verificar si se subió un archivo PDF
        if ($request->hasFile('pdf_poliza')) {
            $pdf_poliza = $request->file('pdf_poliza');

            // Intentar almacenar el archivo en el servidor
            $path = $pdf_poliza->storeAs('polizas', $pdf_poliza->getClientOriginalName(), 'public');

            if ($path) {
                // Si el almacenamiento es exitoso, generar la URL del archivo
                $pdfUrl = asset('storage/' . $path);
            }
        }

        // Crear la póliza con los datos del formulario y la URL del PDF
        Poliza::create(array_merge($request->all(), ['pdf_poliza' => $pdfUrl]));

        return redirect()->route('polizas.index')->with('success', 'Póliza creada correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

        
        $poliza = Poliza::with(['compania', 'comunidad', 'siniestros', 'agente', 'chats.usuario'])->findOrFail($id);

        $this->authorize('view', $poliza);
        
        $siniestros = $poliza->siniestros;
        $chats = $poliza->chats()->with('usuario')->orderBy('created_at')->get();
        $authUser = Auth::id();
        return Inertia::render('polizas/show', [
            'poliza' => $poliza,
            'siniestros' => $siniestros,
            'chats' => $chats,
            'authUser' => $authUser,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    // public function edit(string $id)
    // {
    //     $poliza = Poliza::findOrFail($id);
    //     $companias = Compania::all();
    //     $comunidades = Comunidad::all();
    //     $agentes = Agente::all();
    
    //     return Inertia::render('polizas/edit', compact('poliza', 'companias', 'comunidades', 'agentes'));
    // }
    
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $poliza = Poliza::findOrFail($id);
        $this->authorize('update', $poliza);

        // Capitalizar solo la primera palabra del alias antes de la validación
        $request->merge([
            'alias' => ucfirst(($request->alias))
        ]);
        
        $request->validate([
            'id_compania' => 'required|exists:companias,id',
            'id_comunidad' => 'required|exists:comunidades,id',
            'id_agente' => 'nullable|exists:agentes,id',
            'alias' => 'nullable|string|min:2|max:255',
            'numero' => 'nullable|string|max:20',
            'fecha_efecto' => 'required|date',
            'cuenta' => 'nullable|string|min:20|max:24',
            'forma_pago' => 'required|in:Bianual,Anual,Semestral,Trimestral,Mensual',
            'prima_neta' => 'required|numeric|min:0',
            'prima_total' => 'required|numeric|min:0',
            'pdf_poliza' => 'nullable|file|mimes:pdf|max:2048', // Validar que sea un archivo PDF
            'observaciones' => 'nullable|string',
            'estado' => 'required|in:En Vigor,Anulada,Solicitada,Externa,Vencida',
        ], [
            'id_compania.required' => 'La compañía es obligatoria.',
            'id_comunidad.required' => 'La comunidad es obligatoria.',
            'alias.min' => 'El alias debe tener al menos 2 caracteres.',
            'cuenta.min' => 'La cuenta debe tener al menos 20 caracteres.',
            'forma_pago.required' => 'La forma de pago es obligatoria.',
            'pdf_poliza.mimes' => 'El archivo debe ser un PDF.',
            'pdf_poliza.max' => 'El archivo no debe exceder los 2MB.',
            'pdf_poliza.file' => 'El archivo no es válido.',
            'estado.required' => 'El estado es obligatorio.',
        ]);

        
        $poliza->update($request->all());
        
        return redirect()->route('polizas.index')->with('success', 'Póliza actualizada correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $poliza = Poliza::findOrFail($id);
        $this->authorize('delete', $poliza);

        $poliza->delete();

        return redirect()->route('polizas.index')->with('success', 'Póliza eliminada correctamente.');
    }

    /**
     * Create a mail to anulate a policy
     */
    public function solicitarAnulacion(Poliza $poliza)
    {
        try {
            // Obtener el propietario de la comunidad
            $propietario = User::find($poliza->comunidad->id_propietario);

            if (!$propietario || !$propietario->email) {
                return redirect()->route('polizas.index')->with('error', 'El propietario no tiene un email asociado.');
            }

            // Enviar el correo de solicitud de anulación al email del propietario
            Mail::to($propietario->email)->send(new SolicitudAnulacionPoliza($poliza));

            return redirect()->route('polizas.index')->with('success', 'Solicitud de anulación enviada correctamente.');
        } catch (Exception $e) {
            return redirect()->route('polizas.index')->with('error', 'Error al enviar la solicitud de anulación: ' . $e->getMessage());
        }
    }
}
