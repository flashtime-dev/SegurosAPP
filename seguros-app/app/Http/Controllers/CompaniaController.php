<?php

namespace App\Http\Controllers;

use App\Models\Compania;
use Inertia\Inertia;
use Illuminate\Http\Request;

class CompaniaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch all companies from the database
        $companias = Compania::all();

        // Return a view with the companies
        return Inertia::render('companias.index', compact('companias'));
    }

    public function telefonosAsistencia()
    {
        // Obtener todas las compañías con sus teléfonos
        $companias = Compania::with('telefonos')->get();

        // Pasar los datos a la vista
        return inertia('telefonos-asistencia', [
            'companias' => $companias,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Return a view to create a new company
        return Inertia::render('companias.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:255',
            // Add other validation rules as needed
        ]);

        // Create a new company
        Compania::create($request->all());

        // Redirect to the companies index page with a success message
        return redirect()->route('companias.index')->with('success', 'Compañía creada con éxito.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Find the company by ID
        $compania = Compania::findOrFail($id);

        // Return a view with the company details
        return Inertia::render('companias.show', compact('compania'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Find the company by ID
        $compania = Compania::findOrFail($id);

        // Return a view to edit the company
        return Inertia::render('companias.edit', compact('compania'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validate the request data
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:255',
            // Add other validation rules as needed
        ]);

        // Find the company by ID and update it
        $compania = Compania::findOrFail($id);
        $compania->update($request->all());

        // Redirect to the companies index page with a success message
        return redirect()->route('companias.index')->with('success', 'Compañía actualizada con éxito.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Find the company by ID
        $compania = Compania::findOrFail($id);

        // Delete the company
        $compania->delete();

        // Redirect to the companies index page with a success message
        return redirect()->route('companias.index')->with('success', 'Compañía eliminada con éxito.');
    }
}
