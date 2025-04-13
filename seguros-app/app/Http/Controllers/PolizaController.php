<?php

namespace App\Http\Controllers;

use App\Models\Poliza;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PolizaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $polizas = Poliza::with(['compania', 'comunidad'])->get();

        return inertia('polizas/polizas', [
            'polizas' => $polizas,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Return a view to create a new policy
        return Inertia::render('polizas.create');
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
            'precio' => 'required|numeric|min:0',
            // Add other validation rules as needed
        ]);

        // Create a new policy
        Poliza::create($request->all());

        // Redirect to the policies index page with a success message
        return redirect()->route('polizas.index')->with('success', 'Poliza created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $poliza = Poliza::with([
            'compania',
            'comunidad',
            'chats.usuario' // cargamos los chats y su relación con usuario
        ])->findOrFail($id);

        return inertia('polizas/poliza', [
            'poliza' => $poliza,
            'chats' => $poliza->chats()->orderBy('created_at')->get(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Find the policy by ID
        $poliza = Poliza::findOrFail($id);

        // Return a view to edit the policy
        return Inertia::render('polizas.edit', compact('poliza'));
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
            'precio' => 'required|numeric|min:0',
            // Add other validation rules as needed
        ]);

        // Find the policy by ID and update it
        $poliza = Poliza::findOrFail($id);
        $poliza->update($request->all());

        // Redirect to the policies index page with a success message
        return redirect()->route('polizas.index')->with('success', 'Poliza updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Find the policy by ID
        $poliza = Poliza::findOrFail($id);

        // Delete the policy
        $poliza->delete();

        // Redirect to the policies index page with a success message
        return redirect()->route('polizas.index')->with('success', 'Poliza deleted successfully.');
    }
}
