<?php

namespace App\Http\Controllers;

use App\Models\TelefonoCompania;
use App\Models\Compania;
use Illuminate\Http\Request;

class TelefonoCompaniaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $telefonos = TelefonoCompania::with('compania')->get(); // Obtener todos los teléfonos con su compañía
        return view('telefonos_companias.index', compact('telefonos')); // Retornar la vista con los datos
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $companias = Compania::all(); // Obtener todas las compañías
        return view('telefonos_companias.create', compact('companias')); // Retornar la vista para crear un nuevo teléfono
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_compania' => 'required|exists:companias,id',
            'telefono' => 'required|string|max:15',
            'descripcion' => 'required|string|max:255',
        ]);

        TelefonoCompania::create($request->all()); // Crear un nuevo teléfono

        return redirect()->route('telefonos_companias.index')->with('success', 'Teléfono creado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(TelefonoCompania $telefonoCompania)
    {
        $telefonoCompania->load('compania'); // Cargar la compañía del teléfono
        return view('telefonos_companias.show', compact('telefonoCompania')); // Retornar la vista con los detalles del teléfono
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TelefonoCompania $telefonoCompania)
    {
        $companias = Compania::all(); // Obtener todas las compañías
        return view('telefonos_companias.edit', compact('telefonoCompania', 'companias')); // Retornar la vista para editar el teléfono
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TelefonoCompania $telefonoCompania)
    {
        $request->validate([
            'id_compania' => 'required|exists:companias,id',
            'telefono' => 'required|string|max:15',
            'descripcion' => 'required|string|max:255',
        ]);

        $telefonoCompania->update($request->all()); // Actualizar el teléfono

        return redirect()->route('telefonos_companias.index')->with('success', 'Teléfono actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TelefonoCompania $telefonoCompania)
    {
        $telefonoCompania->delete(); // Eliminar el teléfono
        return redirect()->route('telefonos_companias.index')->with('success', 'Teléfono eliminado correctamente.');
    }
}
