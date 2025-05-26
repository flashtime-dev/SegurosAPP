<?php

namespace App\Http\Controllers;

use App\Models\ChatPoliza;
use App\Models\Poliza;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


use Illuminate\Routing\Controller as BaseController;
use App\Http\Middleware\CheckPermiso;
class ChatPolizaController extends BaseController
{
    public function __construct()
    {
        // Aplica middleware de permisos a métodos específicos
        $this->middleware(CheckPermiso::class . ':chats_polizas.crear', ['only' => ['store']]);
    }
    
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

}
