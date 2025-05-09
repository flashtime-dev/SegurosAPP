<?php

namespace App\Http\Controllers;

use App\Models\Poliza;
use App\Models\Compania;
use App\Models\Comunidad;
use App\Models\Agente;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class PolizaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $polizas = Poliza::with(['compania', 'comunidad', 'agente'])->get();
        $companias = Compania::all();
        $comunidades = Comunidad::all();
        $agentes = Agente::all();
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
    public function create()
    {
        $companias = Compania::all();
        $comunidades = Comunidad::all();
        $agentes = Agente::all();

        return Inertia::render('polizas/create', compact('companias', 'comunidades', 'agentes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_compania' => 'required|exists:companias,id',
            'id_comunidad' => 'required|exists:comunidades,id',
            'id_agente' => 'nullable|exists:agentes,id',
            'numero' => 'required|string|max:20',
            'fecha_efecto' => 'required|date',
            'cuenta' => 'nullable|string|max:24',
            'forma_pago' => 'required|in:Bianual,Anual,Semestral,Trimestral,Mensual',
            'prima_neta' => 'required|numeric|min:0',
            'prima_total' => 'required|numeric|min:0',
            'pdf_poliza' => 'nullable|string|max:255',
            'observaciones' => 'nullable|string',
            'estado' => 'required|in:En Vigor,Anulada,Solicitada,Externa,Vencida',
        ]);

        Poliza::create($request->all());

        return redirect()->route('polizas.index')->with('success', 'Póliza creada correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $poliza = Poliza::with(['compania', 'comunidad', 'agente', 'chats.usuario'])->findOrFail($id);
        $chats = $poliza->chats()->with('usuario')->orderBy('created_at')->get();
        $authUser = Auth::id();

        return Inertia::render('polizas/poliza-id', [
            'poliza' => $poliza,
            'chats' => $chats,
            'authUser' => $authUser,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $poliza = Poliza::findOrFail($id);
        $companias = Compania::all();
        $comunidades = Comunidad::all();
        $agentes = Agente::all();

        return Inertia::render('polizas/edit', compact('poliza', 'companias', 'comunidades', 'agentes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'id_compania' => 'required|exists:companias,id',
            'id_comunidad' => 'required|exists:comunidades,id',
            'id_agente' => 'nullable|exists:agentes,id',
            'numero' => 'required|string|max:20',
            'fecha_efecto' => 'required|date',
            'cuenta' => 'nullable|string|max:24',
            'forma_pago' => 'required|in:Bianual,Anual,Semestral,Trimestral,Mensual',
            'prima_neta' => 'required|numeric|min:0',
            'prima_total' => 'required|numeric|min:0',
            'pdf_poliza' => 'nullable|string|max:255',
            'observaciones' => 'nullable|string',
            'estado' => 'required|in:En Vigor,Anulada,Solicitada,Externa,Vencida',
        ]);

        $poliza = Poliza::findOrFail($id);
        $poliza->update($request->all());

        return redirect()->route('polizas.index')->with('success', 'Póliza actualizada correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $poliza = Poliza::findOrFail($id);
        $poliza->delete();

        return redirect()->route('polizas.index')->with('success', 'Póliza eliminada correctamente.');
    }
}
