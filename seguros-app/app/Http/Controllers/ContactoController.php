<?php

namespace App\Http\Controllers;

use App\Models\Contacto;
use App\Models\Comunidad;
use App\Models\Siniestro;
use Illuminate\Http\Request;

class ContactoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $contactos = Contacto::with('comunidad', 'siniestros')->get(); // Obtener todos los contactos con sus relaciones
        return view('contactos.index', compact('contactos')); // Retornar la vista con los datos
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $comunidades = Comunidad::all(); // Obtener todas las comunidades
        return view('contactos.create', compact('comunidades')); // Retornar la vista para crear un nuevo contacto
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_comunidad' => 'required|exists:comunidades,id',
            'nombre' => 'required|string|max:255',
            'cargo' => 'nullable|string|max:100',
            'piso' => 'nullable|string|max:50',
            'telefono' => 'required|string|max:15',
        ]);

        Contacto::create($request->all()); // Crear un nuevo contacto

        return redirect()->route('contactos.index')->with('success', 'Contacto creado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Contacto $contacto)
    {
        $contacto->load('comunidad', 'siniestros'); // Cargar las relaciones del contacto
        return view('contactos.show', compact('contacto')); // Retornar la vista con los detalles del contacto
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Contacto $contacto)
    {
        $comunidades = Comunidad::all(); // Obtener todas las comunidades
        return view('contactos.edit', compact('contacto', 'comunidades')); // Retornar la vista para editar el contacto
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Contacto $contacto)
    {
        $request->validate([
            'id_comunidad' => 'required|exists:comunidades,id',
            'nombre' => 'required|string|max:255',
            'cargo' => 'nullable|string|max:100',
            'piso' => 'nullable|string|max:50',
            'telefono' => 'required|string|max:15',
        ]);

        $contacto->update($request->all()); // Actualizar el contacto

        return redirect()->route('contactos.index')->with('success', 'Contacto actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contacto $contacto)
    {
        $contacto->siniestros()->detach(); // Eliminar las relaciones con siniestros
        $contacto->delete(); // Eliminar el contacto

        return redirect()->route('contactos.index')->with('success', 'Contacto eliminado correctamente.');
    }
}
