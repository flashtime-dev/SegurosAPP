<?php

namespace App\Http\Controllers;

use App\Models\ChatSiniestro;
use App\Models\Siniestro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatSiniestroController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($idSiniestro)
    {
        $siniestro = Siniestro::with('chats.usuario')->findOrFail($idSiniestro); // Obtener el siniestro con sus chats y usuarios
        return view('chats_siniestros.index', compact('siniestro')); // Retornar la vista con los datos
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $idSiniestro)
    {
        $request->validate([
            'mensaje' => 'required|string|max:1000',
            'adjunto' => 'nullable|boolean',
        ]);

        $chat = ChatSiniestro::create([
            'id_siniestro' => $idSiniestro,
            'id_usuario' => Auth::id(), // Usuario autenticado
            'mensaje' => $request->mensaje,
            'adjunto' => $request->adjunto ?? false,
        ]);

        return redirect()->route('chats-siniestros.index', $idSiniestro)->with('success', 'Mensaje enviado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show($idSiniestro, ChatSiniestro $chatSiniestro)
    {
        $chatSiniestro->load('usuario', 'adjuntos'); // Cargar relaciones del chat
        return view('chats_siniestros.show', compact('chatSiniestro')); // Retornar la vista con los detalles del chat
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $idSiniestro, ChatSiniestro $chatSiniestro)
    {
        $request->validate([
            'mensaje' => 'required|string|max:1000',
            'adjunto' => 'nullable|boolean',
        ]);

        $chatSiniestro->update([
            'mensaje' => $request->mensaje,
            'adjunto' => $request->adjunto ?? $chatSiniestro->adjunto,
        ]);

        return redirect()->route('chats-siniestros.index', $idSiniestro)->with('success', 'Mensaje actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($idSiniestro, ChatSiniestro $chatSiniestro)
    {
        $chatSiniestro->delete(); // Eliminar el chat
        return redirect()->route('chats-siniestros.index', $idSiniestro)->with('success', 'Mensaje eliminado correctamente.');
    }
}
