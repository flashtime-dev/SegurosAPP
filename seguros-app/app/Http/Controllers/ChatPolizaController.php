<?php

namespace App\Http\Controllers;

use App\Models\ChatPoliza;
use App\Models\Poliza;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Events\MessageSent;
use Illuminate\Support\Facades\Log;

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
        Log::info("📤 Recibida petición de chat", [
            'poliza_id' => $idPoliza,
            'user_id' => Auth::id(),
            'mensaje' => $request->mensaje
        ]);

        $request->validate([
            'mensaje' => 'required|string|max:1000',
            'adjunto' => 'nullable|boolean',
        ]);

        $chat = ChatPoliza::create([
            'id_poliza' => $idPoliza,
            'id_usuario' => Auth::id(),
            'mensaje' => $request->mensaje,
            'adjunto' => $request->adjunto ?? false,
        ]);

        $chat->load('usuario');

        Log::info("💾 Chat creado", [
            'chat_id' => $chat->id,
            'chat_data' => $chat->toArray()
        ]);

        Log::info("📡 Enviando broadcast");
        broadcast(new MessageSent($chat))->toOthers();
        Log::info("📡 Broadcast enviado");

        return response()->json(['success' => true, 'chat' => $chat]);
    }
}