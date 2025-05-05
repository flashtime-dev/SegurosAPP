<?php

namespace App\Http\Controllers;

use App\Models\Siniestro;
use App\Models\Poliza;
use App\Models\Contacto;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SiniestroController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $siniestros = Siniestro::with('poliza', 'contactos')->get(); // Obtener todos los siniestros con sus relaciones
        return Inertia::render('siniestros/index', [
            'siniestros' => $siniestros,
        ]); // Retornar la vista con la lista de siniestros
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $polizas = Poliza::all(); // Obtener todas las pólizas
        $contactos = Contacto::all(); // Obtener todos los contactos
        return view('siniestros.create', compact('polizas', 'contactos')); // Retornar la vista para crear un nuevo siniestro
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_poliza' => 'required|exists:polizas,id',
            'declaracion' => 'required|string',
            'tramitador' => 'nullable|string|max:255',
            'expediente' => 'required|string|max:50',
            'exp_cia' => 'nullable|string|max:50',
            'exp_asist' => 'nullable|string|max:50',
            'fecha_ocurrencia' => 'nullable|date',
            'adjunto' => 'nullable|boolean',
            'contactos' => 'nullable|array',
            'contactos.*' => 'exists:contactos,id',
        ]);

        $siniestro = Siniestro::create($request->except('contactos')); // Crear un nuevo siniestro

        if ($request->has('contactos')) {
            $siniestro->contactos()->sync($request->contactos); // Asignar contactos al siniestro
        }

        return redirect()->route('siniestros.index')->with('success', 'Siniestro creado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Siniestro $siniestro)
    {
        $siniestro->load('poliza', 'contactos'); // Cargar las relaciones del siniestro
        return view('siniestros.show', compact('siniestro')); // Retornar la vista con los detalles del siniestro
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Siniestro $siniestro)
    {
        $polizas = Poliza::all(); // Obtener todas las pólizas
        $contactos = Contacto::all(); // Obtener todos los contactos
        return view('siniestros.edit', compact('siniestro', 'polizas', 'contactos')); // Retornar la vista para editar el siniestro
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Siniestro $siniestro)
    {
        $request->validate([
            'id_poliza' => 'required|exists:polizas,id',
            'declaracion' => 'required|string',
            'tramitador' => 'nullable|string|max:255',
            'expediente' => 'required|string|max:50',
            'exp_cia' => 'nullable|string|max:50',
            'exp_asist' => 'nullable|string|max:50',
            'fecha_ocurrencia' => 'nullable|date',
            'adjunto' => 'nullable|boolean',
            'contactos' => 'nullable|array',
            'contactos.*' => 'exists:contactos,id',
        ]);

        $siniestro->update($request->except('contactos')); // Actualizar el siniestro

        if ($request->has('contactos')) {
            $siniestro->contactos()->sync($request->contactos); // Actualizar contactos del siniestro
        }

        return redirect()->route('siniestros.index')->with('success', 'Siniestro actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Siniestro $siniestro)
    {
        $siniestro->contactos()->detach(); // Eliminar las relaciones con contactos
        $siniestro->delete(); // Eliminar el siniestro

        return redirect()->route('siniestros.index')->with('success', 'Siniestro eliminado correctamente.');
    }
}
