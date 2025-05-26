<?php

namespace App\Http\Controllers;

use App\Models\ChatSiniestro;
use App\Models\Siniestro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Routing\Controller as BaseController;
use App\Http\Middleware\CheckPermiso;
class ChatSiniestroController extends BaseController
{
    public function __construct()
    {
        // Aplica middleware de permisos a métodos específicos
        $this->middleware(CheckPermiso::class . ':chat_siniestro.crear', ['only' => ['store']]);
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

        // Cargar el usuario para incluirlo en la respuesta
        
        $chat->load('usuario'); // Carga la relación usuario
        return response()->json(['success' => true, 'chat' => $chat]);
    }

}
