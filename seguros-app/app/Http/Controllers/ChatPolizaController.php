<?php

namespace App\Http\Controllers;

use App\Models\ChatPoliza;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Events\MessageSent;
use Illuminate\Support\Facades\Log;
use Throwable;
use App\Http\Middleware\CheckPermiso;

// Controlador para manejar los chats de pólizas
class ChatPolizaController extends Controller
{
    public function __construct()
    {
        // Aplica middleware de permisos a métodos específicos
        $this->middleware(CheckPermiso::class . ':chats_polizas.crear', ['only' => ['store']]);
    }
    
    /**
     * Método para almacenar un nuevo mensaje en el chat de una póliza.
     */
    public function store(Request $request, $idPoliza)
    {
        try {
            Log::info("📤 Recibida petición de chat", [
                'poliza_id' => $idPoliza,
                'user_id' => Auth::id(),
                'mensaje' => $request->mensaje
            ]);

            // Validar los datos del mensaje
            $request->validate([
                'mensaje' => 'required|string|max:1000',
                'adjunto' => 'nullable|boolean',
            ]);

            // Crear el mensaje del chat
            $chat = ChatPoliza::create([
                'id_poliza' => $idPoliza,
                'id_usuario' => Auth::id(),
                'mensaje' => $request->mensaje,
                'adjunto' => $request->adjunto ?? false,
            ]);

            // Cargar el usuario relacionado con el chat
            $chat->load('usuario');

            Log::info("💾 Chat creado", [
                'chat_id' => $chat->id,
                'chat_data' => $chat->toArray()
            ]);

            // Enviar el mensaje a otros usuarios conectados
            // Esto asume que tienes configurado broadcasting en tu aplicación
            // y que el evento MessageSent está correctamente configurado
            Log::info("📡 Enviando broadcast");
            broadcast(new MessageSent($chat))->toOthers();
            Log::info("📡 Broadcast enviado");

            return response()->json(['success' => true, 'message' => 'Mensaje enviado correctamente.','chat' => $chat]);
        } catch (Throwable $e) {
            Log::error('❌ Error al guardar el mensaje del chat:', [
                'exception' => $e,
                'poliza_id' => $idPoliza,
                'user_id' => Auth::id(),
                'mensaje' => $request->mensaje,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error al guardar el mensaje.'
            ], 500);
        }
    }
}