<?php

namespace App\Http\Controllers;

use App\Models\ChatPoliza;
use App\Models\Poliza;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatPolizaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($idPoliza)
    {
        $poliza = Poliza::with('chats.usuario')->findOrFail($idPoliza); // Obtener la póliza con sus chats y usuarios
        return view('chats_polizas.index', compact('poliza')); // Retornar la vista con los datos
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
    public function store(Request $request, $idPoliza)
    {
        $request->validate([
            'mensaje' => 'required|string|max:1000',
            'adjunto' => 'nullable|boolean',
        ]);

        $chat = ChatPoliza::create([
            'id_poliza' => $idPoliza,
            'id_usuario' => Auth::id(), // Usuario autenticado
            'mensaje' => $request->mensaje,
            'adjunto' => $request->adjunto ?? false,
        ]);

        $chat->load('usuario'); // Carga la relación usuario
        return response()->json(['success' => true, 'chat' => $chat]);
    }

    /**
     * Display the specified resource.
     */
    public function show($idPoliza, ChatPoliza $chatPoliza)
    {
        $chatPoliza->load('usuario', 'adjuntos'); // Cargar relaciones del chat
        return view('chats_polizas.show', compact('chatPoliza')); // Retornar la vista con los detalles del chat
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
    public function update(Request $request, $idPoliza, ChatPoliza $chatPoliza)
    {
        $request->validate([
            'mensaje' => 'required|string|max:1000',
            'adjunto' => 'nullable|boolean',
        ]);

        $chatPoliza->update([
            'mensaje' => $request->mensaje,
            'adjunto' => $request->adjunto ?? $chatPoliza->adjunto,
        ]);

        return redirect()->route('chats-polizas.index', $idPoliza)->with('success', 'Mensaje actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($idPoliza, ChatPoliza $chatPoliza)
    {
        $chatPoliza->delete(); // Eliminar el chat
        return redirect()->route('chats-polizas.index', $idPoliza)->with('success', 'Mensaje eliminado correctamente.');
    }
}
